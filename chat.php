<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duo Fun Chat</title>
    <link rel="stylesheet" href="./source/css/chat.css">
    <link rel="stylesheet" href="./source/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./source/css/style.css">
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

            <div data-text="LOADING..." class="text1"></div>
        </div>
    </div>
    <div class="main-notification-box"></div>
    <?php include 'components/_navbar.php'; ?>

    <div class="container-fluid temp">
        <div class="main-chat-box" id="chat-box">
            <p id="p1" class="text-center">Click New Chat To Start</p>
            <div id="chat">
                <!-- <div class="chat-2 d-flex justify-content-start"><div class="message-box d-flex justify-content-start" style="width: 50%;"><p class="m-0 text partner">helloasdasdasdasdasdjhaksjdhasdasdasdasdasdasdasdasdasdghjagsjhgdhgajhsgdahjsgdjahsdghjagsdhjagshdghsagdhjagshjdahsdkjahsdkjahsjdhajkshdjkahsjkdhjkahsdjahsjdhajkshdjkahsdjkhsdghfasdtysuydfsfjandjsfhdfoiuwoierwerwe1</p></div></div> -->
                <!-- <div class="chat-1 d-flex justify-content-end"><div class="message-box d-flex justify-content-end" style="width: 50%;"><p class="m-0 text me">helloasdasdasdasdasdjhaksjdhkjahsdkjasdasdasdasdasdasdasdasdasdasdasdasdahsjdhajkshdjkahsjkdhjkahsdjahsjdhajkshdjkahsdjkhsdghfasdtysuydfsfjandjsfhdfoiuwoierwerwe1</p></div></div> -->
            </div>
            <div class="input-fields">
                <button id="new-chat">New Chat</button>
                <button id="end-chat">End Chat</button>
                <input type="text" id="message" required readonly>
                <button id="send">Send</button>
            </div>
            <div class="menu">
                <i class="fa-solid fa-ellipsis-vertical menu_icon"></i>
                <div class="dropdown-menu-custom1" id="dropdownMenu1">
                    <?php
                    if(isset($_SESSION['email']) && isset($_SESSION['userId'])){
                        echo '<a href="#" id="addFriendbtn">Add as a friend</a><a href="#" id="reportbtn">Report</a>';
                    }
                    ?>
                    
                </div>
            </div>
        </div>

    </div>
</body>

</html>
<script src="./source/bootstrap/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="crossorigin="anonymous"></script>
<script type="module" src="./source/js/chat.js"></script>
<script type="module" src="./source/js/script.js"></script>
