<!-- Michael Koboldt, William D Minard, Daniel J Hart, Kenny Clark, Matthew Pokoik
		QuickScript
		04-30-15 -->
<!-- Start PHP -->
<?php include 'exec.php';
global $conn;

// page
$page = $_GET['page'];
$table = $_GET['table'];
check_for_table_error($table);
$take = $_GET['take'];
$item = $_GET['item'];
$id = $_GET['id'];

if($_GET['table'] == project)
{
	echo "Are you sure you want to delete " . $item . "?";
	echo "<BR>";
	echo "\n<form method=\"GET\" action=\"page.php\">";
	echo "\n<input type=\"hidden\" name=\"page\" value=\"delete\">";
	echo "\n<input type=\"hidden\" name=\"table\" value=\"project\">";
	echo "\n<input type=\"hidden\" name=\"item\" value=\"". $item ."\">";
	echo "\n<input type=\"hidden\" name=\"id\" value=\"". $id ."\">";
	echo "\n<input type=\"submit\" name=\"submit\" value=\"Yes\">";
	echo "\n<input type=\"button\" value=\"Back\" <a href=\"#\" onclick=\"window.location.href='page.php?page=home'\"></a>";
	echo "</form>";

	if(isset($_GET['submit']))
	{
		$query = "DELETE FROM quickScript.project WHERE project_id = ". $id;

		// send query
		pg_prepare($conn, "delete", $query);
		if (pg_execute($conn, "delete", array()))
		{
				redirect('page.php?page=home');
		}
		else
		{
				redirect('error.php?error_id=4&query='.$query);
		}
	}
}
else if($_GET['table'] == episode)
{
	echo "Are you sure you want to delete " . $item . "?";
	echo "<BR>";
	echo "\n<form method=\"GET\" action=\"page.php\">";
	echo "\n<input type=\"hidden\" name=\"page\" value=\"delete\">";
	echo "\n<input type=\"hidden\" name=\"table\" value=\"episode\">";
	echo "\n<input type=\"hidden\" name=\"item\" value=\"". $item ."\">";
	echo "\n<input type=\"hidden\" name=\"id\" value=\"". $id ."\">";
	echo "\n<input type=\"submit\" name=\"submit\" value=\"Yes\">";
	echo "\n<input type=\"button\" value=\"Back\" <a href=\"#\" onclick=\"history.back();\"></a>";
	echo "</form>";

	if(isset($_GET['submit']))
	{
		$project_id = "SELECT project_id FROM quickScript.episode WHERE episode_id = $1";
		pg_prepare($conn, "find", $project_id);
		$result = pg_execute($conn, "find", array($id));
		$result = pg_fetch_array($result, null, PGSQL_ASSOC);
		$project_id = $result['project_id'];
		$query = "DELETE FROM quickScript.episode WHERE episode_id = $1";

		// send query
		pg_prepare($conn, "delete", $query);
		if (pg_execute($conn, "delete", array($id)))
		{
			redirect('page.php?page=view&table=episode&id=' . $project_id);
		}
		else
		{
			redirect('error.php?error_id=4&query='.$query);
		}
	}
}
else if($_GET['table'] == shooting_day)
{
	echo "Are you sure you want to delete " . $item . "?";
	echo "<BR>";
	echo "\n<form method=\"GET\" action=\"page.php\">";
	echo "\n<input type=\"hidden\" name=\"page\" value=\"delete\">";
	echo "\n<input type=\"hidden\" name=\"table\" value=\"shooting_day\">";
	echo "\n<input type=\"hidden\" name=\"item\" value=\"". $item ."\">";
	echo "\n<input type=\"hidden\" name=\"id\" value=\"". $id ."\">";
	echo "\n<input type=\"submit\" name=\"submit\" value=\"Yes\">";
	echo "\n<input type=\"button\" value=\"Back\" <a href=\"#\" onclick=\"history.back();\"></a>";
	echo "</form>";

	if(isset($_GET['submit']))
	{
		$episode_id = "SELECT episode_id FROM quickScript.shooting_day WHERE sd_id = $1";
		pg_prepare($conn, "find", $episode_id);
		$result = pg_execute($conn, "find", array($id));
		$result = pg_fetch_array($result, null, PGSQL_ASSOC);
		$episode_id = $result['episode_id'];
		//echo "<p>episode_id: $episode_id</p>\n";
		$query = "DELETE FROM quickScript.shooting_day WHERE sd_id = $1";

		// send query
		pg_prepare($conn, "delete", $query);
		if (pg_execute($conn, "delete", array($id))) 
		{
			redirect('page.php?page=view&table=shooting_day&id='.$episode_id);
		}
		else
		{
			redirect('error.php?error_id=4&query='.$query);
		}
	}
}
else if($_GET['table'] == scene)
{
	echo "Are you sure you want to delete scene " . $item . "?";
	echo "<BR>";
	echo "\n<form method=\"GET\" action=\"page.php\">";
	echo "\n<input type=\"hidden\" name=\"page\" value=\"delete\">";
	echo "\n<input type=\"hidden\" name=\"table\" value=\"scene\">";
	echo "\n<input type=\"hidden\" name=\"item\" value=\"". $item ."\">";
	echo "\n<input type=\"hidden\" name=\"id\" value=\"". $id ."\">";
	echo "\n<input type=\"submit\" name=\"submit\" value=\"Yes\">";
	echo "\n<input type=\"button\" value=\"Back\" <a href=\"#\" onclick=\"history.back();\"></a>";
	echo "</form>";

	if(isset($_GET['submit']))
	{
		$sd_id = "SELECT sd_id FROM quickScript.scene WHERE scene_id = $1";
		pg_prepare($conn, "find", $sd_id);
		$result = pg_execute($conn, "find", array($id));
		$result = pg_fetch_array($result, null, PGSQL_ASSOC);
		$sd_id = $result['sd_id'];
		$query = "DELETE FROM quickScript.scene WHERE scene_id =$1";

		// send query
		pg_prepare($conn, "delete", $query);
		if (pg_execute($conn, "delete", array($id))) 
		{
			redirect('page.php?page=view&table=scene&id='.$sd_id);
		}
		else
		{
			redirect('error.php?error_id=4&query='.$query);
		}
	}
}
else if($_GET['table'] == take)
{
	$take = $_GET['take'];
	echo "Are you sure you want to delete take " . $take . "?";
	echo "<BR>";
	echo "\n<form method=\"GET\" action=\"page.php\">";
	echo "\n<input type=\"hidden\" name=\"page\" value=\"delete\">";
	echo "\n<input type=\"hidden\" name=\"table\" value=\"take\">";
	echo "\n<input type=\"hidden\" name=\"item\" value=\"". $item ."\">";
	echo "\n<input type=\"hidden\" name=\"take\" value=\"". $take ."\">";
	echo "\n<input type=\"hidden\" name=\"id\" value=\"". $id ."\">";
	echo "\n<input type=\"submit\" name=\"submit\" value=\"Yes\">";
	echo "\n<input type=\"button\" value=\"Back\" <a href=\"#\" onclick=\"history.back();\"></a>";
	echo "</form>";

	if(isset($_GET['submit']))
	{
		$query = "DELETE FROM quickScript.take WHERE scene_id =" . $id . "
		AND take_number = " .$take. ";";

		// send query
		pg_prepare($conn, "delete", $query);
		if (pg_execute($conn, "delete", array())) 
		{
			redirect('page.php?page=view&table=take&id=' . $id);
		}
		else
		{
			redirect('error.php?error_id=4&query='.$query);
		}
	}
}
else {
	redirect('error.php?error_id=2');
}