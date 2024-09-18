<?php

require_once 'db-connection.php';
session_start();

if(isset($_SESSION['userId'])){
    $user_id = $_SESSION['userId'];
    $friend_id = $_POST['friend_id'];
    $last_timestamp = $_POST['last_timestamp'];

    // Prepare the base query
    $query = "SELECT messages.*, users.u_name
              FROM messages
              INNER JOIN users ON messages.sender_id = users.u_id
              WHERE ((messages.sender_id = ? AND messages.receiver_id = ?) 
                 OR (messages.sender_id = ? AND messages.receiver_id = ?))";

    // Add the condition for fetching only new messages if last_timestamp is provided
    if (!empty($last_timestamp)) {
        $query .= " AND messages.created_at > ?";
    }

    // Order the results
    $query .= " ORDER BY messages.created_at ASC";
    
    $stmt = $conn->prepare($query);

    // Bind parameters
    if (!empty($last_timestamp)) {
        $stmt->bind_param("sssss", $user_id, $friend_id, $friend_id, $user_id, $last_timestamp);
    } else {
        $stmt->bind_param("ssss", $user_id, $friend_id, $friend_id, $user_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    
    echo json_encode($messages);
    
    $stmt->close();
    $conn->close();
}
?>
