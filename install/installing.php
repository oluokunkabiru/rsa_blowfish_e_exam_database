<?php
$error = [];
$success = [];
$defaultConnection = file_get_contents('../includes/connection.php');
header('Content-Type: application/json');
// echo json_encode($_POST);

$defaultContent = explode("\n", $defaultConnection);
$defaultDatabase = trim($defaultContent[7]);
if (!empty($defaultDatabase) || !empty(trim($defaultContent[6])) || !empty(trim($defaultContent[8]))) {
    include('../includes/connection.php');
    $school = new Users;
    $qu = $school->query("SELECT * FROM school_information");
    $datame = $school->data($qu);
    $schoolids = isset($datame['school_id']) ? $datame['school_id'] : "";
    if (!empty($schoolids)) {
        $sch = $school->query("SELECT users.*, school_information.* FROM users JOIN school_information ON users.schoolid = school_information.school_id WHERE users.schoolid ='$schoolids' ");
        $myschool = $school->data($sch);
        $myschoolid = isset($myschool['schoolid']) ? $myschool['schoolid'] : "";
    }
    // print_r($myschool); OM2__])6;I%#
}
// print_r($datame);
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if (isset($_POST['hostname'])) {
    if (empty(test_input($_POST['hostname']))) {
        $error['hostname'] = "Please Provide hostname";
    }
    if (empty(test_input($_POST['username']))) {
        $error['username'] = "Please Provide username";
    }
    if (empty(test_input($_POST['database']))) {
        $error['database'] = "Please Provide database";
    }

    if (count($error) > 0){
        echo json_encode([
			'status' => 'error',
			'data' => json_encode($error),
            

		]);
    }
    if (count($error) == 0) {
        $hostname = test_input($_POST['hostname']);
        $username = test_input($_POST['username']);
        $password = test_input($_POST['password']);
        $dbname = test_input($_POST['database']);
        $q = mysqli_connect($hostname, $username, $password);
        if ($q) {
            $success['dbconnect'] = "Connect to database successfully";
            $db = "CREATE  DATABASE $dbname";
            $cr = mysqli_query($q, $db);
            if ($cr) {
                mysqli_select_db($q, $dbname);
                $nq = "";
                $sqlFile = '../includes/oluokuncbt.sql';
                $files = file($sqlFile);
                foreach ($files as $file) {
                    if (substr($file, 0, 2) == '--' || $file == '') {
                        continue;
                    }
                    $nq .= $file;
                    if (substr(trim($file), -1, 1) == ';') {
                        mysqli_query($q, $nq) or $error['databasxporterror'] = "Error in performing this query
                        <b>$nq</b> <br> Cause : " . mysqli_error($q);
                        $nq = "";
                    }
                }

                $success['dbcreate'] = "Database created successfully and export successfully<br>";
                // echo "<script>
                // setTimeout(() => {
                //     window.location.assign('../');
                    
                // },600);
                // </script>";
                echo json_encode([
                    'status' => 'success',
                    'message' =>  $success['dbcreate'], //json_encode($success),
                    'current' => 'databasetting', 
                    'next'=>'schoolinfos',
                    //$this->classlearners->get_class_learners($class)
                ]);
                $content =
                    "protected $" . "servername ='" . $hostname . "' ;\n " .
                    "protected $" . "serverusername = '" . $username . "';  \n" .
                    "protected $" . "serverpassword = '" . $password . "'; \n " .
                    "protected $" . "dbname = '" . $dbname . "'; \n";

                $toFile = file_get_contents('../includes/connection.php');
                $fopen = fopen('../includes/connection.php', 'w');
                $rows = explode("\n", $toFile);
                $con = explode("\n", $content);
                $rows[7] = $con[0];
                $rows[8] = $con[1];
                $rows[9] = $con[2];
                $rows[10] = $con[3];

                $ct = implode("\n", $rows);
                $fwrite = fwrite($fopen, $ct);
                if ($fwrite) {
                    $success['next'] = 'next';
                }
            } else {
                $error['dbcreatefail'] = "Fail to create database <b>$dbname</b><br>Cause : " . mysqli_error($q);
                echo json_encode([
                    'status' => 'error',
                    'data' => json_encode($error),
                    
        
                ]);
            }
        } else {
            $error['dbconnectfail'] = "Fail to connect to database with following details <br>Hostname = <b>
            $hostname</b> <br>User = <b>$username</b><br>Password = <b>$password</b><br>Cause of failure = <b>" . mysqli_connect_error() . "</b>";
            echo json_encode([
                'status' => 'error',
                'data' => json_encode($error),
                
    
            ]);
        }
    }
}

if (isset($_POST['schoolname'])) {
    if (empty(test_input($_POST['schoolname']))) {
        $error['schoolname'] = "Please provide school name e.g <b>koadit college of ICT</b>";
    }

    if (empty(test_input($_POST['address']))) {
        $error['address'] = "Please provide school address e.g <b>No 5, koadit street</b>";
    }
    if (empty(test_input($_POST['city']))) {
        $error['city'] = "Please provide school city e.g <b>Iseyin</b>";
    }
    if (empty(test_input($_POST['state']))) {
        $error['state'] = "Please provide school state e.g <b>State</b>";
    }

    if ($_FILES['logo']['size'] != 0) {
        $target_dir = "images/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir);
        }
        $check = getimagesize($_FILES["logo"]["tmp_name"]);
        if ($check != true) {
            $error['logo'] = "Image file is expected";
        }

        $target_file = $target_dir . basename($_FILES["logo"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($_FILES["logo"]["size"] > 5000000) {
            $error['logo'] = "Sorry, your file is too large, upload file with less than 5MB";
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $imgError[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
        $image = $target_dir . "oic.png";
        if (file_exists($image)) {
            unlink($image);
        }

        if (count($error) == 0) {
            move_uploaded_file($_FILES["logo"]["tmp_name"], $image);
        }
    } else {
        $error['logo'] = "Please choose school logo";
    }
    if (count($error) > 0){
        echo json_encode([
			'status' => 'error',
			'data' => json_encode($error),
            

		]);
    }

    if (count($error) == 0) {
        $schoolname = test_input($_POST['schoolname']);
        $logo = $image;
        $address = test_input($_POST['address']);
        $city = test_input($_POST['city']);
        $state = test_input($_POST['state']);
        $hostsystem = test_input($_POST['hostsystem']);
        // include('../includes/connection.php');
        // $school = new Users;

        $id = $school->tableid("school_information");
        $default = strrev(base64_encode(strtotime("+2 weeks")));


        $q = $school->query("INSERT INTO school_information(name,activation_key, current_term, logo, address, city, state, hostsystem, school_id)
        VALUES('$schoolname','$default','First Term', '$logo', '$address', '$city', '$state', '$hostsystem', '$id')");
        if ($q) {
            $success['schoolinfosuccess'] = "School Information setup successfully";
            echo json_encode([
                'status' => 'success',
                'message' => $success['schoolinfosuccess'], //$success['dbcreate'], //json_encode($success),
                'current' => 'schoolinfos', 
                'next'=>'admininfo',
                // 'url' => '../',
                //$this->classlearners->get_class_learners($class)
            ]);
        } else {
            $error['schoolinfofail'] = "Fail to create school information <br>Cause : " . $school->connectionError();
            if (count($error) > 0){
                echo json_encode([
                    'status' => 'error',
                    'data' => json_encode($error),
                    
        
                ]);
            }
        }
    }
}

if (isset($_POST['fname'])) {
    if (empty(test_input($_POST['fname']))) {
        $error['fname'] = "Please provide admin firstname";
    }
    if (empty(test_input($_POST['lname']))) {
        $error['lname'] = "Please provide admin lastname";
    }
    if (empty(test_input($_POST['sname']))) {
        $error['sname'] = "Please provide admin surname";
    }
    if (empty(test_input($_POST['adminusername']))) {
        $error['adminusername'] = "Please provide admin username";
    }
    if (empty(test_input($_POST['password']))) {
        $error['password'] = "Please provide admin password";
    }
    if (empty(test_input($_POST['cpassword']))) {
        $error['cpassword'] = "Please confirm admin password";
    }
    if (test_input($_POST['password']) != test_input($_POST['cpassword'])) {
        $error['cpassword'] = "Password not match";
    }
    if (count($error) > 0){
        echo json_encode([
			'status' => 'error',
			'data' => json_encode($error),
            

		]);
    }
    if (count($error) == 0) {
        $sname = test_input($_POST['sname']);
        $fname = test_input($_POST['fname']);
        $lname = test_input($_POST['lname']);
        $username = test_input($_POST['adminusername']);
        $password = md5(test_input($_POST['password']));
        $admin = new Users;
        $role = "admin";
        $id = $admin->tableid("users");
        $schoolid = $datame['school_id'];
        $qa = $admin->query("INSERT INTO users(surname, firstname, lastname, username, role, userid, password, schoolid)
        VALUES('$sname', '$fname', '$lname', '$username', '$role', '$id', '$password', '$schoolid')");
        if ($qa) {
            $success['installed'] = "Congratulation, <br>You have successfully install OLUOKUN KABIRU CBT SOFTWARE";

            // echo "<script>
            // setTimeout(() => {
            //     window.location.assign('../');
                
            // },600);
            // </script>";

            echo json_encode([
                'status' => 'success',
                'message' => $success['installed'], //$success['schoolinfosuccess'], //$success['dbcreate'], //json_encode($success),
                'current' => 'admininfo', 
                'next'=>'congratulation',
                'schoolnames'=>$sname,
                
                'url' => '../',
                //$this->classlearners->get_class_learners($class)
            ]);
        } else {
            $error['installedfail'] = "OOPS! <br> Something went wrong <br>Cause: " . $admin->connectionError();
            if (count($error) > 0){
                echo json_encode([
                    'status' => 'error',
                    'data' => json_encode($error),
                ]);
            }
        }
    }
}





?>