<!-- Michael Koboldt, William D Minard, Daniel J Hart, Kenny Clark, Matthew Pokoik
		QuickScript
		04-30-15 -->

<?php
// Connect to Database
include('./secure/database.php');
$conn = pg_connect(HOST." ".DBNAME." ".USERNAME." ".PASSWORD) 
	or die(header('Location: page.php?page=index'));

// If we are not in an https session, redirect to one

if( $_SERVER[ 'SERVER_PORT' ] != 443 )
{
	$host = $_SERVER[ 'HTTP_HOST' ];
	$request = $_SERVER[ 'REQUEST_URI' ];
	header( 'Location: https://' . $host . $request );
}
function check_for_table_error($table)
{
	if($table==NULL)
	{
		header('location: error.php?error_id=2');
	}
}
function redirect($url)
{
    if (!headers_sent())
    {    
        header('Location: '.$url);
        exit;
        }
    else
        {  
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>'; exit;
    }
}
function take_autoi_data($scene_id)
{
	global $conn;
	// Grab username
	$scene_id = pg_escape_string( htmlspecialchars($scene_id) );
	
	// Query database.
	$query = "SELECT take_number, cam1_file, cam2_file, cam3_file, audio_file, notes, rating, is_dead, is_bloop, take_id, scene_id FROM quickScript.take WHERE scene_id = $1 ORDER BY take_number ASC";
	
	// run
	pg_prepare( $conn, "get_autoi_data", $query );
	$execute = pg_execute( $conn, "get_autoi_data", array($scene_id) );

	while ($line = pg_fetch_array($execute, null, PGSQL_ASSOC)) 
	{
		print_input_values($line);
	}
}
function view_all($username, $table, $display_name)
{
	echo "<li><a href=\"page.php?page=view&table=all&all_table=".$table."\">".$display_name ."</a></li>";
}
function find_all($username, $table)
{
	if( $table == 'episode')
	{
		$query = "SELECT * FROM quickScript.episode WHERE project_id IN(SELECT project_id FROM quickScript.project WHERE owner ILIKE $1);";
		bareBones_table_display($query, "episode", $username);
	}
	else if( $table == 'shooting_day' )
	{
		$query = "SELECT * FROM quickScript.shooting_day WHERE episode_id IN(SELECT episode_id FROM quickScript.episode WHERE project_id IN(SELECT project_id FROM quickScript.project WHERE owner ILIKE $1));";
		bareBones_table_display($query, "shooting_day", $username);
	}
	else if( $table == 'scene' )
	{
		$query = "SELECT * FROM quickScript.scene WHERE sd_id IN(SELECT sd_id FROM quickScript.shooting_day WHERE episode_id IN(SELECT episode_id FROM quickScript.episode WHERE project_id IN(SELECT project_id FROM quickScript.project WHERE owner ILIKE $1)));";
		bareBones_table_display($query, "scene", $username);
	}
	else if( $table == 'take')
	{
		$query = "SELECT * FROM quickScript.take WHERE scene_id IN(SELECT scene_id FROM quickScript.scene WHERE sd_id IN(SELECT sd_id FROM quickScript.shooting_day WHERE episode_id IN(SELECT episode_id FROM quickScript.episode WHERE project_id IN(SELECT project_id FROM quickScript.project WHERE owner ILIKE $1))));";
		bareBones_table_display($query, "take", $username);
	}
}
// Function that checks if a username 
// exists or not.
function check_username($username)
{
	// Connect to Database
	global $conn;
	
	// Error Checking
	$username = pg_escape_string( htmlspecialchars($username) );
	$password = pg_escape_string( htmlspecialchars($password) );
	
	// Create Query
	$query = "SELECT * FROM users.user_info 
				WHERE username 
				LIKE $1";
	
	// Prepare and send query to the database.
	pg_prepare($conn, "check_username_", $query );
	$result = pg_execute($conn, "check_username_", array($username) );
	
	// Return 1 if a username is returned.
	// 0 otherwise.
	
	// If 0 rows are returned, that
	// means that the username does
	// not exist.
	if( pg_num_rows($result) == 0)
		return 0;
	else
		return 1;
}

// Function that creates a new user in the
// database.
function create_user($username, $password)
{
	// Connect to Database
	global $conn;
	
	// Grab username and password from fields.
	$username = pg_escape_string( htmlspecialchars($username) );
	$password = pg_escape_string( htmlspecialchars($password) );
	
	// Salt and hash.
	$salt = sha1( rand() ); 
	$hash = sha1($password . $salt);

	// Send Query to user info table
	$query = "INSERT INTO users.user_info (username) 
				VALUES ($1)";
	
	// Prepare and send query to database.
	pg_prepare($conn, "add_user_info_", $query);

	// Authenticate information.
	$query = "INSERT INTO users.authentication (username, password_hash,salt) 
				VALUES ($1, $2, $3)";
	
	// Prepare and send query to database.
	pg_prepare($conn, "add_user_auth_",$query);
	
	// Execute queries.
	pg_execute($conn, "add_user_info_", array($username));
	pg_execute($conn,"add_user_auth_", array($username, $hash, $salt));
}

// Function that checks the login information
// provided by the user. 

// Makes sure to protect against SQL 
// Injection attacks.
function check_login($username, $password)
{
	// Connect to Database
	global $conn;

	// Query database to determine
	// correct salt and hash.
	$query = "SELECT salt, password_hash 
				FROM users.authentication 
				WHERE username 
				LIKE $1";
	
	// Prepare query.
	pg_prepare($conn,"login",$query);

	// Grab username and password.
	$username = pg_escape_string( htmlspecialchars($username) );
	$password = pg_escape_string( htmlspecialchars($password) );
	
	// Get results.
	$result = pg_execute($conn, "login", array($username));
	
	// Assign result to var of salt.
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$salt = $info['salt'];

	if( sha1($password . $salt) == $info['password_hash'] )
		return 1;
	else 
		return 0;
}

// This function gets the ip of
// the provided username.
function get_ip($username)
{
	// Connect to Database
	global $conn;
	
	// Grab username.
	$username = pg_escape_string(htmlspecialchars($username));
	
	// Query database.
	$query = "SELECT ip_address 
				FROM users.log 
				WHERE username 
				LIKE $1 
				ORDER BY log_date 
				LIMIT 1";
	
	// Prepare the statment.
	pg_prepare($conn,"get_ip_", $query);
	
	// Get results.
	$result = pg_execute($conn,"get_ip_", array($username));
	
	// Get info from results.
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	
	// Return string of ip address.
	return $info['ip_address'];	
}

// This function takes the username, the\
// IP and the action, and writes it to the "logs"
// table.
function log_data($username, $ip, $action)
{
	// Connect to Database
	global $conn;
	
	// Grab usernmae.
	$username = pg_escape_string( htmlspecialchars($username) );
	
	// Grab IP
	$ip = pg_escape_string( htmlspecialchars($ip) );
	
	// Grab action
	$action = pg_escape_string( htmlspecialchars($action) );
	
	// Query Database
	$query = "INSERT INTO users.log (username, ip_address, action) 
				VALUES ($1, $2, $3)";
	
	// Prepare statement
	pg_prepare($conn, "data_log_", $query);
	
	// Execute statement
	pg_execute($conn, "data_log_", array($username, $ip, $action));
}

// This function prints a HTML
// table with all the 
// reg, log in, log out, and
// updated descriptions that a user
// has performed and what time that 
// it occoured.
function print_log_table($username)
{
	// Connect to Database
	global $conn;
	
	// Grab username
	$username = pg_escape_string( htmlspecialchars($username) );
	
	// Query database.
	$query = "SELECT action, ip_address, log_date 
				FROM users.log 
				WHERE username 
				LIKE $1 
				ORDER BY log_date DESC";
	
	// Prepare statement
	pg_prepare($conn,"get_logs_",$query);
	
	// Get results.
	$result = pg_execute($conn,"get_logs_", array($username));

	// Make table.
    echo "\n<table border=\"1\">\n\t<tr>\n";

    $i = 0;
    $numCol = pg_num_fields($result);
	for( $i = 0; $i < $numCol; $i++ )
	{
		$fieldName = pg_field_name($result, $i);
		echo "\t\t<th>" . $fieldName . "</th>\n";
	}
    echo "\t</tr>\n";

	// Fill data
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
    {
      echo "\t<tr>\n";
      foreach($line as $col_value)
      {
        echo "\t\t<td>$col_value</td>\n";
      }
      echo "\t</tr>\n";
    }
    echo "</table>\n";
}

// This function returns the 
// registration date of the provided
// username.
function get_reg_date($username)
{
	// Connect to Database
	global $conn;
	
	// Grab username
	$username = pg_escape_string( htmlspecialchars($username) );
	
	// Query database
	$query = "SELECT registration_date 
				FROM users.user_info 
				WHERE username 
				LIKE $1";
	
	// Prepare query.
	pg_prepare($conn,"reg_date_", $query);
	
	// Get results.
	$result = pg_execute($conn, "reg_date_", array($username));
	
	// Get info from results.
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	
	// return string with reg date.
	return $info['registration_date'];
}
function find_projects($username)
{
	// Grab username
	$username = pg_escape_string( htmlspecialchars($username) );
	
	// Query database.
	$query = "SELECT project_name, created_by, producer, start_date, project_id
				FROM quickScript.project WHERE owner = $1 ORDER BY start_date DESC";
	
	print_table($query, "project", $username);
	
}
function find_episodes($project_id)
{
	// Grab username
	$project_id = pg_escape_string( htmlspecialchars($project_id) );
	
	// Query database.
	$query = "SELECT episode_name, director, writer, editor, episode_id, project_id FROM quickScript.episode WHERE project_id = $1 ORDER BY episode_id ASC";
	
	print_table($query, "episode", $project_id);
}
function find_sds($episode_id)
{
	// Grab username
	$episode_id = pg_escape_string( htmlspecialchars($episode_id) );
	
	// Query database.
	$query = "SELECT sd_date, cam_ops, dop, scripty, audio, gaffer, ad, key_grip, sd_id FROM quickScript.shooting_day WHERE episode_id = $1 ORDER BY sd_date ASC";
	
	print_table($query, "shooting_day", $episode_id);	
}
function find_scenes($sd_id)
{
	// Grab username
	$sd_id = pg_escape_string( htmlspecialchars($sd_id) );
	
	// Query database.
	$query ="SELECT scene_number, description, location, scene_id FROM quickScript.scene WHERE sd_id = $1 ORDER BY scene_id ASC";
	
	print_table($query, "scene", $sd_id);	
}
function view_scenes_from_ep($episode_id)
{
	// Grab username
	$episode_id = pg_escape_string( htmlspecialchars($episode_id) );
	
	// Query database.
	$query ="SELECT scene_number, description, location, scene_id FROM quickScript.scene WHERE sd_id IN
			(
				SELECT sd_id FROM quickScript.shooting_day WHERE episode_id = $1
			) ORDER BY scene_id ASC";
	
	print_table($query, "scene", $episode_id);
}
function find_takes($scene_id)
{
	// Grab username
	$scene_id = pg_escape_string( htmlspecialchars($scene_id) );
	
	// Query database.
	$query = "SELECT take_number, cam1_file, cam2_file, cam3_file, audio_file, notes, rating, is_dead, is_bloop, take_id, scene_id FROM quickScript.take WHERE scene_id = $1 ORDER BY take_number ASC";
	
	print_table($query, "take", $scene_id);			
}
function print_table($query, $table, $id)
{
	global $conn;
	if( $query != NULL )
	{
		pg_prepare( $conn, "print_table", $query );
		$execute = pg_execute( $conn, "print_table", array($id) );

		/* START TABLE */
		echo "<TABLE border=\"1\" margin=\"20px\"> \n";
		echo "<TR>";
		
		// Field names
		
		// Start with 'actions'
		if($table != 'take')
		{
			echo "<TH>Actions</TH>";
		}
		
		$counter = 0;
		for($counter = 0; $counter < pg_num_fields($execute); $counter++)
		{
			$field_names = pg_field_name($execute, $counter);
			echo "<TH>". $field_names ."</TH>";
		}

		// delete column
		echo "<TH>Edit / Delete</TH>";
		
		echo "</TR>";
		
		// Data from query
		while ($line = pg_fetch_array($execute, null, PGSQL_ASSOC)) 
		{
			echo "\t<TR>\n";

			//echo $id;
			
			if($table == 'project')
			{
				echo "\n<TD>";

				// Open
				echo "\n<form method=\"GET\" action=\"page.php\">";
				echo "\n<input type=\"hidden\" name=\"table\" value=\"episode\">";
				echo "\n<input type=\"hidden\" name=\"page\" value=\"view\">";
				echo "\n<input type=\"hidden\" name=\"add_id\" value=\"".$id."\">";
				echo "\n<input type=\"hidden\" name=\"id\" value=\"".$line['project_id']."\">";
				echo "\n<input type=\"submit\" name=\"go\" value=\"Open\">";
				echo "\n</form>";

				echo "</TD>";
			}
			else if($table == 'episode')
			{
				echo "\n<TD>";

				// Open
				echo "\n<form method=\"GET\" action=\"page.php\">";
				echo "\n<input type=\"hidden\" name=\"table\" value=\"shooting_day\">";
				echo "\n<input type=\"hidden\" name=\"page\" value=\"view\">";
				echo "\n<input type=\"hidden\" name=\"add_id\" value=\"".$id."\">";
				echo "\n<input type=\"hidden\" name=\"id\" value=\"".$line['episode_id']."\">";
				echo "\n<input type=\"submit\" name=\"go\" value=\"Open\">";
				echo "\n</form>";

				// Credits
				echo "\n<form method=\"GET\" action=\"page.php\">";
				echo "\n<input type=\"hidden\" name=\"page\" value=\"credits\">";
				echo "\n<input type=\"hidden\" name=\"pid\" value=\"".$line['project_id']."\">";
				echo "\n<input type=\"hidden\" name=\"eid\" value=\"".$line['episode_id']."\">";
				echo "\n<input type=\"submit\" name=\"credits\" value=\"Credits\">";
				echo "\n</form>";

				// Bloopers
				echo "\n<form method=\"GET\" action=\"page.php\">";
				echo "\n<input type=\"hidden\" name=\"page\" value=\"bloopers\">";
				echo "\n<input type=\"hidden\" name=\"pid\" value=\"".$line['project_id']."\">";
				echo "\n<input type=\"hidden\" name=\"eid\" value=\"".$line['episode_id']."\">";
				echo "\n<input type=\"submit\" name=\"bloopers\" value=\"Bloopers\">";
				echo "\n</form>";

				echo "</TD>";
			}
			else if($table == 'shooting_day')
			{
				echo "\n<TD>";

				// Open	
				echo "\n<form method=\"GET\" action=\"page.php\">";
				echo "\n<input type=\"hidden\" name=\"table\" value=\"scene\">";
				echo "\n<input type=\"hidden\" name=\"page\" value=\"view\">";
				echo "\n<input type=\"hidden\" name=\"add_id\" value=\"".$id."\">";
				echo "\n<input type=\"hidden\" name=\"id\" value=\"".$line['sd_id']."\">";
				echo "\n<input type=\"submit\" name=\"go\" value=\"Open\">";
				echo "\n</form>";

				echo "</TD>";
			}
			else if($table == 'scene')
			{
				echo "\n<TD>";

				// Open
				echo "\n<form method=\"GET\" action=\"page.php\">";
				echo "\n<input type=\"hidden\" name=\"table\" value=\"take\">";
				echo "\n<input type=\"hidden\" name=\"page\" value=\"view\">";
				echo "\n<input type=\"hidden\" name=\"add_id\" value=\"".$id."\">";
				echo "\n<input type=\"hidden\" name=\"id\" value=\"".$line['scene_id']."\">";
				echo "\n<input type=\"submit\" name=\"go\" value=\"Open\">";
				echo "\n</form>";

				echo "</TD>";	
			}
			else if($table == 'take')
			{
				echo "\n<input type=\"hidden\" name=\"page\" value=\"view\">";

				// GET Data
				echo "\n<input type=\"hidden\" name=\"take_number\" value=\"".$line['take_number']."\">";
				echo "\n<input type=\"hidden\" name=\"cam1_file\" value=\"".$line['cam1_file']."\">";
				echo "\n<input type=\"hidden\" name=\"cam2_file\" value=\"".$line['cam2_file']."\">";
				echo "\n<input type=\"hidden\" name=\"cam3_file\" value=\"".$line['cam3_file']."\">";
				echo "\n<input type=\"hidden\" name=\"audio_file\" value=\"".$line['audio_file']."\">";
			}
			else {
				echo "";
			}
			
			foreach ($line as $col_value) 
			{
				if($col_value==NULL)
				{
					echo "\t\t<TD bgcolor=\"white\"> </TD> \n";
				}
				else
				{
					if($col_value==t)
					{
						echo "<TD bgcolor=\"#66FF66\"> YES </TD>";
					}
					else if($col_value==f)
					{
						echo "<TD bgcolor=\"#FF6666\"> NO </TD>";
					}
					else
					{
						echo "\t\t<TD> $col_value </TD> \n"; 
					}
				}
			}

			// DELETE
			if($table == 'project')
			{
				echo "\n<TD>";

				// Edit
				echo "\n<form method=\"GET\" action=\"page.php\">";
				echo "\n<input type=\"hidden\" name=\"table\" value=\"project\">";
				echo "\n<input type=\"hidden\" name=\"page\" value=\"edit\">";

				// Send values
				print_input_values($line);
				echo "\n<input type=\"submit\" name=\"go\" value=\"Edit\">";
				echo "\n</form>";

				// Delete
				echo "\n<form method=\"GET\" action=\"page.php\">";

				// Values
				echo "\n<input type=\"hidden\" name=\"table\" value=\"project\">";
				echo "\n<input type=\"hidden\" name=\"page\" value=\"delete\">";
				echo "\n<input type=\"hidden\" name=\"item\" value=\"".$line['project_name']."\">";
				echo "\n<input type=\"hidden\" name=\"id\" value=\"".$line['project_id']."\">";
				echo "\n<input type=\"submit\" name=\"del\" value=\"Delete\">";
				echo "\n</form>";

				echo "</TD>";
			}
			else if($table == 'episode')
			{
				echo "\n<TD>";

				// Edit
				echo "\n<form method=\"GET\" action=\"page.php\">";

				// Values
				echo "\n<input type=\"hidden\" name=\"table\" value=\"episode\">";
				echo "\n<input type=\"hidden\" name=\"page\" value=\"edit\">";
				print_input_values($line);
				echo "\n<input type=\"submit\" name=\"go\" value=\"Edit\">";
				echo "\n</form>";

				// Delete
				echo "\n<form method=\"GET\" action=\"page.php\">";

				// Values
				echo "\n<input type=\"hidden\" name=\"table\" value=\"episode\">";
				echo "\n<input type=\"hidden\" name=\"page\" value=\"delete\">";
				echo "\n<input type=\"hidden\" name=\"item\" value=\"".$line['episode_name']."\">";
				echo "\n<input type=\"hidden\" name=\"id\" value=\"".$line['episode_id']."\">";
				echo "\n<input type=\"submit\" name=\"del\" value=\"Delete\">";
				echo "\n</form>";

				// View all scenes.
				echo "\n<a href=\"page.php?page=view&table=scene&id=" . $line['episode_id'] ."&add_id=".$id."&option=".$line['episode_id']."\"> View All Scenes</a>";
				echo "</TD>";
			}
			else if($table == 'shooting_day')
			{
				echo "\n<TD>";

				// Edit
				echo "\n<form method=\"GET\" action=\"page.php\">";

				// Values
				echo "\n<input type=\"hidden\" name=\"table\" value=\"shooting_day\">";
				echo "\n<input type=\"hidden\" name=\"page\" value=\"edit\">";
				print_input_values($line);
				echo "\n<input type=\"submit\" name=\"go\" value=\"Edit\">";
				echo "\n</form>";

				// Delete
				echo "\n<form method=\"GET\" action=\"page.php\">";

				// Values
				echo "\n<input type=\"hidden\" name=\"table\" value=\"shooting_day\">";
				echo "\n<input type=\"hidden\" name=\"page\" value=\"delete\">";
				echo "\n<input type=\"hidden\" name=\"item\" value=\"".$line['sd_date']."\">";
				echo "\n<input type=\"hidden\" name=\"id\" value=\"".$line['sd_id']."\">";
				echo "\n<input type=\"submit\" name=\"del\" value=\"Delete\">";
				echo "\n</form>";

				echo "</TD>";	
			}
			else if($table == 'scene')
			{
				echo "\n<TD>";

				// Edit
				echo "\n<form method=\"GET\" action=\"page.php\">";

				// values
				echo "\n<input type=\"hidden\" name=\"table\" value=\"scene\">";
				echo "\n<input type=\"hidden\" name=\"page\" value=\"edit\">";
				print_input_values($line);
				echo "\n<input type=\"submit\" name=\"go\" value=\"Edit\">";
				echo "\n</form>";

				// Delete
				echo "\n<form method=\"GET\" action=\"page.php\">";

				// Values
				echo "\n<input type=\"hidden\" name=\"table\" value=\"scene\">";
				echo "\n<input type=\"hidden\" name=\"page\" value=\"delete\">";
				echo "\n<input type=\"hidden\" name=\"item\" value=\"".$line['scene_number']."\">";
				echo "\n<input type=\"hidden\" name=\"id\" value=\"".$line['scene_id']."\">";
				echo "\n<input type=\"submit\" name=\"del\" value=\"Delete\">";
				echo "\n</form>";
				echo "</TD>";
			}
			else if($table == 'take')
			{
				echo "\n<TD>";

				// Edit
				echo "\n<form method=\"GET\" action=\"page.php\">";

				// Values
				echo "\n<input type=\"hidden\" name=\"table\" value=\"take\">";
				echo "\n<input type=\"hidden\" name=\"page\" value=\"edit\">";
				print_input_values($line);
				echo "\n<input type=\"submit\" name=\"go\" value=\"Edit\">";
				echo "\n</form>";

				// Delete
				echo "\n<form method=\"GET\" action=\"page.php\">";

				// Values
				echo "\n<input type=\"hidden\" name=\"table\" value=\"take\">";
				echo "\n<input type=\"hidden\" name=\"page\" value=\"delete\">";
				echo "\n<input type=\"hidden\" name=\"take\" value=\"".$line['take_number']."\">";
				echo "\n<input type=\"hidden\" name=\"id\" value=\"".$line['scene_id']."\">";
				echo "\n<input type=\"submit\" name=\"del\" value=\"Delete\">";
				echo "\n</form>";
				echo "</TD>";
			}
			else {
				header('location: error.php?error_id=2');
			}
		
			echo "\t</TR> \n"; 
		}
		echo "</TABLE> \n";
		
		// Free result set
		//pg_free_result($result);
	}
}
function print_input_values($line)
{
	$i = 1;
	foreach ($line as $col_value) 
	{
		echo "\n<input type=\"hidden\" name=\"".$i."\" value=\"".$col_value."\">";	
		$i++;
	}
}
function save_image($username, $fileName, $fileSize, $fileType, $fileDesc, $fileNmBYusr, $projNm, $fileTmpNm)
{
	global $conn;
	
	$temp = explode("." ,$fileName);
	$newFileName = pg_escape_string(microtime().'.'.end($temp));

	$newFileName = str_replace(" ", "", $newFileName);
	$newFileName = substr($newFileName, 2);

	$query = "SELECT project_id FROM quickscript.project WHERE owner=$1 AND project_name=$2;";
	
	pg_prepare($conn, "upload", $query);
	$result = pg_execute($conn, "upload", array($username, $projNm));
	$line = pg_fetch_array($result, null, PGSQL_ASSOC);
	$trgtDir ='upload/'.$username.'/'.$line['project_id'].'/'.$newFileName;
	pg_free_result($result);
	
	if(file_exists($trgtDir)){
		echo $filename ." Already exists";
		return 0;
	}
	if(!file_exists('./upload/'.$username.'/'.$line['project_id'].'/')){
			mkdir('upload/'.$username.'/'.$line['project_id'].'/', 0777, true);
			chmod('upload/'.$username.'/', 0777);
			chmod('upload/', 0777);
	}

	if(move_uploaded_file($fileTmpNm, $trgtDir)){
		$query = "INSERT INTO quickscript.project_image(path, image_id, size, type, name, description) VALUES ($1, $2, $3, $4, $5, $6);";
		pg_prepare($conn, "insert", $query) or die("Error prepare:".pg_last_error());

		if((pg_execute($conn, "insert", array($trgtDir, $line['project_id'], $fileSize, $fileType, $fileNmByusr, $fileDesc))) != false){
			return 1;
		}
	}
	else{
		return -1;
	}
}

function findImages($username, $projectName){

	global $conn;

	$query = "SELECT img.path FROM quickscript.project_image AS img INNER JOIN quickscript.project AS proj ON img.image_id = proj.project_id WHERE proj.owner =$1 AND proj.project_name =$2";
	pg_prepare($conn, "save", $query);
	$result = pg_execute($conn, "save", array($username, $projectName));
/*	$result = pg_query($conn, $query) or die("Error:". pg_last_error());*/
	echo "<table border='1'>";
	if(pg_num_rows($result) > 0){
		while($line = pg_fetch_array($result)){
			echo "<tr><td>";
				echo "<img src=".$line['path']." width=\"50%\">";
				echo "</td></tr>";
		}	
		echo "</table>";
		pg_free_result($result);
		return 1;
	}
	else{
		return 0;
	}
}
function bareBones_table_display($query, $table, $id)
{
	global $conn;
	if( $query != NULL )
	{
		pg_prepare( $conn, "print_table", $query );
		$execute = pg_execute( $conn, "print_table", array($id) );

		/* START TABLE */
		echo "<TABLE border=\"1\" margin=\"20px\"> \n";
		echo "<TR>";
		
		// Field names
		
		// Start with 'actions'
		/*
		if($table != 'take')
		{
			echo "<TH>Actions</TH>";
		} 
		*/
		
		$counter = 0;
		for($counter = 0; $counter < pg_num_fields($execute); $counter++)
		{
			$field_names = pg_field_name($execute, $counter);
			echo "<TH>". $field_names ."</TH>";
		}

		// delete column
		/*
		echo "<TH>Edit / Delete</TH>";
		*/
		
		echo "</TR>";
		
		// Data from query
		while ($line = pg_fetch_array($execute, null, PGSQL_ASSOC)) 
		{
			echo "\t<TR>\n";

			//echo $id;
			
			
			foreach ($line as $col_value) 
			{
				if($col_value==NULL)
				{
					echo "\t\t<TD bgcolor=\"white\"> </TD> \n";
				}
				else
				{
					if($col_value==t)
					{
						echo "<TD bgcolor=\"#66FF66\"> YES </TD>";
					}
					else if($col_value==f)
					{
						echo "<TD bgcolor=\"#FF6666\"> NO </TD>";
					}
					else
					{
						echo "\t\t<TD> $col_value </TD> \n"; 
					}
				}
			}
		
			echo "\t</TR> \n"; 

		}
		
		echo "</TABLE> \n";
		
		// Free result set
		//pg_free_result($result);
	}
}
?>
