<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../Assets/php/config.php';

$conn = getDBConnection();

/**
 * Function to get existing households by sitio
 * @param mysqli $conn - Database connection
 * @param string $sitio - Sitio name
 * @return array - Array of households
 */
function getHouseholdsBySitio($conn, $sitio) {
    try {
        $stmt = $conn->prepare("
            SELECT h.id, h.household_number, 
                   CONCAT(r.first_name, ' ', IFNULL(r.middle_name, ''), ' ', r.last_name) as head_name
            FROM households h
            LEFT JOIN residents r ON h.id = r.household_id 
                AND r.relationship_to_household_head = 'HouseholdHead'
            WHERE h.sitio = ?
            ORDER BY h.household_number
        ");
        $stmt->bind_param("s", $sitio);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $households = [];
        while ($row = $result->fetch_assoc()) {
            $households[] = $row;
        }
        
        $stmt->close();
        return $households;
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Function to create new household
 * @param mysqli $conn - Database connection
 * @param string $sitio - Sitio name
 * @return int - Household ID
 */
function createNewHousehold($conn, $sitio) {
    try {
        // Generate household number based on sitio and current count
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM households WHERE sitio = ?");
        $stmt->bind_param("s", $sitio);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_assoc()['count'];
        $stmt->close();
        
        // Create household number format: SITIO-001, SITIO-002, etc.
        $householdNumber = strtoupper($sitio) . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        
        // Insert new household
        $stmt = $conn->prepare("INSERT INTO households (household_number, sitio, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
        $stmt->bind_param("ss", $householdNumber, $sitio);
        $stmt->execute();
        
        $householdId = $conn->insert_id;
        $stmt->close();
        
        return $householdId;
    } catch (Exception $e) {
        throw new Exception("Error creating household: " . $e->getMessage());
    }
}

/**
 * Function to insert resident data
 * @param mysqli $conn - Database connection
 * @param array $residentData - Array containing resident information
 * @return bool - Success status
 */
function insertResident($conn, $residentData) {
    try {
        // Start transaction
        $conn->begin_transaction();
        
        // Determine household ID
        if ($residentData['relationHHH'] === 'HouseholdHead') {
            // Create new household for household head
            $householdId = createNewHousehold($conn, $residentData['sitio']);
        } else {
            // Use selected household for family members
            if (empty($residentData['household_id'])) {
                throw new Exception("Please select a household for family members");
            }
            $householdId = $residentData['household_id'];
        }
        
        // Prepare resident data (using snake_case column names to match database)
        $sql = "INSERT INTO residents (
            household_id, 
            first_name, 
            middle_name, 
            last_name, 
            suffix, 
            birthday, 
            age, 
            gender, 
            civil_status, 
            phone_number, 
            citizenship, 
            place_of_birth, 
            occupation, 
            educational_attainment, 
            relationship_to_household_head, 
            fourps_beneficiary, 
            registered_voter, 
            land_status, 
            farm_input
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        // Prepare values for binding
        $middleName = $residentData['middleName'];
        $suffix = $residentData['suffix'];
        $phoneNumber = $residentData['phoneNumber'];
        $occupation = $residentData['occupation'];
        $educAttainment = $residentData['educAttainment'];
        $relationHHH = $residentData['relationHHH'];
        $fourPsBeneficiary = $residentData['fourPsBeneficiary'];
        $registeredVoter = $residentData['registeredVoter'];
        $landStatus = $residentData['landStatus'];
        $farmInput = $residentData['farmInput'];
        
        // Bind parameters - 19 parameters total
        $stmt->bind_param("issssssssssssssssss",
            $householdId,
            $residentData['firstName'],
            $middleName,
            $residentData['lastName'],
            $suffix,
            $residentData['birthday'],
            $residentData['age'],
            $residentData['gender'],
            $residentData['civilStatus'],
            $phoneNumber,
            $residentData['citizenship'],
            $residentData['placeOfBirth'],
            $occupation,
            $educAttainment,
            $relationHHH,
            $fourPsBeneficiary,
            $registeredVoter,
            $landStatus,
            $farmInput
        );
        
        // Execute
        $result = $stmt->execute();
        $stmt->close();
        
        // Commit transaction
        $conn->commit();
        
        return $result;
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        throw new Exception("Error inserting resident: " . $e->getMessage());
    }
}

/**
 * Function to handle form submission and save resident
 * This function should be called when the form is submitted
 */
function handleResidentFormSubmission($conn) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['ajax'])) {
        try {
            // Validate required fields
            $requiredFields = ['firstName', 'lastName', 'birthday', 'gender', 'civilStatus', 'citizenship', 'placeOfBirth', 'sitio'];
            
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("Required field '$field' is missing.");
                }
            }
            
            // Prepare resident data array
            $residentData = [
                'firstName' => trim($_POST['firstName']),
                'middleName' => !empty($_POST['middleName']) ? trim($_POST['middleName']) : null,
                'lastName' => trim($_POST['lastName']),
                'suffix' => !empty($_POST['suffix']) ? $_POST['suffix'] : null,
                'birthday' => $_POST['birthday'],
                'age' => intval($_POST['age']),
                'gender' => $_POST['gender'],
                'civilStatus' => $_POST['civilStatus'],
                'phoneNumber' => !empty($_POST['phoneNumber']) ? trim($_POST['phoneNumber']) : null,
                'citizenship' => trim($_POST['citizenship']),
                'placeOfBirth' => trim($_POST['placeOfBirth']),
                'occupation' => !empty($_POST['occupation']) ? trim($_POST['occupation']) : null,
                'educAttainment' => !empty($_POST['educAttainment']) ? $_POST['educAttainment'] : null,
                'sitio' => $_POST['sitio'],
                'relationHHH' => !empty($_POST['relationHHH']) ? $_POST['relationHHH'] : null,
                'fourPsBeneficiary' => !empty($_POST['fourPsBeneficiary']) ? $_POST['fourPsBeneficiary'] : null,
                'registeredVoter' => !empty($_POST['registeredVoter']) ? $_POST['registeredVoter'] : null,
                'landStatus' => !empty($_POST['landStatus']) ? trim($_POST['landStatus']) : null,
                'farmInput' => !empty($_POST['farmInput']) ? trim($_POST['farmInput']) : null,
                'household_id' => !empty($_POST['household_id']) ? intval($_POST['household_id']) : null
            ];
            
            // Insert resident
            $success = insertResident($conn, $residentData);
            
            if ($success) {
                $message = "Resident record has been successfully saved!";
                $success = true;
            } else {
                $message = "Failed to save resident record. Please try again.";
                $success = false;
            }
            
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
            $success = false;
        }
        
        // Set variables for the alert script in your HTML
        return ['success' => $success, 'message' => $message];
    }
    
    return null;
}

// Get households for dropdown (AJAX handler)
if (isset($_GET['ajax']) && $_GET['ajax'] === 'getHouseholds' && isset($_GET['sitio'])) {
    header('Content-Type: application/json');
    $households = getHouseholdsBySitio($conn, $_GET['sitio']);
    echo json_encode($households);
    exit;
}

// Handle form submission
$formResult = handleResidentFormSubmission($conn);

if ($formResult) {
    $success = $formResult['success'];
    $message = $formResult['message'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Barangay Dinganen Dashboard</title>
    <link rel="stylesheet" href="../Assets/Style/DropdownDesign/Dropdown.css"/>
    <link rel="stylesheet" href="../Assets/Style/Record_Resident.css" />
    <link rel="stylesheet" href="../Assets/Style/notification.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
    <!-- Notification Container -->
    <div class="notification-container" id="notificationContainer"></div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <div class="spinner"></div>
            <div class="loading-text">Saving resident record...</div>
        </div>
    </div>

    <?php if (isset($success)): ?>
        <script>
            // Show notification on page load if there's a form result
            document.addEventListener('DOMContentLoaded', function() {
                showNotification(
                    <?php echo $success ? 'true' : 'false'; ?>,
                    <?php echo json_encode($message); ?>
                );
                
                <?php if ($success): ?>
                    // Clear form after successful submission
                    setTimeout(function() {
                        document.getElementById('residentForm').reset();
                        document.getElementById('age').value = '';
                        document.getElementById('householdSelectionRow').style.display = 'none';
                        document.getElementById('household_id').removeAttribute('required');
                    }, 1000);
                <?php endif; ?>
            });
        </script>
    <?php endif; ?>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <img src="Picture1.png" class="sidebar-picture" >
            <div class="sidebar-title"><B>DinganenHub</B></div>
            <nav>
                <ul>
                    <li onclick="window.location.href='../AdminDashboard.html'" class=""><i class="fa fa-tachometer-alt"></i> Dashboard</li>
                    <li class="dropdown">
                        <i class="fa fa-file-alt"></i> 
                        <span>Residential Details</span>
                        <i class="fa fa-chevron-down dropdown-arrow"></i>
                        <ul class="dropdown-content">
                            <li class="active" onclick="window.location.href='Record_Resident.php'">
                                <a><i class="fa fa-address-card"></i> Record Residential Details</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-certificate"></i> Certificate</a>
                            </li>
                            <li onclick="window.location.href='ResidentRecord.php'">
                                <a><i class="fa fa-id-badge"></i> Resident Record</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-database"></i> Backup</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <i class="fa fa-user"></i> 
                        <span>Blotter Management</span>
                        <i class="fa fa-chevron-down dropdown-arrow"></i>
                        <ul class="dropdown-content">
                            <li onclick="window.location.href='../BlotterManagement/Record_blotter.php'">
                                <a><i class="fa fa-pencil-alt"></i> Record Blotter</a>
                            </li>
                            <li onclick="window.location.href='../BlotterManagement/Viewblotter.php'">
                                <a><i class="fa fa-folder-open"></i> View Blotter</a>
                            </li>
                            <li>
                                <a href=""><i class="fa fa-user-check"></i> Summon</a>
                            </li>
                            <li>
                                <a href=""><i class="fa fa-database"></i> Backup</a>
                            </li>
                        </ul>
                    </li>
                    
                    <li><i class="fa fa-bullhorn"></i> Announcement</li>
                    <li class="dropdown">
                        <i class="fa fa-user"></i> 
                        <span>Account Details</span>
                        <i class="fa fa-chevron-down dropdown-arrow"></i>
                        <ul class="dropdown-content">
                            <li onclick="window.location.href='../AccountDetails/AccountRegistration.php'">
                                <a><i class="fa fa-user-plus"></i> User Registration</a>
                            </li>
                            <li onclick="window.location.href='../AccountDetails/AccountView.php'">
                                <a><i class="fa fa-address-book"></i> Account List</a>
                            </li>
                            <li>
                                <a href=""><i class="fa fa-history"></i> Activity Logs</a>
                            </li>
                            <li>
                                <a href=""><i class="fa fa-database"></i> Backup</a>
                            </li>
                        </ul>
                    </li>
                    <li class="logout"><i class="fa fa-sign-out-alt"></i> Logout</li>
                </ul>

                <script>
                    // Logout function clears static credentials and redirects user
                    function logout() {
                    // Remove static admin credentials from localStorage (adjust key name if different)
                    localStorage.removeItem('adminCredentials');
                    // Redirect to login page (adjust path as per your app)
                    window.location.href = '../index.php';
                    }
                    // Attach click event to logout <li>
                    document.addEventListener('DOMContentLoaded', () => {
                    const logoutElement = document.querySelector('li.logout');
                    if (logoutElement) {
                        logoutElement.addEventListener('click', (event) => {
                        event.preventDefault();
                        logout();
                        });
                    }
                    });
                </script>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="view-container">
                <div class="blotter-header">
                    <h2><i class="fas fa-user-plus"></i> Registration of Residency</h2>
                    <p>Complete all required fields to create a new resident record</p>
                </div>

                <form class="blotter-form" id="residentForm" method="POST" action="Record_Resident.php" onsubmit="return handleFormSubmit(event)">
                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-user"></i> Personal Information</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name <span class="required">*</span></label>
                                <input type="text" id="firstName" name="firstName" placeholder="Enter first name" required>
                            </div>
                            <div class="form-group">
                                <label for="middleName">Middle Name</label>
                                <input type="text" id="middleName" name="middleName" placeholder="Enter middle name">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="lastName">Last Name <span class="required">*</span></label>
                                <input type="text" id="lastName" name="lastName" placeholder="Enter last name" required>
                            </div>
                            <div class="form-group">
                                <label for="suffix">Suffix</label>
                                <select id="suffix" name="suffix">
                                    <option value="">Select suffix</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="birthday">Date of Birth <span class="required">*</span></label>
                                <input type="date" id="birthday" name="birthday" required>
                            </div>
                            <div class="form-group">
                                <label for="age">Age</label>
                                <input type="number" id="age" name="age" placeholder="Auto-calculated" readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="gender">Gender <span class="required">*</span></label>
                                <select id="gender" name="gender" required>
                                    <option value="">Select gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Prefer not to say">Prefer not to say</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="civilStatus">Civil Status <span class="required">*</span></label>
                                <select id="civilStatus" name="civilStatus" required>
                                    <option value="">Select civil status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widow">Widow</option>
                                    <option value="Divorced">Divorced</option>
                                    <option value="Separated">Separated</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="phoneNumber">Phone Number</label>
                                <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Enter phone number" pattern="[0-9]{11}" title="Please enter 11-digit phone number">
                            </div>
                            <div class="form-group">
                                <label for="citizenship">Citizenship <span class="required">*</span></label>
                                <input type="text" id="citizenship" name="citizenship" placeholder="Enter citizenship" value="Filipino" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="placeOfBirth">Place of Birth <span class="required">*</span></label>
                                <input type="text" id="placeOfBirth" name="placeOfBirth" placeholder="Enter place of birth" required>
                            </div>
                            <div class="form-group">
                                <label for="occupation">Occupation</label>
                                <input type="text" id="occupation" name="occupation" placeholder="Enter occupation">
                            </div>
                        </div>

                        <div class="form-row single">
                            <div class="form-group">
                                <label for="educAttainment">Educational Attainment</label>
                                <select id="educAttainment" name="educAttainment">
                                    <option value="">Select educational attainment</option>
                                    <option value="Elementary">Elementary</option>
                                    <option value="High School">High School</option>
                                    <option value="Vocational">Vocational</option>
                                    <option value="College">College</option>
                                    <option value="Graduate Studies">Graduate Studies</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Household Information Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-home"></i> Household Information</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="sitio">Sitio <span class="required">*</span></label>
                                <select id="sitio" name="sitio" required>
                                    <option value="">Select sitio</option>
                                    <option value="CENTRAL">CENTRAL</option>
                                    <option value="DELATINA">DELATINA</option>
                                    <option value="CAMPO">CAMPO</option>
                                    <option value="AUXILLARY">AUXILLARY</option>
                                    <option value="UPPER">UPPER</option>
                                    <option value="LOWER">LOWER</option>
                                    <option value="LECY">LECY</option>
                                    <option value="TINALIAN">TINALIAN</option>
                                    <option value="RIVERSIDE">RIVERSIDE</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="relationHHH">Relationship to Household Head</label>
                                <select id="relationHHH" name="relationHHH">
                                    <option value="">Select relationship</option>
                                    <option value="HouseholdHead">Household Head</option>
                                    <option value="Spouse">Wife/Husband</option>
                                    <option value="Son">Son</option>
                                    <option value="Daughter">Daughter</option>
                                    <option value="Father">Father</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Other Relative">Other Relative</option>
                                </select>
                            </div>
                        </div>

                        <!-- Household selection for family members -->
                        <div class="form-row single" id="householdSelectionRow" style="display: none;">
                            <div class="form-group">
                                <label for="household_id">Select Household <span class="required">*</span></label>
                                <select id="household_id" name="household_id">
                                    <option value="">Select household</option>
                                </select>
                                <small class="form-text">Select the household this person belongs to</small>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-info-circle"></i> Additional Information</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="fourPsBeneficiary">4P's Beneficiary</label>
                                <select id="fourPsBeneficiary" name="fourPsBeneficiary">
                                    <option value="">Select option</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="registeredVoter">Registered Voter</label>
                                <select id="registeredVoter" name="registeredVoter">
                                    <option value="">Select option</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="landStatus">Land Status</label>
                                <input type="text" id="landStatus" name="landStatus" placeholder="e.g., Owner (2 hectares), Tenant">
                            </div>
                        </div>

                        <div class="form-row single">
                            <div class="form-group">
                                <label for="farmInput">Farm Input Details</label>
                                <input type="text" id="farmInput" name="farmInput" placeholder="e.g., Rice - irrigated, Corn - upland, high breed">
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                            <i class="fas fa-undo"></i> Reset Form
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Resident Record
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Notification function
        function showNotification(success, message) {
            const container = document.getElementById('notificationContainer');
            const notification = document.createElement('div');
            notification.className = `notification ${success ? 'success' : 'error'}`;
            
            const icon = success ? 'fa-check-circle' : 'fa-exclamation-circle';
            const title = success ? 'Success!' : 'Error!';
            
            notification.innerHTML = `
                <div class="notification-icon">
                    <i class="fas ${icon}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">${title}</div>
                    <div class="notification-message">${message}</div>
                </div>
                <button class="notification-close" onclick="closeNotification(this)">
                    <i class="fas fa-times"></i>
                </button>
                <div class="notification-progress">
                    <div class="notification-progress-bar"></div>
                </div>
            `;
            
            container.appendChild(notification);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.style.animation = 'slideIn 0.3s ease-out reverse';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }
        
        function closeNotification(button) {
            const notification = button.closest('.notification');
            notification.style.animation = 'slideIn 0.3s ease-out reverse';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }
        
        // Show loading overlay
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('active');
        }
        
        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('active');
        }
        
        // Handle form submission
        function handleFormSubmit(event) {
            // Show loading overlay
            showLoading();
            
            // Allow normal form submission
            return true;
        }
        
        // Calculate age when birthday changes
        document.getElementById('birthday').addEventListener('change', function() {
            const birthDate = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            document.getElementById('age').value = age >= 0 ? age : 0;
        });
        
        // Handle relationship change
        document.getElementById('relationHHH').addEventListener('change', function() {
            const householdRow = document.getElementById('householdSelectionRow');
            const householdSelect = document.getElementById('household_id');
            const sitio = document.getElementById('sitio').value;
            
            if (this.value !== 'HouseholdHead' && this.value !== '') {
                // Show household selection for family members
                householdRow.style.display = 'block';
                householdSelect.setAttribute('required', 'required');
                
                // Load households if sitio is selected
                if (sitio) {
                    loadHouseholds(sitio);
                }
            } else {
                // Hide household selection for household heads
                householdRow.style.display = 'none';
                householdSelect.removeAttribute('required');
                householdSelect.value = '';
            }
        });
        
        // Handle sitio change
        document.getElementById('sitio').addEventListener('change', function() {
            const relationship = document.getElementById('relationHHH').value;
            
            if (relationship !== 'HouseholdHead' && relationship !== '') {
                loadHouseholds(this.value);
            }
        });
        
        // Function to load households
        function loadHouseholds(sitio) {
            const householdSelect = document.getElementById('household_id');
            
            if (!sitio) {
                householdSelect.innerHTML = '<option value="">Select household</option>';
                return;
            }
            
            // Fetch households for the selected sitio
            fetch(`Record_Resident.php?ajax=getHouseholds&sitio=${encodeURIComponent(sitio)}`)
                .then(response => response.json())
                .then(households => {
                    householdSelect.innerHTML = '<option value="">Select household</option>';
                    
                    if (households.length === 0) {
                        householdSelect.innerHTML += '<option value="">No households found in this sitio</option>';
                    } else {
                        households.forEach(household => {
                            const headName = household.head_name || 'No head assigned';
                            householdSelect.innerHTML += `<option value="${household.id}">${household.household_number} - ${headName}</option>`;
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading households:', error);
                    householdSelect.innerHTML = '<option value="">Error loading households</option>';
                });
        }
        
        // Reset form function
        function resetForm() {
            if (confirm('Are you sure you want to reset the form?')) {
                document.getElementById('residentForm').reset();
                document.getElementById('age').value = '';
                document.getElementById('householdSelectionRow').style.display = 'none';
                document.getElementById('household_id').removeAttribute('required');
                showNotification(true, 'Form has been reset successfully!');
            }
        }
    </script>
    <script src="../Assets/JS/dropdown.js"></script>
    
</body>
</html>