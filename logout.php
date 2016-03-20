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
	echo "Logged out successfully.";
	http_response_code(200);
	die();
}
else {
	echo "Not signed in.";
	http_response_code(401);
	die();
}
 ?>