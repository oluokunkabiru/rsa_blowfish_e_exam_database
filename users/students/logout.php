<?php
include("../../includes/connection.php");
session_start();
$user = new Users;
$username = $_SESSION['studentauth'];
$user->query("UPDATE users SET active_status =0 WHERE username='$username' ");
session_unset();
session_destroy();
header('location:../../index.php');

?>
