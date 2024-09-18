<?php
require_once 'db-connection.php';

session_start();
$userId = $_SESSION['userId'];

if ($userId) {
    // Prepare the query to fetch pending friend requests
    $query = $conn->prepare("SELECT fr.*, u.u_id, u.u_name, u.u_profile_pic
    FROM friend_requests AS fr
    JOIN users AS u ON fr.requester_id = u.u_id
    WHERE fr.requestee_id = ? AND fr.req_status = 'pending'
    ORDER BY fr.req_id DESC");


    if ($query) {
        $query->bind_param("s", $userId);
        $query->execute();
        $result = $query->get_result();

        // Fetch all results as an associative array
        $requests = $result->fetch_all(MYSQLI_ASSOC);

        // Return the results as JSON
        echo json_encode($requests);
    } else {
        echo json_encode([]);
    }

    $query->close();
} else {
    echo json_encode([]);
}

$conn->close();
?>
