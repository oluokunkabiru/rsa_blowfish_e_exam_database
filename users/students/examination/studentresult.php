<?php
session_start();
if ($_SESSION['studentauth']) {
    include("../../../includes/connection.php");
    $login = $_SESSION['studentauth'];
    $auth = new Users;
    $query = $auth->query("SELECT users.*, student.*, class.name AS classname FROM users JOIN student ON student.userid = users.userid JOIN 
    class ON class.classid = student.class WHERE users.username ='$login' OR phone = '$login' OR email = '$login'");    
    $user = $auth->data($query);
    $classid =$user['class'];
    $studentid = $user['studentid'];
     $name = $user['surname']." ". $user['firstname']." ". $user['lastname'];
    $username = $user['username'];
    $_SESSION['studentid'] = $user['studentid'];

$exam =  new Examination;
$q = $exam->query("SELECT class.name AS classname, subjects.subjectname AS subjectname, examinations.examname AS examname,studentstartexamination.reg_date AS dates, studentstartexamination.examinationid AS examinationid FROM class JOIN subjects ON class.classid = subjects.classid JOIN examinations ON examinations.subjectid = subjects.subjectid JOIN studentstartexamination ON studentstartexamination.examinationid = examinations.examinationid WHERE class.classid ='$classid' AND studentstartexamination.studentid = '$studentid' ");
// if($user['active_status']==1){
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>AVAILABLE EXAMINATION< RESULT</title>
        <?php include('header.php') ?>

    </head>

    <body class="">
        <?php include('navbar.php') ?>
    <div class="jumbotron">
                <section class="content">
                   <div class="container mt-auto mb-auto">
                        <h2 class="text-center text-uppercase font-weight-bold">Available Result</h2>
                   
                        <div class="row">
                            <?php 
                            
                            while($data = $exam->data($q)){
                            ?>
                            <div class="col-md-3">
                                <a href="checkresult.php?examinationid=<?php echo $data['examinationid'] ?>">
                                <div class="card text-dark">
                                    <div class="card-body">
                                        <h3><?php echo $data['subjectname'] ?></h3>
                                        <small><?php echo $data['dates'] ?></small>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </section>
               
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

