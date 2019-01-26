<?php include('functions.php');
if (!isLoggedIn() || $_SESSION['user'] != "college officer") {
    $_SESSION['msg'] = "You must log in first";
  header('location: index.php');
} ?>
<?php ini_set('max_execution_time', 300);
		
    require_once 'Classes/PHPExcel.php';
	$msgs = array();
		
    if (isset($_POST['submit_senate_list'])) 
    {
    	$session = e($_POST['session']);
		global $errors;
      if (empty($_FILES['senate_list_file']['tmp_name'])) {
    array_push($errors, "Senate list file is required, use the choose file button");
  } if (empty($session)) {
  	array_push($errors, 'Session not provided');
  }
  if (count($errors)==0){
  	$department = $_POST['department'];
		$Level = $_POST['level'];
		$semester = $_POST['semester'];
		$table = $session."_".$semester."_".$Level."_".$department;
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
			$sql_check = "SELECT * FROM `$table`";
			$result = mysqli_query($conn, $sql_check);
			if ($result)
			{
				$sql_delete = "TRUNCATE TABLE `$table`";
				mysqli_query($conn, $sql_delete);
			}
			$excel = new PHPExcel();
			$excel = PHPExcel_IOFactory::load($_FILES['senate_list_file']['tmp_name']);
			$excel->setActiveSheetIndex(0);
			$sql_create = "CREATE TABLE IF NOT EXISTS `$table` (
			id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
			student_name VARCHAR(30) NOT NULL,
			student_matric_number VARCHAR(30) NOT NULL,
			student_level VARCHAR(30) NOT NULL
			)ENGINE = INNODB";
			if (mysqli_query($conn, $sql_create)) 
			{
				$i= 5;
				if ($excel->getActiveSheet()->getCell('A'.$i)->getValue() =="1") {
					while ($excel->getActiveSheet()->getCell('A'.$i)->getValue() !="") 
				{
					$student_matric=$excel->getActiveSheet()->getCell('B'.$i)->getValue();
					$level=$excel->getActiveSheet()->getCell('D'.$i)->getValue();
					$student_name=$excel->getActiveSheet()->getCell('E'.$i)->getValue();
					$sql = "INSERT INTO `$table`(student_name, student_matric_number, student_level) VALUES ('$student_name','$student_matric', '$Level')";
					$res = mysqli_query($conn, $sql); 
					$i++;
				}
				if ($res) 
				{
					array_push($msgs, 'You have successfully uploaded a record for '.$session.' '.$semester.', '.$department.' '.$Level.' Senate List');
				} 
				else 
				{
					array_push($errors, 'Uploading a record for '.$session.' '.$semester.', '.$department.' '.$Level.' Senate List NOT successfull. Ensure a valid file format is choosen for upload.');
				}
				}
				else{
					array_push($errors, 'ERROR!!!...Please check the file to ensure that serial number starts from cell number "5"');
				}
				
				
			}
			mysqli_close($conn);
		}
  }
		
	}
	?>

<?php $site = "co_upload_result.php?logout='1'"; include 'co_header.php';?>
<form id="senate_list_upload" name="senate_list_upload" enctype="multipart/form-data" class="form-group" action="co_portal.php" method="post">
	<div class = "alert alert-warning"> <?php echo display_error();?>  </div> <p><?php echo display_msg();?></p>
    <?php $header = "Upload Template"; include 'body.php';?>
    <input style="margin-top: 10px;" type="file" name="senate_list_file" id="file" accept=".xls, .xlsx, application/vnd/openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"><?php echo "<script src = 'check_file.js'></script>";?>
    <input style="margin-top: 10px; margin-bottom: 10px;" type="submit" name="submit_senate_list" value="Upload">
</section>
</main>
</form>


	<?php include 'footer.php';?>