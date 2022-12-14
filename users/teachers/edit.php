<?php
include('../../includes/connection.php');
// edit class
if(isset($_POST['editclassconfirm'])){
    $error = [];
    $class = new Classes;
    $id = $_POST['editclassconfirm'];
    if(empty($_POST['name'])){
        array_push($error, "Class name must not be empty");
    }
    if($class->checkExist("class", "name", $_POST['name'])){
        array_push($error, "This class already exist");
    }

    foreach($error as $errors){
        echo $errors. "<br>";
    }
    if(count($error)==0){
    $newclass = $class->test_input($_POST['name']);
    $q  = $class->query("UPDATE class SET name='$newclass' WHERE classid ='$id'");  
    if($q){
        echo "<span class='text-success'>Class updated successfully</span>";
    } else{
        echo "Fail to update";
    }
}
}

// edit result
if(isset($_POST['editresultconfirm'])){
    $error = [];
    $class = new Classes;
    $id = $_POST['editresultconfirm'];
    $key = $_POST['key'];
    if(empty($_POST['score'])){
        array_push($error, "Please select score for this examination");
    }
    
    foreach($error as $errors){
        echo $errors. "<br>";
    }
    if(count($error)==0){
    $cscore = $class->test_input($_POST['score']);
    $q = $class->query("SELECT* FROM studentstartexamination WHERE id ='$id' ");
    $qresult = $class->data($q);
    
    $previousScores = isset($qresult['scores']) ? json_decode($qresult['scores'], TRUE):[];
    // print_r($previousScores);
    $totalScores = [];
    $current = ['type' => 'theory', 'score' => $cscore];
    $previousScores[$key] = $current;
    foreach ($previousScores as $value) {
       $totalScores[]= $value['score'];
    }
   
    $type = count($previousScores)*100;
    $scoreSum = array_sum($totalScores);
    $score = ($scoreSum/$type)*100;
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
        echo "<span class='text-success'>Result updated successfully</span>";
    } else{
        echo "Fail to update";
    }
}
}

// edit student
if(isset($_POST['editstudentbtnconfirm'])){
    $id = $_POST['editstudentbtnconfirm'];
    $user =  new Users;
    $predata = $user->query("SELECT * FROM users  WHERE userid = '$id'");
    $userdata = $user->data($predata);
    $pavatar = isset($userdata['avatar'])?$userdata['avatar']:"";
    $prepassword = $userdata['password'];
    $table = "users";
    $column = "username";
    $error = [];
    // echo $table. substr(strlen($column), 0, 1).substr(time(), -1);
        if(empty($_POST['sname'])){
            array_push($error, "Student surname must not be empty");
        }
        if(empty($_POST['fname'])){
            array_push($error, "Student firstname must not be empty");
        }
        if(empty($_POST['lname'])){
            array_push($error, "Student Lastname must not be empty");
        }
        if(empty($_POST['class'])){
            array_push($error, "Student class must not be empty");
        }
        if(empty($_POST['password'])){
            $password = $prepassword;
        }else{
            $password = md5($_POST['password']);
        }
       if(empty($pavatar) && $_FILES['avatar']['size'] == 0){
           $error[] = "Please choose passport for the student for identification";
       }

       if($_FILES['avatar']['size']!=0){
    
        $target_dir = "../students_avatar/";
        if(!is_dir($target_dir)){
            mkdir($target_dir);
        }
        $check = getimagesize($_FILES["avatar"]["tmp_name"]);
          $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $image =$target_dir.str_replace(" ", "_", strtolower($_POST['sname']. $_POST['fname'].$_POST['lname'])."_".rand(10,1000))."_".time().".".$imageFileType;

        if($check != true) {
            $error[] = "Image file is expected";
        }elseif ($_FILES["avatar"]["size"] > 5000000) {
            $error[] = "Sorry, your file is too large, upload file with less than 5MB";
            
        }elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        $error[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }elseif(count($error)==0){
            move_uploaded_file($_FILES["avatar"]["tmp_name"], $image);
            if(!empty($pavatar)){
                unlink($pavatar);
            }
            $avatar = $image;
        }else{
          $error[] ="Please fill the require section";
        }
      }else{
          $avatar = $pavatar;
      }    
   
        foreach($error as $errors){
            echo $errors."<br>";
    }

    if(count($error)==0){
        $sname = $user->test_input($_POST['sname']);
        $fname = $user->test_input($_POST['fname']);
        $lname = $user->test_input($_POST['lname']);
        $class = $user->test_input($_POST['class']);
        $q = $user->query("UPDATE users SET surname ='$sname', password='$password', avatar='$avatar', firstname= '$fname', lastname = '$lname' WHERE userid = '$id' "); 
        $student = new Users;
        $q = $student->query("UPDATE student SET class = '$class' WHERE userid = '$id'") ; 
        if($q){
            echo "<span class='text-success'>Student updated successfully</span>";
        }else{
            echo "Class fail to added =". $user->connectionError();
        }
    }


    
}



// update staffs
// edit student
if(isset($_POST['editstaffbtnconfirm'])){
    $id = $_POST['editstaffbtnconfirm'];
    $user =  new Users;
    $predata = $user->query("SELECT * FROM users  WHERE userid = '$id'");
    $userdata = $user->data($predata);
    $pavatar = isset($userdata['avatar'])?$userdata['avatar']:"";
    $prepassword = $userdata['password'];
    $table = "users";
    $column = "username";
    $error = [];
    // echo $table. substr(strlen($column), 0, 1).substr(time(), -1);
        if(empty($_POST['sname'])){
            array_push($error, "Staff surname must not be empty");
        }
        if(empty($_POST['fname'])){
            array_push($error, "Staff firstname must not be empty");
        }
        if(empty($_POST['lname'])){
            array_push($error, "Staff Lastname must not be empty");
        }
        if(empty($_POST['class'])){
            array_push($error, "Staff class must not be empty");
        }
       
        if(empty($_POST['password'])){
            $password = $prepassword;
        }else{
            $password = md5($_POST['password']);
        }
       if(empty($pavatar) && $_FILES['avatar']['size'] == 0){
           $error[] = "Please choose passport for the staff for identification";
       }

       if($_FILES['avatar']['size']!=0){
    
        $target_dir = "../staffs_avatar/";
        if(!is_dir($target_dir)){
            mkdir($target_dir);
        }
        $check = getimagesize($_FILES["avatar"]["tmp_name"]);
          $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $image =$target_dir.str_replace(" ", "_", strtolower($_POST['sname']. $_POST['fname'].$_POST['lname'])."_".rand(10,1000))."_".time().".".$imageFileType;

        if($check != true) {
            $error[] = "Image file is expected";
        }elseif ($_FILES["avatar"]["size"] > 5000000) {
            $error[] = "Sorry, your file is too large, upload file with less than 5MB";
            
        }elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        $error[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }elseif(count($error)==0){
            move_uploaded_file($_FILES["avatar"]["tmp_name"], $image);
            if(!empty($pavatar)){
                unlink("../$pavatar");
            }
            $avatar = str_replace("../", "", $image);
        }else{
          $error[] ="Please fill the require section";
        }
      }else{
          $avatar = $pavatar;
      }    
   
        foreach($error as $errors){
            echo $errors."<br>";
    }

    if(count($error)==0){
        
        $sname = $user->test_input($_POST['sname']);
        $fname = $user->test_input($_POST['fname']);
        $lname = $user->test_input($_POST['lname']);
        $class = $user->test_input($_POST['class']);
        $q = $user->query("UPDATE users SET surname ='$sname', password='$password', avatar='$avatar', firstname= '$fname', lastname = '$lname' WHERE userid = '$id' "); 
        // $student = new Users;
        $q = $user->query("UPDATE teachers SET classid = '$class' WHERE userid = '$id'") ; 
        if($q){
            echo "<span class='text-success'>Staff updated successfully</span>";
        }else{
            echo "Class fail to added =". $user->connectionError();
        }
    }


    
}



if(isset($_POST['editsubjectbtnconfirmation'])){
    $id =$_POST['editsubjectbtnconfirmation']; 
    $subject = new Subject;
    $table = "subjects";
    $sub = "subjectname";
    $clas = "class";
    $errors = [];
    if(empty($_POST['name'])){
        array_push($errors, "Subject name must not be empty");
    }

    if(empty($_POST['class'])){
        array_push($errors, "Class must be select");
    }
    if(($subject->checkSubjectExist($_POST['name'], $_POST['class']))){
        array_push($errors, "This class already exists");
    }
    foreach($errors as $error){
        echo $error . "<br>";
    }
    if(count($errors)==0){
        $name = $subject->test_input($_POST['name']);
        $class = $subject->test_input($_POST['class']);
        $q = $subject->query("UPDATE subjects SET subjectname = '$name', classid = '$class' WHERE subjectid='$id' ");
        if($q){
            echo "<span class='text-success'>Subject updated successfully</span>";
        }else{
            echo "Fail to insert". $subject->connectionError();
        }
    }
}


// edit examination
if(isset($_POST['editexaminationbtnconfirmation'])){
    $id = $_POST['editexaminationbtnconfirmation'];
    $exam = new Examination;
 $table = "examinations";
 $column = "";
 $error = [];
 $currentdateandtime = date("Y/m/d");
//  echo "<br> Date = $currentdateandtime and this ". strtotime($currentdateandtime)."<br>";
//  echo "curr = ".strtotime($currentdateandtime)." and Stattime = ". strtotime($_POST['startdate']);
 if(empty($_POST['examclass'])){
     array_push($error, "Examination class must select");
 }
 if(empty($_POST['classsubject'])){
     array_push($error, "Examination subject must be selected");
 }
 
 if(empty($_POST['examinationdescription'])){
     array_push($error, "Please describe the examination");
 }
 if(empty($_POST['examname'])){
     array_push($error, "Please enter examination name");
 }
 if(empty($_POST['duration'])){
     array_push($error, "Please enter the examination duration in minutes ");
 }
 if(empty($_POST['startdate'])){
     array_push($error, "Please enter the start date for the examination");
 }
 if(empty($_POST['enddate'])){
    array_push($error, "Please enter the end date for the examination");
}
if(empty($_POST['display'])){
    array_push($error, "Please enter the select display mechanism");
}
if(empty($_POST['exampasscode'])){
    array_push($error, "Please enter the examination PIN");
}

if(empty($_POST['visibility'])){
    array_push($error, "Please select examination visibility");
}
if(strtotime($_POST['startdate']) < strtotime($currentdateandtime)){
    array_push($error, "Examination start must greater than current time ");
}

if(strtotime($_POST['enddate']) < strtotime($_POST['startdate'])){
    array_push($error, "Examination end date must greater than start date");
}
foreach($error as $errors){
    echo $errors . "<br>";
}
 if(count($error)==0){
     $classname = $exam->test_input($_POST['examclass']);
     $subject = $exam->test_input($_POST['classsubject']);
     $examinationdescription = $exam->test_input($_POST['examinationdescription']);
     $examname = $exam->test_input($_POST['examname']);
     $duration = $exam->test_input($_POST['duration']);
     $startdate = $exam->test_input(date_format(date_create($_POST['startdate']), "Y-m-d").date(" H:s:i"));
     $enddate= $exam->test_input(date_format(date_create($_POST['enddate']), "Y-m-d")." 23:59:59");
     $exampasscode= $exam->test_input($_POST['exampasscode']);
     $visibility= $exam->test_input($_POST['visibility']);
     $display = $exam->test_input($_POST['display']);

     $q = $exam->query("UPDATE examinations SET subjectid ='$subject',display='$display', classid='$classname', description='$examinationdescription', 
     examname='$examname', duration='$duration', startdate ='$startdate', enddate='$enddate',
      examinationpin='$exampasscode', visibility='$visibility'WHERE examinationid ='$id'");
     if($q){
         echo "<span class='text-success'>Examination updated successfully</span>";
     }else{
         echo "Examination fail to create". $exam->connectionError();
     }
 }

}
?>