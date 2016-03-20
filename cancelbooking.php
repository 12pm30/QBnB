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
if(isset($_POST['bookID_field'])){
	include_once 'config/connection.php';
	 
	$ableToCancel = 0;
	 
	$query = "SELECT booking_ID FROM booking WHERE booking_ID = ? AND member_ID = ? AND start_date > ?";
	
	if ($stmt = $con->prepare($query)){
		
		$present_date = date("Y-m-d");
		
		$stmt->bind_Param("sss", $_POST['bookID_field'], $_SESSION['id'], $present_date);

		$stmt->execute();
		
		$result = $stmt->get_result();
		
		$num = $result->num_rows;
		
		if ($num == 1){
			$ableToCancel = 1;
		}
		else {
			echo "Booking not found, or already begun";
			http_response_code(400);
			die();
		}
	}
	else {
		echo "SQL Prepare Failed.";
		http_response_code(500);
		die();
	}
	 
	if ($ableToCancel == 1){
		$query = "DELETE FROM booking WHERE booking_ID = ?";
			
			if ($stmt = $con->prepare($query)){
				
				$stmt->bind_Param("s", $_POST['bookID_field']);

				$stmt->execute();
				
				$rowsAffect = $con->affected_rows;
				
				if ($rowsAffect == 1){
					echo "Booking deleted.";
					http_response_code(200);
					die();
				}
				else {
					echo "Error deleting booking.";
					http_response_code(500);
				}
			}
			else {
				echo "SQL Prepare Failed.";
				http_response_code(500);
				die();
			}
	}
}
 ?>
<!DOCTYPE HTML>
<html>
<body>
 
<form name='bookDelForm' id='bookDelForm' action='cancelbooking.php' method='post'>
    <table border='0'>
        <tr>
			<td>Booking ID</td>
			<td><input type='number' name='bookID_field' id='bookID_field' 
			 <?php if (isset($_POST['bookID_field'])) echo 'value="'.$_POST['bookID_field'].'"';?>
			 /></td>
		</tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' id='cancelBookingBtn' name='cancelBookingBtn' value='Cancel Booking' /> 
            </td>
        </tr>
    </table>
</form>
 
</body>
</html>