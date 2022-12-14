<?php
session_start();
  include("../../../includes/connection.php");
  if(isset($_SESSION['studentauth']) && $_GET['examinationid']){
  $login = $_SESSION['studentauth'];
  $auth = new Users;
  $query = $auth->query("SELECT users.*, student.studentid AS studentid FROM users 
  JOIN student ON users.userid = student.userid
   WHERE username ='$login' OR phone = '$login' OR email = '$login'");
  $user = $auth->data($query);
  
  // $names = htmlentities(strtoupper($admins['username']));
  $name = $user['surname'] . " " . $user['firstname'] . " " . $user['lastname'];
  $username = $user['username'];
  $examinationid = $_GET['examinationid'];
  $studentid = $user['studentid'];
  $result = new Examination;
  $subtjet = $result->query("SELECT subjects.subjectname AS subjects,examinations.description AS description,examinations.examname AS exname, examinations.visibility AS visibility FROM subjects 
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
// if($user['active_status']==1){

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STUDENT RESULT</title>
    <?php include('header.php') ?>

</head>
<body>
<?php include('navbar.php') ?>
<div class="container">
<div class="jumbotron">
    <?php
    if($visibility == 'yes'){
    ?>
        <h3 class="text-center text-uppercase">result for <strong><?php echo $exname['subjects'] ?></strong></h3>
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
                    <p class="">Total Mark(%) <strong class="float-right mr-3"><?php echo$resultdetails['score'] ."%" ?></strong> </p>
                    <hr>
                    <p class="">Grade <strong class="float-right mr-3"><?php echo$resultdetails['grade'] ?></strong> </p>
                    <hr>
                    <p class="">Remark <strong class="float-right mr-3"><?php echo$resultdetails['remark'] ?></strong> </p>
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
                    <th>Correct Status</th>
                    <th>Mark Asign</th>
                    <th>Mark Obtain</th>
                </tr>
                
            </thead>
   <tbody>

                    
                    <?php 
                    $no =1;
                    $totalmark =[];
                    
                    $qu = $result->query("SELECT studentexmination.*, questions.question AS question, questions.correct AS correct,questions.mark AS mark FROM studentexmination JOIN questions ON studentexmination.questionid = questions.questionid
                                         WHERE studentexmination.studentid='$studentid' AND studentexmination.examinationid ='$examinationid' ORDER BY studentexmination.id ASC");

                    while($results = $result->data($qu)){

                    
                    ?>
                        <tr>
                        <td><?php echo $no ?></td>
                        <td><?php echo html_entity_decode($results['question'])?></td>
                        
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
                    </td>                        </tr>
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
<?php }else{

 ?>
<h1 class="text-danger font-weight-bold text-center text-uppercase">You are not allow to view this result</h1>
<h3 class="text-danger font-weight-bold text-center text-uppercase">Contact your teacher or school authority</h3>

<?php
}
?>
</div>
</div>
    
</body>
</html>
<?php
    include('script.php');
// }else{
//     header('location:../logout.php');
//     }
} else {
    header('location:../');
}
?>