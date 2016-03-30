<?php
// used to connect to the database
$host = "localhost";
$db_name = "qbnb";
$username = "PUT USERNAME HERE"; // use your own username and password if different from mine
$password = "PUT PASSWORD HERE";

try {
	$con = mysqli_init();
	if (!$con){
		die("mysqli_init failed");
	}
	
	if (!mysqli_real_connect($con,$host,$username,$password,$db_name,3306,NULL,MYSQLI_CLIENT_FOUND_ROWS))
	{
		die("Connection Error: " . mysqli_connect_error());
	}
}
 
// show error
catch(Exception $exception){
    echo "Connection error: " . $exception->getMessage();
}

?>