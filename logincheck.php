<!DOCTYPE html>
<html>
<body>
<?php
include('config.php');
setcookie('username', $_POST['username']);
setcookie('pass', $_POST['password']);
$user = $_POST['username'];
$pass = $_POST['password'];
mysqli_select_db($conn, 'fitness');
//check if user in database
$checkuser = mysqli_query($conn, "SELECT * FROM user WHERE user_name = '$user' ");
//number of rows function, if produces one row then name is there
//password and confirm password on registration, check if those match
if (mysqli_num_rows($checkuser) == true){
	if($row = mysqli_fetch_array($checkuser)){
		if ($row['pass_word'] == $pass){
			$namee = $row['first_name'];
			setcookie('firstname',$namee);
			//sets up the cookies for the next few pages
			header("Location:main.php");
		}
		else{
			//error message is released
			$message = "Incorrect Password";
			echo "<p>Incorrect password</p><br>
			<a href = 'index.php'>Go Back</a>";
		}
	}
}
else{
	//gives an error if the user cannot be found
	$message = "User does not exist, please register if you have not.";
	echo "<p>$message</p><br>
	<a href = 'index.php'>Go Back</a>";
}


?>
</body>
</html>