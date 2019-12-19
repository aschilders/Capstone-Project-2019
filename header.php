<?php
session_start();
$_SESSION['currentpage'] = "home";
?>
<!DOCTYPE html>
<html>
<center>
<head lang="en">
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="EZCheezy">
	<link type="text/css" href="styles/styles.css" rel="stylesheet">
    <title>EZ Cheezy</title>
</head>
<body>
<center>
<div id="page-container">
<div id="content-wrap">
<nav>
	<div class="logocontainer">
	<div id="mainlogo">
		<a href="home.php">
		<img src="images/mainlogo.png" alt="EZ Cheezy logo">
		</a>
	</div>
	<div id="mobilelogo">
		<a href="home.php">
		<img src="images/mobilelogo.png" alt="EZ Cheezy logo">
		</a>
	</div>
	</div>
	<div class="navcontainer">
	<div id="nav1">
    <a href="menu2.php">
		Menu
	</a>
	</div>
	<div id="nav2">
    <a href="menu.php">
		Order
	</a>
	</div>
	<div id="nav2">
    <a href="about.php">
		About
	</a>
	</div>
	<div id="nav2">
    <a href="contact.php">
		Contact
	</a>
	</div>
	<!--<div id="nav2">
    <a href="foodtruck.php">
		Food Truck
	</a>
	</div> -->
	<?php
	if(isset($_SESSION['customeremail']))
	{
    echo '<div id="nav2">
    <a href="updateuser.php">
      Update Information
    </a>
    </div>';
		echo '<div id="nav2">
		<a href="customersignout.php">
			Sign-Out
		</a>
		</div>';
	}
	else {
		echo '<div id="nav2">
		<a href="customersignin.php">
			Sign-In
		</a>
		</div>';
		echo '<div id="nav2">
		<a href="registeruser.php">
			Sign-Up
		</a>
		</div>';
	}
	?>
	</div>
</nav>
