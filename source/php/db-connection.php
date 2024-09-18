<?php
$conn = mysqli_connect('localhost','root','','chatapp');

if(!$conn){
    echo "Error connecting to database";
}
return $conn;
?>