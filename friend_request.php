<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duo Fun Chat</title>
    <link rel="stylesheet" href="./source/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./source/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="./source/css/friend_request.css">
    <link rel="stylesheet" href="./source/css/loader.css">

</head>

<body>

<div id="loader" class="loader-overlay">
        <div id="wifi-loader">
            <svg viewBox="0 0 86 86" class="circle-outer">
                <circle r="40" cy="43" cx="43" class="back"></circle>
                <circle r="40" cy="43" cx="43" class="front"></circle>
                <circle r="40" cy="43" cx="43" class="new"></circle>
            </svg>
            <svg viewBox="0 0 60 60" class="circle-middle">
                <circle r="27" cy="30" cx="30" class="back"></circle>
                <circle r="27" cy="30" cx="30" class="front"></circle>
            </svg>

            <div data-text="LOADING..." class="text"></div>
        </div>
    </div>

    <?php include 'components/_navbar.php'; ?>

    <div class="container-fluid parent-box">
        <main>
            <!-- <img class="background-1"
                src="https://github.com/malunaridev/Challenges-iCodeThis/blob/master/5-friend-request/assets/background-1.png?raw=true" />
            <img class="background-2"
                src="https://github.com/malunaridev/Challenges-iCodeThis/blob/master/5-friend-request/assets/background-2.png?raw=true" /> -->
            <div id="header">
                <h1>Friends Requests</h1>
            </div>

            <div id="friends-request">

                <div class="profile-info" id="requests-body">


                </div>

            </div>

        </main>
    </div>

    <!-- <div class="container" style="margin-top:200px;">
  <table class="table">
    <thead>
      <tr>
        <th>Friend Request</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="requests-body">

    </tbody>
  </table>a
</div> -->

    <script src="./source/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uuid/8.3.2/uuid.min.js"></script>
    <script src="./source/js/script.js"></script>

    <script>
    $(document).ready(function() {
        function loadPendingRequests() {
            $.ajax({
                url: './source/php/fetch_pending_requests.php',
                method: 'POST',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    var tableBody = $('#requests-body');
                    tableBody.empty();

                    if (data.length > 0) {
                        data.forEach(function(request) {
                            var row = `<div class="profile-name d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                        
                        <img class="profile-picture"
                            src="./source/images/${request.u_profile_pic}"
                            alt="profile picture" />
                            <a href="profile.php?id=${request.u_id}">
                            <h3>${request.u_name}</h3>
                            </a>
                            <!-- <h4>8 mutual friends</h4> -->
                        </div>
                        <div>
                        <button class="confirm" data-id="${request.req_id}" id="accept_btn">Confirm</button>
                        <button class="delete" data-id="${request.req_id}" id="reject_btn">Delete</button>
                        </div>
                    </div>`;
                            tableBody.append(row);
                        });
                    } else {
                        tableBody.append('<tr><td colspan="2">No pending requests</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching pending requests:", error);
                    $('#requests-body').append(
                        '<tr><td colspan="2">Error fetching requests</td></tr>');
                }
            });
        }

        loadPendingRequests();

        $(document).on('click', '#accept_btn', function() {
            var req_id = $(this).data('id');
            $.ajax({
                url: './source/php/accept_request.php',
                method: 'POST',
                data: {
                    req_id: req_id
                },
                success: function(response) {
                    console.log("Request accepted:", response);
                    loadPendingRequests();
                },
                error: function(xhr, status, error) {
                    console.error("Error accepting request:", error);
                }
            });
        });


        $(document).on('click', '#reject_btn', function() {
            var req_id = $(this).data('id');
            $.ajax({
                url: './source/php/delete_request.php',
                method: 'POST',
                data: {
                    req_id: req_id
                },
                success: function(response) {
                    console.log("Request accepted:", response);
                    loadPendingRequests();
                },
                error: function(xhr, status, error) {
                    console.error("Error accepting request:", error);
                }
            });
        });
    });
    </script>
</body>

</html>