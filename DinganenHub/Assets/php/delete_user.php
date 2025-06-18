<?php
// delete_user.php
require_once 'Config.php';

// Set JSON header
header('Content-Type: application/json');

// Initialize response
$response = array('success' => false, 'message' => '');

try {
    // Check if ID is provided
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        throw new Exception('User ID is required');
    }
    
    $userId = intval($_POST['id']);
    
    // Get database connection
    $conn = getDBConnection();
    
    // First, check if user exists
    $checkSql = "SELECT id FROM users WHERE id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('User not found');
    }
    
    // Check if user is trying to delete their own account (optional)
    // You might want to prevent this based on your requirements
    session_start();
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $userId) {
        throw new Exception('You cannot delete your own account');
    }
    
    // Check if this is the last admin (optional security measure)
    $adminCheckSql = "SELECT COUNT(*) as admin_count FROM users WHERE Role = 'Admin'";
    $adminResult = $conn->query($adminCheckSql);
    $adminData = $adminResult->fetch_assoc();
    
    if ($adminData['admin_count'] == 1) {
        $roleSql = "SELECT Role FROM users WHERE id = ?";
        $roleStmt = $conn->prepare($roleSql);
        $roleStmt->bind_param("i", $userId);
        $roleStmt->execute();
        $roleResult = $roleStmt->get_result();
        $userData = $roleResult->fetch_assoc();
        
        if ($userData['Role'] == 'Admin') {
            throw new Exception('Cannot delete the last admin account');
        }
    }
    
    // Proceed with deletion
    $deleteSql = "DELETE FROM users WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $userId);
    
    if ($deleteStmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'User deleted successfully';
        
        // Optional: Log this action
        // logActivity('User deleted', 'User ID: ' . $userId);
        
    } else {
        throw new Exception('Failed to delete user: ' . $conn->error);
    }
    
    // Close connections
    $checkStmt->close();
    $deleteStmt->close();
    $conn->close();
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

// Send JSON response
echo json_encode($response);
?>