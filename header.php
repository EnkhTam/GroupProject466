<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<link rel = "stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel = "stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

<link rel= "stylesheet" type = "text/css" href = "custom.css">
</head>
<body>
<?php

//the purpose of this page is an addition so that the user can see if their information is right
include('config.php');
//uses cookies for the user
$user = $_COOKIE['firstname'];
$username = $_COOKIE['username'];
setcookie('firstname', $user);
echo "<h1>Welcome, $user</h1>";
mysqli_select_db($conn, "fitness");
//displays the link bar
echo "<ul>
	<li><a href='userinfo.php'>Your Info</a></li>
	<li><a href='food.php'>Food Log</a></li>
	<li><a href='workouts.php'>Workout Log</a></li>
	<li><a href='index.php'>Log Out</a></li>
	</ul>";

include('sqlheader.php');

?>
</head>
</html>
