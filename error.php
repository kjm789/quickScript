<!-- Michael Koboldt, William D Minard, Daniel J Hart, Kenny Clark, Matthew Pokoik
		QuickScript
		04-30-15 -->
<!DOCTYPE html>
<html>
<head>
<title>QuickScript Error</title>
</head>
<body>
<center>
<?php
	include "exec.php";
	$error_id = $_GET['error_id'];
	if($error_id != NULL)
	{
		echo "<h1>Error #".$error_id."</h1>";
	}

	switch ($error_id) {
		case 1:
			// Insert Error
			echo "<h3>Please check your input values. They may be too long or contain special characters.</h3>";
			echo "<h3>Stay away from these characters: ! @ # $ % ^ & * ( ) ; ' \" ? < > . , { } [ ] \ /</h3>";
			echo "<h3>If the problem persists, email <a href=\"mailto:dhart94@gmail.com\">the webmaster</a></h3>"; 
			break;

		case 2:
			// GET table error
			echo "<h3>Please check your URL. The Table value did not successfully record.</h3>";
			echo "<h3>Change table= to reflect where you want to go.</h3>";
			echo "<h3>If the problem persists, email <a href=\"mailto:dhart94@gmail.com\">the webmaster</a></h3>"; 
			break;

		case 3:
			// GET ID error
			echo "<h3>Please check your URL. The ID value did not successfully record.</h3>";
			echo "<h3>Change ID= to reflect where you want to go.</h3>";
			echo "<h3>If the problem persists, email <a href=\"mailto:dhart94@gmail.com\">the webmaster</a></h3>"; 
			break;

		case 4:
			// Delete Error
			echo "<h3>For some reason, the delete was unsuccessful.</h3>";
			echo "<h3>If the problem persists, email <a href=\"mailto:dhart94@gmail.com\">the webmaster</a></h3>"; 
			break;
		
		default:
			echo "<h1>Error</h1>";
			break;
	}
	
	if( $_GET['query'] != NULL )
	{
		echo "<br><br><br><h4>Developer Information: </h4>";
		echo $_GET['query'];
		echo "<br><br>Details: ";
		echo pg_last_error();
	}
	echo "<h2>Return to <a href=\"page.php?page=home\">home.</a></h2>";
?>