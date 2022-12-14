<?php
session_start();
require_once('../../../includes/constant.php');
require_once("../../../includes/connection.php");
if ($_GET['student'] && $_GET['examinationid']) {
    //     echo date("Y/m/d H:i:s", strtotime("now")) . "\n";
    // echo date("Y/m/d H:i:s", strtotime("+30 minutes"));

    $examid = $_GET['examinationid'];
    $studenid = $_GET['student'];
    $exam = new Examination;
   
   
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
    $tims = $tim->query("SELECT* FROM studentstartexamination WHERE studentid='$studenid' AND examinationid='$examid'");
    $time = $tim->data($tims);
    $times = $time['endtime'];
    $camerapicture = !empty($time['photo']) ? $time['photo'] : "../students_avatar/avatar.png";
    $submissionstatus = $time['status'];
    if (isset($_GET['nextquestions'])) {
        $nextq = $_GET['nextquestions'];
        $ex = $exam->query("SELECT studentexmination.answerstatus AS answerstatus, questions.*, studentexmination.selectedoption AS options, studentexmination.answerid AS answerid, questions.* FROM studentexmination 
    JOIN questions ON studentexmination.questionid = questions.questionid
    WHERE studentexmination.examinationid ='$examid' AND studentexmination.studentid='$studenid' AND studentexmination.answerid ='$nextq'");
        $data = $exam->data($ex);
        $answerid = $data['answerid'];
        // $correctanswer = $data['correct'];
        $curren = $exam->query("SELECT questions.id as qid, studentexmination.id as sqid FROM questions JOIN studentexmination ON questions.questionid = studentexmination.questionid WHERE 
        studentexmination.answerid='$answerid'");
        $curentid = $exam->data($curren);
        $currentid = $curentid['sqid'];
        // echo "<br> Corrected $correctanswer";
        // previous button
        // previous button
        $pre = $exam->query("SELECT studentexmination.id AS sqid, studentexmination.answerid AS answerid, questions.questionid as qid FROM studentexmination 
        JOIN questions ON studentexmination.questionid = questions.questionid WHERE studentexmination.studentid ='$studenid' 
        AND studentexmination.examinationid ='$examid' AND studentexmination.id < $currentid ORDER BY studentexmination.id DESC LIMIT 1");

        if ($pre) {
            $prev = $exam->data($pre);
            $previousquestion = $prev['answerid'];
        } else {
            $previousquestion = "";
        }

        // echo "Previos = $previousquestion";
    } else {
        $ex = $exam->query("SELECT studentexmination.answerstatus AS answerstatus,studentexmination.answerid AS answerid, studentexmination.selectedoption AS options, questions.* FROM studentexmination 
    JOIN questions ON studentexmination.questionid = questions.questionid
    WHERE studentexmination.examinationid ='$examid' AND studentexmination.studentid='$studenid' ORDER BY studentexmination.id ASC LIMIT 1");
        $data = $exam->data($ex);
        $answerid = $data['answerid'];
        // $correctanswer = $data['correct'];
        $previousquestion = "";
    }


    if (isset($_POST['chooseanswer'])) {
        $answerid = $_POST['answerid'];
        $correctness = "";
        $co = new Examination;
        $corr = $co->query("SELECT questions.correct AS correct FROM questions JOIN studentexmination ON questions.questionid = studentexmination.questionid WHERE studentexmination.answerid ='$answerid'");
        $correct = $co->data($corr);
        $correctanswer = $correct['correct'];
        if (!empty($_POST['answer'])) {
          
        } else {
            // next button    
            $curren = $exam->query("SELECT questions.id as qid, studentexmination.id as sqid FROM questions JOIN studentexmination ON questions.questionid = studentexmination.questionid WHERE 
            studentexmination.answerid='$answerid'");
            $curentid = $exam->data($curren);
            $currentid = $curentid['sqid'];

            $que = $exam->query("SELECT studentexmination.id AS sqid, studentexmination.answerid AS answerid, questions.questionid as qid FROM studentexmination 
           JOIN questions ON studentexmination.questionid = questions.questionid WHERE studentexmination.studentid ='$studenid' 
           AND studentexmination.examinationid ='$examid' AND studentexmination.id > $currentid ORDER BY studentexmination.id ASC LIMIT 1");

            $next = $exam->data($que);
            $answerid = $next['answerid'];
            header("location:active_details.php?examinationid=$examid&student=$studenid&nextquestions=$answerid");
        }
    }

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>STUDENT EXAMINATION DETAILS</title>
        <?php include('../header.php') ?>
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

ul#example li { display: inline-block; }

ul#example li span {
    font-size: 50px;
    font-weight: 900;
    color: #00bcd4;
    letter-spacing: 5px;
}
ul#example li span.hap{
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

ul#example li { display: inline-block; }

ul#example li span {
    font-size: 40px;
    font-weight: 900;
    color: #00bcd4;
    letter-spacing: 3px;
}
ul#example li span.hap{
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

    <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <?php include('../sidebar.php') ?>
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid">
          <section>
            <div class="modal" id="addtime">
              <div class="modal-dialog">
                <div class="modal-content">
                   
                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h3 class="modal-title text-uppercase font-weight-bold">add more time to user</h3>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <p class="error text-danger ml-3"></p>
                    <form id="addtimethisuserform">
                      <label for="">Choose Time (Minutes)</label>
                    <select name="addtime" class="custom-select-sm">
                      <option value="">Choose Time</option>
                      <?php 
                        $i = 0;
                        while($i < 60){
                       ?> 
                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php 
                  $i++;
                  } ?>
                    
                  </select>
                  <input type="hidden" name="examid" value="<?php echo $examid ?>">
                  <input type="hidden" name="studentid" value="<?php echo $studenid ?>">
                  
                    </form>
                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                    <button class="btn btn-primary btn-block text-uppercase" id="addtimetothisuserbtn" name="addnewclass">Add time</button>
                  </div>

                </div>
              </div>
        
            </div>
        
          
        </section>

          <?php
                    
                    // $auth = new Users;
                   
                   $query = $auth->query("SELECT users.firstname AS fname, users.avatar AS avatar, users.username AS username, subjects.subjectname AS subjectname, users.surname AS sname, users.lastname AS lname, examinations.examname AS examname, studentstartexamination.studentid AS studentid, studentstartexamination.examinationid AS examinationid, class.name AS classname, class.classid as classid FROM users
                   JOIN student ON users.userid = student.userid
                   JOIN class ON class.classid = student.class
                   JOIN studentstartexamination ON studentstartexamination.studentid = student.studentid
                   JOIN examinations ON examinations.examinationid = studentstartexamination.examinationid 
                   JOIN subjects ON examinations.subjectid = subjects.subjectid
                   WHERE studentstartexamination.examinationid='$examid' AND student.studentid='$studenid'");
                   $user = $auth->data($query);
                //    print_r($user);
                   // $names = htmlentities(strtoupper($admins['username']));$studenid
                   $name = $user['sname'] . " " . $user['fname'] . " " . $user['lname'];
                   $username = $user['username'];
                   $profilepicture = !empty($user['avatar']) ? $user['avatar'] : "../students_avatar/avatar.png";


                    ?>

            <a href="#addtime" class="btn btn-success btn-lg text-uppercase mt-2 mb-2" data-toggle="modal"><span class="fa fa-clock mr-2"></span>Add time to this users</a>
            <a href="#resetquestion" class="float-right btn btn-danger text-uppdercase" data-toggle="modal">Reset examinations</a>
   
            <div class="row">
                    <div class="col-md-7">
                        <div class="card p-3">
                            <div class="row">
                                <div class="col"><h4>Name</h4></div>
                                <div class="col"><h4><strong><?php echo ucwords( $name) ?></strong></h4></div>
                            </div>
                            <div class="row">
                                <div class="col"><h4>Subject</h4></div>
                                <div class="col"><h4><strong><?php echo ucwords( $user['subjectname']) ?></strong></h4></div>
                            </div>
                            <div class="row">
                                <div class="col"><h4>Class</h4></div>
                                <div class="col"><h4><strong><?php echo ucwords( $user['classname']) ?></strong></h4></div>
                            </div>
                            <div class="row">
                                <div class="col"><h4>Username</h4></div>
                                <div class="col"><h4><strong><?php echo ($user['username']) ?></strong></h4></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
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
                                      
                        </div>
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
                                                    <td><a href="active_details.php?examinationid=<?php echo $examid ?>&student=<?php echo $studenid?>&nextquestions=<?php echo $exa['answerid'] ?>" class="btn m-1 p-3 badge badge-pill badge-primary"><?php echo $i + 1; ?></a></td>
                                                <?php
                                                } elseif ($exa['answerstatus'] == "answered") {
                                                ?>
                                                    <td><a href="active_details.php?examinationid=<?php echo $examid ?>&student=<?php echo $studenid?>&nextquestions=<?php echo $exa['answerid'] ?>"  class="btn m-1 p-3 badge badge-pill badge-success"><?php echo $i + 1; ?></a></td> <?php
                                                                                                                                                                                                                            ?>
                                                <?php
                                                } else {
                                                ?>
                                                    <td><a href="active_details.php?examinationid=<?php echo $examid ?>&student=<?php echo $studenid?>&nextquestions=<?php echo $exa['answerid'] ?>" class="btn m-1 p-3 badge badge-pill badge-danger"><?php echo $i + 1; ?></a></td>
                                            <?php
                                                }

                                                $i++;
                                                if ($i % 5 == 0) {
                                                    echo "</tr>";
                                                }
                                            } ?>
                                        </table>
                                        <a href="active_details.php?examinationid=<?php echo $examid ?>&student=<?php echo $studenid?>&previewquestions=preview" class="btn btn-primary btn-block mt-3">Preview</a>
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
                                                    <img src="../<?php echo $profilepicture ?>" style="height: <?php echo isset($camerastatus) && $camerastatus == "on" ? "75px" : "130px" ?>;" class="rounded img-fluid" alt="">
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
                                <?php
                                if (!empty($data['question'])) {
                                ?>
                                    <div class="card card-dark card-outline p-4">
                                        <div class="card-header">
                                            <p><?php echo html_entity_decode($data['question']) ?></p>
                                        </div>
                                    </div>
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <form action="active_details.php?examinationid=<?php echo $examid ?>&student=<?php echo $studenid?>" method="post">
                                                <?php if ($data['option_a'] != "") {
                                                ?>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input options" id="optiona" name="answer" value="A" <?php
                                                                                                                                                        if ($data['answerstatus'] == "answered" && $data['options'] == "A") {
                                                                                                                                                            echo "checked";
                                                                                                                                                        }
                                                                                                                                                        ?>>
                                                        <label class="custom-control-label" for="optiona"> A. <span class="ml-2 text-secondary"><?php echo html_entity_decode($data['option_a']) ?></span> </label>
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
                                                        <label class="custom-control-label" for="optionb"> B. <span class="ml-2 text-secondary"><?php echo html_entity_decode($data['option_b']) ?></span></label>
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
                                                        <label class="custom-control-label" for="optionc"> C. <span class="ml-2 text-secondary"><?php echo html_entity_decode($data['option_c']) ?></span></label>
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
                                                        <label class="custom-control-label" for="optiond"> D. <span class="ml-2 text-secondary"><?php echo html_entity_decode($data['option_d']) ?></span></label>
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
                                                        <label class="custom-control-label" for="optione"> E. <span class="ml-2 text-secondary"><?php echo html_entity_decode($data['option_e']) ?></span></label>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                                <?php if ($data['option_e'] != "") {
                                                ?>

                                                <?php
                                                }
                                                ?>
                                                <input type="hidden" name="answerid" value="<?php echo $answerid ?>">

                                                <div class="mt-5 p-3">
                                                    <?php
                                                    if (!empty($previousquestion)) {
                                                    ?>
                                                        <a href="active_details.php?examinationid=<?php echo $examid ?>&student=<?php echo $studenid?>&nextquestions=<?php echo $previousquestion ?>" class="btn btn-success">Previous</a>
                                                    <?php } ?>

                                                    <button class="btn btn-success float-right" name="chooseanswer">Next</button>

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
                                                <a href="active_details.php?examinationid=<?php echo $examid ?>&student=<?php echo $studenid?>&previewquestions=preview" class="p-3 btn btn-success">Preview</a>
                                            </div>
                                            <div class="text-center p-5">
                                               
                                               
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
                                                <td><a href="active_details.php?examinationid=<?php echo $examid ?>&student=<?php echo $studenid?>&nextquestions=<?php echo $qpreview['answerid'] ?>" class="btn btn-primary">Change</a></td>

                                            </tr>
                                        <?php
                                            $qn++;
                                        } ?>
                                    </tbody>
                                </table>
                                <div class="text-center p-5">
                                    <form action="submit.php" method="post">
                                        <button class="btn btn-lg btn-success" name="submit">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
         
                <?php } ?>
            </section>

            <div class="modal" id="resetquestion">
              <div class="modal-dialog">
                <div class="modal-content">
                   
                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h3 class="modal-title text-uppercase font-weight-bold">reset examination questions for <?= $name ?></h3>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <p class="text-danger ml-3">All question and result for this <?= $name ?>  will be deleted </p>
                    <p class="text-danger"> Click below button to continue the questions reseting</p>
                   
                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                  <a href="reset_examination.php?examinationid=<?= $examid ?>&student=<?= $studenid?>" class="btn btn-danger btn-block text-uppercase my-3"> Proceed to reset</a>

                  </div>

                </div>
              </div>
            </div>
        </div>
        <?php include('../footer.php'); ?>

    </body>

    </html>

    <?php
    include('../script.php');

    if ($submissionstatus != "submitted") {

    ?>

        <script>
               $(document).ready(function() {

                // $("#example").countdown("<?php echo $times ?>").on('update.countdown', function(event) {
                //         var $this = $(this).html(event.strftime('' +
                //                 '<li><span>%-H</span></li>' +
                //                 '<li class="seperator">:</li>' +
                //                 '<li><span>%-M</span></li>' +
                //                 '<li class="seperator">:</li>' +
                //                 '<li><span>%-S</span></li>'
                //             )

                //         )
                //     })
            })

             document.getElementById("optiona").disabled = true;
            document.getElementById("optionb").disabled = true;
            document.getElementById("optionc").disabled = true;
            document.getElementById("optiond").disabled = true;
            document.getElementById("optione").disabled = true;
            // // var date = 



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
  
  // Display the result in the element with id="demo"
  
  $("#remainhours").text(hours);
  $("#remainminutes").text(minutes);
  $("#remainseconds").text(seconds);
  
//   document.getElementById("demo").innerHTML = days + "d " + hours + "h "
//   + minutes + "m " + seconds + "s ";

  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    window.location.assign("submit.php?timeup=yes")
                
        }
}, 1000);
            // // Set the date we're counting down todate, location, timeid
            // var countDownDate = new Date("<?php //echo $times ?>").getTime();

            // // Update the count down every 1 second
            // var x = setInterval(function() {

            //     // Get todays date and time
            //     var now = new Date().getTime();

            //     // Find the distance between now an the count down date
            //     var distance = countDownDate - now;

            //     // Time calculations for days, hours, minutes and seconds
            //     var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            //     var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            //     var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            //     var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            //     // Output the result in an element with id="demo"
            //     document.getElementById("timer").innerHTML = +hours + " : " +
            //         minutes + " : " + seconds;

                // If the count down is over, write some text 
                // if (distance < 0) {
                //     clearInterval(x);
                //     window.location.assign("submit.php?timeup=yes");
                // }
            // }, 1000);


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
    include('script.php');
} else {
    header('location:../');
}
?>