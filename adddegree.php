<?php
  //Create a user session or resume an existing one
 session_start();
 ?>
<?php
 if (!isset($_SESSION['id'])){
	echo "Not signed in.";
	http_response_code(401);
	die();
 }
 ?>
<?php 
 if(isset($_POST['year_field']) and isset($_POST['faculty_field']) and isset($_POST['type_field'])){
	 include_once 'config/connection.php';
	 
	 $query = "INSERT INTO degree (`degree_ID`, `member_ID`, `year`, `faculty`, `type`) VALUES (NULL, ?,?,?,?)";
		
		if ($stmt = $con->prepare($query)){
			
			$stmt->bind_Param("ssss", $_SESSION['id'], $_POST['year_field'], $_POST['faculty_field'], $_POST['type_field']);

			if (!$stmt->execute()){
				printf ("Error: %s",$stmt->error);
				http_response_code(500);
				die();
			}
			else{
				echo "Degree Inserted.";
				http_response_code(200);
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
 <form name='addDeg' id='addDeg' action='adddegree.php' method='post'>
    <table border='0'>
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
                <input type='submit' id='newDegBtn' name='newDegBtn' value='Add Degree' /> 
            </td>
        </tr>
    </table>
</form>
 
</body>
</html>