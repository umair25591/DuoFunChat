<?php
session_start();
require_once './source/php/db-connection.php';

if(isset($_SESSION['email']) && isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
    $query = "SELECT u_profile_pic FROM users WHERE u_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($profileImg);
    $stmt->fetch();
    $stmt->close();
}
?>

<div class="nav-bar">
  <nav class="navbar navbar-expand-lg position-fixed navbar-light nav-bg-colour" style="padding-left:20px;">
    <div class="container-fluid mx-auto">
      <a class="navbar_brand1" href="./index.php"><img src="./source/images/logoname.png" alt="" class="logo"></a>
      <button class="navbar-toggler" id="navtoggle" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav navbar-nav1">
          <li class="nav-item1">
            <a class="nav-link1" href="index.php">Home</a>
          </li>
          <li class="nav-item1">
            <a class="nav-link1" href="chat.php">Random Chat</a>
          </li>
          <li class="nav-item1">
            <a class="nav-link1" href="Rock_Paper_game.php">Rock Paper Game</a>
          </li>
          <li class="nav-item1">
            <a class="nav-link1" href="chess.php">Chess</a>
          </li>
          <li class="nav-item1">
            <a class="nav-link1" href="video_call.php">Video Chat</a>
          </li>
          <li class="nav-item1">
            <a class="nav-link1" href="about.php">About Us</a>
          </li>
          <li class="mobile_nav">
          <?php
              if(isset($_SESSION['email']) && isset($_SESSION['userId'])){
                $profileImgSrc = !empty($profileImg) ? "./source/images/" . $profileImg : "./source/images/profile_img.webp"; // Default image if profile image is not set
                echo '<div class="profile-menu nav-link2">
                            <img src="'. $profileImgSrc .'" class="profile_img" alt="" id="profileImgmobile">
                            <div class="dropdown-menu-custom" id="dropdownMenumobile">
                              <a href="profile.php?id='. $_SESSION['userId'] .'">Profile</a>
                              <a href="friends.php">Friends</a>
                              <a href="friend_request.php">Friend Requests</a>
                              <a href="logout.php">Logout</a>
                            </div>
                          </div>';
              }
              else{
                echo '<button class="login_button" id="login_button1">Login</button>';
              }
          ?>
          </li>
        </ul>
      </div>
      <?php
              if(isset($_SESSION['email']) && isset($_SESSION['userId'])){
                $profileImgSrc = !empty($profileImg) ? "./source/images/" . $profileImg : "./source/images/profile_img.webp"; // Default image if profile image is not set
                echo '<div class="profile-menu nav-link2 desktop_nav">
                            <img src="'. $profileImgSrc .'" class="profile_img" alt="" id="profileImgdesk">
                            <div class="dropdown-menu-custom" id="dropdownMenudesk">
                              <a href="profile.php?id='. $_SESSION['userId'] .'">Profile</a>
                              <a href="friends.php">Friends</a>
                              <a href="friend_request.php">Friend Requests</a>
                              <a href="logout.php">Logout</a>
                            </div>
                          </div>';
              }
              else{
                echo '<button class="login_button desktop_nav" id="login_button2">Login</button>';
              }
          ?>
    </div>
  </nav>
</div>

