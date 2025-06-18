<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Barangay Dinganen Dashboard</title>
    <link rel="stylesheet" href="../Assets/Style/DropdownDesign/SIDEBAR.css" />
    <link rel="stylesheet" href="../Assets/Style/DropdownDesign/Dropdown.css"/>
    <link rel="stylesheet" href="../Assets/Style/ResidentHouseholdView.css"/>
    <link rel="stylesheet" href="../Assets/Style/Edit/EditModal.css"/>
    <link rel="stylesheet" href="../Assets/Style/Notification/Notification.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
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
                            <li onclick="window.location.href='../ResidentData/Record_Resident.php'">
                                <a><i class="fa fa-address-card"></i> Record Residential Details</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-certificate"></i> Certificate</a>
                            </li>
                            <li onclick="window.location.href='../ResidentData/ResidentRecord.php'">
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
                            <li onclick="window.location.href='AccountRegistration.php'">
                                <a><i class="fa fa-user-plus"></i> User Registration</a>
                            </li>
                            <li onclick="window.location.href='AccountView.php'">
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
                    <li class="logout" ><i class="fa fa-sign-out-alt"></i> Logout</li>
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

        <!-- Content Area -->
        <main class="main-content">
            <!-- Resident Records Table -->
            <div class="view-container">
                <div class="blotter-header">
                    <h2 class="blotter-title">
                        <i class="fa fa-users"></i>
                        Resident Records
                    </h2>
                    <div class="search-container">
                        <input type="text" class="search-input" placeholder="Search resident records..." id="searchInputResident">
                        <button class="search-btn" onclick="searchRecords('resident')">
                            <i class="fa fa-search"></i> Search
                        </button>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="blotter-table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-user"></i> Full Name</th>
                                <th><i class="fa fa-birthday-cake"></i> Date of Birth</th>
                                <th><i class="fa fa-venus-mars"></i> Gender</th>
                                <th><i class="fa fa-heart"></i> Civil Status</th>
                                <th><i class="fa fa-phone"></i> Phone Number</th>
                                <th><i class="fa fa-graduation-cap"></i> Educational Attainment</th>
                                <th><i class="fa fa-map-marker-alt"></i> Sitio</th>
                                <th><i class="fa fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody id="residentTableBody">
                            <?php 
                            require_once '../Assets/php/Config.php';
                            $conn = getDBConnection();
                            
                            // Query the residents table
                            $sql = "SELECT * FROM residents ORDER BY id DESC";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    // Get the values with proper error handling
                                    $id = isset($row['id']) ? $row['id'] : 'N/A';
                                    
                                    // Build full name with suffix if available
                                    $firstName = isset($row['first_name']) ? $row['first_name'] : '';
                                    $middleName = isset($row['middle_name']) ? $row['middle_name'] : '';
                                    $lastName = isset($row['last_name']) ? $row['last_name'] : '';
                                    $suffix = isset($row['suffix']) ? $row['suffix'] : '';
                                    
                                    // Construct full name
                                    $fullName = trim($firstName . ' ' . $middleName . ' ' . $lastName);
                                    if (!empty($suffix)) {
                                        $fullName .= ' ' . $suffix;
                                    }
                                    
                                    // Get other fields
                                    $dateOfBirth = isset($row['date_of_birth']) ? $row['date_of_birth'] : '';
                                    $gender = isset($row['gender']) ? $row['gender'] : 'N/A';
                                    $civilStatus = isset($row['civil_status']) ? $row['civil_status'] : 'N/A';
                                    $phoneNumber = isset($row['phone_number']) ? $row['phone_number'] : 'N/A';
                                    $educationalAttainment = isset($row['educational_attainment']) ? $row['educational_attainment'] : 'N/A';
                                    $sitio = isset($row['sitio']) ? $row['sitio'] : 'N/A';
                                    
                                    echo "<tr>
                                        <td><strong>{$fullName}</strong></td>
                                        <td>" . ($dateOfBirth ? date('M d, Y', strtotime($dateOfBirth)) : 'N/A') . "</td>
                                        <td>{$gender}</td>
                                        <td>{$civilStatus}</td>
                                        <td>{$phoneNumber}</td>
                                        <td>{$educationalAttainment}</td>
                                        <td>{$sitio}</td>
                                        <td class='action-buttons'>
                                            <button onclick=\"viewRecord({$id}, 'resident')\" title=\"View Details\"><i class='fa fa-eye'></i></button>
                                            <button onclick=\"editRecord({$id}, 'resident')\" title=\"Edit Record\"><i class='fa fa-edit'></i></button>
                                            <button onclick=\"deleteRecord({$id}, 'resident')\" title=\"Delete Record\"><i class='fa fa-trash'></i></button>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' style='text-align:center; padding: 40px; color: #6b7280;'><i class='fa fa-info-circle' style='font-size: 24px; margin-bottom: 10px; display: block;'></i>No resident records found</td></tr>";
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Household Data Table -->
            <div class="view-container">
                <div class="blotter-header">
                    <h2 class="blotter-title">
                        <i class="fa fa-home"></i>
                        Household Data
                    </h2>
                    <div class="search-container">
                        <input type="text" class="search-input" placeholder="Search household records..." id="searchInputHousehold">
                        <button class="search-btn" onclick="searchRecords('household')">
                            <i class="fa fa-search"></i> Search
                        </button>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="blotter-table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag"></i> ID</th>
                                <th><i class="fa fa-home"></i> Household Number</th>
                                <th><i class="fa fa-user"></i> Full Name</th>
                                <th><i class="fa fa-users"></i> Relationship to Head</th>
                                <th><i class="fa fa-venus-mars"></i> Gender</th>
                                <th><i class="fa fa-briefcase"></i> Occupation</th>
                                <th><i class="fa fa-heart"></i> Civil Status</th>
                                <th><i class="fa fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody id="householdTableBody">
                            <?php 
                            require_once '../Assets/php/Config.php';
                            $conn = getDBConnection();
                            
                            // Query using LEFT JOIN to connect households and residents tables
                            // Using household_id in residents table to link to id in households table
                            $sql = "SELECT 
                                    r.id,
                                    h.household_number,
                                    r.first_name,
                                    r.middle_name,
                                    r.last_name,
                                    r.suffix,
                                    r.relationship_to_household_head,
                                    r.gender,
                                    r.occupation,
                                    r.civil_status,
                                    h.sitio
                                FROM residents r
                                LEFT JOIN households h ON r.household_id = h.id
                                WHERE r.household_id IS NOT NULL
                                ORDER BY h.household_number, r.last_name, r.first_name";
                                
                            $result = $conn->query($sql);
                            
                            // Debug: Check if query executed successfully
                            if (!$result) {
                                echo "<tr><td colspan='8' style='text-align:center; padding: 40px; color: #ef4444;'>
                                    <i class='fa fa-exclamation-triangle' style='font-size: 24px; margin-bottom: 10px; display: block;'></i>
                                    Database Error: " . $conn->error . "</td></tr>";
                            } else if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    // Get the values with proper error handling
                                    $id = isset($row['id']) ? $row['id'] : 'N/A';
                                    $householdNumber = isset($row['household_number']) ? $row['household_number'] : 'N/A';
                                    
                                    // Build full name with suffix if available
                                    $firstName = isset($row['first_name']) ? $row['first_name'] : '';
                                    $middleName = isset($row['middle_name']) ? $row['middle_name'] : '';
                                    $lastName = isset($row['last_name']) ? $row['last_name'] : '';
                                    $suffix = isset($row['suffix']) ? $row['suffix'] : '';
                                    
                                    // Construct full name
                                    $fullName = trim($firstName . ' ' . $middleName . ' ' . $lastName);
                                    if (!empty($suffix)) {
                                        $fullName .= ' ' . $suffix;
                                    }
                                    
                                    // If no name data found, show as "Not Assigned"
                                    if (empty(trim($fullName))) {
                                        $fullName = "Not Assigned";
                                    }
                                    
                                    // Get other fields - check for different possible column names
                                    $relationship = 'N/A';
                                    if (isset($row['relationship_to_household_head']) && !empty($row['relationship_to_household_head'])) {
                                        $relationship = $row['relationship_to_household_head'];
                                    } else if (isset($row['relationship_to_head']) && !empty($row['relationship_to_head'])) {
                                        $relationship = $row['relationship_to_head'];
                                    }
                                    
                                    $gender = isset($row['gender']) && !empty($row['gender']) ? $row['gender'] : 'N/A';
                                    $occupation = isset($row['occupation']) && !empty($row['occupation']) ? $row['occupation'] : 'N/A';
                                    $civilStatus = isset($row['civil_status']) && !empty($row['civil_status']) ? $row['civil_status'] : 'N/A';
                                    
                                    echo "<tr>
                                        <td><strong>#{$id}</strong></td>
                                        <td><span class='household-number'>{$householdNumber}</span></td>
                                        <td><strong>{$fullName}</strong></td>
                                        <td>{$relationship}</td>
                                        <td>{$gender}</td>
                                        <td>{$occupation}</td>
                                        <td>{$civilStatus}</td>
                                        <td class='action-buttons'>
                                            <button onclick=\"viewRecord({$id}, 'resident')\" title=\"View Details\"><i class='fa fa-eye'></i></button>
                                            <button onclick=\"editRecord({$id}, 'resident')\" title=\"Edit Record\"><i class='fa fa-edit'></i></button>
                                            <button onclick=\"deleteRecord({$id}, 'resident')\" title=\"Delete Record\"><i class='fa fa-trash'></i></button>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' style='text-align:center; padding: 40px; color: #6b7280;'>
                                    <i class='fa fa-info-circle' style='font-size: 24px; margin-bottom: 10px; display: block;'></i>
                                    No household records found. Please check if residents have been assigned to households.</td></tr>";
                            }
                            $conn->close();
                            ?>

                            <!-- Edit Modal - Add this before the closing </body> tag in your ResidentRecord.php -->
                            <div id="editModal" class="modal">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2><i class="fa fa-edit"></i> Edit Resident Information</h2>
                                        <span class="close" onclick="closeEditModal()">&times;</span>
                                    </div>
                                    
                                    <form id="editForm" class="edit-form">
                                        <input type="hidden" id="edit_id" name="id">
                                        
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label for="edit_first_name">First Name <span class="required">*</span></label>
                                                <input type="text" id="edit_first_name" name="first_name" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="edit_middle_name">Middle Name</label>
                                                <input type="text" id="edit_middle_name" name="middle_name">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="edit_last_name">Last Name <span class="required">*</span></label>
                                                <input type="text" id="edit_last_name" name="last_name" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="edit_suffix">Suffix</label>
                                                <select id="edit_suffix" name="suffix">
                                                    <option value="">None</option>
                                                    <option value="Jr.">Jr.</option>
                                                    <option value="Sr.">Sr.</option>
                                                    <option value="III">III</option>
                                                    <option value="IV">IV</option>
                                                    <option value="V">V</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label for="edit_date_of_birth">Date of Birth <span class="required">*</span></label>
                                                <input type="date" id="edit_date_of_birth" name="date_of_birth" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="edit_gender">Gender <span class="required">*</span></label>
                                                <select id="edit_gender" name="gender" required>
                                                    <option value="">Select Gender</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="edit_civil_status">Civil Status <span class="required">*</span></label>
                                                <select id="edit_civil_status" name="civil_status" required>
                                                    <option value="">Select Status</option>
                                                    <option value="Single">Single</option>
                                                    <option value="Married">Married</option>
                                                    <option value="Widowed">Widowed</option>
                                                    <option value="Separated">Separated</option>
                                                    <option value="Divorced">Divorced</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label for="edit_phone_number">Phone Number</label>
                                                <input type="tel" id="edit_phone_number" name="phone_number" placeholder="09XX-XXX-XXXX">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="edit_educational_attainment">Educational Attainment</label>
                                                <select id="edit_educational_attainment" name="educational_attainment">
                                                    <option value="">Select Education</option>
                                                    <option value="No Formal Education">No Formal Education</option>
                                                    <option value="Elementary">Elementary</option>
                                                    <option value="Elementary Graduate">Elementary Graduate</option>
                                                    <option value="High School">High School</option>
                                                    <option value="High School Graduate">High School Graduate</option>
                                                    <option value="College">College</option>
                                                    <option value="College Graduate">College Graduate</option>
                                                    <option value="Post Graduate">Post Graduate</option>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="edit_occupation">Occupation</label>
                                                <input type="text" id="edit_occupation" name="occupation">
                                            </div>
                                        </div>
                                        
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label for="edit_sitio">Sitio</label>
                                                <select id="edit_sitio" name="sitio">
                                                    <option value="">Select Sitio</option>
                                                    <option value="Sitio 1">Sitio 1</option>
                                                    <option value="Sitio 2">Sitio 2</option>
                                                    <option value="Sitio 3">Sitio 3</option>
                                                    <option value="Sitio 4">Sitio 4</option>
                                                    <option value="Sitio 5">Sitio 5</option>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="edit_household_id">Household Number</label>
                                                <input type="number" id="edit_household_id" name="household_id" placeholder="Enter household ID">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="edit_relationship_to_household_head">Relationship to Household Head</label>
                                                <select id="edit_relationship_to_household_head" name="relationship_to_household_head">
                                                    <option value="">Select Relationship</option>
                                                    <option value="Head">Head</option>
                                                    <option value="Spouse">Spouse</option>
                                                    <option value="Son">Son</option>
                                                    <option value="Daughter">Daughter</option>
                                                    <option value="Father">Father</option>
                                                    <option value="Mother">Mother</option>
                                                    <option value="Brother">Brother</option>
                                                    <option value="Sister">Sister</option>
                                                    <option value="Grandfather">Grandfather</option>
                                                    <option value="Grandmother">Grandmother</option>
                                                    <option value="Grandson">Grandson</option>
                                                    <option value="Granddaughter">Granddaughter</option>
                                                    <option value="Uncle">Uncle</option>
                                                    <option value="Aunt">Aunt</option>
                                                    <option value="Nephew">Nephew</option>
                                                    <option value="Niece">Niece</option>
                                                    <option value="Cousin">Cousin</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-actions">
                                            <button type="button" class="cancel-btn" onclick="closeEditModal()">
                                                <i class="fa fa-times"></i> Cancel
                                            </button>
                                            <button type="button" class="submit-btn" onclick="submitEditForm()">
                                                <i class="fa fa-save"></i> Update Resident
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Add this to your existing script.js or create a new file

        // Function to open edit modal and load resident data
        function editRecord(id, type) {
            if (type === 'resident') {
                // Show loading state
                showNotification('Loading resident data...', 'info');
                
                // Fetch resident data
                fetch(`..Assets/php/edit_resident.php?id=${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Populate the edit form
                            populateEditForm(data.data);
                            // Show the modal
                            document.getElementById('editModal').style.display = 'block';
                        } else {
                            showNotification(data.message || 'Failed to load resident data', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Error loading resident data', 'error');
                    });
            }
        }

        // Function to populate the edit form with resident data
        function populateEditForm(resident) {
            document.getElementById('edit_id').value = resident.id || '';
            document.getElementById('edit_first_name').value = resident.first_name || '';
            document.getElementById('edit_middle_name').value = resident.middle_name || '';
            document.getElementById('edit_last_name').value = resident.last_name || '';
            document.getElementById('edit_suffix').value = resident.suffix || '';
            document.getElementById('edit_date_of_birth').value = resident.date_of_birth || '';
            document.getElementById('edit_gender').value = resident.gender || '';
            document.getElementById('edit_civil_status').value = resident.civil_status || '';
            document.getElementById('edit_phone_number').value = resident.phone_number || '';
            document.getElementById('edit_educational_attainment').value = resident.educational_attainment || '';
            document.getElementById('edit_occupation').value = resident.occupation || '';
            document.getElementById('edit_sitio').value = resident.sitio || '';
            document.getElementById('edit_household_id').value = resident.household_id || '';
            document.getElementById('edit_relationship_to_household_head').value = resident.relationship_to_household_head || '';
        }

        // Function to submit the edit form
        function submitEditForm() {
            const form = document.getElementById('editForm');
            const formData = new FormData(form);
            
            // Show loading state
            const submitBtn = document.querySelector('.submit-btn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Updating...';
            submitBtn.disabled = true;
            
            fetch('../ResidentData/edit_resident.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Resident updated successfully!', 'success');
                    // Close modal
                    closeEditModal();
                    // Reload the page to show updated data
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showNotification(data.message || 'Failed to update resident', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error updating resident', 'error');
            })
            .finally(() => {
                // Restore button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }

        // Function to close the edit modal
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
            // Reset form
            document.getElementById('editForm').reset();
        }

        // Function to show notifications
        function showNotification(message, type = 'info') {
            // Create notification element if it doesn't exist
            let notification = document.getElementById('notification');
            if (!notification) {
                notification = document.createElement('div');
                notification.id = 'notification';
                document.body.appendChild(notification);
            }
            
            // Set notification class based on type
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <i class="fa ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
                <span>${message}</span>
            `;
            
            // Show notification
            notification.style.display = 'block';
            
            // Hide after 3 seconds
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }

        // Function to delete a record (bonus function)
        function deleteRecord(id, type) {
            if (confirm('Are you sure you want to delete this record?')) {
                fetch(`../ResidentData/delete_resident.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${id}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Record deleted successfully!', 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showNotification(data.message || 'Failed to delete record', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error deleting record', 'error');
                });
            }
        }

        // Function to view record details
        function viewRecord(id, type) {
            // Implement view functionality
            // For now, redirect to a view page or show in a modal
            window.location.href = `../ResidentData/view_resident.php?id=${id}`;
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeEditModal();
            }
        }
    </script>
    <script src="../Assets/JS/dropdown.js"></script>
    <script src="../Assets/JS/script.js"></script>
</body>
</html>