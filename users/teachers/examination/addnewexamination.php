<?php
 include('../../../includes/connection.php');
 $exam = new Examination;
 $table = "examinations";
 $column = "";
 $error = [];
 $currentdateandtime = date("Y/m/d");
//  echo "<br> Date = $currentdateandtime and this ". strtotime($currentdateandtime)."<br>";
//  echo "curr = ".strtotime($currentdateandtime)." and Stattime = ". strtotime($_POST['startdate'])."datebfor = ".date_format(date_create($_POST['startdate']), "Y-m-d");
 if(empty($_POST['examclass'])){
    $error['examclass']= "Examination class must select";
 }
 if(empty($_POST['classsubject'])){
    $error['classsubject']= "Examination subject must be selected";
 }
 
 if(empty($_POST['examinationdescription'])){
    $error['examinationdescription']= "Please describe the examination";
 }
 if(empty($_POST['examname'])){
    $error['examname']= "Please enter examination name";
 }
 if(empty($_POST['duration'])){
    $error['duration']= "Please enter the examination duration in minutes ";
 }
//  if(empty($_POST['tscore'])){
//      array_push($error, "Please enter the examination total scores e.g 40% of total exam");
//  }
 if(empty($_POST['startdate'])){
    $error['startdate']= "Please enter the start date for the examination";
}

if(empty($_POST['display'])){
    $error['display']= "Please enter the select display mechanism";
}
 if(empty($_POST['enddate'])){
    $error['enddate']= "Please enter the end date for the examination";
}
if(empty($_POST['exampasscode'])){
    $error['exampasscode']= "Please enter the examination PIN";
}

if(empty($_POST['visibility'])){
    $error['visibility']= "Please select examination visibility";
}
if(strtotime($_POST['startdate']) < strtotime($currentdateandtime)){
    $error['startdate']=  "Examination start must greater than current time ";
}

if(strtotime($_POST['enddate']) < strtotime($_POST['startdate'])){
    $error['enddate']= "Examination end date must greater than start date";
}
if (count($error) > 0) {
    echo json_encode([
        'status' => 'error',
        'data' => json_encode($error),
        'message'=>"OOPS !!!, Kindly rectify the following errors"
        ]);
}
// foreach($error as $errors){
//     echo $errors . "<br>";
// }
 if(count($error)==0){
     $classname = $exam->test_input($_POST['examclass']);
     $subject = $exam->test_input($_POST['classsubject']);
     $examinationdescription = $exam->test_input($_POST['examinationdescription']);
     $examname = $exam->test_input($_POST['examname']);
     $duration = $exam->test_input($_POST['duration']);
     $display = $exam->test_input($_POST['display']);
    //  $tscore = $exam->test_input($_POST['tscore']);
     $startdate = $exam->test_input(date_format(date_create($_POST['startdate']), "Y-m-d").date(" H:s:i"));
     $enddate= $exam->test_input(date_format(date_create($_POST['enddate']), "Y-m-d")." 23:59:59");
     $exampasscode= $exam->test_input($_POST['exampasscode']);
     $visibility= $exam->test_input($_POST['visibility']);
     $id = $exam->tableid($table);
     $currentTerm = $exam->currentTerm();
     $status ="unavailable";
     $q = $exam->query("INSERT INTO examinations(display, current_term, status,subjectid, classid, description, examname, duration, startdate, enddate, examinationpin, visibility, examinationid)
     VALUES('$display', '$currentTerm','$status','$subject', '$classname', '$examinationdescription', '$examname', '$duration', '$startdate', '$enddate',
     '$exampasscode', '$visibility', '$id' )");
     
     if($q){
        //  echo "<span class='text-success'>Examination added successfully</span>";
            echo json_encode([
                'status' => 'success',
                'message'=>"Examination created successfully",
                'url' => '',
                ]);

     }else{
        //  echo "Examination fail to create". $exam->connectionError();
            echo json_encode([
                'status' => 'error',
                'data' => $exam->connectionError(),
                'message'=>"OOPS !!!, Kindly rectify the following errors"
                ]);
     }
 }
 ?>