<?php
session_start();
require_once('../../../includes/constant.php');
require_once("../../../includes/connection.php");
if ($_SESSION['adminauth']) {
    include('../../../tcpdf/tcpdf_import.php');
ob_start();
    if(isset($_GET['examinationid'])){
    $examinationid = $_GET['examinationid'];

    }
$result ='
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    table, td, th {
        border: 1px solid black;
    }
    table tr th{
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
    tr.even td{
        background-color: #000000c4;
        color:white;
        font-weight: bold;
        
        }

        tr.odd td{
        background-color: white;
        color:#000000c4;
        font-weight: bold;
        
        }

    </style>
  </head>

  <body>';
    
               $exam = new Users;
                if(!empty($examinationid)){
                    $examd = $exam->query("SELECT examinations.*, class.name AS classname, subjects.subjectname AS
                    subjectname FROM examinations JOIN class ON class.classid = examinations.classid JOIN subjects
                    ON subjects.subjectid = examinations.subjectid WHERE examinations.examinationid = '$examinationid' ");
                    $examdetails = $exam->data($examd);
                    $examname = $examdetails['subjectname']. ' for '.$examdetails['classname'].' result';

                if(!empty(trim($examdetails['subjectname']))){
            $result.='
            <h1 style="text-transform: uppercase;">'.$examdetails['subjectname'].' for '.$examdetails['classname'].' result</h1>
          
                    <div class="card">

                    <table id="tables" cellpadding="6" style="width:100%">
                            <thead>
                                <tr>
                                <th style="width: 7%;">ID</th>
                                <th>Student</th>
                                <th>Total score</th>
                                <th>Grade</th>
                                <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                ';
                                $q = $exam->query("SELECT users.userid AS userid, users.surname AS sname, users.firstname AS fname, users.lastname AS lname, student.class, class.name AS cclass,  studentstartexamination.* FROM users JOIN student ON users.userid=student.userid JOIN studentstartexamination ON studentstartexamination.studentid = student.studentid JOIN class ON student.class = class.classid WHERE studentstartexamination.examinationid = '$examinationid' ");
                                // $data = $exam->data($q);
                                $i = 1;
                                while($data = $exam->data($q)){

                                    $studentnames = $data['sname']." ". $data['fname']." ". $data['lname'];
                            $result.='
                                
                                <tr class="'; 
                                if($i%2==0){
                                    $result.='even';
                                }else{
                                    $result.='odd';
                                }
                                $result.='  ">
                                    <td scope="row" style="width: 7%;">'.$i++.'</td>
                                <td>'.ucwords($studentnames).'</td>
                                <td>'.ucwords($data['score']) .'</td>                                
                                <td>'. ucwords($data['grade']) .'</td>                                
                                <td>'.ucwords($data['remark']) .'</td>                                
                                </tr>';
                                
                                
                                 } 
                                 $result.='
                            </tbody>
                    </table>
                    </div>';
                    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('OLUOKUN KABIRU ADESINA');
$pdf->SetTitle($examname);
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

$pdf->writeHTML($result, true, false, true, false, '');
ob_clean();
$pdf->Output($examname, 'D');

                    echo $result;

                    ?>

    <?php 
                }else{

                    ?>

    <h3 class="danger"> No examination with <strong> <?php echo $examinationid ?> </strong> details</h3>
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
  header('location:'. LOGOUT);
}
?>