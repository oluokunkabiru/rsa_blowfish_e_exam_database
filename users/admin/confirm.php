<?php
include('../../includes/connection.php');
// confirm delete
if(isset($_POST['deleteclass'])){
    $id = $_POST['deleteclass'];
    $class = new Classes;
    $q  = $class->query("SELECT* FROM class WHERE classid = '$id' ");
    $data = $class->data($q);
     echo '<div class="modal-content">
     <div class="modal-header">
        <h4 class="modal-title">Are you sure delete <b>'. $data['name'] .'</b> </h4>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
         </div>
       <div class="modal-footer">
    
        <form id="deleteclassconfirmform" action="delete.php" method="POST">
            <input type="hidden" value="'.$id.'" name="deleteclassconfirm">
            <button class="btn btn-danger text-uppercase btndeleteclassconfirm" type="submit">Delete</button>
        </form>
     </div>
       </div>';
}

if(isset($_POST['deleteresult'])){
  $id = $_POST['deleteresult'];
  $class = new Classes;
  $key = $_POST['key'];


  $q  = $class->query("SELECT subjects.subjectname AS subjectname, examinations.subjectid AS
  subjectid, studentstartexamination.id AS id FROM subjects JOIN examinations ON subjects.subjectid = examinations.subjectid JOIN studentstartexamination ON studentstartexamination.examinationid = examinations.examinationid WHERE studentstartexamination.id ='$id' ");
  $data = $class->data($q);
   echo '<div class="modal-content">
   <div class="modal-header">
      <h4 class="modal-title">Are you sure delete <b>'. $data['subjectname'] .'</b> </h4>
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
       </div>
     <div class="modal-footer">
  
      <form id="deleteclassconfirmform" action="../delete.php" method="POST">
      <input type="hidden" value="'.$id.'" name="deleteresultconfirm">
      <input type="hidden" value="'.$key.'" name="key">
      <button class="btn btn-danger text-uppercase btndeleteclassconfirm" type="submit">Delete</button>
      </form>
   </div>
     </div>';
}

// confirm edit class
if(isset($_POST['editclass'])){
  $id = $_POST['editclass'];
  $class = new Classes;
  $q  = $class->query("SELECT* FROM class WHERE classid = '$id' ");
  $data = $class->data($q);
   echo '<div class="modal-content">
   <div class="modal-header">
      <h4 class="modal-title">Edit Class ::  <b class ="text-uppercase">'. $data['name'] .'</b> </h4>
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
       </div>
       <div class="modal-body">
          <p class="error text-danger ml-3"></p>
          <form id="editclassform">
            <div class="form-group">
              <label for="email">Class name:</label>
              <input type="text" class="form-control" value="'.$data['name'].'" name="name">
            </div>
              <input type="hidden" value="'.$id.'" name="editclassconfirm">
</form>
        </div>

     <div class="modal-footer">
          <button class="btn btn-success text-uppercase" id="btneditclassconfirm" >update</button>
      
   </div>
  
     </div>';
}
// end of edit class confirmation
// edit result
if(isset($_POST['editresult'])){
  $id = $_POST['editresult'];
  $key = $_POST['key'];
  $class = new Classes;
  $q  = $class->query("SELECT subjects.subjectname AS subjectname, examinations.subjectid AS
  subjectid, studentstartexamination.scores AS scores, studentstartexamination.score AS score, studentstartexamination.id AS id FROM subjects JOIN examinations ON subjects.subjectid = examinations.subjectid JOIN studentstartexamination ON studentstartexamination.examinationid = examinations.examinationid WHERE studentstartexamination.id = '$id' ");
  $data = $class->data($q);
  $scores = json_decode($data['scores'], TRUE);
//  echo print_r($scores[$key]['score']);
   echo '<div class="modal-content">
   <div class="modal-header">
      <h4 class="modal-title text-uppercase">Edit result ::  <b class ="">'. $data['subjectname'] .'</b> </h4>
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
       </div>
       <div class="modal-body">
          <p class="error text-danger ml-3"></p>
          <form id="editresultform">
          <div class="form-group">
          <label for="email">Score</label>
          <input type="hidden" name="key" value="'. $key .'">
          <select name="score" class="custom-select">
          <option value="'.$scores[$key]['score'].'">'.$scores[$key]['score'].'</option>';
            $score = 1;
            while($score <=100)
            {
              echo '
          
          <option value="'.$score.'">'.$score.'</option>';
            $score++;  }
            echo'
          </select>

           </div>
              <input type="hidden" value="'.$id.'" name="editresultconfirm">
</form>
        </div>

     <div class="modal-footer">
          <button class="btn btn-success text-uppercase" id="btneditresultconfirm" >update result</button>
      
   </div>
  
     </div>';
}
// end of edit class confirmation


// confirm delete student

if(isset($_POST['deletestudent'])){
  $id = $_POST['deletestudent'];
  $student = new Users;
  $q  = $student->query("SELECT* FROM users WHERE userid = '$id' ");
  $data = $student->data($q);
  $name = ucwords($data['surname']." ". $data['firstname']." ". $data['lastname']);
  echo '<div class="modal-content">
  <div class="modal-header">
     <h4 class="modal-title">Are you sure delete <b>'. $name .'</b> </h4>
     <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    <div class="modal-footer">
 
     <form id="deleteclassconfirmform" action="delete.php" method="POST">
         <input type="hidden" value="'.$id.'" name="deletestudentconfirm">
         <button class="btn btn-danger text-uppercase btndeleteclassconfirm" type="submit">Delete</button>
     </form>
  </div>
    </div>';
  
}


// edit student
if(isset($_POST['editstudent'])){
  $id = $_POST['editstudent'];
  $student = new Users;
  $st = $student->query("SELECT users.* , class.name AS classname, class.classid AS classid FROM users
  JOIN student ON users.userid = student.userid
  JOIN class ON class.classid = student.class WHERE users.userid ='$id' ");
  $data = $student->data($st);
  $avatar = !empty($data['avatar'])?$data['avatar']:"../students_avatar/avatar.png";
  echo '
  <div class="modal-content">

  <!-- Modal Header -->
  <div class="modal-header">
    <h3 class="modal-title text-uppercase font-weight-bold">Edit student</h3>
   
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
  </div>
 <div class="text-center">
          <img src="'.$avatar.'" class="rounded-circle" style="width: 60px;" alt="'.$data['username'].'">
    </div>
  <!-- Modal body -->
<form id="editstudentconfirmform"  enctype="multipart/form-data">
  <div class="modal-body">
    <p class="error text-danger ml-3"></p>
      

      <div class="row">
        <div class="col-md-6">
            <div class="form-group">
        <label for="email">Surname:</label>
        <input type="text" class="form-control" name="sname" required value="'. $data['surname'] .'">
         </div>
        </div>
        
        <div class="col-md-6">
           <div class="form-group">
        <label for="email">Firstname:</label>
        <input type="text" class="form-control" name="fname"  value="'. $data['firstname'] .'">
         </div>
        </div>
        <div class="col-md-6">
           <div class="form-group">
        <label for="email">Middlename:</label>
        <input type="text" class="form-control" name="lname"  value="'. $data['lastname'] .'">
         </div>
        </div>
        <div class="col-md-6">
           <div class="form-group">
        <label for="email">Class:</label>
        <select name="class" class="custom-select">
        <option selected  value="'.$data['classid'].'">'.$data['classname'].'</option>';
          $clas = new Classes;
          $q = $clas->query("SELECT* FROM class");
          while($class = $clas->data($q))
          { 
        echo    '
        <option value="'.$class['classid'].'">'.$class['name'].'</option>';
          } 

   echo  '</select>

         </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="email">New password:</label>
            <input type="password" class="form-control" name="password" >
             </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="">Avatar (passport)</label>
               <input type="file" class="form-control-file border" name="avatar">
            </div>
        </div>
      </div>
     
    <input type="hidden"  name="editstudentbtnconfirm" value="'. $id .'">
  </div>
 
  <!-- Modal footer -->
        <div class="modal-footer">
          <button class="btn btn-primary btn-block text-uppercase" type="submit" id="editstudentbtconfirm">Update student</button>
        </div>
      </form>
      </div>
  ';



}
// end of edit student



// edit and delete staffs

if(isset($_POST['deletestaff'])){
  $id = $_POST['deletestaff'];
  $student = new Users;
  $q  = $student->query("SELECT* FROM users WHERE userid = '$id' ");
  $data = $student->data($q);
  $name = ucwords($data['surname']." ". $data['firstname']." ". $data['lastname']);
  echo '<div class="modal-content">
  <div class="modal-header">
     <h4 class="modal-title">Are you sure delete <b>'. $name .'</b> </h4>
     <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    <div class="modal-footer">
 
     <form id="deleteclassconfirmform" action="../delete.php" method="POST">
         <input type="hidden" value="'.$id.'" name="deletestaffconfirm">
         <button class="btn btn-danger text-uppercase btndeleteclassconfirm" type="submit">Delete</button>
     </form>
  </div>
    </div>';
  
}


// edit student
if(isset($_POST['editstaff'])){
  $id = $_POST['editstaff'];
  $student = new Users;
  $st = $student->query("SELECT users.* , class.name AS classname, class.classid AS classid FROM users
  JOIN teachers ON users.userid = teachers.userid
  JOIN class ON class.classid = teachers.classid WHERE users.userid ='$id' ");
  $data = $student->data($st);
  $avatar = !empty($data['avatar'])?$data['avatar']:"staffs_avatar/staffs_avatar.png";


  echo '
  <div class="modal-content">

  <!-- Modal Header -->
  <div class="modal-header">
    <h3 class="modal-title text-uppercase font-weight-bold">Edit Staff</h3>
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
  </div>
  <div class="text-center">
    <img src="../../'.$avatar.'" class="rounded-circle" style="width: 60px;" alt="'.$data['username'].'">
</div>
<!-- Modal body -->
<form id="editstaffconfirmform"  enctype="multipart/form-data">
<div class="modal-body">
<p class="error text-danger ml-3"></p>


<div class="row">
  <div class="col-md-6">
      <div class="form-group">
  <label for="email">Surname:</label>
  <input type="text" class="form-control" name="sname" required value="'. $data['surname'] .'">
   </div>
  </div>
  
  <div class="col-md-6">
     <div class="form-group">
  <label for="email">Firstname:</label>
  <input type="text" class="form-control" name="fname"  value="'. $data['firstname'] .'">
   </div>
  </div>
  <div class="col-md-6">
     <div class="form-group">
  <label for="email">Middlename:</label>
  <input type="text" class="form-control" name="lname"  value="'. $data['lastname'] .'">
   </div>
  </div>
  <div class="col-md-6">
     <div class="form-group">
  <label for="email">Class:</label>
  <select name="class" class="custom-select">
  <option selected  value="'.$data['classid'].'">'.$data['classname'].'</option>';
    $clas = new Classes;
    $q = $clas->query("SELECT* FROM class");
    while($class = $clas->data($q))
    { 
  echo    '
  <option value="'.$class['classid'].'">'.$class['name'].'</option>';
    } 

echo  '</select>

   </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="email">New password:</label>
      <input type="password" class="form-control" name="password" >
       </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="">Avatar (passport)</label>
         <input type="file" class="form-control-file border" name="avatar">
      </div>
  </div>
</div>

<input type="hidden"  name="editstaffbtnconfirm" value="'. $id .'">
</div>

<!-- Modal footer -->
  <div class="modal-footer">
    <button class="btn btn-primary btn-block text-uppercase" type="submit" id="editstudentbtconfirm">Update staff</button>
  </div>
</form>
</div>
  ';



}

// end edit and delete
// subject
if(isset($_POST['deletesubject'])){
  $id = $_POST['deletesubject'];
  $subject = new Subject;
  $q = $subject->query("SELECT* FROM subjects WHERE subjectid = '$id'");
  $data = $subject->data($q);
  echo '<div class="modal-content">
  <div class="modal-header">
     <h4 class="modal-title">Are you sure delete <b>'. $data['subjectname'] .'</b> </h4>
     <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    <div class="modal-footer">
 
     <form id="deleteclassconfirmform" action="delete.php" method="POST">
         <input type="hidden" value="'.$id.'" name="deletesubjectconfirm">
         <button class="btn btn-danger text-uppercase btndeletesubjectconfirm" type="submit">Delete</button>
     </form>
  </div>
    </div>';

}

if(isset($_POST['editsubject'])){
  $id = $_POST['editsubject'];
  $subject = new Subject;
  $q = $subject->query("SELECT subjects.*, class.classid as classid, class.name as classname FROM subjects 
  JOIN class ON subjects.classid = class.classid WHERE subjectid = '$id' ");
  $data = $subject->data($q);
  echo '
  <div class="modal-content">

  <!-- Modal Header -->
  <div class="modal-header">
    <h3 class="modal-title text-uppercase ">Edit Subject :: <span class="font-weight-bold ">'. $data['subjectname'] .'</span></h3>
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
  </div>

  <!-- Modal body -->
  <div class="modal-body">
    <p class="error text-danger ml-3"></p>
    <form id="editubjectform">
      <div class="form-group">
        <label for="email">Subject name:</label>
        <input type="text" class="form-control" name="name" value="'.$data['subjectname'] .'">
      </div>
      <div class="form-group">
        <label for="email">Class:</label>
        <select name="class" class="custom-select">
        <option selected value="'.$data['classid'].'">'.$data['classname'].'</option>';
      
          $clas = new Classes;
          $q = $clas->query("SELECT* FROM class");
          while($class = $clas->data($q))
          {
      echo ' <option value="'.$class['classid'].'">'.$class['name'].'</option>';
          } 
  echo '</select>

         </div>

    <input type="hidden"  name="editsubjectbtnconfirmation" value="'.$id.'">
  <!-- </div> -->

    </form>
  </div>

  <!-- Modal footer -->
  <div class="modal-footer">
    <button class="btn btn-primary btn-block text-uppercase" id="editsubjectbtnconfirm">Update</button>
  </div>

</div>
  ';
}

if(isset($_GET['classselectedforexam'])){
  $id = $_GET['classselectedforexam'];
  $subject = new Subject;
  $q = $subject->query("SELECT* FROM subjects WHERE classid = '$id' ");
  echo '<select class="custom-select" name="classsubject">
      <option value=""> Select Subject</option>';
      while($data = $subject->data($q)){
        echo '<option value="'.$data['subjectid'].'">'. $data['subjectname'].'</option>';

      }                  
 echo '</select> ' ;

}


// edit exanimation

if(isset($_POST['editexamination'])){
  $id = $_POST['editexamination'];
  $exam = new Examination;
  $q = $exam->query("SELECT examinations.*, class.name AS classname, class.classid AS classid,  subjects.subjectname AS 
  subjectname, subjects.subjectid AS subjectid FROM examinations JOIN class ON class.classid = examinations.classid JOIN subjects 
  ON subjects.subjectid = examinations.subjectid WHERE examinations.examinationid= '$id' ");
  $data = $exam->data($q);
  echo '
  <div class="modal-content">

  <!-- Modal Header -->
  <div class="modal-header">
    <h3 class="modal-title text-uppercase ">Edit Examination :: <span class="font-weight-bold ">'. $data['subjectname'] .'</span></h3>
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
  </div>

  <!-- Modal body -->
  <div class="modal-body">
  <p class="error text-danger pl-4"></p>
  <form class="form-horizontal" id="editexaminationformconfirm">
    <div class="card-body">
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">Select Class</label>
        <div class="col-sm-8">
        <select class="custom-select" onchange="selectclass(this.value)" name="examclass">
              <option selected value="'. $data['classid'] .'">'.$data['classname'].'</option>';
             $class = new Classes;
             $q = $class->query("SELECT* FROM class");
             while($dat = $class->data($q))
             {
       echo '<option value="'. $dat['classid'].'">'.$dat['name'] .'</option>';

             }

              
    echo '     </select>  
    <p class="text-danger examclass"></p>
       
          </div>
      </div>
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">Select Subject</label>
        <div class="col-sm-8">
        <select class="custom-select" id="classsubject"  name="classsubject">
             <option selected value="'.$data['subjectid'].'">'.$data['subjectname'].'</option>
              
            </select>  
            <p class="text-danger classsubject"></p>
                  
        </div>
      </div>
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">Examination Description</label>
        <div class="col-sm-8">
        <textarea class="form-control" name="examinationdescription" rows="3" placeholder="Enter examination Description">
        '. $data['description'].'</textarea>
        <p class="text-danger examinationdescription"></p>

        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-sm-4 col-form-label">Examination Name</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" name="examname" value="'.$data['examname'] .'" placeholder="Enter examination name">
          <p class="text-danger examname"></p>

          </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-sm-4 col-form-label">Examination Duration(Mins)</label>
        <div class="col-sm-8">
          <input type="number" class="form-control"  value="'.$data['duration'] .'" name="duration" placeholder="Text name">
          <p class="text-danger duration"></p>

          </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-sm-4 col-form-label">Examination From (DD/MM/YY)</label>
        <div class="col-sm-8">
          <input type="date" name="startdate"  value="'.date_format(date_create($data['startdate']), "Y-m-d") .'" class="form-control float-right" id="startdate">
          <p class="text-danger startdate"></p>

          </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-sm-4 col-form-label" >Examination To (DD/MM/YY)</label>
        <div class="col-sm-8">
          <input type="date" class="form-control" name="enddate" value="'.date_format(date_create($data['enddate']), "Y-m-d") .'" id="enddate" placeholder="Enter End date name">
          <p class="text-danger enddate"></p>

          </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-sm-4 col-form-label">Examination PIN</label>
        <div class="col-sm-8">
          <input type="number" class="form-control" name="exampasscode"  value="'.$data['examinationpin'] .'" placeholder="Text name">
          <p class="text-danger exampasscode"></p>

          </div>
      </div>
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">Exam result visibility to students </label>
        <div class="col-sm-8">
        <select class="custom-select" name="visibility">
          <option selected value="'.$data['visibility'] .'">'.ucfirst($data['visibility']).'</option>
              <option value="yes">Yes</option>
              <option value="no">No</option>
              
            </select>  
            <p class="text-danger visibility"></p>
                  
        </div>
      </div>
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">Question display </label>
        <div class="col-sm-8">
        <select class="custom-select" name="display">
          <option value="'.$data['display'] .'"> '.ucfirst($data['display']).'</option>
              <option value="random">Random</option>
              <option value="static">Static</option>
              
            </select>  
            <p class="text-danger display"></p>
                  
        </div>
      </div>
    </div>
   

  <input type="hidden"  name="editexaminationbtnconfirmation" value="'.$id.'">
  <!-- </div> -->

    </form>
  </div>

  <!-- Modal footer -->
  <div class="modal-footer">
    <button class="btn btn-primary btn-block text-uppercase" id="editexaminationbtnconfirm">Update</button>
  </div>

</div>
  ';
}


// delete examination
if(isset($_POST['deleteexamination'])){
  $id = $_POST['deleteexamination'];
  $subject = new Examination;
  $q = $subject->query("SELECT examinations.*, class.name AS classname, class.classid AS classid,  subjects.subjectname AS 
  subjectname, subjects.subjectid AS subjectid FROM examinations JOIN class ON class.classid = examinations.classid JOIN subjects 
  ON subjects.subjectid = examinations.subjectid WHERE examinations.examinationid= '$id'");
  $data = $subject->data($q);
  echo '<div class="modal-content">
  <div class="modal-header">
     <h5 class="modal-title">Are you sure delete <b>'. $data['subjectname'] .'</b> for class <b>'. ucwords($data['classname']) .'</b> </h5>
     <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    <div class="modal-footer">
 
     <form id="deleteclassconfirmform" action="../delete.php" method="POST">
         <input type="hidden" value="'.$id.'" name="deleteexaminationconfirm">
         <button class="btn btn-danger text-uppercase btndeletesubjectconfirm" type="submit">Delete</button>
     </form>
  </div>
    </div>';
}


if(isset($_POST['deletequestion'])){
  $id = $_POST['deletequestion'];
  $subject = new Examination;
  $q = $subject->query("SELECT* FROM questions WHERE questionid='$id'");
  $data = $subject->data($q);
  echo '<div class="modal-content">
  <div class="modal-header">
     <h5 class="modal-title">Are you sure delete <b>question</b> </h5>
     <div class ="modal-body">
     '. html_entity_decode($data['question']) .'
     </div>
     <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    <div class="modal-footer">
 
     <form id="deleteclassconfirmform" action="../delete.php" method="POST">
     <input type="hidden" value="'.$id.'" name="deletequestionsconfirm">
     <input type="hidden" value="'.$data['examinationid'].'" name="questionsexaminationid">
     <button class="btn btn-danger text-uppercase btndeletesubjectconfirm" type="submit">Delete</button>
     </form>
  </div>
    </div>';
}


if(isset($_POST['activateexamination'])){
  $id = $_POST['activateexamination'];
  $subject = new Examination;
  $q = $subject->query("SELECT* FROM examinations WHERE examinationid='$id'");
  $data = $subject->data($q);
  echo '<div class="modal-content">
  <div class="modal-header">
     <div class ="modal-body">
          <h5>Are you sure you want activate <b>'. ucwords($data['examname']) .'</b> </h5>
     
     </div>
     <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    <div class="modal-footer">
 
     <form id="deleteclassconfirmform" action="../delete.php" method="POST">
     <input type="hidden" value="'.$id.'" name="activateexaminationconfirm">
     <button class="btn btn-success btn-lg text-uppercase btnactivateexaminationconfirm" type="submit">Activate</button>
     </form>
  </div>
    </div>';
}

if(isset($_POST['deactivateexamination'])){
  $id = $_POST['deactivateexamination'];
  $subject = new Examination;
  $q = $subject->query("SELECT * FROM examinations WHERE examinationid='$id'");
  $data = $subject->data($q);
  echo '<div class="modal-content">
  <div class="modal-header">
     <div class ="modal-body">
          <h5>Are you sure you want deactivate <b>'. ucwords($data['examname']) .'</b> </h5>
     
     </div>
     <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    <div class="modal-footer">
 
     <form id="deleteclassconfirmform" action="../delete.php" method="POST">
     <input type="hidden" value="'.$id.'" name="deactivateexaminationconfirm">
     <button class="btn btn-warning btn-lg text-uppercase btndeactivateexaminationconfirm" type="submit">deactivate</button>
     </form>
  </div>
    </div>';
}

if(isset($_POST['stopexamination'])){
  $id = $_POST['stopexamination'];
  $subject = new Examination;
  $q = $subject->query("SELECT* FROM examinations WHERE examinationid='$id'");
  $data = $subject->data($q);
  echo '<div class="modal-content">
  <div class="modal-header">
     <div class ="modal-body">
          <h5>Are you sure you want stop <b>'. ucwords($data['examname']) .'</b> </h5>
     
     </div>
     <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    <div class="modal-footer">
 
     <form id="deleteclassconfirmform" action="../delete.php" method="POST">
     <input type="hidden" value="'.$id.'" name="stopexaminationconfirm">
     <button class="btn btn-danger btn-lg text-uppercase btnstopexaminationconfirm" type="submit">stop</button>
     </form>
  </div>
    </div>';
}
?>




<script>
  $(document).ready(function () {

    // edit confirmed
    $('#btneditclassconfirm').click(function (event) {
      event.preventDefault();
      $.ajax({
        type: 'POST',
        url: 'edit.php',
        data: $('#editclassform').serialize(),
        success: function (data) {
          var result = data;
          $(".error").html(result);
          if (result == "<span class='text-success'>Class updated successfully</span>") {
            // alert(result);
            window.location.assign('manageclass.php');
          }
        }
      })
    })
    // edit class 
    $('#btneditresultconfirm').click(function (event) {
      event.preventDefault();
      $.ajax({
        type: 'POST',
        url: '../edit.php',
        data: $('#editresultform').serialize(),
        success: function (data) {
          var result = data;
          $(".error").html(result);
          if (result == "<span class='text-success'>Result updated successfully</span>") {
            // alert(result);
            window.location.reload();
          }
        }
      })
    })

    // edit student confirmed
    $('#editexaminationbtnconfirm').click(function (event) {
      event.preventDefault();
      $.ajax({
        type: 'POST',
        url: '../edit.php',
        data: $('#editexaminationformconfirm').serialize(),
        dataType: "JSON",

        success: function (data) {
          // var result = data;
          // $(".error").html(result);
          // if (result == "<span class='text-success'>Examination updated successfully</span>") {
          //   // alert(result);
          //   window.location.assign('manageexamination.php');
          // }

          if(data.status=="error"){
                    // var  errors ="";
                    $.each(JSON.parse(data.data), function(key, value) {
                        $('.' +key).html(value);                    
                    })
                }

                if(data.status=="success"){
                 
                // $(".error").html(result);
                // if (result == "<span class='text-success'>Examination added successfully</span>") {
                //     // alert(result);
                    window.location.assign('manageexamination.php');
                }
        }
      })
    })

    // edit examination
    // edit student confirmed
    $('#editstudentconfirmform').submit(function (e) {
      e.preventDefault();
      var datas = new FormData(this);
      $.ajax({
        type: 'POST',
        url: 'edit.php',
        data: datas,
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
          var result = data;
          $(".error").html(result);
          if (result == "<span class='text-success'>Student updated successfully</span>") {
            // alert(result);
            window.location.assign('managestudent.php');
          }
        }
      })
    })

    // edit staff confirmed
    $('#editstaffconfirmform').submit(function (e) {
      e.preventDefault();
      var datas = new FormData(this);
      $.ajax({
        type: 'POST',
        url: '../edit.php',
        data: datas,
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
          var result = data;
          $(".error").html(result);
          if (result == "<span class='text-success'>Staff updated successfully</span>") {
            // alert(result);
            window.location.assign('index.php');
          }
        }
      })
    })
    // edit class 
    // edit student confirmed
    $('#editsubjectbtnconfirm').click(function (event) {
      event.preventDefault();
      $.ajax({
        type: 'POST',
        url: 'edit.php',
        data: $('#editubjectform').serialize(),
        success: function (data) {
          var result = data;
          $(".error").html(result);
          if (result == "<span class='text-success'>Subject updated successfully</span>") {
            // alert(result);
            window.location.assign('managesubject.php');
          }
        }
      })
    })
    // edit class 

  })
  function selectclass(str) {
    // var classe = "";
    // alert(str);
    if (str.length == 0) {
      document.getElementById("classsubject").innerHTML = "";
      return;
    } else {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          var result = document.getElementById("classsubject")
          var ret = this.responseText
          result.innerHTML = ret;
          // classe = str;
        }
      };
      xmlhttp.open("GET", "../confirm.php?classselectedforexam=" + str, true);
      xmlhttp.send();
    }
  }
</script>