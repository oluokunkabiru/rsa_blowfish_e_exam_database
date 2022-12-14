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
    <title>CHECK STUDENT RESULT</title>
    <?php include('../header.php') ?>

  </head>

  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <?php include('../sidebar.php') ?>
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid">
            <h2 class="text-center text-uppercase font-weight-bold mt-2 mb-2">Check student result</h2>

            <div class="row my-4">
            <div class="col-md-3 col-sm-2"></div>
            <div class="col-md-6 col-sm-8">
              <form>
              <label for="email">Enter Student name or student ID</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text fa fa-user font-weight-bold"></span>
                </div>
                <input type="text" class="form-control" oninput="searchstudent(this.value)" placeholder="Student ID or student names">
              </div>
              </form> 

              <div class="my-2">
              <a href="student-list.php?classid=<?php echo $teacherClass ?>" class="btn btn-primary btn-rounded text-uppercase btn-block">view students</a>
            </div>
            </div>
            <div class="col-md-3 col-sm-2"></div>
            </div>
           
            <div class="result" id="searchstudent"></div>
            <div class="row">

            <?php
              $today = date('Y-m-d');
              $teacherclass = $user['classid'];
             $exam = new Examination;
             $q = $exam->query("SELECT examinations.*, class.name AS classname, subjects.subjectname AS
             subjectname FROM examinations JOIN class ON class.classid = examinations.classid JOIN subjects
             ON subjects.subjectid = examinations.subjectid WHERE examinations.enddate < '$today' AND examinations.classid ='$teacherclass' ORDER BY enddate DESC ");
             $i = 1;
             while ($data = $exam->data($q)) {
               $examinationid = $data['examinationid'];
            ?>
              <div class="col-md-4">
                <a href="resultlist.php?examinationid=<?php echo $examinationid ?>">
                <div class="card text-dark">
                  <div class="card-header">
                     <h4><?php echo ucwords($data['classname']) ?></h4>
                     <p><?php echo ucfirst($data['description']) ?></p>
                  </div>
                  <div class="card-body">
                  
                    <div class="row">
                      <div class="col"><p>Subject name</p></div>
                      <div class="col"><p><strong><?php echo ucwords($data['subjectname']) ?></strong></p></div>
                    </div>
                    <div class="row">
                      <div class="col"><p>Examination date</p></div>
                      <div class="col"><p><strong><?php echo date_format(date_create($data['enddate']), "d/m/Y h:s:ia") ?></strong></p></div>
                    </div>
                    <div class="row">
                      <div class="col"><p>Total student attent</p></div>
                      <?php
                       $st = $exam->query("SELECT COUNT(studentstartexamination.examinationid) AS studentsit FROM studentstartexamination WHERE studentstartexamination.examinationid ='$examinationid' ");
                       $totalst = $exam->data($st);
                      ?>
                      <div class="col"><p><strong><?php echo $totalst['studentsit'] ?></strong></p></div>
                    </div>
                   
                  </div>
                </div>
                </a>
              </div>
          <?php } ?>
            </div>
        </section>

      </div>
      <?php include('../footer.php'); ?>
    </div>
  </body>

  </html>


<?php
  include('../script.php');
} else {
  header('location:../../../');
}
?>

<script>
  function searchstudent(str) {
  // var classe = "";
  // alert(str);
  if (str.length == 0) {
      document.getElementById("searchstudent").innerHTML = "";
      return;
  } else {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            var result = document.getElementById("searchstudent")
              var ret = this.responseText
              result.innerHTML = ret;
              // classe = str;
          }
      };
      xmlhttp.open("GET", "searchstudent.php?student="+str, true);
      xmlhttp.send();
  }
} 
  
</script>