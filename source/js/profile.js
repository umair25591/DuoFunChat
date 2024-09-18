$(document).ready(function () {

    $("#submit_edit_form").on("click", function(){
        $("#editform").submit();
    });
    $("#logout_btn").on('click', function(){
        window.location.href = "./logout.php";
    });


    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('id');

    if (userId) {
        $.ajax({
            url: './source/php/user_details.php',
            method: 'GET',
            dataType: 'json',
            data: { id: userId },
            success: function (response) {
                console.log(response);
                if (response.error) {
                    console.log(response.error);
                }
                else {
                    const table = $("#profile_data");
                    const table_data = `<tr>
                                                <th>Full Name</th>
                                                <td>${response.u_name}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>${response.u_email}</td>
                                            </tr>
                                            <tr>
                                                <th>Age</th>
                                                <td>${response.u_age}</td>
                                            </tr>
                                            <tr>
                                                <th>Bio</th>
                                                <td>${response.u_bio}</td>
                                            </tr>`
                    table.append(table_data);
                    $("#user_name").html(response.u_name);

                    if (response.status === "online") {
                        $("#user_image").css("border", "5px solid #6EC531")
                    }
                    else if (response.status === "offline") {
                        $("#user_image").css("border", "5px solid orange")
                    }

                    // Set the edit form inputs with the fetched data
                    $("#edit_username").val(response.u_name);
                    if(response.u_age === "Not Provided"){
                        $("#edit_age").val();
                    }
                    else{
                        $("#edit_age").val(parseInt(response.u_age));
                    }
                    if(response.u_bio === "Not Provided"){
                        $("#edit_bio").val();
                    }
                    else{
                        $("#edit_bio").val(response.u_bio);
                    }
                    

                    if (response.u_profile_pic) {
                        $("#preview_img").attr('src', './source/images/'+response.u_profile_pic).addClass('show');
                        $('.circle-container .placeholder').hide();
                        $("#user_image").attr('src', './source/images/'+response.u_profile_pic);
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching user details:", error);
                alert("An error occurred while fetching user details.");
            }
        });
    } else {
        alert("No user ID provided in the URL.");
    }
});

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
