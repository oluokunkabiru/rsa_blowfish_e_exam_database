<?php
    include('../../includes/connection.php');
    $class =  new Classes;
    $table = "class";
    $column = "name";
    $error = [];
    if(isset($_POST['name'])){
        if(empty($_POST['name'])){
            array_push($error, "Class name must not be empty");
        }
        if($class->checkExist($table, $column, $_POST['name']) != ""){
            array_push($error, "This class already exist, will you like to update it?");
        }
        foreach($error as $errors){
            echo $errors."<br>";
    }

    if(count($error)==0){
        $name = $class->test_input($_POST['name']);
        $id = $class->tableid($table);
        $q = $class->query("INSERT INTO class(name, classid) VALUES('$name', '$id')");
        // $d = $class->data($q);
        // INSERT INTO class(id, name, classid, reg_date) VALUES('','$name', '$id', '' ";
        if($q){
            echo "<span class='text-success'>Class added successfully</span>";
        }else{
            echo "Class fail to added =". $class->connectionError();
        }
    }

    }
?>