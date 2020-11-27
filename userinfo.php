<!DOCTYPE html>
<html>
<head>
<title>All about you</title>
<link rel= "stylesheet" type = "text/css" href = "custom.css">
</head>
<body>
<?php include('header.php'); ?>
<center>
<?php
//the purpose of this page is an addition so that the user can see if their information is right

echo'<div class = "row">
<div class = "col-md-6">';

$result = mysqli_query($conn, "SELECT * FROM user WHERE user_name = '$username'");
//a while loop is set up to create a display
while($row = mysqli_fetch_array($result))
  {
  echo "<div class= 'info'><h2>Your Information:</h2>";
  echo "<h4>First Name: ".$row['first_name']."</h4> 
		<h4>Last Name: ".$row['last_name']."</h4>
		<h4>E-mail: ".$row['email']."</h4>
		<h4>Birth Date: ". $row['birth_date']."</h4>
		<h4>User ID: ".$row['user_id']."</h4>";
		$cweight = mysqli_query($conn, "SELECT * FROM weightlogs 
										JOIN user ON weightlogs.user_id = user.user_id
										JOIN measurement_unit ON weightlogs.unit_id = measurement_unit.unit_id
										WHERE user_name = '$username'
										AND weight_date = (SELECT MAX(weight_date)
															FROM weightlogs 
															JOIN user ON weightlogs.user_id = user.user_id
															WHERE user_name = '$username') GROUP BY weight_date");
		while($cwrow = mysqli_fetch_array($cweight))
		{
			echo "<h4>Current Weight: ".$cwrow['weight']." ".$cwrow['unit_name']."s</h4>";
		}
		echo "</div><br />";
  }

	if(isset($_POST['submit'])){
		$usql = "SELECT user_id FROM user WHERE user_name = '$username'";
		$uresult = mysqli_query($conn,$usql);
		$uno = mysqli_fetch_assoc($uresult);
		$use = $uno['user_id'];
		//when using prepare, date insert doesn't work
/*		$prep = mysqli_prepare($conn, "INSERT INTO weightlogs (user_id, weight, weight_date)
					VALUES (?,?,?)");
		mysqli_stmt_bind_param($prep, "iid", $uno['user_id'], $Wweight, $Wdate);
*/
		$Wweight = ($_POST["Wweight"]);
		$Wdate = ($_POST["Wdate"]);
		$Munit = ($_POST["Munit"]);
//		mysqli_stmt_execute($prep);
//		mysqli_stmt_close($prep);
		$checkdate = mysqli_query($conn, "SELECT * FROM weightlogs 
											JOIN user ON weightlogs.user_id = user.user_id
											WHERE user_name = '$username'
											AND weight_date = '$Wdate'");

		if (mysqli_num_rows($checkdate) == true){
			//if there's already a weight for that date, overwrite it
			mysqli_query($conn, "DELETE w FROM weightlogs w
								LEFT JOIN user u ON u.user_id = w.user_id
								WHERE u.user_name = '$username'
								AND w.weight_date = '$Wdate'");
			echo "test delete";
		}
		mysqli_query($conn, "INSERT INTO weightlogs (user_id, weight, weight_date, unit_id)
					VALUES ('$use','$Wweight','$Wdate','$Munit')");
		header("Location:userinfo.php");
	}
	?>
</div>
<div class = "col-md-6">
	<div class = "add">
		<form action = "" method = "post">
		<h2>Update Weight</h2>
		<label>Weight: </label><input type = "number" name = "Wweight" required> 
		<?php
		$msql = mysqli_query($conn, "SELECT unit_id, unit_sym FROM measurement_unit");
		echo "<select name ='Munit'>";
		while($mrow = mysqli_fetch_assoc($msql)){
			echo "<option value='".$mrow['unit_id']."'>".$mrow['unit_sym']."</option>";
		}
		echo "</select>";
		?>
		<br><br>
		<label>Date: </label><input type = "date" name = "Wdate" required>
		<br>
		<input type="submit" name = "submit" value="submit">
		<input type = "reset" value = "reset">
		<br>
		</form>
	</div>
</div>
</div>
</body>
</html>
