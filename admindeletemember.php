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

if($_SESSION['admin'] != 1){
	echo "Admin access only.";
	http_response_code(403);
	die();
}
?>
<?php
if(isset($_POST['member_ID_field'])){
	include_once 'config/connection.php';

	$query = "DELETE FROM member WHERE member_ID = ?";
	
	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("s", $_POST['member_ID_field']);

		$stmt-> execute();
		
		$result = $stmt->get_result();
		
		$rowsAffect = $con->affected_rows;
		
		if ($rowsAffect == 1){
			printf ("Member deleted successfully.");
            http_response_code(200);
			die();
		}
		else{
			printf ("Error deleting member.");
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

 <form name='deletememberForm' id='deletememberForm' action='admindeletemember.php' method='post'>
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
                <input type='submit' id='deleteBtn' name='deleteBtn' value='Delete Member' /> 
            </td>
        </tr>
    </table>
</form>

</body>
</html>