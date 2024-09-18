export function showNotification(message) {
    const mainNotificationBox = $(".main-notification-box");
    
    const notificationId = `notification-${Date.now()}`;
    const notification = `
        <div id="${notificationId}" class="notification-box">
            ${message}
        </div>
    `;
    
    mainNotificationBox.append(notification);
    setTimeout(() => {
        $(`#${notificationId}`).addClass('show');
    }, 500);

    setTimeout(function() {
        $(`#${notificationId}`).removeClass('show').addClass('hide');
        setTimeout(function() {
            $(`#${notificationId}`).remove();
        }, 500);
    }, 4000);
}




export function checkForMessages(){
    $.ajax({
        url: "./source/php/checkForMessages.php",
        type: "POST",
        dataType: "json",
        success: function (response) {
            console.log(response)
            response.forEach(friend => {
                const friendItem = $(`li[data-friend="${friend.sender_id}"]`);
                if (friend.message_count > 0) {
                    friendItem.find('.unread-count').text(friend.message_count).show();
                } else {
                    friendItem.find('.unread-count').hide();
                }
            });
        },
        error: function (xhr, status, error) {
            console.error("Error checking login status:", error);
        },
    });
}



export function GetAllMessageCountForNotification(){
    $.ajax({
        url: "./source/php/GetAllMessageCount.php",
        type: "POST",
        dataType: "json",
        success: function (response) {
            let lastNotifiedId = localStorage.getItem('lastNotifiedMessageId') || 0;
            if(response.total_unread_count > 0){
                if (response.newest_message_id > lastNotifiedId) {
                    showNotification(`You have ${response.total_unread_count} new messages`);
                    localStorage.setItem('lastNotifiedMessageId', response.newest_message_id);
                }
            }
        },
        error: function (xhr, status, error) {
            console.error("Error checking message count:", error);
        },
    });
}

export function GetFriendRequestForNotification(){
    $.ajax({
        url: "./source/php/fetch_pending_requests.php",
        type: "POST",
        dataType: "json",
        success: function (response) {
            console.log(response);
            let lastNotifiedReqId = localStorage.getItem('lastNotifiedReqId') || 0;
            if(response.length > 0){
                if (response[0].req_id > lastNotifiedReqId) {
                    showNotification(`You have ${response.length} new friend ${response.length > 1 ? 'requests' : 'request'}`);
                    localStorage.setItem('lastNotifiedReqId', response[0].req_id);
                }
            }
        },
        error: function (xhr, status, error) {
            console.error("Error checking message count:", error);
        },
    });
}