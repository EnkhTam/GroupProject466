<!DOCTYPE html>
<html>
<head>
<link rel= "stylesheet" type = "text/css" href = "custom.css">
</head>
<body>
<!--this page is for student specific user account-->
<ul>
	<li><a href="groupdisplay.php">Check Your Info</a></li>
	<li><a href="groupFIRSTPAGE.php">Log Out</a></li>
</ul>

<?php
include('config.php');
$username = $_COOKIE['username'];
mysqli_select_db($conn, 'fitness');
$user = $_COOKIE['firstname'];
//This page just initiates the task bar and sets up the user specific page
setcookie('firstname', $user);
echo "<h1>Welcome $user</h1><br><br>
<img src = 'https://tp1.tupiankucdn.com/gif/s/2018/4253afaf6add450aa83300690a98b8d2.gif'>";
?>
</body>
</html>
