<?php
include("../../../includes/connection.php");

$studentid = $_GET['student'];
$student = new Users;
if(!empty($studentid)){
$q = $student->query("SELECT users.*, student.*,  class.name AS classname FROM users JOIN student ON users.userid = student.userid JOIN class ON student.class = class.classid WHERE users.surname LIKE '%$studentid%' OR users.username LIKE '%$studentid%' OR users.firstname LIKE '%$studentid%' OR users.lastname LIKE '%$studentid%' OR class.name LIKE '%$studentid'");
$data = $student->data($q);
$username = isset($data['username'])?$data['username']:"";
if(!empty($username)){
?>
<table id="tables" class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Surname</th>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Username</th>
        <th>Current Class</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        <?php
      $i = 1;
      while ($data = $student->data($q)) {
          ?>

        <tr>
          <td scope="row"><?php echo $i++ ?></td>
          <td><?php echo ucwords($data['surname'])  ?></td>
          <td><?php echo ucwords($data['firstname'])  ?></td>
          <td><?php echo ucwords($data['lastname'])  ?></td>
          <td><?php echo ucwords($data['username'])  ?></td>
          <td><?php echo ucwords($data['classname'])  ?></td>
          <td> <a href="checkresult.php?student=<?php echo $data['userid']  ?>" class="btn btn-success text-uppercase">Check result</a>   </td>
        </tr>
      <?php
      }
      ?>

    </tbody>

  </table>
  <?php }else{
     
      
      ?>
<h3 class="text-danger text-center"> No user with <strong> <?php echo $studentid ?> </strong> details</h3>
      <?php
  }

} ?>
  