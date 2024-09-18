import {GetAllMessageCountForNotification, GetFriendRequestForNotification} from './functions.js';

document.addEventListener("DOMContentLoaded", function() {
    $.ajax({
        url: "./source/php/checkLogin.php",
        type: "GET",
        dataType: "json",
        success: function (response) {
            // console.log(response);
            if (response.userId) {
                setInterval(GetAllMessageCountForNotification, 3000);
                setInterval(GetFriendRequestForNotification, 3000);
            }
            else {
                
            }
        },
        error: function (xhr, status, error) {
            console.error("Error checking login status:", error);
        },
    });
    
});