<?php
header('Content-Type: application/json');
require_once 'Config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors directly, but log them

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
        $conn = getDBConnection();
        
        if (!$conn) {
            throw new Exception('Database connection failed');
        }
        
        $id = (int)$_POST['id'];
        
        // First check if the record exists
        $checkSql = "SELECT Id FROM blotter WHERE Id = ?";
        $checkStmt = $conn->prepare($checkSql);
        
        if (!$checkStmt) {
            throw new Exception('Prepare statement failed: ' . $conn->error);
        }
        
        $checkStmt->bind_param("i", $id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult && $checkResult->num_rows > 0) {
            // Record exists, proceed with deletion
            $deleteSql = "DELETE FROM blotter WHERE Id = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            
            if (!$deleteStmt) {
                throw new Exception('Prepare delete statement failed: ' . $conn->error);
            }
            
            $deleteStmt->bind_param("i", $id);
            
            if ($deleteStmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Record deleted successfully'
                ]);
            } else {
                throw new Exception('Error deleting record: ' . $deleteStmt->error);
            }
            
            $deleteStmt->close();
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Record not found'
            ]);
        }
        
        $checkStmt->close();
        $conn->close();
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid request'
        ]);
    }
} catch (Exception $e) {
    // Log the error
    error_log('Delete blotter error: ' . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage()
    ]);
}
?>