<?php
header('Content-Type: application/json');
    include('../../includes/connection.php');
    $user =  new Users;
    $table = "users";
    $column = "username";
    $error = [];
    // echo $table. substr(strlen($column), 0, 1).substr(time(), -1);
    if(isset($_POST['addnewstudentbtn'])){
        if(empty($_POST['sname'])){
            $error['sname'] = "Student surname must not be empty";
        }
        if(empty($_POST['fname'])){
            $error['fname'] = "Student firstname must not be empty";
        }
        if(empty($_POST['lname'])){
            $error['lname'] ="Student Lastname must not be empty";
        }
        if(empty($_POST['class'])){
            $error['class'] ="Student class must not be empty";
        }
        if($_FILES['avatar']['size']==0){
            $error['avatar'] ="Please select student passport for physical identification";
          }
          if($_FILES['avatar']['size']!=0){
    
            $target_dir = "../students_avatar/";
            if(!is_dir($target_dir)){
                mkdir($target_dir);
            }
            $check = getimagesize($_FILES["avatar"]["tmp_name"]);
            if($check != true) {
                $error['avatar'] = "Image file is expected";
            }
            
            $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $image =$target_dir.str_replace(" ", "_", strtolower($_POST['sname']. $_POST['fname'].$_POST['lname'])."_".rand(10,1000))."_".time().".".$imageFileType;

            if ($_FILES["avatar"]["size"] > 5000000) {
                $error['avatar'] = "Sorry, your file is too large, upload file with less than 5MB";
                
            }elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $error[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }elseif(count($error)==0){
                move_uploaded_file($_FILES["avatar"]["tmp_name"], $image);
            }else{
              $error['avatar'] ="Please fill the require section";
            }
          }  
            
          if (count($error) > 0){
            echo json_encode([
                'status' => 'error',
                'data' => json_encode($error),
                
    
            ]);
        }
       
    //     foreach($error as $errors){
    //         echo $errors."<br>";
    // }

    if(count($error)==0){
         $username = strtolower($_POST['sname'][0]. $_POST['fname'][0].$_POST['lname']);
        if($user->checkExist($table, $column, $username) != ""){
            $username = str_replace(" ", "",$user->nextUsername($table, $column, $username));
        }
        $sname = $user->test_input($_POST['sname']);
        $fname = $user->test_input($_POST['fname']);
        $lname = $user->test_input($_POST['lname']);
        $class = $user->test_input($_POST['class']);
        $role = "student";
        $avatar = $image;
        $user->setPassword(strtolower($_POST['fname']) );
        $password = $user->password();
        $id = $user->tableid($table);
        $q = $user->query("INSERT INTO users(avatar, surname, firstname, lastname,username,role, password, userid) 
        VALUES('$avatar', '$sname', '$fname', '$lname', '$username','$role', '$password', '$id')");
        $student = new Users;
        $studentid = $student->tableid("student");
        $q = $student->query("INSERT INTO student(class, userid, studentid) VALUES('$class', '$id', '$studentid' ) ") ; 
        if($q){
            echo "<span class='text-success'>Student added successfully</span>";
        }else{
            echo "Class fail to added =". $user->connectionError();
        }
    }

    }





    if(isset($_POST['addnewstudentfilebtn'])){
        $errors = [];
            if($_FILES['studentfile']['size']==0){
                array_push($errors, "<span class='text-danger'>Please Select questions excel file</span>");
            }

            if($_FILES['studentfile']['size'] > 0){
                $target_file = basename($_FILES["studentfile"]["name"]);
               $file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
               $ext = array("csv");
               if(!in_array($file_type, $ext)){
                   array_push($errors, "<span class='text-danger'>Only excel format is required</span>");
               }
               }


               foreach($errors as $error){
                echo $error ."<br>";
            }

            if(count($errors) ==0){

                // if(in_array($file_type, $ext)){
                
                    $file = $_FILES['studentfile']['tmp_name'];
                        
            $errorInFile = ErrorArrayFromCsv($file, ",");
            if(!empty($errorInFile) ){
                echo $errorInFile;
            }else{
            
        //    return print_r(getClassId("Nursery 13"));
        //    exit;
            $handle = fopen($file, "r");
            
            
                        //   $no = 1;
                        echo '<div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <p><strong style="font-size:1.4em">Success</strong><br>Students uploaded successfully</p>.
                        </div>';
                          echo '<table class="table table-bordered" >'; 
                        //   $tfile = fgetcsv($handle, 1000, ",");
                        $index =0;
                          while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
                        {
                            // if($index > 0){
                                // $index++;
                                if($index > 0){
                                // return print_r(($filesop));
                                
                         $surname = $filesop[0];
                          $firstname = $filesop[1]; 
                          $lastname = $filesop[2];
                          $class = $filesop[3]; 
                          
            
                          $username = strtolower($surname[0]. $firstname[0].$lastname);
                          if($user->checkExist($table, $column, $username) != ""){
                              $username = str_replace(" ","",$user->nextUsername($table, $column, $username));
                          }
                          $sname = $user->test_input($surname);
                          $fname = $user->test_input($firstname);
                          $lname = $user->test_input($lastname);
                          $classid = $user->test_input(getClassId($class));
                         
                       
                    if($surname !=""){
                        $files[$index][]=$filesop;
                        $role = "student";
                        $avatar = "../students_avatar/default.png";
                        $user->setPassword(strtolower($firstname) );
                        $password = $user->password();
                        $id = $user->tableid($table);
                        $q = $user->query("INSERT INTO users(avatar, surname, firstname, lastname,username,role, password, userid) 
                        VALUES('$avatar', '$sname', '$fname', '$lname', '$username','$role', '$password', '$id')");
                        $student = new Users;
                        $studentid = $student->tableid("student");
                        $q = $student->query("INSERT INTO student(class, userid, studentid) VALUES('$classid', '$id', '$studentid' ) ") ; 
                        if($q){
                            echo "<span class='text-success'>Student added successfully</span>";
                        }else{
                            echo "Class fail to added =". $user->connectionError();
                        }
                        echo "<tr>
                               <td>$index</td>
                               <td>$surname</td>
                               <td>$firstname</td>
                               <td>$lastname</td>
                               <td>$username</td>
                               <td>$class</td>
                               
                            </tr>";
                          }
                        }
                         $index++;
                        // }
                           }
                        
                          echo '<table>';
            
            }
            
            
            }
            


    }

    function getClassId($classname){
        $class =  new Classes;
        $table = "class";
        $column = "name";
        
        if(!empty($class->checkExist($table, $column, $classname))){
            return $class->checkExist($table, $column, $classname)['classid'];
        }else{
            $name = $class->test_input($classname);
            $id = $class->tableid($table);
            $q = $class->query("INSERT INTO class (name, classid) VALUES('$name', '$id')");
            // return $q;
            if($q){
                $da = $class->query("SELECT * FROM $table ORDER BY id DESC LIMIT 0 , 1");
                $data = $class->data($da);
                return $data['classid'];
            }

        }

    }
    

    function ErrorArrayFromCsv($file, $delimiter) {
        if (($handle = fopen($file, "r")) !== FALSE) {
            $i = 0;
            // fgetcsv()
            $csverror =[];
            $data2DArray = array();
            while (($lineArray = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                for ($j = 0; $j < count($lineArray); $j++) {
                    if($i > 0){
                        // print_r($lineArray);
                        $data2DArray[$i]['surname'] = $lineArray[0];
                        $data2DArray[$i]['firstname'] = $lineArray[1];
                        $data2DArray[$i]['lastname'] = $lineArray[2];
                        $data2DArray[$i]['class'] = $lineArray[3];
                        
                        
                        if(empty($lineArray[0])){
                            $csverror[$i]['error'] = "Surname must not be empty";
                        }
                        if($lineArray[1]==""){
                            $csverror[$i]['error'] = "Firstname must be specify";
                        }
                       
                        if($lineArray[2]==""){
                            $csverror[$i]['error'] = " Lastname must not be empty  ";
                        }
                        if($lineArray[3]==""){
                            $csverror[$i]['error'] = "Class must not be empty ";
                        }
                        
                        }
                }
                $i++;
            }
            fclose($handle);
        }
    // }
    if(count($csverror) >0){
        return printMultiDerror($csverror);
    }
}


    function printMultiDerror($array){
        // Printing all the keys and values one by one
    $keys = array_keys($array);
    $ok =  '<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <p><strong style="font-size:1.4em">OOPS!</strong><br> Please resolve the below error(s)</p>.
    </div>';
    $ok .= '<table class="table table-bordered text-danger" >'; 
    for($i = 0; $i < count($array); $i++) {
        $ok .= '<tr>'; 
    
        $ok .= "<td>". $keys[$i]+1 . "</td>";
        foreach($array[$keys[$i]] as $key => $value) {
            $ok.=  "<td>" . $value . "</td>";
        }
        $ok.= "</tr>";
    }
    $ok.= "</table>";
    return $ok;
    
    }
