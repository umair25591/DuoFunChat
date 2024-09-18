<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duo Fun Chat</title>
    <link rel="stylesheet" href="./source/css/loader.css">
    <link rel="stylesheet" href="./source/css/rock_paper_scissor.css">
    <link rel="stylesheet" href="./source/css/style.css">
    <link rel="stylesheet" href="./source/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css"
        integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous" />
</head>
<body style="background-color: hsl(207, 100%, 89%);">
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

    <div class="container" style="margin-top:130px">
        <h1>Rock-Paper-Scissors</h1>
        <div class="wrapper">
            <div class="message winner" id="winner">Result Here</div>
            <div class="player">
                <div class="top d-flex justify-content-center">
                    <button class="player-option option-rock choice" data-choice="rock">
                        <div class="rock-wrapper">
                            <i class="far fa-hand-rock game_icon"></i>
                        </div>
                    </button>
                    <button class="player-option option-paper choice" data-choice="paper">
                        <i class="far fa-hand-paper game_icon"></i>
                    </button>
                </div>
                <div class="bottom d-flex justify-content-center">
                    <button class="player-option option-scissor choice" data-choice="scissors">
                        <i class="far fa-hand-scissors game_icon"></i>
                    </button>
                </div>

            </div>
            <div class="computer">
                <p class="text-center op-select"></p>
                <div class="computer-option" id="opponent_option">
                    <i class="fas fa-question game_icon"></i>
                </div>
            </div>

        </div>
        <div class="last_box d-flex justify-content-between">
            <div class="game_buttons">
                <button class="next-round" id="playagain">Play Again</button>
                <button class="next-round" id="new-chat">New Game</button>
                <button class="next-round" id="end-chat">End Game</button>
            </div>
            <div class="show_data align-items-center">
                <p id="gamestatus" class="m-0 mt-3"></p>
                <p id="wait" class="m-0"></p>
            </div>
        </div>
    </div>
</body>

</html>
<script src="./source/bootstrap/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
<script type="module" src="./source/js/script.js"></script>
<script type="module" src="./source/js/rock_paper_game_test.js"></script>