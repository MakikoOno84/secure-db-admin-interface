<?php
/*
dbconnect.php: connect database
-------------------------------------------------------------
*/
require_once("dbinfo.php");
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// determine if connection was successful
if( mysqli_connect_errno() != 0 ){
    die("<p>Could not connect to DB: ".$mysqli->connect_error."</p>");	
}
?>