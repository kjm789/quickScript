<!-- Michael Koboldt, William D Minard, Daniel J Hart, Kenny Clark, Matthew Pokoik
        Quick Script 
        4-30-15 -->
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    <title>QuickScript</title>
    <link rel="stylesheet" href="css/app.css"/>
    <link rel="shortcut icon" href="img/favicon.png" />
</head>
<body>

<nav class="navbar navbar-app" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-menu-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">
                <img class="navbar-logo" src="img/quickscript.png" alt="HumblePixel">
            </a>
        </div>
        <div class="collapse navbar-collapse" id="top-menu-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="page.php?page=home">Projects</a></li>
                <li><a href="logout.php"> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>


<!--Dashboard Side-->
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
      <ul class="nav nav-sidebar">
        
        <li class="active"><script type="text/javascript">
            var d = new Date()
            var time = d.getHours()
            if (time<12)
            {
            document.write("<a href=\"#\">Good Morning!</a>")
            }
            else if (time>=12 && time<17)
            {
            document.write("<a href=\"#\">Good Afternoon!</a>")
            }
            else
            {
            document.write("<a href=\"#\">Good Evening!</a>")
            }
            </script><span class="sr-only">(current)</span></a></li>

        <li><a href="page.php?page=add&table=project">Add a Project</a></li>
        <hr>
        <li><p>&nbsp;&nbsp;&nbsp; View all:</p></li>
        <li><a href="page.php?page=home">Projects</a></li>
        <!-- call view_all here -->
        <?php include "exec.php";
        view_all($_SESSION['username'], "episode", "Episodes");
        view_all($_SESSION['username'], "shooting_day", "Shooting Days");
        view_all($_SESSION['username'], "scene", "Scenes");
        view_all($_SESSION['username'], "take", "Takes");
        ?>
        <hr>
        <li><a href="page.php?page=upload">Image Upload</a></li>
        <li><a href="page.php?page=display">View Images</a></li>
      </ul>
    </div>
    <!--Start of PHP content-->
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <?php
    $page = $_GET['page'];
    require($page.'.php');
    echo "<input type=\"hidden\" name=\"page\" value=\"".$page."\">";
    ?>
