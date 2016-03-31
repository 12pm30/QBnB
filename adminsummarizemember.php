<!DOCTYPE HTML>
<html>
<head>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
</head>
<body>
<style>
p{
	font-family: 'Open Sans', sans-serif;
}



table,input {
	font-family: 'Open Sans', sans-serif;
}

input.rounded {
	border: 1px solid #ccc;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px;
	padding: 4px 7px;
	outline: 0;
	-webkit-appearance: none;
}
input.rounded:focus {
	border-color: #339933;
}

span.tab{
    padding: 0 36px;
}

</style>
<?php
  //Create a user session or resume an existing one
 session_start();
 ?>
<?php
if(!isset($_SESSION['id'])){
	echo "Not signed in.";
	http_response_code(401);
	die();
}

if($_SESSION['admin'] != 1){
	echo "Admin access only.";
	http_response_code(403);
	die();
}

?>
<?php
if (isset($_GET['member_ID_field'])){
	include_once 'config/connection.php';
		
	$query = "SELECT member_ID, first_name, middle_initial, last_name, email, primary_phone, secondary_phone, admin, supplier FROM member WHERE member_ID = ?";

	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("s", $_GET['member_ID_field']);

		$stmt->execute();
		
		$result = $stmt->get_result();
		
		$num = $result->num_rows;
		
		if ($num != 1){
			echo "
			<p>
			 <b>Invalid member ID.</b>
			</p>";
		}
		else{
			$row = $result->fetch_assoc();
			if (!empty($row['middle_initial'])){
				$row['middle_initial'] = $row['middle_initial'] . " ";
			}
			echo "<p>
			<b>Member ID: " . $row['member_ID'] . "<br>\n"
			."User Details</b><br>\n"
			."Name: " . $row['first_name'] . " " . $row['middle_initial'] . $row['last_name'] . "<br>\n"
			."Email: " . $row['email'] . "<br>\n"
			."Primary Phone: " . $row['primary_phone'] . "<br>\n";
			
			if (!empty($row['secondary_phone'])){
				echo "Secondary Phone: " . $row['secondary_phone'] . "<br>\n";
			}
			if ($row['admin'] == 1){
				echo "User is an admin <br>\n";
			}
			if ($row['supplier'] == 1){
				echo "User is a supplier <br>\n";
				$supplier = 1;
			}
			else {
				$supplier = 0;
			}
			echo "</p>\n";
		}
	}
	else {
		echo "SQL Prepare Failed. (Member info)";
		http_response_code(500);
		die();
	}
	
	
	$query = "SELECT first_name, middle_initial, last_name, street_number, street_name, apt_number, city, province, postal_code, start_date, end_date FROM booking JOIN property USING (property_ID) JOIN member ON (property.member_ID = member.member_ID) WHERE booking.member_ID = ? ORDER BY start_date";

	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("s", $_GET['member_ID_field']);

		$stmt->execute();
		
		$result = $stmt->get_result();
		
		$num = $result->num_rows;
		
		echo "<b> Bookings Made: </b> <br>\n";
		
		if ($num == 0){
			echo "
			<p>
			 No Bookings.
			</p>";
		}
		else{
			while ($row = $result->fetch_assoc()){
				if (!empty($row['middle_initial'])){
					$row['middle_initial'] = $row['middle_initial'] . " ";
				}
				if (!empty($row['apt_number'])){
					$row['apt_number'] = "Apt. " . $row['apt_number'] . " ";
				}
				echo "<p>
				Owner: " . $row['first_name'] . " " . $row['middle_initial'] . $row['last_name'] . "<br>\n"
				."Location: " . $row['street_number'] . " " . $row['street_name'] . " " . $row['apt_number'] . "<br>\n"
				. "<span class=\"tab\"></span>"
				. $row['city'] . ", " . $row['province'] . " " . $row['postal_code'] . "<br>\n"
				."Dates: " . $row['start_date'] . " to " . $row['end_date'];
				
				echo "</p>\n";
			}
		}
	}
	else {
		echo "SQL Prepare Failed. (Bookings Made)";
		http_response_code(500);
		die();
	}
	
	if ($supplier == 1){
	
		$query = "SELECT property.property_ID, street_number, street_name, apt_number, city, province, postal_code, num_bedrooms, num_bathrooms, accomodation_type, price, district, COUNT(booking_ID), COUNT(rating), AVG(rating) FROM booking JOIN property USING (property_ID) NATURAL JOIN district LEFT JOIN review ON (booking.member_ID = review.member_ID and property.property_ID = review.property_ID) WHERE property.member_ID = ? GROUP BY property.property_ID";

		if ($stmt = $con->prepare($query)){
			
			$stmt->bind_Param("s", $_GET['member_ID_field']);

			$stmt->execute();
			
			$result = $stmt->get_result();
			
			$num = $result->num_rows;
			
			echo "<b> Bookings and Ratings Received: </b> <br>\n";
			
			if ($num == 0){
				echo "
				<p>
				 No Bookings.
				</p>";
			}
			else{
				while ($row = $result->fetch_assoc()){
					if (!empty($row['apt_number'])){
						$row['apt_number'] = "Apt. " . $row['apt_number'] . " ";
					}
					echo "<p>
					Property ID: " . $row['property_ID'] . "<br>\n"
					."District: " . $row['district'] . "<br>\n"
					."Location: " . $row['street_number'] . " " . $row['street_name'] . " " . $row['apt_number'] . "<br>\n"
					. "<span class=\"tab\"></span>"
					. $row['city'] . ", " . $row['province'] . " " . $row['postal_code'] . "<br>\n"
					."Bathrooms: " . $row['num_bathrooms'] . "<br>\n"
					."Bedrooms: " . $row['num_bedrooms'] . "<br>\n"
					."Type: " . $row['accomodation_type'] . "<br>\n"
					."Price: $" . $row['price'] . "<br>\n"
					."Number of Bookings: " . $row['COUNT(booking_ID)'] . "<br>\n"
					."Number of Reviews: " . $row['COUNT(rating)'] . "<br>\n";
					
					if (!empty($row['AVG(rating)'])){
						echo "Average Rating: " . $row['AVG(rating)'];
					}
					
					echo "</p>\n";
				}
			}
		}
		else {
			echo "SQL Prepare Failed. (Supplier info)";
			http_response_code(500);
			die();
		}
	}
	die();
}
?>
<form name='summmemberForm' id='summmemberForm' action='adminsummarizemember.php' method='get'>
    <table border='0'>
        <tr>
			<td>Member ID</td>
			<td><input type='number' name='member_ID_field' id='member_ID_field' 
			 <?php if (isset($_GET['member_ID_field'])) echo 'value="'.$_GET['member_ID_field'].'"';?>
			 /></td>
		</tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' id='summBtn' name='summBtn' value='Summarize' /> 
            </td>
        </tr>
    </table>
</form>
 
</body>
</html>