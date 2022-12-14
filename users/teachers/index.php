<?php
  require_once('../../includes/constant.php');
  require_once("../../includes/connection.php");
session_start();
if ($_SESSION['teacherauth']){

 
    
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>STAFFS DASHBOARD</title>
        <?php include('header.php') ?>

    </head>

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?php include('sidebar.php');?>
            <div class="content-wrapper">
                <section class="content">
                    <div class="container-fluid">
                        <!-- manage users -->
                        <?php
                        $teacherclass = $user['classid'];
                         $student = new Users;
                         $tstudent = $student->query("SELECT COUNT(id) AS totalstudent FROM student WHERE class='$teacherclass' ");
                        $totalstudent = $student->data($tstudent);
                        ?>
                        <section>
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <!-- small box -->
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3><?php echo $totalstudent['totalstudent'] ?></h3>

                                            <p>Total Student</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-users"></i>
                                        </div>
                                        <a href="managestudent.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-lg-3 col-6">
                                    <!-- small box -->
                                    <?php
                                      $staff = new Users;
                                      $tstaff = $staff->query("SELECT COUNT(id) AS totalstaff FROM teachers  WHERE classid='$teacherclass' ");
                                      $totalstaff = $staff->data($tstaff);
                                      ?>
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3><?php echo $totalstaff['totalstaff'] ?></h3>

                                            <p>Total Staffs</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                        </div>
                                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-lg-3 col-6">
                                    <!-- small box -->
                                    <?php
                                      $examinations = new Questions;
                                      $q = $examinations->query("SELECT COUNT(id) AS totalex FROM examinations  WHERE classid='$teacherclass'");
                                      $totalexam = $examinations->data($q);
                                      ?>
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3><?php echo $totalexam['totalex'] ?></h3>

                                            <p>Total Examination</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-book"></i>
                                        </div>
                                        <a href="examination/manageexamination.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-lg-3 col-6">
                                    <!-- small box -->
                                    <?php
                                      $today = date('Y-m-d');
                                      $examinations = new Questions;
                                      $q = $examinations->query("SELECT COUNT(id) AS incomming FROM examinations WHERE enddate > '$today' AND  classid='$teacherclass'");
                                      $totalincomming = $examinations->data($q);
                                      ?>
                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <h3><?php echo $totalincomming['incomming'] ?></h3>

                                            <p>Incomming Examination</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-question"></i>
                                        </div>
                                        <a href="examination/manageexamination.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <!-- ./col -->
                            </div>
                        </section>
                        <!-- manage users -->
                        <!-- section profile -->
                        <div class="row">
          <div class="col-md-6">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
              <div class="text-center">
                <img class="profile-user-img img-fluid img-circle" src="../<?php echo !empty($user['avatar'])?$user['avatar']:"staffs_avatar/staffs_avatar.png" ?>" alt="<?php echo $username ?>">
                </div>
                <h3 class="profile-username text-center"><?php echo ucwords($name); ?></h3>

                <p class="text-muted text-center"><?php echo $username ; ?> </p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Surname</b> <a class="float-right "><?php echo ucwords($user['surname']) ?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Firstname</b> <a class="float-right"><?php echo $user['firstname']?ucwords($user['firstname']):"<span class='text-danger'>NILL</span>" ?> </a>
                  </li>
                  <li class="list-group-item">
                    <b>Lastname</b> <a class="float-right"><?php echo $user['lastname']?ucwords($user['lastname']):"<span class='text-danger'>NILL</span>";?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Role</b> <a class="float-right"><?php echo $user['role']?ucwords($user['role']):"<span class='text-danger'>NILL</span>";?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Class taken</b> <a class="float-right"><?php echo $user['classname']?ucwords($user['classname']):"<span class='text-danger'>NILL</span>";?></a>
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
          <div class="container pt-4 pb-4">
              <!-- <div class="row"> -->
                <!-- <div class="col-md-3"></div> -->
                <!-- <div class="col-md-6"> -->
                  <!-- Info Boxes Style 2 -->
                  <a href="examination/newexamination.php">
                    <div class="info-box mb-3 py-3 bg-warning">
                      <span class="info-box-icon"><i class="fas fa-tag"></i></span>

                      <div class="info-box-content">
                        <h5 class="info-box-text font-weight-bold">New Examination</h5>
                      </div>
                      <!-- /.info-box-content -->
                    </div>
                  </a>
                  <!-- /.info-box -->
                  <a href="examination/manageexamination.php">
                    <div class="info-box mb-3 bg-success">
                      <span class="info-box-icon"><i class="fa fa-question"></i></span>

                      <div class="info-box-content">
                        <h5 class="info-box-text font-weight-bold">Questions Management</h5>
                        <?php
                        $pas = new Questions;
                        $q = $pas->query("SELECT COUNT(id) AS totalex FROM examinations  WHERE classid='$teacherclass'");
                        $dat = $pas->data($q);
                        ?>
                        <span class="info-box-number"><?php echo $dat['totalex'] ?></span>
                      </div>
                      <!-- /.info-box-content -->
                    </div>
                  </a>
                  <!-- /.info-box -->

                  <a href="past-questions">
                    <div class="info-box mb-3 bg-danger">
                      <span class="info-box-icon"><i class="fas fa-cloud-download-alt"></i></span>
                          <?php
                          $today = date('Y-m-d');
                        $pas = new Questions;
                        $q = $pas->query("SELECT COUNT(id) AS totalpast FROM examinations WHERE enddate < '$today' AND  classid='$teacherclass' ");
                        $dat = $pas->data($q);
                        ?>
                      <div class="info-box-content">
                        <h5 class="info-box-text font-weight-bold">Download Past Questions</h5>
                        <span class="info-box-number"><?php echo $dat['totalpast'] ?></span>
                      </div>
                      <!-- /.info-box-content -->
                    </div>
                  </a>
                  <!-- /.info-box -->
                  <a href="students/">
                    <div class="info-box mb-3 bg-info">
                      <span class="info-box-icon"><i class="far fa-list-alt"></i></span>

                      <div class="info-box-content">
                        <h5 class="info-box-text font-weight-bold">Student Result Management</h5>
                      </div>
                      <!-- /.info-box-content -->
                    </div>
                  </a>
                  <!-- /.info-box -->



                </div>
                <!-- <div class="col-md-3"></div> -->
              <!-- </div> -->
            </div>
          
          </div>
          <!-- /.col -->
        </div>
                    </div>
                </section>
            </div>
            <?php include('footer.php'); ?>
            <?php include('script.php'); ?>
        </div>
    </body>

    </html>
<?php
} else {
    header('location:../../');
}
?>