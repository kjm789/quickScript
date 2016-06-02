<!-- Michael Koboldt, William D Minard, Daniel J Hart, Kenny Clark, Matthew Pokoik
		QuickScript
		04-30-15 -->

<!-- Start PHP -->
<?php
include 'exec.php';
if($_SESSION['username'] == NULL)
{
	// If user is not logged in.
	redirect("page.php?page=login");
}
else
{
	// If user is logged in.
	echo "\n\t\t<div align=\"center\">";
}

echo "<h1>Projects<br></h1>";
echo "\n<form method=\"GET\" action=\"page.php\">";
echo "\n<input type=\"hidden\" name=\"table\" value=\"project\">";
echo "\n<input type=\"hidden\" name=\"page\" value=\"add\">";
echo "\n<input type=\"submit\" name=\"add\" value=\"+\">";
echo "\n</form>";

echo "<BR>";

find_projects($_SESSION['username']);

// End PHP
?>
