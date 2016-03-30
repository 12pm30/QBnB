<?php
  //Create a user session or resume an existing one
 session_start();
 ?>
<?php
if(isset($_SESSION['id'])){
	include_once 'config/connection.php';
	
	$query = "SELECT booking_ID, property_ID, first_name, middle_initial, last_name, street_number, street_name, apt_number, city, province, postal_code, start_date, end_date FROM booking JOIN property USING (property_ID) JOIN member ON (property.member_ID = member.member_ID) WHERE end_date >= ? AND booking.member_ID = ? ORDER BY start_date";
	
	if ($stmt = $con->prepare($query)){
		
		$present_date = date("Y-m-d");
		
		$stmt->bind_Param("ss", $present_date, $_SESSION['id']);

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