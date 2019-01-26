<?php include('functions.php');
if (!isLoggedIn() || $_SESSION['user'] != "Lecturer") {
    $_SESSION['msg'] = "You must log in first";
  header('location: index.php');
} ?>
<?php require_once 'Classes/PHPExcel.php'; 
global $msgs, $errors;     
        if (isset($_POST['download_list']))
        {     
        $user = "racohabl_racohabl";
$password = "DvKKVAaazzr7";
$host = "server226.web-hosting.com";
$dbase = "racohabl_oui_result_db";
        $conn = mysqli_connect($host, $user, $password, $dbase);
        if (!$conn)
        {
          die("Connection to database failed");
        }
        else
        {   
        $department = $_POST['department'];
        $Level = $_POST['level'];
        $session = mysqli_real_escape_string($conn, trim($_POST['session']));
          if (empty($session)) {
            array_push($errors, 'Session was not provided');
          }
          if(count($errors)==0){
            $semester = $_POST['semester'];
        $college = $_POST['college'];
        $table = $session."_".$semester."_".$Level."_".$department;

          $excel = new PHPExcel();
          $excel->setActiveSheetIndex(0);
          $i = 7;
          $sql = "SELECT * FROM `$table`";
          $result = mysqli_query($conn, $sql);
          if ($result) {
            $number_of_student = mysqli_num_rows($result);
          if (mysqli_num_rows($result)>0)
          {
            while($row = mysqli_fetch_assoc($result)){
              $excel ->getActiveSheet()
              ->setCellvalue('A'.$i, $row["id"])
              ->setCellvalue('B'.$i, $row["student_name"])
              ->setCellvalue('C'.$i, $row["student_matric_number"]);
              $i++;
            }
            $excel->getActiveSheet()->getColumnDimension('A')->setWidth('5');
            $excel->getActiveSheet()->getColumnDimension('B')->setWidth('30');
            $excel->getActiveSheet()->getColumnDimension('C')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('D')->setWidth('8');
            $excel->getActiveSheet()->getColumnDimension('E')->setWidth('8');
            $excel->getActiveSheet()->getColumnDimension('F')->setWidth('8');
            $excel->getActiveSheet()->getColumnDimension('G')->setWidth('8');
            $excel->getActiveSheet()->getColumnDimension('H')->setWidth('8');
            $excel->getActiveSheet()
            ->setCellvalue('B1', 'Oduduwa University Ipetumodu')
            ->setCellvalue('B2', $college)
            ->setCellvalue('B3', $department)
            ->setCellvalue('B4', $Level)
            ->setCellvalue('B5', 'Course Title Here (Course Code)')
            ->setCellvalue('A6', 'S/N')
            ->setCellvalue('B6', 'NAME')
            ->setCellvalue('C6', 'MATRIC NO')
            ->setCellvalue('D6', 'ATT.')
            ->setCellvalue('E6', 'TEST')
            ->setCellvalue('F6', 'EXAM')
            ->setCellvalue('G6', 'TOTAL')
            ->setCellvalue('H6', 'GRADE')
            ;

      $num1 = $number_of_student+13;
      $num2 = $number_of_student+14;
      $num3 = $number_of_student+12;
      $excel->getActiveSheet()->setCellvalue('C'.$num1, 'Number of Students');
      $excel->getActiveSheet()->setCellvalue('C'.$num2, 'Percentage (%)');
      $excel->getActiveSheet()->setCellvalue('E'.$num3, 'FAILED');
      $excel->getActiveSheet()->setCellvalue('F'.$num3, 'PASSED');
      $excel->getActiveSheet()->getStyle('E'.$num2.':I'.$num2)->getNumberFormat()->setFormatCode('0.00');
      $excel->getActiveSheet()->getStyle('C'.$num1.':F'.$num2)->applyFromArray(
        array(
          'font'=>array(
            'bold'=>true
          )
        )
      );
      $excel->getActiveSheet()->getStyle('E'.$num3.':F'.$num3)->applyFromArray(
        array(
          'font'=>array(
            'bold'=>true
          )
        )
      );
      $excel->getActiveSheet()->mergeCells('C'.$num1.':D'.$num1);
      $excel->getActiveSheet()->mergeCells('C'.$num2.':D'.$num2);
      
      $excel->getActiveSheet()->getStyle('H'.$num3.':I'.$num2)->getAlignment()->setHorizontal('center');
            $excel->getActiveSheet()->mergeCells('B1:H1');
            $excel->getActiveSheet()->mergeCells('B2:H2');
            $excel->getActiveSheet()->mergeCells('B3:H3');
            $excel->getActiveSheet()->mergeCells('B4:H4');
            $excel->getActiveSheet()->mergeCells('B5:H5');
            $excel->getActiveSheet()->getStyle('B1:B5')->applyFromArray(
              array(
                'font'=>array(
                  'bold'=>true
                )
              )
            );
            $excel->getActiveSheet()->getStyle('B1:B5')->getAlignment()->setHorizontal('center');
            $excel->getActiveSheet()->getStyle('A7:A'.$i)->getAlignment()->setHorizontal('center');
            $excel->getActiveSheet()->getStyle('A6:H6')->applyFromArray(
              array(
                'font'=>array(
                  'bold'=>true
                )
              )
            );
            $file = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
            header('Content-Type: application/x-msexcel');
            header('Content-Disposition: attachment; filename= "Template.xls"');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            $file->save('php://output');
          }
          
          }
          else{
            array_push($errors, 'List not found in the database');
          }
          }
        
        }
      }

      ?>
      <?php $site = "get_list.php?logout='1'"; include 'lecturer_header.php';?>
<form enctype="multipart/form-data" class="form-group" action="get_list.php" method="post">
    <?php $header = "Download Template"; include 'body.php';?>
    <input style="margin-top: 10px; margin-bottom: 10px;" type="submit" name="download_list" value="Download List">
</section>
</main>
</form>
    <?php include 'footer.php';?>