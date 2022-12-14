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
    <title> MANAGED CLASS</title>
    <?php include('header.php') ?>

  </head>

  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <?php include('sidebar.php') ?>
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid">

            <h2 class="text-center text-uppercase font-weight-bold mt-2 mb-2">Managed class</h2>
            <a href="#addnewclass" class="btn btn-success btn-lg text-uppercase mt-2 mb-2" data-toggle="modal"><span class="fa fa-user-plus mr-2"></span>Add New Class</a>
            <table id="tables" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Total Student</th>
                  <th>Date Created</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $subjectexist = [];
                $examexist = [];
                $studentexist = [];
                $teacherexist = [];
                $class = new Classes;
                $q = $class->query("SELECT* FROM class ORDER BY id DESC ");
                $i = 1;
                $su = $class->query("SELECT* FROM subjects");
                while ($existexam = $class->data($su)) {
                  $subjectexist[] = $existexam['classid'];
                }
                $su = $class->query("SELECT* FROM examinations");
                while ($existexam = $class->data($su)) {
                  $examexist[] = $existexam['classid'];
                }
                $su = $class->query("SELECT* FROM student");
                while ($existexam = $class->data($su)) {
                  $studentexist[] = $existexam['class'];
                }

                $su = $class->query("SELECT* FROM teachers");
                while ($existexam = $class->data($su)) {
                  $teacherexist[] = $existexam['classid'];
                }

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
                    <td><?php echo $data['reg_date'] ?></td>
                    <td>
                    <a href="students/student-list.php?classid=<?php echo $classid ?>" class="btn btn-success btn-rounded text-uppercase">view students</a>
                      <a href="#editclass" class="btn btn-sm btn-primary col-6" data-toggle="modal" editclass="<?php echo $data['classid'] ?>"><span class="fa fa-edit p-2"></span></a>
                      <?php
                      if (in_array($data['classid'], $subjectexist) || in_array($data['classid'], $teacherexist) || in_array($data['classid'], $studentexist) || in_array($data['classid'], $examexist)) {
                      ?>
                        <a href="#existdeleteclass" class="btn btn-sm btn-danger col-6" data-toggle="modal"><span class="fa fa-trash p-2"></span></a>
                      <?php
                      } else { 
                      ?>
                        <a href="#deleteclass" class="btn btn-sm btn-danger col-6" data-toggle="modal" deleteclassconfirm="<?php echo $data['classid'] ?>"><span class="fa fa-trash p-2"></span></a>
                      <?php } ?>

                    </td>
                  </tr>
                <?php
                }
                ?>

              </tbody>

            </table>
          </div>
          <!-- add new class -->
          <section>
            <div class="modal" id="addnewclass">
              <div class="modal-dialog">
                <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h3 class="modal-title text-uppercase font-weight-bold">add new class</h3>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <p class="error text-danger ml-3"></p>
                    <form id="newclassform">
                      <div class="form-group">
                        <label for="email">Class name:</label>
                        <input type="text" class="form-control" name="name">
                      </div>
                    </form>
                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                    <button class="btn btn-primary btn-block text-uppercase" id="newclassbtn" name="addnewclass">Add</button>
                  </div>

                </div>
              </div>
            </div>

            <div class="modal" id="existdeleteclass">
              <div class="modal-dialog">
                <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h3 class="modal-title text-uppercase font-weight-bold text-danger">OOPS</h3>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <h1 class="text-danger">You can't delete this class</h1>
                    <h4 class="text-danger">because you there is student or teachers or subject or examination are associated with this class </h4>

                  </div>

                  <!-- Modal footer -->


                </div>
              </div>
            </div>

          </section>
          <!-- ///end class -->
          <!-- view class -->
          <div id="deleteclass" class="modal">
            <div class="modal-dialog">
              <div class="deleteclass">

              </div>
            </div>
          </div>
          <div id="editclass" class="modal">
            <div class="modal-dialog">
              <div class="editclass">

              </div>
            </div>
          </div>

        </section>

      </div>
      <?php include('footer.php'); ?>
    </div>
  </body>

  </html>
  <script>
    $(document).ready(function() {


    })
  </script>
<?php
  include('script.php');
} else {
  header('location:../../');
}
?>