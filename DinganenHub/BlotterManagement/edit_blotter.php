<?php
require_once '../Assets/php/Config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'update' && isset($_POST['id'])) {
    $conn = getDBConnection();
    
    $id = (int)$_POST['id'];
    $what = mysqli_real_escape_string($conn, $_POST['what']);
    $where = mysqli_real_escape_string($conn, $_POST['where']);
    $when = mysqli_real_escape_string($conn, $_POST['when']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $suspectname = mysqli_real_escape_string($conn, $_POST['suspectname']);
    $suspectage = (int)$_POST['suspectage'];
    $suspectgender = mysqli_real_escape_string($conn, $_POST['suspectgender']);
    $victimname = mysqli_real_escape_string($conn, $_POST['victimname']);
    $victimage = (int)$_POST['victimage'];
    $victimgender = mysqli_real_escape_string($conn, $_POST['victimgender']);
    $residency = mysqli_real_escape_string($conn, $_POST['residency']);
    $how = mysqli_real_escape_string($conn, $_POST['how']);
    
    $sql = "UPDATE blotter SET 
            what = '$what',
            where_location = '$where',
            when_date = '$when',
            time_time = '$time',
            suspectname = '$suspectname',
            suspectage = $suspectage,
            suspectgender = '$suspectgender',
            victimname = '$victimname',
            victimage = $victimage,
            victimgender = '$victimgender',
            Residency = '$residency',
            how = '$how'
            WHERE Id = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Record updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating record: ' . $conn->error]);
    }
    $conn->close();
    exit;
}

// Fetch record for editing
if (isset($_GET['id'])) {
    $conn = getDBConnection();
    $id = (int)$_GET['id'];
    
    $sql = "SELECT * FROM blotter WHERE Id = $id";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $record = $result->fetch_assoc();
        
        // Debug: Check what columns are actually available
        // Uncomment the line below to see the actual column names
        // echo "<pre>Available columns: " . print_r(array_keys($record), true) . "</pre>";
        
    } else {
        header('Location: Viewblotter.php');
        exit;
    }
    $conn->close();
} else {
    header('Location: Viewblotter.php');
    exit;
}

// Function to safely get field value
function getFieldValue($record, $fieldName, $defaultValue = '') {
    return isset($record[$fieldName]) && $record[$fieldName] !== null ? $record[$fieldName] : $defaultValue;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Blotter Record - Barangay Dinganen Dashboard</title>
    <link rel="stylesheet" href="../Assets/Style/Viewblotter.css" />
    <link rel="stylesheet" href="../Assets/Style/DropdownDesign/Dropdown.css"/>
    <link rel="stylesheet" href="../Assets/Style/Edit/edit_blotter.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <img src="Picture1.png" class="sidebar-picture" alt="Barangay Logo">
            <div class="sidebar-title"><b>DinganenHub</b></div>
            <nav>
                <ul>
                    <li onclick="window.location.href='../AdminDashboard.html'">
                        <i class="fa fa-tachometer-alt"></i> Dashboard
                    </li>
                    
                    <!-- Residential Details Dropdown -->
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
                    
                    <!-- Blotter Management Dropdown -->
                    <li class="dropdown active">
                        <i class="fa fa-user"></i> 
                        <span>Blotter Management</span>
                        <i class="fa fa-chevron-down dropdown-arrow"></i>
                        <ul class="dropdown-content">
                            <li onclick="window.location.href='Record_blotter.php'">
                                <a><i class="fa fa-pencil-alt"></i> Record Blotter</a>
                            </li>
                            <li class="active" onclick="window.location.href='Viewblotter.php'">
                                <a><i class="fa fa-folder-open"></i> View Blotter</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-user-check"></i> Summon</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-database"></i> Backup</a>
                            </li>
                        </ul>
                    </li>
                    
                    <li><i class="fa fa-bullhorn"></i> Announcement</li>
                    
                    <!-- Account Details Dropdown -->
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
                                <a href="#"><i class="fa fa-history"></i> Activity Logs</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-database"></i> Backup</a>
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

        <!-- Content Area -->
        <main class="main-content">
            <div class="edit-page-container">
                <div class="edit-page-header">
                    <h2><i class="fa fa-edit"></i> Edit Blotter Record #<?php echo getFieldValue($record, 'Id'); ?></h2>
                    <button class="back-btn" onclick="window.location.href='../BlotterManagement/Viewblotter.php'">
                        <i class="fa fa-arrow-left"></i> Back to Records
                    </button>
                </div>

                <form id="editBlotterForm" class="edit-form">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?php echo getFieldValue($record, 'Id'); ?>">

                    <div class="form-grid">
                        <!-- Incident Details Section -->
                        <div class="form-section">
                            <h3><i class="fa fa-exclamation-triangle"></i> Incident Details</h3>
                            
                            <div class="form-group">
                                <label for="what">
                                    <i class="fa fa-exclamation-triangle"></i> Incident Type
                                </label>
                                <input type="text" id="what" name="what" value="<?php echo htmlspecialchars(getFieldValue($record, 'what')); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="where">
                                    <i class="fa fa-map-marker-alt"></i> Location
                                </label>
                                <input type="text" id="where" name="where" value="<?php echo htmlspecialchars(getFieldValue($record, 'where', getFieldValue($record, 'where_location'))); ?>" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="when">
                                        <i class="fa fa-calendar"></i> Date
                                    </label>
                                    <input type="date" id="when" name="when" value="<?php echo getFieldValue($record, 'when', getFieldValue($record, 'when_date')); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="time">
                                        <i class="fa fa-clock"></i> Time
                                    </label>
                                    <input type="time" id="time" name="time" value="<?php echo getFieldValue($record, 'time', getFieldValue($record, 'time_time')); ?>" required>
                                </div>
                            </div>
                        </div>

                        <!-- Suspect Details Section -->
                        <div class="form-section">
                            <h3><i class="fa fa-user-secret"></i> Suspect Information</h3>
                            
                            <div class="form-group">
                                <label for="suspectname">
                                    <i class="fa fa-user"></i> Suspect Name
                                </label>
                                <input type="text" id="suspectname" name="suspectname" value="<?php echo htmlspecialchars(getFieldValue($record, 'suspectname')); ?>">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="suspectage">
                                        <i class="fa fa-birthday-cake"></i> Age
                                    </label>
                                    <input type="number" id="suspectage" name="suspectage" value="<?php echo getFieldValue($record, 'suspectage'); ?>" min="1" max="120">
                                </div>

                                <div class="form-group">
                                    <label for="suspectgender">
                                        <i class="fa fa-venus-mars"></i> Gender
                                    </label>
                                    <select id="suspectgender" name="suspectgender">
                                        <option value="">Select Gender</option>
                                        <option value="Male" <?php echo (getFieldValue($record, 'suspectgender') === 'Male') ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo (getFieldValue($record, 'suspectgender') === 'Female') ? 'selected' : ''; ?>>Female</option>
                                        <option value="Other" <?php echo (getFieldValue($record, 'suspectgender') === 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Victim Details Section -->
                        <div class="form-section">
                            <h3><i class="fa fa-user-injured"></i> Victim Information</h3>
                            
                            <div class="form-group">
                                <label for="victimname">
                                    <i class="fa fa-user"></i> Victim Name
                                </label>
                                <input type="text" id="victimname" name="victimname" value="<?php echo htmlspecialchars(getFieldValue($record, 'victimname')); ?>">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="victimage">
                                        <i class="fa fa-birthday-cake"></i> Age
                                    </label>
                                    <input type="number" id="victimage" name="victimage" value="<?php echo getFieldValue($record, 'victimage'); ?>" min="1" max="120">
                                </div>

                                <div class="form-group">
                                    <label for="victimgender">
                                        <i class="fa fa-venus-mars"></i> Gender
                                    </label>
                                    <select id="victimgender" name="victimgender">
                                        <option value="">Select Gender</option>
                                        <option value="Male" <?php echo (getFieldValue($record, 'victimgender') === 'Male') ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo (getFieldValue($record, 'victimgender') === 'Female') ? 'selected' : ''; ?>>Female</option>
                                        <option value="Other" <?php echo (getFieldValue($record, 'victimgender') === 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="residency">
                                    <i class="fa fa-home"></i> Residency
                                </label>
                                <input type="text" id="residency" name="residency" value="<?php echo htmlspecialchars(getFieldValue($record, 'Residency', getFieldValue($record, 'residency'))); ?>">
                            </div>
                        </div>

                        <!-- Additional Details Section -->
                        <div class="form-section full-width">
                            <h3><i class="fa fa-info-circle"></i> Additional Details</h3>
                            
                            <div class="form-group">
                                <label for="how">
                                    <i class="fa fa-file-text"></i> Description/Details
                                </label>
                                <textarea id="how" name="how" rows="6" placeholder="Provide detailed description of the incident..."><?php echo htmlspecialchars(getFieldValue($record, 'how')); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="window.location.href='Viewblotter.php'">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn-update">
                            <i class="fa fa-save"></i> Update Record
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- Success/Error Messages -->
    <div id="messageContainer" class="message-container" style="display: none;">
        <div id="messageContent" class="message-content">
            <i id="messageIcon" class="fa"></i>
            <span id="messageText"></span>
        </div>
    </div>

    <script src="../Assets/JS/dropdown.js"></script>
    <script src="../Assets/JS/script.js"></script>
    <script>
        document.getElementById('editBlotterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Show loading state
            const submitBtn = document.querySelector('.btn-update');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Updating...';
            submitBtn.disabled = true;
            
            fetch('edit_blotter.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showMessage(data.success, data.message);
                
                if (data.success) {
                    setTimeout(() => {
                        window.location.href = 'Viewblotter.php';
                    }, 2000);
                }
            })
            .catch(error => {
                showMessage(false, 'An error occurred while updating the record.');
                console.error('Error:', error);
            })
            .finally(() => {
                // Reset button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });

        function showMessage(isSuccess, message) {
            const container = document.getElementById('messageContainer');
            const content = document.getElementById('messageContent');
            const icon = document.getElementById('messageIcon');
            const text = document.getElementById('messageText');
            
            if (isSuccess) {
                content.className = 'message-content success';
                icon.className = 'fa fa-check-circle';
            } else {
                content.className = 'message-content error';
                icon.className = 'fa fa-exclamation-circle';
            }
            
            text.textContent = message;
            container.style.display = 'block';
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                container.style.display = 'none';
            }, 5000);
        }

        // Form validation
        document.querySelectorAll('input[required]').forEach(input => {
            input.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.classList.add('error');
                } else {
                    this.classList.remove('error');
                }
            });
        });

        // Debug: Show what data is available (remove this in production)
        console.log('Record data loaded:', <?php echo json_encode($record); ?>);
    </script>
</body>
</html>