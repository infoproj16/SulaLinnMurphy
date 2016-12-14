<?php
	// log user out by unsetting session variable
	
	
	session_start();
	if (isset($_SESSION['email'])) {
		unset($_SESSION['email']);
	
	}
	session_destroy();
	
	// redirect user to login page
	header('Location: template1.php');
	exit;
	
?>