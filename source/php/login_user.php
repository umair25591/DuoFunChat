<?php
session_start();
require_once 'db-connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $find_user = "SELECT * FROM users WHERE u_email = ?";
    $stmt = $conn->prepare($find_user);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        if (password_verify($password, $data['u_password'])) {
            if ($data['u_confirmation'] !== 0) {
                $_SESSION['email'] = $email;
                $_SESSION['userId'] = $data['u_id'];
                echo json_encode(['status' => 'success', 'message' => 'Login successful']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Email not confirmed']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Wrong password']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Account not found']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
