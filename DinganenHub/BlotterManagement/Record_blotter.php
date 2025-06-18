<?php 
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../Assets/php/Config.php';

// Initialize message variable
$message = '';
$messageType = '';

// Create a connection using the function from config.php
$conn = getDBConnection();

// Check the connection
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Set charset to handle special characters properly
$conn->set_charset("utf8");

if ($_POST) {
    // Sanitize and prepare data
    $what = mysqli_real_escape_string($conn, $_POST["what"]);
    $when_date = mysqli_real_escape_string($conn, $_POST["when"]); // Changed from "when"
    $where_location = mysqli_real_escape_string($conn, $_POST["where"]); // Changed from "where"
    $time_time = mysqli_real_escape_string($conn, $_POST["time"]); // This was already correct
    $suspectname = mysqli_real_escape_string($conn, $_POST["suspect"]);
    $suspectage = (int)$_POST["suspect-age"];
    $suspectgender = mysqli_real_escape_string($conn, $_POST["suspect-gender"]);
    $victimname = mysqli_real_escape_string($conn, $_POST["victim"]);
    $victimage = (int)$_POST["victim-age"];
    $victimgender = mysqli_real_escape_string($conn, $_POST["victim-gender"]);
    $residency = mysqli_real_escape_string($conn, $_POST["residency"]);
    $how = mysqli_real_escape_string($conn, $_POST["how"]);

    // Fixed SQL query - match column names exactly with your database
    $sql = "INSERT INTO blotter (
        `what`, 
        `when_date`, 
        `where_location`, 
        `time_time`, 
        `suspectname`, 
        `suspectage`, 
        `suspectgender`, 
        `victimname`, 
        `victimage`, 
        `victimgender`, 
        `Residency`, 
        `how`
    ) VALUES (
        '$what', 
        '$when_date', 
        '$where_location', 
        '$time_time', 
        '$suspectname', 
        '$suspectage', 
        '$suspectgender', 
        '$victimname', 
        '$victimage', 
        '$victimgender', 
        '$residency', 
        '$how'
    )";

    if($conn->query($sql) === TRUE) {
        $message = "Blotter Record has been saved successfully!";
        $messageType = "success";
    } else {
        $message = "Error: " . $conn->error;
        $messageType = "error";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Barangay Dinganen Dashboard</title>
    <link rel="stylesheet" href="../Assets/Style/Recordblotter.css" />
    <link rel="stylesheet" href="../Assets/Style/Notification/Notification.css" />
    <link rel="stylesheet" href="../Assets/Style/DropdownDesign/Dropdown.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    
</head>
<body>
    <!-- Notification Container -->
    <div class="notification-container" id="notificationContainer"></div>

    <div class="dashboard-container">
        <!-- sidebar.html -->
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
                            <li class="active" onclick="window.location.href='Record_blotter.php'">
                                <a><i class="fa fa-pencil-alt"></i> Record Blotter</a>
                            </li>
                            <li onclick="window.location.href='Viewblotter.php'">
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
     <!-- sidebar.html -->

      <!-- Content Area -->
        <main class="main-content">
            <!-- View Data -->
            <div class="view-container">
                <div class="blotter-header">
                    <h2><i class="fas fa-clipboard-list"></i> Blotter Record Entry</h2>
                    <p>Complete all required fields to create a new blotter record</p>
                </div>

                <form class="blotter-form" id="blotterForm" method="POST" onsubmit="handleFormSubmit(event)">
                    <!-- Incident Information Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-info-circle"></i> Incident Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="what">What (Incident Type) *</label>
                                <input type="text" id="what" name="what" list="incident-types" placeholder="Enter or select incident type" required>
                                <datalist id="incident-types">
                                    <option value="Theft">
                                    <option value="Assault">
                                    <option value="Domestic Violence">
                                    <option value="Vandalism">
                                    <option value="Noise Complaint">
                                    <option value="Trespassing">
                                    <option value="Fraud">
                                    <option value="Public Disturbance">
                                    <option value="Property Damage">
                                    <option value="Harassment">
                                    <option value="Drug-related Incident">
                                    <option value="Traffic Violation">
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label for="where">Where (Location) *</label>
                                <input type="text" id="where" name="where" placeholder="Enter incident location" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="when">When (Date) *</label>
                                <input type="date" id="when" name="when" required>
                            </div>
                            <div class="form-group">
                                <label for="time">Time *</label>
                                <input type="time" id="time" name="time" required>
                            </div>
                        </div>
                    </div>

                    <!-- Suspect Information Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-user-secret"></i> Suspect Information</h3>
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="suspect">Suspect Name</label>
                                <input type="text" id="suspect" name="suspect" placeholder="Enter suspect's full name">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="suspect-age">Suspect Age</label>
                                <input type="number" id="suspect-age" name="suspect-age" placeholder="Age" min="1" max="120">
                            </div>
                            <div class="form-group">
                                <label for="suspect-gender">Suspect Gender</label>
                                <select id="suspect-gender" name="suspect-gender">
                                    <option value="">Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                    <option value="prefer-not-to-say">Prefer not to say</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Victim Information Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-user-injured"></i> Victim Information</h3>
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="victim">Victim Name *</label>
                                <input type="text" id="victim" name="victim" placeholder="Enter victim's full name" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="victim-age">Victim Age *</label>
                                <input type="number" id="victim-age" name="victim-age" placeholder="Age" min="1" max="120" required>
                            </div>
                            <div class="form-group">
                                <label for="victim-gender">Victim Gender *</label>
                                <select id="victim-gender" name="victim-gender" required>
                                    <option value="">Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                    <option value="prefer-not-to-say">Prefer not to say</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="residency">Residency *</label>
                                <input type="text" id="residency" name="residency" placeholder="Enter complete address" required>
                            </div>
                        </div>
                    </div>

                    <!-- Incident Details Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-file-alt"></i> Incident Details</h3>
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="how">How (Detailed Description) *</label>
                                <textarea id="how" name="how" rows="6" placeholder="Provide a detailed description of how the incident occurred, including circumstances, sequence of events, and any other relevant information..." required></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                            <i class="fas fa-undo"></i> Reset Form
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Blotter Record
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="../Assets/JS/dropdown.js"></script>
    
    <script>
        // Notification System
        function showNotification(type, title, message, duration = 5000) {
            const container = document.getElementById('notificationContainer');
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            
            // Set icon based on type
            let icon = '';
            switch(type) {
                case 'success':
                    icon = 'fa-check-circle';
                    break;
                case 'error':
                    icon = 'fa-times-circle';
                    break;
                case 'info':
                    icon = 'fa-info-circle';
                    break;
            }
            
            // Build notification HTML
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
                <div class="progress-bar"></div>
            `;
            
            // Add to container
            container.appendChild(notification);
            
            // Auto remove after duration
            setTimeout(() => {
                removeNotification(notification);
            }, duration);
        }
        
        function closeNotification(button) {
            const notification = button.closest('.notification');
            removeNotification(notification);
        }
        
        function removeNotification(notification) {
            if (!notification.classList.contains('removing')) {
                notification.classList.add('removing');
                setTimeout(() => {
                    notification.remove();
                }, 400);
            }
        }

        // Form submission handler
        function handleFormSubmit(event) {
            // Show processing notification immediately when form is submitted
            showNotification('info', 'Processing...', 'Saving blotter record...', 3000);
            
            // Allow the form to submit normally
            // The PHP will handle the actual submission
        }

        // Reset form function
        function resetForm() {
            document.getElementById('blotterForm').reset();
            showNotification('info', 'Form Reset', 'All fields have been cleared.', 2000);
        }

        // Show notification from PHP response
        <?php if (!empty($message)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            showNotification(
                '<?php echo $messageType; ?>',
                '<?php echo $messageType === "success" ? "Success!" : "Error!"; ?>',
                '<?php echo addslashes($message); ?>'
            );
            
            <?php if ($messageType === "success"): ?>
            // Optional: Reset form after successful submission
            setTimeout(function() {
                document.getElementById('blotterForm').reset();
            }, 1500);
            <?php endif; ?>
        });
        <?php endif; ?>
    </script>
</body>
</html>