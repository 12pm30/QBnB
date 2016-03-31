<?php
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

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
				
				$newPass = generateRandomString();
				
				$con->begin_transaction();
				
				$query = "UPDATE member SET password = sha2(?,512) WHERE email = ?";
		
				if ($stmt = $con->prepare($query)){
					
					$stmt->bind_Param("ss", $newPass, $_POST['email_field']);

					if(!$stmt->execute()){
						printf ("Error: %s",$stmt->error);
						$con->rollback();
						http_response_code(500);
						die();
					}
					else if ($con->affected_rows != 1){
						echo "Error updating password.";
						$con->rollback();
						http_response_code(500);
						die();
					}
				}
				else {
					echo "SQL Prepare Failed.";
					$con->rollback();
					http_response_code(500);
					die();
				}
                
                //Set who the message is to be sent to
                $mail->addAddress($row['email'], $row['first_name'] . " " . $row['last_name']);
                
                //Set the subject line
                $mail->Subject = 'QBnB Password Reset';
                
                //Set the message body
                $mail->Body = 'Hi ' . $row['first_name'] . ",\n\nWe recently received a request to reset your QBnB password.\n\nYour password has been reset to " . $newPass . "\n\nPlease change your password next time you login.\n\nThanks,\nQBnB Team";
                
                //send the message, check for errors
                if (!$mail->send()) {
                    http_response_code(500);
                    echo "Mailer Error: " . $mail->ErrorInfo;
					$con->rollback();
                    die();
                } else {
                    http_response_code(200);
					$con->commit();
                    echo "Password reset email sent!";
                    die();
                }
            }
            else {
                http_response_code(400);
				$con->rollback();
                echo "Account not found.";
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


<form name='forgotpassword' id='forgotpassword' action='forgotpassword.php' method='post'>
<table border='0'>
<tr>
<td>Email</td>
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