<!-- Michael Koboldt, William D Minard, Daniel J Hart, Kenny Clark, Matthew Pokoik
		QuickScript
		04-30-15 -->
<!-- Start PHP -->
<?php
include 'exec.php';
// Check if user is logged in.
if( isset($_SESSION['username']) )
{
	// If user is logged in.
	redirect("page.php?page=home");
}
else
{
	redirect("page.php?page=login");
}

?>

