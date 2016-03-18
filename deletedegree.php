<!DOCTYPE HTML>
<html>
<body>
  <?php
  //Create a user session or resume an existing one
 session_start();
 ?>
 
 <?php
 if (!isset($_SESSION['id'])){
	echo "Not signed in.";
	die();
 }
 ?>
 
 <?php 
 if(isset($_POST['delID_field'])){
	 include_once 'config/connection.php';
	 
	 $query = "DELETE FROM degree WHERE member_ID = ? and degree_ID = ?";
		
		if ($stmt = $con->prepare($query)){
			
			$stmt->bind_Param("ss", $_SESSION['id'], $_POST['delID_field']);

			$stmt->execute();
			
			$rowsAffect = $con->affected_rows;
			
			if ($rowsAffect == 0){
				echo "Degree not found.";
			}
			else {
				echo "Degree Deleted.";
				die();
			}
		}
		else {
			echo "SQL Prepare Failed.";
		}
}
?>
 <form name='delDeg' id='delDeg' action='deletedegree.php' method='post'>
    <table border='0'>
        <tr>
			<td>Degree to Delete</td>
			<td><input type='number' name='delID_field' id='delID_field' 
			 <?php if (isset($_POST['delID_field'])) echo 'value="'.$_POST['delID_field'].'"';?>
			 /></td>
		</tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' id='delDegBtn' name='delDegBtn' value='Delete Degree' /> 
            </td>
        </tr>
    </table>
</form>
 
</body>
</html>