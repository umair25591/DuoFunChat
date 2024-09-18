<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'db-connection.php';

    if (isset($_POST['userId']) && isset($_POST['partnerId'])) {
        $requester = $_POST['userId'];
        $requestee = $_POST['partnerId'];

        if($requester == $requestee){
            echo json_encode(['message' => 'Can not add yourself']);
            exit;
        }

        if (!empty($requester) && !empty($requestee)) {
            // Prepare the statement to check if the request already exists
            $checkReq = $conn->prepare("SELECT COUNT(*) FROM friend_requests WHERE (requester_id = ? AND requestee_id = ?) OR (requester_id = ? AND requestee_id = ?)");
            
            if ($checkReq) {
                $checkReq->bind_param("ssss", $requester, $requestee, $requestee, $requester);
                $checkReq->execute();
                $checkReq->bind_result($count);
                $checkReq->fetch();
                $checkReq->close();

                if ($count > 0) {
                    echo json_encode(['message' => 'Request already exists !']);
                } else {
                    // Prepare the statement to insert the new friend request
                    $sendReq = $conn->prepare("INSERT INTO friend_requests (requester_id, requestee_id) VALUES (?, ?)");
                    
                    if ($sendReq) {
                        $sendReq->bind_param("ss", $requester, $requestee);
                        
                        if ($sendReq->execute()) {
                            http_response_code(200);
                            echo json_encode(['message' => 'Friend request sent successfully.']);
                        } else {
                            error_log("Error executing the query: " . $sendReq->error);
                            http_response_code(500);
                            echo json_encode(['message' => 'Error executing the query.']);
                        }

                        $sendReq->close();
                    } else {
                        error_log("Error preparing the query: " . $conn->error);
                        http_response_code(500);
                        echo json_encode(['message' => 'Error preparing the query.']);
                    }
                }
            } else {
                error_log("Error preparing the check query: " . $conn->error);
                http_response_code(500);
                echo json_encode(['message' => 'Error preparing the check query.']);
            }
        } else {
            error_log("Invalid input: requester - $requester, requestee - $requestee");
            http_response_code(400);
            echo json_encode(['message' => 'Invalid input.']);
        }
    } else {
        error_log("Missing required parameters.");
        http_response_code(400);
        echo json_encode(['message' => 'Missing required parameters.']);
    }

    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed.']);
}
