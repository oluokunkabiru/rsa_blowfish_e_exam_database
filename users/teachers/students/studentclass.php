<?php
session_start();
require_once('../../../includes/constant.php');
require_once("../../../includes/connection.php");
if ($_SESSION['teacherauth']) {
  //  include('../../includes/connection.php');


?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MANAGED CLASS</title>
    <?php include('../header.php') ?>

  </head>

  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <?php include('../sidebar.php') ?>
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid">

            <h2 class="text-center text-uppercase font-weight-bold mt-2 mb-2">Managed class</h2>
            <table id="tables" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Total Student</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                
                $class = new Classes;
                $q = $class->query("SELECT* FROM class ORDER BY id DESC ");
                $i = 1;
               

                while ($data = $class->data($q)) {
                    $classid = $data['classid'];
                    $to = $class->query("SELECT COUNT(studentid) AS totalstudent FROM student WHERE class = '$classid'");
                    $total = $class->data($to);
                    $totalstudent = isset($total['totalstudent'])?$total['totalstudent']:0;
                    
                ?>
                  <tr>
                    <td scope="row"><?php echo $i++ ?></td>
                    <td><?php echo $data['name']  ?></td>
                    <td>
                        <?php echo $totalstudent ?>
                    </td>
                    <td>
                     <a href="student-list.php?classid=<?php echo $classid ?>" class="btn btn-primary btn-rounded text-uppercase">view students</a>
                    </td>
                  </tr>
                <?php
                }
                ?>

              </tbody>

            </table>
          </div>
          <!-- add new class -->
       
        

        </section>

      </div>
      <?php include('../footer.php'); ?>
    </div>
  </body>

  </html>
  <script>
    $(document).ready(function() {


    })
  </script>
<?php
  include('../script.php');
} else {
  header('location:../../');
}
?>