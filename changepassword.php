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
 if(isset($_POST['passValidate']) and isset($_POST['newPass'])){
	include_once 'config/connection.php';
	 
	$passValid = 0;
	 
	$query = "SELECT member_ID from member WHERE member_ID = ? and password=sha2(?,512)";
	
	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("ss", $_SESSION['id'], $_POST['passValidate']);

		$stmt->execute();
		
		$result = $stmt->get_result();
		
		$num = $result->num_rows;
		
		if ($num == 1){
			$passValid = 1;
		}
		else {
			echo "Incorrect password.";
			http_response_code(400);
			die();
		}
	}
	else {
		echo "SQL Prepare Failed.";
		http_response_code(500);
		die();
	}
	 
	if ($passValid == 1){
		$query = "UPDATE member SET password = sha2(?,512) WHERE member_ID = ?";
			
			if ($stmt = $con->prepare($query)){
				
				$stmt->bind_Param("ss", $_POST['newPass'], $_SESSION['id']);

				$stmt->execute();
				
				$rowsAffect = $con->affected_rows;
				
				if ($rowsAffect == 1){
					echo "Password updated successfully.";
					http_response_code(200);
					die();
				}
				else {
					echo "Error changing password.";
					http_response_code(500);
					die();
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

 <form name='chgPass' id='chgPass' action='changepassword.php' method='post'>
    <table border='0'>
		<tr>
			<td>Verify Current Password</td>
			<td>
				<input type='password' id='passValidate' name='passValidate' />
			</td>
		</tr>
		<tr>
			<td>New Password</td>
			<td>
				<input type='password' id='newPass' name='newPass' />
			</td>
		</tr>
        <tr>
            <td>
                <input type='submit' id='chgPassBtn' name='chgPassBtn' value='Change Password' /> 
            </td>
        </tr>
    </table>
</form>
 
</body>
</html>