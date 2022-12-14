<?php
// echo "<span class='text-success'>Result added successfully</span>"
include('../../../includes/connection.php');
$result =  new Examination;
// $table = "class";
// $column = "name";
$error = [];
if(isset($_POST['score'])){
    if(empty($_POST['subject'])){
        array_push($error, "Please choose subject for this examination");
    }
    if(empty($_POST['score'])){
        array_push($error, "Please choose student score for this examination");
    }
    if(empty($_POST['type'])){
      array_push($error, "Please choose type of result");
  }
    $studentid = $_POST['studentid'];
    if(!empty($_POST['subject'])){
    $subjectid = $_POST['subject'];
    $classid = $_POST['classid'];
    // $classid ="c81e728d9d4c2f636f067f89cc14862c";
    // $subjectid="e4da3b7fbbce2345d7772b0674a318d5";
    $checkexamination = $result->query("SELECT* FROM examinations WHERE subjectid='$subjectid' AND classid='$classid' ");
    $checkexam = $result->data($checkexamination);
    $examinationid = isset($checkexam['examinationid'])?$checkexam['examinationid']:"";
    if(empty($examinationid)){
        $table = 'examinations';
        $id = $result->tableid($table);
        // $id = "vb";
        $time = date('Y-m-d H:i:s');
        $status = "written";
        $visibility ="no";
        $duration =0;
       $c = $result->query("INSERT INTO examinations(subjectid, duration, visibility, status, classid, examinationid, startdate, enddate) 
       VALUES('$subjectid','$duration', '$visibility', '$status', '$classid', '$id', '$time', '$time')  ");
        if($c){
        $checkexamination = $result->query("SELECT* FROM examinations WHERE subjectid='$subjectid' AND classid='$classid' ");
        $checkexam = $result->data($checkexamination);
        $examinationid = $checkexam['examinationid']?$checkexam['examinationid']:"";
        }else{
          echo "Fail to add result". $result->connectionError();
        }
    }
    $q = $result->query("SELECT* FROM studentstartexamination WHERE examinationid ='$examinationid' AND classid='$classid' AND studentid='$studentid'");
    $qresult = $result->data($q);
    $examexit =  isset($qresult['examinationid'])?$qresult['examinationid']:"";

    $etype = isset($qresult['examinationtype'])?$qresult['examinationtype']:"";
   
    // echo array_sum($totalScores);
    // if(!empty($examexit)){
    //     array_push($error, "This examination already done through <b>$etype</b>" );
    // }else{
    // }
    }
   
    
    foreach($error as $errors){
        echo $errors."<br>";
}
if(count($error)==0){
  $cscore = $result->test_input($_POST['score']);
  $type = $result->test_input($_POST['type']);
  // $scores = 
    $previousScores = isset($qresult['scores']) ? json_decode($qresult['scores'], TRUE):[];
    // print_r($previousScores);
    $totalScores = [];
    $current = ['type' => $type, 'score' => $cscore];
    $previousScores[] = $current;
    foreach ($previousScores as $value) {
       $totalScores[]= $value['score'];
    }
   
    $type = count($previousScores)*100;
    $scoreSum = array_sum($totalScores);
    $score = ($scoreSum/$type)*100;
    // echo $score;
    $scores = json_encode($previousScores);
    $remark ="";
    $grade ="";

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
    $examtype ="theory";
// echo "Score = $score <br> Grade = $grade <br> Remark = $remark";  
$time = date('Y-m-d H:i:s');
$status = "submitted";
    if(!empty($examexit)){
     $q= $result->query("UPDATE studentstartexamination SET scores='$scores', classid='$classid', examinationid='$examinationid', grade='$grade', score='$score', remark='$remark' WHERE studentid='$studentid' AND examinationid='$examinationid'");
     if($q){
      echo "<span class='text-success'>Result added successfully</span>";
      }else{
      echo "Fail to add result ". $result->connectionError();
      }
    }else{
      $q= $result->query("INSERT INTO studentstartexamination(studentid, scores, examinationtype, classid, examinationid,starttime, endtime,  status, active_status, grade, score, remark)
      VALUES('$studentid', '$scores', '$examtype', '$classid', '$examinationid', '$time', '$time', '$status', '0', '$grade', '$score', '$remark')");
      if($q){
      echo "<span class='text-success'>Result added successfully</span>";
      }else{
      echo "Fail to add result ". $result->connectionError();
      }
    }
 
 }

}
