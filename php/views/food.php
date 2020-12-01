<!DOCTYPE html>
<html>
<head>
<title>Chomp Chomp</title>
<?php include('../includes/header.php'); ?>
<link rel= "stylesheet" type = "text/css" href = "../../css/base/custom.css">
<link rel = "icon" href="../../media/img/base/cursor.gif">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel= "stylesheet" type = "text/css" href = "../../css/base/custom.css">
<script>
$(document).ready(function(){
	$.fn.dataTable.ext.search.push(
		function (settings, data, dataIndex) {
		var min = $('#min').datepicker("getDate");
		var max = $('#max').datepicker("getDate");
		var startDate = new Date(data[0]);
		if (min == null && max == null) { return true; }
		if (min == null && startDate <= max) { return true;}
		if(max == null && startDate >= min) {return true;}
		if (startDate <= max && startDate >= min) { return true; }
		return false;
        }
	);
       
	$("#min").datepicker({ 
		onSelect: function () { 
			table.draw(); 
		}, 
		changeMonth: true, changeYear: true 
	});
	$("#max").datepicker({ 
		onSelect: function () { 
			table.draw(); 
		}, 
		changeMonth: true, changeYear: true 
	});
	var table = $('#table1').DataTable();

	// Event listener to the two range filtering inputs to redraw on input
	$('#min, #max').change(function () {
		table.draw();
		});
	});

</script>
</head>
<body>
<center>
<?php 
//Senses when user submits a food log and does the following
if(isset($_POST['submitL'])){
		$usql = "SELECT user_id FROM user WHERE user_name = '$username'";
		$uresult = mysqli_query($conn,$usql);
		$uno = mysqli_fetch_assoc($uresult);
		$use = $uno['user_id'];

		//when using prepare, date doesn't insert correctly
/*		$prep = mysqli_prepare($conn, "INSERT INTO foodlogs (user_id, food_id, eaten_date, servings)
					VALUES (?,?,?,?)");
		mysqli_stmt_bind_param($prep, "iidi", $uno['user_id'], $Ffood_id, $Featen_date, $Fservings);
*/
		$Ffood_id = ($_POST["Ffood_id"]);
		$Featen_date = ($_POST["Featen_date"]);
		$Fservings = ($_POST["Fservings"]);
//		mysqli_stmt_execute($prep);
//		mysqli_stmt_close($prep);
		mysqli_query($conn, "INSERT INTO foodlogs (user_id, food_id, eaten_date, servings)
					VALUES ('$use','$Ffood_id','$Featen_date','$Fservings')");
		header("Location:food.php");//refresh the page so the info appears on the table
}

//Senses when user submits a food to the database and does the following
	if(isset($_POST['submitF'])){
		$Dfood_name = ($_POST["Dfood_name"]);
		$checkfood = mysqli_query($conn, "SELECT * from food where food_name = '$Dfood_name' ");
		if(mysqli_num_rows($checkfood) == true){
			$message = "Food name already exists";
			echo "<script> alert('$message')</script>";
		}
		else{
/*			$prep = mysqli_prepare($conn, "INSERT INTO food (food_name, serving_size, calories, protein, carbs)
						VALUES (?,?,?,?,?)");
			mysqli_stmt_bind_param($prep, "siiii", $Dfood_name, $Dserving_size, $Dcalories, $Dprotein, $Dcarbs);
*/			
			$Dfood_name = ($_POST["Dfood_name"]);
			$Dserving_size = ($_POST["Dserving_size"]);
			$Dcalories = ($_POST["Dcalories"]);
			$Dprotein = ($_POST["Dprotein"]);
			$Dcarbs = ($_POST["Dcarbs"]);
			$DFiber = ($_POST["DFiber"]);

			$Munit = ($_POST["Munit"]);
			//$Munit = ($_POST["M1unit"]);
			$M2unit = ($_POST["M2unit"]);
			$M3unit = ($_POST["M3unit"]);
			$M4unit = ($_POST["M4unit"]);

			//converting user-chosen units into mg
			if($M2unit != 28){
				$M2res = mysqli_query($conn, "SELECT * FROM measurement_conv
										WHERE from_unit_id = '$M2unit' AND to_unit_id = '28'");
				$M2row = mysqli_fetch_assoc($M2res);
				$M2divi = $M2row['divi'];
				$M2mult = $M2row['mult'];

			}
			else{
				$M2divi = 1;
				$M2mult = 1;
			}

			if($M3unit != 28){
				$M3res = mysqli_query($conn, "SELECT * FROM measurement_conv
										WHERE from_unit_id = '$M3unit' AND to_unit_id = '28'");
				$M3row = mysqli_fetch_assoc($M3res);
				$M3divi = $M3row['divi'];
				$M3mult = $M3row['mult'];

			}
			else{
				$M3divi = 1;
				$M3mult = 1;
			}

			if($M4unit != 28){
				$M4res = mysqli_query($conn, "SELECT * FROM measurement_conv
										WHERE from_unit_id = '$M4unit' AND to_unit_id = '28'");
				$M4row = mysqli_fetch_assoc($M4res);
				$M4divi = $M4row['divi'];
				$M4mult = $M4row['mult'];

			}
			else{
				$M4divi = 1;
				$M4mult = 1;
			}

			$Dprotein = ($Dprotein * $M2mult)/$M2divi;
			$Dcarbs = ($Dcarbs * $M3mult)/$M3divi;
			$DFiber = ($DFiber * $M4mult)/$M4divi;
//			mysqli_stmt_execute($prep);
//			mysqli_stmt_close($prep);
			mysqli_query($conn, "INSERT INTO food (food_name, serving_size, serving_unit_id, calories, protein, carbs, fiber)
						VALUES ('$Dfood_name','$Dserving_size', '$Munit','$Dcalories','$Dprotein','$Dcarbs','$DFiber')");
			header("Location:food.php");//refresh the page so that the food is now an option
										//in the food log form
		}
	}

$sql = "SELECT * FROM foodlogs 
		JOIN food ON foodlogs.food_id = food.food_id
		JOIN user ON foodlogs.user_id = user.user_id
		WHERE user_name = '$username'";
$result = mysqli_query($conn,$sql);
?>
	<div class = "body">
	<div class = "log">
	<h2>Your Food Logs</h2>
	<?php
	/*$sett = mysqli_query($conn, "SELECT * FROM settings
									JOIN user ON settings.user_id = user.user_id
									WHERE user_name = '$username'");*/
	$sett = mysqli_query($conn, "SELECT * FROM user WHERE user_name = '$username'");
	$srow = mysqli_fetch_array($sett);
	/*$sdsett = mysqli_query($conn, "SELECT * FROM measurement_unit
									JOIN settings ON measurement_unit.unit_id = settings.mset_id
									JOIN user ON settings.user_id = '".$srow['user_id']."'");*/
	$sdsett = mysqli_query($conn, "SELECT * FROM measurement_unit
								JOIN user ON measurement_unit.unit_id = user.mset_id
								WHERE user_name = '$username'");
	$sdrow = mysqli_fetch_array($sdsett);
	echo '<label>Logs between: </label><input name="min" id="min" type="text"><label> and </label><input name="max" id="max" type="text">';
	$table = "<table id = 'table1'>";
	$table.="<thead><tr><th>Date Eaten</th><th>Name</th><th>Servings</th><th>Calories</th><th>Protein(".$sdrow['unit_sym'].")</th><th>Carbs(".$sdrow['unit_sym'].")</th><th>Fiber(".$sdrow['unit_sym'].")</th></tr></thead><tbody>";
	while($row = mysqli_fetch_assoc($result))
	{
		//show nutrition info for amount consumed, not for one serving size
		$cals = $row['calories'] * $row['servings'];
		$prot = $row['protein'] * $row['servings'];
		$carbs = $row['carbs'] * $row['servings'];
		$fiber = $row['fiber'] * $row['servings'];
		if($srow['mset_id'] != 28)
			{
				$displayres = mysqli_query($conn, "SELECT * FROM measurement_conv
											WHERE from_unit_id = 28 AND to_unit_id = '".$srow['mset_id']."'");
				$displayrow = mysqli_fetch_array($displayres);
				$prot = ($prot*$displayrow['mult'])/$displayrow['divi'];
				$carbs = ($carbs*$displayrow['mult'])/$displayrow['divi'];
				$fiber = ($fiber*$displayrow['mult'])/$displayrow['divi'];
				echo "<h2>".$srow['user_id']."</h2>";
			}
		else if($srow['mset_id'] == 28){
		}
		$table .= "<tr> <td>".$row['eaten_date']."</td> <td>".$row['food_name']."</td><td>".$row['servings']."</td> <td>".$cals."</td><td>".$prot."</td><td>".$carbs."</td><td>".$fiber."</td> </tr>";
	}
	$table .= "</tbody></table>";
	echo $table;
	echo "</div></div>";
	?>
<div class = "row">
	<div class ="col-lg-6">
	<div class = "add">
		<form action = "" method = "post">
		<h2>Add Food Log</h2>
		<label>Food Name: </label>
		<?php
		$fsql = mysqli_query($conn, "SELECT food_id, food_name FROM food WHERE food_id > 1");
		echo "<select name ='Ffood_id'>";
		while($frow = mysqli_fetch_assoc($fsql)){
			echo "<option value='".$frow['food_id']."'>".$frow['food_name']."</option>";
		}
		echo "</select>";
		?>
		<br>
		<label>Date Eaten: </label><input type = "date" name = "Featen_date" required>
		<br>
		<label>Servings Eaten: </label><input type = "number" name = "Fservings" required>
		<br>
		<input type="submit" name = "submitL" value="submit">
		<input type = "reset" value = "reset">
		</form>
	</div>
	</div>

	<div class ="col-lg-6">
	<div class = "add">
		<form action = "" method = "post">
		<h2>Add New Food</h2>
		<label>Food Name: </label><input type = "text" name = "Dfood_name" required>
		<br>
		<label>Calories: </label><input type = "number" name = "Dcalories" required>
		<!--?php/*
		$msql = mysqli_query($conn, "SELECT unit_id, unit_sym FROM measurement_unit");
		echo "<select name ='M1unit'>";
		while($mrow = mysqli_fetch_assoc($msql)){
			echo "<option value='".$mrow['unit_id']."'>".$mrow['unit_sym']."</option>";
		}
		echo "</select>";*/
		?>-->
		<br>
		<label>Protein: </label><input type = "number" name = "Dprotein">
		<?php
			$msql = mysqli_query($conn, "SELECT unit_id, unit_sym FROM measurement_unit");
			echo "<select name ='M2unit'>";
			while($mrow = mysqli_fetch_assoc($msql)){
				echo "<option value='".$mrow['unit_id']."'>".$mrow['unit_sym']."</option>";
			}
			echo "</select>";
		?>
		<br>
		<label>Carbs: </label><input type = "number" name = "Dcarbs">
		<?php
			$msql = mysqli_query($conn, "SELECT unit_id, unit_sym FROM measurement_unit");
			echo "<select name ='M3unit'>";
			while($mrow = mysqli_fetch_assoc($msql)){
				echo "<option value='".$mrow['unit_id']."'>".$mrow['unit_sym']."</option>";
			}
			echo "</select>";
		?>
		<br>
		<label>Fiber: </label><input type = "number" name = "DFiber">
		<?php
			$msql = mysqli_query($conn, "SELECT unit_id, unit_sym FROM measurement_unit");
			echo "<select name ='M4unit'>";
			while($mrow = mysqli_fetch_assoc($msql)){
				echo "<option value='".$mrow['unit_id']."'>".$mrow['unit_sym']."</option>";
			}
			echo "</select>";
		?>
		<br>
		<label>Serving Size: </label><input type = "number" name = "Dserving_size" required>
		<?php
			$msql = mysqli_query($conn, "SELECT unit_id, unit_sym FROM measurement_unit");
			echo "<select name ='Munit'>";
			while($mrow = mysqli_fetch_assoc($msql)){
				echo "<option value='".$mrow['unit_id']."'>".$mrow['unit_sym']."</option>";
			}
			echo "</select>";
		?>
		<br>
		<input type="submit" name = "submitF" value="submit">
		<input type = "reset" value = "reset">
		<br>
		</form>
	</div>
	</div>
</div>
</body>
</html>
