<?php
if(isset($_POST['gen'])){
    $generator = ($_POST['generator']);
    $generator = strtotime("+$generator months");
    // echo "<br>hello ". date("Y-m-d H:i", $generator)."<br>";
    $generator = strrev(base64_encode($generator));
    echo $generator;
}


// $d= base64_decode("ZXhhbWluYXRpb24="); //strrev(base64_encode(strtotime("+2 weeks"))); //strrev(base64_encode(strtotime("+2 weeks")));
// echo "<br><br>".$d;


?>

<h1>hello</h1>
<?= base64_encode('<h2 class="text-center text-danger">Your activation key have expired <br> Kindly, contact admin on +234830584550 <br> to subscribe for another plan</h2>') ?>
<form action="gen.php" method="post">

<div class="form-group">
    <label for="sel1">Generator hhhh:</label>
    <select class="form-control" name="generator">
    <option value="3">One term</option>
    <option value="6">two term</option>
    <option value="12">One Season</option>
    <option value="24">Two Season</option>
    <option value="36">Three Season</option>
    </select>
    <p class="text-danger"></p>
    <button type="submit" name="gen">Gen</button>

</div>
</form>



