<?php 
require_once '../Assets/php/Config.php';

// Create a connection using the function from config.php
$conn = getDBConnection();

// Check the connection
if ($conn->connect_error) {
    die("Connection Failed:" . $conn->connect_error);
}

$message = ""; // Initialize message variable
$messageType = ""; // Initialize message type

// Handle form submission
if ($_POST) {
    // Sanitize input data
    $Fullname = mysqli_real_escape_string($conn, $_POST["fullname"]);
    $Birthday = mysqli_real_escape_string($conn, $_POST["birthday"]);
    $Age = (int)$_POST["age"]; // Cast to integer for age
    $Username = mysqli_real_escape_string($conn, $_POST["username"]);
    
    // Hash the password for security
    $Password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $Role = mysqli_real_escape_string($conn, $_POST["role"]);

    // Check if username already exists
    $check_username = "SELECT Username FROM users WHERE Username = '$Username'";
    $result = $conn->query($check_username);
    
    if ($result->num_rows > 0) {
        $message = "Username already exists! Please choose a different username.";
        $messageType = "error";
    } else {
        // Insert query
        $sql = "INSERT INTO users (`Fullname`, `Birthday`, `Age`, `Username`, `Password`, `Role`) 
            VALUES ('$Fullname', '$Birthday', $Age, '$Username', '$Password', '$Role')";

        if($conn->query($sql) === TRUE) {
            $message = "User registered successfully! Account has been created.";
            $messageType = "success";
        } else {
            $message = "Error: " . $conn->error;
            $messageType = "error";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Barangay Dinganen Dashboard - User Registration</title>
    <link rel="stylesheet" href="../Assets/Style/Registration.css" />
    <link rel="stylesheet" href="../Assets/Style/DropdownDesign/Dropdown.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>

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
                            <li class="active" onclick="window.location.href='AccountRegistration.php'">
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
        <main class="main-content" id="mainContent">
            <div class="content-wrapper">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="header-content">
                        <h1><i class="fas fa-user-plus"></i>User Registration</h1>
                        <p class="header-subtitle">Create and manage user accounts for the Barangay system</p>
                    </div>
                </div>

                <!-- Registration Card -->
                <div class="registration-card">
                    <form id="registrationForm" method="POST" action="" class="registration-form">
                        <!-- Personal Information Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="section-title">
                                    <h3>Personal Information</h3>
                                </div>
                            </div>
                            
                            <div class="form-grid">
                                <div class="form-group full-width">
                                    <label for="fullname">
                                        <i class="fas fa-id-card"></i>
                                        Full Name
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text" id="fullname" name="fullname" placeholder="Enter complete full name" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="birthday">
                                        <i class="fas fa-calendar-alt"></i>
                                        Date of Birth
                                        <span class="required">*</span>
                                    </label>
                                    <input type="date" id="birthday" name="birthday" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="age">
                                        <i class="fas fa-birthday-cake"></i>
                                        Age
                                    </label>
                                    <input type="number" id="age" name="age" min="18" max="100" placeholder="Auto-calculated" readonly>
                                    <small class="help-text">Minimum age requirement is 18 years</small>
                                </div>
                            </div>
                        </div>

                        <!-- Account Information Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-key"></i>
                                </div>
                                <div class="section-title">
                                    <h3>Account Information</h3>
                                </div>
                            </div>
                            
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="username">
                                        <i class="fas fa-user-circle"></i>
                                        Username
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text" id="username" name="username" placeholder="Enter unique username" required>
                                    <small class="help-text">Only letters, numbers, and underscores allowed</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="role">
                                        <i class="fas fa-id-badge"></i>
                                        Role
                                        <span class="required">*</span>
                                    </label>
                                    <select id="role" name="role" required>
                                        <option value="">Select user role</option>
                                        <option value="staff">Staff</option>
                                    </select>
                                </div>
                                
                                <div class="form-group full-width">
                                    <label for="password">
                                        <i class="fas fa-lock"></i>
                                        Password
                                        <span class="required">*</span>
                                    </label>
                                    <div class="password-field">
                                        <input type="password" id="password" name="password" placeholder="Create secure password" required minlength="6">
                                        <button type="button" class="password-toggle" onclick="togglePassword()">
                                            <i class="fas fa-eye" id="passwordToggleIcon"></i>
                                        </button>
                                    </div>
                                    <small class="help-text">Password must be at least 6 characters long</small>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                <i class="fas fa-redo"></i>
                                Reset Form
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Register User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Notification Container -->
    <div id="notificationContainer" class="notification-container"></div>
    <script src="../Assets/JS/dropdown.js"></script>

    <script>
        // Mobile menu toggle functionality
        document.getElementById('mobileMenuToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('show');
            mainContent.classList.toggle('expanded');
        });

        // Dropdown functionality
        document.querySelectorAll('.dropdown .nav-item').forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                const dropdown = this.parentElement;
                const isActive = dropdown.classList.contains('show');
                
                // Close all other dropdowns
                document.querySelectorAll('.dropdown').forEach(otherDropdown => {
                    if (otherDropdown !== dropdown) {
                        otherDropdown.classList.remove('show');
                    }
                });
                
                // Toggle current dropdown
                dropdown.classList.toggle('show', !isActive);
            });
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('mobileMenuToggle');
            
            if (window.innerWidth <= 1024 && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target) && 
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                document.getElementById('mainContent').classList.remove('expanded');
            }
        });

        // Auto-calculate age when birthday changes
        document.getElementById('birthday').addEventListener('change', function() {
            const birthday = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthday.getFullYear();
            const monthDiff = today.getMonth() - birthday.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthday.getDate())) {
                age--;
            }
            
            document.getElementById('age').value = age;
            
            // Validate age
            if (age < 18) {
                showNotification('User must be at least 18 years old to register', 'error');
                this.value = '';
                document.getElementById('age').value = '';
            }
        });

        // Username validation
        document.getElementById('username').addEventListener('input', function() {
            const username = this.value;
            const pattern = /^[a-zA-Z0-9_]+$/;
            
            if (username && !pattern.test(username)) {
                this.setCustomValidity('Username can only contain letters, numbers, and underscores');
                this.classList.add('invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('invalid');
            }
        });

        // Form submission validation
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const age = parseInt(document.getElementById('age').value);
            
            if (password.length < 6) {
                showNotification('Password must be at least 6 characters long', 'error');
                e.preventDefault();
                return false;
            }
            
            if (age < 18) {
                showNotification('User must be at least 18 years old to register', 'error');
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
            
            return true;
        });

        // Reset form function
        function resetForm() {
            if(confirm('Are you sure you want to reset all form data?')) {
                document.getElementById('registrationForm').reset();
                document.getElementById('age').value = '';
                // Remove any validation classes
                document.querySelectorAll('.invalid').forEach(el => el.classList.remove('invalid'));
            }
        }

        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('passwordToggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Enhanced Notification System
        function showNotification(message, type = 'info', duration = 5000) {
            const container = document.getElementById('notificationContainer');
            
            // Remove existing notifications of the same type
            const existingNotifications = container.querySelectorAll(`.notification.${type}`);
            existingNotifications.forEach(notification => {
                removeNotification(notification);
            });
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            
            // Set notification content
            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };
            
            const titles = {
                success: 'Success',
                error: 'Error',
                warning: 'Warning',
                info: 'Information'
            };
            
            notification.innerHTML = `
                <div class="notification-icon">
                    <i class="${icons[type] || icons.info}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">${titles[type] || titles.info}</div>
                    <div class="notification-message">${message}</div>
                </div>
                <button class="notification-close" onclick="removeNotification(this.parentElement)" type="button">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Add to container
            container.appendChild(notification);
            
            // Force reflow to ensure the element is in the DOM
            notification.offsetHeight;
            
            // Show notification with animation
            setTimeout(() => {
                notification.classList.add('show');
            }, 50);
            
            // Auto-remove after duration
            const autoRemoveTimer = setTimeout(() => {
                removeNotification(notification);
            }, duration);
            
            // Store the timer on the element for reference
            notification._autoRemoveTimer = autoRemoveTimer;
            
            return notification;
        }

        function removeNotification(notification) {
            if (notification && notification.parentElement) {
                // Clear any existing timer
                if (notification._autoRemoveTimer) {
                    clearTimeout(notification._autoRemoveTimer);
                }
                
                notification.classList.remove('show');
                notification.classList.add('hide');
                
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }
        }

        // Show notification if there's a message from PHP
        <?php if (!empty($message)): ?>
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(() => {
                    showNotification('<?php echo addslashes($message); ?>', '<?php echo $messageType; ?>');
                }, 300);
                
                <?php if ($messageType === 'success'): ?>
                // Clear form after successful registration
                setTimeout(() => {
                    document.getElementById('registrationForm').reset();
                    document.getElementById('age').value = '';
                    // Remove loading state
                    const submitBtn = document.querySelector('button[type="submit"]');
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                }, 2000);
                <?php else: ?>
                // Remove loading state on error
                setTimeout(() => {
                    const submitBtn = document.querySelector('button[type="submit"]');
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                }, 500);
                <?php endif; ?>
            });
        <?php endif; ?>

        // Add input focus animations
        document.querySelectorAll('.form-group input, .form-group select').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
            
            // Check if input has value on load
            if (input.value) {
                input.parentElement.classList.add('focused');
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('show');
                mainContent.classList.remove('expanded');
            }
        });
    </script>
</body>
</html>