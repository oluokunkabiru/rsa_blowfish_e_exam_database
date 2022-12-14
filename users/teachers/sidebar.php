<?php
if (isset($_SESSION['teacherauth'])) {   
    $login = $_SESSION['teacherauth'];
    $auth = new Users;
    $query = $auth->query("SELECT users.*, class.name AS classname, class.classid AS classid, teachers.teacherid AS teacherid FROM users JOIN teachers  ON users.userid=teachers.userid JOIN class ON class.classid = teachers.classid WHERE users.username ='$login' OR users.phone = '$login' OR users.email = '$login'");    
    $user = $auth->data($query);
    // $names = htmlentities(strtoupper($admins['username']));
    $name = $user['surname']." ". $user['firstname']." ". $user['lastname'];
    $username = $user['username'];
    $auth->query("UPDATE users SET active_status =1 WHERE username='$username' ");
    // school details
    $sch = $auth->query("SELECT * FROM school_information");
    $school = $auth->data($sch);
    $schoolName = $school['name'];
    $acr = explode(" ", $schoolName);
    $theme = $school['theme'];

    $acronyms = "";
    foreach($acr as $ac){
        $acronyms.=$ac[0];
    }
    $schoolLogo = $school['logo'];
?>
    
    <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?php BASE_URL ?>" class="nav-link">Home</a>
      </li>

    </ul>

    <!-- SEARCH FORM -->


   
    
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar <?= $theme ?> elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="<?=BASE_URL.$schoolLogo ?>" alt="<?php echo ucwords($schoolName) ?>" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light"><?= strtoupper($acronyms) ?> </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
       
        <div class="info">
          <a href="<?= TEACHER ?>" class="d-block text-uppercase"><?php echo ucwords($name) ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open">
            <a href="<?= TEACHER ?>" class="nav-link active">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Dashboard
              </p>
            </a>
           
            
          </li>
         
          
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Students
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= TEACHER_MANAGE_STUDENT ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Student</p>
                </a>
              </li>
             
              
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Subject
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= TEACHER_MANAGE_SUBJECT ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Managed Subject</p>
                </a>
              </li>
              
              
            </ul>
          </li>
          <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Examination
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= TEACHER_MANAGE_EXAMINATION ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Managed Examination</p>
                                </a>
                            </li>


                        </ul>
                    </li>
          <li class="nav-item has-treeview">
            <a href="<?= LOGOUT ?>" class="nav-link">
              <i class="nav-icon fa fa-power-off text-danger"></i>
              <p>
                Logout
              </p>
            </a>
           
          </li>
         
              
            </ul>
          </li>
              
            </ul>
          </li>
        
         
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside> 
<?php
}
?>