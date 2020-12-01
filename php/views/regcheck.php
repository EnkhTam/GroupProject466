<!DOCTYPE html>
<html>
<head>
<title>You donked up</title>
<link rel= "stylesheet" type = "text/css" href = "../../css/base/custom.css">
<link rel = "icon" href="../../media/img/base/cursor.gif">

<style>
    h2, h3, h4 {
        color: darkslategrey;
        font-weight: bold;
    }
	.regadd{
    background-color:lightpink;
	background-image: url("https://i.pinimg.com/originals/da/00/25/da0025614f7a00c6b7f0788d1b596bc4.gif");
	}
</style>
<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
<script type='text/javascript'>
//javascript to toggle whether the password is visible or not
function togPW() {
  var pw = document.getElementById("newpass");
  var opw = document.getElementById("oldpass");
  if (pw.type === "password") {
    pw.type = "text";
  } else {
    pw.type = "password";
  }
  if(opw.type === "password"){
		opw.type = "text";
  }
  else{
		opw.type = "password";
  }
}
/*function displayForm(x){
	if(x.value == "1"){
		jQuery('#otherInfoD').style.display='inline';
		jQuery('#secqueD').style.visibility='none';
	}
	if(x.value == "2"){
		jQuery('#secqueD').style.display='inline';
		jQuery('#otherInfoD').style.display='none';
	}
}*/
function displayForm(x){
	if(x.value == "1"){
		jQuery('#otherInfoD').toggle('show');
		jQuery('#secqueD').hide();
		jQuery('#newpwD').show();
	}
	if(x.value == "2"){
		jQuery('#secqueD').toggle('show');
		jQuery('#otherInfoD').hide();
		jQuery('#newpwD').show();
	}
}
</script>
</head>
<body>
<?php
include('../includes/config.php');
setcookie('email', $_POST['email']);
$email = $_POST['email'];
mysqli_select_db($conn, 'z1865285');

/*require("/home/data/www/z1865285/public_html/bug/php/includes/PHPMailer/src/PHPMailer.php");
require("/home/data/www/z1865285/public_html/bug/php/includes/PHPMailer/src/SMTP.php");
require("/home/data/www/z1865285/public_html/bug/php/includes/PHPMailer/src/Exception.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;*/

//require 'vendor/autoload.php';

//send user an email to reset their password
if(isset($_POST['cpsubmit'])){
	$neem = ($_POST['neem']);
	$usern = ($_POST['usern']);
	$birth = ($_POST['birthdate']);
	$oldpass = ($_POST['oldpass']);
	$newpass = ($_POST['newpass']);
	$seq = ($_POST['seq']);
	$sqans = ($_POST['sqans']);
	if(empty($newpass)){
		$message = "You didn't enter a new password";
		echo "<script>alert(\"$message\");</script>";
		$help = true;
	}
	else{
		//if other info answered and other form blank
		if((!empty($usern) || !empty($birth) || !empty($oldpass)) && empty($sqans)){
			if(empty($usern) || empty($birth) || empty($oldpass)){
				$message = "Fill out all fields";
				echo "<script>alert(\"$message\");</script>";
				$help = true;
			}
			else{
				$verifysql = "SELECT * FROM user WHERE email = '$neem'";
				$verifyres = mysqli_query($conn, $verifysql);
				$verifyrow = mysqli_fetch_array($verifyres);
				$vpwres = mysqli_query($conn, "SELECT oldpw FROM pw_history WHERE user_id = '".$verifyrow['user_id']."'");
				$vwarr = [];
				while($vwrow = mysqli_fetch_assoc($vpwres))
				{
					array_push($vwarr, $vwrow['oldpw']);
				}
				//if any of the info doesn't match
				if($verifyrow['user_name'] != $usern || $verifyrow['birth_date'] != $birth || !in_array($oldpass,$vwarr))
				{
					$message = "Information doesn't match our database";
					echo "<script>alert(\"$message\");</script>";
					//echo "<script>document.getElementById('otherInfoD').reset()</script>";
					//echo "<script>document.getElementById('secqueD').reset()</script>";
					$help = true;
				}
				else{
					//if the new password is an old one
					if(in_array($newpass,$vwarr))
					{
						mysqli_query($conn, "UPDATE user SET pass_word = '$newpass' WHERE email = '$neem'");
						mysqli_query($conn, "INSERT INTO pw_history (user_id, oldpw)
											VALUES('".$verifyrow['user_id']."', '$newpass')");
						$message = "Password previously used but updated anyway";
						echo "<script>
								alert(\"$message\");
								window.location.replace('../../index.php');
							</script>";
					}
					else{
						mysqli_query($conn, "UPDATE user SET pass_word = '$newpass' WHERE email = '$neem'");
						mysqli_query($conn, "INSERT INTO pw_history (user_id, oldpw)
											VALUES('".$verifyrow['user_id']."', '$newpass')");
						header("Location:../../index.php");
						
					}
				}
			}
		}
		//if security question answered and other form blank
		elseif(!empty($sqans) && empty($usern) && empty($birth) && empty($oldpass)){
			$versqres = mysqli_query($conn, "SELECT * FROM user
									LEFT OUTER JOIN sq_user ON user.user_id = sq_user.user_id
									LEFT OUTER JOIN security_questions ON security_questions.sq_id = sq_user.sq_id
									WHERE email = '$neem'");
			$versqrow = mysqli_fetch_array($versqres);
			if($sqans != $versqrow['answer']){//if the answer is wrong
				$message = "Information doesn't match our database";
				echo "<script>alert(\"$message\");</script>";
				$help = true;
				//echo "<script>document.getElementById('otherInfoD').reset()</script>";
				//echo "<script>document.getElementById('secqueD').reset()</script>";
			}
			else{//if the answer is right
				mysqli_query($conn, "UPDATE user SET pass_word = '$newpass' WHERE email = '$neem'");
				mysqli_query($conn, "INSERT INTO pw_history (user_id, oldpw)
				VALUES('".$versqrow['user_id']."', '$newpass')");
				header("Location:../../index.php");
			}
		}
		//if neither form filled
		elseif(empty($sqans) && empty($usern) && empty($birth) && empty($oldpass)){
			$message = "Fill out all fields";
			echo "<script>alert(\"$message\");</script>";
			$help = true;
		}
		else{//if fields in both are filled
			$message = "Error: Please try again";
			echo "<script>alert(\"$message\");</script>";
			$help = true;
			//echo "<script>document.getElementById('otherInfoD').reset();</script>";
			//echo "<script>document.getElementById('secqueD').reset();</script>";

		}
	}
}

if((isset($_POST['pwr']) || $help = true) && !isset($_POST['user_name']))
{
	//$newpass = $_POST['newpass'];
	$pwrres = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email'");
	$pwrrow = mysqli_fetch_array($pwrres);
	$cpuser = $pwrrow['user_id'];
	if($pwrrow == true){
		//$pwup = mysqli_query($conn,"UPDATE user SET pass_word='$newpass' WHERE email='$email'");
		$link = "<a href='http://students.cs.niu.edu/~z1865285/bug/php/views/regcheck.php?key='".$email."'>Click To Reset Password</a>";
		$subject = "Password Reset";
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: throwawayforprojects@gmail.com" . "\r\n";;
		$body = '
		<html>
		<head></head>
		<body>
		<h2><a href = '.$link.'>Click here to reset password</a></h2>;
		</body>
		</html>';

		$sent = mail($email,$subject,$body,$headers);

		if(!$sent){
			include('../includes/resetpw.php');

		}
		else{
			echo "<h3>Check your email for the password reset</h3>
				<br><a href = '../../index.php'>Back to Login</a>";
		}
		//require_once('phpmail/PHPMailerAutoload.php');
		/*$mail = new PHPMailer();
		$mail->CharSet =  "utf-8";
		$mail->SMTPDebug = SMTP::DEBUG_SERVER;
		$mail->IsSMTP();
		// enable SMTP authentication
		$mail->SMTPAuth = true;              
		$mail->Username = "throwawayforprojects@gmail.com";
		$mail->Password = "z1865285";
		$mail->SMTPSecure = "ssl";  
		// sets gmail as the SMTP server
		//$mail->Host = "smtp.gmail.com";
		$mail->Host = "74.125.195.108";
		//$mail->Host = "108.177.112.109";
		// set the SMTP port for the gmail server
		$mail->Port = "587";
		$mail->setFrom('throwawayforprojects@gmail.com', 'Z1865285');
		$mail->AddAddress($pwrrow['email'], $pwrrow['first_name']." ".$pwrrow['last_name']);
		$mail->Subject  =  'Password Reset';
		$mail->IsHTML(true);
		$mail->Body = 'Click Here to Reset Password '.$link.'';
		if($mail->send())
		{
		  echo "Check your email";
		}
		else
		{
		  echo "Mail Error: ".$mail->ErrorInfo;
		}*/
	}
	else{
		echo "<h2>Invalid Email Address</h2>
			<br><a href = '../../index.php'>Go Back</a>";
	}
}
elseif(isset($_POST['user_name'])){
	$help = false;
	$user = $_POST['user_name'];
	$passw = $_POST['pass_word'];
	$conpass = $_POST['conpass'];
	$birth = $_POST['birthdate'];
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$wenti = $_POST['wenti'];
	$daan = $_POST['daan'];

mysqli_select_db($conn, 'z1865285');
	$checkuser = mysqli_query($conn, "SELECT * FROM user WHERE user_name = '$user'");
	$checkemail = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email'");
	$chuser = mysqli_fetch_array($checkuser);
	$chemail = mysqli_fetch_array($checkemail);
	//if username in db
	if (mysqli_num_rows($checkuser) == true){
		//automatically logs user in if they already have an account
		if($chuser['pass_word'] == $passw){
			setcookie('username', $_POST['user_name']);
			setcookie('pass', $_POST['pass_word']);
			setcookie('firstname', $chuser['first_name']);
			header("Location:../../php/views/main.php");
		}
		else{
			//gives an error if the name already exists
			$message = "lol sorry, someone else already took that username";
			echo "<h2>$message</h2><br>
			<h3><a href = '../../index.php'>Go Back</a><h3>
			";
		}
	}
	//if email in db
	elseif(mysqli_num_rows($checkemail) == true){
		//automatically logs user in if they already have an account with the same password
		if($chemail['pass_word'] == $passw){
			setcookie('username', $chemail['user_name']);
			setcookie('pass', $_POST['pass_word']);
			setcookie('firstname', $chemail['first_name']);
			header("Location:../../php/views/main.php");
		}
		else{
			//gives an error message if email already registered
			$message = "This email already has an account";
			echo "<h2>$message</h2><br>
			<h3><a href = '../../index.php'>Go Back</a><h3><br>";
			//abandoned stuff bc we can't actually send ppl emails but I'll leave it for fun'
			if(isset($_REQUEST['key'])){
				$email = $_GET['key'];
				if(mysqli_num_rows($checkemail) == true){
					echo "<form id = 'rform' action = '' method = 'post'>
					<input type = 'hidden' name = 'email' value = '$email'>
					New Password: <input type = 'password' name = 'newpass' id = 'newpass'>
					<br>
					<input type='checkbox' onclick='togPW()'>Show Password
					<br>
					Confirm Password: <input type = 'password' name = 'conpass' id = 'conpass'>
					<br><br>
					<input type = 'submit' name = 'newp'></input>
					</form>
					";
				}
			}
			else{
				echo "<form id = 'rform' action = '' method = 'post'>
					<input type = 'hidden' name = 'email' value = '$email'>
					<input type = 'submit' name = 'pwr' value = 'CHANGE PASSWORD'></input>
				</form>
				";
			}

			if(isset($_POST['newpass'])){
				$email = $_POST['email'];
				$newpass = $_POST['newpass'];
				if(mysqli_num_rows($checkemail) == true){
					mysqli_query($conn, "UPDATE users set pass_word='$newpass' WHERE email='$email'");
				}
				else{
					echo "Big screw up here oops";
				}
			}
		}
	}
	else{
		if($passw == $conpass){
			//if it all matches the account is created
			mysqli_query($conn, "INSERT INTO user(birth_date, first_name, last_name, user_name, pass_word, email, mset_id, wset_id) 
			VALUES ('$birth', '$firstname', '$lastname', '$user', '$passw','$email',28,33)");

			$uidres = mysqli_query($conn, "SELECT * FROM user WHERE user_name = '$user'");
			$uidrow = mysqli_fetch_array($uidres);

			//default settings
			/*mysqli_query($conn, "INSERT INTO (user_id, wset_id, mset_id)
						VALUES('".$uidrow["user_id"]."',33,28)");*/
			//password history entry
			mysqli_query($conn, "INSERT INTO pw_history(user_id, oldpw)
						VALUES('".$uidrow["user_id"]."', '$passw')");
			//security question entry
			$wentitxtres = mysqli_query($conn, "SELECT * FROM security_questions WHERE sq_text = '$wenti'");
			$wentirow = mysqli_fetch_array($wentitxtres);
			/*$wentiarr = [];
			while($wentirow = mysqli_fetch_array($wentitxtres)){
				array_push($wentiarr, $wentirow['sq_txt']);
			}
			if(!in_array($wenti, $wentiarr)){
				mysqli_query($conn, "INSERT INTO security_questions(sq_txt)
							VALUES('$wenti')");
			}*/
			if($wentirow == NULL){
				mysqli_query($conn, "INSERT INTO security_questions(sq_text)
							VALUES('$wenti')");
			}
			$wentitxtres = mysqli_query($conn, "SELECT * FROM security_questions WHERE sq_text = '$wenti'");
			$wentirow = mysqli_fetch_array($wentitxtres);
			mysqli_query($conn, "INSERT INTO sq_user(user_id,sq_id,answer)
						VALUES('".$uidrow["user_id"]."','".$wentirow["sq_id"]."','$daan')");
			//setcookie('type', $usertype);
			setcookie('username', $user);
			setcookie('pass', $passw);
			setcookie('firstname',$firstname);
			header("Location:../../php/views/main.php");
		}
		else{
			//sends an error message if the confirmation password doesnt work
			$message = "Nah sorry, ur passwords didn't match";
			echo "<p>$message</p><br>
			<h3><a href = '../../index.php'>Go Back</a><h3>";
		}
	}
}
?>
</body>
</html>
