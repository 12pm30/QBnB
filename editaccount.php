<?php
  //Create a user session or resume an existing one
 session_start();
 ?>
<?php
if(isset($_SESSION['id'])){
	include_once 'config/connection.php';
	
	$query = "SELECT first_name, middle_initial, last_name, email, primary_phone, secondary_phone, profile_pic_URL FROM member WHERE member_ID = ?";
	
	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("s", $_SESSION['id']);

		$stmt->execute();
		
		$result = $stmt->get_result();
		
		$num = $result->num_rows;
		
		if ($num == 1){
			$memrow = $result->fetch_assoc();
		}
		else {
			echo "Error: Account not found.";
			http_response_code(500);
			die();
		}
	}
	else {
		echo "SQL Prepare Failed. (Load account)";
		http_response_code(500);
		die();
	}
	
	$query = "SELECT degree_ID, year, faculty, type FROM degree WHERE member_ID = ?";
	
	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("s", $_SESSION['id']);

		$stmt->execute();
		
		$degresult = $stmt->get_result();
		
		$numdegs = $degresult->num_rows;
		
		if ($numdegs <= 0){
			echo "Error: Degrees not found.";
			http_response_code(500);
			die();
		}
	}
	else {
		echo "SQL Prepare Failed. (Load degrees)";
		http_response_code(500);
		die();
	}
}
else {
	echo "Not signed in.";
	http_response_code(401);
	die();
}
 ?>
<?php 
if(isset($_POST['first_name_field']) and isset($_POST['last_name_field']) and isset($_POST['email_field']) and isset($_POST['primary_phone_field']) and isset($_POST['numberDegrees'])){
	$error = 0;
	for ($x = 1; $x <= $_POST['numberDegrees']; $x++){
		if (!isset($_POST['degID_' . $x]) or !isset($_POST['year_' . $x]) or !isset($_POST['faculty_' . $x]) or !isset($_POST['type_' . $x])){
			echo "Error: Degree data missing for entry " . $x;
			$error = 1;
			http_response_code(400);
			die();
		}
	}
	
	if (empty($_POST['middle_initial_field'])){
			$_POST['middle_initial_field'] = NULL;
	}
		
	if (empty($_POST['secondary_phone_field'])){
			$_POST['secondary_phone_field'] = NULL;
	}
		
	if (empty($_POST['ppURL_field'])){
			$_POST['ppURL_field'] = NULL;
	}

	if ($error == 0) {
		include_once 'config/connection.php';
		
		$con->begin_transaction();
	
		$query = "UPDATE member SET first_name = ?, middle_initial = ?, last_name = ?, email = ?, primary_phone = ?, secondary_phone = ?, profile_pic_URL = ? WHERE member_ID = ?";
		
		if ($stmt = $con->prepare($query)){
			
			$stmt->bind_Param("ssssssss", $_POST['first_name_field'], $_POST['middle_initial_field'], $_POST['last_name_field'], $_POST['email_field'], $_POST['primary_phone_field'], $_POST['secondary_phone_field'], $_POST['ppURL_field'], $_SESSION['id']);

			if(!$stmt->execute()){
				printf ("Error: %s",$stmt->error);
				$con->rollback();
				$error = 1;
				http_response_code(500);
				die();
			}
			else if ($con->affected_rows == 1){
				echo "Account update successful. <br>";
			}
			else{
				echo "Error updating account.";
				$con->rollback();
				$error = 1;
				http_response_code(500);
				die();
			}
		}
		else {
			echo "SQL Prepare Failed. (Update account)";
			$con->rollback();
			$error = 1;
			http_response_code(500);
			die();
		}
	}
	for ($x = 1; $x <= $_POST['numberDegrees']; $x++){
		if ($error == 0) {
		
			$query = "UPDATE degree SET year = ?, faculty = ?, type = ? WHERE degree_ID = ? and member_ID = ?";
			
			if ($stmt = $con->prepare($query)){
				
				$stmt->bind_Param("sssss", $_POST['year_' . $x], $_POST['faculty_' . $x], $_POST['type_' . $x], $_POST['degID_' . $x], $_SESSION['id']);

				if(!$stmt->execute()){
					printf ("Error: %s",$stmt->error);
					$con->rollback();
					$error = 1;
					http_response_code(500);
					die();
				}
				else if ($con->affected_rows == 1){
					echo "Degree " . $x . " update successful. <br>";
				}
				else {
					echo $con->affected_rows . "<br>";
					echo "Error updating degree " . $x;
					$error = 1;
					$con->rollback();
					http_response_code(500);
					die();
				}
			}
			else {
				echo "SQL Prepare Failed. (Update degree)";
				$con->rollback();
				$error = 1;
				http_response_code(500);
				die();
			}
		}
	}
	if ($error == 0){
		$con->commit();
		http_response_code(200);
		echo "All updates succeeded.";
		die();
	}
	else{
		$con->rollback();
		echo "Update failed.";
		http_response_code(500);
		die();
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
<title> Edit Account Details </title>
</head>
<body>

<style>
table,input {
	font-family: 'Open Sans', sans-serif;
}

input.rounded {
	border: 1px solid #ccc;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px;
	padding: 4px 7px;
	outline: 0;
	-webkit-appearance: none;
}
input.rounded:focus {
	border-color: #339933;
}
	

</style>

<form name='login' id='login' action='editaccount.php' method='post'>
	<table border='0'>
		<tr>
			<td>First Name*</td>
			<td><input type='text' class="rounded" name='first_name_field' id='first_name_field' 
			<?php echo 'value="'.$memrow['first_name'].'"';?>
			/></td>
		</tr>
		<tr>
			<td>Middle Initial</td>
			 <td><input type='text' class="rounded" maxlength=1 name='middle_initial_field' id='middle_initial_field' 
			 <?php echo 'value="'.$memrow['middle_initial'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Last Name*</td>
			 <td><input type='text' class="rounded" name='last_name_field' id='last_name_field' 
			 <?php echo 'value="'.$memrow['last_name'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Email*</td>
			 <td><input type='email' class="rounded" name='email_field' id='email_field' 
			 <?php echo 'value="'.$memrow['email'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Primary Phone*</td>
			 <td><input type='number' class="rounded" name='primary_phone_field' id='primary_phone_field' 
			 <?php echo 'value="'.$memrow['primary_phone'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Secondary Phone</td>
			 <td><input type='number' class="rounded" name='secondary_phone_field' id='secondary_phone_field' 
			 <?php echo 'value="'.$memrow['secondary_phone'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Profile Picture URL</td>
			 <td><input type='url' class="rounded" name='ppURL_field' id='ppURL_field' 
			 <?php echo 'value="'.$memrow['profile_pic_URL'].'"';?>
			 /></td>
		</tr>	
		<?php	
		echo
		"<input type='hidden' name='numberDegrees' id = 'numberDegrees'
			value = '" . $numdegs . "'
			/>";
		
		$x = 1;
		
		while ($degrow = $degresult->fetch_assoc()) {
				echo 
		"<tr>
			<td><b>Degree " . $x . "</b></td>
		</tr>
		<input type='hidden' name='degID_" . $x . "' id = 'degID_" . $x . "'
		value = '" . $degrow['degree_ID'] . "'
		/>
		<tr>
			<td>Year</td>
			 <td><input type='number' class=\"rounded\" name='year_" . $x . "' id='year_" . $x . "'
			 value = '" . $degrow['year'] . "'
			 /></td>
		</tr>
		<tr>
			<td>Faculty</td>
			 <td><input type='text' class=\"rounded\" name='faculty_" . $x . "' id='faculty_" . $x . "'
			 value = '" . $degrow['faculty'] . "'
			 /></td>
		</tr>
		<tr>
			<td>Type</td>
			 <td><input type='text' class=\"rounded\" name='type_" . $x . "' id='type_" . $x . "'
			 value = '" . $degrow['type'] . "'
			 /></td>
		</tr>"
		;
				$x = $x + 1;
			}
		?>
					
		
		<tr>
			<td></td>
			<td>
				<input type='submit' class="rounded" id='editAcctBtn' name='editAcctBtn' value='Save Changes' /> 
			</td>
		</tr>
	</table>
</form>
 
</body>
</html>