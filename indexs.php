<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OIC ACADEMIC OF CODING::HOME</title>
    <?php include('header.php') ?>
</head>

<body>

    <video autoplay muted loop id="myVideo">
        <source src="images/oicvideo.mp4" type="video/mp4">
    </video>
    <div class="container-fluid bg-primary" style="top:0; position:fixed">
        <marquee behavior="" direction="">
            <h3 class="text-center text-uppercase font-weight-bold text-warning p-4">oic academic of programming cbt
                center</h3>
        </marquee>
    </div>
    <div class="container-fluid content" id="slide">

        <div style="display: table-cell; vertical-align:middle">
            <div class="text-center" style="margin-left: auto; margin-right:auto">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3"></div>
                    <div class="col-lg-6 col-md-6 col-sm-6 ">
                        <div class="jumbotron login">
                            <div class="text-center">
                                <span class="fa fa-user" style="font-size: 3em;"></span>
                            </div>
                            <h3 class="text-center font-weight-bold text-uppercase mb-2">user login</h3>
                            <p class="text-danger" id="loginerror"></p>
                            <form id="userloginform">
                                <label for=""><strong>Username</strong> </label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Username</span>
                                    </div>
                                    <input type="text" class="form-control" name="username" placeholder="Username">
                                </div>
                                <label for=""><strong>Password</strong> </label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Password</span>
                                    </div>
                                    <input type="password" class="form-control" name="password" placeholder="Username">
                                </div>
                                <button class="btn btn-block btn-lg btn-primary" id="userlogin">Login</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3"></div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php include('footer.php') ?>
<script src="js/jquery.vide.min.js"></script>

<script>
    //     $(document).ready(setInterval(slide, 2000));
    // function slider(){
    //   var imag = ["image/3.jpg","image/4.jpg", "image/2.jpg", "image/1.jpg",
    //   "image/5.jpg" ];
    //   var ok = document.getElementById('slide');
    //   var image = imag.sort(function(a, b){return 0.5 - Math.random()});
    //   ok.style.backgroundImage = "linear-gradient(rgba(0, 0, 0, 0.6), rgba(2, 2, 2, 0.6)), url('"+image[0]+"')";
    //   ok.style.backgroundSize = "100%";
    //   ok.style.backgroundRepeat ="no-repeat"; 
    // }
</script>