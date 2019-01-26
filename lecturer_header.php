<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>RACONAS RESULT PORTAL</title>
		
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
      <a class="navbar-brand" href="get_list.php">Course Lecturer</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="get_list.php">Download Template</a></li>
        <li><a href="lecturer_register_course.php">Register Course Details</a></li>
        <li><a href="lecturer_upload_result.php">Upload Result</a></li>
        <li><a href="not_on_list.php">Upload (Not on Template)</a></li>
        <li><a href="student_portal.php">Student Result</a></li>
        <li><a href="lecturer_change_login.php">Change Password<a/></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="<?php echo $site; ?>">Log Out</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

