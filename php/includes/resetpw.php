<?php
			echo'
			<script type="text/javascript">
			//javascript to toggle whether the password is visible or not
			function togPW() {
			  var pw = document.getElementById("newpass");
			  var opw = document.getElementById("oldpass");
			  if (pw.type === "password") {
				pw.type = "text";
				cpw.type = "text";
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
			</script>
			';
			$sqsql = "SELECT * FROM user
						JOIN sq_user USING (user_id)
						JOIN security_questions USING (sq_id)
						WHERE user_id = '$cpuser'";
			$sqres = mysqli_query($conn, $sqsql);
			$sqrow = mysqli_fetch_array($sqres);
			echo "<h3>(This doesn't actually work like I originally wanted by sending an email 
			and is more of a proof-of-concept type thing. The code's all here though, I think.
			Instead, we'll just try to verify with the other info.)</h3><br>
			<div class = 'regadd'>
			<form action = '' method = 'post'>
				<input value = '1' type = 'radio' name = 'selectf' onClick = 'displayForm(this)'></input>Enter other account info<br>
				<div style='display:none;' id='otherInfoD'>
				<!--form action='regcheck.php' method = 'post' id = 'otherinfo'-->
					<label>Username: </label><input type = 'text' name = 'usern'><br>
					<label>Birthday: </label><input type = 'date' name = 'birthdate'><br>
					<label>Last remembered password: </label>
					<input type = 'password' name = 'oldpass' id = 'oldpass'><br>
				<!--/form-->
				</div>
				<br>
				<input value = '2' type = 'radio' name = 'selectf' onClick = 'displayForm(this)'></input>Answer security question<br>
				<div style='display:none;' id='secqueD'>
				<!--form action='' method = 'post' id = 'secque'-->
					<label>Your security question: <label><b>".$sqrow['sq_text']."</b><br>
					<label>Answer: </label><input type = 'text' name = 'sqans'>
					<input type = 'hidden' name = seq value = '".$sqrow['squ_id']."'></input>
				<!--/form-->
				</div>
				<br>
				<div style='display:none;' id='newpwD'>
				<label>New password: </label>
					<input type = 'password' name = 'newpass' id = 'newpass'><br>";
				echo'<input type="checkbox" onclick="togPW()">Show Password';
				echo"
				</div>
				<input type = 'hidden' name = 'neem' value = '$email'></input>
				<input type = 'hidden' name = 'email' value = '$email'>
				<input type = 'submit' name = 'cpsubmit' value = 'Verify Info'>
			</form>
			<br><a href = 'index.php'>Back to Login</a>
			</div>";
?>
