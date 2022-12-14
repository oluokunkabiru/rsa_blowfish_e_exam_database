<?php
session_start();
if ($_SESSION['studentauth']) {
    include("../../../includes/connection.php");
    $login = $_SESSION['studentauth'];
    $auth = new Users;
    $query = $auth->query("SELECT users.*, student.*, class.name AS classname FROM users JOIN student ON student.userid = users.userid JOIN 
    class ON class.classid = student.class WHERE users.username ='$login' OR phone = '$login' OR email = '$login'");    
    $user = $auth->data($query);
    $classid =isset($user['class'])?$user['class']:"";
     $name = $user['surname']." ". $user['firstname']." ". $user['lastname'];
    $username = $user['username'];
    $_SESSION['studentid'] = $user['studentid'];
    $studentid = $user['studentid'];

$exam =  new Examination;
$q = $exam->query("SELECT examinations.*, subjects.subjectname as subjectname FROM examinations JOIN subjects ON 
examinations.subjectid = subjects.subjectid WHERE examinations.classid ='$classid' AND (examinations.status ='available' OR 
examinations.status ='progress') AND (examinations.enddate >= NOW())");
// if($user['active_status']==1){

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>AVAILABLE EXAMINATION</title>
        <?php include('header.php') ?>

    </head>

    <body class="">
        <?php include('navbar.php') ?>
    <div class="jumbotron">
                <section class="content">
                   <div class="container mt-auto mb-auto">
                        <h2 class="text-center text-uppercase font-weight-bold">Available Examination</h2>
                   
                 
                   <div class="row">
                   <!--<div class="col-md-2"></div>-->
                   <!--<div class="col-md-6">-->
                       <?php
                       $checkme = $exam->data($q);
                       $examavail = isset($checkme['examinationid'])?$checkme['examinationid']:"";
                   

                       if(!empty($examavail)){
                        $exam =  new Examination;
                        $q = $exam->query("SELECT examinations.*, subjects.subjectname as subjectname FROM examinations JOIN subjects ON 
                        examinations.subjectid = subjects.subjectid WHERE examinations.classid ='$classid' AND (examinations.status ='available' OR 
                        examinations.status ='progress') AND (examinations.enddate >= NOW()) ORDER BY id DESC");
                        
                       while($exams = $exam->data($q)){
                      
                        $examinationid = $exams['examinationid'];
                        $total = $exam->query("SELECT COUNT(examinationid) AS totalquestion FROM questions WHERE examinationid ='$examinationid' ");
                        $totalquestion = $exam->data($total);
                        $camera =$exams['camera_status'];
                        $past = $exam->query("SELECT* FROM studentstartexamination WHERE studentid ='$studentid' AND examinationid='$examinationid' AND classid='$classid' ");
                        $studentpass = $exam->data($past);
                        $studentalreadydidexamination = isset($studentpass['status'])?$studentpass['status']:"";
                        // echo $studentalreadydidexamination;
                       
                       ?>
                        <div class="col-md-6">

                       <?php if(isset($_SESSION['examinationgoingon']) && $_SESSION['examinationgoingon']==$examinationid && $studentalreadydidexamination !="submitted"){ ?>
                        <a href="conductingexamination.php" camera ="<?php echo $camera ?>"  requestexaminationpin="<?php echo $examinationid ?>">
                                        <div class="info-box mb-3 py-4 bg-success">
                                            <span class="info-box-icon"><i class="fas fa-tag"></i></span>

                                            <div class="info-box-content">
                                                <p class="text-warning font-weight-bold">Click to continue......</p>
                                                <h6 class="info-box-tet font-weight-bold"><?php echo ucwords($exams['subjectname']) ?></h6>
                                                <span class="info-box-number">Duration: <strong><?php echo $exams['duration'] ?></strong></span>
                                                <span class="info-box-number">Number of Question : <strong><?php echo $totalquestion['totalquestion'] ?></strong></span>
                                            </div>
                                            <!-- /.info-box-content -->
                                        </div>
                                    </a>
                        <?php }elseif($studentalreadydidexamination=="submitted"){
                            $_SESSION['examinationgoingon'] = $examinationid ;
                            ?> <a href="conductingexamination.php" camera ="<?php echo $camera ?>"  requestexaminationpin="<?php echo $examinationid ?>">
                            <div class="info-box mb-3 py-4 bg-success">
                                <span class="info-box-icon"><i class="fas fa-tag"></i></span>

                                <div class="info-box-content">
                                    <h6 class="text-danger font-weight-bold">Submitted</h6>
                                    <h4 class="info-box-txt font-weight-bold"><?php echo ucwords($exams['subjectname']) ?></h4>
                                    <span class="info-box-number">Duration: <strong><?php echo $exams['duration'] ?></strong></span>
                                    <span class="info-box-number">Number of Question : <strong><?php echo $totalquestion['totalquestion'] ?></strong></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </a>

                            <?php  
                        } else {?>
                      <a href="#requestexaminationpin" camera ="<?php echo $camera ?>"  data-toggle="modal" requestexaminationpin="<?php echo $examinationid ?>">
                                        <div class="info-box mb-3 py-4 bg-success">
                                            <span class="info-box-icon"><i class="fas fa-tag"></i></span>

                                            <div class="info-box-content">
                                                <h4 class="info-box-txt font-weight-bold"><?php echo ucwords($exams['subjectname']) ?></h4>
                                                <span class="info-box-number">Duration: <strong><?php echo $exams['duration'] ?></strong></span>
                                                <span class="info-box-number">Number of Question : <strong><?php echo $totalquestion['totalquestion'] ?></strong></span>
                                            </div>
                                            <!-- /.info-box-content -->
                                        </div>
                                    </a>

                                    <?php } ?>
                                    </div>

                                    <?php
                       }
                    }else{
                                    ?>
                                <div class="info-box mb-3 py-4 bg-danger">
                                            <span class="info-box-icon"><i class="fas fa-tag"></i></span>

                                            <div class="info-box-content">
                                                <span class="info-box-txt font-weight-bold">No Examination available at this moment</span>
                                               
                                            </div>
                                            <!-- /.info-box-content -->
                                        </div>
                                        
                    <?php } ?>
                                    
                   <!--<div class="col-md-3"></div>-->
                   </div>
                    </div>
                </section>
                <div class="modal" id="requestexaminationpin">
                    <div class="modal-dialog">
                        <div class="requestexaminationpin">

                        </div>
                    </div>
                </div>
          </div>       
            <?php include('footer.php'); ?>
   
    </body>

    </html>
<?php
    include('script.php');
// }else{
//     header('location:../logout.php');
//     }
} else {
    header('location:../../');
}
?>

<script>
    $(document).ready(function(){
  $('#requestexaminationpin').on('show.bs.modal', function (e) {
    // $("#cameralive").html("helo");

  var id = $(e.relatedTarget).attr('requestexaminationpin');
  var camera = $(e.relatedTarget).attr('camera');
//   alert(myid);


  $.ajax({
  type:'post',
  url:'../confirm.php',
  data:'requestexaminationpin='+id,
  success:function(data){
       $('.requestexaminationpin').html(data);
       if(camera=="on"){
           $("#cameraon").removeAttr("style");
        Webcam.set({
			width: 200,
			height: 200,
			image_format: 'png',
			jpeg_quality: 90
		})
        $("#livevideotitle").text("Please take a clear photo here");
        Webcam.attach( '#cameralive' );
        $("#btnconfirmexampin").click(function(){
            Webcam.snap( function(image) {
				// display results in page
                $("#takephotodisplay").text("Your photo preview");
                $("#cameraphoto").html('<img src="'+image+'" style="height: 200px;" class="img-fluid card-img" alt="">')
                $("#takephoto").val(image);
			});
        })
    }

       
        
    }   
})

})

})

</script>