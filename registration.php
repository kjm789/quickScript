<!-- Michael Koboldt, William D Minard, Daniel J Hart, Kenny Clark, Matthew Pokoik
		QuickScript
		04-30-15 -->

<!DOCTYPE html>
<html>
<head>
<title>Registration</title>
</head>
<body>
<div align="center">
	<h1>Register</h1>
	<BR>
<form action="page.php?page=registration" method='post'>
	<label for="username"></label>
	<input type="text" name="username" placeholder="username" id="username">
<br>
<br>
	<label for="password"></label>
	<input type="password" name="password" placeholder="password" id="password">
<br>
<br>
	<input type="submit" name="submit" value="Submit">
</form>
</div>
<!-- Start PHP -->
<?php
include 'exec.php';
if(isset( $_POST['submit']))
{
	// Grab username and password.
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);

	// Check if username is correct.
	if( check_username($username) == 0)
	{
		// Username is correct.
		
		// Get ip.
		$ip = $_SERVER["REMOTE_ADDR"];
		
		// Create the user
		create_user($username, $password);
		
		// Log the Login data
		log_data($username, $ip, "Register ");
		
		// Set session username
		$_SESSION['username'] = $username;
		
		// Redirect.
		redirect("page.php?page=home");
	}
	else
	{
		// Username is not correct.
		
		echo "<div align=\"center\">";
		echo "\n\t\t<h1>Error: Username taken or invalid.</h1>";
	}
}

// End PHP
?>
<div align="center">
<br>Back to <a href="page.php?page=index">login</a> 
</div>
</body>
</html>
