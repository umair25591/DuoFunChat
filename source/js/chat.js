import {showNotification, GetAllMessageCountForNotification} from './functions.js';


var connectionStatus = {
    isConnected: false,
};
var loginStatus = {
    login: false,
};
var friendRequest = {
    status: false,
};
var text = $("#chat");
var conn;
var menu = $(".menu");
var userId;
var partnerId;

function ConnectToStrangers() {
    var url = "wss://duofunchat.com:8080?type=chat";

    if (loginStatus.login === true) {
        url += "&userId=" + encodeURIComponent(userId);
    }

    conn = new WebSocket(url);
    conn.onopen = function (event) {
        // console.log("WebSocket connection established.");
        updateConnectionStatus("connecting");
        connectionStatus.isConnected = true;
    };

    conn.onclose = function (event) {
        updateConnectionStatus("disconnect");
        console.log("WebSocket connection closed.");
    };

    conn.onmessage = function (e) {
        // console.log(e.data);
        const data = JSON.parse(e.data);
        if (data.connection == "CoNneCtIoN ClOsEd") {
            conn.close();
        } else if (data.action === "paired") {
            partnerId = data.partnerId;
            updateConnectionStatus("connected");
            friendRequest.status = true;

        } else if (data.message) {
            var messageText = data.message;
            var message_body = $(
                '<div class="chat-2 d-flex justify-content-start"><div class="message-box d-flex justify-content-start" style="width: 50%;"><p class="m-0 text"></p></div></div>'
            );
            message_body.find("p.m-0").text(messageText);
            text.append(message_body);
            scrol_bottom();
        }
    };
}

$.ajax({
    url: "./source/php/checkLogin.php",
    type: "GET",
    dataType: "json",
    success: function (response) {
        // console.log(response);
        if (response.userId) {
            loginStatus.login = true;
            userId = response.userId;
            setInterval(GetAllMessageCountForNotification, 5000);
        } else {
            loginStatus.login = false;
            console.log("Not logged in");
        }
    },
    error: function (xhr, status, error) {
        console.error("Error checking login status:", error);
        $("#status").text("Error checking login status");
    },
});

function updateConnectionStatus(isConnected) {
    if (isConnected === "connecting") {
        $("#new-chat").css("display", "none");
        $("#end-chat").css("display", "block");
        $("#p1").html("Finding Chat...");
        menu.hide();
    } else if (isConnected === "connected") {
        $("#new-chat").css("display", "none");
        $("#end-chat").css("display", "block");
        $("#p1").html("Connected");
        $("#message").prop("readonly", false);
        if(loginStatus.login === true){
            menu.show();
        }
    } else if (isConnected === "disconnect") {
        $("#new-chat").css("display", "block");
        $("#end-chat").css("display", "none");
        $("#p1").html("Chat End...");
        $("#chat").text("");
        $("#message").prop("readonly", true);
        menu.hide();
    }
}

$(document).ready(function () {
    menu.hide();
    $("#new-chat").on("click", function () {
        $("#new-chat").css("display", "none");
        if ($("#new-chat").css("display") === "none") {
            $("#p1").html("Finding Chat...");
            $("#end-chat").css("display", "block");
            ConnectToStrangers();
        } else {
            console.log("new-chat didnt hide");
        }
    });

    $("#end-chat").on("click", function () {
        if (connectionStatus.isConnected === true) {
            conn.close();
        }
    });

    $("#send").on("click", function () {
        var message = $("#message").val();
        var messageObj = {
            message: message,
        };
        console.log(messageObj);
        var jsonMessage = JSON.stringify(messageObj);

        if (message === "") {
            console.log("Input is empty");
        } else {
            if (conn.readyState === WebSocket.OPEN) {
                conn.send(jsonMessage);
                var message_body = $(
                    '<div class="chat-1 d-flex justify-content-end"><div class="message-box d-flex justify-content-end" style="width: 50%;"><p class="m-0 text"></p></div></div>'
                );
                message_body.find("p.m-0").text(message);
                text.append(message_body);
                scrol_bottom();
                $("#message").val("");
            } else {
                console.error("WebSocket connection is not open.");
            }
        }
    });

    $(document).on("keypress", "#message", function (event) {
        if (event.which === 13) {
            var message = $("#message").val();
            var messageObj = {
                message: message,
            };

            console.log(message);
            var jsonMessage = JSON.stringify(messageObj);

            if (message === "") {
                console.log("Input is empty");
            } else {
                if (conn.readyState === WebSocket.OPEN) {
                    conn.send(jsonMessage);
                    var message_body = $(
                        '<div class="chat-1 d-flex justify-content-end"><div class="message-box d-flex justify-content-end" style="width: 50%;"><p class="m-0 text"></p></div></div>'
                    );
                    message_body.find("p.m-0").text(message);
                    text.append(message_body);
                    scrol_bottom();
                    $("#message").val("");
                } else {
                    console.error("WebSocket connection is not open.");
                }
            }
        }
    });

    // $('#reportbtn').on('click', function(){

    // });

    $('#addFriendbtn').off('click').on("click", function (e) {
        e.preventDefault();
    
    if (connectionStatus.isConnected === true) {
        if(friendRequest.status === true){
            $.ajax({
                url: "./source/php/sendFriendRequest.php",
                method: "POST",
                dataType: "json",
                data: {
                    userId: userId,
                    partnerId: partnerId
                },
                success: function (response) {
                    if(response.message === "Friend request sent successfully."){
                        showNotification(response.message);
                        friendRequest.status = false;
                    }
                    else if(response.message === "Request already exists !"){
                        showNotification(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.log("AJAX error - Status:", status, "Error:", error);
                    showNotification(xhr.responseText);
                },
            });
        }
        else{
            showNotification("Friend Request sent already !");
        }
    } 
    else {
        console.log("User isn't connected");
    }
    });




});

function scrol_bottom() {
    $("#chat").scrollTop($("#chat")[0].scrollHeight);
}




