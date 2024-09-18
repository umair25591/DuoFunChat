<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="./source/css/loader.css">
    <link rel="stylesheet" href="./source/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./source/css/style.css">
    <link rel="stylesheet" href="./source/css/profile.css">
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
            <div data-text="LOADING..." class="text"></div>
        </div>
    </div>
    <?php include 'components/_navbar.php'; ?>

    <div class="container-fluid overcover">
        <div class="container profile-box">
            <?php
            if(isset($_SESSION['success'])){
                echo '<div class="alert alert-success mt-3 alert-dismissible fade show" role="alert">'.$_SESSION['success'].'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                unset($_SESSION['success']);
            }
            else if(isset($_SESSION['error'])){
                echo '<div class="alert alert-danger mt-3 alert-dismissible fade show" role="alert">'.$_SESSION['error'].'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                unset($_SESSION['error']);
            }
            ?>
            
            <div id="about" class="home row">
                <div class="image-box">
                    <img src="./source/images/profile_img.webp" alt="" id="user_image">
                </div>
            </div>
            <div class="basic-detail row">
                <div class="col-md-8 detail-col">
                    <h2 id="user_name"></h2>
                    <?php
                    if(isset($_SESSION['userId']) && isset($_SESSION['email'])){
                        echo '<div class="btn-cover">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editProfile">Edit
                            profile</button>
                        <button class="btn btn-danger" id="logout_btn">Log Out</button>
                    </div>';
                    }
                    ?>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="editProfile" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Profile</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="./source/php/edit_profile.php" method="post" enctype="multipart/form-data"
                                id="editform">
                                <div class="input-box1">
                                    <div class="circle-container" id="circle_container">
                                        <img id="preview_img" src="" alt="Image Preview" />
                                        <div class="placeholder">Upload Image</div>
                                    </div>
                                    <input type="file" name="profile_pic" id="profile_pic" accept="image/*" />
                                </div>
                                <div class="input-box">
                                    <label>Username</label>
                                    <input type="text" name="u_name" class="form-control" id="edit_username"
                                        placeholder="Username">
                                </div>
                                <div class="input-box">
                                    <label>Age</label>
                                    <input type="number" name="u_age" class="form-control" id="edit_age"
                                        placeholder="Your age">
                                </div>
                                <div class="input-box">
                                    <label>Bio</label>
                                    <textarea name="u_bio" class="form-control" id="edit_bio" cols="30" rows="10"
                                        placeholder="bio..."></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="submit_edit_form">Edit Profile</button>
                        </div>
                    </div>
                </div>
            </div>


            <section id="profile" class="home-dat">
                <div class="row no-margin home-det">

                    <div class="col-md-12 home-dat">
                        <div class="jumbo-address">
                            <div class="row no-margin">
                                <div class="col-lg-12 no-padding">

                                    <table class="addrss-list">
                                        <tbody id="profile_data">

                                        </tbody>
                                    </table>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>

</html>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="./source/bootstrap/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uuid/8.3.2/uuid.min.js"></script>
<script src="./source/js/script.js"></script>
<script src="./source/js/profile.js"></script>