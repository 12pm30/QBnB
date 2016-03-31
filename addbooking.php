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
?>
<?php
if(isset($_POST['property_to_view']) and isset($_POST['sdate_field']) and isset($_POST['edate_field'])){
	include_once 'config/connection.php';

	$query = "SELECT a.property_ID FROM property a JOIN booking b USING (property_ID) WHERE (start_date >= ? AND end_date <= ?) OR (start_date <= ? AND end_date > ?) OR (start_date < ? AND end_date >= ?) OR (start_date <= ? AND end_date >= ?) AND property_ID = ?";
	
	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("sssssssss", $_POST['sdate_field'], $_POST['edate_field'], $_POST['sdate_field'], $_POST['sdate_field'],  $_POST['edate_field'],  $_POST['edate_field'], $_POST['sdate_field'],  $_POST['edate_field'], $_POST['property_to_view']);

		$stmt-> execute();
		
		$result = $stmt->get_result();
		
		$num = $result->num_rows;
		
		if ($num != 0){
			printf ("Conflicting booking found.");
            http_response_code(400);
			die();
		}
	}
	else {
        http_response_code(500);
        echo "SQL Prepare Failed. (Check)";
        die();
	}
	
	$query = "INSERT INTO booking (`booking_ID`, `member_ID`, `property_ID`, `start_date`, `end_date`, `status`) VALUES (NULL, ?, ?, ?, ?, '1')";
	
	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("ssss", $_SESSION['id'], $_POST['property_to_view'], $_POST['sdate_field'], $_POST['edate_field']);

		$stmt-> execute();
		
		$rowsAffect = $con->affected_rows;
		
		if ($rowsAffect == 1){
			printf ("Booking successful.");
            http_response_code(200);
			die();
		}
		else{
			printf ("Error adding booking.");
			http_response_code(500);
			die();
		}
	}
	else {
        http_response_code(500);
        echo "SQL Prepare Failed. (Booking)";
        die();
	}
}		 
 ?>
<!DOCTYPE HTML>
<html>
<body>

 <form name='addbookingForm' id='addbookingForm' action='addbooking.php' method='post'>
    <table border='0'>
		<tr>
            <td>Property ID</td>
            <td><input type='number' name='property_to_view' id='property_to_view' 
			<?php if (isset($_POST['property_to_view'])) echo 'value="'.$_POST['property_to_view'].'"';?>
			/></td>
        </tr>
        <tr>
            <td>Start Date</td>
            <td><input type='text' name='sdate_field' id='sdate_field' 
			<?php if (isset($_POST['sdate_field'])) echo 'value="'.$_POST['sdate_field'].'"';?>
			/></td>
        </tr>
        <tr>
            <td>End Date</td>
             <td><input type='text' name='edate_field' id='edate_field' 
			 <?php if (isset($_POST['edate_field'])) echo 'value="'.$_POST['edate_field'].'"';?>
			 /></td>
        </tr>
		<tr>
            <td></td>
            <td>
                <input type='submit' id='bookBtn' name='bookBtn' value='Create Booking' /> 
            </td>
        </tr>
    </table>
</form>

</body>
</html>