document.getElementById('circle_container').addEventListener('click', function () {
	document.getElementById('profile_pic').click();
});

document.getElementById('profile_pic').addEventListener('change', function (event) {
	const file = event.target.files[0];
	const previewImg = document.getElementById('preview_img');
	const placeholder = document.querySelector('.circle-container .placeholder');

	if (file) {
		const reader = new FileReader();

		reader.onload = function (e) {
			previewImg.src = e.target.result;
			previewImg.classList.add('show');
			placeholder.style.display = 'none';
		};

		reader.readAsDataURL(file);
	} else {
		previewImg.classList.remove('show');
		placeholder.style.display = 'block';
	}
});

const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");

sign_up_btn.addEventListener("click", () => {
	container.classList.add("sign-up-mode");
});

sign_in_btn.addEventListener("click", () => {
	container.classList.remove("sign-up-mode");
});

$(document).ready(function () {
	$('#login_form').submit(function (e) {
		e.preventDefault();
		$('.loader-box').css('display', 'flex');
		$('#login_btn').hide();
		$.ajax({
			type: 'POST',
			url: 'source/php/login_user.php',
			data: $(this).serialize(),
			dataType: 'json',
			success: function (response) {
				console.log(response);
				if (response.status === 'success') {
					$(".alert_icon").hide();
					$('#error_msg').css('color', 'green');
					$('#error_msg').html(response.message);
					$('#error_box').css({
						'background-color': 'lightgreen',
						'display': 'flex'
					});
					setTimeout(() => {
						window.location.href = './index.php';
					}, 2000);
				} else {
					$('.loader-box').css('display', 'block');
					$('#login_btn').show();
					$('#error_msg').html(response.message);
					$('#error_box').css("display", "flex");
				}
			},
			error: function (error) {
				$('#login_loader').hide();
				$('#login_btn').val('Login');
				console.error(error);
			}
		});
	});

	// AJAX for signup form
	$('#sign_up_form').submit(function (e) {
		e.preventDefault();
		$('.sign-up-loader-box').css('display', 'flex');
		$('#signup_btn').hide();
		var formData = new FormData(this);
		$.ajax({
			type: 'POST',
			url: 'source/php/signup_user.php',
			data: formData,
			processData: false,
			contentType: false,
			success: function (response) {
				let res = JSON.parse(response);
				$('#error_msg_signup').text(res.message);
				if (res.status === 'success') {
					$(".alert_icon").hide();
					$('#error_msg_signup').css('color', 'green');
					$('#error_box_signup').css({
						'background-color': 'lightgreen',
						'display': 'flex'
					});
					$('.sign-up-loader-box').css('display', 'none');
					$('#signup_btn').show();
				} 
				else {
					$('.sign-up-loader-box').css('display', 'none');
					$('#signup_btn').show();
					$('#error_box_signup').css({
						'color': 'lightred',
						'background-color': 'lightred',
						'display': 'flex'
					});
					$('#error_msg_signup').css('color', 'red');
				}
			},
			error: function (error) {
				$('#sign_up_loader').hide();
				$('#signup_btn').val('Login');
				console.error(error);
			}
		});
	});
});
