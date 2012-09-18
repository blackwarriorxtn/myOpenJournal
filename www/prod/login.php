<?php require('includes/cookiecheck.inc'); 
		include('includes/dbconnect.inc');
session_start();
  // Inialize session
?>
<html>

<head>    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    
    <title>My Open Journal</title>
    
    <meta name="description" content="My Open Journal" />
	<meta name="keywords" content="my open journal" />
    
    <meta name="author" content="CMPSC 483 W" />
    
    <!-- CSS Stylesheets -->
    <link rel="stylesheet" type="text/css" href="css/universal.css" />
    <link rel="stylesheet" type="text/css" href="css/page.css" />
    
    <!-- Menu style and javascript -->
    <!--<link type="text/css" href="../../css/menu.css" rel="stylesheet" /> 
    
    <script type="text/javascript" src="../../js/jquery.js"></script>
    <script type="text/javascript" src="../../js/menu.js"></script>-->
</head>

<body>

<div id="container">
	
	<div id="header"><img src="images/blackbanner.png" border="0"/></div>
	
	<div id="nav">
		<?php 
			include("navigation.php");
		?>
	</div>

	<div id="body_content">
	<div id="content">
		<h2 align="center">User Login</h2>
		<p><br></p>
		<?php
		
			include('loginproc.php');
		?>
		
		
		<p><i><br>Don't have an account? click <a href="register.php">here</a> to register.</i></p>
	</div>
	<?php include("footer.shtml"); ?>
	</div>
	
	

</div>
	
</body>

</html>
