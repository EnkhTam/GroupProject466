<!DOCTYPE html>
<html>
<body>
<?php
include('config.php');
setcookie('username', $_POST['user_name']);
setcookie('pass', $_POST['pass_word']);
$user = $_POST['user_name'];
$pass = $_POST['pass_word'];
$email = $_POST['email'];
$conpass = $_POST['conpass'];
$birth = $_POST['birthdate'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
mysqli_select_db($conn, 'fitness');
	$checkuser = mysqli_query($conn, "SELECT * from user where user_name = '$user' ");
	$checkemail = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email'");
	if (mysqli_num_rows($checkuser) == true){
		//gives an error if the name already exists
		$message = "Username already exists";
		echo "<p>$message</p><br>
		<a href = 'index.php'>Go Back</a>";
	}
	elseif(mysqli_num_rows($checkemail) == true){
		//gives an error message if email already registered
		$message = "This e-mail has already been registered";
		echo "<p>$message</p><br>
		<a href = 'index.php'>Go Back</a>";
	}
	else{
		if($pass == $conpass){
			//if it all matches the account is created
			mysqli_query($conn, "INSERT INTO user(birth_date, first_name, last_name, user_name, pass_word, email) 
			VALUES ('$birth', '$firstname', '$lastname', '$user', '$pass','$email')");
			//setcookie('type', $usertype);
			setcookie('firstname',$firstname);
			header("Location:main.php");
		}
		else{
			//sends an error message if the confirmation password doesnt work
			$message = "Did not enter correct confirmation password";
			echo "<p>$message</p><br>
			<a href = 'index.php'>Go Back</a>";
		}
	}
?>
</body>
</html>
