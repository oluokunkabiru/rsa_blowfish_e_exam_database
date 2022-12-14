<?php
if(isset($_POST['src'])){
    $file = $_POST['src'];
    $folder = "questionsfiles/";

$name = $folder.basename($file);
unlink($name);
    // unlink($file);
}

?>