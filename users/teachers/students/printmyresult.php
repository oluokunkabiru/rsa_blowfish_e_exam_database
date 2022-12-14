<?php
session_start();
if ($_SESSION['teacherauth']) {
    include("../../../includes/connection.php");
    include('../../../tcpdf/tcpdf_import.php');
    ob_start();
    if(isset($_GET['student'])){
    $studentid = $_GET['student'];
    }
$myresult ='
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
           
            
            table,
            td,
            th {
                border: 1px solid black;
            }
            
            table tr th {
                background-color: black;
                color: white;
                font-size: 15px;
                font-weight: bold;
            }
            /* table {
        border-collapse: collapse;
        width: 100%;
    } */
            
            td {
                height: 50px;
                vertical-align: bottom;
            }
            
            tr.even td {
                background-color: #000000c4;
                color: white;
                font-weight: bold;
            }
            
            tr.odd td {
                background-color: white;
                color: #000000c4;
                font-weight: bold;
            }
        </style>
    </head>

    <body class="">
        <div class="wrapper">

            ';
               $student = new Users;
                if(!empty($studentid)){
                $q = $student->query("SELECT users.*, student.*,  class.classid AS classid,  class.name AS classname FROM users JOIN student ON users.userid = student.userid JOIN class ON student.class = class.classid WHERE student.studentid ='$studentid' ");

                $data = $student->data($q);
                $studentids=$data['studentid'];
                $classid = $data['classid'];

                $studentnames = $data['surname']." ". $data['firstname']." ". $data['lastname'];
                if(!empty(trim($studentnames))){            
            $result = $student->query("SELECT subjects.subjectname AS subjectname, class.name AS classname, examinations.examname AS examname,examinations.examinationid AS examinationid, studentstartexamination.id AS id, studentstartexamination.classid AS classid, studentstartexamination.grade AS grade, studentstartexamination.score AS score,studentstartexamination.examinationtype AS examtype, studentstartexamination.remark AS remark FROM subjects JOIN class ON subjects.classid = class.classid JOIN examinations ON examinations.subjectid = subjects.subjectid JOIN studentstartexamination ON studentstartexamination.examinationid = examinations.examinationid WHERE studentstartexamination.studentid='$studentids'");
            // $examname = 
        $myresult.='
                <h1 class="" style="text-transform: uppercase; text-align: center;">'.$studentnames.' result for '.$data['classname'].'</h1>

                <table cellpadding="6" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subject</th>
                            <th>Score</th>
                            <th>Grade</th>
                            <th>Remark</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        ';
               
                $i = 1;
                while ($results = $student->data($result)) {


                    $myresult.='
                            <tr>
                                <td scope="row">
                                    '.$i++.'
                                </td>
                                <td>
                                    '.$results['subjectname'].'
                                </td>
                                <td>
                                    '.$results['score'].'
                                </td>
                                <td>
                                    '.$results['grade'] .'
                                </td>
                                <td>
                                     '.$results['remark'].'
                                </td>
                                <td>
                                    '.ucwords($results['examtype']).'
                                </td>

                            </tr>
                            ';
                }
                $myresult.='

                    </tbody>

                </table>
        </div>';
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('OLUOKUN KABIRU ADESINA');
// $pdf->SetTitle($examname);
$pdf->SetSubject('Village Boy');

// $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO,PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(1.66);


// set font
$pdf->SetFont('dejavusans', '', 7);
// add a page
$pdf->AddPage('P', 'A4');

$pdf->writeHTML($myresult, true, false, true, false, '');
ob_clean();
$pdf->Output($studentnames, 'D');

        echo $myresult;
       ?>


    <?php 
                }else{

                    ?>

    <h3 class="text-danger text-center"> No user with <strong> <?php echo $studentid ?> </strong> details</h3>
    <?php
            }
        }else{

           ?>
        <h3 class="text-danger text-center text-uppercase"><strong> Oops !!! </strong></h3>
        <h4 class="text-danger text-center">It seems you are accessing wrong URL</h4>
        <?php
}
           ?>
            </section>

            </div>
            </div>
            </body>

            </html>


            <?php
} else {
  header('location:../../../');
}
?>