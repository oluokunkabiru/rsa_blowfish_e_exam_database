<?php
session_start();
require_once('../../../includes/constant.php');
  require_once("../../../includes/connection.php");
if ($_SESSION['adminauth']) {
    // include("../../../includes/connection.php");
    if(isset($_GET['examinationid'])){
    $examinationid = $_GET['examinationid'];

    }
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHECK STUDENT RESULT</title>
    <?php include('../header.php') ?>
    <style>
       @media print{
            body *{
                visibility:hidden;
            }
            .noprint *{
              visibility:hidden;

            }
            #printresult, #printresult *{
                visibility: visible;
            }
            #printresult{
                position: absolute;
                left: 0;
                right: 0;
            }
        }
    </style>

  </head>

  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <?php include('../sidebar.php') ?>
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid" id="printresult">
              <?php
               $exam = new Users;
                if(!empty($examinationid)){
                    $examd = $exam->query("SELECT examinations.*, class.name AS classname, subjects.subjectname AS
                    subjectname FROM examinations JOIN class ON class.classid = examinations.classid JOIN subjects
                    ON subjects.subjectid = examinations.subjectid WHERE examinations.examinationid = '$examinationid' ");
                    $examdetails = $exam->data($examd);
                if(!empty(trim($examdetails['subjectname']))){
                      
              ?>
            <h2 class="text-center text-uppercase font-weight-bold py-4">Check <?php echo $examdetails['subjectname'] ?> for <?php echo $examdetails['classname'] ?> result</h2>
                  
                        <a href="printresult.php?examinationid=<?php echo $examinationid ?>" class="btn noprint btn-primary btn-lg my-2"><span class="fa fa-download"></span> Download</a>
                        <button class="btn btn-success btn-lg noprint" onclick="window.print()"> <span class="fa fa-print"></span> Print</button>

                    <div class="card p-3">

                    <table id="tables" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Score</th>
                                <th>Total score (%)</th>
                                <th>Grade</th>
                                <th>Remark</th>
                                <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $q = $exam->query("SELECT users.userid AS userid, users.surname AS sname, users.firstname AS fname, users.lastname AS lname, student.class, class.name AS cclass,  studentstartexamination.* FROM users JOIN student ON users.userid=student.userid JOIN studentstartexamination ON studentstartexamination.studentid = student.studentid JOIN class ON student.class = class.classid WHERE studentstartexamination.examinationid = '$examinationid' ");
                                // $data = $exam->data($q);
                                $i = 1;
                                while($data = $exam->data($q)){

                                    $studentnames = $data['sname']." ". $data['fname']." ". $data['lname'];
                                ?>
                                
                                <tr>
                                    <td scope="row"><?php echo $i++ ?></td>
                                <td><?php echo ucwords($studentnames) ?></td>
                                <td><?php echo ($exam->sumOfScore($data['examinationid'], $data['studentid'])['totalmark']) ."/". ($exam->totalQuestions($data['examinationid'])['totalmark'])?></td>
                                <td><?php echo ucwords($data['score']) ?></td>                                
                                <td><?php echo ucwords($data['grade']) ?></td>                                
                                <td><?php echo ucwords($data['remark']) ?></td>                                
                                <td><a href="checkresuls.php?student=<?php echo $data['userid']?>&&examinationid=<?php echo $examinationid ?>" class="btn btn-primary"> More <span class="ml-2 fa fa-angle-double-right"></span></a></td>
                                </tr>
                                
                                
                                <?php } ?>
                            </tbody>
                    </table>
                    </div>
               
           <?php 
                }else{

                    ?>

                <h3 class="text-danger text-center"> No examination with <strong> <?php echo $examinationid ?> </strong> details</h3>
        <?php
            }
        }else{

           ?>
            <h3 class="text-danger text-center text-uppercase"><strong> Oops !!! </strong></h3>
            <h4 class="text-danger text-center">It seems you are accessing wrong URL</h4>
           <?php
}
           ?>
        </section>

      </div>
      <?php include('../footer.php'); ?>
    </div>
  </body>

  </html>


<?php
  include('../script.php');
} else {
  header('location:'. LOGOUT);
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