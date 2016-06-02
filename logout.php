<!-- Michael Koboldt, William D Minard, Daniel J Hart, Kenny Clark, Matthew Pokoik 
		QuickScript
		4-30-15 -->

<?php

session_start();
	
// Log this logout
include "exec.php";
log_data($_SESSION['username'], get_ip($_SESSION['username']), "Logout " );
	
session_destroy();
redirect("page.php?page=login");

?>