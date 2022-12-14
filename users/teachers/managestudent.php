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
    <title> MANAGED STUDENT</title>
    <?php include('header.php') ?>

  </head>

  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <?php include('sidebar.php') ?>
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid">

            <h2 class="text-center text-uppercase font-weight-bold mt-2 mb-2">Managed students</h2>
            <table id="tables" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Surname</th>
                  <th>Firstname</th>
                  <th>Lastname</th>
                  <th>Username</th>
                  <th>Avatar</th>
                  <th>Class</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i =0;
                $teacherclass = $user['classid'];
                  $student = new Users;
                  $q = $student->query("SELECT users.* , class.name AS class, class.classid as classid FROM users
                  JOIN student ON users.userid = student.userid
                  JOIN class ON class.classid = student.class WHERE class.classid = '$teacherclass' ");
                  while($data = $student->data($q)){
                ?>
                  <tr>
                    <td scope="row"><?php echo ++$i ?></td>
                    <td><?php echo ucwords($data['surname']) ?></td>
                    <td><?php echo ucwords($data['firstname']) ?></td>
                    <td><?php echo ucwords($data['lastname']) ?></td>
                    <td><?php echo $data['username'] ?></td>
                    <td><img src="<?php echo !empty($data['avatar'])?$data['avatar']:"../students_avatar/avatar.png" ?>" class="rounded-circle" style="width: 60px;" alt="<?php echo $data['username'] ?>"></td>

                    <td><?php echo $data['class'] ?></td>
                    <td>
                      <a href="students/checkresult.php?student=<?php echo $data['userid']  ?>" class="btn btn-success" >More <span class="ml-2 fa fa-angle-double-right"></span></a>
                  
                    </td>
                  </tr>
                  <?php } ?>
              </tbody>
              <tbody>
                <?php
                
                ?>
               
               

              </tbody>

            </table>
          </div>
          <!-- modal alert for delete and edit student -->
          <!-- edit -->
         
        </section>

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