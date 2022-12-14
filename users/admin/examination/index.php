<?php
session_start();
if ($_SESSION['adminauth']) {
  require_once('../../../includes/constant.php');
  require_once("../../../includes/connection.php");
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> EXAMINATION</title>
    <?php include('../header.php') ?>

  </head>

  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <?php include('../sidebar.php') ?>
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid">
            <div class="container pt-4 pb-4">
              <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                  <!-- Info Boxes Style 2 -->
                  <a href="newexamination.php">
                    <div class="info-box mb-3 py-3 bg-warning">
                      <span class="info-box-icon"><i class="fas fa-tag"></i></span>

                      <div class="info-box-content">
                        <h5 class="info-box-text font-weight-bold">New Examination</h5>
                      </div>
                      <!-- /.info-box-content -->
                    </div>
                  </a>
                  <!-- /.info-box -->
                  <a href="<?= MANAGE_EXAMINATION ?>">
                    <div class="info-box mb-3 bg-success">
                      <span class="info-box-icon"><i class="fa fa-question"></i></span>

                      <div class="info-box-content">
                        <h5 class="info-box-text font-weight-bold">Questions Management</h5>
                        <?php
                        $pas = new Questions;
                        $q = $pas->query("SELECT COUNT(id) AS totalex FROM examinations");
                        $dat = $pas->data($q);
                        ?>
                        <span class="info-box-number"><?php echo $dat['totalex'] ?></span>
                      </div>
                      <!-- /.info-box-content -->
                    </div>
                  </a>
                  <!-- /.info-box -->
                  <a href="<?=PAST_QUESTION?>">
                    <div class="info-box mb-3 bg-danger">
                      <span class="info-box-icon"><i class="fas fa-cloud-download-alt"></i></span>
                          <?php
                          $today = date('Y-m-d');
                        $pas = new Questions;
                        $q = $pas->query("SELECT COUNT(id) AS totalpast FROM examinations WHERE enddate < '$today' ");
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
                  <a href="<?= STUDENT_RESULT ?>">
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
                <div class="col-md-3"></div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <?php include('../footer.php'); ?>

    </div>
  </body>

  </html>
<?php
  include('../script.php');
} else {
  header('location:'.LOGOUT);
}
?>

