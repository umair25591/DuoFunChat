<?php
session_start();

$response = [
    'userId' => isset($_SESSION['userId']) ? $_SESSION['userId'] : null,
    'email' => isset($_SESSION['email']) ? $_SESSION['email'] : null
];

// session_unset();
// session_destroy();
echo json_encode($response);
exit();
?>