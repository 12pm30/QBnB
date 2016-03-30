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
 if(isset($_POST['delID_field'])){
	 include_once 'config/connection.php';	 
	 
	$query = "DELETE FROM property WHERE member_ID = ? and property_ID = ?";
		
	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("ss", $_SESSION['id'], $_POST['delID_field']);

		$stmt->execute();
		
		$rowsAffect = $con->affected_rows;
		
		if ($rowsAffect == 0){
			echo "Property not found.";
			http_response_code(400);
			die();
		}
		else {
			echo "Property Deleted.";
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

 <form name='delProp' id='delProp' action='deleteproperty.php' method='post'>
    <table border='0'>
        <tr>
			<td>Property to Delete</td>
			<td><input type='number' name='delID_field' id='delID_field' 
			 <?php if (isset($_POST['delID_field'])) echo 'value="'.$_POST['delID_field'].'"';?>
			 /></td>
		</tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' id='delPropBtn' name='delPropBtn' value='Delete Property' /> 
            </td>
        </tr>
    </table>
</form>
 
</body>
</html>