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
										JOIN measurement_unit ON weightlogs.unit_id = measurement_unit.unit_id
										WHERE user_name = '$username'
										AND weight_date = (SELECT MAX(weight_date)
															FROM weightlogs 
															JOIN user ON weightlogs.user_id = user.user_id
															WHERE user_name = '$username') GROUP BY weight_date");
		while($cwrow = mysqli_fetch_array($cweight))
		{
			echo "<br>Current Weight: ".$cwrow['weight']." ".$cwrow['unit_name']."s";
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

<div class = "addf">
	<form action = "" method = "post">
	<h1>Update Weight</h1>
	Weight: <input type = "number" name = "Wweight" required> 
	<?php
	$msql = mysqli_query($conn, "SELECT unit_id, unit_sym FROM measurement_unit");
	echo "<select name ='Munit'>";
	while($mrow = mysqli_fetch_assoc($msql)){
		echo "<option value='".$mrow['unit_id']."'>".$mrow['unit_sym']."</option>";
	}
	echo "</select>";
	?>
	<br><br>
	Date: <input type = "date" name = "Wdate" required>
	<br>
	<input type="submit" name = "submit" value="submit">
	<input type = "reset" value = "reset">
	<br>
	</form>
</div>
</body>
</html>
