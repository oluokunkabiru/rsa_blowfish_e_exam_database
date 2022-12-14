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
            <h2 class="text-center text-uppercase font-weight-bold py-4">Past question for <?php echo $user['classname'] ?></h2>

           
           
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
                <a href="view-questions.php?examinationid=<?php echo $examinationid ?>">
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