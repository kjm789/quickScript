<!-- Michael Koboldt, William D Minard, Daniel J Hart, Kenny Clark, Matthew Pokoik
	QuickScript
	4-30-15 -->

<?php include 'exec.php';

	//if user is not logged in, send them to the index page
	if( $_SESSION[ 'username' ] == NULL )
	{
		header( 'Location: page.php?page=login' );
	} //end if

	//grab username information from session array
	$username = $_SESSION[ 'username' ];

	//grab episode id from url
	$episode_id = $_GET[ 'eid' ];

	//begin page div
	echo "<div align='center'>\n";

	//query to grab episode name
	$query = 'SELECT episode_name FROM quickScript.episode WHERE episode_id = $1';
	pg_prepare( $conn, "episode_name", $query );
	$execute = pg_execute( $conn, "episode_name", array( $episode_id ) );
	$result = pg_fetch_array( $execute, NULL, PGSQL_ASSOC );
	$episode_name = $result[ 'episode_name' ];

	//header for page
	echo "<h1>Blooper Reel For \"$episode_name\"</h1>\n";
	echo "<p>---------------------------------------------------------------</p>\n";

	//query to select all blooper takes based on episode id
	$query = 'SELECT * FROM quickScript.take WHERE is_bloop = true AND scene_id IN
			 (
			 	SELECT scene_id FROM quickScript.scene WHERE sd_id IN
			 	(
			 		SELECT sd_id FROM quickScript.shooting_day WHERE episode_id = $1
			 	)
			 )';
	bareBones_table_display( $query, "take", $episode_id );
/*
	pg_prepare( $conn, "bloopers", $query );
	$execute = pg_execute( $conn, "bloopers", array( $episode_id ) );

	//grab number of rows from query
	$rows = pg_num_rows( $execute );
	
	//begin table tags
	echo "<table border = \"1\" margin=\"20px\">\n";
	echo "\t<tr>\n";

	//for loop to print out table headers
	for( $counter = 0; $counter < pg_num_fields( $execute ); $counter++ )
	{
		$field = pg_field_name( $execute, $counter );
		echo "\t\t<th>" . $field . "</th>';"
	} //end for
	echo "\t</tr>\n";

	//data from query
	while( $line = pg_fetch_array( $execute, NULL, PGSQL_ASSOC ) )
	{
		echo "\t<tr>\n";
		echo "\t\t<td>" . $line[ 'take_number' ] . "</td>\n";
		echo "\t\t<td>" . $line[ 'take_number' ] . "</td>\n";
		echo "\t\t<td>" . $line[ 'take_number' ] . "</td>\n";
		echo "\t\t<td>" . $line[ 'take_number' ] . "</td>\n";
		echo "\t\t<td>" . $line[ 'take_number' ] . "</td>\n";
		echo "\t\t<td>" . $line[ 'take_number' ] . "</td>\n";
		echo "\t\t<td>" . $line[ 'take_number' ] . "</td>\n";
	}
*/
	//return to home page
	echo "<p>---------------------------------------------------------------</p>\n";
	echo "<a href='page.php?page=home'>Return</a> to the home page.";

	//end page div
	echo "</div>\n";
?>