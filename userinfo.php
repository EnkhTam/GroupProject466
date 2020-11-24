<!DOCTYPE html>
<html>
<head>
<link rel= "stylesheet" type = "text/css" href = "custom.css">
</head>
<body>

<?php
//the purpose of this page is an addition so that the user can see if their information is right
include('header.php');
$result = mysqli_query($conn, "SELECT * FROM user WHERE user_name = '$username'");
//a while loop is set up to create a display
while($row = mysqli_fetch_array($result))
  {
  echo "<br /><div class= 'info'>";
  echo "First Name: ".$row['first_name']." 
		<br>Last Name: ".$row['last_name']."
		<br>E-mail: ".$row['email']."
		<br>Birth Date: ". $row['birth_date']."
		<br>User ID: ".$row['user_id'];
		$cweight = mysqli_query($conn, "SELECT * FROM weightlogs 
										JOIN user ON weightlogs.user_id = user.user_id 
										WHERE user_name = '$username'
										AND weight_date = (SELECT MAX(weight_date)
															FROM weightlogs 
															JOIN user ON weightlogs.user_id = user.user_id
															WHERE user_name = '$username')");
		while($cwrow = mysqli_fetch_array($cweight))
		{
			echo "<br>Current Weight: ".$cwrow['weight'];
		}
		echo "</div><br />";
  }

?>

<div class = "addf">
	<form action = "" method = "post">
	<h1>Update Weight</h1>
	Weight: <input type = "number" name = "Wweight" required>
	<br>
	Date: <input type = "date" name = "Wdate" required>
	<br>
	<input type="submit" name = "submit" value="submit">
	<input type = "reset" value = "reset">
	<br>
	</form>
</div>

	<?php
	if(isset($_POST['submit'])){
		$usql = "SELECT user_id FROM user WHERE user_name = '$username'";
		$uresult = mysqli_query($conn,$usql);
		$uno = mysqli_fetch_assoc($uresult);

		$prep = mysqli_prepare($conn, "INSERT INTO weightlogs (user_id, weight, weight_date)
					VALUES (?,?,?)");
		mysqli_stmt_bind_param($prep, "iid", $uno['user_id'], $Wweight, $Wdate);
		$Wweight = ($_POST["Wweight"]);
		$Wdate = ($_POST["Wdate"]);
		//$Wdate = date("Y-m-d", strtotime($_POST['Wdate']));
		//FIGURE OUT WHY DATE WON'T INSERT CORRECTLY
		mysqli_stmt_execute($prep);
		mysqli_stmt_close($prep);
	}
	?>

</body>
</html>
