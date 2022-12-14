<?php
session_start();
require_once('../../../includes/constant.php');
  require_once("../../../includes/connection.php");
if ($_SESSION['adminauth']) {

if ($_GET['student'] && $_GET['examinationid']) {
    //     echo date("Y/m/d H:i:s", strtotime("now")) . "\n";
    // echo date("Y/m/d H:i:s", strtotime("+30 minutes"));

    $examid = $_GET['examinationid'];
    $studentid = $_GET['student'];
    
    $exam = new Examination;
    $question = $exam->query("DELETE FROM studentexmination WHERE studentid = '$studentid' AND examinationid='$examid' ");
    if($question){
        $ex = $exam->query("DELETE FROM studentstartexamination WHERE studentid = '$studentid' AND examinationid='$examid' ");
        if($ex){
         header('location:active.php?examinationid='.$examid);   
        }
    }

}else{
    ?>

<!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACTIVE ONLINE STUDENT</title>
    <?php include('../header.php') ?>
  
    </head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <?php include('sidebar.php');?>

            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="col-md-6">
                            <div class="card">
                                <h3 class="text-danger">No questions available for this student and this examination</h3>
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </div>
            </div>
    </div>
      <?php include('../footer.php'); ?>
    </div>
  </body>

  </html>


<?php
}

include('../script.php');
 }else{
     header('location:'. LOGOUT );
 }
?>