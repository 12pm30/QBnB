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
?>
<?php
if(isset($_POST['property_to_view']) and isset($_POST['rating_field']) and isset($_POST['review_text_field'])){
	include_once 'config/connection.php';

	$query = "UPDATE review SET rating = ?, review_text = ? WHERE member_ID = ? AND property_ID = ?";
	
	if ($stmt = $con->prepare($query)){
		
		$present_date = date("Y-m-d");
		
		$stmt->bind_Param("ssss", $_POST['rating_field'], $_POST['review_text_field'], $_SESSION['id'], $_POST['property_to_view']);

		$stmt-> execute();
		
		$result = $stmt->get_result();
		
		$rowsAffect = $con->affected_rows;
		
		if ($rowsAffect == 1){
			printf ("Review updated successfully.");
            http_response_code(200);
			die();
		}
		else{
			printf ("Error editing review.");
			http_response_code(500);
			die();
		}
	}
	else {
        http_response_code(500);
        echo "SQL Prepare Failed.";
        die();
	}
}		 
 ?>
<!DOCTYPE HTML>
<html>
<body>

 <form name='editreviewForm' id='editreviewForm' action='editreview.php' method='post'>
    <table border='0'>
		<tr>
            <td>Property ID</td>
            <td><input type='number' name='property_to_view' id='property_to_view' 
			<?php if (isset($_POST['property_to_view'])) echo 'value="'.$_POST['property_to_view'].'"';?>
			/></td>
        </tr>
        <tr>
            <td>Rating</td>
            <td><input type='number' name='rating_field' id='rating_field' 
			<?php if (isset($_POST['rating_field'])) echo 'value="'.$_POST['rating_field'].'"';?>
			/></td>
        </tr>
        <tr>
            <td>Review Text</td>
             <td><input type='text' name='review_text_field' id='review_text_field' 
			 <?php if (isset($_POST['review_text_field'])) echo 'value="'.$_POST['review_text_field'].'"';?>
			 /></td>
        </tr>
		<tr>
            <td></td>
            <td>
                <input type='submit' id='reviewBtn' name='reviewBtn' value='Edit Review' /> 
            </td>
        </tr>
    </table>
</form>

</body>
</html>