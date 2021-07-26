<?php

$conn = mysqli_connect("localhost", "root", "", "channel");
 
// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 ?>