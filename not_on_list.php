<?php include('lecturer_function.php');
if (!isLoggedIn() || $_SESSION['user'] != "Lecturer") {
    $_SESSION['msg'] = "You must log in first";
  header('location: index.php');
} ?>
<?php
    
    require_once 'Classes/PHPExcel.php';
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
    if (isset($_POST['Upload'])) 
    {
      $errors   = array(); 
      global $conn, $errors, $msgs;
    $department = $_POST['department'];
    $Level = $_POST['level'];
    $session = e($_POST['session']);
    $semester = $_POST['semester'];
    $course_code = e($_POST['course_code']);
    if (empty($session)) {
      array_push($errors, "Course Code is required");
    }
    if (empty($course_code)) {
        array_push($errors, "Session is required");
      }
      
      if (empty($_FILES['course_result_file']['tmp_name'])) {
    array_push($errors, "Course result file is required, use the choose file button");
  }
      if(count($errors)==0){
    $table = strtoupper($session."_".$semester."_".$Level."_".$department."_".$course_code);
    
    
    $user = "racohabl_racohabl";
$password = "DvKKVAaazzr7";
$host = "server226.web-hosting.com";
$dbase = "racohabl_oui_result_db"; 
    $conn = mysqli_connect($host, $user, $password, $dbase);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    else{
		
      $sql_check = "SELECT * FROM `$table`";
$result = mysqli_query($conn, $sql_check);

$sql_create = "CREATE TABLE IF NOT EXISTS `$table` (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
student_name VARCHAR(30) NOT NULL,
student_matric_number VARCHAR(30) NOT NULL,
total INT(3) NOT NULL,
grade VARCHAR(1) NOT NULL
)ENGINE = INNODB";
    

if (mysqli_query($conn, $sql_create)) {
      $excel = new PHPExcel();
          $excel = PHPExcel_IOFactory::load($_FILES['course_result_file']['tmp_name']);

      $excel->setActiveSheetIndex(0);
      $i= 2;
      if ($excel->getActiveSheet()->getCell('A2')->getValue() == "1") {
       while ($excel->getActiveSheet()->getCell('A'.$i)->getValue() !="") 
      {
        $student_name=$excel->getActiveSheet()->getCell('B'.$i)->getValue();
        $student_matric=$excel->getActiveSheet()->getCell('C'.$i)->getValue();
        $attendance = $excel->getActiveSheet()->getCell('D'.$i)->getValue();
        $test = $excel->getActiveSheet()->getCell('E'.$i)->getValue();
        $exam = $excel->getActiveSheet()->getCell('F'.$i)->getValue();
        $total = $excel->getActiveSheet()->getCell('G'.$i)->getValue(); //$attendance+$test+$exam;
        $grade = $excel->getActiveSheet()->getCell('H'.$i)->getValue();
        if (mysqli_num_rows(mysqli_query($conn, "SELECT student_matric_number FROM `$table` WHERE student_matric_number='$student_matric'"))==0) {
          $sql = "INSERT INTO `$table`(student_name, student_matric_number, attendance, test, exam, total, grade) 
        VALUES ('$student_name','$student_matric', '$attendance', '$test', '$exam', '$total', '$grade')";

         $insert = mysqli_query($conn, $sql);
        }
        
        $i++;
       
      }
      if ($insert) 
      {
          array_push($msgs, 'You have successfully uploaded a "NOT ON TEMPLATE" record for '.$course_code.' result.');
      } 
      else {
          array_push($errors, 'Uploading a "NOT ON TEMPLATE" record for '.$course_code.' result NOT successfull');
      } 
      }
      

      else{
        array_push ($errors, 'ERROR!!!... Please check the file to ensure that serial number starts from cell number "2"');
      }
          }
mysqli_close($conn);

    }      
  }
}

    ?>
<?php $site = "not_on_list.php?logout='1'"; include 'lecturer_header.php';?>
<form enctype="multipart/form-data" class="form-group" action="not_on_list.php" method="post">
  <div class="alert alert-danger"><?php echo display_error();?></div>
  <P><?php display_msg(); ?></P>
    <?php $header = "Upload Course-Based Result (not on template)"; include 'body.php';?>
    <div class="input-group">
      <span class="input-group-addon" id="basic-addon1">Course Code:</span>
      <input type="text" class="form-control" placeholder="ABC 101" name="course_code" aria-describedby="basic-addon1">
    </div>
    <input style="margin-top: 10px;" type="file" name="course_result_file" accept=".xls, .xlsx, application/vnd/openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"><?php echo "<script src = 'check_file.js'></script>";?>
    <input style="margin-top: 10px;" type="submit" name="Upload" value="Upload Result">
</section>
</main>
</form>

	<?php include 'footer.php';?>
