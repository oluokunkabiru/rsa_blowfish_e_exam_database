<?php


$auth->query("UPDATE users SET active_status =1 WHERE username='$username' ");
$sch = $auth->query("SELECT * FROM school_information");
$school = $auth->data($sch);
$schoolName = $school['name'];
$acr = explode(" ", $schoolName);
$acronyms = "";
foreach($acr as $ac){
    $acronyms.=$ac[0];
}
$schoolLogo = $school['logo'];
$schoolAddress = $school['address'];
?> 
 <nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Brand -->
  <a class="navbar-brand" href="../../">
<img src="../<?php echo $schoolLogo ?>" style="width: 10%;" alt="<?=$schoolName?>">
</a>

  <!-- Toggler/collapsibe Button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Navbar links -->
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav ml-auto mx-5">
      <li class="nav-item">
        <a class="nav-link" href="../../">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php">Dashboard</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="examination/">Examination</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout <i class="nav-icon fa fa-power-off text-danger"></i></a>
      </li>
    </ul>
  </div>
</nav> 