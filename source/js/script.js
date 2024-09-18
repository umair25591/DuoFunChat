var user_login = {
    islogin: false
};

document.addEventListener("DOMContentLoaded", function() {
    const loader = document.getElementById("loader");
    loader.classList.add("hidden");
    setTimeout(() => {
        loader.style.display = "none";
    }, 1000);
});
$.ajax({
    url: "./source/php/checkLogin.php",
    type: "GET",
    dataType: "json",
    success: function(response) {
        console.log(response);
        if (response.userId) {
            user_login.islogin = true;
            var conn = new WebSocket(`wss://duofunchat.com:8080?status=online&userId=${response.userId}`);
            conn.onopen = function(event) {
                console.log("WebSocket connection established.");
            };
            conn.onclose = function(event) {
                // console.log("WebSocket connection closed.");
            };
            conn.onmessage = function(e) {
                // Handle WebSocket messages
            };
        } else {
            user_login.islogin = false;
            // setTimeout(() => {
            //     showNotification("Login to add strangers as friends!");
            // }, 2000);
            // console.log("Not logged in");
        }

        // Start animations after determining login status
        startAnimations();
    },
    error: function(xhr, status, error) {
        console.error("Error checking login status:", error);
    }
});

$('#login_button1, #login_button2').on('click', function(e) {
    window.location.href = 'login_signup.html';
});


function startAnimations() {
    var nav_link = $('.nav-link1');
    var nav_link2 = $('.nav-link2');
    var profile_img = $('.profile_img');
    var nav = $('.nav-bg-colour');
    var heading = $('.head1');
    var para = $('.para1');
    var btn = $('.start-chat-btn');
    var img = $('.first-sec-img');
    var nav_brand = $('.navbar_brand1')
    var login_btn = $('#login_button1');
    var login_btn2 = $('#login_button2');
    var nav_toggle = $("#navtoggle");


    heading.css({
        'transform': 'translateX(-1000px)',
        'transition': 'transform 1s ease'
    });

    para.css({
        'transform': 'translateX(-1000px)',
        'transition': 'transform 1s ease'
    });

    btn.css({
        'transform': 'translateX(-1000px)',
        'transition': 'transform 1s ease'
    });

    img.css({
        // 'width': '10px',
        // 'transition': 'width 1s ease'
    });

    if (user_login.islogin) {
        nav.css({
            'top': '-100px',
            'height': 'auto',
        });
    } else {
        nav.css({
            'top': '-100px',
            'height': 'auto',
        });
    }

    nav.animate({
        top: '30px'
    }, 1000);

    setTimeout(function() {
        heading.css('transform', 'translateX(0px)');
        img.css({
            'transform': 'scaleX(1)',
            'transform': 'scaleY(1)'
        });

        nav.animate({
            top: '10px',
            width: '80%'
        }, 700);
    }, 1000);

    setTimeout(() => {
        nav_link.css('display','block');
        nav_brand.css('display','block');
    }, 1600);

    setTimeout(() => {
        nav_link.css('opacity', '0');
        $('.nav-link1').animate({
            opacity: '1',
        }, 1000);
        nav.css('height', '');
    }, 1500);

    setTimeout(() => {
        nav_toggle.css('opacity', '0');
        $('#navtoggle').animate({
            opacity: '1'
        }, 1000);
        nav.css('height', '');
    }, 1500);

    setTimeout(() => {
        login_btn.css('opacity', '0');
        $('#login_button1').animate({
            opacity: '1'
        }, 1000);
        nav.css('height', '');
    }, 1500);

    setTimeout(() => {
        login_btn2.css('opacity', '0');
        $('#login_button2').animate({
            opacity: '1'
        }, 1000);
        nav.css('height', '');
    }, 1500);

    setTimeout(() => {
        nav_brand.css('opacity', '0');
        $('.navbar_brand1').animate({
            opacity: '1'
        }, 1000);
        nav.css('height', '');
    }, 1500);

    setTimeout(() => {
        nav_link2.css('opacity', '0');
        $('.nav-link2').animate({
            opacity: '1'
        }, 1000);
        nav.css('height', '');
    }, 1500);

    setTimeout(() => {
        para.css('transform', 'translateX(0px)');
    }, 1200);

    setTimeout(() => {
        btn.css('transform', 'translateX(0px)');
    }, 1400);

    var currentURL = window.location.href;

    $('.nav-link1').each(function() {
        if (currentURL.endsWith($(this).attr('href'))) {
            $(this).addClass('active1');
        }
    });

    var start_chat = $('.start-chat-btn');
    start_chat.on('click', function(e) {
        e.preventDefault();
        window.location.href = 'chat.php';
    });

    $('#profileImgmobile').click(function(event) {
        $('#dropdownMenumobile').toggle();
        event.stopPropagation();
    });

    $(document).click(function(event) {
        if (!$(event.target).closest('#profileImgmobile, #dropdownMenumobile').length) {
            $('#dropdownMenumobile').hide();
        }
    });

    $('#profileImgdesk').click(function(event) {
        $('#dropdownMenudesk').toggle();
        event.stopPropagation();
    });

    $(document).click(function(event) {
        if (!$(event.target).closest('#profileImgdesk, #dropdownMenudesk').length) {
            $('#dropdownMenudesk').hide();
        }
    });

    $('.menu_icon').click(function(event) {
        $('#dropdownMenu1').toggle();
        event.stopPropagation();
    });

    $('#dropdownMenu1 a').click(function(event) {
        $('#dropdownMenu1').hide();
    });

    $(document).click(function(event) {
        if (!$(event.target).closest('.menu, #dropdownMenu1').length) {
            $('#dropdownMenu1').hide();
        }
    });
}
