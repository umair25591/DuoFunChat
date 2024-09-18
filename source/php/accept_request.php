<?php
require_once 'db-connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $req_id = $_POST['req_id'];
    
    $checkQuery = "SELECT * FROM friend_requests WHERE req_id = ? AND req_status = 'pending'";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("i", $req_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        
        $acceptQuery = "UPDATE friend_requests SET req_status = 'accepted' WHERE req_id = ?";
        $stmt = $conn->prepare($acceptQuery);
        $stmt->bind_param("i", $req_id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to accept request']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Request not found']);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
