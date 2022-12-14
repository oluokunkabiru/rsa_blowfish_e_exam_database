<?php
if (isset($_SESSION['adminauth'])) {
    require_once("../../../includes/connection.php");
   
    $login = $_SESSION['adminauth'];
    $auth = new Users;
    $query = $auth->query("SELECT* FROM users WHERE username ='$login' OR phone = '$login' OR email = '$login'");    
    $user = $auth->data($query);
    // $names = htmlentities(strtoupper($admins['username']));
    $name = $user['surname']." ". $user['firstname']." ". $user['lastname'];
    $username = $user['username'];
    $auth->query("UPDATE users SET active_status =1 WHERE username='$username' ");
    $act = $auth->data($auth->query("SELECT COUNT(id) AS activenow FROM users WHERE active_status='1'"));
    $activenow = $act['activenow'];
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
        <a href="../../../" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

   
    
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar <?= $theme ?> elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="../../<?=$schoolLogo ?>" alt="<?php echo ucwords($schoolName) ?>" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light"><?= strtoupper($acronyms) ?> </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        
        <div class="info">
          <a href="../index.php" class="d-block text-uppercase"><?php echo $name ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open">
            <a href="../" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
           
            
          </li>
         
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Staff
                <i class="fas fa-angle-left right"></i>
                <!-- <span class="badge badge-info right">6</span> -->
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="index.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Staff</p>
                </a>
              </li>
              
              
             
              
              
            </ul>
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
                <a href="../managestudent.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Student</p>
                </a>
              </li>
             
              
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tree"></i>
              <p>
                Class
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../manageclass.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Managed Class</p>
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
                <a href="../managesubject.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Managed Subject</p>
                </a>
              </li>
              
              
            </ul>
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
                                <a href="../examination/manageexamination.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Managed Examination</p>
                                </a>
                            </li>


                        </ul>
                    </li>
          </li>
          <li class="nav-item has-treeview">
            <a href="../logout.php" class="nav-link">
              <i class="nav-icon fa fa-power-off text-danger"></i>
              <p>
                Logout
              </p>
            </a>
           
          </li>
          <li class="nav-item has-treeview">
            <a href="../active.php" class="nav-link">
              <i class="nav-icon fa fa-toggle-on text-success"></i>
              <p>
                Active
              </p>
              <span class="badge badge-info right"><?php echo $activenow ?></span>
            </a>
           
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