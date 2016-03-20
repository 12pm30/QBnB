<?php
  //Create a user session or resume an existing one
 session_start();
 ?>
<?php
 //check if the user is already logged in and has an active session
if(isset($_SESSION['id'])){
    http_response_code(200);
    echo "Already logged in.";
	die();
}
 ?>
<?php
if(!isset($_SESSION['id']) and isset($_POST['email']) and isset($_POST['password'])){
	include_once 'config/connection.php';
	
	$query = "SELECT member_ID, admin, supplier from member WHERE email=? AND password=sha2(?,512)";
	
	if ($stmt = $con->prepare($query)){
		
		$stmt->bind_Param("ss", $_POST['email'], $_POST['password']);

		$stmt->execute();
		
		$result = $stmt->get_result();
		
		$num = $result->num_rows;
		
		if ($num == 1){
			$row = $result->fetch_assoc();
			
			$_SESSION['id'] = $row['member_ID'];
			$_SESSION['admin'] = $row['admin'];
			$_SESSION['supplier'] = $row['supplier'];
			
			echo "Login Success.";
			http_response_code(200);
			die();
		}
		else {
			echo "Login Failed.";
			http_response_code(401);
		}
	}
	else {
		echo "SQL Prepare Failed.";
		http_response_code(500);
	}
}		 
 ?>
 <html>
 <body>
 
 <form name='login' id='login' action='login.php' method='post'>
    <table border='0'>
        <tr>
            <td>Email</td>
            <td><input type='email' name='email' id='email' 
			<?php if (isset($_POST['email'])) echo 'value="'.$_POST['email'].'"';?>
			/></td>
        </tr>
        <tr>
            <td>Password</td>
             <td><input type='password' name='password' id='password' /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' id='loginBtn' name='loginBtn' value='Log In' /> 
            </td>
        </tr>
    </table>
</form>

</body>
</html>