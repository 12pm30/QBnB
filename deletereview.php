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
if(isset($_POST['property_to_view'])){
	include_once 'config/connection.php';

	$query = "DELETE FROM review WHERE member_ID = ? AND property_ID = ?";
	
	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("ss", $_SESSION['id'], $_POST['property_to_view']);

		$stmt-> execute();
		
		$result = $stmt->get_result();
		
		$rowsAffect = $con->affected_rows;
		
		if ($rowsAffect == 1){
			printf ("Review deleted successfully.");
            http_response_code(200);
			die();
		}
		else{
			printf ("Error deleting review.");
			http_response_code(500);
			die();
		}
	}
	else {
        http_response_code(500);
        echo "SQL Prepare Failed.";
        die();
	}
}		 
 ?>
<!DOCTYPE HTML>
<html>
<body>

 <form name='deletereviewForm' id='deletereviewForm' action='deletereview.php' method='post'>
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
                <input type='submit' id='reviewBtn' name='reviewBtn' value='Delete Review' /> 
            </td>
        </tr>
    </table>
</form>

</body>
</html>