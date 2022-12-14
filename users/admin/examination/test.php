<?php
include('../../../includes/connection.php');
$question = new Questions;
if(isset($_POST['submit'])){
$take = $_POST['qu'];
echo strlen($take);
// echo htmlentities($question->setQuestion($take));
echo "<br>Result <br>". htmlspecialchars($_POST['qu']). strlen(html_entity_decode($_POST['qu']));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include('header.php') ?>
</head>
<body>
    <div class="container">
        <div class="jumbotron">
            <form action="test.php" method="post">
            <textarea name="qu" id="textarea" class="textarea" placeholder="Place some text here" cols="30" rows="10">
        <?php if(isset($_POST['qu'])){
            echo htmlspecialchars_decode($_POST['qu']);
        } ?>    
        </textarea>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
</form>
            <?php
            // ax+by=C
            // dx+ey=F

            // x+y=20
            // 3x+4y=90

            $a =1;
            $b=1;
            $c=20;
            $d= 3;
            $e=4;
            $f=90;
            $nu = ($a*$f)-($d*$c);
            $den = ($a*$e)-($b*$d);
            $y = $nu/$den;
            $x = (($c-($b*$y))/$a);
            echo "<h1> Y = $y<br> X = $x</h1>";
            
            ?>
            
        </div>
    </div>
</body>
</html>
<?php include('script.php') ?>
