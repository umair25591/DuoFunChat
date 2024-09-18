import { GetAllMessageCountForNotification } from './functions.js';


var connectionStatus = {
    isConnected: false
};
var Gamestatus = {
    game: 'pause'
};
var conn;
var GameChoices = {
    myChoice: null,
    opponentChoice: null
};
var roomId = getRoomIdFromUrl();
var loginStatus = {
    login: false,
};
var userId = null;


function getRoomIdFromUrl() {
    const params = new URLSearchParams(window.location.search);
    return params.get('roomId');
}


$.ajax({
    url: "./source/php/checkLogin.php",
    type: "GET",
    dataType: "json",
    success: function (response) {
        // console.log(response);
        if (response.userId) {
            setInterval(GetAllMessageCountForNotification, 5000);
            loginStatus.login = true;
            userId = response.userId;
            if (roomId !== null) {
                ConnectToStrangers(roomId);
            }
        } else {
            loginStatus.login = false;
            userId = null;
            console.log("Not logged in");
        }
    },
    error: function (xhr, status, error) {
        console.error("Error checking login status:", error);
        $("#status").text("Error checking login status");
    },
});


function ConnectToStrangers(roomId) {

    if (roomId) {
        if (userId) {
            conn = new WebSocket(`wss://duofunchat.com:8080?type=rps&roomId=${roomId}&userId=${encodeURIComponent(userId)}`);
        }
        else {
            alert('please login first');
            return;
        }
    }
    else {
        conn = new WebSocket('wss://duofunchat.com:8080?type=rps');
    }
    conn.onopen = function (event) {
        console.log('WebSocket connection established.');
        updateConnectionStatus("connecting");
        connectionStatus.isConnected = true;
    };

    conn.onclose = function (event) {
        updateConnectionStatus("disconnect");
        console.log('WebSocket connection closed.');
    };

    conn.onmessage = function (e) {
        console.log(e.data);
        const data = JSON.parse(e.data);
        if (data.choice) {
            $('#opponent_select').html("Opponent Selected!!!");
            GameChoices.opponentChoice = data.choice;
            checkGameResult();
        }
        else if (data.connection === "CoNneCtIoN ClOsEd") {
            ConnectionClose();
        }
        else if (data.playagain === "Opponent wants to play again") {
            $('#wait').html(data.playagain);
        }
        else if (data.action === "playagain") {
            updateConnectionStatus("play_again_connect");
        }
        else if (data.action === "paired") {
            updateConnectionStatus("game_connect");
            Gamestatus.game = "play";
        }
    };
}


function checkGameResult() {
    if (GameChoices.myChoice != null && GameChoices.opponentChoice != null) {
        const result = getResult(GameChoices.myChoice, GameChoices.opponentChoice);
        const resultText = (result === 'win') ? 'You win!' : (result === 'lose' ? 'You lose!' : 'It\'s a draw!');
        console.log(resultText);
        $('#winner').html(resultText).css("opacity", "1");

        // Update the opponent's choice display
        const opponentChoiceClass = {
            rock: 'rock',
            paper: 'paper',
            scissors: 'scissors'
        };

        $('#opponent_option').removeClass('rock paper scissors');
        $('#opponent_option').addClass(opponentChoiceClass[GameChoices.opponentChoice]);
        $('#opponent_option').html(`<i class="far fa-hand-${GameChoices.opponentChoice} game_icon"></i>`);
        $('.op-select').html(`Opponent Selected ${GameChoices.opponentChoice}`).show();

        updateConnectionStatus('result_announce');
        $('#playagain').show();
        $('#playagain').off('click').on('click', function () {
            if (connectionStatus.isConnected === true) {
                const PlayAgainMessage = {
                    playagain: "Opponent wants to play again"
                };
                conn.send(JSON.stringify(PlayAgainMessage));
                $('#playagain').hide();
            }
        });
    }
    else {
        console.log("Not enough Choices");
    }
}



function getResult($choice1, $choice2) {
    if ($choice1 === $choice2) {
        return 'draw';
    } else if (
        ($choice1 === 'rock' && $choice2 === 'scissors') ||
        ($choice1 === 'paper' && $choice2 === 'rock') ||
        ($choice1 === 'scissors' && $choice2 === 'paper')
    ) {
        return 'win';
    } else {
        return 'lose';
    }
}



function updateConnectionStatus(message) {
    if (message == 'game_connect') {
        $("#new-chat").hide()
        $("#end-chat").show()
        $('#playagain').hide()
        $('#gamestatus').html("Game Connected...");
        $('#wait').empty();
        $('#opponent_select').empty();
        connectionStatus.isConnected = true;
        Gamestatus.game = 'play'
        var button = document.querySelectorAll('.choice');
        button.forEach(function (btn) {
            btn.classList.remove('active');
        })
        $('.op-select').hide();
        $('#opponent_option').html(`<i class="fas fa-question game_icon"></i>`);
        $('#opponent_option').removeClass('rock paper scissors');
    }
    else if (message == 'connecting') {
        $("#new-chat").hide()
        $("#end-chat").show()
        $('#playagain').hide()
        $('#wait').empty();
        $('#opponent_select').empty();
        $('#gamestatus').html("Connecting...");
    }
    else if (message == 'play_again_connect') {
        $("#new-chat").hide()
        $("#end-chat").show()
        $('#playagain').hide()
        $('#winner').html("Result Here");
        $('#wait').empty();
        $('#opponent_select').empty();
        $('#gamestatus').html("Game Connnected with user again...");
        Gamestatus.game = 'play'
        var button = document.querySelectorAll('.choice');
        button.forEach(function (btn) {
            btn.classList.remove('active');
        })
        $('.op-select').hide();
        $('#opponent_option').html(`<i class="fas fa-question game_icon"></i>`);
        $('#opponent_option').removeClass('rock paper scissors');
    }
    else if (message == 'result_announce') {
        $('#playagain').hide();
        $('#gamestatus').html("Result Announced");
        Gamestatus.game = 'pause'
        GameChoices.myChoice = null;
        GameChoices.opponentChoice = null;
    }
    else if (message == 'game_end') {
        if (roomId === null) {
            $("#new-chat").show()
        }
        $("#end-chat").hide()
        $('#playagain').hide();
        $('#gamestatus').html("Game End...");
        $('#winner').empty();
        $('#wait').empty();
        $('#opponent_select').empty();
        connectionStatus.isConnected = false;
        GameChoices.myChoice = null;
        GameChoices.opponentChoice = null;
        Gamestatus.game = 'pause'
        var button = document.querySelectorAll('.choice');
        button.forEach(function (btn) {
            btn.classList.remove('active');
        })
        $('#winner').html("Result Here");
        $('.op-select').hide();
        $('#opponent_option').html(`<i class="fas fa-question game_icon"></i>`);
        $('#opponent_option').removeClass('rock paper scissors');
    }
}


function ConnectionClose() {
    if (connectionStatus.isConnected === true) {
        conn.close();
        updateConnectionStatus('game_end');
    }
}



$(document).ready(function () {
    $('#new-chat').on('click', function () {
        ConnectToStrangers(null);
    });

    $('#end-chat').on('click', function () {
        ConnectionClose();
    });


    document.querySelectorAll('.choice').forEach(button => {
        button.addEventListener('click', function () {
            if (Gamestatus.game == 'play' && connectionStatus.isConnected === true) {
                const choice = button.getAttribute('data-choice');
                const choiceObj = {
                    choice: choice
                };
                conn.send(JSON.stringify(choiceObj));
                button.classList.add('active');
                $('#wait').empty()
                $('#wait').html(`You Selected ${choice}`)
                GameChoices.myChoice = choice;
                Gamestatus.game = 'pause'
                checkGameResult();
            }
            else {
                console.log('game is paused')
            }
        });
    });
});