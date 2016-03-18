<!DOCTYPE HTML>
<html>
<body>
  <?php
  //Create a user session or resume an existing one
 session_start();
 ?>
 <?php
if(isset($_SESSION['id'])){
	printf ("Admin: %d <br> Supplier: %d", $_SESSION['admin'], $_SESSION['supplier']);
}
else {
	echo "Not signed in.";
}
 ?>
 
</body>
</html>