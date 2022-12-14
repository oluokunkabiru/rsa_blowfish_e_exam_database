<?php
session_start();
require_once('../../../includes/constant.php');
require_once("../../../includes/connection.php");
if ($_SESSION['adminauth']) {
  //  include('../../includes/connection.php');
  $examid = $_GET['examinationid'];



?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACTIVE ONLINE STUDENT</title>
    <?php include('../header.php') ?>
      <style>
        .imagecontainer {
        position: relative;
        width: 50%;
    }
            .theimage {
            opacity: 1;
            display: block;
            /* width: 100%; */
            height: auto;
            transition: .5s ease;
            backface-visibility: hidden;
          }

        .imageoptions {
        transition: .5s ease;
        opacity: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        /* text-align: center; */
      }
      .imagecontainer:hover .theimage {
        opacity: 0.3;
      }

      .imagecontainer:hover .imageoptions {
        opacity: 1;
      }

      </style>
  </head>

  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <?php include('../sidebar.php');
        if(isset($_GET['capture'])){
          $studentid = $_GET['student'];
          $examinationid = $_GET['examinationid'];
          $camerastatus = $_GET['capture'];
          $came = new Users;
          // $prev = $came->query("SELECT * FROM studentstartexamination WHERE studentid='$student' AND examinationid='$examinationid'");
          // $studentdata = $came->data($prev);
          
          $qe = $came->query("UPDATE studentstartexamination SET capture_status='1' WHERE studentid='$studentid' AND examinationid='$examinationid' ");
          if($qe){
             echo "<script>window.location.assign('active.php?examinationid=$examinationid'); </script>";
          }

        
        }
      ?>
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid">

            <h2 class="text-center text-uppercase font-weight-bold mt-2 mb-2">active online students</h2>
            <a href="#addtime" class="btn btn-success btn-lg text-uppercase mt-2 mb-2" data-toggle="modal"><span class="fa fa-clock mr-2"></span>Add time to this users</a>

            <table id="tables" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Surname</th>
                  <th>Firstname</th>
                  <th>Middlename</th>
                  <th>Username</th>
                  <th>Class</th>
                  <th>Capture Student Photo</th>
                  <th>Examination</th>
                  <th>Profile</th>
                  <th>Exam photo</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i =0;
                  $student = new Users;
                  $q = $student->query("SELECT users.* , examinations.examname AS examname,studentstartexamination.capture_status AS capturestatus,studentstartexamination.captured_photo AS capturephoto,  studentstartexamination.photo AS photo, studentstartexamination.studentid AS studentid, studentstartexamination.examinationid AS examinationid, class.name AS class, class.classid as classid FROM users
                  JOIN student ON users.userid = student.userid
                  JOIN class ON class.classid = student.class
                  JOIN studentstartexamination ON studentstartexamination.studentid = student.studentid
                  JOIN examinations ON examinations.examinationid = studentstartexamination.examinationid WHERE studentstartexamination.examinationid='$examid' AND studentstartexamination.active_status='1'");
                  while($data = $student->data($q)){
                    $capturestatus = isset($data['capturestatus']) && $data['capturestatus']==1?'<span class="spinner-border spinner-border-sm"></span>Capturing...':'';
                    $capturephoto = isset($data['capturephoto'])?json_decode($data['capturephoto'], true):[];
                    // echo print_r($capturephoto);
                    $captureimg = isset($data['capturephoto'])?$data['capturephoto']:"{}";
                    $numberOfCaptures = count($capturephoto);
                    $numberOfCapture = $numberOfCaptures > 0?'<span class="badge  badge-pill p-2 badge-danger text-light ">'.$numberOfCaptures.'</span>':'';
                ?>
                  <tr>
                    <td scope="row"><?php echo ++$i ?></td>
                    <td><?php echo ucwords($data['surname']) ?></td>
                    <td><?php echo ucwords($data['firstname']) ?></td>
                    <td><?php echo ucwords($data['lastname']) ?></td>
                    <td><?php echo $data['username'] ?></td>
                    <td><?php echo $data['class'] ?></td>
                    <td><a href="active.php?capture=on&examinationid=<?php echo $examid?>&student=<?php echo $data['studentid'] ?>"  
                    class=" <?php echo !empty($capturestatus)?"capturingthephoto btn btn-dark btn-rounded":"btn btn-dark btn-rounded" ?>"><span class="fa fa-camera  text-light"> <?php echo $capturestatus ?></span></a> <a href="#displaycapture" data-toggle="modal" captureimage=<?php echo $captureimg ?>
                     studentname="<?php echo ucwords($data['surname'])." ". ucwords($data['firstname'])." ". ucwords($data['lastname'])  ?>" 
                     studentid="<?php echo $data['studentid']?>"
                     examid="<?php echo $examid?>" class=""><?php echo $numberOfCapture ?></a></td>
                    <td><?php echo $data['examname'] ?></td>
                    <td><img src="<?=BASE_URL?><?php echo !empty($data['avatar'])?$data['avatar']:"../../students_avatar/avatar.png" ?>" class="rounded" style="width: 60px;" alt="<?php echo $data['username'] ?>"></td>
                    <td><img src="<?= BASE_URL ?><?php echo !empty($data['photo'])?$data['photo']:"../../students_avatar/avatar.png" ?>" class="rounded" style="width: 80px;" alt="<?php echo $data['username'] ?>"></td>

                    <td>
                      <a class="btn btn-sm btn-primary" href ="active_details.php?examinationid=<?php echo $examid?>&student=<?php echo $data['studentid'] ?>" > More  <span class="fa fa-angle-double-right p-2"></span></a>
                  
                    </td>
                  </tr>
                  <?php } ?>
              </tbody>
              <tbody>
                <?php
                
                ?>
               
               

              </tbody>

            </table>
          </div>
        
          <!-- modal alert for delete and edit student -->
          
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
                    <form id="addtimeform">
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
                    </form>
                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                    <button class="btn btn-primary btn-block text-uppercase" id="addtimebtn" name="addnewclass">Add time</button>
                  </div>

                </div>
              </div>
            </div>

    <div class="modal fade" id="displaycapture">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title studentcapturedname"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <div class="row" id="capturedimages">
          </div>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>

          </section>
          <!-- view class -->
         
        </section>

      </div>
      <?php include('../footer.php'); ?>
    </div>
  </body>

  </html>
 
<?php
  include('../script.php');
} else {
  header('location:'.LOGOUT);
}
?>
 <script>
 

 $(document).ready(function () {

 // delete class confirmation
 $('#displaycapture').on('show.bs.modal', function(e) {
  var images = Object.values(JSON.parse($(e.relatedTarget).attr('captureimage')));
  var studentname = $(e.relatedTarget).attr('studentname');
  var examid = $(e.relatedTarget).attr('examid');
  var studentid = $(e.relatedTarget).attr('studentid');
  $(".studentcapturedname").text(studentname);
  $("#capturedimages").empty();
  // $("#capturedimages");
  
  for (let index = 0; index < images.length; index++) {
    $("#capturedimages").append('<div class="col-md-4 col-sm-3 card imagecontainer">'
    +'<img src="../../camera_monitor_photo/capture/'+images[index] +'" class="card-img rounded theimage"   alt="">'
    +'<div class="imageoptions row">'
    +'<a href="../../camera_monitor_photo/capture/'+images[index] +'" target="_blank" class="btn btn-success col btn-sm  p-1 m-1"><span class="fa fa-eye p-2"></span></a>'
    +'<p onclick=deleteinfo("'+studentid + '","' + examid+'","' + images[index] + '") '
    +'class="btn btn-danger col btn-sm  p-1 m-1 deleteimage"><span class="fa fa-trash p-2"></span></p>'
    +'</div>'
    +'</div>')
  }        
    })
setInterval(() => {
  $("#tables").load(" #tables");
  
}, 6000);

  })


function deleteinfo(studentid, examid, imageid) {
  $.ajax({
            type: 'post',
            url: 'deleteimage.php',
            data: 'studentid=' + studentid+"&examid="+examid+"&imageid="+imageid+"&deletecaptureimage=true",
            success: function(data) {
                if(data=="deleted"){
                  $("#tables").load(" #tables");
                  $('#displaycapture').modal("hide");

                }
            }
        })
}
  </script>