<?php

include("../../../includes/connection.php");
session_start();
    $examid = $_SESSION['examinationgoingon'];
    $studenid = $_SESSION['studentid'];
if (isset($_GET['answerid'])) {
    $answerid = $_GET['answerid'];

    $correctness = "";
    $exam = new Examination;
    $corr = $exam->query("SELECT questions.correct AS correct FROM questions JOIN studentexmination ON questions.questionid = studentexmination.questionid WHERE studentexmination.answerid ='$answerid'");
    $correct = $exam->data($corr);
    $correctanswer = $correct['correct'];
    if (!empty($_GET['answer'])) {
        $answer = $_GET['answer'];
        $answerstatus = "answered";
        if ($answer == $correctanswer) {
            $correctness = "correct";
        } else {
            $correctness = "wrong";
        }

        // echo "Correct = $correctanswer and Choose = $answer Correct Status = $correctness";
        $message =[];
        $ans = $exam->query("UPDATE studentexmination SET selectedoption='$answer',correctness='$correctness', answerstatus='$answerstatus' WHERE answerid='$answerid' ");
        if($ans){
            $curren = $exam->query("SELECT questions.id as qid, studentexmination.id as sqid FROM questions JOIN studentexmination ON questions.questionid = studentexmination.questionid WHERE 
            studentexmination.answerid='$answerid'");
                $curentid = $exam->data($curren);
                $currentid = $curentid['sqid'];
                // next button
                $que = $exam->query("SELECT studentexmination.id AS sqid, studentexmination.answerid AS answerid, questions.questionid as qid FROM studentexmination 
           JOIN questions ON studentexmination.questionid = questions.questionid WHERE studentexmination.studentid ='$studenid' 
           AND studentexmination.examinationid ='$examid' AND studentexmination.id > $currentid ORDER BY studentexmination.id ASC LIMIT 1");
    
                $next = $exam->data($que);
                $answerid = $next['answerid'];
                $message['next'] = $answerid;
                $message['message'] = "Answered successfully";
                echo json_encode($message);

                // header("location:conductingexamination.php?nextquestions=$answerid");
        }
    }
}





?>



<?php

// include("../../../includes/connection.php");

// if (isset($_GET['chooseanswer'])) {
//     $answerid = $_GET['answerid'];
//     $correctness = "";
//     $co = new Examination;
//     $corr = $co->query("SELECT questions.correct AS correct FROM questions JOIN studentexmination ON questions.questionid = studentexmination.questionid WHERE studentexmination.answerid ='$answerid'");
//     $correct = $co->data($corr);
//     $correctanswer = $correct['correct'];
//     if (!empty($_GET['answer'])) {
//         $answer = $_GET['answer'];
//         $answerstatus = "answered";
//         if ($answer == $correctanswer) {
//             $correctness = "correct";
//         } else {
//             $correctness = "wrong";
//         }

//         // echo "Correct = $correctanswer and Choose = $answer Correct Status = $correctness";
//         $ans = $exam->query("UPDATE studentexmination SET selectedoption='$answer',correctness='$correctness', answerstatus='$answerstatus' WHERE answerid='$answerid' ");
//         if ($ans) {
//         $curren = $exam->query("SELECT questions.id as qid, studentexmination.id as sqid FROM questions JOIN studentexmination ON questions.questionid = studentexmination.questionid WHERE 
//         studentexmination.answerid='$answerid'");
//             $curentid = $exam->data($curren);
//             $currentid = $curentid['sqid'];
//             // next button
//             $que = $exam->query("SELECT studentexmination.id AS sqid, studentexmination.answerid AS answerid, questions.questionid as qid FROM studentexmination 
//        JOIN questions ON studentexmination.questionid = questions.questionid WHERE studentexmination.studentid ='$studenid' 
//        AND studentexmination.examinationid ='$examid' AND studentexmination.id > $currentid ORDER BY studentexmination.id ASC LIMIT 1");

//             $next = $exam->data($que);
//             $answerid = $next['answerid'];
//             header("location:conductingexamination.php?nextquestions=$answerid");
//         } else {
//             echo "fail to anseer";
//         }
//     } else {
//         // next button    
//         $curren = $exam->query("SELECT questions.id as qid, studentexmination.id as sqid FROM questions JOIN studentexmination ON questions.questionid = studentexmination.questionid WHERE 
//     studentexmination.answerid='$answerid'");
//         $curentid = $exam->data($curren);
//         $currentid = $curentid['sqid'];

//         $que = $exam->query("SELECT studentexmination.id AS sqid, studentexmination.answerid AS answerid, questions.questionid as qid FROM studentexmination 
//    JOIN questions ON studentexmination.questionid = questions.questionid WHERE studentexmination.studentid ='$studenid' 
//    AND studentexmination.examinationid ='$examid' AND studentexmination.id > $currentid ORDER BY studentexmination.id ASC LIMIT 1");

//         $next = $exam->data($que);
//         $answerid = $next['answerid'];
//         header("location:conductingexamination.php?nextquestions=$answerid");
//     }
// }
?>