<?php
session_start();
if ($_SESSION['adminauth']) {
  require_once('../../includes/constant.php');
  require_once("../../includes/connection.php");
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MANAGE STUDENT</title>
    <?php include('header.php') ?>
   
  </head>

  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <?php include('sidebar.php') ?>
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid">

            <h2 class="text-center text-uppercase font-weight-bold mt-2 mb-2">Managed students</h2>
            <a href="#addnewstudent" class="btn btn-success btn-lg text-uppercase mt-2 mb-2" data-toggle="modal"><span class="fa fa-user-plus mr-2"></span>Add New student</a>
            <table id="tables" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Surname</th>
                  <th>Firstname</th>
                  <th>Middlename</th>
                  <th>Username</th>
                  <th>Class</th>
                  <th>Avatar</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i =0;
                $studentexamexist =[];
                $examexist =[];
               
                  $student = new Users;
                $su = $student->query("SELECT* FROM studentexmination");
                while($existexam = $student->data($su)){
                  $studentexamexist[] = $existexam['studentid'];
                }
                $su = $student->query("SELECT* FROM studentstartexamination");
                while($existexam = $student->data($su)){
                  $examexist[] = $existexam['studentid'];
                }
                
               
                  $q = $student->query("SELECT users.*, student.studentid AS studentid, class.name AS class, class.classid as classid FROM users
                  JOIN student ON users.userid = student.userid
                  JOIN class ON class.classid = student.class ORDER BY users.id DESC" );
                  while($data = $student->data($q)){
                ?>
                  <tr>
                    <td scope="row"><?php echo ++$i ?></td>
                    <td><?php echo ucwords($data['surname']) ?></td>
                    <td><?php echo ucwords($data['firstname']) ?></td>
                    <td><?php echo ucwords($data['lastname']) ?></td>
                    <td><?php echo $data['username'] ?></td>
                    <td><?php echo ucwords($data['class']) ?></td>
                    <td><img src="<?php echo !empty($data['avatar'])?$data['avatar']:"../students_avatar/avatar.png" ?>" class="rounded-circle" style="width: 60px;" alt="<?php echo $data['username'] ?>"></td>
                    <td>
                    <?php
                      if(in_array($data['studentid'], $studentexamexist)||in_array($data['studentid'], $examexist) ){
              //  
               ?>
                        <a href="#activestudent" class="btn btn-sm btn-danger"  data-toggle="modal"><span class="fa fa-trash p-2"></span></a> 
                     <?php
                      }else{
                     ?>
                      <a href="#deletestudent" class="btn btn-sm btn-danger"  data-toggle="modal" deletestudentconfirm="<?php echo $data['userid'] ?>"><span class="fa fa-trash p-2"></span></a> ||
                      <?php } ?>
                      <a href="#editstudent" class="btn btn-sm btn-primary" data-toggle="modal" editstudentconfirm="<?php echo $data['userid'] ?>"><span class="fa fa-edit p-2"></span></a>
                  
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
                  <div id="accordion">
                      <div class="collapse show"id="addbyname" data-parent="#accordion">
                        <div class="modal-body" >
                          <p class="error text-danger ml-3"></p>
                          <a href="#addbyfile" data-toggle="collapse" class="btn btn-sm btn-info">Upload student</a>
                          <form id="newstudentform" enctype="multipart/form-data" >
                            <div class="form-group">
                              <label for="email">Surname:</label>
                              <input type="text" class="form-control" name="sname">
                              <p class="text-danger sname"></p>

                              </div>
                              <div class="form-group">
                              <label for="email">Firstname:</label>
                              <input type="text" class="form-control" name="fname">
                              <p class="text-danger fname"></p>

                              </div>
                              <div class="form-group">
                              <label for="email">Middlename:</label>
                              <input type="text" class="form-control" name="lname">
                              <p class="text-danger lname"></p>

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
                              <p class="text-danger class"></p>

                              </div>
                              <div class="form-group">
                              <label for="">Avatar (passport)</label>
                                  <input type="file" class="form-control-file border" name="avatar">
                                  <p class="text-danger avatar"></p>

                                </div>

                          <input type="hidden"  name="addnewstudentbtn">
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                          <button class="btn btn-primary btn-block text-uppercase" type="submit" id="newstudentbtn">Add new student</button>
                        </div>
                        </form>
                      </div>

                      <div class="collapse"id="addbyfile" data-parent="#accordion">
                      <div class="modal-body" >
                          <p class="error text-danger ml-3"></p>
                          <a href="#addbyname" data-toggle="collapse" class="btn btn-sm btn-info">Add Student</a>

                          <form id="newstudentfileform" enctype="multipart/form-data" >
                            <div class="form-group">
                              <label for="email">Upload Student:<a href="<?=BASE_URL?>includes/Student_staff_upload.csv" class="btn btn-sm btn-danger" download>Download format</a></label>
                              <input type="file" class="form-control" name="studentfile">
                            </div>
                            <input type="hidden" name="addnewstudentfilebtn">
                          
                      </div>
                      <div class="modal-footer">
                          <button class="btn btn-primary btn-block text-uppercase" type="submit" id="newstudentfilebtn">Add new student</button>
                        </div>
                        </form>
                      </div>
                      

                      
                  </div>

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
      <?php include('footer.php'); ?>
      <?php
        include('script.php');
      ?>

    </div>
  </body>

  </html>



<?php } else {
  header('location:'.BASE_URL);
}
?>