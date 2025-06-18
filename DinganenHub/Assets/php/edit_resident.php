<?php
require_once 'Config.php';

header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit(0);
}

$response = ['success' => false, 'message' => ''];

try {
    $conn = getDBConnection();
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
        // Fetch resident data for editing
        $id = intval($_GET['id']);
        
        $sql = "SELECT * FROM residents WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $resident = $result->fetch_assoc();
            $response['success'] = true;
            $response['data'] = $resident;
        } else {
            $response['message'] = 'Resident not found';
        }
        
        $stmt->close();
        
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Update resident data
        $id = intval($_POST['id']);
        
        // Validate required fields
        $required_fields = ['first_name', 'last_name', 'date_of_birth', 'gender', 'civil_status'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Field '$field' is required");
            }
        }
        
        // Prepare update query
        $sql = "UPDATE residents SET 
                first_name = ?,
                middle_name = ?,
                last_name = ?,
                suffix = ?,
                date_of_birth = ?,
                gender = ?,
                civil_status = ?,
                phone_number = ?,
                educational_attainment = ?,
                occupation = ?,
                sitio = ?,
                household_id = ?,
                relationship_to_household_head = ?
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        
        // Get values with defaults for optional fields
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'] ?? '';
        $last_name = $_POST['last_name'];
        $suffix = $_POST['suffix'] ?? '';
        $date_of_birth = $_POST['date_of_birth'];
        $gender = $_POST['gender'];
        $civil_status = $_POST['civil_status'];
        $phone_number = $_POST['phone_number'] ?? '';
        $educational_attainment = $_POST['educational_attainment'] ?? '';
        $occupation = $_POST['occupation'] ?? '';
        $sitio = $_POST['sitio'] ?? '';
        $household_id = !empty($_POST['household_id']) ? intval($_POST['household_id']) : null;
        $relationship_to_household_head = $_POST['relationship_to_household_head'] ?? '';
        
        // Bind parameters
        $stmt->bind_param("sssssssssssiis",
            $first_name,
            $middle_name,
            $last_name,
            $suffix,
            $date_of_birth,
            $gender,
            $civil_status,
            $phone_number,
            $educational_attainment,
            $occupation,
            $sitio,
            $household_id,
            $relationship_to_household_head,
            $id
        );
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Resident updated successfully';
            
            // Log the activity (if you have an activity log table)
            // $log_sql = "INSERT INTO activity_logs (user_id, action, description, created_at) 
            //             VALUES (?, 'UPDATE', ?, NOW())";
        } else {
            throw new Exception('Failed to update resident: ' . $stmt->error);
        }
        
        $stmt->close();
    }
    
    $conn->close();
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>