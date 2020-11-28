<!-- 
Titled "index.php" so that it'll be displayed as default as soon as the folder is opened
Basically the login/register page
-->
<!DOCTYPE html>
<html>
<head>
<title>Greetings, Traveler</title>
<link rel= "stylesheet" type = "text/css" href = "custom.css">
<link rel = "icon" href="cursor.gif">
<style>
/*Want the background of this page to be different than other pages*/
.indexbody{
	background-image: url("https://data.whicdn.com/images/321306780/original.gif");
	background-size:100%;
	height: 100vh;
	margin-top:-20px;
}
</style>
<script>
//javascript to toggle whether the password is visible or not
function togPW() {
  var lpw = document.getElementById("lpw");
  var pw = document.getElementById("pass_word");
  var cpw = document.getElementById("conpass");
  if (lpw.type === "password" || pw.type === "password" || cpw.type === "password") {
    lpw.type = "text";
    pw.type = "text";
    cpw.type = "text";
  } else if(lpw.type === "text" || pw.type === "text" || cpw.type === "password") {
    lpw.type = "password";
    pw.type = "password";
    cpw.type = "password";
  }
}
</script>
</head>

<body>
<br>
<div class = indexbody>
<center>
<?php include('sqlheader.php');?>
<!--login and register divs-->
<br>
<div class = "login">
	<h1>LOGIN</h1>
	<br>
	<form action = "logincheck.php" method = 'post'>
	Username: <input type = "text" name = "username">
	<br><br>
	Password: <input type="password" name = "password" id="lpw" required>
	<input type="checkbox" onclick="togPW()">Show Password
	<br>
	<br>
	<input type = "submit"></input>
	<input type = "reset"></input>
	</form>
	<br>
</div>

<div class = "register">
	<h1>REGISTER</h1>
	<br><br>
	<form action = "regcheck.php" method = 'post'>
		<!-- could do type="email" instead of pattern, but that would vary acceptable outputs depending on browser-->
	E-Mail: <input pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" name = "email" required>
	<br>
	<br>
	<!--Enter ID: <input type = 'text' name = 'ID'>-->
	Username: <input type = "text" name = "user_name" required><br>
	Password: <input type="password" name = "pass_word" id="pass_word" required>
	Confirm Password: <input type="password" name = "conpass" id="conpass" required>
	<input type="checkbox" onclick="togPW()">Show Password
	<br><br>
	First Name: <input type = "text" name = "firstname" required>
	Last Name: <input type = "text" name = "lastname" required>
	<br><br>
	Birth Date:<input type = "date" name = "birthdate" required>
	<label>Security Question:
	<?php
	$msql = mysqli_query($conn, "SELECT sq_id, sq_text FROM security_questions");
	echo "<input list ='wenti' name = 'wenti' required></label>
			<datalist id = 'wenti'>";
	while($mrow = mysqli_fetch_assoc($msql)){
		echo "<option value='".$mrow['sq_text']."'>".$mrow['sq_text']."</option>";
	}
	echo "</datalist>";
	?>
	<br>
	<label>Answer:</label><input type = 'text' name = 'daan' required>
	<br><br>
	<input type = "submit"></input>
	<input type = "reset"></input>
	</form>
</div>
</div>
</body>
</html>
