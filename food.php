<!DOCTYPE html>
<html>
<head>
<?php include('header.php'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
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

       
            $("#min").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });
            $("#max").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });
            var table = $('#table1').DataTable();

            // Event listener to the two range filtering inputs to redraw on input
            $('#min, #max').change(function () {
                table.draw();
            });
        });

</script>
</head>
<body>
<?php 
$sql = "SELECT * FROM foodlogs 
		JOIN food ON foodlogs.food_id = food.food_id
		JOIN user ON foodlogs.user_id = user.user_id  
		WHERE user_name = '$username'";
$result = mysqli_query($conn,$sql);
?>
	<div class = "body">
	<h2>Your Food Logs</h2>
	<?php
	echo 'Logs between: <input name="min" id="min" type="text"> and <input name="max" id="max" type="text">';
	$table = "<table id = 'table1'>";
	$table.="<thead><tr><th>Date Eaten</th><th>Name</th><th>Servings</th><th>Calories</th><th>Protein</th><th>Carbs</th></tr></thead><tbody>";
	while($row = mysqli_fetch_assoc($result))
	{
		//show nutrition info for amount consumed, not for one serving size
		$cals = $row['calories'] * $row['servings'];
		$prot = $row['protein'] * $row['servings'];
		$carbs = $row['carbs'] * $row['servings'];
		$table .= "<tr> <td>".$row['eaten_date']."</td> <td>".$row['food_name']."</td><td>".$row['servings']."</td> <td>".$cals."</td><td>".$prot."</td><td>".$carbs."</td> </tr>";
	}
	$table .= "</tbody></table>";
	echo $table;
	?>
	</div>

	<div class = "addflog">
	<form action = "" method = "post">
	<h2>Add Food Log</h2>
	Food Name: 
	<?php
	$fsql = mysqli_query($conn, "SELECT food_id, food_name FROM food");
	echo "<select name ='Ffood_id'>";
	while($frow = mysqli_fetch_assoc($fsql)){
		echo "<option value='".$frow['food_id']."'>".$frow['food_name']."</option>";
	}
	echo "</select>";
	?>
	<br>
	Date Eaten: <input type = "date" name = "Featen_date" required>
	<br>
	Servings Eaten: <input type = "number" name = "Fservings" required>
	<br>
	<input type="submit" name = "submitL" value="submit">
	<input type = "reset" value = "reset">
	</form>
	</div>

	<div class = "addf">
	<form action = "" method = "post">
	<h2>Add New Food</h2>
	Food Name: <input type = "text" name = "Dfood_name" required>
	<br>
	Calories: <input type = "number" name = "Dcalories" required>
	<br>
	Protein: <input type = "number" name = "Dprotein" required>
	<br>
	Carbs: <input type = "number" name = "Dcarbs" required>
	<br>
	Serving Size: <input type = "number" name = "Dserving_size" required>
	<br>
	<input type="submit" name = "submitF" value="submit">
	<input type = "reset" value = "reset">
	<br>
	</form>
	</div>

	<?php
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
		header("Location:food.php");
	}

	if(isset($_POST['submitF'])){
		$Dfood_name = ($_POST["Dfood_name"]);
		$checkfood = mysqli_query($conn, "SELECT * from food where food_name = '$Dfood_name' ");
		if(mysqli_num_rows($checkfood) == true){
			$message = "Food name already exists";
			echo "<p>$message</p><br>";
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
//			mysqli_stmt_execute($prep);
//			mysqli_stmt_close($prep);
			mysqli_query($conn, "INSERT INTO food (food_name, serving_size, calories, protein, carbs)
						VALUES ('$Dfood_name','$Dserving_size','$Dcalories','$Dprotein','$Dcarbs')");
			header("Location:food.php");
		}
	}
	?>
</form>
</body>
</html>
