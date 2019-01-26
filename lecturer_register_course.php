<?php include('lecturer_function.php');
if (!isLoggedIn() || $_SESSION['user'] != "Lecturer") {
    $_SESSION['msg'] = "You must log in first";
  header('location: index.php');
} ?>
<?php
    $msgs = array();
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
function e($val){
  global $conn;
  return mysqli_real_escape_string($conn, trim($val));
}
    if (isset($_POST['submit'])) 
    {
      $errors   = array(); 
      global $conn, $errors, $msgs;
    
    $course_code = e($_POST['course_code']);
    $course_title = e($_POST['course_title']);
    $table = "course_details";
    if (empty($course_code)) {
        array_push($errors, "Course Code is required");
      }
      if (empty($course_title)) {
        array_push($errors, "Course Title is required");
      }
      
      if(count($errors)==0){
   $user = "racohabl_racohabl";
$password = "DvKKVAaazzr7";
$host = "server226.web-hosting.com";
$dbase = "racohabl_oui_result_db";  
    $conn = mysqli_connect($host, $user, $password, $dbase);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    else{
		
     $sql_create = "CREATE TABLE IF NOT EXISTS `$table` (
			id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
			course_code VARCHAR(30) NOT NULL,
			course_title VARCHAR(30) NOT NULL
			)ENGINE = INNODB";
			mysqli_query($conn, $sql_create);
if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `$table` WHERE course_code = '$course_code'"))==0){
  $result = mysqli_query($conn, "INSERT INTO `$table`(course_code, course_title) VALUES('$course_code', '$course_title')");
  if ($result) {
  array_push($msgs, 'Details for '.$course_code.' were successfully registered');
}
}
else{
    $edit = mysqli_query($conn, "UPDATE `$table` SET course_title = '$course_title' WHERE course_code = '$course_code'");
    if ($edit) {
  array_push($msgs, 'Course Title for '.$course_code.' was successfully updated');
    }
}


          }
mysqli_close($conn);

          
  }
}
    ?>
<?php $site = "lecturer_register_course.php?logout='1'"; include 'lecturer_header.php';?>
<form enctype="multipart/form-data" class="form-group" action="lecturer_register_course.php" method="post">
  <div><?php echo display_error();?><?php echo display_msg();?></div>
<main class="row">
  <section class="col-md-10 col-xs-offset-1 col-xs-10">
    <h3><center>Register Course Details</center></h3>    
    <div class="input-group">
      <span class="input-group-addon" id="basic-addon1">Course Code:</span>
      <input type="text" class="form-control" placeholder="ABC 101" name="course_code" aria-describedby="basic-addon1">
    </div>
    <div class="input-group">
      <span class="input-group-addon" id="basic-addon1">Course Title:</span>
      <input type="text" class="form-control" name="course_title" aria-describedby="basic-addon1">
    </div>
    <input style="margin-top: 10px;" type="submit" name="submit" value="Register">
</section>
</main>
</form>

	<footer class="site-footer navbar navbar-inverse">
  <h5><center>Developed by <font style="font-style: italic;">herbeysoftweb solutions</font><br><br>Contact: adeyemioluwaseun47@gmail.com<br>+234 9034582835</center></h5>
</footer>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
