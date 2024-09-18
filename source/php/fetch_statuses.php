<?php
require_once 'db-connection.php';
session_start();

if(isset($_SESSION['userId'])){
    $user_id = $_SESSION['userId'];

    $query = "SELECT users.*
              FROM users
              JOIN friend_requests 
              ON (users.u_id = friend_requests.requester_id AND friend_requests.requestee_id = ?)
              OR (users.u_id = friend_requests.requestee_id AND friend_requests.requester_id = ?)
              WHERE friend_requests.req_status = 'accepted'";
    
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ss", $user_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $friends = [];
        while ($row = $result->fetch_assoc()) {
            $friends[] = $row;
        }
        
        echo json_encode($friends);
    
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement.']);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
}
