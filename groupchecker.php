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
//checks first if it is a student account
//$checkuser = mysqli_query($conn, "SELECT * from student where user_name = '$user' ");
$checkuser = mysqli_query($conn, "SELECT * FROM user WHERE user_name = '$user' ");
//number of rows function, if produces one row then name is there
//password and confirm password on registration, check if those match
if (mysqli_num_rows($checkuser) == 1){
	if($row = mysqli_fetch_array($checkuser)){
		if ($row['pass_word'] == $pass){
			//$user_type = 'student';
			$namee = $row['first_name'];
			//setcookie('type', $user_type);
			setcookie('firstname',$namee);
			//sets up the cookies for the next few pages
			header("Location:main.php");
		}
		else{
			//error message is released
			$message = "Incorrect Password";
			echo "<p>Incorrect password</p><br>
			<a href = 'groupFIRSTPAGE.php'>Go Back</a>";
		}
	}
}
else{
	//then it checks if it is a staff account
	//$checkuser = mysqli_query($conn, "SELECT * from staff where user_name = '$user'");
	//if(mysqli_num_rows($checkuser) == 1){
	//	while($row = mysqli_fetch_array($checkuser)){
	//		if ($row['pass_word'] == $pass){
	//			$user_type = 'staff';
	//			$namee = $row['first_name'];
	//			setcookie('type', $user_type);
	//			setcookie('firstname',$namee);
				//sets up the cookies for the next pages
	//			header("Location:staffmain.php");
	//		}
	//		else{
	//			//gives an error if the password is wrong
	//			$message = "Incorrect Password";
	//			echo "<p>$message</p><br>
	//			<a href = 'homepage.php'>Go Back</a>";
	//		}
	//	}
	//}
	//else{
		//gives an error if the user cannot be found
		$message = "User does not exist, please register if you have not.";
		echo "<p>$message</p><br>
		<a href = 'groupFIRSTPAGE.php'>Go Back</a>";
//	}
}


?>
</body>
</html>
