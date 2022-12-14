<?php
session_start();
require_once('../../../includes/constant.php');
require_once("../../../includes/connection.php");
if ($_SESSION['adminauth']) {
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEW EXAMINATION</title>
    <?php include('../header.php') ?>

  </head>

  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <?php include('../sidebar.php') ?>
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-2"></div>
              <div class="col-md-8 py-4">
                <!-- Horizontal Form -->
                <div class="card card-info">
                  <div class="card-header">
                    <h3 class="card-title">
                      <h3 class="text-center text-uppercase font-weight-bold">new examination</h3>
                    </h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <p class="error text-danger pl-4"></p>
                  <form class="form-horizontal" id="newexaminationform">
                      <div class="card-body">
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">Select Class</label>
                          <div class="col-sm-8">
                            <select class="custom-select" onchange="selectclass(this.value)" name="examclass">
                              <option value="">Select class</option>
                              <?php
                              $class = new Classes;
                              $q = $class->query("SELECT* FROM class");
                              while ($data = $class->data($q)) {
                                echo '<option value="' . $data['classid'] . '">' . $data['name'] . '</option>';
                              }

                              ?>

                            </select>

                            <p class="text-danger examclass"></p>

                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">Select Subject</label>
                          <div class="col-sm-8">
                            <select class="custom-select" id="classsubject" name="classsubject">

                            </select>

                            <p class="text-danger classsubject"></p>

                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">Examination Description</label>
                          <div class="col-sm-8">
                            <textarea class="form-control" name="examinationdescription" rows="3" placeholder="Enter examination Description"></textarea>
                            <p class="text-danger examinationdescription"></p>

                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="" class="col-sm-4 col-form-label">Examination Name</label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control" name="examname" placeholder="Enter examination name">
                            <p class="text-danger examname"></p>

                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="" class="col-sm-4 col-form-label">Examination Duration(Mins)</label>
                          <div class="col-sm-8">
                            <input type="number" class="form-control" name="duration" placeholder="Duration Time">
                            <p class="text-danger duration"></p>

                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="" class="col-sm-4 col-form-label">Examination From (DD/MM/YY)</label>
                          <div class="col-sm-8">
                            <input type="date" name="startdate" class="form-control float-right" id="startdate">
                            <p class="text-danger startdate"></p>

                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="" class="col-sm-4 col-form-label">Examination To (DD/MM/YY)</label>
                          <div class="col-sm-8">
                            <input type="date" class="form-control" name="enddate" id="enddate" placeholder="Enter End date name">
                            <p class="text-danger enddate"></p>

                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="" class="col-sm-4 col-form-label">Examination PIN</label>
                          <div class="col-sm-8">
                            <input type="number" class="form-control" name="exampasscode" placeholder="Pass Code">
                            <p class="text-danger exampasscode"></p>

                          </div>
                        </div>
                        <!-- <div class="form-group row">
                          <label for="" class="col-sm-4 col-form-label">Examination Total score (%)</label>
                          <div class="col-sm-8">
                            <input type="number" min="0" max="100" class="form-control" name="tscore" placeholder="e.g 40%">
                          </div>
                        </div> -->
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">Exam result visibility to students </label>
                          <div class="col-sm-8">
                            <select class="custom-select" name="visibility">
                              <option value=""> Select visibility</option>
                              <option value="yes">Yes</option>
                              <option value="no">No</option>

                            </select>
                            <p class="text-danger visibility"></p>

                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">Question display </label>
                          <div class="col-sm-8">
                            <select class="custom-select" name="display">
                              <option value=""> Select question display</option>
                              <option value="random">Random</option>
                              <option value="static">Static</option>

                            </select>
                            <p class="text-danger display"></p>

                          </div>
                        </div>
                      </div>
                      <!-- /.card-body -->
                      <div class="card-footer">
                        <button id="newexaminationbtn" class="btn btn-primary btn-block btn-lg text-uppercase">Create new examination</button>
                      </div>
                      <!-- /.card-footer -->
                    </form>
                </div>
              </div>
              <div class="col-md-2"></div>
              <!-- /.card -->

            </div>
          </div>
        </section>
      </div>
      <?php include('footer.php'); ?>

    </div>
  </body>

  </html>
  <script>
    function selectclass(str) {
      // var classe = "";
      // alert(str);
      if (str.length == 0) {
        document.getElementById("classsubject").innerHTML = "";
        return;
      } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            var result = document.getElementById("classsubject")
            var ret = this.responseText
            result.innerHTML = ret;
            // classe = str;
          }
        };
        xmlhttp.open("GET", "../confirm.php?classselectedforexam=" + str, true);
        xmlhttp.send();
      }
    }
  </script>
<?php
  include('../script.php');
} else {
  header('location:' . LOGOUT);
}
?>