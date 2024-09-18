<?php
session_start();
if(isset($_SESSION['userId'])){

require_once 'db-connection.php';

$current_user_id = $_SESSION['userId'];
$friend_id = $_POST['friend_id'];

// Mark messages as read
$update_query = "UPDATE messages SET msg_status = 'read' WHERE receiver_id = '$current_user_id' AND sender_id = '$friend_id' AND msg_status = 'unread'";
    if (mysqli_query($conn, $update_query)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
}
else{
    http_response_code(404);
    echo "user_not_found";
}
?>
