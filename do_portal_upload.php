<?php ini_set('max_execution_time', 300);
require_once 'Classes/PHPExcel.php';
include('do_function.php');
if (!isLoggedIn() || $_SESSION['user'] != "Departmental Officer") {
    $_SESSION['msg'] = "You must log in first";
  header('location: index.php');
} 
$msgs =array();
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
      global $errors;
       $session = e($_POST['session']);
       if (empty($session)) {
         array_push($errors, 'Session was not provided');
       }
      if (empty($_FILES['result_file']['tmp_name'])) {
    array_push($errors, "Result file is required, use the choose file button");
  }
  if (count($errors)==0) {
    $department = $_POST['department'];
    $Level = $_POST['level'];
    $semester = $_POST['semester'];
    $table_course = $department."_".$Level."_".$semester."_courses";
    $table = $session."_".$semester."_".$department."_".$Level."_Result";
    $tbl_cumulative = $department."_cumulative";
    
$user = "racohabl_racohabl";
$password = "DvKKVAaazzr7";
$host = "server226.web-hosting.com";
$dbase = "racohabl_oui_result_db"; 
    $conn = mysqli_connect($host, $user, $password, $dbase);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    else{
      $sql_create1 = "CREATE TABLE IF NOT EXISTS `$tbl_cumulative` (
      student_matric_number VARCHAR(30) NOT NULL PRIMARY KEY,
      ctp INT(3) NULL,
      ctu INT(3) NULL,
      cgp DECIMAL(3,2) NULL
      )ENGINE = INNODB";
      mysqli_query($conn, $sql_create1);
      $sql_check = "SELECT * FROM `$table`";
$result = mysqli_query($conn, $sql_check);

if ($result){
array_push($errors, "This result has been uploaded before. Exam officer can only upload a result once!");
//$sql_delete = "TRUNCATE TABLE `$table`";
//mysqli_query($conn, $sql_delete);
}
else{
$sql_create = "CREATE TABLE IF NOT EXISTS `$table` (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
student_name VARCHAR(50) NOT NULL,
student_matric_number VARCHAR(30) NOT NULL
)ENGINE = INNODB";


if (mysqli_query($conn, $sql_create)) {
      $excel = new PHPExcel();
          $excel = PHPExcel_IOFactory::load($_FILES['result_file']['tmp_name']);
          $excel->setActiveSheetIndex(0);
          if ($excel->getActiveSheet()->getCell('A8')->getValue() == "1") {
            $sql_course = "SELECT id FROM `$table_course`";
          $number_of_courses = mysqli_num_rows(mysqli_query($conn, $sql_course));
          $column_identifier = array("D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
          $noc = $number_of_courses - 1;
    for ($x=$noc; $x >=0; $x--) { 
      $column_ID= $column_identifier[$x];
      $new_column = $excel->getActiveSheet()->getCell($column_ID.'6')->getValue();
      $sql_add_column = "ALTER TABLE `$table` ADD `$new_column` VARCHAR(3) after student_matric_number";
      mysqli_query($conn, $sql_add_column);
    }
    $column_ID1 = $column_identifier[$noc];
    $new_column1 = $excel->getActiveSheet()->getCell($column_ID1.'6')->getValue(); 
    $sql_add_column1 = "ALTER TABLE `$table` ADD TP INT(3) after `$new_column1`";
    mysqli_query($conn, $sql_add_column1);
    $sql_add_column2 = "ALTER TABLE `$table` ADD TU INT(3) after TP";
    mysqli_query($conn, $sql_add_column2);
    $sql_add_column3 = "ALTER TABLE `$table` ADD GP DECIMAL(3,2) after TU";
    mysqli_query($conn, $sql_add_column3);
    $sql_add_column4 = "ALTER TABLE `$table` ADD REMARKS VARCHAR(10) after GP";
    mysqli_query($conn, $sql_add_column4);

      $i= 8;
      while ($excel->getActiveSheet()->getCell('A'.$i)->getValue() !="") 
      {
        $student_name=$excel->getActiveSheet()->getCell('B'.$i)->getValue();
        $student_matric=$excel->getActiveSheet()->getCell('C'.$i)->getValue();

        $sql = "INSERT INTO `$table`(student_name, student_matric_number) VALUES ('$student_name','$student_matric')";


        $i++;

        mysqli_query($conn, $sql);
        if(mysqli_num_rows(mysqli_query($conn, "SELECT student_matric_number FROM `$tbl_cumulative` WHERE student_matric_number = '$student_matric'"))==0){
          $sql1 = "INSERT INTO `$tbl_cumulative`(student_matric_number) VALUES ('$student_matric')";
           mysqli_query($conn, $sql1);
        }

      }

      $column_ID3= $column_identifier[$number_of_courses];
      $column_ID4= $column_identifier[$number_of_courses+1];
      $column_ID5= $column_identifier[$number_of_courses+2];
      $column_ID6= $column_identifier[$number_of_courses+3];
      for ($y=0; $y < $number_of_courses; $y++) { 
        $column_ID2= $column_identifier[$y];
        $column_name = $excel->getActiveSheet()->getCell($column_ID2.'6')->getValue();
        $j=8; 
        $id = 1;
        while ($excel->getActiveSheet()->getCell('C'.$j)->getValue() !=""){
          $student_matric=$excel->getActiveSheet()->getCell('C'.$j)->getValue();
          $grade = $excel->getActiveSheet()->getCell($column_ID2.$j)->getValue();
          $tp = $excel->getActiveSheet()->getCell($column_ID3.$j)->getValue();
          $tu = $excel->getActiveSheet()->getCell($column_ID4.$j)->getValue();
          $gp = $excel->getActiveSheet()->getCell($column_ID5.$j)->getValue();
          $remarks = $excel->getActiveSheet()->getCell($column_ID6.$j)->getValue();
          $sql_update = "UPDATE `$table` SET `$column_name`='$grade', TP ='$tp', TU='$tu', GP='$gp', REMARKS ='$remarks' WHERE student_matric_number='$student_matric'";
          
          
          $update = mysqli_query($conn, $sql_update);
         
          //INSERT INTO table CTP, CTU and CGP //get add update
          
          
      $j++; 
          $id++;
        }
      }
    $g = 8;
    while ($excel->getActiveSheet()->getCell('A'.$g)->getValue() !=""){
     $student_matric_number = $excel->getActiveSheet()->getCell('C'.$g)->getValue();
     $tp = $excel->getActiveSheet()->getCell($column_ID3.$g)->getValue();
          $tu = $excel->getActiveSheet()->getCell($column_ID4.$g)->getValue();
          $gp = $excel->getActiveSheet()->getCell($column_ID5.$g)->getValue();
          $sql_cumulative = "SELECT ctp, ctu FROM `$tbl_cumulative` WHERE student_matric_number = '$student_matric_number'";
          $get = mysqli_query($conn, $sql_cumulative);
          if (mysqli_num_rows($get)) {
            while ($get_row = mysqli_fetch_assoc($get)) {
        $ctp = $get_row['ctp'];
        $ctu = $get_row['ctu'];
              if($tu > 0){
        $ctp += $tp;
        $ctu += $tu;
        $ctp = sprintf("%.2f", $ctp);
        $ctu = sprintf("%.2f", $ctu);
        $cgp = $ctp/$ctu;
        $cgp = number_format((float)$cgp, 2, '.', '');
        $sql_cumulative_update = "UPDATE `$tbl_cumulative` SET ctp = '$ctp', ctu = '$ctu', cgp = '$cgp' WHERE student_matric_number='$student_matric_number'";
        $cumulative_update = mysqli_query($conn, $sql_cumulative_update);
        }
      }
      }
      $g++;
    }
      if ($update &&  $cumulative_update) 
      {
          array_push($msgs, 'You have successfully uploaded a record for '.$session.' '.$semester.', '.$department.' '.$Level.' result');
      } 
      else {
          array_push($errors, 'ERROR!!!... Uploading a record for '.$session.' '.$semester.', '.$department.' '.$Level.' result NOT successfull.');
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
    
}?>
<?php $site = "do_portal_upload.php?logout='1'"; include 'do_header.php';?>
    <form name="get_list_form" class="form-group" enctype="multipart/form-data" action="do_portal_upload.php" method="post">
      <div class = "alert alert-warning"> <?php echo display_error();?>  </div>
      <p><?php echo display_msg(); ?></p>
  <?php $header = "Upload Level Result"; include 'body.php';?>
  <input style="margin-top: 10px;" type="file" name="result_file" accept=".xls, .xlsx, application/vnd/openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"><?php echo "<script src = 'check_file.js'></script>";?>
    <input style="margin-top: 10px;" type="submit" name="upload_result" value="Upload">
</section>
</main>
</form>
<?php include 'footer.php';?>