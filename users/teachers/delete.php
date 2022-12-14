<?php 
include('../../includes/connection.php');
if(isset($_POST['deleteclassconfirm'])){
    $id = $_POST['deleteclassconfirm'];
$delete = new Classes;
$d = $delete->query("DELETE FROM class WHERE classid = '$id' ");
if($d){
    $delete->redirect("manageclass.php");
}else{
    echo "fail to delete";
}
}

if(isset($_POST['deleteresultconfirm'])){
    $id = $_POST['deleteresultconfirm'];
    $key = $_POST['key'];
$class = new Classes;
$q = $class->query("SELECT* FROM studentstartexamination WHERE id ='$id' ");
$qresult = $class->data($q);

$previousScores = isset($qresult['scores']) ? json_decode($qresult['scores'], TRUE):[];
// print_r($previousScores);
if(array_key_exists($key, $previousScores)){
    unset($previousScores[$key]);
}

$totalScores = [];

foreach ($previousScores as $value) {
   $totalScores[]= $value['score'];
}

$type = count($previousScores)*100;
$scoreSum = array_sum($totalScores);
$score = $scoreSum > 0 ? ($scoreSum/$type)*100:0;
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

$q  = $class->query("UPDATE studentstartexamination SET score='$score', scores='$scores', grade='$grade', remark='$remark' WHERE id ='$id'");  
if($q){
    header('location:'. $_SERVER['HTTP_REFERER']);
} else{
    echo "Fail to update";
}
}


if(isset($_POST['deletestudentconfirm'])){
    $id = $_POST['deletestudentconfirm'];
$delete = new Classes;
$d = $delete->query("DELETE FROM student WHERE userid = '$id' ");
$d =$delete->query("DELETE FROM users WHERE userid = '$id' ");
if($d){
    $delete->redirect("managestudent.php");
}else{
    echo "fail to delete";
}
}

// delete staff

if(isset($_POST['deletestaffconfirm'])){
    $id = $_POST['deletestaffconfirm'];
$delete = new Classes;
$d = $delete->query("DELETE FROM teachers WHERE userid = '$id' ");
$d =$delete->query("DELETE FROM users WHERE userid = '$id' ");
if($d){
    $delete->redirect("staffs/index.php");
}else{
    echo "fail to delete";
}
}

if(isset($_POST['deletesubjectconfirm'])){
    $id = $_POST['deletesubjectconfirm'];
$delete = new Classes;
$d = $delete->query("DELETE FROM subjects WHERE subjectid = '$id' ");
if($d){
    $delete->redirect("managesubject.php");
}else{
    echo "fail to delete";
}
}


if(isset($_POST['deleteexaminationconfirm'])){
    $id = $_POST['deleteexaminationconfirm'];
$delete = new Classes;
$d = $delete->query("DELETE FROM examinations WHERE examinationid = '$id' ");
if($d){
    $delete->redirect("examination/manageexamination.php");
}else{
    echo "fail to delete";
}
}

if(isset($_POST['deletequestionsconfirm'])){
    $id = $_POST['deletequestionsconfirm'];
    $examid = $_POST['questionsexaminationid'];
    
$delete = new Classes;
$q = $delete->query("SELECT* FROM questions WHERE questionid = '$id'");
$data = $delete->data($q);
$dom = new \DOMDocument();
$question = $data['question'];
$dom->loadHTML(html_entity_decode($question), libxml_use_internal_errors(true));
$images = $dom->getElementsByTagName('img');
echo "Images = ". print_r($images);
foreach ($images as $image) {
$src = $image->getAttribute('src');
$img = str_replace("../", "", $src);
unlink("../".$img);
}
$question = $data['option_a'];
$dom->loadHTML(html_entity_decode($question), libxml_use_internal_errors(true));
$images = $dom->getElementsByTagName('img');
foreach ($images as $image) {
    $src = $image->getAttribute('src');
    $img = str_replace("../", "", $src);
    unlink("../".$img);
}
$question = $data['option_b'];
$dom->loadHTML(html_entity_decode($question), libxml_use_internal_errors(true));
$images = $dom->getElementsByTagName('img');
foreach ($images as $image) {
    $src = $image->getAttribute('src');
    $img = str_replace("../", "", $src);
    unlink("../".$img);
}
$question = $data['option_c'];
$dom->loadHTML(html_entity_decode($question), libxml_use_internal_errors(true));
$images = $dom->getElementsByTagName('img');
foreach ($images as $image) {
    $src = $image->getAttribute('src');
    $img = str_replace("../", "", $src);
    unlink("../".$img);
}
$question = $data['option_d'];
$dom->loadHTML(html_entity_decode($question), libxml_use_internal_errors(true));
$images = $dom->getElementsByTagName('img');
foreach ($images as $image) {
    $src = $image->getAttribute('src');
    $img = str_replace("../", "", $src);
    unlink("../".$img);
}
$question = $data['option_e'];
$dom->loadHTML(html_entity_decode($question), libxml_use_internal_errors(true));
$images = $dom->getElementsByTagName('img');
foreach ($images as $image) {
    $src = $image->getAttribute('src');
    $img = str_replace("../", "", $src);
    unlink("../".$img);
}

$d = $delete->query("DELETE FROM questions WHERE questionid = '$id' ");
if($d){
    $delete->redirect("examination/examinationquestions.php?examinationid=$examid&&view=question");
}else{
    echo "fail to delete";
}
}


// activate examination
if(isset($_POST['activateexaminationconfirm'])){
    $id = $_POST['activateexaminationconfirm'];
$delete = new Examination;
$status = "available";
$d = $delete->query("UPDATE examinations SET status='$status' WHERE examinationid = '$id' ");
if($d){
    $delete->redirect("examination/manageexamination.php");
}else{
    echo "fail to activate";
}
}

// deactivate examination
if(isset($_POST['deactivateexaminationconfirm'])){
    $id = $_POST['deactivateexaminationconfirm'];
$delete = new Examination;
$status = "unavailable";
$d = $delete->query("UPDATE examinations SET status='$status' WHERE examinationid = '$id' ");
if($d){
    $delete->redirect("examination/manageexamination.php");
}else{
    echo "fail to activate";
}
}



// deactivate examination
if(isset($_POST['stopexaminationconfirm'])){
    $id = $_POST['stopexaminationconfirm'];
$delete = new Examination;
$status = "unavailable";
$delete->query("UPDATE examinations SET status='$status' WHERE examinationid = '$id' ");

$delete->query("DELETE FROM studentstartexamination WHERE examinationid = '$id' ");
$de = $delete->query("DELETE FROM studentexmination WHERE examinationid = '$id' ");
if($de){
    $delete->redirect("examination/examinationquestions.php");
}else{
    echo "fail to delete ". $delete->connectionError();
}

}

?>