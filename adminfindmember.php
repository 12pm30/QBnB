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
if (isset($_POST['searchBtn'])){
	include_once 'config/connection.php';
	
	$query = "SELECT member_ID, first_name, middle_initial, last_name, email, primary_phone, secondary_phone, admin, supplier, profile_pic_URL FROM member WHERE 1 ";
	
	$x = 0;
	$paramtype = "";
	
	if (!empty($_POST['first_name_field'])){
		$query = $query . " AND first_name = ?";
		$paramarray[$x] = $_POST['first_name_field'];
		$paramtype = $paramtype . "s";
		$x += 1;
	}
	
	if (!empty($_POST['middle_initial_field'])){
		$query = $query . " AND middle_initial = ?";
		$paramarray[$x] = $_POST['middle_initial_field'];
		$paramtype = $paramtype . "s";
		$x += 1;
	}
	
	if (!empty($_POST['last_name_field'])){
		$query = $query . " AND last_name = ?";
		$paramarray[$x] = $_POST['last_name_field'];
		$paramtype = $paramtype . "s";
		$x += 1;
	}
	
	if (!empty($_POST['email_field'])){
		$query = $query . " AND email = ?";
		$paramarray[$x] = $_POST['email_field'];
		$paramtype = $paramtype . "s";
		$x += 1;
	}
	
	if (!empty($_POST['phone_num_field'])){
		$query = $query . " AND (primary_phone = ? OR secondary_phone = ?)";
		$paramarray[$x] = $_POST['phone_num_field'];
		$paramarray[$x+1] = $_POST['phone_num_field'];
		$paramtype = $paramtype . "ss";
		$x += 2;
	}
	
	if ($stmt = $con->prepare($query)){
		
		if ($x == 1){$stmt->bind_Param($paramtype, $paramarray[0]);}
		if ($x == 2){$stmt->bind_Param($paramtype, $paramarray[0], $paramarray[1]);}
		if ($x == 3){$stmt->bind_Param($paramtype, $paramarray[0], $paramarray[1], $paramarray[2]);}
		if ($x == 4){$stmt->bind_Param($paramtype, $paramarray[0], $paramarray[1], $paramarray[2], $paramarray[3]);}
		if ($x == 5){$stmt->bind_Param($paramtype, $paramarray[0], $paramarray[1], $paramarray[2], $paramarray[3], $paramarray[4]);}
		if ($x == 6){$stmt->bind_Param($paramtype, $paramarray[0], $paramarray[1], $paramarray[2], $paramarray[3], $paramarray[4], $paramarray[5]);}
		
		$stmt->execute();
					
		$result = $stmt->get_result();
		
		$num = $result->num_rows;
		
		$resarray = array();
		
		while ($row = $result->fetch_assoc()){
			$resarray[] = $row;
		}
		
		echo json_encode($resarray);
		http_response_code(200);
		die();
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

<form name='findmemberForm' id='findmemberForm' action='adminfindmember.php' method='post'>
    <table border='0'>
        <tr>
			<td>First Name</td>
			<td><input type='text' name='first_name_field' id='first_name_field' 
			 <?php if (isset($_POST['first_name_field'])) echo 'value="'.$_POST['first_name_field'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Middle Initial</td>
			<td><input type='text' name='middle_initial_field' id='middle_initial_field' 
			 <?php if (isset($_POST['middle_initial_field'])) echo 'value="'.$_POST['middle_initial_field'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Last Name</td>
			<td><input type='text' name='last_name_field' id='last_name_field' 
			 <?php if (isset($_POST['last_name_field'])) echo 'value="'.$_POST['last_name_field'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><input type='email' name='email_field' id='email_field' 
			 <?php if (isset($_POST['email_field'])) echo 'value="'.$_POST['email_field'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Phone Number</td>
			<td><input type='number' name='phone_num_field' id='phone_num_field' 
			 <?php if (isset($_POST['phone_num_field'])) echo 'value="'.$_POST['phone_num_field'].'"';?>
			 /></td>
		</tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' id='searchBtn' name='searchBtn' value='Search' /> 
            </td>
        </tr>
    </table>
</form>
 
</body>
</html>