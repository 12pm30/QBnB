<?php
  //Create a user session or resume an existing one
 session_start();
 ?>
<?php
if(isset($_SESSION['id'])){
	$resarray = array();
    $resarray['id'] = $_SESSION['id'];
	$resarray['admin'] = $_SESSION['admin'];
	$resarray['supplier'] = $_SESSION['supplier'];
	echo json_encode($resarray);
	http_response_code(200);
	die();
}
else {
	echo "Not signed in.";
	http_response_code(401);
	die();
}
 ?>