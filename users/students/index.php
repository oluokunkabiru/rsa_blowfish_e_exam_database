<?php
session_start();
if ($_SESSION['studentauth']) {
    include("../../includes/connection.php");
    $login = $_SESSION['studentauth'];
    $auth = new Users;
    $query = $auth->query("SELECT users.*, student.*, class.name AS classname FROM users JOIN student ON student.userid = users.userid JOIN 
    class ON class.classid = student.class WHERE users.username ='$login' OR phone = '$login' OR email = '$login'");    
    $user = $auth->data($query);
    // $names = htmlentities(strtoupper($admins['username']));
    $name = $user['surname']." ". $user['firstname']." ". $user['lastname'];
    $username = $user['username'];
    // if($user['active_status']==1){

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>STUDENT DASHBOARD</title>
        <?php include('header.php') ?>

    </head>

    <body class="">
        <?php include('navbar.php') ?>
    <div class="jumbotron">

                <section class="content">

                    <div class="container-fluid">
                                    <div class="text-center">
                                        <h3 class="font-weight-bold text-uppercase"><?= $schoolName ?></h3>
                                        <h4><?= $schoolAddress ?></h4>
                                    </div>

                        <!-- section profile -->
                        <div class="container py-5">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Profile Image -->
                                    <div class="card card-primary card-outline">
                                        <div class="card-body box-profile">
                                        <div class="text-center">
                                                <img class="profile-user-img img-fluid img-circle" src="<?php echo !empty($user['avatar'])?$user['avatar']:"../students_avatar/avatar.png" ?>" alt="User profile picture">
                                                </div>
                                                 <h3 class="profile-username text-center"><?php echo ucwords($name); ?></h3>

                                            <p class="text-muted text-center"><?php echo $username; ?> </p>

                                            <ul class="list-group list-group-unbordered mb-3">
                                                <li class="list-group-item">
                                                    <b>Surname</b> <a class="float-right "><?php echo ucwords($user['surname']) ?></a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Firstname</b> <a class="float-right"><?php echo $user['firstname'] ? ucwords($user['firstname']) : "<span class='text-danger'>NILL</span>" ?> </a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Middlename</b> <a class="float-right"><?php echo $user['lastname'] ? ucwords($user['lastname']) : "<span class='text-danger'>NILL</span>"; ?></a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Class</b> <a class="float-right"><?php echo $user['role'] ? ucwords($user['classname']) : "<span class='text-danger'>NILL</span>"; ?></a>
                                                </li>
                                            </ul>

                                            <a href="#" class="btn btn-primary btn-block"><b></b></a>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->



                                </div>
                                <!-- /.col -->
                                <div class="col-md-6">
                                    <!-- Info Boxes Style 2 -->
                                    <div class="card card-primary card-outline">
                                        <div class="card-body box-profile">
                                    <a href="examination/">
                                        <div class="info-box mb-3 py-4 bg-warning">
                                            <span class="info-box-icon"><i class="fas fa-tag"></i></span>

                                            <div class="info-box-content">
                                                <h5 class="info-box-text font-weight-bold">Take Examination</h5>
                                            </div>
                                            <!-- /.info-box-content -->
                                        </div>
                                    </a>
                                    <!-- /.info-box -->
                                    <!-- <a href="manageexamination.php">
                                        <div class="info-box mb-3 py-4 bg-success">
                                            <span class="info-box-icon"><i class="far fa-heart"></i></span>

                                            <div class="info-box-content">
                                                <h5 class="info-box-text font-weight-bold">Resume Examination</h5>
                                            </div>
                                            <!-- /.info-box-content 
                                        </div>
                                    </a>
                                    <!-- /.info-box 
                                    <a href="practice/">
                                        <div class="info-box mb-3 py-4 bg-danger">
                                            <span class="info-box-icon"><i class="fas fa-cloud-download-alt"></i></span>

                                            <div class="info-box-content">
                                                <h5 class="info-box-text font-weight-bold">Practice Questions</h5>
                                            </div>
                                            <!-- /.info-box-content 
                                        </div>
                                    </a> -->
                                    <!-- /.info-box -->
                                    <a href="examination/studentresult.php">
                                        <div class="info-box mb-3 py-4 bg-info">
                                            <span class="info-box-icon"><i class="far fa-comment"></i></span>

                                            <div class="info-box-content">
                                                <h5 class="info-box-text font-weight-bold">Check Result</h5>
                                            </div>
                                            <!-- /.info-box-content -->
                                        </div>
                                    </a>
                                    <!-- /.info-box -->



                                </div>
                            </div>
                            <!-- /.col -->
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
          </div>       
            <?php include('footer.php'); ?>
   
    </body>

    </html>
<?php
    include('script.php');
    // }else{
    // header('location:logout.php');
    // }
} else {
    header('location:../../');
}
?>