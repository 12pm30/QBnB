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
if(isset($_POST['enable_disable']) and isset($_POST['property_to_change_field'])){
	include_once 'config/connection.php';

	if ($_POST['enable_disable'] != "0" and $_POST['enable_disable'] != "1"){
		echo "Invalid status";
		http_response_code(400);
		die();
	}
	
	$query = "UPDATE property SET enabled = ? WHERE property_ID = ? AND member_ID = ?";
	
	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("sss", $_POST['enable_disable'], $_POST['property_to_change_field'], $_SESSION['id']);

		$stmt->execute();
		
		$rowsAffect = $con->affected_rows;
		
		if ($rowsAffect == 0){
			echo "Property not found.";
			http_response_code(400);
			die();
		}
		else {
			if ($_POST['enable_disable'] == "0") {
				echo "Property disabled.";
			}
			else {
				echo "Property enabled.";
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
 
<form name='enableDisableForm' id='enableDisableForm' action='enabledisableproperty.php' method='post'>
    <table border='0'>
        <tr>
			<td>Property ID</td>
			<td><input type='number' name='property_to_change_field' id='property_to_change_field' 
			 <?php if (isset($_POST['property_to_change_field'])) echo 'value="'.$_POST['property_to_change_field'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Enable/Disable (1/0)</td>
			<td><input type='number' name='enable_disable' id='enable_disable' 
			 <?php if (isset($_POST['enable_disable'])) echo 'value="'.$_POST['enable_disable'].'"';?>
			 /></td>
		</tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' id='enabledisableBtn' name='enabledisableBtn' value='Submit' /> 
            </td>
        </tr>
    </table>
</form>
 
</body>
</html>