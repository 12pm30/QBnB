<?php
  //Create a user session or resume an existing one
 session_start();
 ?>
<?php
if(isset($_SESSION['id'])){
	include_once 'config/connection.php';
	
	$query = "SELECT property_ID, street_number, street_name, apt_number, city, province, postal_code, enabled FROM property WHERE member_ID = ?";
	
	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("s", $_SESSION['id']);

		$stmt->execute();
		
		$result = $stmt->get_result();
		
		$num = $result->num_rows;
		
		$resarray = array();
		
		while ($row = $result->fetch_assoc()){
			$resarray[] = $row;
		}
		echo json_encode($resarray);
		http_response_code(200);
	}
	else {
		echo "SQL Prepare Failed.";
		http_response_code(500);
	}
}
else {
	echo "Not signed in.";
	http_response_code(401);
}
 ?>