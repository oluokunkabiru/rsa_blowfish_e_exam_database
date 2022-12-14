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
    <title> MANAGED SUBJECT</title>
    <?php include('header.php') ?>

  </head>

  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <?php include('sidebar.php') ?>
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid">

            <h2 class="text-center text-uppercase font-weight-bold mt-2 mb-2">Managed subject</h2>
            <a href="#addnewsubject" class="btn btn-success btn-lg text-uppercase mt-2 mb-2 " data-toggle="modal"><span class="fa fa-user-plus mr-2"></span>Add New subject</a>
            <table id="tables" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Subject name</th>
                  <th>Class</th>
                  <th>Date Created</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $class = new Classes;
                $examexist =[];
                $su = $class->query("SELECT* FROM examinations");
                while($existexam = $class->data($su)){
                  $examexist[] = $existexam['subjectid'];
                }
                $q = $class->query("SELECT subjects.*, class.id as classid, class.name as classname FROM subjects 
                JOIN class ON subjects.classid = class.classid ORDER BY id DESC ");
                $i = 1;
                while ($data = $class->data($q)) {


                ?>
                  <tr>
                    <td scope="row"><?php echo $i++ ?></td>
                    <td><?php echo $data['subjectname']  ?></td>
                    <td><?php echo $data['classname']  ?></td>
                    <td><?php echo $data['reg_date'] ?></td>
                    <td>
                    <?php
                      if(in_array($data['subjectid'], $examexist)){
                     ?>
                        <a href="#haveexamination" class="btn btn-sm btn-danger"  data-toggle="modal"><span class="fa fa-trash p-2"></span></a> 
                     <?php
                      }else{
                     ?>
                      <a href="#deletesubject" class="btn btn-sm btn-danger"  data-toggle="modal" deletesubjectconfirm="<?php echo $data['subjectid'] ?>"><span class="fa fa-trash p-2"></span></a> ||
                      <?php } ?>
                      <a href="#editsubject" class="btn btn-sm btn-primary" data-toggle="modal" editsubject="<?php echo $data['subjectid'] ?>"><span class="fa fa-edit p-2"></span></a>
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
          <div class="modal" id="haveexamination">
              <div class="modal-dialog">
                <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h3 class="modal-title text-uppercase font-weight-bold text-danger" >OOPS</h3>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                  <h1 class="text-danger">You can't delete this subject</h1>
                  <h4 class="text-danger">because you have create or setup  examination for this subject </h4>
                    
                  </div>

                  <!-- Modal footer -->
                  

                </div>
              </div>
            </div>

            <div class="modal" id="addnewsubject">
              <div class="modal-dialog">
                <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h3 class="modal-title text-uppercase font-weight-bold">add new subject</h3>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <p class="error text-danger ml-3"></p>
                    <form id="newsubjectform">
                      <div class="form-group">
                        <label for="email">Subject name:</label>
                        <input type="text" class="form-control" name="name">
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

                    <input type="hidden"  name="addnewsubjectbtn">
                  <!-- </div> -->

                    </form>
                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                    <button class="btn btn-primary btn-block text-uppercase" id="newsubjectbtn" name="addnewclass">Add</button>
                  </div>

                </div>
              </div>
            </div>
          </section>
          <!-- ///end class -->
          <!-- view class -->
          <div id="deletesubject" class="modal">
            <div class="modal-dialog">
              <div class="deletesubject">
              
              </div>
            </div>
          </div>
          <div id="editsubject" class="modal">
          <div class="modal-dialog">
              <div class="editsubject">
              
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
 $(document).ready(function () {


})
  </script>
<?php
  include('script.php');
} else {
  header('location:../../');
}
?>