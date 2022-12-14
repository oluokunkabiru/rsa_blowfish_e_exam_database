<?php
include("../../includes/connection.php");
session_start();
$user = new Users;
$username = $_GET['user'];
$user->query("UPDATE users SET active_status =0 WHERE userid='$username' ");

header('location:active.php');

?>
