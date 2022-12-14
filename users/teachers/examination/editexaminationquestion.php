<?php
include('../../../includes/connection.php');
$question = new Questions;
$table = "questions";
$column = "";
$error = [];
if(strlen($_POST['question']) < 5){
    array_push($error, "Question must not be empty<br>");
}
if(strlen($_POST['optiona'])<5){

    array_push($error, "Option A must not be empty<br>");
}
if(strlen($_POST['optionb'])<5){
    array_push($error, "Option B must not be empty<br>");
}
if(strlen($_POST['optionc'])<5){
    array_push($error, "Option C must not be empty<br>");
}
if(empty($_POST['optionc'])  && $_POST['correct']=="C"){
    array_push($error, "Option C must not be empty because you choose it as correct answer<br>");
}
if(strlen($_POST['optiond'])<5 && $_POST['correct']=="D"){
    array_push($error, "Option D must not be empty because you choose it as correct answer<br>");
}

if(strlen($_POST['optione'])<5 && $_POST['correct']=="E"){
    array_push($error, "Option E must not be empty because you choose it as correct answer<br>");
}

if(empty($_POST['mark'])){
    array_push($error, "Please specify the question mark<br>");
}

if(empty($_POST['correct'])){
    array_push($error, "Please specify the the correct option<br>");
}

$success =[];


if(count($error) > 0){
$errors['error'] = $error;
echo json_encode($errors);

}

if(count($error)==0){
 

    $questions  = $question->setQuestion($_POST['question']);
    $optiona = $question->setQuestion($_POST['optiona']);
    $optionb = $question->setQuestion($_POST['optionb']);
    $optionc =$_POST['optionc']?$question->setQuestion($_POST['optionc']):"";
    $optiond = $_POST['optiond']?$question->setQuestion($_POST['optiond']):"";
    $optione = $_POST['optione']?$question->setQuestion($_POST['optione']):"";
    $qtype = $question->test_input($_POST['qtype']);
    $correctanswer = $question->test_input($_POST['correct']);
    $mark = $question->test_input($_POST['mark']);
    $examid = $_POST['examid'];
    $id = $_POST['editquestionsid'];
  
    // echo "<br>$questions<br>$optiona<br>$optionb<br>$optionc<br>$optiond<br>$correctanswer";
    $q = $question->query("UPDATE questions SET examinationid ='$examid', questiontype='$qtype', question='$questions', 
    option_a='$optiona', option_b='$optionb', option_c='$optionc', option_d='$optiond', option_e='$optione', correct='$correctanswer',  
    mark='$mark' WHERE questionid = '$id' ");
    if($q){
             array_push($success, "examinationquestions.php?examinationid=$examid&&view=question");
    
        $ok['vb'] = $success;
        echo json_encode($ok);
       
        }else{
        echo "fail to add question";
    }


}
?>