<?php
session_start();
include('../includes/connection.php');
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
    $errors = [];
    if(empty($_POST['username'])){
        array_push($errors, "Username field need to be filled");
    }
    if(empty($_POST['password'])){
        array_push($errors, "Password field need to be filled");
    }
    if(count($errors) > 0){
        echo "<h5 class ='text-center font-weight-bold'> OOPS! kindly rectify below error(s)</h5>";
        foreach($errors as $error){
            echo $error."<br>";
        }
    }

    if(count($errors)==0){
        $login = new Connections;
        $username = $login->test_input($_POST['username']);
        $password = md5($login->test_input($_POST['password']));
        
       $use =  $login->query("SELECT* FROM users WHERE username = '$username'  && password = '$password' ");
        $user = $login->data($use);
        // print_r($user);
       $role = isset($user['role'])?$user['role']:"";
        if(isset($user)){
        if($role == 'admin'){
            echo "admin";
            $_SESSION['adminauth'] = $user['username'];
            $_SESSION['adminlogin'] = time();

        }elseif($role=='teacher'){
            echo "teacher";

            $_SESSION['teacherauth'] = $user['username'];
            $_SESSION['login'] = time();
        }
        elseif($role=='student'){
            echo "student";
            $_SESSION['studentauth'] = $user['username'];
            $_SESSION['login'] = time();

        }else{
            echo  "Invalid user type";
        }
       }else{
           echo "Incorrect  username or password, please try again";
       }
        

    }


?>