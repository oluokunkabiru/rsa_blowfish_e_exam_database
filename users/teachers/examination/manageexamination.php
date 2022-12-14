<?php
session_start();
if ($_SESSION['teacherauth']) {
  //  include('../../includes/connection.php');
  require_once('../../../includes/constant.php');
  require_once("../../../includes/connection.php");
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> MANAGED EXAMINATION</title>
    <?php include '../header.php' ?>

  </head>

  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <?php include '../sidebar.php';


      ?>
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid">
            <h2 class="text-center text-uppercase font-weight-bold mt-2 mb-2">Manage Examination</h2>
            <a href="#addnewexaminationmodal" class="btn btn-success btn-lg text-uppercase my-2" data-toggle="modal">add new examination</a>
            <div class="table-responsive">
              <table id="tables" class="table table-bordered table-hover table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Subject</th>
                    <th>Class</th>
                    <th>Validity</th>
                    <th>Duration</th>
                    <th>Secret Code</th>
                    <th>Visibility</th>
                    <!-- <th>Camera</th> -->
                    <th>Total Questions</th>
                    <th>Total Student attend</th>
                    <th>Status</th>

                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>

                  <?php
                  $exam = new Examination;
                  if (isset($_GET['camerastatus'])) {
                    $cameraStatus = $_GET['camerastatus'];
                    $examinationidcam = $_GET['examinationid'];
                    $cam =  $exam->query("UPDATE examinations SET camera_status='$cameraStatus' WHERE examinationid = '$examinationidcam'");
                    if ($cam) {
                      echo "<script>
                              window.location.assign('manageexamination.php');
                          </script>";
                    }
                  }

                  $q = $exam->query("SELECT examinations.*, class.name AS classname, subjects.subjectname AS
                subjectname FROM examinations JOIN class ON class.classid = examinations.classid JOIN subjects
                ON subjects.subjectid = examinations.subjectid ORDER BY startdate DESC ");
                  $i = 1;
                  while ($data = $exam->data($q)) {
                    $cameraon = '<a href="manageexamination.php?camerastatus=off&examinationid=' . $data['examinationid'] . '" class="btn btn-success"><span class="badge badge-pill badge-light">ON</span></a> ';
                    $cameraoff = '<a href="manageexamination.php?camerastatus=on&examinationid=' . $data['examinationid'] . '" class="btn btn-danger"><span class="badge badge-pill badge-light">OFF</span></a> ';
                    $camera =  $data['camera_status'] == "on" ? $cameraon : $cameraoff;

                  ?>
                    <tr>
                      <td scope="row"><?php echo $i++ ?></td>
                      <td><?php echo ucwords($data['subjectname']) ?></td>
                      <td><?php echo ucwords($data['classname']) ?></td>
                      <td><?php echo date_format(date_create($data['startdate']), "d/m/Y h:s:ia") . " <strong>To</strong> " . date_format(date_create($data['enddate']), "d/m/Y h:s:ia") ?></td>
                      <td><?php echo $data['duration'] ?></td>
                      <td><?php echo $data['examinationpin'] ?></td>
                      <td><?php echo ucwords($data['visibility']) ?></td>


                      <?php
                      $exid = $data['examinationid'];
                      $qu = $exam->query("SELECT COUNT(id) AS totalquest FROM questions WHERE examinationid='$exid' ");
                      $tot = $exam->data($qu);

                      ?>
                      <td><?php echo $tot['totalquest'] ?></td>
                      <?php
                      // total student sit
                      $st = $exam->query("SELECT COUNT(studentstartexamination.examinationid) AS studentsit FROM studentstartexamination WHERE studentstartexamination.examinationid = '$exid'");
                      $totalst = $exam->data($st);

                      // total student online
                      $stonline = $exam->query("SELECT COUNT(studentstartexamination.examinationid) AS studentonline FROM studentstartexamination WHERE studentstartexamination.examinationid = '$exid' AND active_status='1'");
                      $totalstudentonline = $exam->data($stonline);
                      $onlinestudent = $totalstudentonline['studentonline'];
                      ?>
                      <td>
                        <?php if ($totalst['studentsit'] > 0) { ?>
                          <a href="attend_examination.php?examinationid=<?php echo $exid ?>" class="font-weight-bold"><?php echo $totalst['studentsit'] ?></a>
                        <?php } else {
                        ?>
                          0
                        <?php
                        } ?>
                        <?php if ($onlinestudent > 0) { ?>
                          <a href="active.php?examinationid=<?php echo $exid ?>">
                            <div class="float-right bg-white rounded">
                              <i class="fa fa-toggle-on text-success  p-2">
                                <span class="badge badge-info"><?php echo $onlinestudent ?></span>
                              </i>
                            </div>

                          </a>
                        <?php } ?>
                      </td>
                      <td><?php
                          if (strtotime($data['enddate']) >= strtotime(date("Y/m/d"))) {
                            // echo "End date = ". strtotime($data['enddate'])." today = ".strtotime(date("Y/m/d"));
                            if ($data['status'] == "unavailable") {

                          ?>
                            <p class="text-danger"><?php echo ucwords($data['status']) ?></p>
                            <a href="#activateexamination" class="btn btn-sm btn-success p-2 text-uppercase" data-toggle="modal" activateexamination="<?php echo $data['examinationid'] ?>">Actvate
                            </a>
                          <?php
                            } elseif ($data['status'] == "available") {

                          ?>
                            <p class="text-success"><?php echo ucwords($data['status']) ?></p>

                            <a href="#deactivateexamination" class="btn btn-sm p-2 btn-danger  text-uppercase" data-toggle="modal" deactivateexamination="<?php echo $data['examinationid'] ?>">deactvate
                            </a>
                          <?php
                            } else {
                          ?>
                            <p class="text-success"><?php echo ucwords($data['status']) ?></p>
                            <a href="#stopexamination" class="btn btn-sm p-2 btn-danger  text-uppercase" data-toggle="modal" stopexamination="<?php echo $data['examinationid'] ?>">stop
                            </a>
                          <?php
                            }
                          } else {

                          ?>
                          <p class="btn btn-primary text-uppercase btn-lg">Over</p>
                        <?php
                          }
                        ?>
                      </td>

                      <td>
                        <?php
                        if ($data['status'] != "progress" && $data['status'] != "written") {

                        ?>

                          <!-- <i class="fa-regular fa-comment-question"></i> -->

                          <a href="examinationquestions.php?examinationid=<?php echo $data['examinationid'] ?>&&view=question" class="btn btn-sm btn-success m-1"> <span class="fa fa-plus"></span><span class="fa fa-question p-2"> </span></a>
                          <a href="#editexamination" class="btn btn-sm btn-primary m-1" data-toggle="modal" editexamination="<?php echo $data['examinationid'] ?>"><span class="fa fa-edit p-2"></span></a>
                          <a href="#deleteexamination" class="btn btn-sm btn-danger m-1" data-toggle="modal" deleteexamination="<?php echo $data['examinationid'] ?>"><span class="fa fa-trash p-2"></span></a>
                          <a href="../past-questions/view-questions.php?examinationid=<?php echo  $data['examinationid'] ?>" class="btn btn-sm btn-warning m-1"><span class="fa fa-eye p-2"></span></a>

                        <?php
                        } else {
                        ?>
                          <p class="text-success"><?php echo ucwords($data['status']) . "..." ?></p>
                          <?php if ($data['status'] != "written") { ?>
                            <a href="#stopexamination" class="btn btn-sm p-2 btn-danger  text-uppercase" data-toggle="modal" stopexamination="<?php echo $data['examinationid'] ?>">stop
                            </a>

                        <?php
                          }
                        }
                        ?>

                        <?php echo $camera ?>
                      </td>
                    </tr>
                  <?php
                  }
                  ?>

                </tbody>

              </table>
            </div>
          </div>
          <!-- add new examination -->
          <div class="modal" id="addnewexaminationmodal">
            <div class="modal-dialog">
              <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">

                  <div class="card card-info">
                    <div class="card-header">
                      <h3 class="card-title">
                        <h3 class="text-center text-uppercase font-weight-bold">new examination</h3>
                      </h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <p class="error text-danger pl-4"></p>
                    <form class="form-horizontal" id="newexaminationform">
                      <div class="card-body">
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">Select Class</label>
                          <div class="col-sm-8">
                            <select class="custom-select" onchange="selectclass(this.value)" name="examclass">
                              <option value="">Select class</option>
                              <?php
                              $class = new Classes;
                              $q = $class->query("SELECT* FROM class");
                              while ($data = $class->data($q)) {
                                echo '<option value="' . $data['classid'] . '">' . $data['name'] . '</option>';
                              }

                              ?>

                            </select>

                            <p class="text-danger examclass"></p>

                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">Select Subject</label>
                          <div class="col-sm-8">
                            <select class="custom-select" id="classsubject" name="classsubject">

                            </select>

                            <p class="text-danger classsubject"></p>

                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">Examination Description</label>
                          <div class="col-sm-8">
                            <textarea class="form-control" name="examinationdescription" rows="3" placeholder="Enter examination Description"></textarea>
                            <p class="text-danger examinationdescription"></p>

                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="" class="col-sm-4 col-form-label">Examination Name</label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control" name="examname" placeholder="Enter examination name">
                            <p class="text-danger examname"></p>

                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="" class="col-sm-4 col-form-label">Examination Duration(Mins)</label>
                          <div class="col-sm-8">
                            <input type="number" class="form-control" name="duration" placeholder="Duration Time">
                            <p class="text-danger duration"></p>

                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="" class="col-sm-4 col-form-label">Examination From (DD/MM/YY)</label>
                          <div class="col-sm-8">
                            <input type="date" name="startdate" class="form-control float-right" id="startdate">
                            <p class="text-danger startdate"></p>

                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="" class="col-sm-4 col-form-label">Examination To (DD/MM/YY)</label>
                          <div class="col-sm-8">
                            <input type="date" class="form-control" name="enddate" id="enddate" placeholder="Enter End date name">
                            <p class="text-danger enddate"></p>

                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="" class="col-sm-4 col-form-label">Examination PIN</label>
                          <div class="col-sm-8">
                            <input type="number" class="form-control" name="exampasscode" placeholder="Pass Code">
                            <p class="text-danger exampasscode"></p>

                          </div>
                        </div>
                        <!-- <div class="form-group row">
                          <label for="" class="col-sm-4 col-form-label">Examination Total score (%)</label>
                          <div class="col-sm-8">
                            <input type="number" min="0" max="100" class="form-control" name="tscore" placeholder="e.g 40%">
                          </div>
                        </div> -->
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">Exam result visibility to students </label>
                          <div class="col-sm-8">
                            <select class="custom-select" name="visibility">
                              <option value=""> Select visibility</option>
                              <option value="yes">Yes</option>
                              <option value="no">No</option>

                            </select>
                            <p class="text-danger visibility"></p>

                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">Question display </label>
                          <div class="col-sm-8">
                            <select class="custom-select" name="display">
                              <option value=""> Select question display</option>
                              <option value="random">Random</option>
                              <option value="static">Static</option>

                            </select>
                            <p class="text-danger display"></p>

                          </div>
                        </div>
                      </div>
                      <!-- /.card-body -->
                      <div class="card-footer">
                        <button id="newexaminationbtn" class="btn btn-primary btn-block btn-lg text-uppercase">Create new examination</button>
                      </div>
                      <!-- /.card-footer -->
                    </form>
                  </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

              </div>
            </div>
          </div>
          <!-- add new class -->

          <!-- ///end class -->
          <!-- view class -->
          <div id="deleteexamination" class="modal">
            <div class="modal-dialog">
              <div class="deleteexamination">

              </div>
            </div>
          </div>
          <div id="activateexamination" class="modal">
            <div class="modal-dialog">
              <div class="activateexamination">

              </div>
            </div>
          </div>
          <div id="stopexamination" class="modal">
            <div class="modal-dialog">
              <div class="stopexamination">

              </div>
            </div>
          </div>
          <div id="deactivateexamination" class="modal">
            <div class="modal-dialog">
              <div class="deactivateexamination">

              </div>
            </div>
          </div>
          <div id="editexamination" class="modal">
            <div class="modal-dialog">
              <div class="editexamination">

              </div>
            </div>
          </div>

        </section>

      </div>
      <?php
      include '../footer.php';
      include '../script.php';

      ?>
    </div>
  </body>

  </html>

  <script>
    function selectclass(str) {
      // var classe = "";
      // alert(str);
      if (str.length == 0) {
        document.getElementById("classsubject").innerHTML = "";
        return;
      } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            var result = document.getElementById("classsubject")
            var ret = this.responseText
            result.innerHTML = ret;
            // classe = str;
          }
        };
        xmlhttp.open("GET", "../confirm.php?classselectedforexam=" + str, true);
        xmlhttp.send();
      }
    }
  </script>
<?php
} else {
  header('location:' . TEACHER);
}
?>


<script>

</script>