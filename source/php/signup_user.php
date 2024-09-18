<?php
session_start();
require_once 'db-connection.php';
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function generateUuidV4() {
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function generateUniqueToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $userId = generateUuidV4();
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $profilePic = $_FILES['profile_pic'];

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($profilePic['name'])) {
        $response['status'] = 'error';
        $response['message'] = "All fields are required.";
        echo json_encode($response);
        exit();
    }

    if ($password !== $confirm_password) {
        $response['status'] = 'error';
        $response['message'] = "Passwords do not match.";
        echo json_encode($response);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['status'] = 'error';
        $response['message'] = "Invalid email format.";
        echo json_encode($response);
        exit();
    }

    // Handle profile picture upload
    $targetDir = "../images/";
    $fileName = basename($profilePic["name"]);
    $targetFile = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($profilePic["tmp_name"]);
    if ($check === false) {
        $response['status'] = 'error';
        $response['message'] = "File is not an image.";
        echo json_encode($response);
        exit();
    }

    // Check file size (5MB maximum)
    if ($profilePic["size"] > 5000000) {
        $response['status'] = 'error';
        $response['message'] = "Sorry, your file is too large.";
        echo json_encode($response);
        exit();
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $response['status'] = 'error';
        $response['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        echo json_encode($response);
        exit();
    }

    if (!move_uploaded_file($profilePic["tmp_name"], $targetFile)) {
        $response['status'] = 'error';
        $response['message'] = "Sorry, there was an error uploading your file.";
        echo json_encode($response);
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT u_id FROM users WHERE u_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response['status'] = 'error';
        $response['message'] = "Email already in use.";
        echo json_encode($response);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO users (u_id, u_name, u_email, u_password, u_profile_pic, u_confirmation) VALUES (?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("sssss", $userId, $name, $email, $hashed_password, $fileName);

    if ($stmt->execute()) {
        $confirmation_code = generateUniqueToken();
        $stmt = $conn->prepare("INSERT INTO email_confirmation (user_id, confirmation_code) VALUES (?, ?)");
        $stmt->bind_param("ss", $userId, $confirmation_code);
        if ($stmt->execute()) {
            $mail = new PHPMailer(true);
            $confirmation_link = "https://192.168.1.101.com/ChatApp/source/php/confirm-email.php?token=" . $confirmation_code;

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'muhammadumair25591@gmail.com';
                $mail->Password = 'plef baul riyx bkvq';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('duofun@chat.com', 'Duo Fun Chat');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Email Confirmation';
                $mail->Body = "Please click the following link to confirm your email address: <a href='$confirmation_link'>$confirmation_link</a>";
                $mail->AltBody = "Please click the following link to confirm your email address: $confirmation_link";

                $mail->send();

                $response['status'] = 'success';
                $response['message'] = "Account Created. Please Confirm your email to login";
                echo json_encode($response);
                exit();
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                echo json_encode($response);
                exit();
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = "An error occurred. Please try again.";
            echo json_encode($response);
            exit();
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = "An error occurred. Please try again.";
        echo json_encode($response);
        exit();
    }
} else {
    $response['status'] = 'error';
    $response['message'] = "Invalid request method";
    echo json_encode($response);
    exit();
}
?>
