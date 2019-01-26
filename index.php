<?php include('functions.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>RACONAS RESULT PORTAL</title>
		
        <link rel="stylesheet" href="css/bootstrap.min.css" >
        <link rel="stylesheet" type="text/css" href="css/new.css">
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
  </div>
    
</nav>
<body>
	<main class="row">
  <section class="col-md-10 col-xs-offset-1 col-xs-10">
    <h3><center>Staff Login</center></h3>
	<form method="post" action="index.php">

		<?php echo display_error(); ?>
		<div class="input-group">
			<span class="input-group-addon" id="basic-addon1">Username</span>
			<input type="text" class="form-control" name="username" id="username" aria-describedby="basic-addon1">
			<script type="text/javascript">document.getElementById('username').value = "<?php echo $_POST['username'];?>";</script>
		</div>
		<div class="input-group">
			<span class="input-group-addon" id="basic-addon1">Password</span>
			<input type="password" class="form-control" name="password" id="password" aria-describedby="basic-addon1">
		</div>
		
		<div class="input-group">
			<input type="submit" name="submit" value="Login" style="margin-top: 10px;">
		</div>
		</section>
  </main>      
	</form>
<footer class="site-footer navbar navbar-inverse">
  <h5><center>Developed by <font style="font-style: italic;">herbeysoftweb solutions</font><br><br>Contact: adeyemioluwaseun47@gmail.com<br>+234 9034582835</center></h5>
</footer>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>