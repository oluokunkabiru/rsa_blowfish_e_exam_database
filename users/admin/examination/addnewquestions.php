<?php
include('../../../includes/connection.php');
$question = new Questions;
$table = "questions";
$column = "";
$error = [];

if(empty($_POST['question'])){
    array_push($error, "Question must not be empty<br>");
}
if(empty($_POST['optiona'])){
    array_push($error, "Option A must not be empty<br>");
}
if(empty($_POST['optionb'])){
    array_push($error, "Option B must not be empty<br>");
}
if(empty($_POST['mark'])){
    array_push($error, "Please specify the question mark<br>");
}
if(strlen($_POST['optionc'])<5 && $_POST['correct']=="C"){
    array_push($error, "Option C must not be empty because you choose it as correct answer<br>");
}
if(strlen($_POST['optiond'])<5 && $_POST['correct']=="D"){
    array_push($error, "Option D must not be empty because you choose it as correct answer<br>");
}

if(strlen($_POST['optione'])<5 && $_POST['correct']=="E"){
    array_push($error, "Option E must not be empty because you choose it as correct answer<br>");
}

if(empty($_POST['correct'])){
    array_push($error, "Please specify the the correct option<br>");
}

$success =[];
if(count($error) > 0){
    echo json_encode([
        'status' => 'error',
        'data' => json_encode($error),
        'message'=>"OOPS !!!, Kindly rectify the following errors"
        ]);

}

// print_r($question->setQuestion($_POST['question']));
if(count($error)==0){
    $key = $_POST['exampin'];
    $questions  = $question->RSAEncrypt($question->setQuestion($_POST['question']), $key);
    $optiona = $question->RSAEncrypt($question->setQuestion($_POST['optiona']), $key);
    $optionb = $question->RSAEncrypt($question->setQuestion($_POST['optionb']), $key);
    $optionc =$_POST['optionc']?$question->RSAEncrypt($question->setQuestion($_POST['optionc']), $key):"";
    $optiond = $_POST['optiond']?$question->RSAEncrypt($question->setQuestion($_POST['optiond']), $key):"";
    $optione = $_POST['optione']?$question->RSAEncrypt($question->setQuestion($_POST['optione']), $key):"";
    $qtype = "normal";
    $correctanswer = $question->RSAEncrypt($question->test_input($_POST['correct']), $key);
    $mark = $question->RSAEncrypt($question->test_input($_POST['mark']), $key);

    $examid = $_POST['examid'];

    $id = $question->tableid($table);
    $q = $question->query("INSERT INTO questions(examinationid, questiontype, question, option_a, option_b, option_c, option_d, option_e, correct,  mark, questionid)
    VALUES('$examid', '$qtype', '$questions', '$optiona','$optionb','$optionc','$optiond','$optione','$correctanswer', '$mark','$id' )");
   
   if($q){
       array_push($success, "examinationquestions.php?examinationid=$examid&&view=question");
    
        $ok['ok'] = $success;
        echo json_encode($ok);

    }else{
        echo "fail to add question ". $question->connectionError();
    }


}
?>