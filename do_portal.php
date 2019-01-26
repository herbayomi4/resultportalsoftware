<?php include('do_function.php');
if (!isLoggedIn() || $_SESSION['user'] != "Departmental Officer") {
    $_SESSION['msg'] = "You must log in first";
  header('location: index.php');
} ?>
<?php
require_once 'Classes/PHPExcel.php';
$fail = array();
  $pass = array();

  if (isset($_POST['get_result']))
  {
    global $errors, $fail, $pass;
    $session = e($_POST['session']);
    if (empty($session)) {
      array_push($errors, 'Session was not provided');
    }
    if (count($errors)==0) {
      $user = "racohabl_racohabl";
$password = "DvKKVAaazzr7";
$host = "server226.web-hosting.com";
$dbase = "racohabl_oui_result_db";
        $conn = mysqli_connect($host, $user, $password, $dbase);
        if (!$conn)
        {
          die("Connection failed: " . mysqli_connect_error());
        }
        else
        {
    $department = $_POST['department'];
    $Level = $_POST['level'];
    $semester = $_POST['semester'];
    $college = $_POST['college'];
    $number_of_courses = $_POST['number_of_courses'];
    $course_codes = array('course_code_0','course_code_1','course_code_2','course_code_3','course_code_4','course_code_5','course_code_6','course_code_7','course_code_8','course_code_9','course_code_10','course_code_11','course_code_12','course_code_13','course_code_14');
    $num_array = array();
    for ($w=0; $w < $number_of_courses; $w++) { 
    $codec = $_POST[$course_codes[$w]];
      $table_list2 =  strtoupper($session."_".$semester."_".$Level."_".$department."_".$codec);
      $mysqli = mysqli_query($conn, "SELECT * FROM `$table_list2`");
      if(!$mysqli){
        continue;
      }
      $num_rows = mysqli_num_rows($mysqli);
      array_push($num_array, $num_rows);
    }
    $num_array_count = count($num_array);
    $sum = 0;
    for ($j=0; $j < $num_array_count; $j++) { 
      $sum += $num_array[$j];
    }
    $mean = round($sum/$num_array_count);
    for ($p=0; $p < $num_array_count; $p++) { 
      $subtract = $num_array[$p]-$mean;
      if($subtract >0){
        $index = $_POST[$course_codes[$p]];
        break;
      }
      elseif($subtract ==0){
        $index = $_POST[$course_codes[$p]];
      }
    }
    //$course_code1 = mysqli_real_escape_string($conn, trim($_POST['course_code_0']));
    $table_list =  strtoupper($session."_".$semester."_".$Level."_".$department."_".$index); 
    $table_course = $department."_".$Level."_".$semester."_courses";
    $b5_content= $semester.' '.$Level.' LEVEL RESULT SPREADSHEET';
      for ($u=0; $u < $number_of_courses ; $u++) { 
  if (empty(e($_POST['course_code_'.$u]))) {
  array_push($errors, "Course codes provided not up to ".$number_of_courses." course(s)");
}
}
if (count($errors)==0) {    
      $sql_check = "SELECT * FROM `$table_course`";
$result2 = mysqli_query($conn, $sql_check);

if ($result2){

$sql_delete = "TRUNCATE TABLE `$table_course`";
mysqli_query($conn, $sql_delete);
}

$sql_create = "CREATE TABLE IF NOT EXISTS `$table_course` (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
course_code VARCHAR(30) NOT NULL,
course_unit VARCHAR(30) NOT NULL
)ENGINE = INNODB";
mysqli_query($conn, $sql_create);

  $excel = new PHPExcel();
          $excel->setActiveSheetIndex(0);
          $a = 8;
          $sql = "SELECT * FROM `$table_list`";
          $result = mysqli_query($conn, $sql);
      if($result){
      $number_of_student = mysqli_num_rows($result);
          if (mysqli_num_rows($result)>0)
          {
            while($row = mysqli_fetch_assoc($result)){
              $excel ->getActiveSheet()
              ->setCellvalue('A'.$a, $row["id"])
              ->setCellvalue('B'.$a, $row["student_name"])
              ->setCellvalue('C'.$a, $row["student_matric_number"]);
              $a++;
            }

            //array for column identifier"
            for ($m=0; $m < $number_of_courses; $m++){
        $course_code = mysqli_real_escape_string($conn, trim($_POST['course_code_'.$m]));
        $course_unit = mysqli_real_escape_string($conn, trim($_POST['course_unit_'.$m]));
        $sql_course_update = "INSERT INTO `$table_course`(course_code, course_unit) VALUES('$course_code', '$course_unit')";
        mysqli_query($conn, $sql_course_update);
      }
            $column_identifier = array("D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
            for ($z=0; $z < $number_of_courses; $z++) { 
              $course_code = $_POST['course_code_'.$z];
              $course_u = $_POST['course_unit_'.$z];
        $column_ID=$column_identifier[$z];
              $excel->getActiveSheet()
              ->setCellvalue($column_ID.'6', $course_code)
              ->setCellvalue($column_ID.'7', '('.$course_u.')');
              $excel->getActiveSheet()
              ->getColumnDimension($column_ID)->setWidth('8');
              $table_course_result = strtoupper($session."_".$semester."_".$Level."_".$department."_".$course_code);
              $sql_course_result = "SELECT * FROM `$table_course_result`";
              $result_result = mysqli_query($conn, $sql_course_result);
              if ($result_result) {
                if (mysqli_num_rows($result_result)>0) {
                $b = 8;
                while ($row_result = mysqli_fetch_assoc($result_result)) {
                  $excel -> getActiveSheet()
                  ->setCellvalue($column_ID.$b, $row_result["grade"]);
                  $b++;
                }
              }
              else{
                continue;
              }
              }
              else{
                continue;
              }
              
            }
            $column_identifier = array("D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
            $excel->getActiveSheet()->getColumnDimension('A')->setWidth('4');
            $excel->getActiveSheet()->getColumnDimension('B')->setWidth('30');
            $excel->getActiveSheet()->getColumnDimension('C')->setWidth('15');
            $v = $number_of_courses;
            switch ($department) {
      case 'Biochemistry':
        $dept = "Department of CHemical Sciences (Biochemistry Unit)";
        break;
      case 'Industrial Chemistry':
        $dept = "Department of CHemical Sciences (Industrial Chemistry Unit)";
        break;
      case 'Microbiology':
        $dept = "Department of Biological Sciences (Microbiology Unit)";
        break;
      case 'Physics':
        $dept = "Department of Physics with Electronics";
        break;
      case 'Biochemistry':
        $dept = "Department of CHemical Sciences (Biochemistry Unit)";
        break;
      case 'Computer Science':
        $dept = "Department of Mathematical Sciences (Computer Science Unit)";
        break;
      default:
        $dept = "";
        break;
      }
            $excel->getActiveSheet()
            ->setCellvalue('B1', 'ODUDUWA UNIVERSITY IPETUMODU')
            ->setCellvalue('B2', 'COLLEGE OF NATURAL AND APPLIED SCIENCES')
            ->setCellvalue('B3', strtoupper($dept))
            ->setCellvalue('B4', strtoupper($session.' SESSION'))
            ->setCellvalue('B5', strtoupper($b5_content))
            ->setCellvalue('A6', 'S/N')
            ->setCellvalue('B6', 'NAME')
            ->setCellvalue('C6', 'STUDENT ID')
            ->setCellvalue($column_identifier[$v].'6', 'TP')
            ->setCellvalue($column_identifier[$v+1].'6', 'TU')
            ->setCellvalue($column_identifier[$v+2].'6', 'GPA')
            ->setCellvalue($column_identifier[$v+3].'6', 'REMARKS')
            ;
      //GET TOTAL POINT
      $column_identifier = array("D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
      $sql_for_total_point = "SELECT * FROM `$table_list`";
      $r = 8; $g = 1;
      $n = $number_of_courses;
      $result_for_total_point = mysqli_query($conn, $sql_for_total_point);
      if (mysqli_num_rows($result_for_total_point)>0){
        while($row_for_total_point = mysqli_fetch_assoc($result_for_total_point)){
          $value = 0; 
          for ($y=0; $y < $number_of_courses; $y++){
            $column_ID2 = $column_identifier[$y];
            $course_unit = $_POST['course_unit_'.$y];
            $grade = $excel->getActiveSheet()->getCell($column_ID2.$r)->getValue();
            if($grade == "A" || $grade == "B" || $grade == "C" || $grade == "D" || $grade == "F"){
              switch($grade){
                case "A":
                $grade_value = 5;
                break;
                case "B":
                $grade_value = 4;
                break;
                case "C":
                $grade_value = 3;
                break;
                case "D":
                $grade_value = 2;
                break;
                case "F":
                $grade_value = 0;
                break;
                default:
                break;
              }
              $value += $grade_value * $course_unit;
            }
          }
          $g++;
          $excel->getActiveSheet()->setCellvalue($column_identifier[$n].$r, $value);
          //GET TOTAL UNIT
          $course_unit1 = 0;
          for ($noc=0; $noc < $number_of_courses; $noc++){
            $column_ID3 = $column_identifier[$noc];
            $grade2 = $excel->getActiveSheet()->getCell($column_ID3.$r)->getValue();
            if($grade2 == "A" || $grade2 == "B" || $grade2 == "C" || $grade2 == "D" || $grade2 == "F"){
              $course_unit1 += $_POST['course_unit_'.$noc];
             }
          }
          $excel->getActiveSheet()->setCellvalue($column_identifier[$n+1].$r, $course_unit1);
          $value1 = sprintf("%0.2f",$value);
          $course_unit1 = sprintf("%0.2f",$course_unit1);
          if($course_unit1 != 0){
          $gp=$value/$course_unit1;
          $gp = number_format((float)$gp, 2, '.', '');
          $excel->getActiveSheet()->setCellvalue($column_identifier[$n+2].$r, $gp);
          $excel->getActiveSheet()->getStyle($column_identifier[$number_of_courses+2].$r)->getNumberFormat()->setFormatCode('0.00');
          $excel->getActiveSheet()->getStyle($column_identifier[$n+2].$r)->getAlignment()->setHorizontal('center');
          if($gp >= 1.50){
            $remarks = "PASS";
            $pass[] = $gp;
          }
          else if($course_unit1 == 0){
            $remarks = "";
          }
          else if($gp<1.50){
            $remarks = "FAIL";
            $fail[]=$gp;
          }
          $excel->getActiveSheet()->setCellvalue($column_identifier[$n+3].$r, $remarks);
          }
          $r++;
          
        }
      }
      //$sql_number_of_student = "SELECT * FROM `$table_list`";
      //$number_of_student = mysqli_num_rows(mysqli_query($conn, $sql_number_of_student));
      $failNumber = count($fail);
      $failPercent = ($failNumber/$number_of_student)*100;
      $passNumber = count($pass);
      $passPercent = ($passNumber/$number_of_student)*100;
      $num1 = $number_of_student+13;
      $num2 = $number_of_student+14;
      $num4 = $number_of_student+15;
      $num5 = $number_of_student+16;
      $num6 = $number_of_student+17;
      $num7 = $number_of_student+18;
      $num8 = $number_of_student+19;
      $num9 = $number_of_student+20;
      $num3 = $number_of_student+12;
      $excel->getActiveSheet()->setCellvalue('B'.$num3, 'ANALYSIS');
      $excel->getActiveSheet()->setCellvalue('B'.$num1, 'No of Students');
      $excel->getActiveSheet()->setCellvalue('B'.$num2, 'No of Passes');
      $excel->getActiveSheet()->setCellvalue('B'.$num4, 'No of Failure(s)');
      $excel->getActiveSheet()->setCellvalue('B'.$num5, 'Percentage pass');
      $excel->getActiveSheet()->setCellvalue('B'.$num6, 'percentage Failure');
      $excel->getActiveSheet()->setCellvalue('C'.$num8, 'Absent');
      $excel->getActiveSheet()->setCellvalue('C'.$num1, $number_of_student);
      $excel->getActiveSheet()->setCellvalue('C'.$num2, $passNumber);
      $excel->getActiveSheet()->setCellvalue('C'.$num4, $failNumber);
      $excel->getActiveSheet()->setCellvalue('C'.$num5, $passPercent.'%');
      $excel->getActiveSheet()->setCellvalue('C'.$num6, $failPercent.'%');
      $excel->getActiveSheet()->setCellvalue('C'.$num9, 'Examination Malpractice');
      $excel->getActiveSheet()->setCellvalue('B'.$num8, '*');
      $excel->getActiveSheet()->setCellvalue('B'.$num9, '**');
      $excel->getActiveSheet()->getStyle('C'.$num5)->getNumberFormat()->setFormatCode('0.00');
      $excel->getActiveSheet()->getStyle('C'.$num6)->getNumberFormat()->setFormatCode('0.00');
      $excel->getActiveSheet()->getStyle('B'.$num3.':C'.$num9)->applyFromArray(
        array(
          'font'=>array(
            'bold'=>true
          )
        )
      );
      /*$excel->getActiveSheet()->getStyle('H'.$num3.':I'.$num3)->applyFromArray(
        array(
          'font'=>array(
            'bold'=>true
          )
        )
      );
      $excel->getActiveSheet()->mergeCells('F'.$num1.':G'.$num1);
      $excel->getActiveSheet()->mergeCells('F'.$num2.':G'.$num2);
      */
      $excel->getActiveSheet()->getStyle('C'.$num3.':C'.$num6)->getAlignment()->setHorizontal('center');
  
   $objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('OUI Logo');
$objDrawing->setDescription('OUI Logo');
$objDrawing->setPath('images/Oduduwa_University.png');
$objDrawing->setCoordinates('A1');                      
//setOffsetX works properly
$objDrawing->setOffsetX(10); 
$objDrawing->setOffsetY(10);                
//set width, height
$objDrawing->setWidth(200); 
$objDrawing->setHeight(90); 
$objDrawing->setWorksheet($excel->getActiveSheet());
      $h = $_POST['number_of_courses'];
            $excel->getActiveSheet()->mergeCells('B1:'.$column_identifier[$h+3].'1');
            $excel->getActiveSheet()->mergeCells('B2:'.$column_identifier[$h+3].'2');
            $excel->getActiveSheet()->mergeCells('B3:'.$column_identifier[$h+3].'3');
            $excel->getActiveSheet()->mergeCells('B4:'.$column_identifier[$h+3].'4');
            $excel->getActiveSheet()->mergeCells('B5:'.$column_identifier[$h+3].'5');
            $excel->getActiveSheet()->getStyle('B1:'.$column_identifier[$h+3].'5')->applyFromArray(
              array(
                'font'=>array(
                  'bold'=>true,
                  'size'=> 16,
                  'name' => 'Times New Roman'
                )
              )
            );
  $excel->getActiveSheet()->getStyle('B1:'.$column_identifier[$h+3].'1')->applyFromArray(
              array(
                'font'=>array(
                  'bold'=>true,
                  'size'=> 22,
                  'name' => 'Times New Roman'
                )
              )
            );
            $excel->getActiveSheet()->getStyle('B2:'.$column_identifier[$h+3].'2')->applyFromArray(
              array(
                'font'=>array(
                  'bold'=>true,
                  'size'=> 16,
                  'name' => 'Times New Roman'
                )
              )
            );


            /*$styleArray = array(
              'font' =>array(
                'size'=> 8,
                'name' => 'Times New Roman'
              )
            );
            $excel->getActiveSheet()->getStyle('A6:'.$column_identifier[$h+3].'6')->applyFromArray($styleArray);*/
            $excel->getActiveSheet()->getStyle('B1:'.$column_identifier[$h+3].'5')->getAlignment()->setHorizontal('center');
            $excel->getActiveSheet()->getStyle('D6:'.$column_identifier[$h+3].'6')->getAlignment()->setHorizontal('center');
            $excel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal('center');
            $excel->getActiveSheet()->getStyle('A6:'.$column_identifier[$h+3].'6')->applyFromArray(
              array(
                'font'=>array(
                  'bold'=>true
                )
              )
            );
            $p =8;
            while ($excel->getActiveSheet()->getCell('C'.$p)->getValue() !="") {
              $excel->getActiveSheet()->getStyle('A7:A'.$p)->getAlignment()->setHorizontal('center');
        $excel->getActiveSheet()->getStyle($column_identifier[$n+3].'8:'.$column_identifier[$n+3].$p)->getAlignment()->setHorizontal('center');
              $excel->getActiveSheet()->getStyle('D7:'.$column_identifier[$n+6].$p)->getAlignment()->setHorizontal('center');
              $p++;
            }
            $file = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
            header('Content-Type: application/x-msexcel');
            header('Content-Disposition: attachment; filename= "'.$Level.' Result.xls"');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            $file->save('php://output');
          }
          
      }
      else{
            array_push($errors, "Template not found in the database");
          } 
          
        
}
        }  
    }
    
      }
          
      ?>
<?php $site = "do_portal.php?logout='1'"; include 'do_header.php';?>
<form class="form-group" action="do_portal.php" method="post">
 <div class = "alert alert-warning"> <?php echo display_error();?>  </div>
    <?php $header = "Obtain Level Results"; include 'body.php';?>
 
        <div class="input-group">
  <span class="input-group-addon" id="basic-addon1">NUMBER OF COURSES</span>
  <select type="text" class="form-control" id="number_of_courses" name="number_of_courses" aria-describedby="basic-addon1">
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
            <option>6</option>
            <option>7</option>
            <option>8</option>
            <option>9</option>
            <option>10</option>
            <option>11</option>
            <option>12</option>
            <option>13</option>
            <option>14</option>
            <option>15</option>
  </select>
  <script type="text/javascript">
    document.getElementById('number_of_courses').value = "<?php echo $_POST['number_of_courses'];?>";
  </script>
</div>
<input style="margin-top: 10px;" type = "submit" value = "Continue" name = "display" >
<?php
if (isset($_POST['display'])) {
   $number_of_courses = $_POST['number_of_courses'];
   echo '<div class="row">
          <div class="col-md-6 col-xs-6">';

            for ($y=0; $y <$number_of_courses ; $y++) { 
               echo '<div class="input-group">
              <span class="input-group-addon" id="basic-addon1">Course Code:</span>
              <input type="text" class="form-control" placeholder="ABC 101" name="course_code_'.$y.'" aria-describedby="basic-addon1">
            </div>';
             } 
            
                      
          echo '</div><div class="col-md-4 col-xs-6">';
            for ($c=0; $c < $number_of_courses ; $c++) { 
              echo '<div class="input-group">
              <span class="input-group-addon" id="basic-addon1">Course Unit:</span>
              <select class="form-control" name="course_unit_'.$c.'" aria-describedby="basic-addon1">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
                <option>6</option>
              </select>
            </div>';
            }
            echo '</div></div><input style="margin-top: 10px;" type = "submit" value = "Get Result" name = "get_result" >';
          }
            ?>
            
  
    </section>
  </main>
      </form>        
  <?php include 'footer.php';?>