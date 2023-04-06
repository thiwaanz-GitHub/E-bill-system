<?php

$con = new mysqli('localhost','root','','web_test');

if($con->connect_error){
  $_SESSION['error'] = "Database Connection Error.";
  die('Connection Failed : ' .$con->connect_error);
}

?>