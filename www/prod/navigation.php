<?php
if (Cookie_IsNotSet())
{
	echo <<<HTML
		<a href="index.php">Home</a>
		<a href="login.php">Login</a>
		<a href="register.php">Register</a>
		<a href="search.php">Search</a>
		<a class="lastchild" href="contact.php">Contact us</a>
HTML;
}
else
{
echo <<<HTML
	
		<a href="index.php">Home</a>
		<a href="logout.php">Logout</a>
		<a href="uploadarticle.php">Upload</a>
		<a href="profile.php">Profile</a>
		<a href="search.php">Search</a>
		<a class="lastchild" href="contact.php">Contact us</a>
HTML;
}
?>