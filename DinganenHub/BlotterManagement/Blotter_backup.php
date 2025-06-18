<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Barangay Dinganen Dashboard - Blotter Backup</title>
    <link rel="stylesheet" href="../Assets/Style/Recordblotter.css" />
    <link rel="stylesheet" href="../Assets/Style/Backup/BlotterBackup.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
    <div class="dashboard-container">
        <!-- sidebar.html -->
        <aside class="sidebar">
            <img src="Picture1.png" class="sidebar-picture" >
            <div class="sidebar-title"><B>DinganenHub</B></div>
            <nav>
                <ul>
                    <li onclick="window.location.href='../AdminDashboard.html'" class="active"><i class="fa fa-tachometer-alt"></i> Dashboard</li>
                    <li class="dropdown"><i class="	fa fa-file-alt dropbtn"></i> Residential Details  <i class="fa fa-chevron-down dropdown-arrow"></i>
                        <ul class="dropdown-content">
                            <li><a href="Record_Resident.html"><i class="fa fa-address-card"></i> Record Residential Details</a></li>
                            <li><a href=""><i class=" fa fa-certificate"></i> Certificate</a></li>
                            <li><a href=""><i class=" fa fa-id-badge"></i> Resident Record</a></li>
                            <li><a href=""><i class="fa fa-database"></i> Backup</a></li>
                        </ul>
                    </li>
                    <li class="dropdown active" ><i class="fa fa-user dropbtn"></i> Blotter Management  <i class="fa fa-chevron-down dropdown-arrow"></i>
                        <ul class="dropdown-content">
                            <li><a href="Viewblotter.php"><i class="fa fa-pencil-alt"></i> Record Blotter</a></li>
                            <li><a href="Viewblotter.php"><i class="fa fa-folder-open"></i> View Blotter</a></li>
                            <li><a href=""><i class="fa fa-user-check"></i> Summon</a></li>
                            <li class="active"><a href=""><i class="fa fa-database"></i> Backup</a></li>
                        </ul>
                    </li>
                    
                    <li><i class="fa fa-bullhorn"></i> Announcement</li>
                    <li class="dropdown"><i class="fa fa-user dropbtn"></i> Account Details  <i class="fa fa-chevron-down dropdown-arrow"></i>
                        <ul class="dropdown-content">
                            <li><a href="../AccountDetails/AccountRegistration.php"><i class="fa fa-user-plus"></i> User Registration</a></li>
                            <li><a href=""><i class="fa fa-address-book"></i> Account List</a></li>
                            <li><a href=""><i class="fa fa-history"></i> Activity Logs</a></li>
                            <li><a href=""><i class="fa fa-database"></i> Backup</a></li>
                        </ul>
                    </li>
                    <li><i class="fa fa-sign-out-alt"></i> Logout</li>
                </ul>
            </nav>
        </aside>
     <!-- sidebar.html -->

      <!-- Content Area -->
        <main class="main-content">
            <!-- Backup Header -->
            <div class="backup-header">
                <i class="fas fa-database"></i>
                <h1>Blotter Database Backup</h1>
            </div>

            <!-- Excel Export Information -->
            <div class="excel-info">
                <div class="excel-format">
                    <i class="fas fa-file-excel"></i>
                    <span>Excel Format Export</span>
                </div>
                <p class="format-description">
                    Export your complete blotter database to Excel format (.xlsx) for easy viewing, analysis, and archival purposes.
                </p>
            </div>

            <!-- Backup Options -->
            <div class="backup-cards">
                <!-- Quick Backup -->
                <div class="backup-card">
                    <div class="card-header">
                        <div class="card-icon quick-backup">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div class="card-title">
                            <h3>Quick Backup</h3>
                            <p>Export all blotter records instantly</p>
                        </div>
                    </div>
                    <div class="card-content">
                        <p>Generate a complete backup of all blotter records in your database. This includes all case details, complainants, respondents, and incident information.</p>
                        <button class="backup-btn primary" onclick="performQuickBackup()">
                            <i class="fas fa-download"></i>
                            Export All Records
                        </button>
                    </div>
                </div>

                <!-- Custom Backup -->
                <div class="backup-card">
                    <div class="card-header">
                        <div class="card-icon custom-backup">
                            <i class="fas fa-filter"></i>
                        </div>
                        <div class="card-title">
                            <h3>Custom Backup</h3>
                            <p>Filter and export specific records</p>
                        </div>
                    </div>
                    <div class="card-content">
                        <p>Export blotter records based on specific criteria such as date range, case status, or incident type.</p>
                        
                        <div class="filter-section">
                            <div class="filter-group">
                                <label for="dateFrom">Date Range:</label>
                                <div class="date-inputs">
                                    <input type="date" id="dateFrom" class="date-input" />
                                    <span>to</span>
                                    <input type="date" id="dateTo" class="date-input" />
                                </div>
                            </div>
                            
                            <div class="filter-group">
                                <label for="statusFilter">Case Status:</label>
                                <select id="statusFilter" class="status-select">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="resolved">Resolved</option>
                                    <option value="pending">Pending</option>
                                    <option value="dismissed">Dismissed</option>
                                </select>
                            </div>
                        </div>
                        
                        <button class="backup-btn secondary" onclick="performCustomBackup()">
                            <i class="fas fa-download"></i>
                            Export Filtered Records
                        </button>
                    </div>
                </div>

                <!-- Scheduled Backup -->
                <div class="backup-card">
                    <div class="card-header">
                        <div class="card-icon scheduled-backup">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="card-title">
                            <h3>Scheduled Backup</h3>
                            <p>Set up automatic backups</p>
                        </div>
                    </div>
                    <div class="card-content">
                        <p>Configure automatic backup schedules to ensure your blotter data is regularly saved without manual intervention.</p>
                        
                        <div class="schedule-options">
                            <label class="schedule-option">
                                <input type="radio" name="schedule" value="daily" />
                                Daily
                            </label>
                            <label class="schedule-option">
                                <input type="radio" name="schedule" value="weekly" />
                                Weekly
                            </label>
                            <label class="schedule-option">
                                <input type="radio" name="schedule" value="monthly" />
                                Monthly
                            </label>
                        </div>
                        
                        <button class="backup-btn tertiary" onclick="setupScheduledBackup()">
                            <i class="fas fa-calendar-alt"></i>
                            Setup Schedule
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recent Backups -->
            <div class="recent-backups">
                <div class="section-header">
                    <h2>Recent Backups</h2>
                    <button class="refresh-btn" onclick="refreshBackupHistory()">
                        <i class="fas fa-sync-alt"></i>
                        Refresh
                    </button>
                </div>
                
                <div class="backup-history" id="backupHistory">
                    <!-- Sample backup items -->
                    <div class="backup-item">
                        <div class="backup-info">
                            <div class="backup-file-icon">
                                <i class="fas fa-file-excel"></i>
                            </div>
                            <div class="backup-details">
                                <h4>Blotter_Backup_2025_06_11.xlsx</h4>
                                <p>Complete database backup • 1,247 records</p>
                                <div class="backup-date">June 11, 2025 - 10:30 AM</div>
                            </div>
                        </div>
                        <div class="backup-actions">
                            <button class="action-btn download" onclick="downloadBackup('backup_1')">
                                <i class="fas fa-download"></i>
                                Download
                            </button>
                            <button class="action-btn delete" onclick="deleteBackup('backup_1')">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>
                    
                    <div class="backup-item">
                        <div class="backup-info">
                            <div class="backup-file-icon">
                                <i class="fas fa-file-excel"></i>
                            </div>
                            <div class="backup-details">
                                <h4>Blotter_Filtered_2025_06_10.xlsx</h4>
                                <p>Filtered backup (Active cases) • 342 records</p>
                                <div class="backup-date">June 10, 2025 - 3:45 PM</div>
                            </div>
                        </div>
                        <div class="backup-actions">
                            <button class="action-btn download" onclick="downloadBackup('backup_2')">
                                <i class="fas fa-download"></i>
                                Download
                            </button>
                            <button class="action-btn delete" onclick="deleteBackup('backup_2')">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>
                    
                    <div class="backup-item">
                        <div class="backup-info">
                            <div class="backup-file-icon">
                                <i class="fas fa-file-excel"></i>
                            </div>
                            <div class="backup-details">
                                <h4>Blotter_Monthly_2025_05.xlsx</h4>
                                <p>Monthly backup (May 2025) • 156 records</p>
                                <div class="backup-date">June 1, 2025 - 12:00 AM</div>
                            </div>
                        </div>
                        <div class="backup-actions">
                            <button class="action-btn download" onclick="downloadBackup('backup_3')">
                                <i class="fas fa-download"></i>
                                Download
                            </button>
                            <button class="action-btn delete" onclick="deleteBackup('backup_3')">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Storage Information -->
            <div class="storage-info">
                <div class="storage-card">
                    <div class="storage-header">
                        <i class="fas fa-hdd"></i>
                        <h3>Backup Storage</h3>
                    </div>
                    <div class="storage-bar">
                        <div class="storage-used" style="width: 35%"></div>
                    </div>
                    <div class="storage-text">
                        <span>Used: 175 MB of 500 MB</span>
                        <span class="storage-percentage">35%</span>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Notification Container -->
    <div id="notificationContainer"></div>

    <script src="../Assets/JS/dropdown.js"></script>
    <script>
        // Backup functionality
        function performQuickBackup() {
            showNotification('Processing backup...', 'info');
            
            // Simulate backup process
            setTimeout(() => {
                showNotification('Backup completed successfully!', 'success');
                
                // Simulate file download
                const filename = `Blotter_Backup_${new Date().toISOString().split('T')[0].replace(/-/g, '_')}.xlsx`;
                simulateDownload(filename);
                
                // Add to backup history
                addToBackupHistory(filename, 'Complete database backup', getTotalRecords());
            }, 2000);
        }

        function performCustomBackup() {
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            const status = document.getElementById('statusFilter').value;
            
            if (!dateFrom && !dateTo && !status) {
                showNotification('Please select at least one filter option.', 'error');
                return;
            }
            
            showNotification('Processing filtered backup...', 'info');
            
            setTimeout(() => {
                showNotification('Filtered backup completed successfully!', 'success');
                
                const filename = `Blotter_Filtered_${new Date().toISOString().split('T')[0].replace(/-/g, '_')}.xlsx`;
                simulateDownload(filename);
                
                // Add to backup history with filter info
                let filterInfo = 'Filtered backup';
                if (status) filterInfo += ` (${status} cases)`;
                if (dateFrom || dateTo) filterInfo += ` • Date range applied`;
                
                addToBackupHistory(filename, filterInfo, Math.floor(Math.random() * 500) + 100);
            }, 2500);
        }

        function setupScheduledBackup() {
            const selectedSchedule = document.querySelector('input[name="schedule"]:checked');
            
            if (!selectedSchedule) {
                showNotification('Please select a backup schedule.', 'error');
                return;
            }
            
            showNotification(`${selectedSchedule.value.charAt(0).toUpperCase() + selectedSchedule.value.slice(1)} backup schedule has been set up successfully!`, 'success');
        }

        function refreshBackupHistory() {
            showNotification('Refreshing backup history...', 'info');
            
            setTimeout(() => {
                showNotification('Backup history refreshed!', 'success');
            }, 1000);
        }

        function downloadBackup(backupId) {
            showNotification('Downloading backup file...', 'info');
            
            setTimeout(() => {
                showNotification('Download started!', 'success');
            }, 500);
        }

        function deleteBackup(backupId) {
            if (confirm('Are you sure you want to delete this backup file?')) {
                showNotification('Backup file deleted successfully!', 'success');
                
                // Remove the backup item from the list
                const backupItem = event.target.closest('.backup-item');
                if (backupItem) {
                    backupItem.remove();
                }
            }
        }

        // Utility functions
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            
            const iconMap = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                info: 'fas fa-info-circle'
            };
            
            notification.innerHTML = `
                <div class="notification-content">
                    <i class="${iconMap[type]}"></i>
                    <span>${message}</span>
                </div>
                <button class="notification-close" onclick="closeNotification(this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            document.getElementById('notificationContainer').appendChild(notification);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 5000);
        }

        function closeNotification(button) {
            const notification = button.closest('.notification');
            if (notification) {
                notification.remove();
            }
        }

        function simulateDownload(filename) {
            // This would be replaced with actual download functionality
            console.log(`Downloading: ${filename}`);
        }

        function getTotalRecords() {
            // This would be replaced with actual database count
            return Math.floor(Math.random() * 1000) + 500;
        }

        function addToBackupHistory(filename, description, recordCount) {
            const backupHistory = document.getElementById('backupHistory');
            const now = new Date();
            const dateString = now.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            const timeString = now.toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit',
                hour12: true
            });
            
            const newBackupItem = document.createElement('div');
            newBackupItem.className = 'backup-item';
            newBackupItem.innerHTML = `
                <div class="backup-info">
                    <div class="backup-file-icon">
                        <i class="fas fa-file-excel"></i>
                    </div>
                    <div class="backup-details">
                        <h4>${filename}</h4>
                        <p>${description} • ${recordCount} records</p>
                        <div class="backup-date">${dateString} - ${timeString}</div>
                    </div>
                </div>
                <div class="backup-actions">
                    <button class="action-btn download" onclick="downloadBackup('backup_new')">
                        <i class="fas fa-download"></i>
                        Download
                    </button>
                    <button class="action-btn delete" onclick="deleteBackup('backup_new')">
                        <i class="fas fa-trash"></i>
                        Delete
                    </button>
                </div>
            `;
            
            backupHistory.insertBefore(newBackupItem, backupHistory.firstChild);
        }

        // Set default date values
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const lastMonth = new Date();
            lastMonth.setMonth(lastMonth.getMonth() - 1);
            const lastMonthDate = lastMonth.toISOString().split('T')[0];
            
            document.getElementById('dateTo').value = today;
            document.getElementById('dateFrom').value = lastMonthDate;
        });
    </script>
</body>
</html>