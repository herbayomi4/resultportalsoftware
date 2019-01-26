<?php require('fpdf/fpdf.php'); include('lecturer_function.php');
if (!isLoggedIn() || $_SESSION['user'] != "Lecturer") {
    $_SESSION['msg'] = "You must log in first";
  header('location: index.php');
  }
$errors   = array(); 
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
if (isset($_POST['submit'])) {
  global $conn, $errors;
  include 'dbConnect.php';
  $department = $_POST['department'];
  $Level = $_POST['level'];
  $session = e($_POST['session']);
  $semester = $_POST['semester'];
  $matric_number = e($_POST['matric_number']);
  $_SESSION['college'] = $_POST['college'];
  $_SESSION['department'] = $department;
  $_SESSION['session'] =  $session;
  $_SESSION['semester'] = $semester;
  $_SESSION['Level'] = $Level;
  $_SESSION['matric_number'] = $matric_number;  
  
  switch ($department) {
			case 'Biochemistry':
				$_SESSION['dept'] = "Department of CHemical Sciences (Biochemistry Unit)";
				break;
			case 'Industrial Chemistry':
				$_SESSION['dept'] = "Department of CHemical Sciences (Industrial Chemistry Unit)";
				break;
			case 'Microbiology':
				$_SESSION['dept'] = "Department of Biological Sciences (Microbiology Unit)";
				break;
			case 'Physics':
				$_SESSION['dept'] = "Department of Physics/Electronics";
				break;
			case 'Biochemistry':
				$_SESSION['dept'] = "Department of CHemical Sciences (Biochemistry Unit)";
				break;
			case 'Computer Science':
				$_SESSION['dept'] = "Department of Mathematical Sciences (Computer Science Unit)";
				break;
			default:
				$_SESSION['dept'] = "";
				break;
			}
  
  
if (empty($matric_number)) {
    array_push($errors, "Matric number is required");
  }
  if (empty($session)) {
    array_push($errors, "Session is required");
  }
  if(count($errors)==0){
    $table = $session."_".$semester."_".$department."_".$Level."_Final_Result";
  $table_course = $department."_".$Level."_".$semester."_courses";
  $sql_get_result = "SELECT * FROM `$table` WHERE student_matric_number = '$matric_number'";
  $Result = mysqli_query($conn, $sql_get_result);
  if ($Result) {
    if(mysqli_num_rows($Result)>0){
    $row_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT student_name, tp, tu, gp, ctu, ctp, cgp FROM `$table` WHERE student_matric_number = '$matric_number'"));
    $_SESSION['name'] = $row_name['student_name'];
    $_SESSION['tp'] = $row_name['tp'];
    $_SESSION['tu'] = $row_name['tu'];
    $_SESSION['gp'] = $row_name['gp'];
    $_SESSION['ctp'] = $row_name['ctp'];
    $_SESSION['ctu'] = $row_name['ctu'];
    $_SESSION['cgp'] = $row_name['cgp'];
    include 'student_result_format.php';
    $sql_course = "SELECT course_code FROM `$table_course`";
    $result = mysqli_query($conn, $sql_course);
    $i = 0;
    while ($row_course = mysqli_fetch_array($result)) {
      $column=strtoupper($row_course['course_code']);
      
        $sql_check_grade = "SELECT `$column` FROM `$table` WHERE student_matric_number = '$matric_number'";
        $result_check_grade = mysqli_query($conn, $sql_check_grade);
        while ($row_grade = mysqli_fetch_assoc($result_check_grade)){
          if ($row_grade[$column] == "A" || $row_grade[$column] == "B" || $row_grade[$column] == "C" || $row_grade[$column] == "D"  || $row_grade[$column] == "F" ){
            $pdf->Cell(10);
            $pdf->Cell($width_cell[0],7,$column,1,0,'C');
            $ct = mysqli_fetch_assoc(mysqli_query($conn, "SELECT course_title FROM course_details WHERE course_code = '$column'"));
            $course_title = strtoupper($ct['course_title']);
            $pdf->Cell($width_cell[1],7,$course_title,1,0,'J');
            $cu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT course_unit FROM `$table_course` WHERE course_code = '$column'"));
            $course_unit = $cu['course_unit'];
            $pdf->Cell($width_cell[2],7,$course_unit,1,0,'C');
            $tbl = strtoupper($session."_".$semester."_".$Level."_".$department."_".$column);
            $sc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT total FROM `$tbl` WHERE student_matric_number = '$matric_number'"));
            $score = $sc['total'];
            $pdf->Cell($width_cell[3],7,$score,1,0,'C');
            $gr = mysqli_fetch_assoc(mysqli_query($conn, "SELECT grade FROM `$tbl` WHERE student_matric_number = '$matric_number'"));
            $grade = $gr['grade'];
            $pdf->Cell($width_cell[3],7,$grade,1,1,'C');
          }
        }
      
    }
    $pdf->Output();
  }
  else{
    array_push($errors, $matric_number." result for ".$session." ".$Level." ".$semester." semester NOT available");
  }    
  }
  else{
    array_push($errors, "Result for ".$semester." NOT found, Ensure the college officer has uploaded the final result.");
  }
  
  }

  
}
?>
<?php include 'lecturer_header.php';?>
<form class="form-group" action="student_portal.php" method="post">
<div class="alert alert-warning"><?php echo display_error();?></div>
<?php $header = "Download Semester Result"; include 'body.php';?>
<div class="input-group">
  <span class="input-group-addon" id="basic-addon1">MATRIC NUMBER:</span>
  <input type="text" class="form-control" name="matric_number" placeholder="U/10/MB/0001" aria-describedby="basic-addon1">
</div>
<input style="margin-top: 10px; margin-bottom: 10px;" type="submit" name="submit" value="Download Result">
</section>
</main>
</form>
<?php include 'footer.php';?>