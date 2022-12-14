<?php
session_start();
if ($_SESSION['adminauth']) {
    //  include('../../includes/connection.php');
    include('../../../includes/constant.php');




?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SCHOOLS INFORMATION AND ADMIN PROFILE UPDATE</title>
        <?php include('../header.php') ?>

    </head>

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <!-- <img src="../../../images/" alt=""> -->
            <?php include('sidebar.php');
            $welcome = "PGgyIGNsYXNzPSd0ZXh0LWNlbnRlciB0ZXh0LWRhbmdlcic+WW91IGhhdmUgRmluaXNoIHRoZSBmcmVlIGRlbW8gPGJyPiBLaW5kbHksIGNvbnRhY3QgYWRtaW4gb24gKzIzNDgzMDU4NDU1MCA8L2gyPg==";
            $error = [];
            $success = [];
            function test_input($data)
            {
                $data = trim($data);
                $data = stripslashes($data);
                $data = str_replace("'", "&apos;", $data);
                $data = htmlspecialchars($data);
                return $data;
            }
            if (isset($_POST['updateadmin'])) {
                if (empty(test_input($_POST['fname']))) {
                    $error['ufname'] = "Please provide admin firstname";
                }
                if (empty(test_input($_POST['lname']))) {
                    $error['ulname'] = "Please provide admin lastname";
                }
                if (empty(test_input($_POST['sname']))) {
                    $error['usname'] = "Please provide admin surname";
                }
                // echo md5("admin")==$user['password']?"<br>password matched <br>":"<br>Not matched".htmlspecialchars($user['password'])."<br>";

                if (!empty($_POST['newpassword'])) {
                    if (empty(test_input($_POST['oldpassword']))) {
                        $error['oldpassword'] = "Please provide your old password";
                    } elseif (md5($_POST['oldpassword']) != $user['password']) {
                        $error['oldpassword'] = "Your old password not matched ";
                    } else {
                        if (empty(test_input($_POST['newpassword']))) {
                            $error['unewpassword'] = "Please provide new password";
                        } elseif (strlen(test_input($_POST['newpassword'])) < 5) {
                            $error['unewpassword'] = "Your password must atleast 5 characters";
                        } elseif (empty(test_input($_POST['cpassword']))) {
                            $error['ucpassword'] = "Please confirm your password";
                        } elseif (test_input($_POST['newpassword']) != test_input($_POST['cpassword'])) {
                            $error['ucpassword'] = "Password not match";
                        } else {
                            $password = md5($_POST['newpassword']);
                        }
                    }
                } else {
                    $password = $user['password'];
                }
                // echo "akhdadhaha afhakhfashf afhash afha a ";

                if (count($error) == 0) {
                    $admins = new Users;
                    $sname =  $admins->test_input($_POST['sname']);
                    $fname =  $admins->test_input($_POST['fname']);
                    $lname =  $admins->test_input($_POST['lname']);
                    $q =  $admins->query("UPDATE users SET surname ='$sname', password='$password', firstname= '$fname', lastname = '$lname' WHERE userid = '$userid' ");
                    if ($q) {
                        $success['adminupdte'] = "Admin profile updated successfully";
                    } else {
                        $error['adminprofilefail'] = "Profile fail to upate";
                    }
                }
            }
            if (isset($_POST['adminconfigbtn'])) {
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

                if (count($error) == 0) {
                    $sname = test_input($_POST['sname']);
                    $fname = test_input($_POST['fname']);
                    $lname = test_input($_POST['lname']);
                    $username = test_input($_POST['adminusername']);

                    $password = md5(test_input($_POST['password']));
                    $admin = new Users;
                    $role = "admin";
                    if ($admin->checkExist("users", "username", $username) != "") {
                        $username = $admin->nextUsername("users", "username", $username);
                    }
                    $id = $admin->tableid("users");
                    $schoolid = $schoolId;
                    $qa = $admin->query("INSERT INTO users(surname, firstname, lastname, username, role, userid, password, schoolid)
                    VALUES('$sname', '$fname', '$lname', '$username', '$role', '$id', '$password', '$schoolid')");
                    if ($qa) {
                        $success['installed'] = "Congratulation, <br>You have successfully added $sname $fname $lname as an <b>admin</b>";
                    } else {
                        $error['installedfail'] = "OOPS! <br> Something went wrong <br>Cause: " . $admin->connectionError();
                    }
                }
            }


            if (isset($_POST['schoolconfigbtn'])) {
                if (empty(test_input($_POST['schoolname']))) {
                    $error['schoolname'] = "Please provide school name e.g <b>koadit college of ICT</b>";
                }
                if (empty(test_input($_POST['currentterm']))) {
                    $error['currentterm'] = "Please select  current term e.g <b>First term</b>";
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

                // if (empty(test_input($_POST['key']))) {
                //     $error['state'] = "Please provide school state e.g <b>State</b>";
                // }

                if ($_FILES['logo']['size'] != 0) {
                    $target_dir = BASE_URL."images/";
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
                        $image = "images/oic.png";
                    }
                } else {
                    $image = $schoolLogo;
                }


                if (count($error) == 0) {
                    $schoolname = test_input($_POST['schoolname']);
                    $logo = $image;
                    $address = test_input($_POST['address']);
                    $city = test_input($_POST['city']);
                    $state = test_input($_POST['state']);
                    

                  
                    $key = test_input($_POST['key']);
                    $theme = test_input($_POST['theme']);
                    $currentterm = test_input($_POST['currentterm']);
                    // $hostsystem = test_input($_POST['hostsystem']);
                    // include('../includes/connection.php');
                    $school = new Users;
                    // $id = $school->tableid("school_information");
                    $q = $school->query("UPDATE school_information SET name='$schoolname', activation_key= '$key', theme = '$theme',  current_term='$currentterm', logo='$logo', address='$address', city='$city', state='$state' WHERE id='$schoolId'");
                    if ($q) {
                        $success['schoolinfosuccess'] = "School Information updated successfully";
                    } else {
                        $error['schoolinfofail'] = "Fail to update school information <br>Cause : " . $school->connectionError();
                    }
                }
            }

            ?>

            <div class="content-wrapper">
                <section class="content">
                    <div class="container-fluid">
                        <h2 class="text-center text-uppercase font-weight-bold mt-2 mb-2">SCHOOL INFORMATION UPDATE</h2>
                        <?= ""//date("d-m-Y:H:s:i", base64_decode(strrev($school['activation_key']))) ?>

                        <p><?= ""//base64_decode("MTY4NTMyNjkxNA==") ?></p>
                        <h5 class="text-danger"><?= isset($error['dbconnectfail']) ? $error['dbconnectfail'] : "" ?></h5>
                        <h5 class="text-danger"><?= isset($error['dbcreatefail']) ? $error['dbcreatefail'] : "" ?></h5>
                        <h5 class="text-danger"><?= isset($error['schoolinfofail']) ? $error['schoolinfofail'] : "" ?></h5>
                        <h5 class="text-danger"><?= isset($error['installedfail']) ? $error['installedfail'] : "" ?></h5>
                        <h5 class="text-danger"><?= isset($error['adminprofilefail']) ? $error['adminprofilefail'] : "" ?></h5>
                        <h5 class="text-success"><?= isset($success['dbconnect']) ? $success['dbconnect'] : "" ?></h5>
                        <h5 class="text-success"><?= isset($success['dbcreate']) ? $success['dbcreate'] : "" ?></h5>
                        <h5 class="text-success"><?= isset($success['schoolinfosuccess']) ? $success['schoolinfosuccess'] : "" ?></h5>
                        <h5 class="text-success"><?= isset($success['installed']) ? $success['installed'] : "" ?></h5>
                        <h5 class="text-success"><?= isset($success['adminupdte']) ? $success['adminupdte'] : "" ?></h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title font-weight-bold text-center text-uppercase">Admin Profile</h3>
                                    </div>
                                    <div class="card-body">

                                        <form action="index.php" method="POST">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="email">Surname:</label>
                                                        <input type="text" value="<?= $user['surname'] ?>" class="form-control" name="sname">
                                                        <p class="text-danger"><?= isset($error['usname']) ? $error['usname'] : "" ?></p>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="email">Firstname:</label>
                                                        <input type="text" value="<?= $user['firstname'] ?>" class="form-control" name="fname">
                                                        <p class="text-danger"><?= isset($error['ufname']) ? $error['ufname'] : "" ?></p>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="email">Middlename:</label>
                                                        <input type="text" value="<?= $user['lastname'] ?>" class="form-control" name="lname">
                                                        <p class="text-danger"><?= isset($error['ulname']) ? $error['ulname'] : "" ?></p>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="email">Old Password:</label>
                                                        <input type="password" class="form-control" name="oldpassword">
                                                        <p class="text-danger"><?= isset($error['oldpassword']) ? $error['oldpassword'] : "" ?></p>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="email">New Password:</label>
                                                        <input type="password" class="form-control" name="newpassword">
                                                        <p class="text-danger"><?= isset($error['unewpassword']) ? $error['unewpassword'] : "" ?></p>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="email">Confirm Password:</label>
                                                        <input type="password" class="form-control" name="cpassword">
                                                        <p class="text-danger"><?= isset($error['ucpassword']) ? $error['ucpassword'] : "" ?></p>

                                                    </div>
                                                </div>
                                            </div>
                                            <button name="updateadmin" class="btn btn-success text-uppercase" type="submit">update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">SCHOOL INFO</h3>
                                    </div>
                                    <div class="card-body">
                                        <form id="dbconfig" action="index.php" method="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="text">School name :</label>
                                                        <input type="text" value="<?= $schoolName ?>" class="form-control <?= isset($error['schoolname']) ? " border border-danger" : "" ?>" name="schoolname" placeholder="Please provide school name e.g koadit college of ICT">
                                                        <p class="text-danger"><?= isset($error['schoolname']) ? $error['schoolname'] : "" ?></p>

                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="text">Logo:</label>
                                                        <input type="file" class="form-control-file border <?= isset($error['logo']) ? " border border-danger" : "" ?>" name="logo">
                                                        <p class="text-danger"><?= isset($error['logo']) ? $error['logo'] : "" ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">

                                                    <div class="form-group">
                                                        <label for="text">State:</label>
                                                        <input type="text" value="<?= $schoolState ?>" class="form-control <?= isset($error['state']) ? " border border-danger" : "" ?>" name="state" placeholder="Please provide state e.g Oyo state">
                                                        <p class="text-danger"><?= isset($error['state']) ? $error['state'] : "" ?></p>

                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="text">City:</label>
                                                        <input type="text" value="<?= $schoolCity ?>" class="form-control <?= isset($error['city']) ? " border border-danger" : "" ?>" name="city" placeholder="Please provide school city e.g Iseyin">
                                                        <p class="text-danger"><?= isset($error['city']) ? $error['city'] : "" ?></p>

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="sel1">Theme:</label>
                                                        <select class="form-control" name="theme">
                                                        <option value="sidebar-dark-success" <?= $theme=="sidebar-dark-success"?"selected":"" ?> >Dark - Green  </option>
                                                        <option value="sidebar-dark-primary" <?= $theme=="sidebar-dark-primary"?"selected":"" ?> >Dark - Blue  </option>
                                                        <option value="sidebar-dark-light" <?= $theme=="sidebar-dark-light"?"selected":"" ?> >Dark - White  </option>
                                                        <option value="sidebar-dark-warning" <?= $theme=="sidebar-dark-warning"?"selected":"" ?> >Dark - Yellow  </option>
                                                        <option value="sidebar-dark-danger" <?= $theme=="sidebar-dark-danger"?"selected":"" ?> >Dark - Red  </option>
   

                                                        <option value="sidebar-light-success" <?= $theme=="sidebar-light-success"?"selected":"" ?> >White - Green  </option>
                                                        <option value="sidebar-light-primary" <?= $theme=="sidebar-light-primary"?"selected":"" ?> >White - Blue  </option>
                                                        <option value="sidebar-light-light" <?= $theme=="sidebar-light-light"?"selected":"" ?> >White - White  </option>
                                                        <option value="sidebar-light-warning" <?= $theme=="sidebar-light-warning"?"selected":"" ?> >White - Yellow  </option>
                                                        <option value="sidebar-light-danger" <?= $theme=="sidebar-light-danger"?"selected":"" ?> >White - Red  </option>
   



                                                        
                                                                                                                </select>
                                                        <p class="text-danger"><?= isset($error['currentterm']) ? $error['currentterm'] : "" ?></p>

                                                    </div>

                                                </div>

                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="sel1">Current Term:</label>
                                                        <select class="form-control" name="currentterm">
                                                            <option value="First term">First term</option>
                                                            <option value="Second term">Second term</option>
                                                            <option value="Third term">Third term</option>
                                                        </select>
                                                        <p class="text-danger"><?= isset($error['currentterm']) ? $error['currentterm'] : "" ?></p>

                                                    </div>

                                                </div>
                                                <div class="col-md-7">

                                                    <div class="form-group">
                                                        <label for="text">Activation Key  <small class="text-danger"> <?= date('d-m-Y H:s:ia',  (float) base64_decode(strrev($activationKey))) ?> </small> :</label>
                                                        <input type="text" value="<?= $activationKey ?>" class="form-control <?= isset($error['state']) ? " border border-danger" : "" ?>" name="key" placeholder="Activation key">
                                                        <p class="text-danger"><?= isset($error['key']) ? $error['key'] : "" ?></p>

                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                
                                                </div>


                                                
                                                <div class="col-md-12">

                                                    <div class="form-group">
                                                        <label for="text">Address:</label>
                                                        <textarea class="form-control <?= isset($error['address']) ? " border border-danger" : "" ?>" name="address" placeholder="Please provide databse name e.g No 5 koadit_ict street">
                    <?= $schoolAddress ?>
                    </textarea>
                                                        <p class="text-danger"><?= isset($error['address']) ? $error['address'] : "" ?></p>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="center">
                                                <button type="submit" name="schoolconfigbtn" class="btn btn-primary text-uppercase">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="card">

                                <!-- Modal Header -->
                                <div class="card-header">
                                    <h3 class="card-title text-uppercase font-weight-bold">Add admin</h3>
                                </div>

                                <!-- Modal body -->
                                <form action="" method="post">
                                    <div class="card-body">
                                        <div class="row">


                                            <div class="form-group col-md-6">
                                                <label for="text">Admin surname:</label>
                                                <input type="text" class="form-control <?= isset($error['sname']) ? " border border-danger" : "" ?>" name="sname" placeholder="Please provide surname e.g Oluokun">
                                                <p class="text-danger"><?= isset($error['sname']) ? $error['sname'] : "" ?></p>

                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="text">Admin firstname:</label>
                                                <input type="text" class="form-control <?= isset($error['fname']) ? " border border-danger" : "" ?>" name="fname" placeholder="Please provide firstname e.g Kabiru">
                                                <p class="text-danger"><?= isset($error['fname']) ? $error['fname'] : "" ?></p>

                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="text">Admin Middlename:</label>
                                                <input type="text" class="form-control <?= isset($error['lname']) ? " border border-danger" : "" ?>" name="lname" placeholder="Please provide lastname e.g Adesina">
                                                <p class="text-danger"><?= isset($error['lname']) ? $error['lname'] : "" ?></p>

                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="text">Username:</label>
                                                <input type="text" class="form-control <?= isset($error['adminusername']) ? " border border-danger" : "" ?>" name="adminusername" placeholder="Please provide username e.g ICT_PC">
                                                <p class="text-danger"><?= isset($error['adminusername']) ? $error['adminusername'] : "" ?></p>

                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="text">Password:</label>
                                                <input type="password" class="form-control <?= isset($error['password']) ? " border border-danger" : "" ?>" name="password" placeholder="Please provide choose password">
                                                <p class="text-danger"><?= isset($error['password']) ? $error['password'] : "" ?></p>

                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="text">Confirm password:</label>
                                                <input type="password" class="form-control <?= isset($error['cpassword']) ? " border border-danger" : "" ?>" name="cpassword" placeholder="Please provide confirmed your password">
                                                <p class="text-danger"><?= isset($error['cpassword']) ? $error['cpassword'] : "" ?></p>

                                            </div>
                                        </div>

                                    </div>

                                    <!-- Modal footer -->
                                    <div class="card-footer">
                                        <button type="submit" name="adminconfigbtn" class="btn btn-info text-uppercase">add new admin</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </section>

            </div>
            <?php include('../footer.php'); ?>
        </div>
    </body>

    </html>
   

    
<?php
    include('../script.php');
} else {
    header('location:'.BASE_URL);
}
?>