<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duo Fun Chat</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/confirm_email.css">
</head>

<body>
    <div class="container-fluid main-box d-flex justify-content-center align-items-center">
        <div class="confirm_box">
            <?php
session_start();
require_once 'db-connection.php';

if (isset($_GET['token'])) {
    $confirmation_token = $_GET['token'];

    // Validate the token
    $stmt = $conn->prepare("SELECT user_id FROM email_confirmation WHERE confirmation_code = ?");
    $stmt->bind_param("s", $confirmation_token);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $user_id = $row['user_id'];
        $stmt = $conn->prepare("UPDATE users SET u_confirmation = 1 WHERE u_id = ?");
        $stmt->bind_param("s", $user_id);

        if ($stmt->execute()) {
            $stmt = $conn->prepare("DELETE FROM email_confirmation WHERE confirmation_code = ?");
            $stmt->bind_param("s", $confirmation_token);
            $stmt->execute();

            echo '<h1 class="text-center heading">Email Confirmed!</h1>
                  <p>Congratulations! Your email has been confirmed and your account is ready. Please go back to the login page.</p>
                  <div class="btn_box d-flex justify-content-end">
                      <button onclick="window.location.href=\'../../login.php\'">Back to login</button>
                  </div>';
        }
        else {
            echo '<h1 class="text-center heading">Try Again!</h1>
                  <p>An error occurred. Please try again.</p>
                  <div class="btn_box d-flex justify-content-end">
                      <button onclick="window.location.href=\'../../signup.php\'">Back to Signup</button>
                  </div>';
        }
    }
    else {
        echo '<h1 class="text-center heading">Invalid Token</h1>
                  <p>Invalid or expired confirmation token.</p>
                  <div class="btn_box d-flex justify-content-end">
                  </div>';
    }
}
else{
    echo '<h1 class="text-center heading">Invalid Token</h1>
                  <p>Invalid or expired confirmation token.</p>
                  <div class="btn_box d-flex justify-content-end">
                  </div>';
}
?>
        </div>
    </div>
</body>

</html>
<script src="../bootstrap/js/bootstrap.min.js"></script>