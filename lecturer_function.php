<?php 
session_start();

// connect to database
include 'dbConnect.php';

// variable declaration
$username = "";
$email    = "";
function isLoggedIn()
{
	if (isset($_SESSION['user'])) {
		return true;
	}else{
		return false;
	}
}
if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: index.php");
}
?>
