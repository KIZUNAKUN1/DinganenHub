<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Barangay Dinganen - Blotter Records</title>
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../Assets/Style/Viewblotter.css" />
    <link rel="stylesheet" href="../Assets/Style/DropdownDesign/Dropdown.css"/>
    <link rel="stylesheet" href="../Assets/Style/Edit/edit_blotter.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    
    <!-- Additional CSS for notifications -->
    <style>
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            font-weight: 500;
            max-width: 300px;
            animation: slideIn 0.3s ease;
        }
        
        .notification.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .notification.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
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
                    <li class="dropdown">
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

        <!-- Main Content Area -->
        <main class="main-content">
            <div class="view-container">
                <!-- Header Section -->
                <div class="blotter-header">
                    <h2 class="blotter-title">
                        <i class="fa fa-file-alt"></i>
                        Blotter Records
                    </h2>
                    <div class="search-container">
                        <input 
                            type="text" 
                            class="search-input" 
                            placeholder="Search blotter records..." 
                            id="searchInput"
                        >
                        <button class="search-btn" onclick="searchRecords()">
                            <i class="fa fa-search"></i> Search
                        </button>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="table-wrapper">
                    <table class="blotter-table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag"></i> ID</th>
                                <th><i class="fa fa-exclamation-triangle"></i> Incident</th>
                                <th><i class="fa fa-map-marker-alt"></i> Location</th>
                                <th><i class="fa fa-calendar"></i> Date</th>
                                <th><i class="fa fa-clock"></i> Time</th>
                                <th><i class="fa fa-user-secret"></i> Suspect Name</th>
                                <th><i class="fa fa-birthday-cake"></i> Suspect Age</th>
                                <th><i class="fa fa-venus-mars"></i> Suspect Gender</th>
                                <th><i class="fa fa-user-injured"></i> Victim Name</th>
                                <th><i class="fa fa-birthday-cake"></i> Victim Age</th>
                                <th><i class="fa fa-venus-mars"></i> Victim Gender</th>
                                <th><i class="fa fa-home"></i> Residency</th>
                                <th><i class="fa fa-info-circle"></i> Details</th>
                                <th><i class="fa fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody id="blotterTableBody">
                            <?php 
                            require_once '../Assets/php/Config.php';
                            $conn = getDBConnection();

                            // Fetch blotter records
                            $sql = "SELECT * FROM blotter ORDER BY Id DESC";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    // Extract data with proper column names matching your database
                                    $id = $row['Id'];
                                    $incident = isset($row['what']) ? $row['what'] : 'N/A';
                                    $location = isset($row['where_location']) ? $row['where_location'] : 'N/A';
                                    $date = isset($row['when_date']) ? $row['when_date'] : '';
                                    $time = isset($row['time_time']) ? $row['time_time'] : '';
                                    $suspectName = isset($row['suspectname']) ? $row['suspectname'] : 'N/A';
                                    $suspectAge = isset($row['suspectage']) ? $row['suspectage'] : 'N/A';
                                    $suspectGender = isset($row['suspectgender']) ? $row['suspectgender'] : 'N/A';
                                    $victimName = isset($row['victimname']) ? $row['victimname'] : 'N/A';
                                    $victimAge = isset($row['victimage']) ? $row['victimage'] : 'N/A';
                                    $victimGender = isset($row['victimgender']) ? $row['victimgender'] : 'N/A';
                                    $residency = isset($row['Residency']) ? $row['Residency'] : 'N/A';
                                    $details = isset($row['how']) ? $row['how'] : 'N/A';
                                    
                                    // Format date and time
                                    $formattedDate = 'N/A';
                                    $formattedTime = 'N/A';
                                    
                                    if (!empty($date) && $date != '0000-00-00') {
                                        $formattedDate = date('M d, Y', strtotime($date));
                                    }
                                    
                                    if (!empty($time) && $time != '00:00:00') {
                                        $formattedTime = date('h:i A', strtotime($time));
                                    }
                                    
                                    // Truncate details for preview
                                    $detailsPreview = strlen($details) > 50 ? substr($details, 0, 50) . "..." : $details;
                                    
                                    // Output table row
                                    echo "<tr data-id='{$id}'>
                                    <td><strong>#{$id}</strong></td>
                                    <td><span class='incident-type'>{$incident}</span></td>
                                    <td>{$location}</td>
                                    <td>{$formattedDate}</td>
                                    <td>{$formattedTime}</td>
                                    <td>{$suspectName}</td>
                                    <td>{$suspectAge}</td>
                                    <td>{$suspectGender}</td>
                                    <td>{$victimName}</td>
                                    <td>{$victimAge}</td>
                                    <td>{$victimGender}</td>
                                    <td>{$residency}</td>
                                    <td><span class='details-preview'>{$detailsPreview}</span></td>
                                    <td class='action-buttons'>
                                        <button onclick=\"viewRecord({$id})\" title=\"View Details\"><i class='fa fa-eye'></i></button>
                                        <button onclick=\"editRecord({$id})\" title=\"Edit Record\"><i class='fa fa-edit'></i></button>
                                        <button onclick=\"deleteRecord({$id})\" title=\"Delete Record\"><i class='fa fa-trash'></i></button>
                                    </td>
                                </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='14' style='text-align:center; padding: 40px; color: #6b7280;'><i class='fa fa-info-circle' style='font-size: 24px; margin-bottom: 10px; display: block;'></i>No blotter records found</td></tr>";
                            }

                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- No records message (hidden by default) -->
                <div id="noRecordsMessage" style="display: none; text-align: center; padding: 40px; color: #6b7280;">
                    <i class="fa fa-info-circle" style="font-size: 48px; margin-bottom: 20px; display: block;"></i>
                    <h3>No Records Found</h3>
                    <p>There are no blotter records matching your search criteria.</p>
                </div>

                <!-- Error message (hidden by default) -->
                <div id="errorMessage" style="display: none; text-align: center; padding: 40px; color: #dc3545;">
                    <i class="fa fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 20px; display: block;"></i>
                    <h3>Error Loading Records</h3>
                    <p>There was an error fetching the blotter records. Please try again later.</p>
                    <button onclick="location.reload()" style="margin-top: 20px; padding: 10px 20px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">
                        <i class="fa fa-refresh"></i> Retry
                    </button>
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript Section -->
    <script>
        // ===== RECORD OPERATIONS =====
        
        /**
         * View a blotter record
         */
        function viewRecord(id) {
            // Option 1: Redirect to a view page
            window.location.href = `view_blotter_details.php?id=${id}`;
            
            // Option 2: Show in modal (uncomment if you prefer modal view)
            /*
            fetch(`../Assets/php/get_blotter_details.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showRecordModal(data.record);
                    } else {
                        showNotification('Error loading record: ' + data.message, false);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to load record details', false);
                });
            */
        }

        /**
         * Edit a blotter record
         */
        function editRecord(id) {
            if (confirm('Are you sure you want to edit this record?')) {
                window.location.href = `edit_blotter.php?id=${id}`;
            }
        }

        /**
         * Delete a blotter record
         */
        function deleteRecord(id) {
        if (confirm('Are you sure you want to delete this record? This action cannot be undone.')) {
            // Show loading state
            const deleteBtn = event.target.closest('button');
            const originalContent = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            deleteBtn.disabled = true;
            
            // Create FormData for proper POST request
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id);
            
            // Send delete request
            fetch('../Assets/php/delete_blotter.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                // Check if response is OK
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showNotification('Record deleted successfully', true);
                    // Find and remove the row
                    const rows = document.querySelectorAll('#blotterTableBody tr');
                    rows.forEach(row => {
                        const idCell = row.querySelector('td:first-child strong');
                        if (idCell && idCell.textContent === `#${id}`) {
                            row.style.transition = 'opacity 0.3s, transform 0.3s';
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(-20px)';
                            setTimeout(() => {
                                row.remove();
                                checkEmptyTable();
                            }, 300);
                        }
                    });
                } else {
                    showNotification('Error: ' + (data.message || 'Failed to delete record'), false);
                    // Reset button
                    deleteBtn.innerHTML = originalContent;
                    deleteBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while deleting the record', false);
                // Reset button
                deleteBtn.innerHTML = originalContent;
                deleteBtn.disabled = false;
            });
        }
    }

        // ===== SEARCH FUNCTIONALITY =====
        
        /**
         * Search records in the table
         */
        function searchRecords() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
            const tableRows = document.querySelectorAll('#blotterTableBody tr');
            let visibleRows = 0;

            tableRows.forEach(row => {
                // Skip if it's the "no records" row
                if (row.querySelector('td[colspan]')) {
                    return;
                }
                
                const rowText = row.textContent.toLowerCase();
                if (searchTerm === '' || rowText.includes(searchTerm)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Show/hide no records message
            const noRecordsMsg = document.getElementById('noRecordsMessage');
            const tableWrapper = document.querySelector('.table-wrapper');
            
            if (visibleRows === 0 && searchTerm !== '') {
                tableWrapper.style.display = 'none';
                noRecordsMsg.style.display = 'block';
            } else {
                tableWrapper.style.display = 'block';
                noRecordsMsg.style.display = 'none';
            }
        }

        // ===== UTILITY FUNCTIONS =====
        
        /**
         * Show notification message
         */
        function showNotification(message, isSuccess = true) {
            const notification = document.createElement('div');
            notification.className = `notification ${isSuccess ? 'success' : 'error'}`;
            notification.innerHTML = `
                <i class="fa fa-${isSuccess ? 'check' : 'exclamation'}-circle" style="margin-right: 8px;"></i>
                ${message}
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 4 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease forwards';
                setTimeout(() => notification.remove(), 300);
            }, 4000);
        }

        /**
         * Check if table is empty and show appropriate message
         */
        function checkEmptyTable() {
            const rows = document.querySelectorAll('#blotterTableBody tr');
            const hasData = Array.from(rows).some(row => !row.querySelector('td[colspan]'));
            
            if (!hasData) {
                const tbody = document.getElementById('blotterTableBody');
                tbody.innerHTML = `
                    <tr>
                        <td colspan='14' style='text-align:center; padding: 40px; color: #6b7280;'>
                            <i class='fa fa-info-circle' style='font-size: 24px; margin-bottom: 10px; display: block;'></i>
                            No blotter records found
                        </td>
                    </tr>
                `;
            }
        }

        /**
         * Format date for display
         */
        function formatDate(dateString) {
            if (!dateString || dateString === 'N/A') return 'N/A';
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            } catch (e) {
                return dateString;
            }
        }

        /**
         * Format time for display
         */
        function formatTime(timeString) {
            if (!timeString || timeString === 'N/A') return 'N/A';
            try {
                const [hours, minutes] = timeString.split(':');
                const date = new Date();
                date.setHours(hours, minutes);
                return date.toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
            } catch (e) {
                return timeString;
            }
        }

        // ===== EVENT LISTENERS =====
        
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            
            if (searchInput) {
                // Search on Enter key
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        searchRecords();
                    }
                });

                // Real-time search as user types
                searchInput.addEventListener('input', function() {
                    searchRecords();
                });

                // Clear search on Escape key
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        this.value = '';
                        searchRecords();
                    }
                });
            }

            // Add hover effect to table rows
            const tableRows = document.querySelectorAll('#blotterTableBody tr');
            tableRows.forEach(row => {
                if (!row.querySelector('td[colspan]')) {
                    row.style.cursor = 'pointer';
                    row.addEventListener('mouseenter', function() {
                        this.style.backgroundColor = '#f5f5f5';
                    });
                    row.addEventListener('mouseleave', function() {
                        this.style.backgroundColor = '';
                    });
                }
            });
        });

        // ===== PRINT FUNCTIONALITY (OPTIONAL) =====
        
        /**
         * Print a specific record
         */
        function printRecord(id) {
            fetch(`../Assets/php/get_blotter_details.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const record = data.record;
                        const printWindow = window.open('', '_blank', 'width=800,height=600');
                        
                        const printContent = `
                            <!DOCTYPE html>
                            <html>
                            <head>
                                <title>Blotter Record #${record.id}</title>
                                <style>
                                    body { 
                                        font-family: Arial, sans-serif; 
                                        margin: 20px;
                                        line-height: 1.6;
                                    }
                                    .header { 
                                        text-align: center; 
                                        margin-bottom: 30px;
                                        border-bottom: 2px solid #333;
                                        padding-bottom: 20px;
                                    }
                                    .section { 
                                        margin-bottom: 25px;
                                    }
                                    .section h4 {
                                        margin-bottom: 10px;
                                        color: #333;
                                        border-bottom: 1px solid #ddd;
                                        padding-bottom: 5px;
                                    }
                                    .field {
                                        margin-bottom: 8px;
                                    }
                                    .label { 
                                        font-weight: bold; 
                                        display: inline-block; 
                                        width: 150px;
                                        color: #555;
                                    }
                                    .value {
                                        color: #000;
                                    }
                                    @media print { 
                                        body { margin: 0; }
                                        .no-print { display: none; }
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="header">
                                    <h1>Barangay Dinganen</h1>
                                    <h2>Blotter Record #${record.Id}</h2>
                                    <p>Printed on: ${new Date().toLocaleString()}</p>
                                </div>
                                
                                <div class="section">
                                    <h4>Incident Information</h4>
                                    <div class="field">
                                        <span class="label">Incident Type:</span>
                                        <span class="value">${record.what || 'N/A'}</span>
                                    </div>
                                    <div class="field">
                                        <span class="label">Location:</span>
                                        <span class="value">${record.location || record.where || 'N/A'}</span>
                                    </div>
                                    <div class="field">
                                        <span class="label">Date:</span>
                                        <span class="value">${formatDate(record.date || record.when)}</span>
                                    </div>
                                    <div class="field">
                                        <span class="label">Time:</span>
                                        <span class="value">${formatTime(record.time_time || record.time)}</span>
                                    </div>
                                </div>
                                
                                <div class="section">
                                    <h4>Involved Parties</h4>
                                    <div class="field">
                                        <span class="label">Suspect Name:</span>
                                        <span class="value">${record.suspectname || 'N/A'}</span>
                                    </div>
                                    <div class="field">
                                        <span class="label">Suspect Age:</span>
                                        <span class="value">${record.suspectage || 'N/A'} years old</span>
                                    </div>
                                    <div class="field">
                                        <span class="label">Suspect Gender:</span>
                                        <span class="value">${record.suspectgender || 'N/A'}</span>
                                    </div>
                                    <div class="field">
                                        <span class="label">Victim Name:</span>
                                        <span class="value">${record.victimname || 'N/A'}</span>
                                    </div>
                                    <div class="field">
                                        <span class="label">Victim Age:</span>
                                        <span class="value">${record.victimage || 'N/A'} years old</span>
                                    </div>
                                    <div class="field">
                                        <span class="label">Victim Gender:</span>
                                        <span class="value">${record.victimgender || 'N/A'}</span>
                                    </div>
                                    <div class="field">
                                        <span class="label">Residency:</span>
                                        <span class="value">${record.Residency || 'N/A'}</span>
                                    </div>
                                </div>
                                
                                <div class="section">
                                    <h4>Incident Details</h4>
                                    <p>${record.how || 'No additional details provided.'}</p>
                                </div>
                                
                                <div class="no-print" style="text-align: center; margin-top: 40px;">
                                    <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px;">
                                        Print Document
                                    </button>
                                    <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; margin-left: 10px;">
                                        Close
                                    </button>
                                </div>
                            </body>
                            </html>
                        `;
                        
                        printWindow.document.write(printContent);
                        printWindow.document.close();
                        
                        // Auto-trigger print dialog
                        printWindow.onload = function() {
                            printWindow.print();
                        };
                    } else {
                        showNotification('Error loading record for printing', false);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to load record for printing', false);
                });
        }
    </script>
    
    <!-- External Scripts -->
    <script src="../Assets/JS/dropdown.js"></script>
    <script src="../Assets/JS/script.js"></script>
</body>
</html>