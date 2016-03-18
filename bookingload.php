<!DOCTYPE HTML>
<html>
<body>
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
		
		while ($row = $result->fetch_assoc()){
			printf("Booking ID: %s<br>Property ID: %s<br>First Name: %s<br>Middle Initial: %s<br>Last Name: %s<br>Street Number: %s<br>Street Name: %s<br>Apt. Number: %s<br>City: %s<br>Province: %s<br>Postal Code: %s<br>Start Date: %s<br>End Date: %s<br>", $row['booking_ID'],$row['property_ID'], $row['first_name'], $row['middle_initial'], $row['last_name'], $row['street_number'], $row['street_name'], $row['apt_number'], $row['city'], $row['province'], $row['postal_code'], $row['start_date'], $row['end_date']);
		}
	}
	else {
		echo "SQL Prepare Failed.";
	}
}
else {
	echo "Not signed in.";
}
 ?>
 
</body>
</html>