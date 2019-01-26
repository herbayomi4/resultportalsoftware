<?php include('functions.php');
if (!isLoggedIn() || $_SESSION['user'] != "college officer") {
	$_SESSION['msg'] = "You must log in first";
	header('location: index.php');
}?>
<!DOCTYPE html>
<html lang="en">
<?php $site = "co_change_login.php?logout='1'"; include 'co_header.php';?>

	<main class="row">
  <section class="col-md-10 col-xs-offset-1 col-xs-10">
    <h3><center>Change Login Detail</center></h3>
    <form method="post" action="co_change_login.php" class = "form-group">
      <h4 class="alert alert-warning"><?php echo display_error(); echo display_msg(); ?></h4>
    
<!--use form-group on every page-->
<div class="input-group">
  <span class="input-group-addon" id="basic-addon1">OLD PASSWORD</span>
  <input type="password" class="form-control" name="password1" aria-describedby="basic-addon1">
</div>
<div class="input-group">
  <span class="input-group-addon" id="basic-addon1">NEW PASSWORD</span>
  <input type="password" class="form-control" name="password2" aria-describedby="basic-addon1">
</div>
<div class="input-group">
  <span class="input-group-addon" id="basic-addon1">CONFIRM PASSWORD</span>
  <input type="password" class="form-control" name="password3" aria-describedby="basic-addon1">
</div>
<input type="submit" class="btn" style="margin-top: 10px;" name="change_login" value="Chnage"/>

</form>
</section>
</main>
<footer class="site-footer navbar navbar-inverse">
  <h5><center>Developed by <font style="font-style: italic;">herbeysoftweb solutions</font><br><br>Contact: adeyemioluwaseun47@gmail.com<br>+234 9034582835</center></h5>
</footer>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>