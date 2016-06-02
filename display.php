<!-- Michael Koboldt, William D Minard, Daniel J Hart, Kenny Clark, Matthew Pokoik
	QuickScript
	4-30-15
-->
<?php
echo "<div align=\"center\">";
	session_start();
	require_once "exec.php";

	global $conn;

	if($_SESSION['username'] == NULL)
	{
		// If user is not logged in.
		redirect("page.php?page=login");
	}

	$query = "SELECT project_name FROM quickScript.project WHERE owner='".$_SESSION['username'] ."';";

	$result = pg_query($query) or die($query ."<BR> QUERY FAILED: ".pg_last_error());

	if(pg_num_rows($result) > 0)
	{
		echo "Please Select a Project:<BR><BR>";
		echo "\n<form method=\"POST\" action=\"page.php?page=display\">";
		echo "<select name=\"project\">";
		while($line = pg_fetch_array($result, null, PGSQL_ASSOC))
		{
			foreach($line as $display)
			{
				echo "<option	value='". $display ."'>$display</option>";
			}
		}
		echo "</select>";
		echo "<br>";
		echo "<br>";
		echo "<input type=\"submit\" name=\"display\" value=\"Display\">";		
		echo "</form>";

	}
	else
	{
		echo "Return to home <a href=\"home.php\">here</a>";
	}
	
	if(isset($_POST['display']))
	{
		$projName = pg_escape_string(htmlspecialchars($_POST['project']));

		if(findImages($_SESSION['username'], $projName))
		{
			// snip
		}
		else
		{
			echo "\n<BR>No images to display.";
		}
	}

?>
</body>
</html>
