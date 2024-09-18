<?php
// send_message.php
include 'db-connection.php';
session_start();

if(isset($_SESSION['userId'])){
    $sender_id = $_SESSION['userId'];
    $receiver_id = $_POST['receiver_id'];
    $message = $_POST['message'];
    
    $query = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $sender_id, $receiver_id, $message);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send message']);
    }
    
    $stmt->close();
    $conn->close();
}
?>
