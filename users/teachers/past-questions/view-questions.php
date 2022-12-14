<?php

use Dompdf\Dompdf;
use Dompdf\Options;
require_once('../../../includes/constant.php');
require_once("../../../includes/connection.php");
session_start();
if ($_SESSION['teacherauth']) {
    // include('../../dompdf/autoload.inc.php');
    // $pdfOptions = new Options();
    // $pdfOptions->set('defaultFont', 'Arial');
    // Instantiate Dompdf with our options

    // ob_start();
    // $option = new Options();
    // $option->setIsRemoteEnabled(true);
    // $option->setDefaultFont();
    // $pdf = new Dompdf();
    // $pdf->getOptions()->setChroot('../../../plugins/dist/css/adminlte.min.css');


?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>VIEW EXAMINATION QUESTIONS</title>
        <?php include('../header.php') ?>
        <style>
            .questionoption p {
                display: inline;
            }
            @media print{
            body *{
                visibility:hidden;
            }
            #printpastquestion, #printpastquestion *{
                visibility: visible;
            }
            #printpastquestion{
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
                    <div class="container-fluid" id="printpastquestion">
                        <div class="container pt-4 pb-4">
                            <?php
                            $examinationid = $_GET['examinationid'];
                            $past = new Examination;
                            $ex = $past->query("SELECT examinations.*, class.name AS classname, subjects.subjectname AS 
                    subjectname FROM examinations JOIN class ON class.classid = examinations.classid JOIN subjects 
                    ON subjects.subjectid = examinations.subjectid WHERE examinationid='$examinationid'");
                            $exams = $past->data($ex);
                            $examname = $exams['subjectname'] . ' for ' . $exams['classname'];
                            // $past_question = 
                            ?>
                            <div id="printpastquestion">
                                <h1 class="text-cente text-uppercase font-weight-bold mt-2 mb-2">Examination Details</h1>
                                <!-- <table>
                                    <tr>
                                        <td style="width: 25%; padding: 2%;">
                                            <h3>Subject name</h3>
                                        </td>
                                        <td style="width: 25%;">
                                            <h3>
                                                <h3><strong>
                                                        <?php //echo ucwords($exams['subjectname']) ?></strong>
                                                </h3>
                                            </h3>
                                        </td>
                                    </tr>
                                </table> -->
                                <div class="row" style="">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col">
                                                <h3>Subject name</h3>
                                            </div>
                                            <div class="col">
                                                <h3><strong>
                                                        <?php echo ucwords($exams['subjectname']) ?></strong>
                                                </h3>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col">
                                                <h3>Examination class</h3>
                                            </div>
                                            <div class="col">
                                                <h3><strong><?php echo ucwords($exams['classname']) ?></strong></h3>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col">
                                                <h3>Examination description</h3>
                                            </div>
                                            <div class="col">
                                                <h3><strong><?php echo ucwords($exams['description']) ?></strong></h3>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col">
                                                <h3>Examination date</h3>
                                            </div>
                                            <div class="col">
                                                <h3><strong><?php echo date("M d, Y", strtotime($exams['startdate']))  ?></strong></h3>
                                            </div>
                                        </div>
                                        <hr>
                                        <?php
                                        $qu = $past->query("SELECT COUNT(id) AS totalquest FROM questions WHERE examinationid='$examinationid' ");
                                        $tot = $past->data($qu);
                                        ?>

                                        <div class="row">
                                            <div class="col">
                                                <h3>Total Question</h3>
                                            </div>
                                            <div class="col">
                                                <h3><strong><?php echo  $tot['totalquest'] ?></strong></h3>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <a href="past.php?examinationid=<?php echo $examinationid ?>" class="btn btn-primary">Download Past question</a>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-success" onclick="window.print()" >Print <span class="ml-2 fa fa-print"></span></button>
                                    </div>
                                </div>


                                <h1 class="text-center text-uppercase font-weight-bold mt-2 mb-2"> questions </h1>
                                <?php
                                $exam = new Examination;
                                $q = $exam->query("SELECT questions.*, examinations.duration as duration FROM questions JOIN examinations ON questions.examinationid = examinations.examinationid WHERE examinations.examinationid = '$examinationid' ");
                                $i = 1;
                                while ($data = $exam->data($q)) {
                                ?>


                                    <div class="card mt-2">
                                        <div class="card-header">
                                            <span class="font-weight-bold display-4 float-left mx-3"><?php echo $i ?></span>
                                            <?php echo html_entity_decode($data['question']) ?>
                                        </div>
                                        <div class="card-body">
                                            <?php
                                            if ($data['option_a'] != "") {
                                            ?>
                                                <!-- $past_question.=' < -->
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" disabled class="custom-control-input options" id="optiona" <?php
                                                            }
                                                            if ($data['correct'] == "A") {
                                                                echo 'checked';
                                                            }
                                                                ?>>
                                                    <label class="custom-control-label" for="optiona">

                                                        A. <span class="ml-2 text-secondary questionoption"><span style="display: inline;">
                                                                <?php
                                                                if ($data['correct'] == "A") {
                                                                ?>
                                                                    <span class="mx-2 fa fa-check-square font-weight-bold text-dark"></span>
                                                                <?php
                                                                }
                                                                ?>


                                                                <!-- $past_question.=' -->
                                                            </span><?php echo   html_entity_decode($data['option_a']) ?>

                                                        </span>


                                                    </label>
                                                </div>


                                                <?php
                                                if ($data['option_b'] != "") {
                                                    // $past_question.='
                                                ?>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" disabled class="custom-control-input options" id="optionb" <?php
                                                                                                                                        if ($data['correct'] == "B") {
                                                                                                                                            echo 'checked';
                                                                                                                                        }
                                                                                                                                        //  $past_question.='
                                                                                                                                        ?>>
                                                        <label class="custom-control-label" for="optionb"> B. <span class="ml-2 text-secondary questionoption">
                                                                <?php
                                                                if ($data['correct'] == "B") {
                                                                    // $past_question.='
                                                                ?>
                                                                    <span class="mx-2 fa fa-check-square font-weight-bold text-dark"></span>

                                                                <?php  }

                                                                echo html_entity_decode($data['option_b'])
                                                                ?></span> </label>
                                                    </div>
                                                <?php }


                                                if ($data['option_c'] != "") {
                                                    // $past_question.='
                                                ?>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" disabled class="custom-control-input options" id="optionc" <?php
                                                                                                                                        if ($data['correct'] == "C") {
                                                                                                                                            echo "checked";
                                                                                                                                        }
                                                                                                                                        // $past_question.='
                                                                                                                                        ?>>
                                                        <label class="custom-control-label" for="optionc"> C. <span class="ml-2 text-secondary questionoption">
                                                                <?php
                                                                if ($data['correct'] == "C") {

                                                                    // $past_question.='
                                                                ?>
                                                                    <span class="mx-2 fa fa-check-square font-weight-bold text-dark"></span>
                                                                <?php
                                                                }

                                                                echo html_entity_decode($data['option_c'])  ?></span> </label>
                                                    </div>
                                                <?php
                                                }

                                                if ($data['option_d'] != "") {
                                                    // $past_question.='
                                                ?>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" disabled class="custom-control-input options" id="optiond" <?php
                                                                                                                                        if ($data['correct'] == "D") {
                                                                                                                                            echo  "checked";
                                                                                                                                        }
                                                                                                                                        // $past_question.='
                                                                                                                                        ?>>
                                                        <label class="custom-control-label" for="optiond"> D. <span class="ml-2 text-secondary questionoption">
                                                                <?php
                                                                if ($data['correct'] == "D") {
                                                                    // $past_question.='
                                                                ?>
                                                                    <span class="mx-2 fa fa-check-square font-weight-bold text-dark"></span>
                                                                <?php
                                                                }

                                                                echo html_entity_decode($data['option_d']) ?></span> </label>
                                                    </div>
                                                <?php
                                                }

                                                if ($data['option_e'] != "") {
                                                    // $past_question.='
                                                ?>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" disabled class="custom-control-input options" id="optione" <?php if ($data['correct'] == "E") {
                                                                                                                                            echo "checked";
                                                                                                                                        }
                                                                                                                                        // $past_question.='
                                                                                                                                        ?>>
                                                        <label class="custom-control-label" for="optione"> E. <span class="ml-2 text-secondary questionoption">
                                                                <?php
                                                                if ($data['correct'] == "E") {
                                                                    // $past_question.='
                                                                ?>
                                                                    <span class="mx-2 fa fa-check-square font-weight-bold text-dark"></span>
                                                                <?php
                                                                }

                                                                echo  html_entity_decode($data['option_e']) ?></span>

                                                        </label>
                                                    </div>

                                                <?php } ?>

                                        </div>
                                    </div>
                                <?php
                                    $i++;
                                }
                                ?>
                            </div>
                            <!-- echo $past_question; -->

                            <!-- // $pdf->loadHtml($past_question);
                            // $pdf->setPaper('Letter', 'landscape');
                            //  $pdf->render();
                            //  ob_end_clean();
                            //  $pdf->stream(str_replace (" ", "_", $examname), array('Attachment' =>1));
                        
                        

                        ?> -->


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
    header('location:../../../');
}
?>
<script src="../../../js/jspdf.min.js"></script>

<script>
    function printme() {



        var pdf = new jsPDF('l', 'pt', 'letter')

            // source can be HTML-formatted string, or a reference
            // to an actual DOM element from which the text will be scraped.
            ,
            source = $('#printpastquestion')[0]

            // we support special element handlers. Register them with jQuery-style 
            // ID selector for either ID or node name. ("#iAmID", "div", "span" etc.)
            // There is no support for any other type of selectors 
            // (class, of compound) at this time.
            ,
            specialElementHandlers = {
                // element with id of "bypass" - jQuery style selector
                '#bypassme': function(element, renderer) {
                    // true = "handled elsewhere, bypass text extraction"
                    return true
                }
            }

        margins = {
            top: 80,
            bottom: 60,
            left: 40,
            width: 522
        };
        // all coords and widths are in jsPDF instance's declared units
        // 'inches' in this case
        pdf.fromHTML(
            source // HTML string or DOM elem ref.
            , margins.left // x coord
            , margins.top // y coord
            , {
                'width': margins.width // max width of content on PDF
                    ,
                'elementHandlers': specialElementHandlers
            },
            function(dispose) {
                // dispose: object with X, Y of the last line add to the PDF 
                //          this allow the insertion of new lines after html
                pdf.save('<?php echo str_replace(" ", "_", $examname) ?>.pdf');
            },
            margins
        )
    }
</script>