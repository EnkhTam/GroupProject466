<!DOCTYPE html>
<html>
<head>
<?php include('header.php'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<script>
     $(document).ready(function(){
		//LIMIT DATES
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
        var table = $('#table1').removeAttr('width').DataTable( {
			"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data;
 
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
 
				// Total over all pages
				total = api
					.column(2)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
 
				// Total over this page
				pageTotal = api
					.column(2, { page: 'current'})
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
 
				// Update footer
				$( api.column(1).footer() ).html(
					'Calories Burned: '+pageTotal +' calories ('+ total +' total)'
				);

				average = total / api.column().data().count();
				pageAverage = pageTotal / api.column(2, {page:'current'}).data().count();
				// Update footer
				$( api.column(2).footer() ).html(
					'Average Burned: '+pageAverage +' calories ('+ average +' total)'
				);
			}
		} );

        // Event listener to the two range filtering inputs to redraw on input
		$('#min, #max').change(function () {
			table.draw();
		});

	});
</script>
</head>
<body>
<?php
	if(isset($_POST['submit'])){
		$usql = "SELECT user_id FROM user WHERE user_name = '$username'";
		$uresult = mysqli_query($conn,$usql);
		$uno = mysqli_fetch_assoc($uresult);
		$use = $uno['user_id'];

		//when using prepare, date doesn't insert correctly
/*		$prep = mysqli_prepare($conn, "INSERT INTO workoutlogs (user_id, wolog_id, workout_name, workout_date, calories_burned)
					VALUES (?,?,?,?,?)");
		mysqli_stmt_bind_param($prep, "iisdi", $uno['user_id'], $WO_name, $WO_date, $WO_cals);
*/
		$WO_name = ($_POST["WO_name"]);
		$WO_date = ($_POST["WO_date"]);
		$WO_cals = ($_POST["WO_cals"]);
//		mysqli_stmt_execute($prep);
//		mysqli_stmt_close($prep);
		mysqli_query($conn, "INSERT INTO workoutlogs (user_id, workout_name, workout_date, calories_burned)
					VALUES ('$use','$WO_name','$WO_date','$WO_cals')");
		header("Location:workouts.php");
	}

$sql = "SELECT * FROM workoutlogs 
		JOIN user ON workoutlogs.user_id = user.user_id  
		WHERE user_name = '$username'";
$result = mysqli_query($conn,$sql);
?>
	<div class = "body">
	<h2>Your Food Logs</h2>
	<?php
	echo 'Logs between: <input name="min" id="min" type="text"> and <input name="max" id="max" type="text">';
	$table = "<table id = 'table1'>";
	$table.="<thead><tr><th>Date Done</th><th>Type</th><th>Calories Burned</th></tr></thead><tbody>";
	while($row = mysqli_fetch_assoc($result))
	{
		$table .= "<tr> <td>".$row['workout_date']."</td> <td>".$row['workout_name']."</td><td>".$row['calories_burned']."</td></tr>";
	}
	$table .= "</tbody>";
	$table .="<tfoot>
			<tr>
			<th style='text-align:right'></th>
			<th style='text-align:left'></th>
			<th style='text-align:left'></th>
			</tr>
			</tfoot></table>";
	echo $table;
?>
	</div>

	<div class = "addwlog">
	<form action = "" method = "post">
	<h2>Add Workout Log</h2>
	Workout Type: <input type = "text" name = "WO_name" required>
	<br>
	Date Done: <input type = "date" name = "WO_date" required>
	<br>
	Calories Burned: <input type = "number" name = "WO_cals" required>
	<br>
	<input type="submit" name = "submit" value="submit">
	<input type = "reset" value = "reset">
	</form>
	</div>
</form>
</body>
</html>
