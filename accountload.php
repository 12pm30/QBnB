<!DOCTYPE HTML>
<html>
<body>
  <?php
  //Create a user session or resume an existing one
 session_start();
 ?>
 <?php
if(isset($_SESSION['id'])){
	include_once 'config/connection.php';
	
	$query = "SELECT profile_pic_URL, first_name, middle_initial, last_name FROM member WHERE member_ID = ?";
	
	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("s", $_SESSION['id']);

		$stmt->execute();
		
		$result = $stmt->get_result();
		
		$num = $result->num_rows;
		
		if ($num == 1){
			$row = $result->fetch_assoc();
			
			printf("First Name: %s <br> Middle Initial: %s <br> Last name: %s <br> Profile Picture: %s <br> Admin: %d", $row['first_name'], $row['middle_initial'], $row['last_name'], $row['profile_pic_URL'], $_SESSION['admin']);
			
		}
		else {
			echo "Error: Account not found.";
		}
	}
	else {
		echo "SQL Prepare Failed.";
	}
}
else {
	echo "Not signed in.";
}
 ?>
 
</body>
</html>