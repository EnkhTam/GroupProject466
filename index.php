<!DOCTYPE html>
<html>
<head>
<title>Greetings, Traveler</title>
<link rel= "stylesheet" type = "text/css" href = "custom.css">
</head>

<body>
<center>
<?php include('sqlheader.php');?>

<div class = "login">
	<h1>LOGIN</h1>
	<br>
	<form action = "logincheck.php" method = 'post'>
	Username: <input type = "text" name = "username">
	<br><br>
	Password: <input type = "text" name = "password">
	<br><br>
	<input type = "submit">
	</form>
	<br>
</div>
<!--there are two divs for the login and the registration-->
<div class = "register">
	<h1>REGISTER</h1>
	<br><br>
	<form action = "regcheck.php" method = 'post'>
	E-Mail: <input type = "text" name = "email">
	<br>
	<br><br>
	<!--Enter ID: <input type = 'text' name = 'ID'>-->
	Username: <input type = "text" name = "user_name">
	Password: <input type = "text" name = "pass_word">
	<br><br>
	Confirm Password: <input type = 'text' name = "conpass">
	<br><br>
	First Name: <input type = "text" name = "firstname">
	Last Name: <input type = "text" name = "lastname">
	<br><br>
	Birth Date:<input type = "date" name = "birthdate">
	<br><br>
	<input type = "submit">
	<p name = 'result'></p>
	</form>
</div>
</body>
</html>
