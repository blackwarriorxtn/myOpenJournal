<?php 
	include("includes/functions.php");
	require('includes/cookiecheck.inc'); 
	require('includes/dbconnect.inc');
	// Check, if user is already login, then jump to secured page
Session_start();
	if (isset($_COOKIE['username']))
		$loggedin=true;
	else
		$loggedin=false;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    
    <title>My Open Journal | Home</title>
    
    <meta name="description" content="My Open Journal" />
	<meta name="keywords" content="my open journal" />
    
    <meta name="author" content="CMPSC 483 W" />
    
    <!-- CSS Stylesheets -->
    <link rel="stylesheet" type="text/css" href="css/universal.css" />
    <link rel="stylesheet" type="text/css" href="css/index.css" />
    
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
	
	<div id="description">
	<div align='vertical this doesnt work right yo hgo hoho'>
	<p> <font size='3'><b>myOpenJournal</b> is driven to make publishing academic works free and easy for anyone in order to
open the door for people to spread their academic knowledge. <b>myOpenJournal </b> was imagined as a
place where information is freely available and easily accessible. All academic publications can be peer
reviewed and critiqued through our online system. This will encourage freedom of information on the
web.</font></p>
	</div>
	</div>
	
	<div id="login">
		<?php

		if($loggedin)
		{
			$username=$_COOKIE['username'];
			$dbcnx= new MySQL;
			$dbcnx->MySQL();

			$query = "SELECT first_name, last_name
					  FROM users
					  WHERE username = '".$username."'";
					  
			$result = mysql_query($query);
			if (!$result)   
			{
			echo("<P>Error performing query: " .
				mysql_error() . "</P>");
			exit();
			}
			$info=mysql_fetch_array($result);
			list($first_name, $last_name)=$info;
			
			echo "Hello $first_name  $last_name, you are currently logged in as user ".$username."<BR>";

			echo "click here to <a href='logout.php'>logout</a>";
			$dbcnx->close();
		}
		else
		{
			showLogIn();

			echo "<br><font size=2>Don't have an account? click <a href='register.php'>here</a> to register</font>";
		}

		
		
		?>
	</div>
	
	<?php include("footer.shtml"); ?>
	
</div>

</body>
