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
	
	$query = "SELECT property_ID, first_name, middle_initial, last_name, street_number, street_name, apt_number, city, province, postal_code, num_bedrooms, num_bathrooms, accomodation_type, price, AVG(rating) FROM (property NATURAL JOIN member) LEFT JOIN review USING (property_ID) WHERE 1";
	
	$x = 0;
	$paramtype = "";
	
	if (!empty($_POST['district_field'])){
		$query = $query . " AND district_ID = ?";
		$paramarray[$x] = $_POST['district_field'];
		$paramtype = $paramtype . "s";
		$x += 1;
	}
	
	if (!empty($_POST['city_field'])){
		$query = $query . " AND city = ?";
		$paramarray[$x] = $_POST['city_field'];
		$paramtype = $paramtype . "s";
		$x += 1;
	}
	
	if (!empty($_POST['province_field'])){
		$query = $query . " AND province = ?";
		$paramarray[$x] = $_POST['province_field'];
		$paramtype = $paramtype . "s";
		$x += 1;
	}
	
	if (!empty($_POST['type_field'])){
		$query = $query . " AND accomodation_type = ?";
		$paramarray[$x] = $_POST['type_field'];
		$paramtype = $paramtype . "s";
		$x += 1;
	}
	
	if (!empty($_POST['min_bedrooms'])){
		$query = $query . " AND num_bedrooms >= ?";
		$paramarray[$x] = $_POST['min_bedrooms'];
		$paramtype = $paramtype . "s";
		$x += 1;
	}
	
	if (!empty($_POST['max_bedrooms'])){
		$query = $query . " AND num_bedrooms <= ?";
		$paramarray[$x] = $_POST['max_bedrooms'];
		$paramtype = $paramtype . "s";
		$x += 1;
	}
	
	if (!empty($_POST['min_bathrooms'])){
		$query = $query . " AND num_bathrooms >= ?";
		$paramarray[$x] = $_POST['min_bathrooms'];
		$paramtype = $paramtype . "s";
		$x += 1;
	}
	
	if (!empty($_POST['max_bathrooms'])){
		$query = $query . " AND num_bathrooms <= ?";
		$paramarray[$x] = $_POST['max_bathrooms'];
		$paramtype = $paramtype . "s";
		$x += 1;
	}
	
	if (!empty($_POST['min_price'])){
		$query = $query . " AND price >= ?";
		$paramarray[$x] = $_POST['min_price'];
		$paramtype = $paramtype . "s";
		$x += 1;
	}
	
	if (!empty($_POST['max_price'])){
		$query = $query . " AND price <= ?";
		$paramarray[$x] = $_POST['max_price'];
		$paramtype = $paramtype . "s";
		$x += 1;
	}
	
	if (!empty($_POST['list_of_selected_features'])){
		$numfeatures = substr_count($_POST['list_of_selected_features'], ",");
		if (preg_match("/^([0-9,]+)$/",$_POST['list_of_selected_features'])){
			$query = $query . " AND property_ID IN (SELECT a.property_ID FROM property a NATURAL JOIN prop_feature b WHERE b.feature_ID IN (" . $_POST['list_of_selected_features'] .") GROUP BY a.property_ID HAVING COUNT(b.feature_ID) = ?)";
			$paramarray[$x] = $numfeatures+1;
			$paramtype = $paramtype . "s";
			$x += 1;
		}
		else{
			echo "Features ignored due to invalid input <br>";
		}
	}
	
	$query = $query . " GROUP BY property_ID";
	
	if (!empty($_POST['min_rating']) or !empty($_POST['max_rating'])){
		if (!empty($_POST['min_rating']) and !empty($_POST['max_rating'])){
			$query = $query . " HAVING AVG(rating) >= ? AND AVG(rating) <= ?";
			$paramarray[$x] = $_POST['min_rating'];
			$paramarray[$x+1] = $_POST['max_rating'];
			$paramtype = $paramtype . "ss";
			$x += 2;
		}
		else if (!empty($_POST['min_rating'])){
			$query = $query . " HAVING AVG(rating) >= ?";
			$paramarray[$x] = $_POST['min_rating'];
			$paramtype = $paramtype . "s";
			$x += 1;
		}
		else{
			$query = $query . " HAVING AVG(rating) <= ?";
			$paramarray[$x+1] = $_POST['max_rating'];
			$paramtype = $paramtype . "s";
			$x += 1;
		}
	}

	if ($stmt = $con->prepare($query)){
		
		if ($x == 1){$stmt->bind_Param($paramtype, $paramarray[0]);}
		if ($x == 2){$stmt->bind_Param($paramtype, $paramarray[0], $paramarray[1]);}
		if ($x == 3){$stmt->bind_Param($paramtype, $paramarray[0], $paramarray[1], $paramarray[2]);}
		if ($x == 4){$stmt->bind_Param($paramtype, $paramarray[0], $paramarray[1], $paramarray[2], $paramarray[3]);}
		if ($x == 5){$stmt->bind_Param($paramtype, $paramarray[0], $paramarray[1], $paramarray[2], $paramarray[3], $paramarray[4]);}
		if ($x == 6){$stmt->bind_Param($paramtype, $paramarray[0], $paramarray[1], $paramarray[2], $paramarray[3], $paramarray[4], $paramarray[5]);}
		if ($x == 7){$stmt->bind_Param($paramtype, $paramarray[0], $paramarray[1], $paramarray[2], $paramarray[3], $paramarray[4], $paramarray[5], $paramarray[6]);}
		if ($x == 8){$stmt->bind_Param($paramtype, $paramarray[0], $paramarray[1], $paramarray[2], $paramarray[3], $paramarray[4], $paramarray[5], $paramarray[6], $paramarray[7]);}
		if ($x == 9){$stmt->bind_Param($paramtype, $paramarray[0], $paramarray[1], $paramarray[2], $paramarray[3], $paramarray[4], $paramarray[5], $paramarray[6], $paramarray[7], $paramarray[8]);}
		if ($x == 10){$stmt->bind_Param($paramtype, $paramarray[0], $paramarray[1], $paramarray[2], $paramarray[3], $paramarray[4], $paramarray[5], $paramarray[6], $paramarray[7], $paramarray[8], $paramarray[9]);}
		if ($x == 11){$stmt->bind_Param($paramtype, $paramarray[0], $paramarray[1], $paramarray[2], $paramarray[3], $paramarray[4], $paramarray[5], $paramarray[6], $paramarray[7], $paramarray[8], $paramarray[9], $paramarray[10]);}
		if ($x == 12){$stmt->bind_Param($paramtype, $paramarray[0], $paramarray[1], $paramarray[2], $paramarray[3], $paramarray[4], $paramarray[5], $paramarray[6], $paramarray[7], $paramarray[8], $paramarray[9], $paramarray[10], $paramarray[11]);}
		if ($x == 13){$stmt->bind_Param($paramtype, $paramarray[0], $paramarray[1], $paramarray[2], $paramarray[3], $paramarray[4], $paramarray[5], $paramarray[6], $paramarray[7], $paramarray[8], $paramarray[9], $paramarray[10], $paramarray[11], $paramarray[12]);}
		if ($x == 14){$stmt->bind_Param($paramtype, $paramarray[0], $paramarray[1], $paramarray[2], $paramarray[3], $paramarray[4], $paramarray[5], $paramarray[6], $paramarray[7], $paramarray[8], $paramarray[9], $paramarray[10], $paramarray[11], $paramarray[12], $paramarray[13]);}
		if ($x == 15){$stmt->bind_Param($paramtype, $paramarray[0], $paramarray[1], $paramarray[2], $paramarray[3], $paramarray[4], $paramarray[5], $paramarray[6], $paramarray[7], $paramarray[8], $paramarray[9], $paramarray[10], $paramarray[11], $paramarray[12], $paramarray[13], $paramarray[14]);}
		
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

<form name='findpropForm' id='findpropForm' action='adminfindproperty.php' method='post'>
    <table border='0'>
		<tr>
			<td>District ID</td>
			<td><input type='number' name='district_field' id='district_field' 
			 <?php if (isset($_POST['district_field'])) echo 'value="'.$_POST['district_field'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>City</td>
			<td><input type='text' name='city_field' id='city_field' 
			 <?php if (isset($_POST['city_field'])) echo 'value="'.$_POST['city_field'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Province</td>
			<td><input type='text' name='province_field' id='province_field' 
			 <?php if (isset($_POST['province_field'])) echo 'value="'.$_POST['province_field'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Accomodation Type (House, Apartment, Room)</td>
			<td><input type='text' name='type_field' id='type_field' 
			 <?php if (isset($_POST['type_field'])) echo 'value="'.$_POST['type_field'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Min Bedrooms</td>
			<td><input type='text' name='min_bedrooms' id='min_bedrooms' 
			 <?php if (isset($_POST['min_bedrooms'])) echo 'value="'.$_POST['min_bedrooms'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Max Bedrooms</td>
			<td><input type='text' name='max_bedrooms' id='max_bedrooms' 
			 <?php if (isset($_POST['max_bedrooms'])) echo 'value="'.$_POST['max_bedrooms'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Min Bathrooms</td>
			<td><input type='text' name='min_bathrooms' id='min_bathrooms' 
			 <?php if (isset($_POST['min_bathrooms'])) echo 'value="'.$_POST['min_bathrooms'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Max Bathrooms</td>
			<td><input type='text' name='max_bathrooms' id='max_bathrooms' 
			 <?php if (isset($_POST['max_bathrooms'])) echo 'value="'.$_POST['max_bathrooms'].'"';?>
			 /></td>
		</tr>	
		<tr>
			<td>Min Price</td>
			<td><input type='text' name='min_price' id='min_price' 
			 <?php if (isset($_POST['min_price'])) echo 'value="'.$_POST['min_price'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Max Price</td>
			<td><input type='text' name='max_price' id='max_price' 
			 <?php if (isset($_POST['max_price'])) echo 'value="'.$_POST['max_price'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Feature IDs</td>
			<td><input type='text' name='list_of_selected_features' id='list_of_selected_features' 
			 <?php if (isset($_POST['list_of_selected_features'])) echo 'value="'.$_POST['list_of_selected_features'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Min Rating</td>
			<td><input type='text' name='min_rating' id='min_rating' 
			 <?php if (isset($_POST['min_rating'])) echo 'value="'.$_POST['min_rating'].'"';?>
			 /></td>
		</tr>
		<tr>
			<td>Max Rating</td>
			<td><input type='text' name='max_rating' id='max_rating' 
			 <?php if (isset($_POST['max_rating'])) echo 'value="'.$_POST['max_rating'].'"';?>
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