<?php
require_once 'Config.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        throw new Exception('Resident ID is required');
    }
    
    $conn = getDBConnection();
    $id = intval($_POST['id']);
    
    // First check if resident exists
    $check_sql = "SELECT id FROM residents WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Resident not found');
    }
    $check_stmt->close();
    
    // Delete the resident
    $delete_sql = "DELETE FROM residents WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $id);
    
    if ($delete_stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Resident deleted successfully';
        
        // Optional: Log the deletion
        // $log_sql = "INSERT INTO activity_logs (user_id, action, description, created_at) 
        //             VALUES (?, 'DELETE', ?, NOW())";
    } else {
        throw new Exception('Failed to delete resident');
    }
    
    $delete_stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>