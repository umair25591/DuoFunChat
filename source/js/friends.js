import {checkForMessages} from './functions.js';

$(document).ready(function () {
        $('.toggle-friends-list').click(function() {
            $('#people-list').toggleClass('show');
        });
    
        $(document).click(function(event) {
            if (!$(event.target).closest('#people-list, .toggle-friends-list').length) {
                $('#people-list').removeClass('show');
            }
        });
    
        $('#people-list input[type="text"]').on('input', function() {
            var searchTerm = $(this).val().toLowerCase();
            $('#friends-list li').each(function() {
                var friendName = $(this).text().toLowerCase();
                if (friendName.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

    
        function formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
        
            // Create a date object for "yesterday"
            const yesterday = new Date(now);
            yesterday.setDate(now.getDate() - 1);
        
            const dateYear = date.getFullYear();
            const dateMonth = date.getMonth();
            const dateDay = date.getDate();
        
            const nowYear = now.getFullYear();
            const nowMonth = now.getMonth();
            const nowDay = now.getDate();
        
            const yesterdayYear = yesterday.getFullYear();
            const yesterdayMonth = yesterday.getMonth();
            const yesterdayDay = yesterday.getDate();
        
            const hours = date.getHours().toString().padStart(2, '0');
            const minutes = date.getMinutes().toString().padStart(2, '0');
            const timeString = `${hours}:${minutes}`;
        
            if (dateYear === nowYear && dateMonth === nowMonth && dateDay === nowDay) {
                return `Today at ${timeString}`;
            } else if (dateYear === yesterdayYear && dateMonth === yesterdayMonth && dateDay === yesterdayDay) {
                return `Yesterday at ${timeString}`;
            } else {
                return `${date.toLocaleDateString()} at ${timeString}`;
            }
        }
        
        

    function markMessagesAsRead(friend_id) {
        $.ajax({
            url: "./source/php/mark_messages_read.php",
            method: "POST",
            data: {
                friend_id: friend_id
            },
            success: function (response) {
                // console.log(response);
            },
            error: function (xhr, status, error) {
                console.error("Error marking messages as read:", error);
            },
        });
    }

    function fetchFriends() {
        $.ajax({
            url: "./source/php/fetch_friends.php",
            method: "POST",
            dataType: "json",
            success: function (data) {
                var friendsList = $("#friends-list");
                friendsList.empty();

                if (data.length > 0) {
                    data.forEach(function (friend) {
                        const user_status = friend.status === "online"
                            ? '<i class="fa fa-circle online"></i> online'
                            : '<i class="fa fa-circle offline"></i> offline';

                        const listItem = `
                            <li class="clearfix friends_list" data-friend="${friend.u_id}" data-name="${friend.u_name}" data-img="${friend.u_profile_pic}">
                            <div>
                                <img src="./source/images/${friend.u_profile_pic}" alt="profile-image" width="50px" height="50px" style="border-radius:50px;"/>
                                <div class="about">
                                    <div class="name">${friend.u_name}</div>
                                    <div class="status">
                                        ${user_status}
                                    </div>
                                </div>
                                </div>
                                 <div class="unread-count" style="display: none;"></div>
                            </li>`;
                        friendsList.append(listItem);
                    });

                    updateStatuses();
                } else {
                    friendsList.append('<li class="list-group-item">No friends found</li>');
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching friends:", error);
                $("#friends-list").append(
                    '<li class="list-group-item">Error fetching friends</li>'
                );
            },
        });
    }

    function updateStatuses() {
        $.ajax({
            url: "./source/php/fetch_statuses.php",
            method: "POST",
            dataType: "json",
            success: function (data) {
                data.forEach(function (friend) {
                    const user_status = friend.status === "online"
                        ? '<i class="fa fa-circle online"></i> online'
                        : '<i class="fa fa-circle offline"></i> offline';

                    $(`li[data-friend="${friend.u_id}"] .status`).html(user_status);
                });

                // Call updateStatuses again every second
                setTimeout(updateStatuses, 1000);
            },
            error: function (xhr, status, error) {
                console.error("Error fetching statuses:", error);
            },
        });
    }

    fetchFriends();
    setInterval(() => {
        checkForMessages();
    }, 1000);

    var currentFriendId = null;
    var currentFriendName = null;
    var currentFriendPic = null;
    var latestMessageTimestamp = {};

    $(document).on("click", ".friends_list", function () {
        $(".friends_list").removeClass("active");
        $(this).addClass("active");

        currentFriendId = $(this).data("friend");
        currentFriendName = $(this).data("name");
        currentFriendPic = $(this).data("img");

        $(".chat-with").html(currentFriendName);
        $("#friend_img").attr("src", `./source/images/${currentFriendPic}`);
        $('#friend_profile').attr("href", `profile.php?id=${currentFriendId}`)

        latestMessageTimestamp[currentFriendId] = null;
        getMessages(currentFriendId, true);
    });


    function getMessages(friend_id, initialLoad = false) {
        $.ajax({
            url: "./source/php/fetch_messages.php",
            method: "POST",
            data: {
                friend_id: friend_id,
                last_timestamp: latestMessageTimestamp[friend_id]
            },
            dataType: "json",
            success: function (data) {
                var messagesDiv = $("#messages");

                if (initialLoad) {
                    messagesDiv.empty();
                }

                if (data.length > 0) {
                    data.forEach(function (message) {
                        var messageHTML = ''
                        var messageContent = message.message;
                        if(messageContent.startsWith("VIDEO_CALL|")){
                            const videoCallLink = messageContent.split("|")[1];
                            messageHTML = `<li class="clearfix">
                            <div class="message-data ${message.sender_id === currentFriendId ? '' : 'align-right'}">
                                <span class="message-data-time">${formatDate(message.created_at)}</span> &nbsp; &nbsp;
                            </div>
                            <div class="message ${message.sender_id === currentFriendId ? 'my-message' : 'other-message float-right'}">
                                <a href="${videoCallLink}" target="_blank">Click here to start video call</a>
                            </div>
                        </li>`;
                        }
                        else if(messageContent.startsWith("RPS_GAME|")){
                            const RpsGameLink = messageContent.split("|")[1];
                            messageHTML = `<li class="clearfix">
                            <div class="message-data ${message.sender_id === currentFriendId ? '' : 'align-right'}">
                                <span class="message-data-time">${formatDate(message.created_at)}</span> &nbsp; &nbsp;
                            </div>
                            <div class="message ${message.sender_id === currentFriendId ? 'my-message' : 'other-message float-right'}">
                                <a href="${RpsGameLink}" target="_blank">Click here Start to Game</a>
                            </div>
                        </li>`;
                        }
                        else{
                            messageHTML = `<li class="clearfix">
                            <div class="message-data ${message.sender_id === currentFriendId ? '' : 'align-right'}">
                                <span class="message-data-time">${formatDate(message.created_at)}</span> &nbsp; &nbsp;
                            </div>
                            <div class="message ${message.sender_id === currentFriendId ? 'my-message' : 'other-message float-right'}">
                                ${message.message}
                            </div>
                        </li>`;
                        }
                        

                        messagesDiv.append(messageHTML);

                        scrollBottom();

                        // Update the latest message timestamp
                        latestMessageTimestamp[friend_id] = message.created_at;
                    });

                    markMessagesAsRead(friend_id);
                    $(`li[data-friend="${friend_id}"]`).find('.unread-count').text("").hide();
                }

                // Continue polling for new messages
                setTimeout(() => {
                    getMessages(friend_id);
                }, 1000);
            },
            error: function (xhr, status, error) {
                console.error("Error fetching messages:", error);
                $("#messages").append("<div>Error fetching messages</div>");
            },
        });
    }

    $("#send_btn").click(function () {
        sendMessage();
    });

    $("#message-to-send").keydown(function (event) {
        if (event.key === "Enter" && !event.shiftKey) {
            event.preventDefault();
            sendMessage();
        }
    });

    function sendMessage() {
        var message = $("#message-to-send").val();
        if (currentFriendId && message.trim() !== "") {
            $.ajax({
                url: "./source/php/send_message.php",
                method: "POST",
                dataType: "json",
                data: {
                    receiver_id: currentFriendId,
                    message: message,
                },
                success: function (response) {
                    console.log("Message sent:", response);
                    $("#message-to-send").val("");
                },
                error: function (xhr, status, error) {
                    console.error("Error sending message:", error);
                },
            });
        }
    }

    function scrollBottom() {
        $(".chat-history").scrollTop($(".chat-history")[0].scrollHeight);
    }


    $("#option_send_btn").click(function () {
        var selectedOption = $("#option_select").val();
        var linkCreated = null
        var randomId = Math.floor(Math.random()*10);
        if(selectedOption == "videoCall"){
            linkCreated = `VIDEO_CALL|https://192.168.1.101.com/ChatApp/video_call.php?roomId=${randomId}`;
        }
        else if(selectedOption == "rpsGame"){
            linkCreated = `RPS_GAME|https://192.168.1.101.com/ChatApp/Rock_Paper_game.php?roomId=${randomId}`;
        }
        if (currentFriendId && selectedOption) {
            $.ajax({
                url: "./source/php/send_message.php",
                method: "POST",
                dataType: "json",
                data: {
                    receiver_id: currentFriendId,
                    message: linkCreated,
                },
                success: function (response) {
                    console.log("Message sent:", response);
                    $("#option_select").prop('selectedIndex', 0);
                },
                error: function (xhr, status, error) {
                    console.error("Error sending message:", error);
                },
            });
        } 
        else {
            alert("Please select an option and a friend to send the invitation.");
        }
    });
});