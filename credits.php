<!-- Michael Koboldt, William D Minard, Daniel J Hart, Kenny Clark, Matthew Pokoik
	QuickScript
	4-30-15 -->
<?php
	//begin user session
	session_start( );

	//include database connection
	include( "exec.php" );

	//if user is not logged in, send them to the index page
	if( $_SESSION[ 'username' ] == NULL )
	{
		header( 'Location: index.php' );
	} //end if

	//grab username information from session array
	$username = $_SESSION[ 'username' ];

	//grab episode id from url
	$episode_id = $_GET[ 'eid' ];

	//begin page div
	echo "<div align='center'>\n";

	//execute query to grab relevant info from episode table
	$query = 'SELECT episode_name, director, writer, editor FROM quickScript.episode WHERE episode_id = $1'; 
	pg_prepare( $conn, "episode_crew_info", $query );
	$execute = pg_execute( $conn, "episode_crew_info", array( $episode_id ) );
	$result = pg_fetch_array( $execute, NULL, PGSQL_ASSOC );

	//header for page
	$episode = $result[ 'episode_name' ];
	echo "\t<h1>Credit Roll For \"$episode\"</h1>\n";
	echo "<p>---------------------------------------------------------------</p>\n";

	//grab and print info
	$director = $result[ 'director' ];
	$writer = $result[ 'writer' ];
	$editor = $result[ 'editor' ];
	echo "<h3>DIRECTOR</h3>\n";
	echo "<p>$director</p>\n";
	echo "<h3>WRITER</h3>\n";
	echo "<p>$writer</p>\n";
	echo "<h3>EDITOR</h3>\n";
	echo "<p>$editor</p>\n";

	//execute query for crew information from projects table
	$query = 'SELECT created_by, producer FROM quickScript.project WHERE project_id IN
			 (
			 	SELECT project_id FROM quickScript.episode WHERE episode_id = $1
			 )';
	pg_prepare( $conn, "project_crew_info", $query );
	$result = pg_execute( $conn, "project_crew_info", array( $episode_id ) );
	$result = pg_fetch_array( $result, NULL, PGSQL_ASSOC );

	//grab and print info
	$creator = $result[ 'created_by' ];
	$producer = $result[ 'producer' ];
	echo "<h3>CREATED BY</h3>\n";
	echo "<p>$creator</p>\n";
	echo "<h3>PRODUCER</h3>\n";
	echo "<p>$producer</p>\n";

	//execute query for crew information from shooting_day table
	$query = 'SELECT * FROM quickScript.shooting_day WHERE episode_id = $1';
	pg_prepare( $conn, "shoot_crew_info", $query );
	$execute = pg_execute( $conn, "shoot_crew_info", array( $episode_id ) );

	//grab number of rows from result
	$rows = pg_num_rows( $execute );

	//iterate through the shooting days and grab crew data
	for( $counter = 0; $counter < $rows; $counter++ )
	{
		//grab row information
		$result = pg_fetch_array( $execute, NULL, PGSQL_ASSOC );

		//grab cam_ops data
		if( $cam_ops == NULL )
		{
			$cam_ops = $result[ 'cam_ops' ];
		} //end if
		else
		{
			$position = strpos( $cam_ops,  $result[ 'cam_ops' ] );
			if( $position === false )
			{
				$cam_ops = $cam_ops . ", " . $result[ 'cam_ops' ];
			} //end if
		} //end else

		//grab dop data
		if( $dop == NULL )
		{
			$dop = $result[ 'dop' ];
		} //end if
		else
		{
			$position = strpos( $dop,  $result[ 'dop' ] );
			if( $position === false )
			{
				$dop = $dop . ", " . $result[ 'dop' ];
			} //end if
		} //end else

		//grab scripty data
		if( $scripty == NULL )
		{
			$scripty = $result[ 'scripty' ];
		} //end if
		else
		{
			$position = strpos( $scripty,  $result[ 'scripty' ] );
			if( $position === false )
			{
				$scripty = $scripty . ", " . $result[ 'scripty' ];
			} //end if
		} //end else

		//grab audio data
		if( $audio == NULL )
		{
			$audio = $result[ 'audio' ];
		} //end if
		else
		{
			$position = strpos( $audio,  $result[ 'audio' ] );
			if( $position === false )
			{
				$audio = $audio . ", " . $result[ 'audio' ];
			} //end if
		} //end else

		//grab gaffer data
		if( $gaffer == NULL )
		{
			$gaffer = $result[ 'gaffer' ];
		} //end if
		else
		{
			$position = strpos( $gaffer,  $result[ 'gaffer' ] );
			if( $position === false )
			{
				$gaffer = $gaffer . ", " . $result[ 'gaffer' ];
			} //end if
		} //end else

		//grab ad data
		if( $ad == NULL )
		{
			$ad = $result[ 'ad' ];
		} //end if
		else
		{
			$position = strpos( $ad,  $result[ 'ad' ] );
			if( $position === false )
			{
				$ad = $ad . ", " . $result[ 'ad' ];
			} //end if
		} //end else

		//grab key_grip data
		if( $key_grip == NULL )
		{
			$key_grip = $result[ 'key_grip' ];
		} //end if
		else
		{
			$position = strpos( $key_grip,  $result[ 'key_grip' ] );
			if( $position === false )
			{
				$key_grip = $key_grip . ", " . $result[ 'key_grip' ];
			} //end if
		} //end else
	} //end for

	echo "<h3>ASSISTANT DIRECTOR</h3>\n";
	echo "<p>$ad</p>\n";
	echo "<h3>CAMERA OPERATORS</h3>\n";
	echo "<p>$cam_ops</p>\n";
	echo "<h3>DIRECTOR OF PHOTOGRAPHY</h3>\n";
	echo "<p>$dop</p>\n";
	echo "<h3>SCRIPTY</h3>\n";
	echo "<p>$scripty</p>\n";
	echo "<h3>AUDIO</h3>\n";
	echo "<p>$audio</p>\n";
	echo "<h3>GAFFER</h3>\n";
	echo "<p>$gaffer</p>\n";
	echo "<h3>KEY GRIP</h3>\n";
	echo "<p>$key_grip</p>\n";

	//return to home page
	echo "<p>---------------------------------------------------------------</p>\n";
	echo "<a href='page.php?page=home'>Return</a> to the home page.";

	//end page div
	echo "</div>\n";
?> 













