<?php
require_once 'db-connection.php';

// Check if 'id' parameter is present in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $userId = $_GET['id'];

    // Prepare and execute the query
    $query = $conn->prepare("SELECT u_name, u_email, u_profile_pic, u_age, u_bio, status FROM users WHERE u_id = ?");
    
    if ($query) {
        $query->bind_param("s", $userId);
        $query->execute();
        $result = $query->get_result();
        $user = $result->fetch_assoc();
        
        if ($user) {
            // User found, pass user data to the frontend
            $profileImage = !empty($user['profile_image']) ? $user['profile_image'] : 'default-profile.png';
            $user['profile_image'] = "./source/images/" . $profileImage; // Adjust path as needed
            
            echo json_encode($user);
        } else {
            // User not found
            echo json_encode(['error' => 'User not found']);
        }

        $query->close();
    } else {
        // Query preparation failed
        echo json_encode(['error' => 'Error preparing query']);
    }
} else {
    // No ID provided
    echo json_encode(['error' => 'No user ID provided']);
}

$conn->close();
?>
