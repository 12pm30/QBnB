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
			$row = $result->fetch_assoc();
			$resarray = array();
			
			$resarray[] = $row;	
			echo json_encode($resarray);	
		}
		else {
			echo "Error: Account not found.";
			http_response_code(500);
		}
	}
	else {
		echo "SQL Prepare Failed. (Acct. Info)";
		http_response_code(500);
	}
	
	$query = "SELECT year, faculty, type FROM degree WHERE member_ID = ?";
	
	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("s", $_SESSION['id']);

		$stmt->execute();
		
		$result = $stmt->get_result();
		
		$resarray = array();
		
		while($row = $result->fetch_assoc()){
			$resarray[] = $row;	
		}
		
		echo json_encode($resarray);	
	}
	else {
		echo "SQL Prepare Failed. (Deg. Info)";
		http_response_code(500);
	}
}
else {
	echo "Not signed in.";
	http_response_code(401);
}
 ?>