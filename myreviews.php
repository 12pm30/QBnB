<?php
  //Create a user session or resume an existing one
 session_start();
?>
<?php
 if (!isset($_SESSION['id'])){
	echo "Not signed in.";
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
	
	$num = $result->num_rows;
	
	if ($num == 0){
		echo "No reviews found.";
		die();
	}
}
else {
	echo "SQL Prepare Failed.";
}
?>
<?php
		/*
		echo
		"<input type='hidden' name='numberReviews' id = 'numberReviews'
			value = '" . $num . "'
			/>";
		*/
			
		// create an array
		$dataarray = array();
		while ($row = $result->fetch_assoc()){
			$dataarray[] = $row;
		}
		
		echo json_encode($dataarray);
			
		/*
		$x = 1;
		
		while ($reviewrow = $result->fetch_assoc()) {
				echo 
		"<tr>
			<td><b>Review " . $x . "</b></td>
		</tr>
		<tr>
			<td>Property ID: " . $reviewrow['property_ID'] . "</td>
		</tr>
		<tr>
			<td>First Name: " . $reviewrow['first_name'] . "</td>
		</tr>
		<tr>
			<td>Middle Initial: " . $reviewrow['middle_initial'] . "</td>
		</tr>
		<tr>
			<td>Last Name: " . $reviewrow['last_name'] . "</td>
		</tr>
		<tr>
			<td>Street Number: " . $reviewrow['street_number'] . "</td>
		</tr>
		<tr>
			<td>Street Name: " . $reviewrow['street_name'] . "</td>
		</tr>
		<tr>
			<td>Apt Number: " . $reviewrow['apt_number'] . "</td>
		</tr>
		<tr>
			<td>City: " . $reviewrow['city'] . "</td>
		</tr>
		<tr>
			<td>Province: " . $reviewrow['province'] . "</td>
		</tr>
		<tr>
			<td>Postal Code: " . $reviewrow['postal_code'] . "</td>
		</tr>
		<tr>
			<td>Rating: " . $reviewrow['rating'] . "</td>
		</tr>
		<tr>
			<td>Review Text: " . $reviewrow['review_text'] . "</td>
		</tr>"
		;
				$x = $x + 1;
			}
			
		*/
?>