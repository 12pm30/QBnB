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
if(isset($_POST['property_to_view'])){
	include_once 'config/connection.php';
	
	$query = "SELECT first_name, middle_initial, last_name, street_number, street_name, apt_number, city, province, postal_code, num_bedrooms, num_bathrooms, accomodation_type, price, district FROM property NATURAL JOIN district NATURAL JOIN member WHERE property_ID = ?";
	
	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("s", $_POST['property_to_view']);

		$stmt->execute();
		
		$result = $stmt->get_result();
		
		$num = $result->num_rows;
		
		if ($num == 1){
			$row = $result->fetch_assoc();
			$resarraypd = $row;
		}
		else {
			echo "Error: Property not found.";
			http_response_code(500);
			die();
		}
	}
	else {
		echo "SQL Prepare Failed. (Prop. details)";
		http_response_code(500);
		die();
	}
	
	$query = "SELECT feature_name FROM feature NATURAL JOIN prop_feature WHERE property_ID = ?";
	
	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("s", $_POST['property_to_view']);

		$stmt->execute();
		
		$result = $stmt->get_result();
		
		$resarrayf = array();
		
		while ($row = $result->fetch_assoc()){
			$resarrayf[] = $row;
		}
	}
	else {
		echo "SQL Prepare Failed. (Features)";
		http_response_code(500);
		die();
	}
	
	
	$query = "SELECT start_date, end_date FROM booking WHERE property_ID = ? AND end_date > ?";
	
	if ($stmt = $con->prepare($query)){
		
		$present_date = date("Y-m-d");
		
		$stmt->bind_Param("ss", $_POST['property_to_view'], $present_date);

		$stmt->execute();
		
		$result = $stmt->get_result();
		
		$resarraya = array();
		
		while ($row = $result->fetch_assoc()){
			$resarraya[] = $row;
		}
	}
	else {
		echo "SQL Prepare Failed. (Availability)";
		http_response_code(500);
		die();
	}
	
	$query = "SELECT booking_id, start_date, end_date FROM booking WHERE property_ID = ? AND end_date > ? and member_ID = ?";
	
	if ($stmt = $con->prepare($query)){
		
		$present_date = date("Y-m-d");
		
		$stmt->bind_Param("sss", $_POST['property_to_view'], $present_date, $_SESSION['id']);

		$stmt->execute();
		
		$result = $stmt->get_result();
		
		$resarrayb = array();
		
		while ($row = $result->fetch_assoc()){
			$resarrayb[] = $row;
		}
	}
	else {
		echo "SQL Prepare Failed. (Bookings)";
		http_response_code(500);
		die();
	}
	
	$query = "SELECT caption, photo_URL FROM prop_photo WHERE property_ID = ?";
	
	if ($stmt = $con->prepare($query)){
		
		$present_date = date("Y-m-d");
		
		$stmt->bind_Param("s", $_POST['property_to_view']);

		$stmt->execute();
		
		$result = $stmt->get_result();
		
		$resarrayp = array();
		
		while ($row = $result->fetch_assoc()){
			$resarrayp[] = $row;
		}
	}
	else {
		echo "SQL Prepare Failed. (Photos)";
		http_response_code(500);
		die();
	}
	
	$query = "SELECT first_name, middle_initial, last_name, rating, review_text, reply_text FROM review NATURAL JOIN member WHERE property_ID = ?";
	
	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("s", $_POST['property_to_view']);

		$stmt->execute();
		
		$result = $stmt->get_result();
		
		$resarrayr = array();
		
		while ($row = $result->fetch_assoc()){
			$resarrayr[] = $row;
		}
	}
	else {
		echo "SQL Prepare Failed. (Reviews)";
		http_response_code(500);
		die();
	}
	
	$resarray = array();
	$resarray['details'] = $resarraypd;
	$resarray['features'] = $resarrayf;
	$resarray['availability'] = $resarraya;
	$resarray['bookings'] = $resarrayb;
	$resarray['photos'] = $resarrayp;
	$resarray['reviews'] = $resarrayr;
	
	echo json_encode($resarray);
	http_response_code(200);
	die();
}
 ?>
 <!DOCTYPE HTML>
<html>
<body>

 <form name='propviewcustomerForm' id='propviewcustomerForm' action='propertyviewcustomer.php' method='post'>
    <table border='0'>
		<tr>
            <td>Property ID</td>
            <td><input type='number' name='property_to_view' id='property_to_view' 
			<?php if (isset($_POST['property_to_view'])) echo 'value="'.$_POST['property_to_view'].'"';?>
			/></td>
        </tr>
		<tr>
            <td></td>
            <td>
                <input type='submit' id='showPropBtn' name='showPropBtn' value='View Property (as customer)' /> 
            </td>
        </tr>
    </table>
</form>

</body>
</html>