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
                $mail->Body = 'Hi ' . $row['first_name'] . ",\n\nWe recently recieved a request to reset your QBnB password. Please visit the link below to complete the request. If you didn't make a request, you can ignore this email.\n\nhttp://qbnb.ca/passwordreset.php?key=bafcf9caccec47008992e3b060e8dcec\n\nThanks,\n\nQBnB Team";
                
                
                
                //send the message, check for errors
                if (!$mail->send()) {
                    http_response_code(500);
                    echo "Mailer Error: " . $mail->ErrorInfo;
                    die();
                } else {
                    http_response_code(200);
                    echo "Password reset email sent!";
                    die();
                }
            }
            else {
                http_response_code(400);
                echo "Account not found.";
                die();
            }
        }
        else {
            echo "SQL Prepare Failed.";
        }
    }
    ?>

<!DOCTYPE HTML>
<html>
<body>


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