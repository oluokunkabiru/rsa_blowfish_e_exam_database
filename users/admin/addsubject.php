<?php
include('../../includes/connection.php');
    if(isset($_POST['addnewsubjectbtn'])){
        $subject = new Subject;
        $table = "subjects";
        $sub = "subjectname";
        $clas = "class";
        $errors = [];
        if(empty($_POST['name'])){
            array_push($errors, "Subject name must not be empty");
        }

        if(empty($_POST['class'])){
            array_push($errors, "Class must be select");
        }
        if(($subject->checkSubjectExist($_POST['name'], $_POST['class']))){
            array_push($errors, "This class already exists");
        }
        foreach($errors as $error){
            echo $error . "<br>";
        }
        if(count($errors)==0){
            $name = $subject->test_input($_POST['name']);
            $class = $subject->test_input($_POST['class']);
            $id = $subject->tableid($table);
            $q = $subject->query("INSERT INTO subjects(subjectname, classid, subjectid) VALUES('$name', '$class', '$id')");
            if($q){
                echo "<span class='text-success'>Subject added successfully</span>";
            }else{
                echo "Fail to insert". $subject->connectionError();
            }
        }
    }
?>