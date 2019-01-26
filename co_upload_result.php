<?php
include('lecturer_function.php');
if (!isLoggedIn() || $_SESSION['user'] != "college officer") {
    $_SESSION['msg'] = "You must log in first";
  header('location: index.php');
  }
  ?>
  <?php ini_set('max_execution_time', 300);
require_once 'Classes/PHPExcel.php';
$msgs = array();
$errors = array();
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
    if (isset($_POST['upload_result'])) 
    {
    $user = "racohabl_racohabl";
$password = "DvKKVAaazzr7";
$host = "server226.web-hosting.com";
$dbase = "racohabl_oui_result_db";  
    $conn = mysqli_connect($host, $user, $password, $dbase);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    else{
      $session = mysqli_real_escape_string($conn, trim($_POST['session']));
      global $errors, $msgs;
      if (empty($_FILES['result_file']['tmp_name'])) {
    array_push($errors, "Result file is required, use the choose file button");
  }
  if (empty($session)) {
    array_push($errors, 'Session was not provided');
  }
  if (count($errors)==0){
    $department = $_POST['department'];
    $Level = $_POST['level'];
    $semester = $_POST['semester'];
    $table = $session."_".$semester."_".$department."_".$Level."_Final_Result";
    $table_course = $department."_".$Level."_".$semester."_courses";
    
      $sql_check = "SELECT * FROM `$table`";
$result = mysqli_query($conn, $sql_check);

if ($result){

$sql_delete = "TRUNCATE TABLE `$table`";
mysqli_query($conn, $sql_delete);
}
$sql_create = "CREATE TABLE IF NOT EXISTS `$table` (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
student_name VARCHAR(50) NOT NULL,
student_matric_number VARCHAR(30) NOT NULL,
tp INT(4) NOT NULL,
tu INT(4) NOT NULL,
gp DECIMAL(3,2) NOT NULL,
ctp INT(4) NOT NULL,
ctu INT(4) NOT NULL,
cgp DECIMAL(3,2) NOT NULL
)ENGINE = INNODB";

if (mysqli_query($conn, $sql_create)) {
      $excel = new PHPExcel();
          $excel = PHPExcel_IOFactory::load($_FILES['result_file']['tmp_name']);
          $excel->setActiveSheetIndex(0);
      $number_of_courses = mysqli_num_rows(mysqli_query($conn, "SELECT course_code FROM `$table_course`"));
          $column_identifier = array("D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
          $noc = $number_of_courses - 1;
    for ($x=$noc; $x >=0; $x--) { 
      $column_ID= $column_identifier[$x];
      $new_column = $excel->getActiveSheet()->getCell($column_ID.'6')->getValue();
      $sql_add_column = "ALTER TABLE `$table` ADD `$new_column` VARCHAR(3) after student_matric_number";
      mysqli_query($conn, $sql_add_column);
    }
      $i= 8;
      if ($excel->getActiveSheet()->getCell('A'.$i)->getValue() =="1") {
        while ($excel->getActiveSheet()->getCell('A'.$i)->getValue() !="") 
      {
        $student_name=$excel->getActiveSheet()->getCell('B'.$i)->getValue();
        $student_matric=$excel->getActiveSheet()->getCell('C'.$i)->getValue();
    $tp = $excel->getActiveSheet()->getCell($column_identifier[$number_of_courses].$i)->getValue();
    $tu = $excel->getActiveSheet()->getCell($column_identifier[$number_of_courses+1].$i)->getValue();
    $gp = $excel->getActiveSheet()->getCell($column_identifier[$number_of_courses+2].$i)->getValue();
    $ctp = $excel->getActiveSheet()->getCell($column_identifier[$number_of_courses+3].$i)->getValue();
    $ctu = $excel->getActiveSheet()->getCell($column_identifier[$number_of_courses+4].$i)->getValue();
    $cgp = $excel->getActiveSheet()->getCell($column_identifier[$number_of_courses+5].$i)->getValue();
        $sql = "INSERT INTO `$table`(student_name, student_matric_number, tp, tu, gp, ctp, ctu, cgp) VALUES ('$student_name','$student_matric','$tp','$tu', '$gp', '$ctp', '$ctu', '$cgp')";
        $i++;
        mysqli_query($conn, $sql);
      }
    
    for ($y=0; $y < $number_of_courses; $y++){
      $column_ID2= $column_identifier[$y];
      $column_name = $excel->getActiveSheet()->getCell($column_ID2.'6')->getValue();
      $j=8; 
      $id = 1;
      while ($excel->getActiveSheet()->getCell('A'.$j)->getValue() !=""){
        $grade = $excel->getActiveSheet()->getCell($column_ID2.$j)->getValue();
        $sql_update = "UPDATE `$table` SET `$column_name`='$grade' WHERE id='$id'";
        mysqli_query($conn, $sql_update);
        $j++;
        $id++;
      }
    }
    
      
      if (mysqli_query($conn, $sql) ) 
      {
        array_push($msgs, 'You have successfully uploaded a record for '.$session.' '.$semester.', '.$department.' '.$Level.' result');
      } 
      else {
          array_push($errors, 'ERROR!!!... Uploading a record for '.$session.' '.$semester.', '.$department.' '.$Level.' result NOT successfull. Ensure a valid file format is choosen for upload.');
      }
      }
      else{
        array_push($errors, 'ERROR!!!... Please check the file to ensure that serial number starts from cell number "8"');
      }
      
          }

mysqli_close($conn);

          
  }
  }
    
}
    ?>
	<?php $site = "co_upload_result.php?logout='1'"; include 'co_header.php';?>
<form class="form-group" action="co_upload_result.php" enctype="multipart/form-data" method="post">
  <div class = "alert alert-warning"> <?php echo display_error();?>  </div>
  <p><?php echo display_msg();?></p>
  <?php $header = "Upload Cumulative Result"; include 'body.php';?>
  <input style="margin-top: 10px;" type="file" name="result_file" accept=".xls, .xlsx, application/vnd/openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"><?php echo "<script src = 'check_file.js'></script>";?>
    <input style="margin-top: 10px;" type="submit" name="upload_result" value="Upload">
</section>
</main>
</form>
<?php include 'footer.php';?>