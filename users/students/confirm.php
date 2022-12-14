<?php
    include("../../includes/connection.php");
if(isset($_POST['requestexaminationpin'])){
    $id = $_POST['requestexaminationpin'];
    $subject = new Examination;
    $q = $subject->query("SELECT examinations.*, class.name AS classname, class.classid AS classid,  subjects.subjectname AS 
    subjectname, subjects.subjectid AS subjectid FROM examinations JOIN class ON class.classid = examinations.classid JOIN subjects 
    ON subjects.subjectid = examinations.subjectid WHERE examinations.examinationid= '$id'");
    $data = $subject->data($q);
    echo '<div class="modal-content">
    <div class="modal-header">
       <h5 class="modal-title">Are you sure to start <b>'. $data['subjectname'] .'</b> </h5>
       
       <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        

        <div class ="modal-body">
            <p class="error text-danger ml-4"></p>
            <div class="card-deck" id="cameraon" style="display:none;">
                <div class="card">
                    <div class="card-header"><h5 class="card-title" id="livevideotitle"></h5></div>
                    <div class="card-body">
                   <div id="cameralive" style="height: 200px; width: 200px;" class="card-img w-100">
                    </div>
                   </div>
                </div>
                <div class="card">
                    <div class="card-header"><h4 class="card-title" id="takephotodisplay"></h4></div>
                    <div class="card-body">
                    <div id="cameraphoto"></div>
                    </div>
                </div>

            </div>
       <form id="confirmexaminationpin">
        <div class="form-group">

            <label for="">Enter Examination PIN</label>
            <input type="text" class="form-control" id="pin" name="pin">
            <input type="hidden" value="'.$id.'" name="requestexampinexaminationid">
            <input type="hidden" id="takephoto" name="photo" value="">

          </div> 
           </div>
      <div class="modal-footer">
   
       <button class="btn btn-success btn-lg text-uppercase" id="btnconfirmexampin" type="submit">check</button>
       </form>
    </div>
      </div>';
}
?>

<script>
    // new examinations form
$('#btnconfirmexampin').click(function (event) {
  event.preventDefault();
  $('head').append('<style> .page-loader .loader p:before {content: "Generating";}</style>')

  $.ajax({
      type: 'POST',
      url: '../check.php',
      data: $('#confirmexaminationpin').serialize(),

      success: function (data) {
          var result = data;
          $(".error").html(result);
          if (result =="<span class='text-success'>Examination started successfully</span>") {
              // alert(result);
              window.location.assign('conductingexamination.php');    
              }
      },
      beforeSend: function() {
            pageLoader.show();
        },

        complete: function() {
            pageLoader.hide();
        }
  })
})
</script>