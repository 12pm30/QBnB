<?php
  //Create a user session or resume an existing one
 session_start();
?>
<?php
 if (!isset($_SESSION['id'])){
	echo "Not signed in.";
	http_response_code(401);
	die();
 }
 ?>
<?php 
include_once 'config/connection.php';
 
$query = "SELECT property_ID, first_name, middle_initial, last_name, street_number, street_name, apt_number, city, province, postal_code, rating, review_text FROM review JOIN (property NATURAL JOIN member) USING (property_ID) WHERE review.member_ID = ?";

if ($stmt = $con->prepare($query)){
	
	$stmt->bind_Param("s", $_SESSION['id']);

	$stmt->execute();
	
	$result = $stmt->get_result();
}
else {
	echo "SQL Prepare Failed.";
	http_response_code(500);
	die();
}
?>
<?php
		$dataarray = array();
		while ($row = $result->fetch_assoc()){
			$dataarray[] = $row;
		}
		
		echo json_encode($dataarray);
		http_response_code(200);
		die();
?>