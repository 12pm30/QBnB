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
else if ($_SESSION['supplier'] != 1){
	echo "Not an authorized supplier.";
	http_response_code(403);
	die();
}
?>
<?php
if(isset($_POST['districtID_field']) and isset($_POST['street_number_field']) and isset($_POST['street_name_field']) and isset($_POST['apt_number_field']) and isset($_POST['city_field']) and isset($_POST['province_field']) and isset($_POST['postal_code_field']) and isset($_POST['num_bedrooms_field']) and isset($_POST['num_bathrooms_field']) and isset($_POST['accomodation_type_field']) and isset($_POST['price_field'])){
	include_once 'config/connection.php';
    
	$error = 0;
	
	$con->begin_transaction();
	
	$query = "INSERT INTO property (`property_ID`, `member_ID`, `district_ID`, `enabled`, `street_number`, `street_name`, `apt_number`, `city`, `province`, `postal_code`, `num_bedrooms`, `num_bathrooms`, `accomodation_type`, `price`) VALUES (NULL, ?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	
	if ($stmt = $con->prepare($query)){
		
		if (empty($_POST['apt_number_field'])){
			$_POST['apt_number_field'] = NULL;
		}
		
		$stmt->bind_Param("ssssssssssss", $_SESSION['id'], $_POST['districtID_field'], $_POST['street_number_field'], $_POST['street_name_field'], $_POST['apt_number_field'], $_POST['city_field'], $_POST['province_field'], $_POST['postal_code_field'], $_POST['num_bedrooms_field'], $_POST['num_bathrooms_field'], $_POST['accomodation_type_field'], $_POST['price_field']);

		if (!$stmt->execute()){
			printf ("Error: %s",$stmt->error);
			$con->rollback();
			$error = 1;
            http_response_code(400);
			die();
		}
		else{
			$propID = $con->insert_id;
		}
	}
	else {
		$con->rollback();
		$error = 1;
        http_response_code(500);
        echo "SQL Prepare Failed. (Property)";
        die();
	}
	
	if ($error == 0){
		$query = "INSERT INTO prop_feature (`property_ID`, `feature_ID`) VALUES (?, ?)";
		
		$explFeatures = explode(",", $_POST['features_field']);
		$numFeatures = count($explFeatures);
		
		for ($i = 0; $i < $numFeatures; $i++){
		
			if ($stmt = $con->prepare($query)){
				
				$stmt->bind_Param("ss", $propID, $explFeatures[$i]);

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
				echo "SQL Prepare Failed. (Feature)";
				die();
			}
		}
	}
	
	if ($error == 0){
		$query = "INSERT INTO prop_photo (`photo_ID`, `property_ID`, `caption`, `photo_URL`) VALUES (NULL, ?, ?, ?)";
		
		$photosArr = json_decode($_POST['json_photos_field']);
		$numPhotos = count($photosArr);
		
		for ($i = 0; $i < $numPhotos; $i++){
		
			if ($stmt = $con->prepare($query)){
				
				$stmt->bind_Param("sss", $propID, $photosArr[$i]->caption, $photosArr[$i]->URL);

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
				echo "SQL Prepare Failed. (Photo)";
				die();
			}
		}
	}
	
	if ($error == 0){
		$con->commit();
		http_response_code(200);
		echo "Property Created Successfully.";
		die();
	}
	else{
		$con->rollback();
		echo "Creation failed.";
		http_response_code(500);
		die();
	}
	
}		 
 ?>
<!DOCTYPE HTML>
<html>
<body>

 <form name='newproperty' id='newproperty' action='newproperty.php' method='post'>
    <table border='0'>
		<tr>
		<td><b>New Property Info</b></td>
		</tr>
		<tr>
            <td>District ID*</td>
            <td><input type='number' name='districtID_field' id='districtID_field' 
			<?php if (isset($_POST['districtID_field'])) echo 'value="'.$_POST['districtID_field'].'"';?>
			/></td>
        </tr>
        <tr>
            <td>Street Number*</td>
            <td><input type='number' name='street_number_field' id='street_number_field' 
			<?php if (isset($_POST['street_number_field'])) echo 'value="'.$_POST['street_number_field'].'"';?>
			/></td>
        </tr>
        <tr>
            <td>Street Name*</td>
             <td><input type='text' name='street_name_field' id='street_name_field' 
			 <?php if (isset($_POST['street_name_field'])) echo 'value="'.$_POST['street_name_field'].'"';?>
			 /></td>
        </tr>
		<tr>
            <td>Apt. Number</td>
             <td><input type='number' name='apt_number_field' id='apt_number_field' 
			 <?php if (isset($_POST['apt_number_field'])) echo 'value="'.$_POST['apt_number_field'].'"';?>
			 /></td>
        </tr>
		<tr>
            <td>City*</td>
             <td><input type='text' name='city_field' id='city_field' 
			 <?php if (isset($_POST['city_field'])) echo 'value="'.$_POST['city_field'].'"';?>
			 /></td>
        </tr>
		<tr>
            <td>Province*</td>
             <td><input type='text' maxlength=2 name='province_field' id='province_field' /></td>
        </tr>
		<tr>
            <td>Postal Code*</td>
             <td><input type='text' maxlength=7 name='postal_code_field' id='postal_code_field' 
			 <?php if (isset($_POST['postal_code_field'])) echo 'value="'.$_POST['postal_code_field'].'"';?>
			 /></td>
        </tr>
		<tr>
            <td>Number of Bedrooms*</td>
             <td><input type='number' name='num_bedrooms_field' id='num_bedrooms_field' 
			 <?php if (isset($_POST['num_bedrooms_field'])) echo 'value="'.$_POST['num_bedrooms_field'].'"';?>
			 /></td>
        </tr>
		<tr>
            <td>Number of Bathrooms*</td>
             <td><input type='number' name='num_bathrooms_field' id='num_bathrooms_field' 
			 <?php if (isset($_POST['num_bathrooms_field'])) echo 'value="'.$_POST['num_bathrooms_field'].'"';?>
			 /></td>
        </tr>
		<tr>
            <td>Accomodation Type*</td>
             <td><select name="accomodation_type_field">
				<option value="Apartment">Apartment</option>
				<option value="House">House</option>
				<option value="Room">Room</option>
				</select>
			</td>
        </tr>
		<tr>
			<td>Price*</td>
			<td><input type='number' name='price_field' id='price_field' step="0.01" 
			 <?php if (isset($_POST['price_field'])) echo 'value="'.$_POST['price_field'].'"';?>
			 /></td>
		</tr>
		<tr>
            <td>Feature IDs (comma sep.)</td>
             <td><input type='text' name='features_field' id='features_field' 
			 <?php if (isset($_POST['features_field'])) echo 'value="'.$_POST['features_field'].'"';?>
			 /></td>
        </tr>
		<tr>
            <td>Photos (JSON - Link:, Caption:)</td>
             <td><input type='text' name='json_photos_field' id='json_photos_field' 
			 <?php if (isset($_POST['json_photos_field'])) echo 'value="'.$_POST['json_photos_field'].'"';?>
			 /></td>
        </tr>
		<tr>
            <td></td>
            <td>
                <input type='submit' id='newPropBtn' name='newPropBtn' value='Create Property' /> 
            </td>
        </tr>
    </table>
</form>

</body>
</html>