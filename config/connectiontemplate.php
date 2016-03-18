<?php

// !!!FILL THIS IN AND RENAME TO connection.php!!!

// used to connect to the database!
$host = "localhost";
$db_name = "qbnb";
$username = "PUT USERNAME HERE";
$password = "PUT PASSWORD HERE";

try {
    $con = new mysqli($host,$username,$password, $db_name);
}
 
// show error
catch(Exception $exception){
    echo "Connection error: " . $exception->getMessage();
}

?>