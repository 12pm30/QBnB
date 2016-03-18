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
	
	$query = "SELECT * from district";
	
	if ($stmt = $con->prepare($query)){

		$stmt->execute();
		
		$result = $stmt->get_result();
		
		$num = $result->num_rows;
		
		printf("Districts: <br>");
		
		while ($row = $result->fetch_assoc()){
			printf("District ID: %s <br> District Title: %s <br>", $row['district_ID'], $row['district']);
		}
	}
	else {
		echo "SQL Prepare Failed.";
	}
	
	$query = "SELECT * from feature";
	
	if ($stmt = $con->prepare($query)){

		$stmt->execute();
		
		$result = $stmt->get_result();
		
		$num = $result->num_rows;
		
		printf("Features: <br>");
		
		while ($row = $result->fetch_assoc()){
			printf("Feature ID: %s <br> Feature Name: %s <br>", $row['feature_ID'], $row['feature_name']);
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