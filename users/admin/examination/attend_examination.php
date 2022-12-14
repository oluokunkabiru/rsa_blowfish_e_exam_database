<?php
session_start();
if ($_SESSION['adminauth']) {
  //  include('../../includes/connection.php');
  require_once('../../../includes/constant.php');
  require_once("../../../includes/connection.php");
  $examid = $_GET['examinationid'];


?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATTENDED STUDENT FOR EXAMINATION</title>
    <?php include('../header.php') ?>

  </head>

  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <?php include('../sidebar.php') ?>
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid">

            <h2 class="text-center text-uppercase font-weight-bold mt-2 mb-2">EXAMINATION ATTENDED students</h2>
            <!-- <a href="#addtime" class="btn btn-success btn-lg text-uppercase mt-2 mb-2" data-toggle="modal"><span class="fa fa-clock mr-2"></span>Add time to this users</a> -->

            <table id="tables" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Surname</th>
                  <th>Firstname</th>
                  <th>Middlename</th>
                  <th>Username</th>
                  <th>Class</th>
                  <th>Examination</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i =0;
                  $student = new Users;
                  $q = $student->query("SELECT users.* , examinations.examname AS examname, studentstartexamination.studentid AS studentid, studentstartexamination.examinationid AS examinationid, class.name AS class, class.classid as classid FROM users
                  JOIN student ON users.userid = student.userid
                  JOIN class ON class.classid = student.class
                  JOIN studentstartexamination ON studentstartexamination.studentid = student.studentid
                  JOIN examinations ON examinations.examinationid = studentstartexamination.examinationid WHERE studentstartexamination.examinationid='$examid'");
                  while($data = $student->data($q)){
                ?>
                  <tr>
                    <td scope="row"><?php echo ++$i ?></td>
                    <td><?php echo ucwords($data['surname']) ?></td>
                    <td><?php echo ucwords($data['firstname']) ?></td>
                    <td><?php echo ucwords($data['lastname']) ?></td>
                    <td><?php echo $data['username'] ?></td>
                    <td><?php echo $data['class'] ?></td>
                    <td><?php echo $data['examname'] ?></td>
                    <td>
                      <a class="btn btn-sm btn-primary" href ="../students/checkresuls.php?examinationid=<?php echo $examid?>&student=<?php echo $data['userid'] ?>" > More  <span class="fa fa-angle-double-right p-2"></span></a>
                  
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
          
          <section>
            <div class="modal" id="addtime">
              <div class="modal-dialog">
                <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h3 class="modal-title text-uppercase font-weight-bold">add more time to user</h3>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <p class="error text-danger ml-3"></p>
                    <form id="addtimeform">
                      <label for="">Choose Time (Minutes)</label>
                    <select name="addtime" class="custom-select-sm">
                      <option value="">Choose Time</option>
                      <?php 
                        $i = 0;
                        while($i < 60){
                       ?> 
                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php 
                  $i++;
                  } ?>
                    
                  </select>
                  <input type="hidden" name="examid" value="<?php echo $examid ?>">
                    </form>
                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                    <button class="btn btn-primary btn-block text-uppercase" id="addtimebtn" name="addnewclass">Add time</button>
                  </div>

                </div>
              </div>
            </div>
          </section>
          <!-- view class -->
         
        </section>

      </div>
      <?php include('../footer.php'); ?>
    </div>
  </body>

  </html>
  <script>

<?php
  include('../script.php');
} else {
  header('location:'. LOGOUT );
}
?>