<?php
session_start();
date_default_timezone_set("Africa/Lagos");
// echo "current " . date("Y-m-d H:i:s")."<br>";
    include("../../includes/connection.php");
if(isset($_POST['requestexampinexaminationid'])){
    if(empty($_POST['pin'])){
        echo "Please enter examination pin,<br>You can ask your teacher for the pin";
    }
   
    if(!empty($_POST['pin'])){
      
        $login = $_SESSION['studentauth'];
        $auth = new Users;
        $query = $auth->query("SELECT users.*, student.*, class.name AS classname FROM users JOIN student ON student.userid = users.userid JOIN 
        class ON class.classid = student.class WHERE users.username ='$login' OR phone = '$login' OR email = '$login'");    
        $camuser = $auth->data($query);
        $camclassid =isset($camuser['class'])?$camuser['class']:"";
        $camname = $camuser['surname']." ". $camuser['firstname']." ". $camuser['lastname'];
        $camusername = $camuser['username'];
        $classname = isset($camuser['classname'])?$camuser['classname']:"";
       
       
     

        $pin = new Examination;
        

        $examid = $_POST['requestexampinexaminationid'];
        
        $pinno = $pin->test_input($_POST['pin']);
        $pinno = $pin->rsaPublic($pinno); 

        // $pin = $exams['rsa_private'];

        $pinno= base64_decode($pinno);  //$exam->rsaPublic($pin);
        // print_r(($pinss));
        // exit();
        // $key =  $pinss;
        $q = $pin->query("SELECT* FROM examinations WHERE examinationid ='$examid' AND examinationpin='$pinno' ");
       $d = $pin->data($q);

    //    $camexamname = isset($d['examinationame'])
    //    $exid = $d['examinationid'];
    //    $stdid = $_SESSION['studentid'];
    $classid = isset($d['classid'])?$d['classid']:"";
        if(isset($d['examinationid']) && !empty($d['examinationid'])){
            $studentid=$_SESSION['studentid']; 
            $examname = $d['examname'];
            $question = new Examination;
            if (strtolower($d['display'])=="random") {
                $q = $question->query("SELECT* FROM questions WHERE examinationid='$examid' ORDER BY RAND() ");
            } else {
                $q = $question->query("SELECT* FROM questions WHERE examinationid='$examid' ORDER BY id ASC");
            }
            
            // check if staudent already enter the pi before
            $ch = $question->query("SELECT* FROM studentexmination WHERE examinationid ='$examid' AND studentid ='$studentid' ");
            $chec = $question->data($ch);
            $examsta = $question->query("SELECT* FROM studentstartexamination WHERE studentid='$studentid' AND examinationid='$examid'");
            $estatus = $question->data($examsta);
            // print_r($estatus);
            // check if users already start the examination
            if(isset($estatus['examinationid']) && !empty($estatus['examinationid'])){
                $starttime = $estatus['starttime'];
                $duration = $estatus['duration'];
                // echo "Duration $duration";
                $endtime = $estatus['endtime'];
                $status ="starting"; 

                // echo "Start already<br>Current Timme $starttime<br>Duration: $duration<br> End time = $endtime";

            }else{
                // new user with new examination
                $starttime = date("Y-m-d H:i:s", strtotime("now"));
                $duration = $d['duration'];
                $time = new DateTime($starttime);
                $time->add(new DateInterval("PT".$duration."M"));
                $endtime = $time->format("Y-m-d H:i:s");
                $status ="starting"; 
                $examtype="cbt";
                // echo "New Examination<br>Current Timme $starttime<br>Duration: $duration<br> End time = $endtime";

                
                $camera = "";
                if(!empty($_POST['photo'])){
                    $image = $_POST['photo'];
                        $dir = "../camera_monitor_photo/";
                        if(!is_dir($dir)){
                            mkdir($dir);
                        }
                        $imgPart = explode(";base64,", $image);
                        $imageType = explode("image/", $imgPart[0]);
                        $ext = $imageType[1];
                        $imageBase64 = base64_decode($imgPart[1]);
                        $imageName = str_replace(" ", "-", $camname)."_".$camusername ."_".str_replace(" ", "-",$classname)."_".str_replace(" ", "-",$examname)."_start_" .$starttime."_end_".$endtime  .".".$ext;
                        $camera= $dir.$imageName;
                        // echo $camera;
                        file_put_contents($camera, $imageBase64);
                        }
                        // echo "Hello $camera";
                $exst = $question->query("INSERT INTO studentstartexamination(photo, studentid,examinationtype, classid, examinationid,starttime, endtime, duration, status, active_status)
                  VALUES('$camera','$studentid','$examtype', '$classid', '$examid', '$starttime', '$endtime', '$duration', '$status', '1')");
                //  if($exst){
                //     echo "Create exam for users";
                // }else{
                //     echo "Fail to create exam ". $question->connectionError();
                // }
        
}
            if(isset($chec['examinationid'])&& !empty($chec['examinationid'])){
                $_SESSION['examinationgoingon'] = $examid;
                $_SESSION['submitted'] ="";
                echo "<span class='text-success'>Examination started successfully</span>";
             }
            else{
            while($data = $question->data($q)){
                $studentquestion = $data['questionid'];
                $answerstatus= "unanswered";
                $answerid = $question->tableid("studentexmination");
                $ex = $question->query("INSERT INTO studentexmination(questionid, studentid, examinationid, answerstatus, answerid)
                VALUES('$studentquestion', '$studentid', '$examid', '$answerstatus', '$answerid')");
               
            }
            $_SESSION['examinationgoingon'] = $examid;
            echo "<span class='text-success'>Examination started successfully</span>";
            }

        }else{
            echo "Incorect Public Key, Please try again or ask your teacher";
        }
    }
}

?>