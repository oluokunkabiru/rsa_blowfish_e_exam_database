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
    <title>MANAGE STUDENT</title>
    <?php include('../header.php') ?>

  </head>

  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <?php include('../sidebar.php') ?>
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid">
          <?php
          $classid = $_GET['classid'];
          $classes = new Users;
          $c = $classes->query("SELECT* FROM class WHERE classid = '$classid'");
          $d = $classes->data($c);
          $classname = isset($d['name'])?$d['name']:"";
          if(!empty($classname)){
          
          ?>

            <h2 class="text-center text-uppercase font-weight-bold pt-2 mb-2"> <?php echo $classname ?> students</h2>
            <table id="tables" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Surname</th>
                  <th>Firstname</th>
                  <th>Lastname</th>
                  <th>Username</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i =0;
              
                  $student = new Users;
               
                  $q = $student->query("SELECT users.*, student.studentid AS studentid, class.name AS class, class.classid as classid FROM users
                  JOIN student ON users.userid = student.userid
                  JOIN class ON class.classid = student.class WHERE class.classid='$classid'" );
                  while($data = $student->data($q)){
                ?>
                  <tr>
                    <td scope="row"><?php echo ++$i ?></td>
                    <td><?php echo ucwords($data['surname']) ?></td>
                    <td><?php echo ucwords($data['firstname']) ?></td>
                    <td><?php echo ucwords($data['lastname']) ?></td>
                    <td><?php echo $data['username'] ?></td>
                    <td> <a href="checkresult.php?student=<?php echo $data['userid']  ?>" class="btn btn-success text-uppercase">Check result</a>   </td>

                  </tr>
                  <?php } ?>
              </tbody>
              <tbody>
                
               
               

              </tbody>

            </table>
          </div>
          <?php }else{ ?>
                <h3 class="text-danger text-center font-weight-bold text-uppercase py-5">no student in this class</h3>
          <?php } ?>
          <!-- modal alert for delete and edit student -->
          <!-- edit -->

          <div class="modal" id="activestudent">
              <div class="modal-dialog">
                <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h3 class="modal-title text-uppercase font-weight-bold text-danger" >OOPS</h3>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                  <h1 class="text-danger">You can't delete this student</h1>
                  <h4 class="text-danger">because he/she have perform in some school activities, try to erase all his/her activities including examinations </h4>
                    
                  </div>

                  <!-- Modal footer -->
                  

                </div>
              </div>
            </div>


          <div class="modal" id="editstudent">
          <div class="modal-dialog">
              <div class="editstudent">
              
              </div>
            </div>
          </div>
          <!-- delete -->
          <div class="modal" id="showstudent">
             <div class="modal-dialog">
              <div class="showstudent">
              
              </div>
            </div>
          </div>
          <div class="modal" id="deletestudent">
          <div class="modal-dialog">
              <div class="deletestudent">
              
              </div>
            </div>
          </div>
          <!-- add new class -->
          <section>
            <div class="modal" id="addnewstudent">
              <div class="modal-dialog">
                <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h3 class="modal-title text-uppercase font-weight-bold">add new student</h3>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <p class="error text-danger ml-3"></p>
                    <form id="newstudentform">
                      <div class="form-group">
                        <label for="email">Surname:</label>
                        <input type="text" class="form-control" name="sname">
                         </div>
                         <div class="form-group">
                        <label for="email">Firstname:</label>
                        <input type="text" class="form-control" name="fname">
                         </div>
                         <div class="form-group">
                        <label for="email">Lastname:</label>
                        <input type="text" class="form-control" name="lname">
                         </div>
                         <div class="form-group">
                        <label for="email">Class:</label>
                        <select name="class" class="custom-select">
                        <option value="">Select class</option>
                        <?php
                          $class = new Classes;
                          $q = $class->query("SELECT* FROM class");
                          while($data = $class->data($q))
                          {
                        ?>
                        
                        <option value="<?php echo $data['classid'] ?>"><?php echo $data['name'] ?></option>
                          <?php } ?>
                        </select>

                         </div>

                    <input type="hidden"  name="addnewstudentbtn">
                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                    <button class="btn btn-primary btn-block text-uppercase" id="newstudentbtn">Add new student</button>
                  </div>
              </form>
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
          <div id="editclass" class="modal"></div>

        </section>

      </div>
      <?php include('../footer.php'); ?>
    </div>
  </body>

  </html>
  <script>
 $(document).ready(function () {


})
  </script>
<?php
  include('../script.php');
} else {
  header('location:../../');
}
?>