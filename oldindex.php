<?php
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
if(empty($defaultDatabase)){
	header('location:install/');
}
if(!empty($defaultDatabase)){
include('includes/connection.php');
$school = new Users;
$qu = $school->query("SELECT * FROM school_information");
$datame = $school->data($qu);
$schoolids = isset($datame['school_id'])?$datame['school_id']:"";

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
$address= (isset($myschool['address'])?$myschool['address']:"" ).", ". (isset($myschool['city'])?$myschool['city']:"")." ". $state[0]. " state Nigeria";
if(empty($myschoolid)){
	header('location:install/');
}
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
    <?php include('header.php') ?>
    <link rel="stylesheet" href="css/indexlogin.css">
	<link rel="icon" href="images/oic.png">
</head>

<body data-vide-bg="images/oicvideo">

<div>
	<div class="center-container">
		<!--header-->
		<!-- <img src="images/oic.png" style="width: 10%;" alt="OIC"> -->
		<div class="header-w3l">
			<h1><?= $myschoolname ?></h1>
			<p class="text-light"><?= $address ?></p>
		</div>
		<!--//header-->
		<!--main-->
		<div class="main-content-agile">
			<div class="wthree-pro">
                    <span class="fa fa-user text-white" style="font-size: 4em;"></span>
                <h2><?php echo isset($username)?ucwords($username):"User Login" ?></h2>
			</div>
			
                 <p class="text-danger" id="loginerror"></p>

			<div class="sub-main-w3">
				<?php if(empty($name)){
					?>
                <form id="userloginform">
					<input placeholder="Username" name="username" type="text">
					<span class="icon1"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <input type="password" name="password" placeholder="Password">
					<span class="icon2"><i class="fa fa-unlock" aria-hidden="true"></i></span>
					<div class="rem-w3">
						<!-- <span class="check-w3"><input type="checkbox" />Remember Me</span>
						<a href="#">Forgot Password?</a> -->
						<div class="clear"></div>
					</div>
                    <button class="btn btn-block btn-lg btn-primary" id="userlogin">Login</button>
				</form>

				<?php }else{
					?>
					
					<div class="text-center text-white my-4 display-4">
					<h1><?php echo ucwords($name) ?></h1>
					</div>
					<div class="row">
						<div class="col">
							<a href="logout.php?username=<?php echo $username ?>" class="btn btn-block btn-lg btn-warning text-white font-weight-bold">Logout</a>
						</div>
						<div class="col">
							<a href="<?php echo $dashboard ?>" class="btn btn-block btn-lg btn-light text-warning font-weight-bold">Dashboard</a>
						</div>
					</div>
                    <!-- <a href="logout.php" class="btn btn-block btn-lg btn-warning text-white font-weight-bold">Logout</a> -->
					

			<?php } ?>
			</div>

			<!-- <div class="text-center">
			</div> -->
		<!--//main-->
		<!--footer-->
		<div class="footer">
			<p>&copy; <?php echo date('Y') ?> Design and Developed by <a href="https://github.com/oluokunkabiru">OLUOKUN KABIRU ADESINA</a></a></p>
		</div>
		<!--//footer-->
	</div>
</div>
</body>

</html>
<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/jquery.vide.min.js"></script>
<?php include('footer.php') ?>

<script>
    //     $(document).ready(setInterval(slide, 2000));
    // function slider(){
    //   var imag = ["image/3.jpg","image/4.jpg", "image/2.jpg", "image/1.jpg",
    //   "image/5.jpg" ];
    //   var ok = document.getElementById('slide');
    //   var image = imag.sort(function(a, b){return 0.5 - Math.random()});
    //   ok.style.backgroundImage = "linear-gradient(rgba(0, 0, 0, 0.6), rgba(2, 2, 2, 0.6)), url('"+image[0]+"')";
    //   ok.style.backgroundSize = "100%";
    //   ok.style.backgroundRepeat ="no-repeat"; 
    // }
</script>

<?php } ?>