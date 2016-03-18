<!DOCTYPE HTML>
<html>
<body>
  <?php
  //Create a user session or resume an existing one
 session_start();
 ?>
 <?php
if(isset($_SESSION['id'])){
	//Destroy the user's session.
	$_SESSION['id']=null;
	$_SESSION['admin']=null;
	$_SESSION['supplier']=null;
	session_destroy();
	echo "Logged out successfully.";
}
else {
	echo "Not signed in.";
}
 ?>
 
</body>
</html>