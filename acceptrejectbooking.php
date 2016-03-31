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
if(isset($_POST['confirm_reject']) and isset($_POST['booking_to_edit'])){
	include_once 'config/connection.php';

	if ($_POST['confirm_reject'] != "CONFIRMED" and $_POST['confirm_reject'] != "REJECTED"){
		echo "Invalid status";
		http_response_code(400);
		die();
	}
	
	$query = "UPDATE booking SET status = ? WHERE booking_ID = ? and status = \"PENDING\" AND property_ID in (SELECT property_ID from property WHERE member_ID = ?)";
	
	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("sss", $_POST['confirm_reject'], $_POST['booking_to_edit'], $_SESSION['id']);

		$stmt->execute();
		
		$rowsAffect = $con->affected_rows;
		
		if ($rowsAffect == 0){
			echo "Booking not found.";
			http_response_code(400);
			die();
		}
		else {
			if ($_POST['confirm_reject'] == "CONFIRMED") {
				echo "Booking confirmed.";
			}
			else {
				echo "Booking rejected.";
			}
			http_response_code(200);
			die();
		}
	}
	else {
		echo "SQL Prepare Failed.";
		http_response_code(500);
		die();
	}
}
 ?>
<!DOCTYPE HTML>
<html>
<body>
 
<form name='acceptRejectForm' id='acceptRejectForm' action='acceptrejectbooking.php' method='post'>
    <table border='0'>
        <tr>
			<td>CONFIRMED or REJECTED</td>
			<td><input type='text' name='confirm_reject' id='confirm_reject' 
			 <?php if (isset($_POST['confirm_reject'])) echo 'value="'.$_POST['confirm_reject'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Booking ID</td>
			<td><input type='number' name='booking_to_edit' id='booking_to_edit' 
			 <?php if (isset($_POST['booking_to_edit'])) echo 'value="'.$_POST['booking_to_edit'].'"';?>
			 /></td>
		</tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' id='arsubmitBtn' name='arsubmitBtn' value='Submit' /> 
            </td>
        </tr>
    </table>
</form>
 
</body>
</html>