<?php
    include('../../../includes/connection.php');
    $timing =  new Examination;
    
    $error = [];
    if(isset($_POST['addtime'])){
        if(empty($_POST['addtime'])){
            array_push($error, "Time  name must not be empty");
        }
       
        foreach($error as $errors){
            echo $errors."<br>";
    }

    if(count($error)==0){
        $examid = $_POST['examid'];
        $q = $timing->query("SELECT* FROM studentstartexamination WHERE examinationid='$examid' AND active_status='1' ");
      $status = false;
        $data = $timing->data($q);
           $startimes = $data['endtime'];
          $starttime = date($startimes, strtotime("now"));
            $times = $timing->test_input($_POST['addtime']);
            $studentid = $timing->test_input($_POST['studentid']);
            $time = new DateTime($starttime);
            $time->add(new DateInterval("PT".$times."M"));
            $endtime = $time->format("Y-m-d H:i:s");
            $up = $timing->query("UPDATE studentstartexamination SET endtime = '$endtime', status='starting'  WHERE examinationid='$examid' AND active_status='1' AND studentid='$studentid' ");
            $status = true;
            // echo "Previous time  = $startimes <br> Current time = $endtime <br>Student Id = $studentid";
       
       

        if($status==true){
            echo "<span class='text-success'>Time added successfully</span>";
        }else{
            echo "Class fail to added =". $class->connectionError();
        }
    }

    }
?>