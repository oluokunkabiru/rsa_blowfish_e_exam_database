<?php
$return_value = "";

if ($_FILES['image']['name']) {
if (!$_FILES['image']['error']) {
$ext = explode('.', $_FILES['image']['name']);
$name =$ext[0]."_".time();
$filename = $name . '.' . $ext[1];
$folder = "questionsfiles/";
if(!is_dir($folder)){
    mkdir($folder);
}
$destination = $folder . $filename;
$location = $_FILES["image"]["tmp_name"];
move_uploaded_file($location, $destination);
$return_value = "../../".$destination;
}else{
$return_value = 'Ooops! Your upload triggered the following error: '.$_FILES['image']['error'];
}
}

echo $return_value;
?>