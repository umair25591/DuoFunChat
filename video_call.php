<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duo Fun Chat</title>
    <link rel="stylesheet" href="./source/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./source/css/style.css">
    <link rel="stylesheet" href="./source/css/video_call.css">
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

    <div class="main-notification-box"></div>

    <?php include 'components/_navbar.php'; ?>


    <div id="video-container">
        <video id="localVideo" autoplay muted></video>
        <video id="remoteVideo" autoplay></video>
        <div class="action_tab">
            
        </div>
        <div class="action_tab">
            <div class="center_box">
            <div class="friend_box">
            <img src="./source/images/add.png" alt="" class="call_icon friend">
            <img src="./source/images/add-friend.png" alt="" class="call_icon add_friend">
            </div>
            <div class="speaker_box">
            <img src="./source/images/volume.png" alt="" class="call_icon speaker_mute">
            <img src="./source/images/speaker.png" alt="" class="call_icon speaker">
            </div>
            <div class="mic_box">
            <img src="./source/images/mic.png" alt="" class="call_icon mic">
            <img src="./source/images/mute.png" alt="" class="call_icon mic_mute">
            </div>
            <div class="call_box">
            <img src="./source/images/start_call.png" alt="" class="call_icon" id="startCall">
            <img src="./source/images/hung_up.png" alt="" class="call_icon" id="endCall">
            </div>
            </div>
        </div>
    </div>

</body>

</html>
<script src="./source/bootstrap/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uuid/8.3.2/uuid.min.js"></script>
<script type="module" src="./source/js/video_Call.js"></script>
<script type="module" src="./source/js/script.js"></script>