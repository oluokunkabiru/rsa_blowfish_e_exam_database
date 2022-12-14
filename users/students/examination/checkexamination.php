<?php
session_start();
 if($_SESSION['studentauth'] )
 {
     
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHECK AVAILABLE EXAMINATION</title>
    <?php include('header.php') ?>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
        <?php include('sidebar.php')?>
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <h1>Check Examination</h1>
                </div>
            </section>
        </div>
<?php include('footer.php'); ?>

</div>
</body>
</html>
<?php
include('script.php');
 }else{
     header('location:../../');
 }
?>