<?php
session_start();
require_once('../../../includes/constant.php');
require_once("../../../includes/connection.php");
if ($_SESSION['teacherauth']) {
    // include("../../../includes/connection.php");
    // if(isset($_GET['examinationid'])){
       
          

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
               if(isset($_GET['student']) && $_GET['examinationid']){
                $userid = $_GET['student'];
                $auth = new Users;
                $query = $auth->query("SELECT users.*, student.studentid AS studentid FROM users 
                JOIN student ON users.userid = student.userid
                 WHERE users.userid ='$userid' ");
                $user = $auth->data($query);
                
                // $names = htmlentities(strtoupper($admins['username']));
                $name = $user['surname'] . " " . $user['firstname'] . " " . $user['lastname'];
                $username = $user['username'];
                $examinationid = $_GET['examinationid'];
                $studentid = $user['studentid'];
                $result = new Examination;
                $subtjet = $result->query("SELECT subjects.subjectname AS subjects, examinations.description AS description,  examinations.examname AS exname, examinations.visibility AS visibility FROM subjects 
                JOIN examinations ON subjects.subjectid = examinations.subjectid WHERE examinations.examinationid ='$examinationid'");
                $exname = $result->data($subtjet);
                $visibility = $exname['visibility'];
              
              $re = $result->query("SELECT* FROM studentstartexamination WHERE examinationid='$examinationid' AND studentid='$studentid'");
              $resultdetails = $result->data($re);
              $totmrk = $result->query("SELECT SUM(questions.mark) AS totalmark FROM questions WHERE examinationid ='$examinationid'");
              $tmark = $result->data($totmrk);
              $markear = $result->query("SELECT SUM( questions.mark) AS totalmark FROM questions JOIN studentexmination ON questions.questionid = studentexmination.questionid 
              WHERE studentexmination.examinationid='$examinationid' AND studentexmination.studentid='$studentid' AND studentexmination.correctness='correct'");
              $markearn = $result->data($markear);
              ?>
          <h3 class="text-center text-uppercase py-3"><strong><?php echo $name ?></strong> result for <?php echo $exname['subjects'] ?></h3>
          <button class="btn btn-success btn-lg noprint" onclick="window.print()"> <span class="fa fa-print"></span> Print</button>
          <a href="#resetquestion" class="float-right btn btn-danger text-uppdercase" data-toggle="modal">Reset examinations</a>

        <div class="row">
        <div class="col-md-3 "></div>
        <?php if($resultdetails['totalquestions'] !=""){ ?>
        <div class="col-md-6">
            <div class="card card-outline card-success">
                <div class="card-body">
                <p>Total Question <strong class="float-right mr-3"><?php echo $resultdetails['totalquestions'] ?></strong> </p>
                    <hr>
                    <p>Total Answered <strong class="float-right mr-3"><?php echo $resultdetails['totalquestionanswered'] ?></strong> </p>
                    <hr>
                    <p class="text-danger">Total Unanswered <strong class="float-right mr-3"><?php echo $resultdetails['totalquestions']-$resultdetails['totalquestionanswered']  ?></strong> </p>
                    <hr>
                    <p class="">Total Mark <strong class="float-right mr-3"><?php echo $markearn['totalmark'] ."/". $tmark['totalmark'] ?></strong> </p>
                    <hr>
                    <p class="">Total Mark(%) <strong class="float-right mr-3"><?php  echo $resultdetails['score'] ."%" ?></strong> </p>
                          
                    <hr>
                    <p class="">Grade <strong class="float-right mr-3"><?php echo $resultdetails['grade'] ?></strong> </p>
                    <hr>
                    <p class="">Remark <strong class="float-right mr-3"><?php echo $resultdetails['remark'] ?></strong> </p>
                    <hr>
                    <!-- <p class="">Total Mark assign <strong class="float-right mr-3"><?php //echo $exname['tscore'] ?></strong> </p> -->
                    <hr>
                    <!-- <p class="">Total Mark Assign score <strong class="float-right mr-3"><?php // echo number_format(($resultdetails['score'] /100)*$exname['tscore'], 2) ."/". $exname['tscore'] ?></strong> </p> -->
                    <hr>

                    <h3><a href="#resultdetails" data-toggle="collapse">See more details</a></h3>
                </div>
            </div>
        </div>
        <?php }else{ ?>
          <h2 class="text-uppercase text-center text-danger">this examination is not cbt</h2>
          <?php } ?>
        <div class="col-md-3"></div>

        </div>
        <div class="collapse" id="resultdetails">
  <div class="card">
        <div class="card-header">
        <table class="table table-hover table-bolder">
            <thead>
                <tr>
                    <th>Question Number</th>
                    <th>Questions</th>
                    <th>Answer Status</th>
                    <th>Choosed Options</th>
                    <th>Correct Options</th>
                    <th>Correct Options</th>
                    
                    <th>Mark Asign</th>
                    <th>Mark Obtain</th>
                </tr>
                
            </thead>
   <tbody>

                    
                    <?php 
                    $no =1;
                    $totalmark =[];
                    
                    $qu = $result->query("SELECT studentexmination.*, questions.question AS question, questions.correct AS correct, questions.mark AS mark FROM studentexmination JOIN questions ON studentexmination.questionid = questions.questionid
                                         WHERE studentexmination.studentid='$studentid' AND studentexmination.examinationid ='$examinationid' ORDER BY studentexmination.id ASC");

                    while($results = $result->data($qu)){

                    
                    ?>
                        <tr>
                        <td><?php echo $no ?></td>
                        <td><?php echo html_entity_decode($results['question']) ?></td>
                        <td><?php if($results['answerstatus']=="answered"){
                         ?>
                         <span class="text-success"><?php echo ucwords($results['answerstatus'] )?></span>

                    <?php 
                    } else{

                     ?>  
                            <span class="text-danger"><?php echo ucwords($results['answerstatus'] )?></span>
                    <?php } ?>  
                    </td>
                    <td><?php echo $results['selectedoption']?$results['selectedoption']:"<span class='text-danger'> Not Option Selected </span>" ?></td>
                    <td><?php echo $results['correct']?$results['correct']:"<span class='text-danger'> Not Option Specify </span>" ?></td>
                    <td><?php if($results['correctness']=="correct"){
                         ?>
                         <span class="text-success fa fa-check"></span>

                    <?php 
                    } else{

                     ?>  
                         <span class="text-danger fas fa-times"></span>
                    <?php } ?>  
                    </td>
                    <td><?php echo $results['mark'] ?></td>
                    <td><?php if($results['correctness']=="correct"){
                         ?>
                         <span class="text-success"><?php echo $results['mark'];
                         ?></span>

                    <?php 
                    } else{

                     ?>  
                         <span class="text-danger">0</span>
                    <?php } ?>  
                    </td>                        
                  </tr>
                    <?php 
                    $no++;
                } 
                ?>
</tbody>
<tfoot>
    <tr>
        <th>TOTAL</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th><?php echo $tmark['totalmark'] ?></th>
        <th><?php echo $markearn['totalmark'] ?></th>
    </tr>
</tfoot>
        </table>
        </div>
    </div>
</div>
<div class="modal" id="resetquestion">
              <div class="modal-dialog">
                <div class="modal-content">
                   
                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h3 class="modal-title text-uppercase font-weight-bold">reset examination questions for <?= $name ?></h3>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <p class="text-danger ml-3">All question and result for this <?= $name ?>  will be deleted </p>
                    <p class="text-danger"> Click below button to continue the questions reseting</p>
                   
                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                  <a href="../examination/reset_examination.php?examinationid=<?= $examinationid ?>&student=<?= $studentid ?>" class="btn btn-danger btn-block text-uppercase my-3"> Proceed to reset</a>

                  </div>

                </div>
              </div>
            </div>

                    </div>
               
         <?php
            
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
  header('location:../../../');
}
?>

<script>

</script>