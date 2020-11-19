<!DOCTYPE html>
<html>
<body>
<?php
include('config.php');
setcookie('username', $_POST['user_name']);
setcookie('pass', $_POST['pass_word']);
setcookie('type', $_POST['user_type']);
$user = $_POST['user_name'];
$pass = $_POST['pass_word'];
//all the variables are initialized
$conpass = $_POST['conpass'];
//$usertype = $_POST['user_type'];
//$ID = $_POST['ID'];
$birth = $_POST['birthdate'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
mysqli_select_db($conn, 'fitness');
//checks if student or staff
//if($usertype == 'student'){
	$checkuser = mysqli_query($conn, "SELECT * from user where user_name = '$user' ");
	if (mysqli_num_rows($checkuser) == 1){
		//gives an error if the name already exists
		$message = "Username already exists";
		echo "<p>$message</p><br>
		<a href = 'groupFIRSTPAGE.php'>Go Back</a>";
	}
	else{
		if($pass == $conpass){
			//if it all matches the account is created
			mysqli_query($conn, "INSERT INTO user(birth_date, first_name, last_name, user_name, pass_word) VALUES ('$birth', '$firstname', '$lastname', '$user', '$pass')");
			//setcookie('type', $usertype);
			setcookie('firstname',$firstname);
			header("Location:main.php");
		}
		else{
			//sends an error message if the confirmation password doesnt work
			$message = "Did not enter correct confirmation password";
			echo "<p>$message</p><br>
			<a href = 'groupFIRSTPAGE.php'>Go Back</a>";
		}
	}
//}
/*else if ($usertype = 'staff'){
	$checkuser = mysqli_query($conn, "SELECT * from staff where user_name = $user");
	if(mysqli_num_rows($checkuser) == 1){
		//sends an error message if the confirmation password doesnt work
		$message = "Username already exists";
		echo "<p>$message</p><br>
		<a href = 'groupFIRSTPAGE.php'>Go Back</a>";
	}
	else{
		if($pass == $conpass){
			//if the confirmation works, the account is created
			mysqli_query($conn, "INSERT INTO 	staff(staff_no, birth_date, first_name, last_name, user_name, pass_word) VALUES ('$ID', '$birth', '$firstname', '$lastname', '$user', '$pass')");
			setcookie('type', $usertype);
			setcookie('firstname',$firstname);
			header("Location:staffmain.php");
		}
		else{
			//Sends an error if it does not match
			$message = "Did not enter correct confirmation password";
			echo "<p>$message</p><br>
			<a href = 'groupFIRSTPAGE.php'>Go Back</a>";
		}
	}
}*/
?>
</body>
</html>
