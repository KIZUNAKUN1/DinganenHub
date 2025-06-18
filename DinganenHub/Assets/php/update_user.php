<?php
require_once 'Config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn = getDBConnection();
        
        // Get and sanitize POST data
        $id = $_POST['id'] ?? '';
        $fullname = $_POST['fullname'] ?? '';
        $birthday = $_POST['birthday'] ?? '';
        $age = $_POST['age'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? '';
        
        // Validate required fields
        if (empty($id) || empty($fullname) || empty($birthday) || empty($age) || empty($username) || empty($role)) {
            echo json_encode(['success' => false, 'message' => 'All fields except password are required']);
            exit;
        }
        
        // Check if username already exists for other users
        $checkSql = "SELECT id FROM users WHERE Username = ? AND id != ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("si", $username, $id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Username already exists']);
            exit;
        }
        
        // Build SQL query based on whether password is provided
        if (!empty($password)) {
            // Update with password
            $sql = "UPDATE users SET Fullname=?, Birthday=?, Age=?, Username=?, Password=?, Role=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("ssisssi", $fullname, $birthday, $age, $username, $hashedPassword, $role, $id);
        } else {
            // Update without password
            $sql = "UPDATE users SET Fullname=?, Birthday=?, Age=?, Username=?, Role=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssissi", $fullname, $birthday, $age, $username, $role, $id);
        }
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true, 'message' => 'User updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No changes were made or user not found']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating user: ' . $stmt->error]);
        }
        
        $stmt->close();
        $checkStmt->close();
        $conn->close();
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>