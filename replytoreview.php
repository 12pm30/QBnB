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
if(isset($_POST['reply_text_field']) and isset($_POST['member_to_reply']) and isset($_POST['property_to_view'])){
	include_once 'config/connection.php';
	
	$query = "UPDATE review SET reply_text = ? WHERE member_ID = ? and property_ID = ? AND property_ID in (SELECT property_ID from property WHERE member_ID = ?)";
	
	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("ssss", $_POST['reply_text_field'], $_POST['member_to_reply'], $_POST['property_to_view'], $_SESSION['id']);

		$stmt->execute();
		
		$rowsAffect = $con->affected_rows;
		
		if ($rowsAffect == 0){
			echo "Review not found.";
			http_response_code(400);
			die();
		}
		else {
			echo "Reply added/updated";
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
 
<form name='replyForm' id='replyForm' action='replytoreview.php' method='post'>
    <table border='0'>
        <tr>
			<td>Reply Text</td>
			<td><input type='text' name='reply_text_field' id='reply_text_field' 
			 <?php if (isset($_POST['reply_text_field'])) echo 'value="'.$_POST['reply_text_field'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Member ID</td>
			<td><input type='number' name='member_to_reply' id='member_to_reply' 
			 <?php if (isset($_POST['member_to_reply'])) echo 'value="'.$_POST['member_to_reply'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Property ID</td>
			<td><input type='number' name='property_to_view' id='property_to_view' 
			 <?php if (isset($_POST['property_to_view'])) echo 'value="'.$_POST['property_to_view'].'"';?>
			 /></td>
		</tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' id='replysubmitBtn' name='replysubmitBtn' value='Submit Reply' /> 
            </td>
        </tr>
    </table>
</form>
 
</body>
</html>