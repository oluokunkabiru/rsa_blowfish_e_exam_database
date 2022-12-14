<?php
session_start();
if ($_SESSION['studentauth'] && $_SESSION['examinationgoingon']) {
    // current page
    $currentpage=isset($_GET['nextquestions'])?"conductingexamination.php?nextquestions=".$_GET['nextquestions']:"conductingexamination.php";
    // echo $currentpage;

    include("../../../includes/connection.php");
    $login = $_SESSION['studentauth'];
    $auth = new Users;
    $query = $auth->query("SELECT* FROM users WHERE username ='$login' OR phone = '$login' OR email = '$login'");
    $user = $auth->data($query);

    // $names = htmlentities(strtoupper($admins['username']));
    $name = $user['surname'] . " " . $user['firstname'] . " " . $user['lastname'];
    $username = $user['username'];
    $profilepicture = !empty($user['avatar']) ? $user['avatar'] : "../students_avatar/avatar.png";
    $exam = new Examination;
    $examid = $_SESSION['examinationgoingon'];
    $studenid = $_SESSION['studentid'];
    $questionid = "";
    $nextquestion = "";
    $answerid = "";
    $data = "";
    $correctanswer = "";
    $woo = "";
    $previousquestion = "";
    $c = "";
    $tim = new Examination;
    $camst = $tim->query("SELECT* FROM examinations WHERE examinationid='$examid' ");
    $cam = $tim->data($camst);
    $camerastatus = $cam['camera_status'];
    $decryptionKey = $cam['rsa_public'];

    $decryptionKey = $tim->rsaPublic($decryptionKey); 

        // $pin = $exams['rsa_private'];

        $decryptionKey= base64_decode($decryptionKey); 
    // echo $decryptionKey;
    

    $tims = $tim->query("SELECT* FROM studentstartexamination WHERE studentid='$studenid' AND examinationid='$examid'");
    $time = $tim->data($tims);
    $camerapicture = !empty($time['photo']) ? $time['photo'] : "../students_avatar/avatar.png";
    // echo "camera ". $time['photo'];
    $cameracapture = !empty($time['capture_status']) ? $time['capture_status'] : "";
   $cameraphoto = isset($time['captured_photo'])?json_decode($time['captured_photo'],true):[];
    // echo "Cmera capture status ". $cameracapture;
    if(isset($_POST['capturedstudent'])){
        $imagecapture = $_POST['capturedstudent'];
        // $image = $_POST['photo'];
        $dir = "../../camera_monitor_photo/capture/";
        if(!is_dir($dir)){
            mkdir($dir);
        }
        // echo $imagecapture;
        $imgPart = explode(";base64,", $imagecapture);
        $imageType = explode("image/", $imgPart[0]);
        $ext = $imageType[1];
        $imageBase64 = base64_decode($imgPart[1]);
        $imageName = str_replace(" ", "-", $name)."_".$username ."_". time() .".".$ext;
        $cameracap= $dir.$imageName;
        // echo $camera;
        file_put_contents($cameracap, $imageBase64);
    //   echo "<br>Before ".  count($cameraphoto);
      $nextindex = 0;
      foreach ($cameraphoto as $key => $value) {
        $nextindex=$key;
      }
        $cameraphoto[$nextindex+1] = $imageName;
        // echo "Get ". $_GET['nextquestions'];
        $mycapture = json_encode($cameraphoto);
        // print_r($mycapture);
        // echo "nextpage". $nextpagesisexist;
        $aftercapture = $exam->query("UPDATE studentstartexamination SET captured_photo='$mycapture', capture_status=NULL WHERE studentid='$studenid' AND examinationid='$examid'");
        if($aftercapture){
            header("location:$currentpage");
    }
    }

    if (isset($time['studentid'])) {

        $times = isset($time['endtime']) ? $time['endtime'] : "00:00:00";
        $timetofinish = $times;
        $starttime = isset($time['starttime']) ? $time['starttime'] : "00:00:00";
        $submittime = isset($time['reg_date']) ? $time['reg_date'] : "00:00:00";
        $totaltime = date_diff(date_create($starttime), date_create($submittime));
        $submissionstatus = isset($time['status']) ? $time['status'] : 0;

        if (isset($_GET['nextquestions'])) {
            $nextq = $_GET['nextquestions'];
            $ex = $exam->query("SELECT studentexmination.answerstatus AS answerstatus, questions.*, studentexmination.selectedoption AS options, studentexmination.answerid AS answerid, questions.* FROM studentexmination 
    JOIN questions ON studentexmination.questionid = questions.questionid
    WHERE studentexmination.examinationid ='$examid' AND studentexmination.studentid='$studenid' AND studentexmination.answerid ='$nextq'");
            $data = $exam->data($ex);
            $answerid = isset($data['answerid']) ? $data['answerid'] : "";
            // $correctanswer = $data['correct'];
            $curren = $exam->query("SELECT questions.id as qid, studentexmination.id as sqid FROM questions JOIN studentexmination ON questions.questionid = studentexmination.questionid WHERE 
        studentexmination.answerid='$answerid'");
            $curentid = $exam->data($curren);
            // print_r($curentid);
            $currentid = isset($curentid['sqid']) ? $curentid['sqid'] : "";
            // echo "Currrent Id = $currentid<br>";
            // echo "<br> Corrected $correctanswer";
            // previous button
            // previous button
            $pre = $exam->query("SELECT studentexmination.id AS sqid, studentexmination.answerid AS answerid, questions.questionid as qid FROM studentexmination 
        JOIN questions ON studentexmination.questionid = questions.questionid WHERE studentexmination.studentid ='$studenid' 
        AND studentexmination.examinationid ='$examid' AND studentexmination.id < '$currentid' ORDER BY studentexmination.id DESC LIMIT 1");

            // if ($pre) {
            $prev = $exam->data($pre);
            $previousquestion = isset($prev['answerid']) ? $prev['answerid'] : "";

            $npre = $exam->query("SELECT studentexmination.id AS sqid, studentexmination.answerid AS answerid, questions.questionid as qid FROM studentexmination 
            JOIN questions ON studentexmination.questionid = questions.questionid WHERE studentexmination.studentid ='$studenid' 
            AND studentexmination.examinationid ='$examid' AND studentexmination.id > '$currentid' ORDER BY studentexmination.id ASC LIMIT 1");
            // if ($pre) {
                $next = $exam->data($npre);
                $nextquestion = isset($next['answerid']) ? $next['answerid'] : "";

            // } else {
            //     $previousquestion = "";
            // }

            // echo "Previos = $previousquestion";
        } else {
            $ex = $exam->query("SELECT studentexmination.answerstatus AS answerstatus,studentexmination.answerid AS answerid, studentexmination.selectedoption AS options, questions.* FROM studentexmination 
    JOIN questions ON studentexmination.questionid = questions.questionid
    WHERE studentexmination.examinationid ='$examid' AND studentexmination.studentid='$studenid' ORDER BY studentexmination.id ASC LIMIT 1");
            $data = $exam->data($ex);
            $answerid = isset($data['answerid']) ? $data['answerid'] : "";
            // $correctanswer = $data['correct'];
            $previousquestion = "";
        }


        if (isset($_GET['answer'])) {
            $answerid = $_GET['answerid'];
            $correctness = "";
            $co = new Examination;
            $corr = $co->query("SELECT questions.correct AS correct FROM questions JOIN studentexmination ON questions.questionid = studentexmination.questionid WHERE studentexmination.answerid ='$answerid'");
            $correct = $co->data($corr);
            $correctanswer = $correct['correct'];
            if (!empty($_GET['answer'])) {
                $answer = $_GET['answer'];
                $answerstatus = "answered";
                if ($answer == $correctanswer) {
                    $correctness = "correct";
                } else {
                    $correctness = "wrong";
                }
        
                // echo "Correct = $correctanswer and Choose = $answer Correct Status = $correctness";
                $message =[];
                $ans = $exam->query("UPDATE studentexmination SET selectedoption='$answer',correctness='$correctness', answerstatus='$answerstatus' WHERE answerid='$answerid' ");
                if($ans){
                    $curren = $exam->query("SELECT questions.id as qid, studentexmination.id as sqid FROM questions JOIN studentexmination ON questions.questionid = studentexmination.questionid WHERE 
                    studentexmination.answerid='$answerid'");
                        $curentid = $exam->data($curren);
                        $currentid = $curentid['sqid'];
                        // next button
                        $que = $exam->query("SELECT studentexmination.id AS sqid, studentexmination.answerid AS answerid, questions.questionid as qid FROM studentexmination 
                   JOIN questions ON studentexmination.questionid = questions.questionid WHERE studentexmination.studentid ='$studenid' 
                   AND studentexmination.examinationid ='$examid' AND studentexmination.id > $currentid ORDER BY studentexmination.id ASC LIMIT 1");
            
                        $next = $exam->data($que);
                        $answerid = $next['answerid'];
                        $message['next'] = $answerid;
                        // $message['message'] = "Answered successfully";
                        // echo json_encode($message);
        
                        header("location:conductingexamination.php?nextquestions=$answerid");
                }
            } 
            // else {
            //     // next button    
            //     $curren = $exam->query("SELECT questions.id as qid, studentexmination.id as sqid FROM questions JOIN studentexmination ON questions.questionid = studentexmination.questionid WHERE 
            //     studentexmination.answerid='$answerid'");
            //     $curentid = $exam->data($curren);
            //     $currentid = $curentid['sqid'];
    
            //     $que = $exam->query("SELECT studentexmination.id AS sqid, studentexmination.answerid AS answerid, questions.questionid as qid FROM studentexmination 
            //    JOIN questions ON studentexmination.questionid = questions.questionid WHERE studentexmination.studentid ='$studenid' 
            //    AND studentexmination.examinationid ='$examid' AND studentexmination.id > $currentid ORDER BY studentexmination.id ASC LIMIT 1");
    
            //     $next = $exam->data($que);
            //     $answerid = $next['answerid'];
            //     header("location:active_details.php?examinationid=$examid&student=$studenid&nextquestions=$answerid");
            // }
        }
        // if($user['active_status']==1){
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>STUDENT CONDUCTING AN EXAMINATION</title>
            <?php include('header.php') ?>
            <style>
                p {
                    display: inline;
                }

                img {
                    display: block;
                    margin: 10px;
                }

                .timing {
                    border: red 5px solid;
                    max-width: max-content;
                    margin-bottom: 1em;
                    padding: 1em;
                }

                ul#example {
                    list-style: none;
                    padding: 19px 0 0;
                    margin: 0;
                    display: block;
                    text-align: center;
                    background-color: #fff;
                }

                ul#example li {
                    display: inline-block;
                }

                ul#example li span {
                    font-size: 50px;
                    font-weight: 900;
                    color: #00bcd4;
                    letter-spacing: 5px;
                }

                ul#example li span.hap {
                    font-size: 3em;
                    font-weight: 900;
                    color: #00bcda;
                    letter-spacing: 3px;
                }

                ul#example li.seperator {
                    font-size: 50px;
                    vertical-align: top;
                    line-height: 80px;
                    color: #000000;
                    margin: 0 0.3em;
                }

                ul#example li p {
                    color: #000;
                    font-size: 1em;
                    line-height: 1em;
                }



                @media screen and (max-width: 768px) {

                    /* .content {
                top: 40%;
                left: 0;
                right: 0;
                /* position: relative; 
            }
            .content h5 {
                font-size: 13px;
            } */
                    ul#example {
                        list-style: none;
                        padding: 12px 0 0;
                        margin: 0;
                        display: block;
                        text-align: center;
                        background-color: white
                    }

                    ul#example li {
                        display: inline-block;
                    }

                    ul#example li span {
                        font-size: 40px;
                        font-weight: 900;
                        color: #00bcd4;
                        letter-spacing: 3px;
                    }

                    ul#example li span.hap {
                        font-size: 2em;
                        font-weight: 900;
                        color: #00bcda;
                        letter-spacing: 3px;
                    }

                    ul#example li.seperator {
                        font-size: 30px;
                        vertical-align: top;
                        line-height: 50px;
                        color: #000000;
                        margin: 0 0.15em;
                    }

                    ul#example li p {
                        color: #000;
                        font-size: 0.6em;
                        line-height: 0.6em;
                    }


                }
            </style>

        </head>

        <body class="<?php echo isset($cameracapture) && $cameracapture==1?"capturethestudent":"" ?>">
            <?php include('navbar.php');
            
            if(isset($cameracapture) && $cameracapture==1){
            ?>
            <form action="<?php echo $currentpage ?>" id="captureform" method="post">
                <input type="hidden" value="" id="capturedthestudent" name="capturedstudent">
            </form>
            <?php } ?>
            <div class="jumbotron">

                <section class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <!-- <div id="demo"> -->
                                <div id="mytime">

                                </div>
                               
                            <!-- </div> -->
                            <div class="card">
                            <ul id="example">
                                <li><span id="remainhours">00</span>
                                    </li>
                                    <li class="seperator">:</li>
                                    <li><span id="remainminutes">00</span>
                                    </li>
                                    <li class="seperator">:</li>
                                    <li><span id="remainseconds">00</span>
                                    </li>
                                </ul>
                                <!-- <ul id="examp">

                                    <li><span class="hours">00</span>
                                    </li>
                                    <li class="seperator">:</li>
                                    <li><span class="minutes">00</span>
                                    </li>
                                    <li class="seperator">:</li>
                                    <li><span class="seconds">00</span>
                                    </li>
                                </ul> -->

                            </div>
                        </div>

                        <?php if ($submissionstatus == "submitted") { ?>
                        <div class="col-md-8">
                        <div class="card">
                        <div class="card-header"><h5 class="card-titl text-center">Time summary</h5></div>
                        
                        <div class="card-deck">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Start time</h6>
                                    <p class="font-weight-bold"><?php echo date_format(date_create($starttime) ,"Y-m-d h:i:sa") ?></p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h6>End time</h6>
                                    <p class="font-weight-bold"><?php echo date_format(date_create($timetofinish) ,"Y-m-d h:i:sa") ?></p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h6>Time Spent</h6>
                                    <p class="font-weight-bold"><?php echo $totaltime->i." min ". "," .$totaltime->s." sec" ?></p>
                                </div>
                            </div>
                        </div>
                        </div>
                        </div>
                        <?php }else{ ?>
                            <div class="col-md-4">
                            <!-- <div id="demo"> -->
                                <div id="mytime">

                                </div>
                               
                            <!-- </div> -->
                            <div class="card">
                                <?php
                                $tans = $exam->query("SELECT COUNT('answerstatus') AS totalanswered FROM studentexmination WHERE answerstatus='answered' 
                                AND examinationid ='$examid' AND studentid='$studenid' ");
                               $answeredStatus = $exam->data($tans);

                               $untans = $exam->query("SELECT COUNT('answerstatus') AS totalquestion FROM studentexmination WHERE 
                                 examinationid ='$examid' AND studentid='$studenid' ");
                               $unansweredStatus = $exam->data($untans);
                                ?>
                            <ul id="example">
                                <li><span id="answer" class="text-success"><?= $answeredStatus['totalanswered'] ?></span>
                                    </li>
                                    <li class="seperator">/</li>
                                    <li><span id="remainder" class="text-warning"><?= $unansweredStatus['totalquestion'] ?></span>
                                    </li>

                                </ul>
                              

                            </div>
                        </div>
                            <?php } ?>

                    </div>
                </div>
                    <!-- <div class="text-center timing">
                    <h3>Time : <strong><span id="timer">00:00:00</strong></span></h3>
                </div> -->
                    <?php
                    if (!isset($_GET['previewquestions'])) {
                    ?>
                        <div class="container">
                            <div class="row">

                                <div class="col-md-4">
                                <div class="capturemeunknow" style="display: none;"></div>
                                    <?php if ($camerastatus == "on") { ?>
                                        <div class="card card-warning card-outline">
                                            <div class="card-header">
                                                <h5 class="text-center font-weight-bold text-danger">Becareful, You are being monitored</h5>
                                            </div>
                                            <div class="card-body box-profile">
                                                <div id="livepreview"></div>

                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <!-- <div class="card-title"> -->
                                            <h4 class="text-center font-weight-bold">Questions</h4>
                                            <!-- </div> -->
                                        </div>
                                        <div class="card-body box-profile">
                                            <table>
                                                <?php
                                                $qlist = new Examination;
                                                $i = 0;
                                                $quelist = $qlist->query("SELECT studentexmination.id AS sqid, studentexmination.answerstatus AS answerstatus, studentexmination.answerid AS answerid, questions.questionid as qid FROM studentexmination 
                                        JOIN questions ON studentexmination.questionid = questions.questionid WHERE studentexmination.studentid ='$studenid' 
                                        AND studentexmination.examinationid ='$examid' ORDER BY studentexmination.id ASC");
                                                while ($exa = $qlist->data($quelist)) {


                                                ?>
                                                    <?php
                                                    if ($i % 5 == 0) {
                                                        echo "<tr>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($exa['answerid'] == $answerid) {
                                                    ?>
                                                        <td><a href="conductingexamination.php?nextquestions=<?php echo $exa['answerid'] ?>" class="btn m-1 p-3 badge badge-pill badge-primary"><?php echo $i + 1; ?></a></td>
                                                    <?php
                                                    } elseif ($exa['answerstatus'] == "answered") {
                                                    ?>
                                                        <td><a href="conductingexamination.php?nextquestions=<?php echo $exa['answerid'] ?>" class="btn m-1 p-3 badge badge-pill badge-success"><?php echo $i + 1; ?></a></td> <?php
                                                                                                                                                                                                                                ?>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <td><a href="conductingexamination.php?nextquestions=<?php echo $exa['answerid'] ?>" class="btn m-1 p-3 badge badge-pill badge-danger"><?php echo $i + 1; ?></a></td>
                                                <?php
                                                    }

                                                    $i++;
                                                    if ($i % 5 == 0) {
                                                        echo "</tr>";
                                                    }
                                                } ?>
                                            </table>
                                            <a href="conductingexamination.php?previewquestions=preview" class="btn btn-primary btn-block mt-3">Preview</a>
                                        </div>
                                    </div>
                                    <div class="card card-success card-outline">
                                        <div class="card-header">
                                            <!-- <div class="card-title"> -->
                                            <h4 class="text-center font-weight-bold">Student Pictures</h4>
                                            <!-- </div> -->
                                            <div class="card-deck">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h6 class="card-title">Profile picture</h6>
                                                    </div>
                                                    <img src="../<?php echo $profilepicture ?>" style="height: <?php echo isset($camerastatus) && $camerastatus == "on" ? "75px" : "130px" ?>; object-fit:contain" class="rounded img-fluid" alt="">
                                                    <!-- </div> -->
                                                </div>
                                                <?php if ($camerastatus == "on") { ?>

                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h6 class="card-title">Examination Photo</h6>
                                                        </div>
                                                        <img src="../<?php echo $camerapicture ?>" style="height: 75px;" class="rounded img-fluid" alt="">
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="card-body box-profile">

                                        </div>
                                    </div>
                                   



                                </div>

                                <div class="col-md-8">
                                    <div id="answeringstatus">

                                    </div>
                                    <?php
                                    if (!empty($data['question'])) {
                                    ?>
                                        <div class="card card-dark card-outline p-4">
                                            <div class="card-header">
                                                <p><?php echo html_entity_decode($exam->DecyptRSA($data['question'], $decryptionKey)) ?></p>
                                            </div>
                                        </div>
                                        <div class="card card-primary card-outline">
                                            <div class="card-header">
                                                <form action="conductingexamination.php" method="GET">
                                                    <?php if ($data['option_a'] != "") {
                                                    ?>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input options" id="optiona" name="answer" value="A" <?php
                                                                                                                                                            if ($data['answerstatus'] == "answered" && $data['options'] == "A") {
                                                                                                                                                                echo "checked";
                                                                                                                                                            }
                                                                                                                                                            ?>>
                                                            <label class="custom-control-label" for="optiona"> A. <span class="ml-2 text-secondary"><?php echo html_entity_decode($exam->DecyptRSA($data['option_a'], $decryptionKey)) ?></span> </label>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                    <?php if ($data['option_b'] != "") {
                                                    ?>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input options" id="optionb" name="answer" value="B" <?php
                                                                                                                                                            if ($data['answerstatus'] == "answered" && $data['options'] == "B") {
                                                                                                                                                                echo "checked";
                                                                                                                                                            }
                                                                                                                                                            ?>>
                                                            <label class="custom-control-label" for="optionb"> B. <span class="ml-2 text-secondary"><?php echo html_entity_decode($exam->DecyptRSA($data['option_b'], $decryptionKey)) ?></span></label>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                    <?php if ($data['option_c'] != "") {
                                                    ?>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input options" id="optionc" name="answer" value="C" <?php
                                                                                                                                                            if ($data['answerstatus'] == "answered" && $data['options'] == "C") {
                                                                                                                                                                echo "checked";
                                                                                                                                                            }
                                                                                                                                                            ?>>
                                                            <label class="custom-control-label" for="optionc"> C. <span class="ml-2 text-secondary"><?php echo html_entity_decode($exam->DecyptRSA($data['option_c'], $decryptionKey)) ?></span></label>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                    <?php if ($data['option_d'] != "") {
                                                    ?>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input options" id="optiond" name="answer" value="D" <?php
                                                                                                                                                            if ($data['answerstatus'] == "answered" && $data['options'] == "D") {
                                                                                                                                                                echo "checked";
                                                                                                                                                            }
                                                                                                                                                            ?>>
                                                            <label class="custom-control-label" for="optiond"> D. <span class="ml-2 text-secondary"><?php echo html_entity_decode($exam->DecyptRSA($data['option_d'], $decryptionKey)) ?></span></label>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                    <?php if ($data['option_e'] != "") {
                                                    ?>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input options" id="optione" name="answer" value="E" <?php
                                                                                                                                                            if ($data['answerstatus'] == "answered" && $data['options'] == "E") {
                                                                                                                                                                echo "checked";
                                                                                                                                                            }
                                                                                                                                                            ?>>
                                                            <label class="custom-control-label" for="optione"> E. <span class="ml-2 text-secondary"><?php echo html_entity_decode($exam->DecyptRSA($data['option_e'], $decryptionKey)) ?></span></label>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                    <?php if ($data['option_e'] != "") {
                                                    ?>

                                                    <?php
                                                    }
                                                    ?>
                                                    <input type="hidden" name="answerid" id="answerid" value="<?php echo $answerid ?>">
                                                    <input type="hidden" name="nextquestions" id="nextquestions" value="<?php echo $nextquestion ?>">

                                                    <div class="mt-5 p-3">
                                                        <?php
                                                        if (!empty($previousquestion)) {
                                                        ?>
                                                            <a href="conductingexamination.php?nextquestions=<?php echo $previousquestion ?>" class="btn btn-success">Previous</a>
                                                        <?php } ?>

                                                        <button type="submit" class="btn btn-success float-right" name="chooseanswer">Next</button>

                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                    <?php
                                    } else {

                                    ?>
                                        <div class="card p-4">
                                            <div class="card-body text-center">
                                                <h3>You have reached the end of questions</h3>
                                                <div class="text-center">
                                                    <a href="conductingexamination.php?previewquestions=preview" class="p-3 btn btn-success">Preview</a>
                                                </div>
                                                <div class="text-center p-5">
                                                    <?php

                                                    if ($submissionstatus != "submitted") {
                                                    ?>
                                                        <form action="submit.php" method="post">
                                                            <button class="btn btn-lg btn-success" name="submit">Submit</button>
                                                        </form>
                                                    <?php } else {

                                                    ?>
                                                        <a href="checkresult.php?examinationid=<?php echo $examid ?>" class="btn btn-success btn-lg">Check result</a></p>

                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>

                                    <?php }  ?>
                                </div>
                            </div>
                        </div>

                    <?php
                    }
                    if (isset($_GET['previewquestions'])) {
                    ?>
                        <div class="container">
                            <div class="card">
                                <div class="card-header">
                                    <!-- <div class="card-title"> -->
                                    <h3 class="text-center font-weight-bold">Questions Preview</h3>
                                    <!-- </div> -->
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-hover table-striped table-active">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Question Number</th>
                                                <th>Question Status</th>
                                                <th>Choose Option</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-light">

                                            <?php
                                            $qn = 1;
                                            $qs = $exam->query("SELECT studentexmination.id AS sqid, studentexmination.selectedoption AS selectedoption, studentexmination.answerstatus AS answerstatus, studentexmination.answerid AS answerid, questions.questionid as qid FROM studentexmination 
                                JOIN questions ON studentexmination.questionid = questions.questionid WHERE studentexmination.studentid ='$studenid' 
                                AND studentexmination.examinationid ='$examid' ORDER BY studentexmination.id ASC ");

                                            while ($qpreview = $exam->data($qs)) {


                                            ?>
                                                <tr>
                                                    <td><?php echo $qn ?></td>
                                                    <td><?php
                                                        if ($qpreview['answerstatus'] == "unanswered") {
                                                        ?>
                                                            <span class="text-danger"><?php echo ucfirst($qpreview['answerstatus']) ?></span>
                                                        <?php } else {

                                                        ?>
                                                            <span class="text-success"><?php echo ucfirst($qpreview['answerstatus']) ?></span>
                                                        <?php } ?>

                                                    </td>
                                                    <td><?php echo $qpreview['selectedoption'] ? $qpreview['selectedoption'] : "<span class='text-danger'>Nill</span>"  ?></td>
                                                    <td><a href="conductingexamination.php?nextquestions=<?php echo $qpreview['answerid'] ?>" class="btn btn-primary">Change</a></td>

                                                </tr>
                                            <?php
                                                $qn++;
                                            } ?>
                                        </tbody>
                                    </table>
                                    <div class="text-center p-5">
                                        <form action="submit.php" method="post">
                                        <?php if ($submissionstatus != "submitted") { ?>

                                            <button class="btn btn-lg btn-success" name="submit">Submit</button>
                                            <?php } ?>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </section>
            </div>
            <?php include('footer.php'); ?>

        </body>

        </html>
        <?php

        include('script.php');

        if ($submissionstatus != "submitted") {

        ?>
            <script src="../../../plugins/jquery.countdown/jquery.countdown.min.js"></script>
            <script>
                $(document).ready(function() {
                    // if(camera=="on"){
                    <?php if ($camerastatus == "on" || $cameracapture==1) { ?>
                        Webcam.set({
                            width: 320,
                            height: 300,
                            image_format: 'png',
                            jpeg_quality: 90
                        })
                    <?php } ?>
                   
                   <?php if ($camerastatus == "on") { ?>
                        Webcam.attach('#livepreview');
                    <?php } ?>
       
                         <?php

                    if(isset($cameracapture) && $cameracapture==1){

                    
                    ?>
                    Webcam.attach(".capturemeunknow");
                     $(".capturethestudent").click(function(){
                        Webcam.snap( function(image) {
                                // display results in page
                                $("#capturedthestudent").val(image);
                            });
                            $("#captureform").submit();

                     })
                    <?php } ?>


                    
                    $(document).on('change', '.options', function(){
                    var opt = $(this).val();
                    var answerid = $("#answerid").val();
                    // alert(answerid);
                    $.ajax({
                        type: 'GET',
                        url: 'answering.php',
                        data: 'answerid=' + answerid+"&answer="+opt,
                        dataType: "JSON",
                        success: function(data) {
                            // vboy = pas
                            // console.log(data.message);
                            // alert(data)
                            // $('#answeringstatus').html(' <div class="alert alert-success alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert">&times;</button> <strong>Success!</strong> '+ data.message +'</div>');
                            // window.location.assign("conductingexamination.php?nextquestions="+ data.next +"");
                        },

                    })
                })

                })


var serverTime = <?php echo ((strtotime('now') + date_offset_get(new DateTime)) * 1000) ?>;
var localTime = + Date.now();
var timeDiff = serverTime - localTime;
var countDownDate = <?php echo ( (strtotime(date("$times")) + date_offset_get(new DateTime)) * 1000); ?>;

var start = "<?php echo strtotime('now') ?>" * 1000;  
    var now =   new Date(start).getTime(); 
// var i =0;
var x = setInterval(function() {

  var distance = countDownDate - now;
//   $("#mytime").text(countDownDate);
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
  var t = +Date.now() + timeDiff
  var now = new Date(t);
// var now = Date.now();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;
    // console.log(now);
    
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Display the result in the element with id="demo"
  
  $("#remainhours").text(hours);
  $("#remainminutes").text(minutes);
  $("#remainseconds").text(seconds);
    if (distance < 0) {
    clearInterval(x);
    window.location.assign("submit.php?timeup=yes")
                
        }
}, 1000);
            </script>
        <?php
        } else {

        ?>
            <script>
                document.getElementById("optiona").disabled = true;
                document.getElementById("optionb").disabled = true;
                document.getElementById("optionc").disabled = true;
                document.getElementById("optiond").disabled = true;
                document.getElementById("optione").disabled = true;
            </script>
<?php

        }
        // }else{
        //     header('location:../logout.php');
        //     }
    } else {
        header('location:index.php');
    }
} else {
    header('location:../');
}
?>