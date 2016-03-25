<?php
  //Create a user session or resume an existing one
 session_start();
 ?>
 <?php
if(isset($_SESSION['id'])){
	//Destroy the user's session.
	$_SESSION['id']=null;
	$_SESSION['admin']=null;
	$_SESSION['supplier']=null;
	session_destroy();
    http_response_code(200);
	echo "Logged out successfully.";
}
else {
    http_response_code(400);
	echo "Not signed in.";
}
 ?>