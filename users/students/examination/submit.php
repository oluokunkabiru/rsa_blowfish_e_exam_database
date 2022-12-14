<?php
session_start();
include("../../../includes/connection.php");
if(isset($_SESSION['studentauth'])){
$login = $_SESSION['studentauth'];
    $auth = new Users;
    $query = $auth->query("SELECT users.*, student.*, class.name AS classname FROM users JOIN student ON student.userid = users.userid JOIN 
    class ON class.classid = student.class WHERE users.username ='$login' OR phone = '$login' OR email = '$login'");    
    $user = $auth->data($query);
    $classid =$user['class'];
     $name = $user['surname']." ". $user['firstname']." ". $user['lastname'];
    $username = $user['username'];

    if(isset($_GET['timeup'])){
      
        $studentid = $_SESSION['studentid'];
        $examid = $_SESSION['examinationgoingon'];
        $result = new Examination;
        $status = "submitted";        

  $subtjet = $result->query("SELECT subjects.subjectname AS subjects, examinations.description AS description, examinations.examname AS exname, examinations.visibility AS visibility FROM subjects 
  JOIN examinations ON subjects.subjectid = examinations.subjectid WHERE examinations.examinationid ='$examid'");
  $exname = $result->data($subtjet);
  $visibility = $exname['visibility'];
   $qu = $result->query("SELECT studentexmination.*, questions.correct AS correct,questions.mark AS mark FROM studentexmination JOIN questions ON studentexmination.questionid = questions.questionid
   WHERE studentexmination.studentid='$studentid' AND studentexmination.examinationid ='$examid' ORDER BY studentexmination.id ASC");

  $totmrk = $result->query("SELECT SUM(questions.mark) AS totalmark FROM questions WHERE examinationid ='$examid'");
  $tmark = $result->data($totmrk);
  $markear = $result->query("SELECT SUM( questions.mark) AS totalmark FROM questions JOIN studentexmination ON questions.questionid = studentexmination.questionid 
  WHERE studentexmination.examinationid='$examid' AND studentexmination.studentid='$studentid' AND studentexmination.correctness='correct'");
  
  $markearn = $result->data($markear);
  $toque = $result->query("SELECT COUNT(questions.id) AS totalquestion FROM questions WHERE examinationid ='$examid'");
  $tquestion = $result->data($toque);
  $toqans = $result->query("SELECT COUNT(studentexmination.id) AS totalans FROM studentexmination WHERE answerstatus='answered' AND examinationid ='$examid' AND studentid = '$studentid' ");
  $totalans = $result->data($toqans);
  $totalquestion = $tquestion['totalquestion'];
  $totalquestionanswered = $totalans['totalans'];
  $cbt =number_format(($markearn['totalmark']/$tmark['totalmark'])*100 );

  $ex = $result->query("SELECT * FROM studentstartexamination WHERE  examinationid='$examid' AND studentid='$studentid'");
  $preScore = $result->data($ex);

    //  $cscore = $result->test_input($_POST['score']);
    // $scores = 
    $previousScores = isset($preScore['scores']) ? json_decode($preScore['scores'], TRUE):[];
    // print_r($previousScores);
    $totalScores = [];
    $current = ['type' => 'cbt', 'score' => $cbt];
    $previousScores[] = $current;
    foreach ($previousScores as $value) {
       $totalScores[]= $value['score'];
    }
   
    $type = count($previousScores)*100;
    $scoreSum = array_sum($totalScores);
    $score = $scoreSum > 0 ? ($scoreSum/$type)*100: 0;
    // echo $score;
    $scores = json_encode($previousScores);

  $grade ="";
  $remark ="";
  if($score >= 70 && $score <=100){
      $grade ="A";
      $remark ="Excellent";
  }elseif($score >=60 && $score < 70){
    $grade ="B";
    $remark ="Very Good";
  }elseif($score >=50 && $score < 60){
    $grade ="C";
    $remark ="Good";
  }elseif($score >=40 && $score < 50){
    $grade ="D";
    $remark ="Poor";
  }elseif($score >=0 && $score < 40){
    $grade ="F";
    $remark ="Very Poor";
  }else{
    $grade ="No such grade";
    $remark ="Probably you dont take the examination";
  }

$exams = $result->query("UPDATE studentstartexamination SET status='$status', grade='$grade', remark='$remark', active_status='0', score='$score',scores='$scores', totalquestions='$totalquestion', totalquestionanswered='$totalquestionanswered' WHERE examinationid='$examid' AND studentid='$studentid'");
        if($exams){
            $_SESSION['submitted'] ="Submitted";
        }


    }

    // if($user['active_status']==1){
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OIC ACADEMICS :: EXAMINATION TIME UP</title>
    <?php include('header.php') ?>

</head>
<body>
    <?php include('navbar.php') ?>
    <div class="card">
        <div class="card-body">
    <div class="container">
        <div class="row">
        <div class="col-md-3 col-sm-3"></div>
        <div class="col-md-6 col-sm-6">
            <?php     
            if(isset($_GET['timeup'])){
             ?>
            <div class="jumbotron text-center">
            
                <p class="text-center text-danger"><span class="fa fa-clock" style="font-size: 6em;" ></span></p>
                <h3 class="text-danger font-weight-bold text-center">You examination time is up</h3>
                <button class=" btn btn-success p-3">Your examination have submitted successfully</button>
               
                <p class="my-3 text-uppercase"><a href="conductingexamination.php" class="btn btn-primary">back to questions</a> 
                <?php 
                
                if($visibility == 'yes'){
                ?>
                <a href="checkresult.php?examinationid=<?php echo $examid ?>" class="btn btn-primary">Check result</a>
                <?php } ?>
                </p>

            </div>
            <?php } 
                if(isset($_POST['submit'])){
                    $studentid = $_SESSION['studentid'];
                    $examid = $_SESSION['examinationgoingon'];
                    $result = new Examination;
                    $status = "submitted";
                    $subtjet = $result->query("SELECT subjects.subjectname AS subjects, examinations.description AS description, examinations.examname AS exname, examinations.visibility AS visibility FROM subjects 
                    JOIN examinations ON subjects.subjectid = examinations.subjectid WHERE examinations.examinationid ='$examid'");
                    $exname = $result->data($subtjet);
                    $visibility = $exname['visibility'];

              $totmrk = $result->query("SELECT SUM(questions.mark) AS totalmark FROM questions WHERE examinationid ='$examid'");
              $tmark = $result->data($totmrk);
              $markear = $result->query("SELECT SUM( questions.mark) AS totalmark FROM questions JOIN studentexmination ON questions.questionid = studentexmination.questionid 
              WHERE studentexmination.examinationid='$examid' AND studentexmination.studentid='$studentid' AND studentexmination.correctness='correct'");
              $markearn = $result->data($markear);
              $toque = $result->query("SELECT COUNT(questions.id) AS totalquestion FROM questions WHERE examinationid ='$examid'");
              $tquestion = $result->data($toque);
              $toqans = $result->query("SELECT COUNT(studentexmination.id) AS totalans FROM studentexmination WHERE answerstatus='answered' AND examinationid ='$examid' AND studentid = '$studentid' ");
              $totalans = $result->data($toqans);
              $totalquestion = $tquestion['totalquestion'];
              $totalquestionanswered = $totalans['totalans'];
              $cbt =number_format(($markearn['totalmark']/$tmark['totalmark'])*100 );

              $ex = $result->query("SELECT * FROM studentstartexamination WHERE  examinationid='$examid' AND studentid='$studentid'");
              $preScore = $result->data($ex);
            
                //  $cscore = $result->test_input($_POST['score']);
                // $scores = 
                $previousScores = isset($preScore['scores']) ? json_decode($preScore['scores'], TRUE):[];
                // print_r($previousScores);
                $totalScores = [];
                $current = ['type' => 'cbt', 'score' => $cbt];
                $previousScores[] = $current;
                foreach ($previousScores as $value) {
                   $totalScores[]= $value['score'];
                }
               
                $type = count($previousScores)*100;
                $scoreSum = array_sum($totalScores);
                $score = $scoreSum > 0 ? ($scoreSum/$type)*100: 0;
                // echo $score;
                $scores = json_encode($previousScores);
                          
              $grade ="";
              $remark ="";
              if($score >= 70 && $score <=100){
                  $grade ="A";
                  $remark ="Excellent";
              }elseif($score >=60 && $score < 70){
                $grade ="B";
                $remark ="Very Good";
              }elseif($score >=50 && $score < 60){
                $grade ="C";
                $remark ="Good";
              }elseif($score >=40 && $score < 50){
                $grade ="D";
                $remark ="Poor";
              }elseif($score >=0 && $score < 40){
                $grade ="F";
                $remark ="Very Poor";
              }else{
                $grade ="No such grade";
                $remark ="Probably you dont take the examination";
              }
            
              $exams = $result->query("UPDATE studentstartexamination SET status='$status', grade='$grade', remark='$remark', active_status='0', score='$score',scores='$scores', totalquestions='$totalquestion', totalquestionanswered='$totalquestionanswered' WHERE examinationid='$examid' AND studentid='$studentid'");
              if($exams){
                        $_SESSION['submitted'] ="Submitted";
                    }
            
            
                
            

            ?>
            <div class="jumbotron text-center">
             
                <p class="text-center text-success"><span class="fa fa-check" style="font-size: 6em;" ></span></p>
                <h3 class="text-success font-weight-bold text-center">Congratulation</h3>
                <button class=" btn btn-success p-3">Your examination have submitted successfully</button>
                <p class="my-3 text-uppercase"><a href="conductingexamination.php" class="btn btn-primary">back to questions</a> 
                      <?php 
                      
                      if($visibility == 'yes'){
                      ?>
                      <a href="checkresult.php?examinationid=<?php echo $examid ?>" class="btn btn-primary">Check result</a>
                      <?php } ?>
                   </p>
            </div>
                <?php }?>
        </div>
        <div class="col-md-3 col-sm-3"></div>
        </div>

    </div></div>
</div>
    
</body>
</html>
<?php
//  }else{
//   header('location:../logout.php');
//   }

}else{
  header('location:../');
  }
  
?>