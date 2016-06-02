<!-- Michael Koboldt, William D Minard, Daniel J Hart, Kenny Clark, Matthew Pokoik
		QuickScript
		04-30-15 -->
<!-- Start PHP -->
<?php
	//require exec.php for db connection and php functionality
	require( 'exec.php' );

	//begin user session
	session_start( );

	//if user isn't logged in 
	if( $_SESSION[ 'username' ] == NULL )
	{
		//redirect to login page
		redirect( "Location: page.php?page=index" );
	} //end if

	//page div to align content to center of page
	echo "<div align=\"center\">";

	//grab all get variables out of url
	$page = $_GET[ 'page' ];
	$table = $_GET[ 'table' ];
	$id = $_GET[ 'id' ];
	$add_id = $_GET[ 'add_id' ];
	$option = $_GET[ 'option' ];
	$all_table = $_GET[ 'all_table' ];

	//if table does not exist, error out
	if( $table == NULL )
	{
		redirect( 'location: error.php?error_id=2' );
	} //end if
	else if( $id == NULL )
	{
		redirect( 'error.php?error_id=3' );
	} //end else if

	//if table is episode, print out form for it 
	if( $_GET[ 'table' ] == episode )
	{
		echo "<h1>Episodes<br></h1>";
		echo "\n<form method=\"GET\" action=\"page.php\">";
		echo "\n<input type=\"hidden\" name=\"table\" value=\"episode\">";
		echo "\n<input type=\"hidden\" name=\"page\" value=\"add\">";
		echo "\n<input type=\"hidden\" name=\"project_id\" value=\"".$id."\">";
		echo "\n<input type=\"submit\" name=\"add\" value=\"+\">";
		echo "\n</form>";
		echo "<BR>";
		find_episodes( $id );
		echo "\n<BR><input type=\"button\" value=\"Back\" <a href=\"#\" onclick=\"window.location.href='page.php?page=home'\"></a>";
	}
	else if( $_GET['table'] == shooting_day )
	{
		echo "<h1>Shooting Days<br></h1>";
		echo "\n<form method=\"GET\" action=\"page.php\">";
		echo "\n<input type=\"hidden\" name=\"page\" value=\"add\">";
		echo "\n<input type=\"hidden\" name=\"table\" value=\"shooting_day\">";
		echo "\n<input type=\"hidden\" name=\"episode_id\" value=\"".$id."\">";
		echo "\n<input type=\"submit\" name=\"add\" value=\"+\">";
		echo "\n</form>";
		echo "<BR>";
		find_sds($id);
		echo "\n<BR><input type=\"button\" value=\"Back\" <a href=\"#\" onclick=\"history.back();\"></a>";
	}
	else if( $_GET['table'] == scene )
	{
		if($option==NULL)
		{
			echo "<h1>Scenes<br></h1>";
			echo "\n<form method=\"GET\" action=\"page.php\">";
			echo "\n<input type=\"hidden\" name=\"page\" value=\"add\">";
			echo "\n<input type=\"hidden\" name=\"table\" value=\"scene\">";
			echo "\n<input type=\"hidden\" name=\"sd_id\" value=\"".$id."\">";
			echo "\n<input type=\"submit\" name=\"add\" value=\"+\">";
			echo "\n</form>";
			echo "<BR>";
			find_scenes($id);
		}
		else
		{
			echo "<h1>All Scenes<br></h1>";
			echo "<BR>";
			view_scenes_from_ep($option);
		}
		echo "\n<BR><input type=\"button\" value=\"Back\" <a href=\"#\" onclick=\"history.back();\"></a>";
	}
	else if( $_GET['table'] == take )
	{
		echo "<h1>Takes<br></h1>";
		echo "\n<form method=\"GET\" action=\"page.php\">";
		echo "\n<input type=\"hidden\" name=\"page\" value=\"add\">";
		echo "\n<input type=\"hidden\" name=\"table\" value=\"take\">";
		echo "\n<input type=\"hidden\" name=\"scene_id\" value=\"".$id."\">";
		echo "\n<input type=\"submit\" name=\"add\" value=\"+\">";
		echo "<BR>";
		echo "<BR>";
		take_autoi_data($id);
		echo "\n</form>";
		find_takes($id);
		echo "\n<BR><input type=\"button\" value=\"Back\" <a href=\"#\" onclick=\"history.back();\"></a>";
	}
	else if($_GET['table'] == all)
	{
		echo "<h1>All ".$all_table."s for ".$_SESSION['username']."<br></h1>";
		echo "<BR>";
		echo "<BR>";
		find_all($_SESSION['username'], $all_table);
		echo "\n<BR><input type=\"button\" value=\"Back\" <a href=\"#\" onclick=\"history.back();\"></a>";
	}
	else {
		header('Location: error.php?error_id=2');
	}
?>