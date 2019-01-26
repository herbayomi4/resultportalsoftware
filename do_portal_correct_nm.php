<?php ini_set('max_execution_time', 5000); include('do_function.php');
if (!isLoggedIn() || $_SESSION['user'] != "Departmental Officer") {
    $_SESSION['msg'] = "You must log in first";
  header('location: index.php');
} ?>
<?php

$msgs =array();
function display_msg() {
  global $msgs;

  if (count($msgs) > 0){
    echo '<div class="alert alert-warning">';
      foreach ($msgs as $msg){
        echo $msg .'<br>';
      }
    echo '</div>';
  }
} 
if (isset($_POST['update']))
{
    global $msgs, $errors;
    $department = $_POST['department'];
    $Level = $_POST['level'];
    $session = mysqli_real_escape_string($conn, trim($_POST['session']));
    $semester = $_POST['semester'];
    $college = $_POST['college'];
    $tbl_senate_list = $session."_".$semester."_".$Level."_".$department;
    $tbl_course_list = $department."_".$Level."_".$semester."_courses";
    $tbl_do_result = $session."_".$semester."_".$department."_".$Level."_Result";
    $tbl_co_result = $session."_".$semester."_".$department."_".$Level."_final_result";
    $tbl_cumulative = $department."_cumulative";
    $old_matric_number = mysqli_real_escape_string($conn, trim($_POST['old_matric_number']));
    $new_matric_number = mysqli_real_escape_string($conn, trim($_POST['new_matric_number']));
    $old_name = mysqli_real_escape_string($conn, trim($_POST['old_name']));
    $new_name = mysqli_real_escape_string($conn, trim($_POST['new_name']));

    if (empty($session) || empty($old_matric_number) || empty($new_matric_number)|| empty($old_name)|| empty($new_name)) {
        array_push($errors, 'ERROR!!!... All fields are required');
    }
    if(count($errors)==0)
    {
        $sql = "SELECT * FROM `$tbl_senate_list` WHERE student_matric_number = '$old_matric_number'"; $result = mysqli_query($conn, $sql);
        if ($result)
        {
            mysqli_query($conn, "UPDATE `$tbl_senate_list` SET student_matric_number = '$new_matric_number', student_name = '$new_name' WHERE student_matric_number = '$old_matric_number'");
            mysqli_query($conn, "UPDATE `$tbl_do_result` SET student_matric_number = '$new_matric_number', student_name = '$new_name' WHERE student_matric_number = '$old_matric_number'");
            mysqli_query($conn, "UPDATE `$tbl_co_result` SET student_matric_number = '$new_matric_number', student_name = '$new_name' WHERE student_matric_number = '$old_matric_number'");
            //while ($courses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `$tbl_course_list`"))) {
                //$course_code = $courses['course_code'];
                $tbl_course_result = $session."_".$session_type."_".$semester."_".$Level."_".$department."_MCB 203";
                mysqli_query($conn, "UPDATE `$tbl_course_result` SET student_matric_number='$new_matric_number', student_name ='$new_name' WHERE student_matric_number ='$old_matric_number'");
            //}
            $sql1 = "SELECT * FROM `$tbl_cumulative` WHERE student_matric_number = '$new_matric_number'"; $result1 = mysqli_num_rows(mysqli_query($conn, $sql1));
            if ($result1>0) {
                while ($cumulative = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `$tbl_cumulative` WHERE student_matric_number = '$old_matric_number'"))) {
                    $ctu = $cumulative['ctu'];
                    $ctp = $cumulative['ctp'];
                }
                while ($cumulative = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `$tbl_cumulative` WHERE student_matric_number = '$new_matric_number'"))) {
                    $ctu1 = $cumulative['ctu'];
                    $ctp1 = $cumulative['ctp'];
                }
                $ctu2 = $ctu + $ctu1;
                $ctp2 = $ctp + $ctp1;
                $cgp = $ctp2/$ctu2;
                $cgp = number_format((float)$cgp, 2, '.', '');
                mysqli_query($conn, "UPDATE `$tbl_cumulative` SET ctp = '$ctp2', ctu = '$ctu2', cgp = '$cgp' WHERE student_matric_number = '$old_matric_number'");
                
            }
            else
            {
                mysqli_query($conn, "UPDATE `$tbl_cumulative` SET student_matric_number = '$new_matric_number' WHERE student_matric_number = '$old_matric_number'");
            }

            array_push($msgs, 'Name and Matric number correction was successfull');
        }
        else
        {
            array_push($errors, $old_matric_number.' NOT found in the template for '.$Level.' '.$department.' uploaded by the College Officer');
        }
    }
}
?>
<?php $site = "do_portal_correct_nm.php?logout='1'"; include 'do_header.php';?>
<form class="form-group" action="do_portal_correct_nm.php" method="post">
 <h5> <?php echo display_msg();?>  </h5>
 <h5> <?php echo display_error();?>  </h5>
    <?php $header = "Correct Error in Name and Matric Number"; include 'body.php';?>
    <div class="input-group">
    	<span class="input-group-addon" id="basic-addon1">Wrong Matric Number:</span>
    	<input type="text" class="form-control" name="old_matric_number" aria-describedby="basic-addon1">
    </div>
    <div class="input-group">
    	<span class="input-group-addon" id="basic-addon1">Correct Matric Number:</span>
    	<input type="text" class="form-control" name="new_matric_number" aria-describedby="basic-addon1">
    </div>
    <div class="input-group">
    	<span class="input-group-addon" id="basic-addon1">Wrong Name:</span>
    	<input type="text" class="form-control" name="old_name" aria-describedby="basic-addon1">
    </div>
    <div class="input-group">
    	<span class="input-group-addon" id="basic-addon1">Correct Name:</span>
    	<input type="text" class="form-control" name="new_name" aria-describedby="basic-addon1">
    </div>
    <input style="margin-top: 10px;" type="submit" name="update" value="Update">
  </section>
</main>
</form>
<?php include 'footer.php';?>