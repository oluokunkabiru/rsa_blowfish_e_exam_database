<?php
session_start();
if ($_SESSION['adminauth']) {
 include('../../../includes/connection.php');
$image = new Examination;
if(isset($_POST['deletecaptureimage'])){
    $studentid = $_POST['studentid'];
    $examid = $_POST['examid'];
    $imageid = $_POST['imageid'];
    
    $qu = $image->query("SELECT * FROM studentstartexamination WHERE examinationid='$examid' AND studentid='$studentid'");
    $prev = $image->data($qu);
    $previousimage = isset($prev['captured_photo'])?json_decode($prev['captured_photo'], true):[];
    $searchimage = array_search($imageid, $previousimage);
    unset($previousimage[$searchimage]);
    // echo "image  $imageid";
    unlink("../../camera_monitor_photo/capture/$imageid");

    $newimages = json_encode($previousimage);
    $newimage = $image->query("UPDATE studentstartexamination SET captured_photo='$newimages' WHERE examinationid='$examid' AND studentid='$studentid'");
    if($newimage){
        //   unlink("../../camera_monitor_photo/capture/$imageid");
          echo "deleted";
    }

}
} else {
    header('location:../../../');
  }
?>