<?php
session_start();
require_once('../../../includes/constant.php');
require_once("../../../includes/connection.php");
if ($_SESSION['teacherauth']) {
    // include("../../../includes/connection.php");
    if(isset($_GET['student'])){
    $studentid = $_GET['student'];
    }
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHECK STUDENT RESULT</title>
    <?php include('../header.php') ?>

  </head>

  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <?php include('../sidebar.php') ?>
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid">
              <?php
               $student = new Users;
                if(!empty($studentid)){
                $q = $student->query("SELECT users.*, student.*,  class.name AS classname FROM users JOIN student ON users.userid = student.userid JOIN class ON student.class = class.classid WHERE users.userid ='$studentid' ");

                $data = $student->data($q);
                $studentids=isset($data['studentid'])?$data['studentid']:"";
                if(!empty($studentids)){
                $studentnames = $data['surname']." ". $data['firstname']." ". $data['lastname'];
               
                //  }
                  // if(!empty(trim($studentnames))){     
              ?>
            <h2 class="text-center text-uppercase font-weight-bold mt-2 mb-2">Check <?php echo $studentnames ?> result</h2>
          <?php 
            $result = $student->query("SELECT DISTINCT class.name AS classname, class.id AS classid, studentstartexamination.classid AS classid FROM class JOIN studentstartexamination ON class.classid = studentstartexamination.classid WHERE studentstartexamination.studentid='$studentids'");
                  $da = $student->data($result);
                  $resultlist = isset($da['classname'])?$da['classname']:"";
                  // echo "result " . strlen($resultlist);
                  if(!empty($resultlist)){

                  $result = $student->query("SELECT DISTINCT class.name AS classname, class.id AS classid, studentstartexamination.classid AS classid FROM class JOIN studentstartexamination ON class.classid = studentstartexamination.classid WHERE studentstartexamination.studentid='$studentids'");


          ?>
            <div class="row">
              <?php 
                while($class = $student->data($result)){
                  // $resultlist = isset($class['classname'])?$class['classname']:"";
               ?>
                <div class="col-md-4">
                    <a href="#selectTerm" selectClass="<?php echo ucwords($class['classname']) ?>" data-toggle="modal" resultUrl="student_result.php?class=<?php echo $class['classid']  ?>&student=<?php echo $studentids ?>">
                    <div class="card">
                        <div class="card-header">
                            <h4><?php echo ucwords($class['classname']) ?></h4>
                        </div>
                       
                    </div>
                </a>
                </div>
                <?php }
                ?>
                <?php }else{ ?>
                    <h3 class="text-center text-uppercase text-danger py-5"> no result found for <?php echo $studentnames ?></h3>
                  
                  <?php } ?>
            </div>
           <?php 
                }else{

                    ?>

                <h3 class="text-danger text-center"> No user with <strong> <?php echo $studentid ?> </strong> details</h3>
        <?php
            }
        }else{

           ?>
            <h3 class="text-danger text-center text-uppercase"><strong> Oops !!! </strong></h3>
            <h4 class="text-danger text-center">It seems you are accessing wrong URL</h4>
           <?php
}
           ?>


<div class="modal fade" id="selectTerm">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title text-uppercase font-weight-bold">Select term for <span id="selectClass"></span> result</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
        <a href="" id="firstTermUrl">
          <div class="card">
              <div class="card-body">
                <h3>First Term</h3>
              </div>
          </div>
          </a>

          <a href="" id="secondTermUrl">
          <div class="card">
              <div class="card-body">
                <h3>Second Term</h3>
              </div>
          </div>
          </a>

          <a href="" id="thirdTermUrl">
          <div class="card">
              <div class="card-body">
                <h3>Third Term</h3>
              </div>
          </div>
          </a>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>

        </section>

      </div>
      <?php include('../footer.php'); ?>
    </div>
  </body>

  </html>


<?php
  include('../script.php');
} else {
  header('location:../../../');
}
?>

<script>
  function searchstudent(str) {
  // var classe = "";
  // alert(str);
  if (str.length == 0) {
      document.getElementById("searchstudent").innerHTML = "";
      return;
  } else {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            var result = document.getElementById("searchstudent")
              var ret = this.responseText
              result.innerHTML = ret;
              // classe = str;
          }
      };
      xmlhttp.open("GET", "searchstudent.php?student="+str, true);
      xmlhttp.send();
  }
} 
  

$("#selectTerm").on('show.bs.modal', function (e) {
  var currentUrl = $(e.relatedTarget).attr('resultUrl');
  var selectClass = $(e.relatedTarget).attr('selectClass');
  var firstTermUrl = currentUrl+"&term=First term";
  var secondTermUrl = currentUrl+"&term=Second term";
  var thirdTermUrl = currentUrl+"&term=Third term";
  $("#selectClass").text(selectClass);
  $("#firstTermUrl").attr("href", firstTermUrl);
  $("#secondTermUrl").attr("href", secondTermUrl);
  $("#thirdTermUrl").attr("href", thirdTermUrl);
    });
</script>