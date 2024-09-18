<?php

require_once 'db-connection.php';
session_start();

if (isset($_SESSION['userId'])) {
    $user_id = $_SESSION['userId'];

    $query = "SELECT COUNT(msg_id) AS total_unread_count,
                     MAX(messages.msg_id) AS newest_message_id
              FROM messages
              WHERE receiver_id = ? AND msg_status = 'unread'";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_id);

    $stmt->execute();
    $result = $stmt->get_result();
    
    // Fetch the count
    $count = $result->fetch_assoc();
    
    echo json_encode($count);
    
    $stmt->close();
    $conn->close();
}
?>
