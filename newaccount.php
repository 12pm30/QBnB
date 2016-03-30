<?php
if(isset($_POST['first_name_field']) and isset($_POST['last_name_field']) and isset($_POST['email_field']) and isset($_POST['password_field']) and isset($_POST['primary_phone_field']) and isset($_POST['year_field']) and isset($_POST['faculty_field']) and isset($_POST['type_field'])){
	include_once 'config/connection.php';
	include_once 'config/mail.php';
    
	$error = 0;
	
	$con->begin_transaction();
	
	$query = "INSERT INTO member (`member_ID`, `first_name`, `middle_initial`, `last_name`, `email`, `password`, `primary_phone`, `secondary_phone`, `admin`, `supplier`, `profile_pic_URL`) VALUES (NULL, ?, ?, ?, ?, sha2(?,512), ?, ?, '0', '0', ?)";
	
	if ($stmt = $con->prepare($query)){
		
		if (empty($_POST['middle_initial_field'])){
			$_POST['middle_initial_field'] = NULL;
		}
		
		if (empty($_POST['secondary_phone_field'])){
			$_POST['secondary_phone_field'] = NULL;
		}
		
		if (empty($_POST['ppURL_field'])){
			$_POST['ppURL_field'] = NULL;
		}
		
		$stmt->bind_Param("ssssssss", $_POST['first_name_field'], $_POST['middle_initial_field'], $_POST['last_name_field'], $_POST['email_field'], $_POST['password_field'], $_POST['primary_phone_field'], $_POST['secondary_phone_field'], $_POST['ppURL_field']);

		if (!$stmt->execute()){
			printf ("Error: %s",$stmt->error);
			$con->rollback();
			$error = 1;
            http_response_code(400);
			die();
		}
		else{
			$memID = $con->insert_id;
		}
	}
	else {
		$con->rollback();
		$error = 1;
        http_response_code(500);
        echo "SQL Prepare Failed. (Account Information)";
        die();
	}
	
	if ($error == 0){
		$query = "INSERT INTO degree (`degree_ID`, `member_ID`, `year`, `faculty`, `type`) VALUES (NULL, ?,?,?,?)";
		
		if ($stmt = $con->prepare($query)){
			
			$stmt->bind_Param("ssss", $memID, $_POST['year_field'], $_POST['faculty_field'], $_POST['type_field']);

			if (!$stmt->execute()){
				$con->rollback();
				$error = 1;
                
                printf ("Error: %s",$stmt->error);
                http_response_code(400);
                die();
			}
		}
		else {
			$con->rollback();
			$error = 1;
            http_response_code(500);
            echo "SQL Prepare Failed. (Degree Information)";
            die();
		}
	}
	
	if ($error == 0){
		
        //Set who the message is to be sent to
        $mail->addAddress($_POST['email_field'], $_POST['first_name_field'] . " " . $_POST['last_name_field']);
        
        //Set the subject line
        $mail->Subject = 'QBnB Account Creation Success';
        
        //Set the message body
        $mail->Body = 'Hi ' . $_POST['first_name_field'] . " " .  $_POST['last_name_field'] . ",\n\nThank you for creating an account with QBnB! We are happy to have you on board with our Queen's exclusive housing sharing system! We hope you will have an excellent time using our service. You may log in with the following email and the password you specified.\n\nLogin Email: " . $_POST['email_field'] . "\n\nIf you have any questions, please send an email to cs@qbnb.ca.\n\nThanks,\n\nQBnB Team";
        
        
        
        //send the message, check for errors
        if (!$mail->send()) {
            $con->rollback();
            http_response_code(500);
            echo "Mailer Error: " . $mail->ErrorInfo;
            die();
        } else {
            $con->commit();
            http_response_code(200);
            echo("Successfully created account. Please log in with your email and password.");
            die();
        }
        
	}
}		 
 ?>
<!DOCTYPE HTML>
<html>
<body>

 <form name='newaccount' id='newaccount' action='newaccount.php' method='post'>
    <table border='0'>
		<tr>
		<td><b>Member Info</b></td>
		</tr>
        <tr>
            <td>First Name*</td>
            <td><input type='text' name='first_name_field' id='first_name_field' 
			<?php if (isset($_POST['first_name_field'])) echo 'value="'.$_POST['first_name_field'].'"';?>
			/></td>
        </tr>
        <tr>
            <td>Middle Initial</td>
             <td><input type='text' maxlength=1 name='middle_initial_field' id='middle_initial_field' 
			 <?php if (isset($_POST['middle_initial_field'])) echo 'value="'.$_POST['middle_initial_field'].'"';?>
			 /></td>
        </tr>
		<tr>
            <td>Last Name*</td>
             <td><input type='text' name='last_name_field' id='last_name_field' 
			 <?php if (isset($_POST['last_name_field'])) echo 'value="'.$_POST['last_name_field'].'"';?>
			 /></td>
        </tr>
		<tr>
            <td>Email*</td>
             <td><input type='email' name='email_field' id='email_field' 
			 <?php if (isset($_POST['email_field'])) echo 'value="'.$_POST['email_field'].'"';?>
			 /></td>
        </tr>
		<tr>
            <td>Password*</td>
             <td><input type='password' name='password_field' id='password_field' /></td>
        </tr>
		<tr>
            <td>Primary Phone*</td>
             <td><input type='number' name='primary_phone_field' id='primary_phone_field' 
			 <?php if (isset($_POST['primary_phone_field'])) echo 'value="'.$_POST['primary_phone_field'].'"';?>
			 /></td>
        </tr>
		<tr>
            <td>Secondary Phone</td>
             <td><input type='number' name='secondary_phone_field' id='secondary_phone_field' 
			 <?php if (isset($_POST['secondary_phone_field'])) echo 'value="'.$_POST['secondary_phone_field'].'"';?>
			 /></td>
        </tr>
		<tr>
            <td>Profile Picture URL</td>
             <td><input type='url' name='ppURL_field' id='ppURL_field' 
			 <?php if (isset($_POST['ppURL_field'])) echo 'value="'.$_POST['ppURL_field'].'"';?>
			 /></td>
        </tr>
		<tr>
			<td><b>Degree Info</b></td>
		</tr>
		<tr>
			<td>Year</td>
			<td><input type='number' name='year_field' id='year_field' 
			 <?php if (isset($_POST['year_field'])) echo 'value="'.$_POST['year_field'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Faculty</td>
			<td><input type='text' name='faculty_field' id='faculty_field' 
			 <?php if (isset($_POST['faculty_field'])) echo 'value="'.$_POST['faculty_field'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Type</td>
			<td><input type='text' name='type_field' id='type_field' 
			 <?php if (isset($_POST['type_field'])) echo 'value="'.$_POST['type_field'].'"';?>
			 /></td>
		</tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' id='newAcctBtn' name='newAcctBtn' value='Create Account' /> 
            </td>
        </tr>
    </table>
</form>

</body>
</html>