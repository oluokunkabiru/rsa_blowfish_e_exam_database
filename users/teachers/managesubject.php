<?php
session_start();
require_once('../../includes/constant.php');
require_once("../../includes/connection.php");
if ($_SESSION['teacherauth']) {
  //  include('../../includes/connection.php');


?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MANAGED SUBJECT</title>
    <?php include('header.php') ?>

  </head>

  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <?php include('sidebar.php') ?>
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid">

            <h2 class="text-center text-uppercase font-weight-bold mt-2 mb-2">Managed subject</h2>
            <table id="tables" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Subject name</th>
                  <th>Class</th>
                  <th>Date Created</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $class = new Classes;
                $teacherclass = $user['classid'];
                $q = $class->query("SELECT subjects.*, class.id as classid, class.name as classname FROM subjects 
                JOIN class ON subjects.classid = class.classid WHERE class.classid= '$teacherclass' ORDER BY id DESC ");
                $i = 1;
                while ($data = $class->data($q)) {


                ?>
                  <tr>
                    <td scope="row"><?php echo $i++ ?></td>
                    <td><?php echo $data['subjectname']  ?></td>
                    <td><?php echo $data['classname']  ?></td>
                    <td><?php echo $data['reg_date'] ?></td>
                   
                  </tr>
                <?php
                }
                ?>

              </tbody>

            </table>
          </div>
         

      </div>
      <?php include('footer.php'); ?>
    </div>
  </body>

  </html>
  <script>
 $(document).ready(function () {


})
  </script>
<?php
  include('script.php');
} else {
  header('location:../../');
}
?>