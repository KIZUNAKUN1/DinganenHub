<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Barangay Dinganen Dashboard</title>
    <link rel="stylesheet" href="../Assets/Style/DropdownDesign/SIDEBAR.css" />
    <link rel="stylesheet" href="../Assets/Style/Edit/AccountEdit.css" />
    <link rel="stylesheet" href="../Assets/Style/Edit/Delete.css" />
    <link rel="stylesheet" href="../Assets/Style/DropdownDesign/Dropdown.css"/>
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
                            <li class="active" onclick="window.location.href='AccountView.php'">
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
            <!-- View Data -->
            <div class="view-container">
                <div class="blotter-header">
                    <h2 class="blotter-title">
                        <i class="fa fa-file-alt"></i>
                        Account Details
                    </h2>
                    <div class="search-container">
                        <input type="text" class="search-input" placeholder="Search account records..." id="searchInput">
                        <button class="search-btn" onclick="searchRecords()">
                            <i class="fa fa-search"></i> Search
                        </button>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="blotter-table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag"></i> ID</th>
                                <th><i class="fa fa-user"></i> Fullname</th>
                                <th><i class="fa fa-birthday-cake"></i> Birthday</th>
                                <th><i class="fa fa-calendar"></i> Age</th>
                                <th><i class="fa fa-at"></i> Username</th>
                                <th><i class="fa fa-user-secret"></i> Password</th>
                                <th><i class="fa fa-user-tag"></i> Role</th>
                                <th><i class="fa fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>

                        <tbody id="blotterTableBody">
                            <?php 
                            require_once '../Assets/php/Config.php';
                            $conn = getDBConnection();
                            $sql = "SELECT * FROM users ORDER BY id DESC";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $id = isset($row['id']) ? $row['id'] : (isset($row['Id']) ? $row['Id'] : 'N/A');
                                    $fullname = isset($row['Fullname']) ? $row['Fullname'] : (isset($row['fullname']) ? $row['fullname'] : 'N/A');
                                    $birthday = isset($row['Birthday']) ? $row['Birthday'] : (isset($row['birthday']) ? $row['birthday'] : '');
                                    $age = isset($row['Age']) ? $row['Age'] : (isset($row['age']) ? $row['age'] : 'N/A');
                                    $username = isset($row['Username']) ? $row['Username'] : (isset($row['username']) ? $row['username'] : 'N/A');
                                    $password = isset($row['Password']) ? $row['Password'] : (isset($row['password']) ? $row['password'] : 'N/A');
                                    $role = isset($row['Role']) ? $row['Role'] : (isset($row['role']) ? $row['role'] : 'N/A');
                                    
                                    echo "<tr>
                                        <td><strong>#{$id}</strong></td>
                                        <td><span class='incident-type'>{$fullname}</span></td>
                                        <td>" . ($birthday ? date('M d, Y', strtotime($birthday)) : 'N/A') . "</td>
                                        <td>{$age}</td>
                                        <td>{$username}</td>
                                        <td>" . str_repeat('*', min(strlen($password), 8)) . "</td>
                                        <td>{$role}</td>
                                        <td class='action-buttons'>
                                            <button onclick=\"viewRecord({$id})\" title=\"View Details\"><i class='fa fa-eye'></i></button>
                                            <button onclick=\"openEditModal({$id}, '{$fullname}', '{$birthday}', {$age}, '{$username}', '{$role}')\" title=\"Edit Record\"><i class='fa fa-edit'></i></button>
                                            <button onclick=\"deleteRecord({$id})\" title=\"Delete Record\"><i class='fa fa-trash'></i></button>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' style='text-align:center; padding: 40px; color: #6b7280;'><i class='fa fa-info-circle' style='font-size: 24px; margin-bottom: 10px; display: block;'></i>No account records found</td></tr>";
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Edit User Modal -->
                <div id="editModal" class="modal-overlay">
                    <div class="edit-modal">
                        <div class="loading-overlay" id="loadingOverlay">
                            <div class="spinner"></div>
                        </div>
                        
                        <div class="modal-header">
                            <h3>
                                <i class="fa fa-user-edit"></i>
                                Edit User Profile
                            </h3>
                            <button type="button" class="close-btn" onclick="closeModal()">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                        
                        <div class="modal-body" id="modalBody">
                            <form id="editUserForm" onsubmit="handleFormSubmit(event)">
                                <input type="hidden" id="edit-id" name="id">
                                
                                <div class="form-group">
                                    <label for="edit-fullname">
                                        <i class="fa fa-user"></i> Full Name
                                    </label>
                                    <input 
                                        type="text" 
                                        id="edit-fullname" 
                                        name="fullname" 
                                        required 
                                        placeholder="Enter full name"
                                    >
                                </div>
                                
                                <div class="form-group">
                                    <label for="edit-birthday">
                                        <i class="fa fa-birthday-cake"></i> Birthday
                                    </label>
                                    <input 
                                        type="date" 
                                        id="edit-birthday" 
                                        name="birthday" 
                                        required
                                        onchange="calculateAge()"
                                    >
                                </div>

                                <div class="form-group">
                                    <label for="edit-age">
                                        <i class="fa fa-calendar"></i> Age
                                    </label>
                                    <input 
                                        type="number" 
                                        id="edit-age" 
                                        name="age" 
                                        required 
                                        min="1" 
                                        max="120"
                                        placeholder="Enter age"
                                    >
                                </div>
                                
                                <div class="form-group">
                                    <label for="edit-username">
                                        <i class="fa fa-at"></i> Username
                                    </label>
                                    <input 
                                        type="text" 
                                        id="edit-username" 
                                        name="username" 
                                        required 
                                        placeholder="Enter username"
                                    >
                                </div>
                                
                                <div class="form-group">
                                    <label for="edit-password">
                                        <i class="fa fa-lock"></i> Password
                                    </label>
                                    <div class="password-input-container">
                                        <input 
                                            type="password" 
                                            id="edit-password" 
                                            name="password" 
                                            placeholder="Leave blank to keep current password"
                                        >
                                        <button type="button" id="togglePassword" class="toggle-password" onclick="togglePasswordVisibility()">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                    <p class="help-text">Leave blank if you don't want to change the password</p>
                                </div>
                                
                                <div class="form-group">
                                    <label for="edit-role">
                                        <i class="fa fa-user-tag"></i> Role
                                    </label>
                                    <select id="edit-role" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="Staff">Staff</option>
                                        
                                    </select>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary" id="submitEditBtn">
                                        <i class="fa fa-save"></i>
                                        Save Changes
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="closeModal()">
                                        <i class="fa fa-times"></i>
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- External Scripts -->
    <script src="../Assets/JS/dropdown.js"></script>
    <script src="../Assets/JS/script.js"></script>
    
    <!-- Main Application Script -->
    <script>
        // Global variable to store current editing user ID (declared only once)
        let currentEditingId = null;

        // Function to open edit modal with user data
        function openEditModal(id, fullname, birthday, age, username, role) {
            currentEditingId = id;
            
            // Populate form fields
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-fullname').value = fullname;
            document.getElementById('edit-birthday').value = birthday;
            document.getElementById('edit-age').value = age;
            document.getElementById('edit-username').value = username;
            document.getElementById('edit-role').value = role;
            
            // Show modal
            const modal = document.getElementById('editModal');
            modal.classList.add('active');
            
            // Remove any existing alerts
            const existingAlert = document.querySelector('.alert');
            if (existingAlert) {
                existingAlert.remove();
            }
        }

        // Function to calculate age from birthday
        function calculateAge() {
            const birthdayInput = document.getElementById('edit-birthday');
            const ageInput = document.getElementById('edit-age');
            
            if (birthdayInput.value) {
                const birthday = new Date(birthdayInput.value);
                const today = new Date();
                let age = today.getFullYear() - birthday.getFullYear();
                const monthDiff = today.getMonth() - birthday.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthday.getDate())) {
                    age--;
                }
                
                ageInput.value = age;
            }
        }

        function closeModal() {
            const modal = document.getElementById('editModal');
            modal.classList.remove('active');
            
            // Reset form
            document.getElementById('editUserForm').reset();
            currentEditingId = null;
            
            // Remove any alerts
            const existingAlert = document.querySelector('.alert');
            if (existingAlert) {
                existingAlert.remove();
            }
        }

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('edit-password');
            const toggleBtn = document.getElementById('togglePassword');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.innerHTML = '<i class="fa fa-eye-slash"></i>';
            } else {
                passwordInput.type = 'password';
                toggleBtn.innerHTML = '<i class="fa fa-eye"></i>';
            }
        }

        function showAlert(type, message) {
            // Remove existing alert if any
            const existingAlert = document.querySelector('.alert');
            if (existingAlert) {
                existingAlert.remove();
            }
            
            // Create new alert
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.innerHTML = `
                <i class="fa fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            `;
            
            // Insert alert at the beginning of modal body
            const modalBody = document.getElementById('modalBody');
                modalBody.insertBefore(alert, modalBody.firstChild);
                
                // Auto-remove alert after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        }

        function showLoading(show) {
            const loadingOverlay = document.getElementById('loadingOverlay');
            if (show) {
                loadingOverlay.classList.add('active');
            } else {
                loadingOverlay.classList.remove('active');
            }
        }

        function handleFormSubmit(event) {
            event.preventDefault();
            
            // Show loading
            showLoading(true);
            const submitBtn = document.getElementById('submitEditBtn');
            submitBtn.disabled = true;
            
            // Get form data
            const formData = new FormData(document.getElementById('editUserForm'));
            
            // Send AJAX request to update user
            fetch('../Assets/php/update_user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showLoading(false);
                submitBtn.disabled = false;
                
                if (data.success) {
                    showAlert('success', 'User profile updated successfully!');
                    
                    // Update the table row with new data
                    updateTableRow(currentEditingId, {
                        fullname: formData.get('fullname'),
                        birthday: formData.get('birthday'),
                        age: formData.get('age'),
                        username: formData.get('username'),
                        role: formData.get('role')
                    });
                    
                    // Close modal after 2 seconds
                    setTimeout(() => {
                        closeModal();
                    }, 2000);
                } else {
                    showAlert('error', data.message || 'Error updating user profile');
                }
            })
            .catch(error => {
                showLoading(false);
                submitBtn.disabled = false;
                showAlert('error', 'Network error. Please try again.');
                console.error('Error:', error);
            });
        }

        // Function to update table row after successful edit
        function updateTableRow(userId, userData) {
            const rows = document.querySelectorAll('#blotterTableBody tr');
            
            rows.forEach(row => {
                const idCell = row.cells[0];
                if (idCell && idCell.textContent.includes('#' + userId)) {
                    row.cells[1].innerHTML = `<span class="incident-type">${userData.fullname}</span>`;
                    row.cells[2].textContent = userData.birthday ? new Date(userData.birthday).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    }) : 'N/A';
                    row.cells[3].textContent = userData.age;
                    row.cells[4].textContent = userData.username;
                    row.cells[6].textContent = userData.role;
                    
                    // Add highlight effect
                    row.style.backgroundColor = '#e0f2fe';
                    setTimeout(() => {
                        row.style.backgroundColor = '';
                        row.style.transition = 'background-color 0.5s ease';
                    }, 1000);
                }
            });
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Additional functions for other actions
        function viewRecord(id) {
            // Implement view functionality
            alert('View record ID: ' + id);
        }

        function deleteRecord(id) {
            // Create custom confirmation modal
            const confirmHtml = `
                <div id="deleteConfirmModal" class="modal-overlay active">
                    <div class="confirm-modal">
                        <div class="confirm-icon">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <h3>Confirm Deletion</h3>
                        <p>Are you sure you want to delete this user account?</p>
                        <p class="warning-text"><i class="fa fa-info-circle"></i> This action cannot be undone.</p>
                        <div class="confirm-actions">
                            <button class="btn btn-danger" onclick="confirmDelete(${id})">
                                <i class="fa fa-trash"></i> Yes, Delete
                            </button>
                            <button class="btn btn-secondary" onclick="cancelDelete()">
                                <i class="fa fa-times"></i> Cancel
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            // Add confirmation modal to body
            document.body.insertAdjacentHTML('beforeend', confirmHtml);
        }

        function confirmDelete(id) {
            // Show loading state
            const confirmModal = document.getElementById('deleteConfirmModal');
            const modalContent = confirmModal.querySelector('.confirm-modal');
            modalContent.innerHTML = `
                <div class="delete-loading">
                    <div class="spinner"></div>
                    <p>Deleting user account...</p>
                </div>
            `;
            
            // Send delete request
            fetch('../Assets/php/delete_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + id
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    modalContent.innerHTML = `
                        <div class="delete-success">
                            <i class="fa fa-check-circle"></i>
                            <h3>Success!</h3>
                            <p>User account has been deleted successfully.</p>
                        </div>
                    `;
                    
                    // Remove row from table with animation
                    const rows = document.querySelectorAll('#blotterTableBody tr');
                    rows.forEach(row => {
                        const idCell = row.cells[0];
                        if (idCell && idCell.textContent.includes('#' + id)) {
                            row.style.transition = 'all 0.3s ease';
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(-100%)';
                            setTimeout(() => {
                                row.remove();
                                // Check if table is empty
                                checkEmptyTable();
                            }, 300);
                        }
                    });
                    
                    // Close modal after 1.5 seconds
                    setTimeout(() => {
                        confirmModal.remove();
                    }, 1500);
                    
                } else {
                    // Show error message
                    modalContent.innerHTML = `
                        <div class="delete-error">
                            <i class="fa fa-times-circle"></i>
                            <h3>Error!</h3>
                            <p>${data.message || 'Failed to delete user account'}</p>
                            <button class="btn btn-secondary" onclick="cancelDelete()">
                                <i class="fa fa-times"></i> Close
                            </button>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                modalContent.innerHTML = `
                    <div class="delete-error">
                        <i class="fa fa-times-circle"></i>
                        <h3>Network Error!</h3>
                        <p>Unable to connect to the server. Please try again.</p>
                        <button class="btn btn-secondary" onclick="cancelDelete()">
                            <i class="fa fa-times"></i> Close
                        </button>
                    </div>
                `;
            });
        }

        function cancelDelete() {
            const confirmModal = document.getElementById('deleteConfirmModal');
            if (confirmModal) {
                confirmModal.remove();
            }
        }

        function checkEmptyTable() {
            const tbody = document.getElementById('blotterTableBody');
            const rows = tbody.querySelectorAll('tr');
            
            if (rows.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" style="text-align:center; padding: 40px; color: #6b7280;">
                            <i class="fa fa-info-circle" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                            No account records found
                        </td>
                    </tr>
                `;
            }
        }

        function searchRecords() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#blotterTableBody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Add event listener for search input (search as you type)
        document.getElementById('searchInput').addEventListener('keyup', searchRecords);
    </script>
</body>
</html>