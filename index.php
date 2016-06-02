<!-- Michael Koboldt, William D Minard, Daniel J Hart, Kenny Clark, Matthew Pokoik
		QuickScript
		04-30-15 -->
<!-- Start PHP -->
<?php
	//require exec.php for db connection and php functionality
	include 'exec.php';

	//begin user session
	session_start();

	//check if user is logged in
	if( isset($_SESSION['username']) )
	{
		//if user logged in, redirect to dashboard
		redirect("page.php?page=home");
	} //end if
	else
	{
		//if user ins't logged in, redirect to login page
		redirect("page.php?page=login");
	} //end else
?>