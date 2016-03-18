<!DOCTYPE HTML>
<html>
<body>
 
 <?php
if(isset($_POST['email_field'])){
	include_once 'config/connection.php';
	include_once 'config/mail.php';
	
	$query = "SELECT first_name, last_name, email from member WHERE email = ?";
	
	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("s", $_POST['email_field']);

		$stmt->execute();
		
		$result = $stmt->get_result();
		
		$num = $result->num_rows;
		
		if ($num == 1){
			$row = $result->fetch_assoc();
			
			//Set who the message is to be sent to
			$mail->addAddress($row['email'], $row['first_name'] . " " . $row['last_name']);

			//Set the subject line
			$mail->Subject = 'QBnB Password Reset';

			//Set the message body
			$mail->Body = 'Temporary message body...';

			//send the message, check for errors
			if (!$mail->send()) {
				echo "Mailer Error: " . $mail->ErrorInfo;
			} else {
				echo "Password reset email sent!";
				die();
			}
		}
		else {
			echo "Account not found.";
		}
	}
	else {
		echo "SQL Prepare Failed.";
	}
}		 
 ?>

 <form name='forgotpassword' id='forgotpassword' action='forgotpassword.php' method='post'>
    <table border='0'>
		<tr>
            <td>Email*</td>
             <td><input type='email' name='email_field' id='email_field' 
			 <?php if (isset($_POST['email_field'])) echo 'value="'.$_POST['email_field'].'"';?>
			 /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' id='resetPassBtn' name='resetPassBtn' value='Reset Password' /> 
            </td>
        </tr>
    </table>
</form>

</body>
</html>