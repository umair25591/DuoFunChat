<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duo Fun Chat</title>
    <link rel="stylesheet" href="./source/css/loader.css">
    <link rel="stylesheet" href="./source/css/style.css">
    <link rel="stylesheet" href="./source/css/footer.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./source/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
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

    <div class="main-notification-box"></div>
    
    <section class="top-section">
        <div class="container-fluid h-100">
            <div class="row h-100">
                <div class="col-12 d-flex justify-content-center col-md-6 left-col">
                    <div class="left">
                        <h1 class="head1"> <span class="meet"> Find </span> Your Virtual Friend</h1>
                        <p class="para1 mt-3">Start New Chat With Strangers All Around the World</p>
                        <button class="start-chat-btn mt-2">Start Chat</button>
                    </div>
                </div>
                <div class="col-12 col-md-6 right-col p-0">
                    <img src="./source/images/girl_boy_img.webp" alt="" class="first-sec-img">
                </div>
            </div>
        </div>
    </section>
    <div class="we-help-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="imgs-grid" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-delay="100"
                        data-aos-offset="200" data-aos-duration="500" data-aos-once="true">
                        <div class="grid grid-1"><img src="images/fr3.png" alt="Untree.co"></div>
                    </div>
                </div>
                <div class="col-lg-6 d-flex align-items-center">
                    <div class="ps-lg-5 pt-5" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-delay="100"
                        data-aos-offset="200" data-aos-duration="500" data-aos-once="true">
                        <h2 class="section-title mb-4">What is a random chat ?</h2>
                        <p>In the ever-evolving digital landscape, random chat has emerged as a
                            fascinating and engaging way to connect with people from diverse backgrounds across the
                            globe.
                            If you're wondering, "What is a random chat?" you've come to the right place. Our website is
                            your gateway to unraveling the exciting world of random chat, and we're here to provide you
                            with
                            valuable insights.</p>
                        <p><button class="explore-chat-btn mt-2">Explore now</button></p>
                    </div>
                </div>
            </div>
        </div>
    </div>






    <div class="we-help-section mt-4" id="section-2">
        <div class="container">
            <div class="row flex-column-reverse flex-lg-row justify-content-between">

                <div class="col-lg-6 d-flex align-items-center" data-aos="fade-up" data-aos-easing="ease-in-out"
                    data-aos-delay="100" data-aos-offset="200" data-aos-duration="500" data-aos-once="true">
                    <div class="pt-5">
                        <h2 class="section-title mb-4 text-white">How Safe Our Site Is ?</h2>
                        <ul class="sec-2-list text-white">
                            <li> In the world of random chat, safety is our top priority. Our website is dedicated to
                                providing a secure and respectful environment for all users.</li>
                            <li>Explore how our advanced content moderation tools filter out inappropriate content,
                                ensuring
                                a positive user experience.</li>
                            <li>We've implemented cutting-edge security measures, including state-of-the-art encryption
                                protocols, to protect your data during transmission.</li>
                            <li>Our expert team consistently updates and monitors our security infrastructure to
                                proactively
                                mitigate potential threats.</li>
                            <li>Your privacy and data security are of the utmost importance to us. We maintain strict
                                confidentiality standards to ensure your information remains secure.</li>
                            <li>You can confidently browse and interact on our site, knowing that your safety is our top
                                priority.</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="imgs-grid" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-delay="100"
                        data-aos-offset="200" data-aos-duration="500" data-aos-once="true">
                        <div class="grid grid-2"><img src="images/fr2.png" alt="Untree.co"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="product-section">
        <div class="container">
            <div class="row">

                <!-- Start Column 2 -->
                <div class="col-12 col-md-4 col-lg-4 mb-5 mb-md-0" data-aos="fade-up" data-aos-easing="ease-in-out"
                    data-aos-delay="100" data-aos-offset="200" data-aos-duration="500" data-aos-once="true">
                    <a class="product-item" href="chat.php">
                        <img src="./source/images/chatting_img.png" class="img-fluid product-thumbnail">
                        <h3 class="product-title">Random Chat</h3>
                        <span class="icon-cross">
                            <p>visit now</p>
                        </span>
                    </a>
                </div>
                <!-- End Column 2 -->

                <!-- Start Column 3 -->
                <div class="col-12 col-md-4 col-lg-4 mb-5 mb-md-0" data-aos="fade-up" data-aos-easing="ease-in-out"
                    data-aos-delay="200" data-aos-offset="200" data-aos-duration="500" data-aos-once="true">
                    <a class="product-item" href="video_call.php">
                        <img src="./source/images/video_call.png" class="img-fluid product-thumbnail">
                        <h3 class="product-title">Random Video Call</h3>
                        <span class="icon-cross">
                            <p>visit now</p>
                        </span>
                    </a>
                </div>
                <!-- End Column 3 -->

                <!-- Start Column 4 -->
                <div class="col-12 col-md-4 col-lg-4 mb-5 mb-md-0" data-aos="fade-up" data-aos-easing="ease-in-out"
                    data-aos-delay="300" data-aos-offset="200" data-aos-duration="500" data-aos-once="true">
                    <a class="product-item" href="Rock_Paper_game.php">
                        <img src="./source/images/rock_paper_sessior_img.png" class="img-fluid product-thumbnail">
                        <h3 class="product-title">Rock Paper Game</h3>
                        <span class="icon-cross">
                            <p>visit now</p>
                        </span>
                    </a>
                </div>
                <!-- End Column 4 -->

            </div>
        </div>
    </div>



    <?php include 'components/_footer.php'; ?>



</body>

</html>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
AOS.init();
</script>
<script src="./source/bootstrap/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uuid/8.3.2/uuid.min.js"></script>
<script type="module" src="./source/js/script.js"></script>
<script type="module" src="./source/js/home.js"></script>