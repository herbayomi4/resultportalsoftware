<?php include('functions.php');
if (!isLoggedIn()) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login_page.php');
} ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>OUI STAFF PORTAL</title>
		
        <link rel="stylesheet" href="css/bootstrap.min.css" >
        <link rel="stylesheet" type="text/css" href="new.css">
	<!--<link rel="stylesheet" href="style.css">-->
</head>
<body>
    <nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="row">
      
        <center>
          <h2>OUI STAFF RESULT PORTAL</h2>
          <h4><?php echo "Welcome ".$name;?></h4>
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
      <a class="navbar-brand" href="co_portal.php">College Officer</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="co_portal.php">Upload Senate List</a></li>
        <li><a href="co_get_result">Get Result</a></li>
        <li><a href="co_upload_result.php">Upload Result</a></li>
        <li><a href="register.php">Staff Register</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="<?php echo $site; ?>">Log Out</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

