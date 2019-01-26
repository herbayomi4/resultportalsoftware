<?php  ini_set('max_execution_time', 30000000000000);
    
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
   $user = "racohabl_racohabl";
$password = "DvKKVAaazzr7";
$host = "server226.web-hosting.com";
$dbase = "racohabl_oui_result_db";
    $conn = mysqli_connect($host, $user, $password, $dbase);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    else{


    if (isset($_POST['Upload'])) 
    {
      $errors   = array(); 
      global $conn, $errors, $msgs;
      if (empty($_FILES['course_result_file']['tmp_name'])) {
    array_push($errors, "Course result file is required, use the choose file button");
  } 
   if(count($errors)==0){
    
    
      $excel = new PHPExcel();
          $excel = PHPExcel_IOFactory::load($_FILES['course_result_file']['tmp_name']);

      $excel->setActiveSheetIndex(0);
      $i= 1;
       while ($excel->getActiveSheet()->getCell('B'.$i)->getValue() !="") 
      {
        $student_name=$excel->getActiveSheet()->getCell('A'.$i)->getValue();
        $student_matric=$excel->getActiveSheet()->getCell('B'.$i)->getValue();
        $department = $excel->getActiveSheet()->getCell('C'.$i)->getValue();
        $session = $excel->getActiveSheet()->getCell('D'.$i)->getValue();
        $Level = $excel->getActiveSheet()->getCell('E'.$i)->getValue();
        $semester = $excel->getActiveSheet()->getCell('F'.$i)->getValue();
        $course_code = $excel->getActiveSheet()->getCell('G'.$i)->getValue(); 
        $table = $session."_".$semester."_".$Level."_".$department."_".$course_code; 
        $total = $excel->getActiveSheet()->getCell('K'.$i)->getValue();
        if ($total>=70) {
          $grade = 'A'; 
        }        
        else if ($total>=60 && $total<70) {
          $grade = 'B';  
        }
        else if ($total>=50 && $total<60) {
          $grade = 'C';  
        }
        else if ($total>=45 && $total<50) {
          $grade = 'D';  
        }
        else if ($total<45) {
          $grade = 'F';  
        }

        $sql_create = "CREATE TABLE IF NOT EXISTS `$table` (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
student_name VARCHAR(30) NOT NULL,
student_matric_number VARCHAR(30) NOT NULL,
total INT(3) NOT NULL,
grade VARCHAR(1) NOT NULL
)ENGINE = INNODB";
    

if (mysqli_query($conn, $sql_create) || !mysqli_query($conn, $sql_create)) {
        $sql = "INSERT INTO `$table`(student_name, student_matric_number, total, grade) 
        VALUES ('$student_name','$student_matric', '$total', '$grade')";
        $insert = mysqli_query($conn, $sql);
      }
    
      $i++;
  }
		if ($insert) 
      {
          array_push($msgs, 'You have successfully uploaded records for the huge unordered results.');
      } 
      else {
          array_push($errors, 'Uploading records for the huge unordered results NOT successfull');
      } 
mysqli_close($conn);
   }
          
  }

}
    ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OUI STAFF PORTAL</title>
    
        <link rel="stylesheet" href="css/bootstrap.min.css" >
        <link rel="stylesheet" type="text/css" href="css/new.css">
  <!--<link rel="stylesheet" href="style.css">-->
</head>
<body>
    <nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="row">
      
        <center>
          <h1>RACONAS RESULT PORTAL</h1>
          <h3>Oduduwa University Ipetumodu</h3>
          <h4>Welcome</h4>
        </center>
    </div>

    <div class="navbar-header">
    </div>  
  </div><!-- /.container-fluid -->
    <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="get_list.php">Uploading Unodered Result</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="lecturer_upload_result.php">Upload Result</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>


<form enctype="multipart/form-data" class="form-group" action="unodered_wahala.php" method="post">
  <div class="alert alert-danger"><?php echo display_error();?></div>
  <P><?php display_msg(); ?>
  <main class="row">
  <section class="col-md-10 col-xs-offset-1 col-xs-10">
    <input style="margin-top: 10px;" type="file" name="course_result_file"  accept=".xls, .xlsx, application/vnd/openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel">
    <input style="margin-top: 10px;" type="submit" name="Upload" value="Upload Result">
</section>
</main>
</form>

<footer class="site-footer navbar navbar-inverse navbar-fixed-bottom">
  <h5><center>Developed by <font style="font-style: italic;">herbeysoftweb solutions</font><br><br>Contact: adeyemioluwaseun47@gmail.com<br>+234 9034582835</center></h5>
</footer>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>