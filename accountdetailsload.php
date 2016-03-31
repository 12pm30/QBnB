<?php
  //Create a user session or resume an existing one
 session_start();
 ?>
<?php
if(isset($_SESSION['id'])){
	if (isset($_POST['member_ID_field'])){
		include_once 'config/connection.php';
		
		$query = "SELECT first_name, middle_initial, last_name, email, primary_phone, secondary_phone, profile_pic_URL FROM member WHERE member_ID = ?";
		
		if ($stmt = $con->prepare($query)){
			
			$stmt->bind_Param("s", $_POST['member_ID_field']);

			$stmt->execute();
			
			$result = $stmt->get_result();
			
			$num = $result->num_rows;
			
			if ($num == 1){
				$row = $result->fetch_assoc();
				$resarraymd = array();
				
				$resarraymd[] = $row;
			}
			else {
				echo "Error: Account not found.";
				http_response_code(500);
				die();
			}
		}
		else {
			echo "SQL Prepare Failed. (Acct. Info)";
			http_response_code(500);
			die();
		}
		
		$query = "SELECT year, faculty, type FROM degree WHERE member_ID = ?";
		
		if ($stmt = $con->prepare($query)){
			
			$stmt->bind_Param("s", $_POST['member_ID_field']);

			$stmt->execute();
			
			$result = $stmt->get_result();
			
			$resarrayd = array();
			
			while($row = $result->fetch_assoc()){
				$resarrayd[] = $row;	
			}	
		}
		else {
			echo "SQL Prepare Failed. (Deg. Info)";
			http_response_code(500);
			die();
		}
		
		$resarray = array();
		$resarray['details'] = $resarraymd;
		$resarray['degrees'] = $resarrayd;
		echo json_encode($resarray);
		
		http_response_code(200);
		die();
	}
}
else {
	echo "Not signed in.";
	http_response_code(401);
}
 ?>
<!DOCTYPE HTML>
<html>
<body>
 
<form name='detailsForm' id='detailsForm' action='accountdetailsload.php' method='post'>
    <table border='0'>
		<tr>
			<td>Member ID</td>
			<td><input type='number' name='member_ID_field' id='member_ID_field' 
			 <?php if (isset($_POST['member_ID_field'])) echo 'value="'.$_POST['member_ID_field'].'"';?>
			 /></td>
		</tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' id='lookupBtn' name='lookupBtn' value='Lookup Member' /> 
            </td>
        </tr>
    </table>
</form>
 
</body>
</html>