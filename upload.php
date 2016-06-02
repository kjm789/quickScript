<!-- Michael Koboldt, William D Minard, Daniel J Hart, Kenny Clark, Matthew Pokoik
		QuickScript
		4-30-15 -->
<?php
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
echo "<div align=\"center\">";
if(pg_num_rows($result) > 0){
	echo "Please Select a Project:<BR><BR>";
	echo "\n<form enctype=\"multipart/form-data\" method=\"POST\" action=\"page.php?page=upload\">";
	echo "<input type=\"hidden\" name=\"page\" value=\"upload\">";
	echo "<select name=\"project\">";
	while($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
		foreach($line as $display){
				echo "<option	value='". $display ."'>$display</option>";
		}
	}
			echo "</select>";
	echo "<BR>";
	echo "<BR>";
	pg_free_result($result);
	echo "<p>
		<label>Choose a image to upload:</label>
		<BR>
		<input type=\"file\" name=\"file\">
		</p>";
	echo "<p>
		<label>Name:</label>
		<BR>
		<input type=\"text\" name=\"nm\">
		</p>";
	echo "<p>
		<label>Description:</label>
		<BR>
		<textarea align=\"left\" name=\"desc\" rows=\"5\" cols=\"20\"></textarea>
		</p>";
	echo "<p>
		<input type=\"submit\" name=\"upload\" value=\"Upload\">
		</p>";
	echo "</div>";
	echo "</form>";
}
if(isset($_POST['upload']))
{
	if(getimagesize($_FILES['file']['tmp_name']) < 0)
	{
		echo "Please select an image";
	}
	else if($_POST['desc'] == null || $_POST['name']){
		echo "Please enter something for name and description";
	}
	else{
		$fileName = pg_escape_string(ltrim($_FILES['file']['name']));
		$descript = pg_escape_string(htmlspecialchars($_POST['desc']));
		$nm = pg_escape_string(htmlspecialchars($_POST['nm']));
		$projNm = pg_escape_string(htmlspecialchars($_POST['project']));	

		$extStr = "png,jpeg,jpg,gif";
		$allowedExt = explode(',', $extStr);
		$detectdType = substr($fileName, strrpos($fileName, '.') + 1);


		$maxSize = 400000; //400kb

		echo "<BR>";	
		if(!in_array($detectdType, $allowedExt)){
			echo "\nOnly ". $extStr ." files allowed to be uploaded! \n";
		}
		else if($_FILES['file']['size'] > $maxSize){
			echo $ext ."<BR>Only the file less than ". $maxSize ."mb is allowed to upload \n";
		}
		else{
			if($_FILES['file']['error'] > 0){
				echo "return code:" . $_FILES['file']['error'];
			}
			else{
				if(save_image($_SESSION['username'], $fileName, $_FILES['file']['size'], $_FILES['file']['type'], $descript, $nm, $projNm, $_FILES['file']['tmp_name']))
				{
					redirect("page.php?page=display");
				}
				else{
					echo "Error with save_image()";
				}
			}
		}
	}
}
?>
</body>
</html>
