<?php
include_once('includes/constant.php');
session_start();
// $mac = system('arp -an');
// echo "mac = $mac";
// echo "<br>belo<br>";

// $ipconfig =   shell_exec ("ifconfig -a | grep -Po 'HWaddr \K.*$'");  
//     // display mac address   
//  echo "mac $ipconfig";

$defaultConnection = file_get_contents('includes/connection.php');
$defaultContent = explode("\n", $defaultConnection);
$defaultDatabase = trim($defaultContent[7]);
if(empty($defaultDatabase) || empty(trim($defaultContent[9])) || empty(trim($defaultContent[8]))){
	header('location:install/');
}
if(!empty($defaultDatabase)){
include('includes/connection.php');
$school = new Users;
$qu = $school->query("SELECT * FROM school_information  ORDER BY id ASC");
$datame = $school->data($qu);
$schoolids = isset($datame['school_id'])?$datame['school_id']:"";
// print_r($datame);
// exit;
if(empty($schoolids)){
	header('location:install/');
}
else{
	
	
$sch = $school->query("SELECT users.*, school_information.* FROM users JOIN school_information ON users.schoolid = school_information.school_id WHERE users.schoolid ='$schoolids' ");
$myschool = $school->data($sch);

$myschoolid = isset($myschool['schoolid'])?$myschool['schoolid']:"";
$myschoolname = isset($myschool['name'])?$myschool['name']:"";
$sta = isset($myschool['state'])?$myschool['state']:"";
$state = explode(" ", $sta);
// print_r($state);
// 	exit;
$address= (isset($myschool['address'])?$myschool['address']:"" ).", ". (isset($myschool['city'])?$myschool['city']:"")." ". $state[0]. " state Nigeria";
// if(empty($myschoolid)){
// 	header('location:install/');
// }
}
$username ="";
$name = "";
$dashboard ="";
// echo $myschoolname;
if(isset($_SESSION['adminauth'])){
	$auth = new Users;
	$login = $_SESSION['adminauth'];
    $query = $auth->query("SELECT* FROM users WHERE username ='$login' OR phone = '$login' OR email = '$login'");    
    $user = $auth->data($query);
    // $names = htmlentities(strtoupper($admins['username']));
    $sname = isset($user['surname'])?$user['surname']:"";
	$fname= isset($user['firstname'])?$user['firstname']:"";
	$lname =  isset($user['lastname'])?$user['lastname']:"";
	$name = $sname." ". $fname." ". $lname;
	$username = isset($user['username'])?$user['username']:"";	
	$dashboard = "users/admin";
}elseif(isset($_SESSION['teacherauth'])){
	$auth = new Users;
	$login = $_SESSION['teacherauth'];
    $query = $auth->query("SELECT* FROM users WHERE username ='$login' OR phone = '$login' OR email = '$login'");    
    $user = $auth->data($query);
    // $names = htmlentities(strtoupper($admins['username']));
    // $name = $user['surname']." ". $user['firstname']." ". $user['lastname'];
	$sname = isset($user['surname'])?$user['surname']:"";
	$fname= isset($user['firstname'])?$user['firstname']:"";
	$lname =  isset($user['lastname'])?$user['lastname']:"";
	$name = $sname." ". $fname." ". $lname;
	$username = isset($user['username'])?$user['username']:"";	
	$dashboard = "users/teachers";

}elseif(isset($_SESSION['studentauth'])){
	$auth = new Users;
	$login = $_SESSION['studentauth'];
    $query = $auth->query("SELECT* FROM users WHERE username ='$login' OR phone = '$login' OR email = '$login'");    
    $user = $auth->data($query);
    // $names = htmlentities(strtoupper($admins['username']));
    // $name = $user['surname']." ". $user['firstname']." ". $user['lastname'];
	$sname = isset($user['surname'])?$user['surname']:"";
	$fname= isset($user['firstname'])?$user['firstname']:"";
	$lname =  isset($user['lastname'])?$user['lastname']:"";
	$name = $sname." ". $fname." ". $lname;
	$username = isset($user['username'])?$user['username']:"";	
	$dashboard = "users/students";
}else{
	$username ="";
	$name = "";
	$dashboard ="";
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta property="og:url"                content="" />
		<meta property="og:type"               content="article" />
		<meta property="og:title"              content="Administration, Staffs, Parents, Student Portal" />
		<meta property="og:image:alt"              content="XFINDERS" />
		<meta property="og:description"        content="Users Login to perform there specify activities" />
		<meta property="og:image"              content="images/oic.png" />
		<meta property="fb:app_id"              content="421991560027667" />
        <link rel="icon" href="images/oic.png" type="image/png">

	<title><?= $myschoolname ?> | Log in</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?= BASE_URL ?>assets/plugins/fontawesome-free/css/all.min.css">
	<!-- Ionicons -->
	<!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
	<!-- icheck bootstrap -->
	<link rel="stylesheet" href="<?= BASE_URL ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?= BASE_URL ?>assets/dist/css/adminlte.min.css">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<!-- Font Awesome Icons : ver 4.7.0 -->
	<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?= BASE_URL ?>assets/loaders/pageloader-loading.css">
	<style>
		.page-loader .loader p:before {
			content: "loading";
		}
	</style>
	
</head>

<body class="hold-transition login-page"data-vide-bg="assets/images/oicvideo" id="bodybackground" style="background-image: url('assets/images/astc_dp.png'); background-repeat: no-repeat;background-position: center;background-size:auto;">
<div style="position:absolute; top:0; z-index:100000;">
</div>	

<div class="login-box">
		<div class="login-logo">
			<!--<a href="assets/index2.html"><b>Admin</b>LTE</a>-->
		</div>
		<!-- /.login-logo -->
		<div class="card">
			<div class="card-body login-card-body">
				<p class="login-box-msg">
					<img src="images/oic.png" width="110px" height="90px" />
				</p>
				<h3 class="login-box-msg text-uppercase"><?= $myschoolname ?></h3>
				<p class="text-secondary text-center"><?= $address ?></p>
                <p class="text-danger" id="loginerror"></p>

				<?php if(empty($name)){
					?>

				<form role="form" id="userloginform" method="post">
					<div class="input-group mb-3">
						<input type="text" name="username" id="email" class="form-control" placeholder="Username">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-user"></span>
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<input type="password" name="password" id="password" class="form-control" placeholder="Password">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-8">
							<div class="icheck-primary">
								<input type="checkbox" id="remember">
								<label for="remember">
									Remember Me
								</label>
							</div>
						</div>
						<!-- /.col -->
						<div class="col-4">
							<button type="submit" class="btn btn-primary btn-block" id="userlogin"><i class="nav-icon fa fa-sign-in"></i> Sign In</button>
						</div>
						<!-- /.col -->
					</div>
				</form>
				
				<?php }else{
					?>
					
					<div class="text-center text-success my-4 display-4">
					<h1><?php echo ucwords($name) ?></h1>
					</div>
					<div class="row">
						<div class="col">
							<a href="logout.php?username=<?php echo $username ?>" class="btn btn-block btn-lg btn-secondary text-white font-weight-bold">Logout</a>
						</div>
						<div class="col">
							<a href="<?php echo $dashboard ?>" class="btn btn-block btn-lg btn-success text-white font-weight-bold">Dashboard</a>
						</div>
					</div>
                    <!-- <a href="logout.php" class="btn btn-block btn-lg btn-warning text-white font-weight-bold">Logout</a> -->
					

			<?php } ?>
				<!--
		<div class="social-auth-links text-center mb-3">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
      </div>
       /.social-auth-links -->

				<!--<p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p>-->
	  <div  >
	  </div>
			</div>
			<!-- /.login-card-body -->
		</div>
	</div>
	<!-- /.login-box -->
	<!-- <div align="center"><img src=assets/images/brand.pn" /></div> -->

	<!-- jQuery -->
	<script src="<?= BASE_URL ?>assets/plugins/jquery/jquery-2.1.4.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="<?= BASE_URL ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- jquery-validation -->
	<script src="<?= BASE_URL ?>assets/plugins/jquery-validation/jquery.validate.min.js"></script>
	<script src="<?= BASE_URL ?>assets/loaders/pageloader.js"></script>

	<!-- <script src="assets/plugins/jquery-validation/additional-methods.min.js"></script> -->
	<!-- AdminLTE App -->
	<!-- <script src="assets/dist/js/adminlte.min.js"></script> -->
	<script src="<?= BASE_URL ?>assets/js/jquery.vide.min.js"></script>
	<script src="<?= BASE_URL ?>plugins/datatables/datatables.min.js"></script>
	<script src="<?= BASE_URL ?>plugins/summernote/summernote-bs4.min.js"></script>

	<script src="<?= BASE_URL ?>script.js"></script>



	<script>
		$(document).ready(function () {
			// alert("hello")
			pageLoader.hide();
		})
		// alert("hello")

// $(document).ajaxStart(function(){
//     // $("#loading").removeClass('hide');
// 	pageLoader.show();
// }).ajaxStop(function(){
//     // $("#loading").addClass('hide');
// 	pageLoader.show();
// });

// $(document).on({
//     // ajaxStart: function(){
//     //     pageLoader.show();
//     // },
//     // ajaxStop: function(){ 
//     //     pageLoader.hide();
//     // }    
// });

	</script>
</body>

</html>
<?php } ?>