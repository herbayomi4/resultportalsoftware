<?php
$user = "racohabl_racohabl";
$password = "DvKKVAaazzr7";
$host = "server226.web-hosting.com";
$dbase = "racohabl_oui_result_db";
$conn = mysqli_connect($host, $user, $password, $dbase);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}
?>