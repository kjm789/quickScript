<!-- Michael Koboldt, William D Minard, Daniel J Hart, Kenny Clark, Matthew Pokoik
		QuickScript
		04-30-15 -->
<div align="center">               
<div id="login">
<h1>Login</h1>
<BR>
<form action="page.php?page=login" method='post'>
	<label for="username"></label>
	<input type="text" name="username" placeholder="username" maxlength="30" size="30" id="username">
<br>
<BR>
	<label for="password"></label>
	<input type="password" name="password" placeholder="password" maxlength="40" size="30" id="password">
<br>
	<input type="hidden" name="page" value="index">
	<BR>
	<input type="submit" name="submit" value="Submit">
</form>
<!-- Start PHP -->
<?php

include('exec.php');

session_start();

// Check if user is logged in.
if( isset($_SESSION['username']) )
{
	// If user is logged in.
	redirect("page.php?page=home");
}

if( isset($_POST['submit']) )
{
	// Grab username and password.
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);

	// Get ip.
	$ip = $_SERVER["REMOTE_ADDR"];
	
	if( check_login($username, $password, $conn) == 1 )
	{
		// If username is correct.
		$_SESSION['username'] = $username;

		// Log the login
		log_data($username, $ip, "Login");
		
		// Redirect.
		redirect("page.php?page=home");
	}
	else
	{
		// If username is not correct.
		
		echo "<div align=\"center\">";
	  	echo "<h1>Incorrect Login Username or Password.</h1>";
	}
		
}

?>
<BR>
<p>Register <a href="page.php?page=registration">here</a></p>

