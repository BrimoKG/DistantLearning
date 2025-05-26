<?php 
	
	// if direct access to this page
if(preg_match("/config.php/",$_SERVER['SCRIPT_FILENAME'])){
	die("Access denied: Please away from here.");
}

	$connection = mysqli_connect('localhost:3307','root','mysql','quadratic') or die("Database Not connected".mysqli_connect_error());
	session_start();
	$connection->set_charset('utf8mb4');
 ?>