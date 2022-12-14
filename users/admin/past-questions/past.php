<?php
session_start();
include("../../../includes/connection.php");
// ini_set('memory_limit', '20480M');
// set_time_limit(0);
if ($_SESSION['adminauth']) {
if(isset($_GET['examinationid'])){
$examinationid = $_GET['examinationid'];
$past = new Examination;
$ex = $past->query("SELECT examinations.*, class.name AS classname, subjects.subjectname AS 
subjectname FROM examinations JOIN class ON class.classid = examinations.classid JOIN subjects 
ON subjects.subjectid = examinations.subjectid WHERE examinationid='$examinationid'");
$exams = $past->data($ex);
$examname = $exams['subjectname']. ' for '.$exams['classname'];
}


include('../../../tcpdf/tcpdf_import.php');
// include('../../../../oic_cbt/users/admin/examination/questions.php');
ob_start();


$output ='
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        '.file_get_contents("../../style.css").'
    </style>
</head>
<body>
    <div class="container">
    <div class="half text-center m my">
    <h1 class="m">Examination Details</h1>
    <hr>
    <hr>
        <table>
            <thead>
                <tr>
                    <td class="text-left"><h3>Subject Name</h3></td>
                    <td class="text-left"><h3><b>'.ucwords($exams['subjectname'] ).'</b></h3></td>
                    
                </tr>
                <tr>
                    <td class="text-left"><h3>Examination Class</h3></td>
                    <td class="text-left"><h3><b>'.ucwords($exams['classname'] ).'</b></h3></td>
                    
                </tr>

                <tr>
                    <td class="text-left"><h3>Examination Description</h3></td>
                    <td class="text-left"><h3><b>'.ucwords($exams['description'] ).'</b></h3></td>
                    
                </tr>
                <tr>
                    <td class="text-left"><h3>Examination Date</h3></td>
                    <td class="text-left"><h3><b>'.date("M d, Y", strtotime($exams['startdate'])) .'</b></h3></td>
                    
                </tr>';
                // $pdf->writeHTML($output, true, false, true, false, '');
                 $qu = $past->query("SELECT COUNT(id) AS totalquest FROM questions WHERE examinationid='$examinationid' ");
                 $tot = $past->data($qu);
                $output.='
                <tr>
                    <td class="text-left"><h3>Total Question</h3></td>
                    <td class="text-left"><h3><b>'.$tot['totalquest'] .'</b></h3></td>
                    
                </tr>
            </thead>
        </table>


       
    
            
    </div>';
     
                               
        $exam = new Examination;
        $q = $exam->query("SELECT questions.*, examinations.duration as duration FROM questions JOIN examinations ON questions.examinationid = examinations.examinationid WHERE examinations.examinationid = '$examinationid' ");
        $i = 1;
        while ($data = $exam->data($q)) {
            $output.='
    <div class="card">
        
    <table>
    <tr>
    <td style="width: 10%;" class="num">'. $i.'</td>
    <td>
    '.html_entity_decode($data['question']).'
    <table>
';
        if ($data['option_a'] != "") {
        $output.='
        <tr class="'; if ($data['correct'] == "A") {         
            $output.='correct'; }
        $output.='"> 
        <td  style="width: 10%;" class="opt 
         ">A.</td>
         <td>
         '. html_entity_decode($data['option_a']).'
          </td>
        </tr>';
        } 
        if ($data['option_b'] != "") {
            $output.='
            <tr class="'; if ($data['correct'] == "B") {         
                $output.='correct'; }
            $output.='"> 
            <td  style="width: 10%;" class="opt 
             ">B.</td>
             <td>
             '. html_entity_decode($data['option_b']).'
              </td>
            </tr>';
            } 

            if ($data['option_c'] != "") {
                $output.='
                <tr class="'; if ($data['correct'] == "C") {         
                    $output.='correct'; }
                $output.='"> 
                <td  style="width: 10%;" class="opt 
                 ">C.</td>
                 <td>
                 '. html_entity_decode($data['option_c']).'
                  </td>
                </tr>';
                } 

                if ($data['option_d'] != "") {
                    $output.='
                    <tr class="'; if ($data['correct'] == "D") {         
                        $output.='correct'; }
                    $output.='"> 
                    <td  style="width: 10%;" class="opt 
                     ">D.</td>
                     <td>
                     '. html_entity_decode($data['option_d']).'
                      </td>
                    </tr>';
                    } 
                    if ($data['option_e'] != "") {
                        $output.='
                        <tr class="'; if ($data['correct'] == "E") {         
                            $output.='correct'; }
                        $output.='"> 
                        <td  style="width: 10%;" class="opt 
                         ">E.</td>
                         <td>
                         '. html_entity_decode($data['option_e']).'
                          </td>
                        </tr>';
                        } 
        $output.='

        </table>
    </td>
        
        
    </tr>
    </table>
    </div>
    <hr>';
$i++;
} 
$output .= '<img src="'.K_PATH_IMAGES.'images/oic.png'.' " alt="village boy">' ;

$output.='
</div>
</body>
</html>
';

// class genPdf extends TCPDF{
//     public function Header()
//     {
//         $img = K_PATH_IMAGES.'../images/oic.png';
//         $this->Image($img. 10,10,15,'', 'PNG','T', false, 300, '', false,0, false, false, false);
//         $this->SetFont('helvetica', 'B', 20);
//         $this->SetAuthor('OLUOKUN KABIRU ADESINA');
//         $this->Cell(0, 10, 'Village boy', 0, false, 'C', 0, '', 0, 'M', 'M');
//     }
// }
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

$pdf->writeHTML($output, true, false, true, false, '');
ob_clean();
$pdf->Output($examname, 'D');

echo $output;
?>
    <?php
} else {
    header('location:../../../');
}