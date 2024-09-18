<?php

require_once 'db-connection.php';
session_start();

if(isset($_SESSION['userId'])){
    $user_id = $_SESSION['userId'];

    $query = "SELECT 
    messages.sender_id,
    users.u_name,
    COUNT(messages.msg_id) AS message_count
        FROM 
            messages
        INNER JOIN 
            users ON messages.sender_id = users.u_id
        WHERE 
            messages.receiver_id = ? AND msg_status = 'unread'
        GROUP BY 
            messages.sender_id, users.u_name
        ";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_id);

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
