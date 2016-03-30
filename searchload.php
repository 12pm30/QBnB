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
		
		$resarrayd = array();
		
		while ($row = $result->fetch_assoc()){
			$resarrayd[] = $row;
		}
	}
	else {
		echo "SQL Prepare Failed. (District)";
		http_response_code(500);
		die();
	}
	
	$query = "SELECT * from feature";
	
	if ($stmt = $con->prepare($query)){

		$stmt->execute();
		
		$result = $stmt->get_result();
		
		$num = $result->num_rows;
		
		$resarrayf = array();
		
		while ($row = $result->fetch_assoc()){
			$resarrayf[] = $row;
		}
	}
	else {
		echo "SQL Prepare Failed. (Feature)";
		http_response_code(500);
		die();
	}
	
	$resarray = array();
	$resarray['districts'] = $resarrayd;
	$resarray['features'] = $resarrayf;
	
	echo json_encode($resarray);
	http_response_code(200);
	die();
}
else {
	echo "Not signed in.";
	http_response_code(401);
	die();
}
 ?>