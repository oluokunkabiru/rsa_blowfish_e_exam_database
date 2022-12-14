<?php
session_start();
require_once('../../../includes/constant.php');
  require_once("../../../includes/connection.php");
if ($_SESSION['adminauth']) {
    // include("../../../includes/connection.php");
    if (isset($_GET['student'])) {
        $studentid = $_GET['student'];
        $term = $_GET['term'];
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CHECK STUDENT RESULT</title>
        <?php include('../header.php') ?>
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }

                .noprint * {
                    visibility: hidden;

                }

                #printresult,
                #printresult * {
                    visibility: visible;
                }

                #printresult {
                    position: absolute;
                    left: 0;
                    right: 0;
                }
            }
        </style>

    </head>

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?php include('../sidebar.php') ?>
            <div class="content-wrapper">
                <section class="content">
                    <div class="container-fluid" id="printresult">
                        <?php
                        $student = new Users;
                        if (!empty($studentid)) {
                            $q = $student->query("SELECT users.*, student.*,  class.classid AS classid,  class.name AS classname FROM users JOIN student ON users.userid = student.userid JOIN class ON student.class = class.classid WHERE student.studentid ='$studentid' ");

                            $data = $student->data($q);
                            $studentids = $data['studentid'];
                            $classid = $data['classid'];
                            $studentnames = $data['surname'] . " " . $data['firstname'] . " " . $data['lastname'];
                            if (!empty(trim($studentnames))) {

                                $result = $student->query("SELECT subjects.subjectname AS subjectname, class.name AS classname, examinations.examname AS examname,examinations.examinationid AS examinationid, studentstartexamination.id AS id, studentstartexamination.classid AS classid, studentstartexamination.grade AS grade, studentstartexamination.scores AS scores, studentstartexamination.score AS score,studentstartexamination.examinationtype AS examtype, studentstartexamination.remark AS remark FROM subjects JOIN class ON subjects.classid = class.classid JOIN examinations ON examinations.subjectid = subjects.subjectid JOIN studentstartexamination ON studentstartexamination.examinationid = examinations.examinationid WHERE studentstartexamination.studentid='$studentids' AND examinations.current_term='$term' ");
                        ?>
                                <h2 class="text-center text-uppercase font-weight-bold mt-2 mb-2">
                                    <?php echo $studentnames ?>
                                    <?php echo $data['classname'] ?>
                                    <?php echo $term ?> result
                                </h2>
                                <a href="#addresult" class="btn btn-success btn-lg text-uppercase mt-2 mb-2" data-toggle="modal"><span class="fa fa-book mr-2"></span>Add New result</a>
                                <a href="printmyresult.php?student=<?php echo $studentid ?>" class="btn btn-primary btn-lg my-2 float-right"><span class="fa fa-download"></span> Download result</a>
                                <button class="btn btn-success btn-lg noprint" onclick="window.print()"> <span class="fa fa-print"></span> Print</button>


                                <table id="tables" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Subject</th>
                                            <th>Score (%)</th>
                                            <th>Aggregate (%)</th>
                                            <th>Grade</th>
                                            <th>Remark</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $i = 1;
                                        $agg = [];
                                        while ($results = $student->data($result)) {
                                            if (count(isset($results['scores']) ? json_decode($results['scores'], TRUE) : []) > 0) {

                                        ?>

                                                <tr>
                                                    <td scope="row">
                                                        <?php echo $i++ ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ucwords($results['subjectname']) ?>
                                                    </td>
                                                    <td>

                                                        <?php
                                                        $scores = isset($results['scores']) ? json_decode($results['scores'], TRUE) : [];
                                                        foreach ($scores as $key => $score) {


                                                        ?>
                                                            <p>
                                                                <?php echo ucwords($score['type']) ?> : <span class="font-weight-bold"><?php echo number_format($score['score']) ?></span>
                                                            </p>
                                                            <?php
                                                            if ($score['type'] != 'cbt') {
                                                            ?>
                                                                <a href="#editresult" class="btn btn-sm btn-primary" data-toggle="modal" key="<?= $key ?>" editresult="<?php echo $results['id'] ?>"><span class="fa fa-edit p-1"></span></a>
                                                                <a href="#deleteresult" class="btn btn-sm btn-danger" data-toggle="modal" key="<?= $key ?>" deleteresultconfirm="<?php echo $results['id'] ?>"><span class="fa fa-trash p-1"></span></a>
                                                            <?php } else { ?>
                                                                <a href="checkresuls.php?student=<?php echo $data['userid'] ?>&&examinationid=<?php echo $results['examinationid'] ?>" class="btn btn-primary"> More <span class="ml-2 fa fa-angle-double-right"></span></a>
                                                            <?php } ?>


                                                        <?php }  ?>

                                                    </td>
                                                    <th>
                                                        <?php $agg[] = number_format($results['score'], 2);
                                                        echo number_format($results['score'], 2);  ?> %
                                                    </th>


                                                    <td>
                                                        <?php echo $results['grade']  ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $results['remark']  ?>
                                                    </td>

                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>

                                    </tbody>
                                    <?php

                                    if ($student->getAggregate($agg) > 0) {
                                    ?>
                                        <tfoot>
                                            <tr>
                                                <th>Average</th>
                                                <td></td>
                                                <td></td>
                                                <th><?= $student->getAggregateSum($agg) . "  <b> (" . $student->getAggregate($agg) . " %)</b>" ?></th>
                                                <th><?= $student->getGrade($student->getAggregate($agg)) ?></th>
                                                <th><?= $student->getRemark($student->getAggregate($agg)) ?></th>
                                            </tr>
                                        </tfoot>
                                    <?php } ?>

                                </table>
                    </div>
                    <!-- add new class -->
                    <section>
                        <div class="modal" id="addresult">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h3 class="modal-title text-uppercase font-weight-bold">add new result</h3>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <p class="error text-danger ml-3"></p>
                                        <form id="addresultform">
                                            <input type="hidden" name="studentid" value="<?php echo $studentids ?>">
                                            <input type="hidden" name="classid" value="<?php echo $classid ?>">
                                            <div class="form-group">
                                                <label for="email">Subject</label>
                                                <select name="subject" class="custom-select">
                                                    <option value="">Select Subject</option>
                                                    <?php
                                                    // $class = new Classes;
                                                    $q = $student->query("SELECT* FROM subjects WHERE classid = '$classid' ");
                                                    while ($data = $student->data($q)) {
                                                    ?>

                                                        <option value="<?php echo $data['subjectid'] ?>"><?php echo $data['subjectname'] ?></option>
                                                    <?php } ?>
                                                </select>

                                            </div>

                                            <div class="form-group">
                                                <label for="email">Score</label>
                                                <select name="score" class="custom-select">
                                                    <option value="">Select Score</option>
                                                    <?php
                                                    // $class = new Classes;
                                                    $score = 1;
                                                    while ($score <= 100) {
                                                    ?>

                                                        <option value="<?php echo $score ?>"><?php echo $score ?></option>
                                                    <?php $score++;
                                                    } ?>
                                                </select>

                                            </div>
                                            <div class="form-group">
                                                <label for="sel1">Result type:</label>
                                                <select class="form-control" name="type" id="sel1">
                                                    <option>Theory</option>
                                                    <option>Test</option>
                                                    <option>Practical</option>
                                                    <option>Assignment</option>
                                                    <option>Attendance</option>
                                                    <option>Neatness</option>

                                                </select>
                                                </div> 
                                        </form>
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <button class="btn btn-primary btn-block text-uppercase" id="addnewresult" name="addnewclass">Add result</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- ///end class -->
                    <!-- view class -->
                    <div id="deleteresult" class="modal">
                        <div class="modal-dialog">
                            <div class="deleteresult">

                            </div>
                        </div>
                    </div>
                    <div id="editresult" class="modal">
                        <div class="modal-dialog">
                            <div class="editresult">

                            </div>
                        </div>
                    </div>

                <?php
                            } else {

                ?>

                    <h3 class="text-danger text-center"> No user with <strong> <?php echo $studentid ?> </strong> details</h3>
                <?php
                            }
                        } else {

                ?>
                <h3 class="text-danger text-center text-uppercase"><strong> Oops !!! </strong></h3>
                <h4 class="text-danger text-center">It seems you are accessing wrong URL</h4>
            <?php
                        }
            ?>
                </section>

            </div>
            <?php include('../footer.php'); ?>
        </div>
    </body>

    </html>


<?php
    include('../script.php');
} else {
    header('location:'. LOGOUT);
}
?>