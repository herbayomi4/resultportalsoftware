<?php 
session_start();

// connect to database
include 'dbConnect.php';

// variable declaration
$username = "";
$email    = "";
$errors   = array(); 
$msgs = array();


// call the register() function if register_btn is clicked
if (isset($_POST['register_btn'])) {
	register();
}

// REGISTER USER
function register(){
	// call these variables with the global keyword to make them available in function
	global $conn, $errors, $username, $office, $msgs;

	// receive all input values from the form. Call the e() function
    // defined below to escape form values
    $office      =  e($_POST['officer']);
	$username    =  e($_POST['username']);
	$email       =  e($_POST['email']);
	$password_1  =  e($_POST['password_1']);
	$password_2  =  e($_POST['password_2']);
        $sql_check = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM staff_register WHERE username = '$username'"));

	// form validation: ensure that the form is correctly filled
        if ($sql_check > 0) {
                array_push($errors, "Username already exist!");
        }
	if (empty($username)) { 
		array_push($errors, "Username is required"); 
	}
	if (empty($email)) { 
		array_push($errors, "Email is required"); 
	}
	if (empty($password_1)) { 
		array_push($errors, "Password is required"); 
	}
	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
	}
	if(strlen($password_1)<8){
		array_push($errors, "Minimum of 8 characters is required for a strong password");
	}

	// register user if there are no errors in the form
	if (count($errors) == 0) {
		$password = md5($password_1);//encrypt the password before saving in the database
			$query = "INSERT INTO staff_register (username, email, password, office) 
					  VALUES('$username', '$email', '$password', '$office')";
			mysqli_query($conn, $query);

			array_push($msgs, "Registration was successfull");		
	}
}

if (isset($_POST['change_login'])) {
	change();
}

// REGISTER USER
function change(){
	// call these variables with the global keyword to make them available in function
	global $conn, $errors, $msgs;

	// receive all input values from the form. Call the e() function
    // defined below to escape form values
	$password1  =  e($_POST['password1']);
        $password2  =  e($_POST['password2']);
	$password3  =  e($_POST['password3']);
	$password1 = md5($password1);
        $sql_check = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM staff_register WHERE password = '$password1'"));

	// form validation: ensure that the form is correctly filled
        if ($sql_check == 0) {
                array_push($errors, "Old Password not identified!");
        }
	if (empty($password1)) { 
		array_push($errors, "Old Password is required"); 
	}
        if (empty($password2)) { 
		array_push($errors, "New Password is required"); 
	}
	if ($password2 != $password3) {
		array_push($errors, "The two passwords do not match");
	}
	if(strlen($password2)<8){
		array_push($errors, "Minimum of 8 characters is required for a strong password");
	}

	// register user if there are no errors in the form
	if (count($errors) == 0) {
		$password = md5($password2);//encrypt the password before saving in the database
			$query = "UPDATE staff_register SET password='$password' WHERE password='$password1'";
			mysqli_query($conn, $query);

			array_push($msgs, "Password update was successfull");		
	}
}
// return user array from their id
function getUserById($id){
	global $conn;
	$query = "SELECT * FROM staff_register WHERE id=" . $id;
	$result = mysqli_query($conn, $query);

	$user = mysqli_fetch_assoc($result);
	return $user;
}

// escape string

function display_error() {
	global $errors;

	if (count($errors) > 0){
		echo '<div class="alert alert-danger">';
			foreach ($errors as $error){
				echo $error .'<br>';
			}
		echo '</div>';
	}
}
 function display_msg() {
  global $msgs;

  if (count($msgs) > 0){
    echo '<div class="alert alert-info">';
      foreach ($msgs as $msg){
        echo $msg .'<br>';
      }
    echo '</div>';
  }
}
function e($val){
	global $conn;
	return mysqli_real_escape_string($conn, trim($val));
}
if (isset($_POST['submit'])) {
	login();
}

// LOGIN USER
function login(){
	global $conn, $username, $errors;

	// grap form values
	$username = e($_POST['username']);
	$password = e($_POST['password']);
	//$_SESSION['username'] = $username;
	// make sure form is filled properly
	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}

	// attempt login if no errors on form
	if (count($errors) == 0) {
		$password = md5($password);

		$query = "SELECT * FROM staff_register WHERE username='$username' AND password='$password'";
		$results = mysqli_query($conn, $query);
		if ($results) {
		if (mysqli_num_rows($results) >0) { // user found
			// check if user is admin or user
			$logged_in_user = mysqli_fetch_assoc($results);
			if ($logged_in_user['office'] == "Lecturer") {
				$_SESSION['user'] = "Lecturer";
				header('location: get_list.php');		  
			}
			else if($logged_in_user['office'] == "Departmental Officer"){
				$_SESSION['user'] = "Departmental Officer";
				header('location: do_portal.php');	
			}
			else if($logged_in_user['office'] == "college officer"){
				$_SESSION['user'] = "college officer";
				header('location: co_portal.php');
			}
		}else {
			array_push($errors, "Wrong username/password combination");
		}	
		}
		else{
			array_push($errors, "Record Not Found");
		}
		
	}
}
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