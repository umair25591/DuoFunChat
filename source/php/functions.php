<?php
function updateStatusInDatabase($conn, $userId, $status){
    if (!empty($userId) && !empty($status)) {
        $validStatuses = ['online', 'offline', 'away', 'busy'];
        if (in_array($status, $validStatuses)) {
            $sql = 'UPDATE users SET status = ? WHERE u_id = ?';
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("ss", $status, $userId);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        } else {
            echo "Invalid status: $status";
        }
    }
}
?>
