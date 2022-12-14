<?
include('../includes/constant.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBT INSTALLATION CONFIGURATION</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>bootsrap/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/loaders/pageloader-pacman.css">
    <style>
        .page-loader .loader p:before {
            content: "Installing";
        }
    </style>
</head>

<body>
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center card-title font-weight-bold py-4 text-uppercase">cbt installation configuration</h3>
                        </div>
                        <div class="card-body">
                        <h5 class="text-success" id="successmesssage"></h5>
                        <h5 class="text-success dbcreatefail dbconnectfail schoolinfofail" ></h5>




                            <!-- Database setting -->
                            <div class="databasetting d-block">
                                <div class="text-center">
                                    <span class="fa fa-database display-4 bg-dark text-white p-4 rounded-circle"></span>
                                </div>
                                <h4 class="text-center font-weight-bold my-3">Database setup</h4>
                                <form id="dbconfig" method="POST">
                                    <div class="form-group">
                                        <label for="text">Host name:</label>
                                        <input type="text" class="form-control <?= isset($error['hostname']) ? " border border-danger" : "" ?>" value="localhost" name="hostname" placeholder="Please provide host name e.g localhost">
                                        <p class="text-danger hostname"></p>

                                    </div>
                                    <div class="form-group">
                                        <label for="text">Host username:</label>
                                        <input type="text" class="form-control<?= isset($error['username']) ? " border border-danger" : "" ?>" value="root" name="username" placeholder="Please provide host username e.g root">
                                        <p class="text-danger username"></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="text">Host password:</label>
                                        <input type="password" class="form-control <?= isset($error['password']) ? " border border-danger" : "" ?>" name="password" placeholder="Please provide password e.g village@2020">
                                        <p class="text-danger password"></p>

                                    </div>
                                    <div class="form-group">
                                        <label for="text">Database name:</label>
                                        <input type="text" class="form-control <?= isset($error['database']) ? " border border-danger" : "" ?>" value="oluokuncbt" name="database" placeholder="Please provide databse name e.g koadit_ict">
                                        <p class="text-danger database"></p>

                                    </div>

                                    <button type="submit" name="dbconfigbtn" class="btn float-right btn-lg btn-primary">Next</button>
                                </form>
                            </div>







                            <!-- School information setting -->
                            <div class="schoolinfos d-none">


                                <div class="text-center">
                                    <span class="fa fa-school display-4 bg-dark text-white p-4 rounded-circle"></span>
                                </div>
                                <h4 class="text-center font-weight-bold my-3">School information setup</h4>
                                <form id="dbconfig" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="text">School name:</label>
                                        <input type="text" class="form-control <?= isset($error['schoolname']) ? " border border-danger" : "" ?>" name="schoolname" placeholder="Please provide school name e.g koadit college of ICT">
                                        <p class="text-danger schoolname"></p>

                                    </div>
                                    <div class="form-group">
                                        <label for="text">Logo:</label>
                                        <input type="file" class="form-control-file border <?= isset($error['logo']) ? " border border-danger" : "" ?>" name="logo">
                                        <p class="text-danger logo"><?= isset($error['logo']) ? $error['logo'] : "" ?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="text">Host System:</label>
                                        <input type="text" class="form-control <?= isset($error['hostsystem']) ? " border border-danger" : "" ?>" name="hostsystem" placeholder="Please provide host system e.g ICT_PC">
                                        <p class="text-danger"><?= isset($error['hostsystem']) ? $error['hostsystem'] : "" ?></p>

                                    </div>
                                    <div class="form-group">
                                        <label for="text">Address:</label>
                                        <textarea class="form-control <?= isset($error['address']) ? " border border-danger" : "" ?>" name="address" placeholder="Please provide databse name e.g No 5 koadit_ict street">

                    </textarea>
                                        <p class="text-danger address"></p>

                                    </div>
                                    <div class="form-group">
                                        <label for="text">City:</label>
                                        <input type="text" class="form-control <?= isset($error['city']) ? " border border-danger" : "" ?>" name="city" placeholder="Please provide school city e.g Iseyin">
                                        <p class="text-danger city"><?= isset($error['city']) ? $error['city'] : "" ?></p>

                                    </div>
                                    <div class="form-group">
                                        <label for="text">State:</label>
                                        <input type="text" class="form-control <?= isset($error['state']) ? " border border-danger" : "" ?>" name="state" placeholder="Please provide state e.g Oyo state">
                                        <p class="text-danger state"><?= isset($error['state']) ? $error['state'] : "" ?></p>

                                    </div>

                                    <button type="submit" name="schoolconfigbtn" class="btn float-right btn-lg btn-primary">Next</button>
                                </form>

                            </div>




                            <!-- School Information -->
                            <div class="admininfo d-none">
                                <div class="text-center">
                                    <span class="fa fa-key display-4 bg-dark text-white p-4 rounded-circle"></span>
                                </div>
                                <h3 class="text-center font-weight-bold my-3">Add admin</h3>

                                <form id="dbconfig" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="text">Admin surname:</label>
                                        <input type="text" class="form-control <?= isset($error['sname']) ? " border border-danger" : "" ?>" name="sname" placeholder="Please provide surname e.g Oluokun">
                                        <p class="text-danger sname"><?= isset($error['sname']) ? $error['sname'] : "" ?></p>

                                    </div>
                                    <div class="form-group">
                                        <label for="text">Admin firstname:</label>
                                        <input type="text" class="form-control <?= isset($error['fname']) ? " border border-danger" : "" ?>" name="fname" placeholder="Please provide firstname e.g Kabiru">
                                        <p class="text-danger fname"><?= isset($error['fname']) ? $error['fname'] : "" ?></p>

                                    </div>
                                    <div class="form-group">
                                        <label for="text">Admin lastname:</label>
                                        <input type="text" class="form-control <?= isset($error['lname']) ? " border border-danger" : "" ?>" name="lname" placeholder="Please provide lastname e.g Adesina">
                                        <p class="text-danger lname"><?= isset($error['lname']) ? $error['lname'] : "" ?></p>

                                    </div>
                                    <div class="form-group">
                                        <label for="text">Username:</label>
                                        <input type="text" class="form-control <?= isset($error['adminusername']) ? " border border-danger" : "" ?>" name="adminusername" placeholder="Please provide username e.g ICT_PC">
                                        <p class="text-danger adminusername"><?= isset($error['adminusername']) ? $error['adminusername'] : "" ?></p>

                                    </div>
                                    <div class="form-group">
                                        <label for="text">Password:</label>
                                        <input type="password" class="form-control <?= isset($error['password']) ? " border border-danger" : "" ?>" name="password" placeholder="Please provide choose password">
                                        <p class="text-danger password"><?= isset($error['password']) ? $error['password'] : "" ?></p>

                                    </div>
                                    <div class="form-group">
                                        <label for="text">Confirm password:</label>
                                        <input type="password" class="form-control <?= isset($error['cpassword']) ? " border border-danger" : "" ?>" name="cpassword" placeholder="Please provide confirmed your password">
                                        <p class="text-danger cpassword"><?= isset($error['cpassword']) ? $error['cpassword'] : "" ?></p>

                                    </div>


                                    <button type="submit" name="adminconfigbtn" class="btn float-right btn-lg btn-primary">Finisih Setting</button>
                                </form>

                            </div>



                            <!-- congratullation message after installation -->
                            <div class="congratulation d-none">


                                <div class="text-center my-2">
                                    <span class="fa fa-check display-4 bg-success text-white p-4 rounded-circle"></span>
                                </div>
                                <div class="card bg-success text-white ">
                                    <div class="card-header">

                                        <h3 class="card-titlee">Congratulation</h3>

                                    </div>
                                    <div class="card-body">
                                        <h5> Dear <span class="schoolnames"></span>, you have successfully installed OLUOKUN KABIRU ADESINA CBT APPLICATION SOFTWARE </h5>
                                        <a href="../" class="my-3 btn btn-dark btn-block text-uppercase">click here to login</a>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
    </div>

    <script src="<?= BASE_URL ?>bootsrap/jquery.js"></script>
    <script src="<?= BASE_URL ?>bootsrap/popper.js"></script>
    <script src="<?= BASE_URL ?>bootsrap/bootstrap.min.js"></script>
    <script src="<?= BASE_URL ?>assets/loaders/pageloader.js"></script>
    <script src="<?= BASE_URL ?>plugins/datatables/datatables.min.js"></script>
    <script src="<?= BASE_URL ?>plugins/summernote/summernote-bs4.min.js"></script>

    <script src="<?= BASE_URL ?>script.js"></script>
    <script>
        $(document).ready(function() {
            pageLoader.hide();

        })


        $('form').submit(function(e) {
            e.preventDefault();
            var datas = new FormData(this);
            $.ajax({

                type: 'POST',
                url: 'installing.php',
                data: datas,
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == "error") {
                        $.each(JSON.parse(data.data), function(key, value) {
                            $('.' + key).html(value);

                            // console.log(key + " : " + value);
                        })

                        // console.log(data.next);
                        // console.log(data.current);
                        


                    }

                    if (data.status == "success") {
                        $("#successmesssage").html(data.message);
                        $("."+data.next).removeClass("d-none").addClass('d-block');
                        $("."+data.current).removeClass("d-block").addClass('d-none');
                    }

                    if (data.url ){
                        setTimeout(() => {
                            window.location.assign(data.url);
                        },600)
                    }

                    if (data.schoolnames){
                        $("#schoolnames").html(data.schoolnames);

                    }

                },
                beforeSend: function() {
                    pageLoader.show();
                },

                complete: function() {
                    pageLoader.hide();
                }
            })
        })
    </script>
</body>

</html>