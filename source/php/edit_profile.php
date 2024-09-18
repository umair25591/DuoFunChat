<?php
session_start();
require_once 'db-connection.php'; // Include your database connection script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['userId']; // Assuming user ID is stored in session after login
    $u_name = $_POST['u_name'] ?? null;
    $u_age = $_POST['u_age'] ?? null;
    $u_bio = $_POST['u_bio'] ?? null;
    $profile_pic = $_FILES['profile_pic'] ?? null;

    // Handle profile picture upload
    $profile_pic_url = null;
    if ($profile_pic && $profile_pic['error'] == 0) {
        $upload_dir = '../images/'; // Directory where images will be uploaded
        $upload_file = $upload_dir . basename($profile_pic['name']);
        $upload_ok = 1;
        $image_file_type = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        $check = getimagesize($profile_pic['tmp_name']);
        if ($check !== false) {
            $upload_ok = 1;
        } else {
            $upload_ok = 0;
        }

        // Check file size (limit to 5MB)
        if ($profile_pic['size'] > 5000000) {
            $upload_ok = 0;
        }

        // Allow certain file formats
        if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg" && $image_file_type != "gif") {
            $upload_ok = 0;
        }

        // Check if $upload_ok is set to 0 by an error
        if ($upload_ok == 0) {
            // Handle error (you can customize the error message)
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($profile_pic['tmp_name'], $upload_file)) {
                $profile_pic_url = $upload_file;
            } else {
                $profile_pic_url = null;
            }
        }
    }

    // Prepare the SQL update statement dynamically based on provided fields
    $update_fields = [];
    $params = [];

    if ($u_name) {
        $update_fields[] = "u_name = ?";
        $params[] = $u_name;
    }
    if ($u_age) {
        $update_fields[] = "u_age = ?";
        $params[] = $u_age;
    }
    if ($u_bio) {
        $update_fields[] = "u_bio = ?";
        $params[] = $u_bio;
    }
    if ($profile_pic_url) {
        $update_fields[] = "u_profile_pic = ?";
        $params[] = $profile_pic_url;
    }

    if (count($update_fields) > 0) {
        $params[] = $user_id;
        $stmt = $conn->prepare("UPDATE users SET " . implode(', ', $update_fields) . " WHERE u_id = ?");
        $stmt->bind_param(str_repeat('s', count($update_fields)) . 'i', ...$params);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Profile updated successfully.";
            header('Location: ../../profile.php?id=' . $user_id);
        } else {
            $_SESSION['error'] = "There was an error updating your profile. Please try again.";
            header('Location: ../../profile.php?id=' . $user_id);
        }

        $stmt->close();
    }

    $conn->close();
} else {
    header('Location: profile.php');
}
?>
