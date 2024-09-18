<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duo Fun Chat</title>

    <link rel="stylesheet" href="./source/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./source/css/style.css">
    <link rel="stylesheet" href="./source/css/friends.css">
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


    <div class="container-friends clearfix">
    <button class="toggle-friends-list">Show Friends</button>
        <div class="people-list" id="people-list">
            <div class="search">
                <input type="text" placeholder="search" />
                <i class="fa fa-search"></i>
            </div>
            <ul class="list" id="friends-list">

            </ul>
        </div>

        <div class="chat">
            <div class="chat-header clearfix">
                <a href="#" id="friend_profile">
                <img src="./source/images/profile_img.webp" id="friend_img" alt="avatar" width="50px" height="50px" style="border-radius:50px;"/>
                </a>

                <div class="chat-about">
                    <div class="chat-with">Click to start chat</div>
                </div>
            </div>

            <div class="chat-history">
                <ul id="messages">
                    <p>Click on chat to start chat</p>
                </ul>

            </div>

            <div class="chat-message clearfix">
                <textarea name="message-to-send" id="message-to-send" placeholder="Type your message"
                    rows="3"></textarea>

                    <select id="option_select">
                        <option selected disabled>Open this select menu</option>
                        <option value="videoCall">Video Call</option>
                        <option value="rpsGame">Rock Paper Sessior Game</option>
                    </select>
                    <button id="option_send_btn">Send</button>

                <button id="send_btn">Send</button>
            </div>
        </div>
    </div>
</body>

</html>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="./source/bootstrap/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uuid/8.3.2/uuid.min.js"></script>
<script src="./source/js/script.js"></script>
<script type="module" src="./source/js/friends.js"></script>