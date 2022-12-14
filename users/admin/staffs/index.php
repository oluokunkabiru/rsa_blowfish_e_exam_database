<?php
session_start();
if ($_SESSION['adminauth']) {
  //  include('../../includes/connection.php');
  require_once('../../../includes/constant.php');
  require_once("../../../includes/connection.php");




?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MANAGED STAFFS</title>
    <?php include('../header.php') ?>

  </head>

  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <?php include('../sidebar.php') ?>
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid">

            <h2 class="text-center text-uppercase font-weight-bold mt-2 mb-2">Managed staffs</h2>
            <a href="#addnewstaff" class="btn btn-success btn-lg text-uppercase mt-2 mb-2" data-toggle="modal"><span class="fa fa-user-plus mr-2"></span>Add New staff</a>
            <table id="tables" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Surname</th>
                  <th>Firstname</th>
                  <th>Middlename</th>
                  <th>Username</th>
                  <th>Avatar</th>
                  <th>Class</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i =0;
                  $staff = new Users;
                  $q = $staff->query("SELECT users.* , class.name AS class, class.classid as classid FROM users
                  JOIN teachers ON users.userid = teachers.userid
                  JOIN class ON class.classid = teachers.classid ORDER BY users.id DESC" );
                  while($data = $staff->data($q)){
                    $avatar = !empty($data['avatar'])?$data['avatar']:"staffs_avatar/staffs_avatar.png";

                ?>
                  <tr>
                    <td scope="row"><?php echo ++$i ?></td>
                    <td><?php echo ucwords($data['surname']) ?></td>
                    <td><?php echo ucwords($data['firstname']) ?></td>
                    <td><?php echo ucwords($data['lastname']) ?></td>
                    <td><?php echo $data['username'] ?></td>
                    <td><img src="../../<?php echo $avatar ?>" class="rounded-circle" style="width: 60px;" alt="<?php echo $data['username'] ?>"></td>

                    <td><?php echo $data['class'] ?></td>
                    <td>
                      <a href="#deletestaff" class="btn btn-sm btn-danger"  data-toggle="modal" deletestaffconfirm="<?php echo $data['userid'] ?>"><span class="fa fa-trash p-2"></span></a> ||
                      <a href="#editstaff" class="btn btn-sm btn-primary" data-toggle="modal" editstaffconfirm="<?php echo $data['userid'] ?>"><span class="fa fa-edit p-2"></span></a>
                  
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
          <!-- modal alert for delete and edit staff -->
          <!-- edit -->
          <div class="modal" id="editstaff">
          <div class="modal-dialog">
              <div class="editstaff">
              
              </div>
            </div>
          </div>
          <!-- delete -->
          <div class="modal" id="showstaff">
             <div class="modal-dialog">
              <div class="showstaff">
              
              </div>
            </div>
          </div>
          <div class="modal" id="deletestaff">
          <div class="modal-dialog">
              <div class="deletestaff">
              
              </div>
            </div>
          </div>
          <!-- add new class -->
          <section>
            <div class="modal" id="addnewstaff">
              <div class="modal-dialog">
                <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h3 class="modal-title text-uppercase font-weight-bold">add new staff</h3>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>

                  <!-- Modal body -->

                  <div id="accordion">
                      <div class="collapse show"id="addbyname" data-parent="#accordion">
                        <div class="modal-body" >
                          <p class="error text-danger ml-3"></p>
                          <a href="#addbyfile" data-toggle="collapse" class="btn btn-sm btn-info">Upload staffs</a>
                          <form id="newstaffform" enctype="multipart/form-data" >
                            <div class="form-group">
                              <label for="email">Surname:</label>
                              <input type="text" class="form-control" name="sname">
                              </div>
                              <div class="form-group">
                              <label for="email">Firstname:</label>
                              <input type="text" class="form-control" name="fname">
                              </div>
                              <div class="form-group">
                              <label for="email">Middle Name:</label>
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
                              <div class="form-group">
                              <label for="">Avatar (passport)</label>
                                  <input type="file" class="form-control-file border" name="avatar">
                                </div>

                                <input type="hidden"  name="addnewstaffbtn">
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                          <button class="btn btn-primary btn-block text-uppercase" type="submit" id="newstudentbtn">Add new staf</button>
                        </div>
                        </form>
                      </div>

                      <div class="collapse"id="addbyfile" data-parent="#accordion">
                      <div class="modal-body" >
                          <p class="error text-danger ml-3"></p>
                          <a href="#addbyname" data-toggle="collapse" class="btn btn-sm btn-info">Add Staff</a>

                          <form id="newstafffileform" enctype="multipart/form-data" >
                            <div class="form-group">
                              <label for="email">Upload Staffs: <a href="../../../includes/Student_staff_upload.csv" class="btn btn-sm btn-danger" download>Download format</a></label>
                              <input type="file" class="form-control" name="stafffile">
                            </div>
                            <input type="hidden" name="addnewstafffilebtn">
                          
                      </div>
                      <div class="modal-footer">
                          <button class="btn btn-primary btn-block text-uppercase" type="submit" id="newstudentfilebtn">Add new staffs</button>
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
    </div>
  </body>

  </html>

<?php
  include('../script.php');
} else {
  header('location:../../');
}
?>