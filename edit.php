<!-- Michael Koboldt, William D Minard, Daniel J Hart, Kenny Clark, Matthew Pokoik
		QuickScript
		04-30-15 -->

<!-- Start PHP -->
<?php include 'exec.php';
global $conn;

// page
$page = $_GET['page'];

echo "<div align=\"center\">";

if($_GET['table'] == project)
{
		$name = pg_escape_string(htmlspecialchars($_GET['1']));
		$created_by = pg_escape_string(htmlspecialchars($_GET['2']));
		$producer = pg_escape_string(htmlspecialchars($_GET['3']));
		$start_date = pg_escape_string(htmlspecialchars($_GET['4']));
		$id = pg_escape_string(htmlspecialchars($_GET['5']));
	
		// START FORM 
		echo "\n<form method=\"GET\" action=\"page.php\">";
		echo "\n<input type=\"hidden\" name=\"table\" value=\"project\">";
		echo "\n<input type=\"hidden\" name=\"page\" value=\"edit\">";
		echo "\n<input type=\"hidden\" name=\"5\" value=\"".$id."\">";
		// Start table
		echo "\n<TABLE border=\"1\">";

		// d
		echo "\t \n<TR>";
		echo "\t\t \n<TD>Name*</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"1\" required autocomplete=\"yes\" maxlength=\"50\" placeholder=\"project name\" size=\"50\" value=\"".$name."\"></TD>";
		echo "\t \n</TR>";
		
		// pop
		echo "\t \n<TR>";
		echo "\t\t \n<TD>Created by*:</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"2\" required autocomplete=\"yes\" maxlength=\"50\" placeholder=\"created by\" size=\"50\" value=\"".$created_by."\"></TD>";
		echo "\t \n</TR>";
		
		// pop
		echo "\t \n<TR>";
		echo "\t\t \n<TD>producer</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"3\" name=\"producer\" autocomplete=\"yes\" maxlength=\"30\" placeholder=\"producer\" size=\"30\" value=\"".$producer."\"></TD>";
		echo "\t \n</TR>";
		
		// pop
		echo "\t \n<TR>";
		echo "\t\t \n<TD>Start Date*</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"4\" required autocomplete=\"yes\" maxlength=\"11\" placeholder=\"YYYY-MM-DD\" size=\"11\" value=\"".$start_date."\"></TD>";
		echo "\t \n</TR>";

		// End Table
		echo "\n</TABLE>";

		echo "\n<BR>";

		echo "\n<input type=\"submit\" name=\"submit\" value=\"Save\">";
		echo "\n<input type=\"button\" value=\"Cancel\" <a href=\"#\" onclick=\"window.location.href='page.php?page=home'\"></a>";
		echo "\n</form>";

		if(isset($_GET['submit']))
		{
			$name = pg_escape_string(htmlspecialchars($name));
			$created_by = pg_escape_string(htmlspecialchars($created_by));
			$producer = pg_escape_string(htmlspecialchars($producer));
			$start_date = pg_escape_string(htmlspecialchars($start_date));
			
			$query = "UPDATE quickScript.project
			SET project_name = '".$name."', created_by ='".$created_by."', producer = '".$producer."', start_date = '".$start_date."' WHERE project_id = ". $id;

		// send query
		pg_prepare($conn, "insert", $query);
		if (pg_execute($conn, "insert", array())) 
		{
			redirect('page.php?page=home');
		}
		else
		{
			redirect('error.php?error_id=1&query='.$query);
		} 
	}
}
else if($_GET['table'] == episode)
{
		$name = pg_escape_string(htmlspecialchars($_GET['1']));
		$writer = pg_escape_string(htmlspecialchars($_GET['3']));
		$editor = pg_escape_string(htmlspecialchars($_GET['4']));
		$director = pg_escape_string(htmlspecialchars($_GET['2']));
		$id = pg_escape_string(htmlspecialchars($_GET['5']));

		// START FORM 
		echo "\n<form method=\"GET\" action=\"page.php\">";
		echo "\n<input type=\"hidden\" name=\"table\" value=\"episode\">";
		echo "\n<input type=\"hidden\" name=\"page\" value=\"edit\">";
		echo "\n<input type=\"hidden\" name=\"5\" value=\"".$id."\">";

		// Start table
		echo "\n<TABLE border=\"1\">";

		// more fields
		echo "\t \n<TR>";
		echo "\t\t \n<TD>Episode Name</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"1\" pattern=\"[^'\\x22]+\" autocomplete=\"yes\" required maxlength=\"50\" placeholder=\"episode name\" size=\"50\" value=\"".$name."\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Director</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"2\" name=\"director\" pattern=\"[^'\\x22]+\" autocomplete=\"yes\" required maxlength=\"50\" placeholder=\"director\" size=\"50\" value=\"".$director."\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Writer</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"3\" autocomplete=\"yes\" required maxlength=\"100\" placeholder=\"writer\" size=\"100\" value=\"".$writer."\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Editor</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"4\" pattern=\"[^'\\x22]+\" autocomplete=\"yes\" required maxlength=\"100\" placeholder=\"editor\" size=\"100\" value=\"".$editor."\"></TD>";
		echo "\t \n</TR>";

		// End Table
		echo "\n</TABLE>";

		echo "\n<BR>";

		echo "\n<input type=\"submit\" name=\"submit\" value=\"Save\">";
		echo "\n<input type=\"button\" value=\"Cancel\" <a href=\"#\" onclick=\"history.back();\"></a>";
		echo "\n</form>";
		
		if(isset($_GET['submit']))
		{
			$name = pg_escape_string(htmlspecialchars($name));
			$writer = pg_escape_string(htmlspecialchars($writer));
			$director = pg_escape_string(htmlspecialchars($director));
			$editor = pg_escape_string(htmlspecialchars($editor));

			$project_id = "SELECT project_id FROM quickScript.episode WHERE episode_id = ". $id;
			pg_prepare($conn, "find", $project_id);
			$result = pg_execute($conn, "find", array());
			$result = pg_fetch_array($result, null, PGSQL_ASSOC);
			$project_id = $result['project_id'];

		// enter query
		$query = "UPDATE quickScript.episode
		SET episode_name = '".$name."', writer ='".$writer."', director = '".$director."', editor = '".$editor."' WHERE episode_id = ". $id;
		
		// send query
		pg_prepare($conn, "insert", $query);
		if (pg_execute($conn, "insert", array())) 
		{
			redirect('page.php?page=view&table=episode&id=' . $project_id);
		}
		else
		{
			redirect('error.php?error_id=1&query='.$query);
		}
	}
}
else if($_GET['table'] == shooting_day)
{
		$date = pg_escape_string(htmlspecialchars($_GET['1']));
		$cam = pg_escape_string(htmlspecialchars($_GET['2']));
		$dop = pg_escape_string(htmlspecialchars($_GET['3']));
		$scripty = pg_escape_string(htmlspecialchars($_GET['4']));
		$audio = pg_escape_string(htmlspecialchars($_GET['5']));
		$gaffer = pg_escape_string(htmlspecialchars($_GET['6']));
		$ad = pg_escape_string(htmlspecialchars($_GET['7']));
		$key = pg_escape_string(htmlspecialchars($_GET['8']));

		$id = pg_escape_string(htmlspecialchars($_GET['9']));

		// START FORM 
		echo "\n<form method=\"GET\" action=\"page.php\">";
		echo "\n<input type=\"hidden\" name=\"table\" value=\"shooting_day\">";
		echo "\n<input type=\"hidden\" name=\"page\" value=\"edit\">";
		echo "\n<input type=\"hidden\" name=\"9\" value=\"".$id."\">";


		// Start table
		echo "\n<TABLE border=\"1\">";

		// more fields
		echo "\t \n<TR>";
		echo "\t\t \n<TD>Date</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"1\" required autocomplete=\"yes\" maxlength=\"11\" placeholder=\"YYYY-MM-DD\" size=\"11\" value=\"".$date."\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Cam Op</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"2\" autocomplete=\"yes\" maxlength=\"30\" placeholder=\"cam ops\" size=\"30\" value=\"".$cam."\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>DOP</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"3\" autocomplete=\"yes\" maxlength=\"30\" placeholder=\"dop\" size=\"30\" value=\"".$dop."\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Scripty</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"4\" autocomplete=\"yes\" maxlength=\"30\" placeholder=\"scripty\" size=\"30\" value=\"".$scripty."\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Audio</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"5\" autocomplete=\"yes\" maxlength=\"30\" placeholder=\"audio\" size=\"30\" value=\"".$audio."\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Gaffer</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"6\" autocomplete=\"yes\" maxlength=\"30\" placeholder=\"gaffer\" size=\"30\" value=\"".$gaffer."\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>AD</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"7\" autocomplete=\"yes\" maxlength=\"30\" placeholder=\"ad\" size=\"30\" value=\"".$ad."\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Key Grip</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"8\" autocomplete=\"yes\" maxlength=\"30\" placeholder=\"key grip\" size=\"30\" value=\"".$key."\"></TD>";
		echo "\t \n</TR>";

		// End Table
		echo "\n</TABLE>";

		echo "\n<BR>";

		echo "\n<input type=\"submit\" name=\"submit\" value=\"Save\">";
		echo "\n<input type=\"button\" value=\"Cancel\" <a href=\"#\" onclick=\"history.back();\"></a>";
		echo "\n</form>";
		
		if(isset($_GET['submit']))
		{
			$date = pg_escape_string(htmlspecialchars($date));
			$cam = pg_escape_string(htmlspecialchars($cam));
			$dop = pg_escape_string(htmlspecialchars($dop));
			$scripty = pg_escape_string(htmlspecialchars($scripty));
			$audio = pg_escape_string(htmlspecialchars($audio));
			$gaffer = pg_escape_string(htmlspecialchars($gaffer));
			$ad = pg_escape_string(htmlspecialchars($ad));
			$key_grip = pg_escape_string(htmlspecialchars($key_grip));

			$episode_id = "SELECT episode_id FROM quickScript.shooting_day WHERE sd_id =". $id;
		pg_prepare($conn, "find", $episode_id);
		$result = pg_execute($conn, "find", array());
		$result = pg_fetch_array($result, null, PGSQL_ASSOC);
		$episode_id = $result['episode_id'];

		// enter query
		$query = "UPDATE quickScript.shooting_day
		SET sd_date = '".$date."', cam_ops ='".$cam."', dop = '".$dop."', scripty = '".$scripty."' , audio = '".$audio."', gaffer = '".$gaffer."', ad = '".$ad."', key_grip = '".$key."' WHERE sd_id = ". $id;
		
		// send query
		pg_prepare($conn, "edit", $query);
		if (pg_execute($conn, "edit", array())) 
		{
			redirect('page.php?page=view&table=shooting_day&id='.$episode_id);
		}
		else
		{
			redirect('error.php?error_id=1&query='.$query);
		}
	}
}
else if($_GET['table'] == scene)
{
		$num = pg_escape_string(htmlspecialchars($_GET['1']));
		$desc = pg_escape_string(htmlspecialchars($_GET['2']));
		$loc = pg_escape_string(htmlspecialchars($_GET['3']));
		$id = pg_escape_string(htmlspecialchars($_GET['4']));

		// START FORM 
		echo "\n<form method=\"GET\" action=\"page.php\">";
		echo "\n<input type=\"hidden\" name=\"page\" value=\"edit\">";
		echo "\n<input type=\"hidden\" name=\"4\" value=\"".$id."\">";
		echo "\n<input type=\"hidden\" name=\"table\" value=\"scene\">";

		// Start table
		echo "\n<TABLE border=\"1\">";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Scene Number</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"1\" required autocomplete=\"yes\" maxlength=\"10\" placeholder=\"scene number\" size=\"10\" value=\"".$num."\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Description</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"2\" autocomplete=\"yes\" maxlength=\"100\" placeholder=\"description\" size=\"100\" value=\"".$desc."\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Location</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"3\" required autocomplete=\"yes\" maxlength=\"30\" placeholder=\"location\" size=\"30\" value=\"".$loc."\"></TD>";
		echo "\t \n</TR>";

		// End Table
		echo "\n</TABLE>";

		echo "\n<BR>";

		echo "\n<input type=\"submit\" name=\"submit\" value=\"Save\">";
		echo "\n<input type=\"button\" value=\"Cancel\" <a href=\"#\" onclick=\"history.back();\"></a>";
		echo "\n</form>";
		
		if(isset($_GET['submit']))
		{
			$num = pg_escape_string(htmlspecialchars($num));
			$desc = pg_escape_string(htmlspecialchars($desc));
			$loc = pg_escape_string(htmlspecialchars($loc));

			$sd_id = "SELECT sd_id FROM quickScript.scene WHERE scene_id = ".$id;
			pg_prepare($conn, "find", $sd_id);
			$result = pg_execute($conn, "find", array());
			$result = pg_fetch_array($result, null, PGSQL_ASSOC);
			$sd_id = $result['sd_id'];

		$query = "UPDATE quickScript.scene
		SET scene_number = '".$num."', description ='".$desc."', location = '".$loc."' WHERE scene_id = ". $id;
		
		// send query
		pg_prepare($conn, "insert", $query);
		if (pg_execute($conn, "insert", array())) 
		{
			redirect('page.php?page=view&table=scene&id='.$sd_id);
		}
		else
		{
			redirect('error.php?error_id=1&query='.$query);
		}
	}
}
else if($_GET['table'] == take)
{
		$num = $_GET['1'];
		$cam1 = $_GET['2'];
		$cam2 = $_GET['3'];
		$cam3 = $_GET['4'];
		$audio = $_GET['5'];
		$notes = $_GET['6'];
		$rating = $_GET['7'];
		$dead = $_GET['8'];
		$bloop = $_GET['9'];
		$take_id = $_GET['10'];
		$scene_id = $_GET['11'];

		// START FORM 
		echo "\n<form method=\"GET\" action=\"page.php\">";
		echo "\n<input type=\"hidden\" name=\"table\" value=\"take\">";
		echo "\n<input type=\"hidden\" name=\"page\" value=\"edit\">";
		echo "\n<input type=\"hidden\" name=\"10\" value=\"".$take_id."\">";
		echo "\n<input type=\"hidden\" name=\"11\" value=\"".$scene_id."\">";

		// Start table
		echo "\n<TABLE border=\"1\">";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Take Number</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"1\" required autocomplete=\"yes\" maxlength=\"5\" placeholder=\"take number\" size=\"5\" min=\"1\" value=\"".$num."\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Cam 1</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"2\" required autocomplete=\"yes\" maxlength=\"10\" placeholder=\"cam1 file\" size=\"10\" value=\"".$cam1."\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Cam 2</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"3\" autocomplete=\"yes\" maxlength=\"10\" placeholder=\"cam2 file\" size=\"10\" value=\"".$cam2."\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Cam 3</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"4\" autocomplete=\"yes\" maxlength=\"10\" placeholder=\"cam3 file\" size=\"10\" value=\"".$cam3."\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Audio File</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"5\" autocomplete=\"yes\" maxlength=\"10\" placeholder=\"audio file\" size=\"10\" value=\"".$audio."\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Notes</TD>";
		echo "\t\t \n<TD><input type=\"text\" name=\"6\" autocomplete=\"yes\" maxlength=\"100\" placeholder=\"notes\" size=\"100\" value=\"".$notes."\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Rating</TD>";
		echo "\t\t \n<TD><input type=\"radio\" value=\"1\" name=\"7\">1";
		echo "\t\t\n<input type=\"radio\" value=\"2\" name=\"7\">2";
		echo "\t\t\n<input type=\"radio\" value=\"3\" name=\"7\">3";
		echo "\t\t\n<input type=\"radio\" value=\"4\" name=\"7\">4";
		echo "\t\t\n<input type=\"radio\" value=\"5\" name=\"7\" checked=\"yes\">5";
		echo "\t\t\n<input type=\"radio\" value=\"6\" name=\"7\">6";
		echo "\t\t\n<input type=\"radio\" value=\"7\" name=\"7\">7";
		echo "\t\t\n<input type=\"radio\" value=\"8\" name=\"7\">8";
		echo "\t\t\n<input type=\"radio\" value=\"9\" name=\"7\">9";
		echo "\t\t\n<input type=\"radio\" value=\"10\" name=\"7\">10</td>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Dead Take?</TD>";
		echo "\t\t \n<TD><input type=\"radio\" name=\"8\" value=\"TRUE\">Yes";
		echo "\t\t \n<input type=\"radio\" name=\"8\" value=\"FALSE\" checked=\"yes\">No</TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Blooper?</TD>";
		echo "\t\t \n<TD><input type=\"radio\" name=\"9\" value=\"TRUE\">Yes";
		echo "\t\t \n<input type=\"radio\" name=\"9\" value=\"FALSE\" checked=\"yes\">No</TD>";
		echo "\t \n</TR>";

		// End Table
		echo "\n</TABLE>";

		echo "\n<BR>";

		echo "\n<input type=\"submit\" name=\"submit\" value=\"Save\">";
		echo "\n<input type=\"button\" value=\"Cancel\" <a href=\"#\" onclick=\"history.back();\"></a>";
		echo "\n</form>";
		
		if(isset($_GET['submit']))
		{
		
		//$take_id = $_GET['10'];
		//$scene_id = $_GET['11'];
		$num = pg_escape_string(htmlspecialchars($num));
		$cam1 = pg_escape_string(htmlspecialchars($cam1));
		$cam2 = pg_escape_string(htmlspecialchars($cam2));
		if($cam2 == "")
		{
			$cam2 = "NULL";
		}
		$cam3 = pg_escape_string(htmlspecialchars($cam3));
		if($cam3 == "")
		{
			$cam3 = "NULL";
		}
		$audio = pg_escape_string(htmlspecialchars($audio));
		if($audio == "")
		{
			$audio = "NULL";
		}
		$notes = pg_escape_string(htmlspecialchars($notes));

		// enter query
		$query = "UPDATE quickScript.take
		SET take_number = ".$num.", cam1_file = ".$cam1.", cam2_file = ".$cam2.", cam3_file = ".$cam3.", audio_file = ".$audio.", notes = '".$notes."', rating = ".$rating.", is_dead = ".$dead.", is_bloop = ".$bloop." WHERE take_id = ".$take_id;
		
		// send query
		pg_prepare($conn, "insert", $query);
		if (pg_execute( $conn, "insert", array()))
		{
			redirect("page.php?page=view&table=take&id=".$scene_id);
		}
		else
		{
			//echo "Error\n";
		}

	}
}
else {
	redirect('error.php?error_id=2');
}

?>
