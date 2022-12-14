<?php
include('../../../includes/connection.php');

$errors = [];
if($_FILES['excelfile']['size']==0){
    array_push($errors, "<span class='text-danger'>Please Select questions excel file</span>");
}

if($_FILES['excelfile']['size']!=0){
 $target_file = basename($_FILES["excelfile"]["name"]);
$file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$ext = array("csv");
if(!in_array($file_type, $ext)){
    array_push($errors, "<span class='text-danger'>Only excel format is required</span>");
}
}

if (count($errors) > 0){
    echo json_encode([
        'status' => 'error',
        'data' => json_encode($errors),
        

    ]);
}
// foreach($errors as $error){
//     echo $error ."<br>";
// }





    // }
// echo "<h2> Table 2</h1>";
// echo '<table class="table table-bordered " >'; 
// $tfile = fgetcsv($handle, 1000, ",");


// function printMultiD($array){
//     // Printing all the keys and values one by one
//     // echo "Herror";
// $keys = array_keys($array);
// echo '<table class="table table-bordered text-success" >'; 
// for($i = 0; $i < count($array); $i++) {
//     echo '<tr>'; 

//     echo "<td>". $keys[$i] . "</td>";
//     foreach($array[$keys[$i]] as $key => $value) {
//         echo  "<td>" . $value . "</td>";
//     }
//     echo "</tr>";
// }
// echo "</table>";

// }


// function printMultiDerror($array){
    // Printing all the keys and values one by one
// $keys = array_keys($array);
// $ok =  '<div class="alert alert-danger alert-dismissible">
// <button type="button" class="close" data-dismiss="alert">&times;</button>
// <p><strong style="font-size:1.4em">OOPS!</strong><br> Please resolve the below error(s)</p>.
// </div>';
// $ok .= '<table class="table table-bordered text-danger" >'; 
// for($i = 0; $i < count($array); $i++) {
//     // $ok .= '<tr>'; 

//     $ok .= "<td>". $keys[$i]+1 . "</td>";
//     foreach($array[$keys[$i]] as $key => $value) {
//         $ok.=  "<td>" . $value . "</td>";
//     }
//     $ok.= "</tr>";
// }
// $ok.= "</table>";
// return $ok;
// echo json_encode([
//     'status' => 'error',
//     'data' => json_encode($array),
    

// ]);

// }

// display good result
// function printErrorFreeMultiD($array){
//     // Printing all the keys and values one by one
// $keys = array_keys($array);
// $ok =  '<div class="alert alert-success alert-dismissible">
// <button type="button" class="close" data-dismiss="alert">&times;</button>
// <p><strong style="font-size:1.4em">OOPS!</strong><br> Please resolve the below error(s)</p>.
// </div>';
// $ok .= '<table class="table table-bordered text-success" >'; 
// for($i = 0; $i < count($array); $i++) {
//     $ok .= '<tr>'; 

//     $ok .= "<td>". $keys[$i] . "</td>";
//     foreach($array[$keys[$i]] as $key => $value) {
//         $ok.=  "<td>" . $value . "</td>";
//     }
//     $ok.= "</tr>";
// }
// $ok.= "</table>";
// }





    // error free
    function ErrorArrayFromCsv($file, $delimiter) {
        if (($handle = fopen($file, "r")) !== FALSE) {
            $i = 0;
            // fgetcsv()
            $csverror =[];
            $suggestcorrect = ["A", "a", "B", "b", "c", "C", "D", "d", "E","e"];
            $data2DArray = array();
            while (($lineArray = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                for ($j = 0; $j < count($lineArray); $j++) {
                    if($i > 0){
                        // print_r($lineArray);
                        $data2DArray[$i]['question'] = $lineArray[0];
                        $data2DArray[$i]['optiona'] = $lineArray[1];
                        $data2DArray[$i]['optionb'] = $lineArray[2];
                        $data2DArray[$i]['optionc'] = $lineArray[3];
                        $data2DArray[$i]['optiond'] = $lineArray[4];
                        $data2DArray[$i]['optione'] = $lineArray[5];
                        $data2DArray[$i]['correct'] = $lineArray[6];
                        $data2DArray[$i]['mark'] = $lineArray[7];
                        if($lineArray[3]=="" && ($lineArray[6]=="c" || $lineArray[6]=="C")){
                            $csverror[$i] = "Option C must not be empty because you choose it as correct answer";
                        }
                        if($lineArray[4]=="" && ($lineArray[6]=="d" || $lineArray[6]=="D")){
                            $csverror[$i] = "Option D must not be empty because you choose it as correct answer";
                        }
                        
                        if($lineArray[5]=="" && ($lineArray[6]=="e" || $lineArray[6]=="E")){
                            $csverror[$i] = "Option E must not be empty because you choose it as correct answer";
                        }
                        if($lineArray[0]==""){
                            $csverror[$i] = "Questions must not be empty";
                        }
                        if($lineArray[7]==""){
                            $csverror[$i] = "Question mark must be specify";
                        }
                        if(!preg_match("/^[0-9]*$/",$lineArray[7])){
                            $csverror[$i] = "Mark must be number";
                        }
                       
                        if($lineArray[1]==""){
                            $csverror[$i] = " Option A must not be empty  ";
                        }
                        if($lineArray[2]==""){
                            $csverror[$i] = "Option B must not be empty ";
                        }
                        if(!in_array($lineArray[6], $suggestcorrect)){
                            $csverror[$i] = "Correct option must be either A, B, C, D, E ";
                        }
    
                        // if($data2DArray[$i][$j]==""){
                        //     $data2DArray[$i][$j] = count($lineArray);
                        // }
                        }
                }
                $i++;
            }
            fclose($handle);
        }

        // return $csverror;
    // }
    if(count($csverror) > 0){
        return $csverror;
        // return printMultiDerror($csverror);
    }
}



if(count($errors) == 0){

    // if(in_array($file_type, $ext)){
    
$file = $_FILES['excelfile']['tmp_name'];
            
$errorInFile = ErrorArrayFromCsv($file, ",");
// echo count($errorInFile);
// exit();
if(count($errorInFile) > 0){
    // echo $errorInFile;
    echo json_encode([
    'status' => 'error',
    'data' => json_encode($errorInFile),
    'message'=>"OOPS !!!, Kindly rectify the following errors"
    ]);
}else{


$handle = fopen($file, "r");


            //   $no = 1;
            // echo '<div class="alert alert-success alert-dismissible">
            // <button type="button" class="close" data-dismiss="alert">&times;</button>
            // <p><strong style="font-size:1.4em">Success</strong><br>Your examination have uploaded successfully</p>.
            // </div>';
            //   echo '<table class="table table-bordered" >'; 
            //   $tfile = fgetcsv($handle, 1000, ",");
            $index =0;
            $succ = [];
              while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
            {
                // if($index > 0){
                    // $index++;
                    if($index > 0){
                    // return print_r(($filesop));
                    
             $questioni = $filesop[0];
              $optionas = $filesop[1]; 
              $optionbs = $filesop[2];
              $optioncs = $filesop[3]; 
              $optionds = $filesop[4]; 
              $optiones = $filesop[5]; 
              $corrects = $filesop[6]; 
              $marks = $filesop[7]; 


             
           
        if($questioni !=""){
            $files[$index][]=$filesop;
            // return print_r($files);
            $question = new Questions;
            $table = "questions";
            $questions  = $question->setQuestion($questioni);
              $optiona = $question->setQuestion($optionas);
              $optionb = $question->setQuestion($optionbs);
              $optionc = $optioncs?$question->setQuestion($optioncs):"";
              $optiond = $optionds?$question->setQuestion($optionds):"";
              $optione = $optiones?$question->setQuestion($optiones):"";
              $qtype = "file";
              $correctanswer = ucwords($question->test_input($corrects));
              $mark = $question->test_input($marks);
                 $examid = $_POST['examid'];
              $id = $question->tableid($table);
              $q  = $question->query("INSERT INTO questions(examinationid, questiontype, question, option_a, option_b, option_c, option_d, option_e, correct,  mark, questionid)
              VALUES('$examid', '$qtype', '$questions', '$optiona','$optionb','$optionc','$optiond','$optione','$correctanswer', '$mark','$id' )");
               $succ[] = "<tr>
                   <td>$index</td>
                   <td>$questions</td>
                   <td>$optiona</td>
                   <td>$optionb</td>
                   <td>$optionc</td>
                   <td>$optiond</td>
                   <td>$optione</td>
                   <td>$correctanswer</td>
                   <td>$mark</td>
                </tr>";
              }
            }
             $index++;
            // }
               }

               echo json_encode([
                'status' => 'success',
                'data' => json_encode($succ),
                'message'=>"Uploaded successfully"
                ]);
            
            //   echo '<table>';

}


}

// }

