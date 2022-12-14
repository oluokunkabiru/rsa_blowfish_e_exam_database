<?php
session_start();
if ($_SESSION['adminauth']) {
  //  include('../../includes/connection.php');
  require_once('../../includes/constant.php');
  require_once("../../includes/connection.php");


?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACTIVE USERS</title>
    <?php include('header.php') ?>

  </head>

  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <?php include('sidebar.php') ?>
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid">

            <h2 class="text-center text-uppercase font-weight-bold mt-2 mb-2">active online </h2>
            <table id="tables" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Username</th>
                  <th>Role</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $class = new Classes;
                $q = $class->query("SELECT* FROM users WHERE active_status='1' ORDER BY surname DESC ");
                $i = 1;
                while ($data = $class->data($q)) {
                    $name = $data['surname']." ". $data['firstname']." ". $data['lastname'];
                    $username =$data['username'];
                    $role = $data['role'];
                    $userid = $data['userid'];
                ?>
                  <tr>
                    <td scope="row"><?php echo $i++ ?></td>
                    <td><?php echo ucwords($name)?></td>
                    <td><?php echo $username  ?></td>
                    <td><?php echo ucwords($role)  ?></td>
                   <td><a href="logmeout.php?user=<?php echo $userid ?>" class="btn btn-danger">Logout</a></td>
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
      <?php include('footer.php'); ?>
    </div>
  </body>

  </html>

<?php
  include('script.php');
} else {
  header('location:../../');
}
?>