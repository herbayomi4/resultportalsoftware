<?php ini_set('max_execution_time', 300); include('do_function.php');
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
if (isset($_POST['update'])) {
    global $msgs, $errors;
	$department = $_POST['department'];
    $Level = $_POST['level'];
    $session = mysqli_real_escape_string($conn, trim($_POST['session']));
    $semester = $_POST['semester'];
    $college = $_POST['college'];
    $table = $session."_".$semester."_".$department."_".$Level."_Result";
    $tbl_cumulative = $department."_cumulative";
    $matric_number = mysqli_real_escape_string($conn, trim($_POST['matric_number']));
    $old_tp = mysqli_real_escape_string($conn, trim($_POST['old_tp']));
    $old_tu = mysqli_real_escape_string($conn, trim($_POST['old_tu']));
    $new_tp = mysqli_real_escape_string($conn, trim($_POST['new_tp']));
    $new_tu = mysqli_real_escape_string($conn, trim($_POST['new_tu']));
    if (empty($session) || empty($old_tp) || empty($old_tu) || empty($new_tu) || empty($new_tp)) {
        array_push($errors, 'ERROR!!!... All fields are required');
    }
    if (count($errors)==0) {
         $gp = $new_tp/$new_tu;
    $gp = number_format((float)$gp, 2, '.', '');
    $sql = "SELECT * FROM `$table` WHERE student_matric_number = '$matric_number'"; $result = mysqli_query($conn, $sql);
    if ($result) {
        $sql1 = "UPDATE `$table` SET TP = '$new_tp', TU = '$new_tu', GP = '$gp' WHERE student_matric_number = '$matric_number'";
    mysqli_query($conn, $sql1);
    $sql2 = "SELECT * FROM `$tbl_cumulative` WHERE student_matric_number = '$matric_number'";
    $res2 = mysqli_query($conn, $sql2);
    if (mysqli_num_rows($res2)>0) {
        while ($result = mysqli_fetch_assoc($res2)) {
            $old_ctp = $result['ctp'];
            $old_ctu = $result['ctu'];
        }
        $new_ctp = $old_ctp - $old_tp + $new_tp;
        $new_ctu = $old_ctu - $old_tu + $new_tu;
        $cgp = $new_ctp/$new_ctu;
        $cgp = number_format((float)$cgp, 2, '.', '');

        $sql3 = "UPDATE `$tbl_cumulative` SET ctp = '$new_ctp', ctu = '$new_ctu', cgp = '$cgp' WHERE student_matric_number = '$matric_number'";
        $res = mysqli_query($conn, $sql3);
        if ($res) {
            array_push($msgs, 'Result for '.$matric_number.' has been successfully updated');
        }
        else{
            array_push($msgs, 'Update NOT SUCCESSFULL');
        }
    }
    }
    else{
        array_push($msgs, $matric_number.' result for '.$session.' '.$semester.' semester NOT FOUND for update');
    }
    

    }

   
    }
?>
<?php $site = "do_portal_correct.php?logout='1'"; include 'do_header.php';?>
<form class="form-group" action="do_portal_correct.php" method="post">
 <h5> <?php echo display_msg();?>  </h5>
 <h5> <?php echo display_error();?>  </h5>
    <?php $header = "Correct Error in Level Results"; include 'body.php';?>
    <div class="input-group">
    	<span class="input-group-addon" id="basic-addon1">Matric Number:</span>
    	<input type="text" class="form-control" name="matric_number" aria-describedby="basic-addon1">
    </div>
    <div class="input-group">
    	<span class="input-group-addon" id="basic-addon1">Wrong Total Point:</span>
    	<input type="text" class="form-control" name="old_tp" aria-describedby="basic-addon1">
    </div>
    <div class="input-group">
    	<span class="input-group-addon" id="basic-addon1">Wrong Total Unit:</span>
    	<input type="text" class="form-control" name="old_tu" aria-describedby="basic-addon1">
    </div>
    <div class="input-group">
    	<span class="input-group-addon" id="basic-addon1">Correct Total Point:</span>
    	<input type="text" class="form-control" name="new_tp" aria-describedby="basic-addon1">
    </div>
    <div class="input-group">
    	<span class="input-group-addon" id="basic-addon1">Correct Total Unit:</span>
    	<input type="text" class="form-control" name="new_tu" aria-describedby="basic-addon1">
    </div>
    <input style="margin-top: 10px;" type="submit" name="update" value="Update">
  </section>
</main>
</form>
<?php include 'footer.php';?>
