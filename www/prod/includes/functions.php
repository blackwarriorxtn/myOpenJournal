<?php
//contains functions that will commonly be used within different pages
	$username=$_COOKIE['username'];
	$dbcnx = @mysql_connect("localhost", "djd5202", "4834life");
	if (!$dbcnx) 
	{
		echo( "<P>Unable to connect to the " .
		"database server at this time.</P>" );
		 exit();
	}

	mysql_select_db("myopenjournal", $dbcnx);



function showLogIn()
{	//include('loginproc.php');
?>
	<table border="0" style="background-color:#FFFFFF; color:#000000">
	<form method="POST" action="login.php">
	<tr>
		<td style="background-color:#FFFFFF; color:#000000">Username</td>
		<td>:</td>
		<td><input type="text" name="username" size="20"></td>
	</tr>
	<tr>
		<td style="background-color:#FFFFFF; color:#000000">Password</td>
		<td>:</td>
		<td><input type="password" name="password" size="20"></td>
	</tr>
	<tr>
	<td style="background-color:#FFFFFF; color:#000000" colspan="3">
		<input id="stayloggedin" name="stayloggedin"  type="checkbox" value="stayin" />
		<label for="stayloggedin" style="background-color:#FFFFFF; color:#000000">Stay Logged In?</label></td>
	</tr>
	
	<tr>
		<td ></td>
		<td></td>
		<td style="background-color:#FFFFFF; color:#000000"><br><input id="submit" type="submit" value="Login">
		<input type="hidden" name="submit"></td>
	</tr>
	</form>
	</table>
<?php
}  /** end showLogIn **/


function isUsernameAvail($uname) 
{
	$user_query = mysql_query("SELECT * FROM users U WHERE U.username = '" . $uname. "'") or die(mysql_error());
			       
        $user_info = mysql_fetch_assoc($user_query);
     
        if($user_info['username'] == NULL)
	{
		
		return true;
	}

	else
	{
		return false;
	}
 
}

function cldb()
{
  mysql_close($dbcnx);
}

?>
