<?php
// $auth->query("UPDATE users SET active_status =1 WHERE username='$username' ");
$auth->query("UPDATE users SET active_status =1 WHERE username='$username' ");

?> 
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Brand -->
  <a class="navbar-brand" href="../../../">
  <img src="../../../images/oic.png" style="width: 10%;" alt="OIC">

</a>

  <!-- Toggler/collapsibe Button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Navbar links -->
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav ml-auto mx-5">
      <li class="nav-item">
        <a class="nav-link" href="../../../">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../">Dashboard</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php">Examination</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../logout.php">Logout <i class="nav-icon fa fa-power-off text-danger"></i></a>
      </li>
    </ul>
  </div>
</nav> 