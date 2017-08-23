<?php 

$hostname 		= "localhost";
$database_name 	= "training_database";
$username 		= "root";
$password 		= "";

$con= mysqli_connect($hostname,$username,$password,$database_name);
/* Checking to see if the connection is working. */
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

?>