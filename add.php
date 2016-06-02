<!-- Michael Koboldt, William D Minard, Daniel J Hart, Kenny Clark, Matthew Pokoik
		QuickScript
		04-30-15 -->
<!-- Start PHP -->
<?php

session_start();
include "exec.php";
global $conn;

if($_SESSION['username'] == NULL)
{
	// If user is not logged in.
	redirect("page.php?page=login");
}

// page
$page = $_GET['page'];
$id = $_GET['id']; 

$table = $_GET['table'];
check_for_table_error($table);
$project_id = $_GET['project_id'];
$episode_id = $_GET['episode_id'];
$scene_id = $_GET['scene_id'];
$sd_id = $_GET['sd_id'];

// Take shit
$cam1_file = $_GET['2'];
$cam2_file = $_GET['3'];
$cam3_file = $_GET['4'];
$take_number = $_GET['1'];
$audio_file = $_GET['5'];

echo "<div align=\"center\">";

if($_GET['table'] == project)
{
		// START FORM 
		echo "\n<form method=\"GET\" action=\"page.php\">";
		echo "\n<input type=\"hidden\" name=\"table\" value=\"project\">";
		echo "\n<input type=\"hidden\" name=\"page\" value=\"add\">";

		// Start table
		echo "\n<TABLE border=\"1\">";

		// d
		echo "\t \n<TR>";
		echo "\t\t \n<TD>Name*</TD>";
		echo "\t\t \n<TD><input type=\"text\" pattern=\"[^'\\x22]+\" name=\"name\" required autocomplete=\"yes\" maxlength=\"50\" placeholder=\"project name\" size=\"50\"></TD>";
		echo "\t \n</TR>";
		
		// pop
		echo "\t \n<TR>";
		echo "\t\t \n<TD>Created by*</TD>";
		echo "\t\t \n<TD><input type=\"text\" pattern=\"[^'\\x22]+\" name=\"created_by\" required autocomplete=\"yes\" maxlength=\"50\" placeholder=\"created by\" size=\"50\"></TD>";
		echo "\t \n</TR>";
		
		// pop
		echo "\t \n<TR>";
		echo "\t\t \n<TD>Producer</TD>";
		echo "\t\t \n<TD><input type=\"text\" pattern=\"[^'\\x22]+\" name=\"producer\" autocomplete=\"yes\" maxlength=\"30\" placeholder=\"producer\" size=\"30\"></TD>";
		echo "\t \n</TR>";
		
		// pop
		echo "\t \n<TR>";
		echo "\t\t \n<TD>Start Date*</TD>";
		echo "\t\t \n<TD><input type=\"date\" pattern=\"[^'\\x22]+\" name=\"start_date\" required autocomplete=\"yes\" maxlength=\"11\" placeholder=\"YYYY-MM-DD\" size=\"11\"";
		echo " value=".date("Y-m-d")."></TD>";
		echo "\t \n</TR>";

		// End Table
		echo "\n</TABLE>";

		echo "\n<BR>";

		echo "\n<input type=\"submit\" name=\"submit\" value=\"Save\">";
		echo "\n<input type=\"button\" value=\"Cancel\" <a href=\"#\" onclick=\"window.location.href='page.php?page=home'\"></a>";
		echo "\n</form>";

		if(isset($_GET['submit']))
		{
			$name = pg_escape_string(htmlspecialchars($_GET['name']));
			$created_by = pg_escape_string(htmlspecialchars($_GET['created_by']));
			$producer = pg_escape_string(htmlspecialchars($_GET['producer']));
			$start_date = pg_escape_string(htmlspecialchars($_GET['start_date']));
			
			$query = "INSERT INTO quickScript.project 	
				VALUES('".$_SESSION['username']."','".$name."', DEFAULT, '".$created_by."', '".$producer."', '".$start_date."');";

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
		// START FORM 
		echo "\n<form method=\"GET\" action=\"page.php\">";
		echo "\n<input type=\"hidden\" name=\"project_id\" value=\"".$project_id."\">";
		echo "\n<input type=\"hidden\" name=\"page\" value=\"add\">";
		echo "\n<input type=\"hidden\" name=\"table\" value=\"".$table."\">";

		// Start table
		echo "\n<TABLE border=\"1\">";

		// more fields
		echo "\t \n<TR>";
		echo "\t\t \n<TD>Episode Name*</TD>";
		echo "\t\t \n<TD><input type=\"text\" pattern=\"[^'\\x22]+\" name=\"name\" pattern=\"[^'\\x22]+\" autocomplete=\"yes\" required maxlength=\"50\" placeholder=\"episode name\" size=\"50\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Director*</TD>";
		echo "\t\t \n<TD><input type=\"text\" pattern=\"[^'\\x22]+\" name=\"director\" pattern=\"[^'\\x22]+\" autocomplete=\"yes\" required maxlength=\"50\" placeholder=\"director\" size=\"50\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Writer*</TD>";
		echo "\t\t \n<TD><input type=\"text\" pattern=\"[^'\\x22]+\" name=\"writer\" pattern=\"[^'\\x22]+\" autocomplete=\"yes\" required maxlength=\"100\" placeholder=\"writer\" size=\"100\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Editor*</TD>";
		echo "\t\t \n<TD><input type=\"text\" pattern=\"[^'\\x22]+\" name=\"editor\" pattern=\"[^'\\x22]+\" autocomplete=\"yes\" required maxlength=\"100\" placeholder=\"editor\" size=\"100\"></TD>";
		echo "\t \n</TR>";

		// End Table
		echo "\n</TABLE>";

		echo "\n<BR>";

		echo "\n<input type=\"submit\" name=\"submit\" value=\"Save\">";
		echo "\n<input type=\"button\" value=\"Cancel\" <a href=\"#\" onclick=\"history.back();\"></a>";
		echo "\n</form>";
		
		if(isset($_GET['submit']))
		{

		// sanitize inputs
		$name = pg_escape_string(htmlspecialchars($_GET['name']));
		$writer = pg_escape_string(htmlspecialchars($_GET['writer']));
		$editor = pg_escape_string(htmlspecialchars($_GET['editor']));
		$director = pg_escape_string(htmlspecialchars($_GET['director']));

		// enter query
		$query = "INSERT INTO quickScript.episode
				VALUES(".$project_id.", '".$name."', DEFAULT, '".$director."', '".$writer."', '".$editor."');";

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
		// START FORM 
		echo "\n<form method=\"GET\" action=\"page.php\">";
		echo "\n<input type=\"hidden\" name=\"episode_id\" value=\"".$episode_id."\">";
		echo "\n<input type=\"hidden\" name=\"page\" value=\"add\">";
		echo "\n<input type=\"hidden\" name=\"table\" value=\"".$table."\">";

		// Start table
		echo "\n<TABLE border=\"1\">";

		// more fields
		echo "\t \n<TR>";
		echo "\t\t \n<TD>Date*</TD>";
		echo "\t\t \n<TD><input type=\"date\" pattern=\"[^'\\x22]+\" name=\"date\" required autocomplete=\"yes\" maxlength=\"11\" placeholder=\"YYYY-MM-DD\" size=\"11\"";
		echo " value=".date("Y-m-d")."></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Cam Op</TD>";
		echo "\t\t \n<TD><input type=\"text\" pattern=\"[^'\\x22]+\" name=\"cam\" autocomplete=\"yes\" maxlength=\"30\" placeholder=\"cam ops\" size=\"30\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>DOP</TD>";
		echo "\t\t \n<TD><input type=\"text\" pattern=\"[^'\\x22]+\" name=\"dop\" autocomplete=\"yes\" maxlength=\"30\" placeholder=\"dop\" size=\"30\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Scripty</TD>";
		echo "\t\t \n<TD><input type=\"text\" pattern=\"[^'\\x22]+\" name=\"scripty\" autocomplete=\"yes\" maxlength=\"30\" placeholder=\"scripty\" size=\"30\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Audio</TD>";
		echo "\t\t \n<TD><input type=\"text\" pattern=\"[^'\\x22]+\" name=\"audio\" autocomplete=\"yes\" maxlength=\"30\" placeholder=\"audio\" size=\"30\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Gaffer</TD>";
		echo "\t\t \n<TD><input type=\"text\" pattern=\"[^'\\x22]+\" name=\"gaffer\" autocomplete=\"yes\" maxlength=\"30\" placeholder=\"gaffer\" size=\"30\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>AD</TD>";
		echo "\t\t \n<TD><input type=\"text\" pattern=\"[^'\\x22]+\" name=\"ad\" autocomplete=\"yes\" maxlength=\"30\" placeholder=\"ad\" size=\"30\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Key Grip</TD>";
		echo "\t\t \n<TD><input type=\"text\" pattern=\"[^'\\x22]+\" name=\"key\" autocomplete=\"yes\" maxlength=\"30\" placeholder=\"key grip\" size=\"30\"></TD>";
		echo "\t \n</TR>";

		// End Table
		echo "\n</TABLE>";

		echo "\n<BR>";

		echo "\n<input type=\"submit\" name=\"submit\" value=\"Save\">";
		echo "\n<input type=\"button\" value=\"Cancel\" <a href=\"#\" onclick=\"history.back();\"></a>";
		echo "\n</form>";
		
		if(isset($_GET['submit']))
		{

		// sanitize inputs
		$date = pg_escape_string(htmlspecialchars($_GET['date']));
		$cam = pg_escape_string(htmlspecialchars($_GET['cam']));
		$dop = pg_escape_string(htmlspecialchars($_GET['dop']));
		$scripty = pg_escape_string(htmlspecialchars($_GET['scripty']));
		$audio = pg_escape_string(htmlspecialchars($_GET['audio']));
		$gaffer = pg_escape_string(htmlspecialchars($_GET['gaffer']));
		$ad = pg_escape_string(htmlspecialchars($_GET['ad']));
		$key = pg_escape_string(htmlspecialchars($_GET['key']));

		// enter query
		$query = "INSERT INTO quickScript.shooting_day
				VALUES(".$episode_id.", '".$date."', DEFAULT, '".$cam."', '".$dop."', '".$scripty."', '".$audio."', '".$gaffer."', '".$ad."', '".$key."');";

		// send query
		pg_prepare($conn, "insert", $query);
		if (pg_execute($conn, "insert", array())) 
		{
			redirect("page.php?page=view&table=shooting_day&id=" . $episode_id);
		}
		else
		{
			redirect("error.php?error_id=1&query=".$query);
		}
	}
}
else if($_GET['table'] == scene)
{
		// START FORM 
		echo "\n<form method=\"GET\" action=\"page.php\">";
		echo "\n<input type=\"hidden\" name=\"sd_id\" value=\"".$sd_id."\">";
		echo "\n<input type=\"hidden\" name=\"page\" value=\"add\">";
		echo "\n<input type=\"hidden\" name=\"table\" value=\"".$table."\">";

		// Start table
		echo "\n<TABLE border=\"1\">";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Scene Number*</TD>";
		echo "\t\t \n<TD><input type=\"text\" pattern=\"[^'\\x22]+\" name=\"num\" required autocomplete=\"yes\" maxlength=\"10\" placeholder=\"scene number\" size=\"10\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Description*</TD>";
		echo "\t\t \n<TD><input type=\"text\" pattern=\"[^'\\x22]+\" name=\"desc\" autocomplete=\"yes\" maxlength=\"100\" placeholder=\"description\" size=\"100\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Location*</TD>";
		echo "\t\t \n<TD><input type=\"text\" pattern=\"[^'\\x22]+\" name=\"loc\" required autocomplete=\"yes\" maxlength=\"30\" placeholder=\"location\" size=\"30\"></TD>";
		echo "\t \n</TR>";

		// End Table
		echo "\n</TABLE>";

		echo "\n<BR>";

		echo "\n<input type=\"submit\" name=\"submit\" value=\"Save\">";
		echo "\n<input type=\"button\" value=\"Cancel\" <a href=\"#\" onclick=\"history.back();\"></a>";
		echo "\n</form>";
		
		if(isset($_GET['submit']))
		{
		// sanitize inputs
		$num = pg_escape_string(htmlspecialchars($_GET['num']));
		$desc = pg_escape_string(htmlspecialchars($_GET['desc']));
		$loc = pg_escape_string(htmlspecialchars($_GET['loc']));

		// enter query
		$query = "INSERT INTO quickScript.scene
				VALUES(".$sd_id.", DEFAULT, '".$num."', '".$desc."', '".$loc."');";

		// send query
		pg_prepare($conn, "insert", $query);
		if (pg_execute($conn, "insert", array())) 
		{
			redirect('page.php?page=view&table=scene&id=' . $sd_id);
		}
		else
		{
			redirect('error.php?error_id=1&query='.$query);
		}
	}
}
else if($_GET['table'] == take)
{
		// START FORM 
		echo "\n<form method=\"GET\" action=\"page.php\">";
		echo "\n<input type=\"hidden\" name=\"scene_id\" value=\"".$scene_id."\">";
		echo "\n<input type=\"hidden\" name=\"page\" value=\"".$page."\">";
		echo "\n<input type=\"hidden\" name=\"table\" value=\"".$table."\">";

		// Start table
		echo "\n<TABLE border=\"1\">";

		if($take_number==NULL)
		{
			echo "\t \n<TR>";
			echo "\t\t \n<TD>Take Number*</TD>";
			echo "\t\t \n<TD><input type=\"number\" pattern=\"[^'\\x22]+\" name=\"num\" required autocomplete=\"yes\" maxlength=\"5\" placeholder=\"take number\" size=\"5\" min=\"1\" value=\"1\"></TD>";
			echo "\t \n</TR>";
		}
		else
		{
			$take_number += 1;
			echo "\t \n<TR>";
			echo "\t\t \n<TD>Take Number*</TD>";
			echo "\t\t \n<TD><input type=\"number\" pattern=\"[^'\\x22]+\" name=\"num\" required autocomplete=\"yes\" maxlength=\"5\" placeholder=\"take number\" size=\"5\" min=\"1\" value=\"".$take_number."\"></TD>";
			echo "\t \n</TR>";
		}


		if($cam1_file==NULL)
		{
			echo "\t \n<TR>";
			echo "\t\t \n<TD>Cam 1</TD>";
			echo "\t\t \n<TD><input type=\"number\" pattern=\"[^'\\x22]+\" name=\"cam1\" required autocomplete=\"yes\" maxlength=\"10\" placeholder=\"cam1 file\" size=\"10\" value=\"1\"></TD>";
			echo "\t \n</TR>";
		}
		else
		{
			$cam1_file += 1;
			echo "\t \n<TR>";
			echo "\t\t \n<TD>Cam 1</TD>";
			echo "\t\t \n<TD><input type=\"number\" pattern=\"[^'\\x22]+\" name=\"cam1\" required autocomplete=\"yes\" maxlength=\"10\" placeholder=\"cam1 file\" size=\"10\" value=\"".$cam1_file."\"></TD>";
			echo "\t \n</TR>";
		}

		if($cam2_file==NULL)
		{
			echo "\t \n<TR>";
			echo "\t\t \n<TD>Cam 2</TD>";
			echo "\t\t \n<TD><input type=\"number\" pattern=\"[^'\\x22]+\" name=\"cam2\" autocomplete=\"yes\" maxlength=\"10\" placeholder=\"cam2 file\" size=\"10\"></TD>";
			echo "\t \n</TR>";
		}
		else
		{
			$cam2_file += 1;
			echo "\t \n<TR>";
			echo "\t\t \n<TD>Cam 2</TD>";
			echo "\t\t \n<TD><input type=\"number\" pattern=\"[^'\\x22]+\" name=\"cam2\" autocomplete=\"yes\" maxlength=\"10\" placeholder=\"cam2 file\" size=\"10\" value=\"".$cam2_file."\"></TD>";
			echo "\t \n</TR>";
		}

		if($cam3_file==NULL)
		{
			echo "\t \n<TR>";
			echo "\t\t \n<TD>Cam 3</TD>";
			echo "\t\t \n<TD><input type=\"number\" pattern=\"[^'\\x22]+\" name=\"cam3\" autocomplete=\"yes\" maxlength=\"10\" placeholder=\"cam3 file\" size=\"10\"></TD>";
			echo "\t \n</TR>";
		}
		else
		{
			$cam3_file += 1;
			echo "\t \n<TR>";
			echo "\t\t \n<TD>Cam 3</TD>";
			echo "\t\t \n<TD><input type=\"number\" pattern=\"[^'\\x22]+\" name=\"cam3\" autocomplete=\"yes\" maxlength=\"10\" placeholder=\"cam3 file\" size=\"10\" value=\"".$cam3_file."\"></TD>";
			echo "\t \n</TR>";
		}

		if($audio_file==NULL)
		{
			echo "\t \n<TR>";
			echo "\t\t \n<TD>Audio File</TD>";
			echo "\t\t \n<TD><input type=\"number\" pattern=\"[^'\\x22]+\" name=\"audio\" autocomplete=\"yes\" maxlength=\"10\" placeholder=\"audio file\" size=\"10\" value=\"1\"></TD>";
			echo "\t \n</TR>";
		}
		else
		{
			$audio_file += 1;
			echo "\t \n<TR>";
			echo "\t\t \n<TD>Audio File</TD>";
			echo "\t\t \n<TD><input type=\"text\" pattern=\"[^'\\x22]+\" name=\"audio\" autocomplete=\"yes\" maxlength=\"10\" placeholder=\"audio file\" size=\"10\" value=\"".$audio_file."\"></TD>";
			echo "\t \n</TR>";
		}

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Notes</TD>";
		echo "\t\t \n<TD><input type=\"text\" pattern=\"[^'\\x22]+\" name=\"notes\" autocomplete=\"yes\" maxlength=\"100\" placeholder=\"notes\" size=\"100\"></TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Rating*</TD>";
		echo "\t\t \n<TD><input type=\"radio\" value=\"1\" name=\"rating\">1";
		echo "\t\t\n<input type=\"radio\" value=\"2\" name=\"rating\">2";
		echo "\t\t\n<input type=\"radio\" value=\"3\" name=\"rating\">3";
		echo "\t\t\n<input type=\"radio\" value=\"4\" name=\"rating\">4";
		echo "\t\t\n<input type=\"radio\" value=\"5\" name=\"rating\" checked=\"yes\">5";
		echo "\t\t\n<input type=\"radio\" value=\"6\" name=\"rating\">6";
		echo "\t\t\n<input type=\"radio\" value=\"7\" name=\"rating\">7";
		echo "\t\t\n<input type=\"radio\" value=\"8\" name=\"rating\">8";
		echo "\t\t\n<input type=\"radio\" value=\"9\" name=\"rating\">9";
		echo "\t\t\n<input type=\"radio\" value=\"10\" name=\"rating\">10</td>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Dead Take?</TD>";
		echo "\t\t \n<TD><input type=\"radio\" name=\"dead\" value=\"TRUE\">Yes";
		echo "\t\t \n<input type=\"radio\" name=\"dead\" value=\"FALSE\" checked=\"yes\"> No</TD>";
		echo "\t \n</TR>";

		echo "\t \n<TR>";
		echo "\t\t \n<TD>Blooper?</TD>";
		echo "\t\t \n<TD><input type=\"radio\" name=\"bloop\" value=\"TRUE\">Yes";
		echo "\t\t \n<input type=\"radio\" name=\"bloop\" value=\"FALSE\" checked=\"yes\">No</TD>";
		echo "\t \n</TR>";

		// End Table
		echo "\n</TABLE>";

		echo "\n<BR>";

		echo "\n<input type=\"submit\" name=\"submit\" value=\"Save\">";
		echo "\n<input type=\"button\" value=\"Cancel\" <a href=\"#\" onclick=\"history.back();\"></a>";
		echo "\n</form>";
		
		if(isset($_GET['submit']))
		{
		// sanitize inputs
		$num = pg_escape_string(htmlspecialchars($_GET['num']));
		$cam1 = pg_escape_string(htmlspecialchars($_GET['cam1']));
		$cam2 = pg_escape_string(htmlspecialchars($_GET['cam2']));
		if($cam2 == "")
		{
			$cam2 = "NULL";
		}
		$cam3 = pg_escape_string(htmlspecialchars($_GET['cam3']));
		if($cam3 == "")
		{
			$cam3 = "NULL";
		}
		$audio = pg_escape_string(htmlspecialchars($_GET['audio']));
		if($audio == "")
		{
			$audio = "NULL";
		}
		$notes = pg_escape_string(htmlspecialchars($_GET['notes']));
		$rating = pg_escape_string(htmlspecialchars($_GET['rating']));
		$dead = pg_escape_string(htmlspecialchars($_GET['dead']));
		$bloop = pg_escape_string(htmlspecialchars($_GET['bloop']));

		// enter query
		$query = "INSERT INTO quickScript.take
				VALUES(".$scene_id.", ".$num.", ".$cam1.", ".$cam2.", ".$cam3.", ".$audio.", '".$notes."',".$rating.", ".$dead.", ".$bloop.", DEFAULT);";

		// send query
		pg_prepare($conn, "insert", $query);
		if (pg_execute($conn, "insert", array())) 
		{
			redirect("page.php?page=view&table=take&id=" . $scene_id);
		}
		else
		{
			redirect("error.php?error_id=1");
		}
	}
}
else {
	redirect("error.php?error_id=2");
}

?>
