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
 if(isset($_POST['passValidate'])){
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
		$query = "DELETE FROM member WHERE member_ID = ?";
			
			if ($stmt = $con->prepare($query)){
				
				$stmt->bind_Param("s", $_SESSION['id']);

				$stmt->execute();
				
				$rowsAffect = $con->affected_rows;
				
				if ($rowsAffect == 0){
					echo "Member not found.";
					http_response_code(500);
					die();
				}
				else {
					echo "Member Deleted. <br>";
					$_SESSION['id']=null;
					$_SESSION['admin']=null;
					$_SESSION['supplier']=null;
					session_destroy();
					echo "Logged out.";
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
}
?>
<!DOCTYPE HTML>
<html>
<body>

 <form name='delAcct' id='delAcct' action='closeaccount.php' method='post'>
    <table border='0'>
		<tr>
			<td>Verify Password</td>
			<td>
				<input type='password' id='passValidate' name='passValidate' />
			</td>
		</tr>
        <tr>
            <td>
                <input type='submit' id='delAcctBtn' name='delAcctBtn' value='Delete Account' /> 
            </td>
        </tr>
    </table>
</form>
 
</body>
</html>