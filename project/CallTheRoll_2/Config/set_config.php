<?php
$num=$_POST['num'];
if(file_put_contents("Tarot.txt",$num)){
    echo 1;
}
?>