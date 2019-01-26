<?php include('do_function.php');
if (!isLoggedIn() || $_SESSION['user'] != "college officer") {
    $_SESSION['msg'] = "You must log in first";
  header('location: index.php');
} ?>
<?php	
    require_once 'Classes/PHPExcel.php';
    global $errors;
    $fail = array();
  $pass = array();	
    if (isset($_POST['get_result'])) 
    {
		global $fail, $pass;
		$department = $_POST['department'];		
		$Level = $_POST['level'];
		$session = e($_POST['session']);
		$semester = $_POST['semester'];
		$college = $_POST['college'];
		$tbl_result = $session."_".$semester."_".$department."_".$Level."_Result";
		$table_course = $department."_".$Level."_".$semester."_courses";
		$tbl_cumulative = $department."_cumulative";
		
		include 'dbConnect.php';
    if (empty($session)) {
      array_push($errors, 'ERROR!!!... Session is needed');
    }
    if (count($errors)==0) {
      $excel = new PHPExcel();
      $excel->setActiveSheetIndex(0);
      //get number of courses from course table_course
      $sql_number_of_courses = "SELECT course_code FROM `$table_course`";
      $res = mysqli_query($conn, $sql_number_of_courses);
      $number_of_courses = mysqli_num_rows($res);
      $column_identifier = array("D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W","X", "Y", "Z");
      $a = 8;
      $sql = "SELECT * FROM `$tbl_result`";
      $result = mysqli_query($conn, $sql);
      if ($result) {
        $number_of_student = mysqli_num_rows($result);
      if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_assoc($result)){
        $excel ->getActiveSheet()
              ->setCellvalue('A'.$a, $row["id"])
              ->setCellvalue('B'.$a, $row["student_name"])
              ->setCellvalue('C'.$a, $row["student_matric_number"]);
        $a++; 
        }
      }
      }
      else{
        array_push($errors, 'Result for '.$session.' '.$semester.' '.$Level. 'Level '.$department.' NOT found');
      }
      
      $sql_course = "SELECT * FROM `$table_course`";
      $result_course = mysqli_query($conn, $sql_course);
      $number_of_courses = mysqli_num_rows($result_course);
      if($number_of_courses > 0){
        $i = 0;
        while($row_course = mysqli_fetch_assoc($result_course)){
          $excel ->getActiveSheet()
          ->setCellvalue($column_identifier[$i].'6', $row_course["course_code"])
          ->setCellvalue($column_identifier[$i].'7', '('.$row_course["course_unit"].')');
          $i++;
        }
        for ($z=0; $z < $number_of_courses; $z++) { 
              $course_code = $excel->getActiveSheet()->getCell($column_identifier[$z].'6')->getValue();
        $column_ID=$column_identifier[$z];
              $excel->getActiveSheet()
              ->setCellvalue($column_ID.'6', $course_code);
              $excel->getActiveSheet()
              ->getColumnDimension($column_ID)->setWidth('8');
              $table_course_result = strtoupper($session."_".$semester."_".$Level."_".$department."_".$course_code);
              $sql_course_result = "SELECT grade FROM `$table_course_result`";
              $result_result = mysqli_query($conn, $sql_course_result);
              if ($result_result){
              if (mysqli_num_rows($result_result)>0) {
                $b = 8;
                while ($row_result = mysqli_fetch_assoc($result_result)) {
                  $excel -> getActiveSheet()
                  ->setCellvalue($column_ID.$b, $row_result['grade']);
                  $b++;
                }
              }
              else{
                array_push($errors, "No record found in database result");
              }
              }
              else{
                array_push($errors, "Result not found in database");
              }
            }
      //$sql_list = "SELECT student_matric_number FROM `$table_list`";
      //$result_list = mysqli_query($conn, $sql_list);
      //if(mysqli_num_rows($result_list)){
        //while($row2 = mysqli_fetch_assoc($result_list)){
        $e = 8;
        
          $sql2 = "SELECT TU, TP, GP FROM `$tbl_result`";
          $result2 = mysqli_query($conn, $sql2);
          if($result || mysqli_num_rows($result2)>0){
            while($row2 = mysqli_fetch_assoc($result2)){
              $excel->getActiveSheet()
              ->setCellvalue($column_identifier[$number_of_courses].$e, $row2["TP"])
              ->setCellvalue($column_identifier[$number_of_courses+1].$e, $row2["TU"])
              ->setCellvalue($column_identifier[$number_of_courses+2].$e, $row2["GP"])
              ->getStyle($column_identifier[$number_of_courses+2].$e)->getNumberFormat()->setFormatCode('0.00');
                        $e++;
            }
          } 
          $f = 8;
          /*$sql3 = "SELECT student_matric_number FROM `$table_list`";
          $result3 = mysqli_query($conn, $sql3);
          if($result3 || mysqli_num_rows($result3)){
            while($row3 = mysqli_fetch_assoc($result3)){
              $stu = $row3["student_matric_number"];*/
              while($excel->getActiveSheet()->getCell('C'.$f)->getValue() !=""){
                $stu = $excel->getActiveSheet()->getCell('C'.$f)->getValue();
                $sql4 = "SELECT * FROM `$tbl_cumulative` WHERE student_matric_number = '$stu'";
              $result4 = mysqli_query($conn, $sql4);
              if($result4 || mysqli_num_rows($result4)){
                while($row4 = mysqli_fetch_assoc($result4)){
                  $excel->getActiveSheet()
                  ->setCellvalue($column_identifier[$number_of_courses+3].$f, $row4["ctp"])
                  ->setCellvalue($column_identifier[$number_of_courses+4].$f, $row4["ctu"])
                  ->setCellvalue($column_identifier[$number_of_courses+5].$f, $row4["cgp"]);
                  if($row4["cgp"] >= 1.50){
                    $remarks = "PASS";
                    $pass[] = $row4['cgp'];
                  }
                        else if($row4["cgp"]<1.50){
                        $remarks = "FAIL";
                        $fail[] = $row4['cgp'];
                        }
                  $excel->getActiveSheet()
                  ->setCellvalue($column_identifier[$number_of_courses+6].$f, $remarks)
                  ->getStyle($column_identifier[$number_of_courses+5].$f)->getNumberFormat()->setFormatCode('0.00');
                }
              }
              $f++;
              }
              
            //}
          //}
        //}
      //}
      
      }
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
      $v = $number_of_courses;
            $excel->getActiveSheet()
            ->setCellvalue('B1', 'ODUDUWA UNIVERSITY IPETUMODU')
            ->setCellvalue('B2', strtoupper($college))
            ->setCellvalue('B3', strtoupper($department))
            ->setCellvalue('B4', strtoupper($session))
            ->setCellvalue('B5', strtoupper($semester." ".$Level." level"." result spreadsheet"))
            ->setCellvalue('A6', 'S/N')
            ->setCellvalue('B6', 'NAME')
            ->setCellvalue('C6', 'MATRIC')
            ->setCellvalue($column_identifier[$v].'6', 'TP')
            ->setCellvalue($column_identifier[$v+1].'6', 'TU')
            ->setCellvalue($column_identifier[$v+2].'6', 'GPA')
      ->setCellvalue($column_identifier[$v+3].'6', 'CTP')
      ->setCellvalue($column_identifier[$v+4].'6', 'CTU')
      ->setCellvalue($column_identifier[$v+5].'6', 'CGPA')
            ->setCellvalue($column_identifier[$v+6].'6', 'REMARKS')
            ;

            $failNumber = count($fail);
      $failPercent = ($failNumber/$number_of_student)*100;
      $passNumber = count($pass);
      $passPercent = ($passNumber/$number_of_student)*100;
      $num1 = $number_of_student+13;
      $num2 = $number_of_student+14;
      $num3 = $number_of_student+12;
      $excel->getActiveSheet()->setCellvalue('F'.$num1, 'Number of Students');
      $excel->getActiveSheet()->setCellvalue('F'.$num2, 'Percentage (%)');
      $excel->getActiveSheet()->setCellvalue('H'.$num3, 'FAILED');
      $excel->getActiveSheet()->setCellvalue('I'.$num3, 'PASSED');
      $excel->getActiveSheet()->setCellvalue('H'.$num1, $failNumber);
      $excel->getActiveSheet()->setCellvalue('I'.$num1, $passNumber);
      $excel->getActiveSheet()->setCellvalue('H'.$num2, $failPercent);
      $excel->getActiveSheet()->setCellvalue('I'.$num2, $passPercent);
      $excel->getActiveSheet()->getStyle('H'.$num2.':I'.$num2)->getNumberFormat()->setFormatCode('0.00');
      $excel->getActiveSheet()->getStyle('F'.$num1.':F'.$num2)->applyFromArray(
        array(
          'font'=>array(
            'bold'=>true
          )
        )
      );
      $excel->getActiveSheet()->getStyle('H'.$num3.':I'.$num3)->applyFromArray(
        array(
          'font'=>array(
            'bold'=>true
          )
        )
      );
      $excel->getActiveSheet()->mergeCells('F'.$num1.':G'.$num1);
      $excel->getActiveSheet()->mergeCells('F'.$num2.':G'.$num2);
      
      $excel->getActiveSheet()->getStyle('H'.$num3.':I'.$num2)->getAlignment()->setHorizontal('center');

      $n = $number_of_courses;
            $excel->getActiveSheet()->mergeCells('B1:'.$column_identifier[$n+6].'1');
            $excel->getActiveSheet()->mergeCells('B2:'.$column_identifier[$n+6].'2');
            $excel->getActiveSheet()->mergeCells('B3:'.$column_identifier[$n+6].'3');
            $excel->getActiveSheet()->mergeCells('B4:'.$column_identifier[$n+6].'4');
            $excel->getActiveSheet()->mergeCells('B5:'.$column_identifier[$n+6].'5');
            $excel->getActiveSheet()->getStyle('B1:'.$column_identifier[$n+6].'5')->applyFromArray(
              array(
                'font'=>array(
                  'bold'=>true,
                  'size'=> 11,
                  'name' => 'Times New Roman'
                )
              )
            );
             $excel->getActiveSheet()->getStyle('B1:'.$column_identifier[$h+6].'1')->applyFromArray(
              array(
                'font'=>array(
                  'bold'=>true,
                  'size'=> 16,
                  'name' => 'Times New Roman'
                )
              )
            );
            $excel->getActiveSheet()->getStyle('B2:'.$column_identifier[$h+6].'2')->applyFromArray(
              array(
                'font'=>array(
                  'bold'=>true,
                  'size'=> 12,
                  'name' => 'Times New Roman'
                )
              )
            );
            $excel->getActiveSheet()->getStyle('B1:'.$column_identifier[$n+6].'5')->getAlignment()->setHorizontal('center');
      $excel->getActiveSheet()->getStyle('D6:'.$column_identifier[$n+6].'6')->getAlignment()->setHorizontal('center');
      $excel->getActiveSheet()->getStyle('D7:'.$column_identifier[$n-1].'7')->getAlignment()->setHorizontal('center');
            $excel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal('center');
            $excel->getActiveSheet()->getStyle('A6:'.$column_identifier[$n+6].'6')->applyFromArray(
              array(
                'font'=>array(
                  'bold'=>true,
                )
              )
            );
            $styleArray = array(
              'font' =>array(
                'size'=> 8,
                'name' => 'Times New Roman'
              )
            );
            $num = $number_of_student +7;
            $excel->getActiveSheet()->getStyle('A6:'.$column_identifier[$n+6].$num)->applyFromArray($styleArray);
      $excel->getActiveSheet()->getColumnDimension('A')->setWidth('3');
            $excel->getActiveSheet()->getColumnDimension('B')->setWidth('25');
            $excel->getActiveSheet()->getColumnDimension('C')->setWidth('12');
      
      $p =8;
      while ($excel->getActiveSheet()->getCell('B'.$p)->getValue() !="") {
        $excel->getActiveSheet()->getStyle('A8:A'.$p)->getAlignment()->setHorizontal('center');
        $excel->getActiveSheet()->getStyle($column_identifier[$n+6].'8:'.$column_identifier[$n+6].$p)->getAlignment()->setHorizontal('center');
        $excel->getActiveSheet()->getStyle('D8:'.$column_identifier[$n+6].$p)->getAlignment()->setHorizontal('center');
        $p++;
      }
          
    

mysqli_close($conn);

      
  $file = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
            header('Content-Type: application/x-msexcel');
            header('Content-Disposition: attachment; filename= "'.$department.' '.$Level.' Result.xls"');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            $file->save('php://output');
    }
			
}
    ?>
<?php $site = "co_get_result.php?logout='1'"; include 'co_header.php';?>
<form class="form-group" action="co_get_result.php" method="post">
  <?php echo display_error();?>
	<?php $header = "Obtain Cumulative Result"; include 'body.php';?>
    <input style="margin-top: 10px;" type="submit" name="get_result" value="Get Result">
</section>
</main>
</form>
<?php include 'footer.php';?>