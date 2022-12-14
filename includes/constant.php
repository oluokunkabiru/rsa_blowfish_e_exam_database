<?php
define("CBT_FOLDER", "RSA_BLOWFISH_E_EXAM");
$url      = "http://" . $_SERVER['HTTP_HOST']."/".CBT_FOLDER."/" ;
$validURL = str_replace("&", "&amp", $url);
define("BASE_URL", $validURL);
define("LOADER_INFO", 'Wait');



// urls
// admin URL
define('ADMIN', BASE_URL.'users/admin/');
define('MANAGE_STAFF', ADMIN."staffs");
define('MANAGE_STUDENT', ADMIN."managestudent.php");
define('STUDENT_RESULT', ADMIN."students");
define('PAST_QUESTION', ADMIN."past-questions");
define('MANAGE_CLASS', ADMIN."manageclass.php");
define('MANAGE_SUBJECT', ADMIN."managesubject.php");
define('MANAGE_EXAMINATION', ADMIN."examination/manageexamination.php");
define('ACTIVE_LOGIN_USER', ADMIN.'active.php');
define('LOGOUT', ADMIN.'logout.php');



// teacher URL

define('TEACHER', BASE_URL.'users/teachers/');
define('TEACHER_MANAGE_STUDENT', TEACHER."managestudent.php");
define('TEACHER_STUDENT_RESULT', TEACHER."students");
define('TEACHER_PAST_QUESTION', TEACHER."past-questions");
define('TEACHER_MANAGE_CLASS', TEACHER."manageclass.php");
define('TEACHER_MANAGE_SUBJECT', TEACHER."managesubject.php");
define('TEACHER_MANAGE_EXAMINATION', TEACHER."examination/manageexamination.php");






?>