<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<!--  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  
    <script type="application/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src ="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">-->

<link rel = "stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel = "stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>

<link rel= "stylesheet" type = "text/css" href = "custom.css">
</head>
<body>
<?php
//the purpose of this page is an addition so that the user can see if their information is right
include('config.php');
//uses cookies for the user
$user = $_COOKIE['firstname'];
//$user_type = $_COOKIE['type'];
echo "<h1>Welcome $user</h1>";
mysqli_select_db($conn, "fitness");
$username = $_COOKIE['username'];
//displays the link bar
echo "<ul>
	<li><a href='groupdisplay.php'>Check Your Info</a></li>
	<li><a href='groupfooddisplay.php'>Check Your Food</a></li>
	<li><a href='groupFIRSTPAGE.php'>Log Out</a></li>
</ul>";

?>
</body>
</html>

<html>
<head>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<?php
include ('config.php');
$sql = 'CREATE DATABASE fitness';
if (mysqli_select_db($conn, 'fitness')){
	//echo "database exists";
}
else{
	//this code is for when the database doesn't exist
	if (mysqli_query($conn,$sql)){
		echo "Database Created Successfully";
	}
	else{
		echo "Error creating database: ".mysqli_error($conn);
	}
}
mysqli_select_db($conn,'fitness');
//creates the tables for the database
mysqli_query($conn,"CREATE TABLE food(
fentry_no INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_no INT(11) UNSIGNED,
eaten_date DATE,
food_name VARCHAR(14),
calories INT,
protein INT,
carbs INT,
FOREIGN KEY (user_no) REFERENCES user (user_no)
)");

mysqli_query($conn, $sql);
mysqli_query($conn, "INSERT INTO food (fentry_no, user_no, eaten_date, food_name, calories, protein, carbs)
		VALUES ('1', '1', '2000-03-15', 'testfood', '0', '0', '0')");
mysqli_select_db($conn,'fitness');
$sql = "SELECT * FROM food JOIN user ON food.user_no = user.user_no WHERE user_name = '$username'";
$result = mysqli_query($conn,$sql);?>
<script>
/*$(document).ready(function() {
//    $('#table1').DataTable( {
       // "processing": true,
        //"serverSide": true,
        
//    } );
//} );
});*/
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
	<div class = "body">
	<?php
	echo 'Logs between: <input name="min" id="min" type="text"> and <input name="max" id="max" type="text">';
	$table = "<table id = 'table1'>";
	$table.="<thead><tr><th>Date Eaten</th><th>Name</th><th>Calories</th><th>Protein</th><th>Carbs</th></tr></thead><tbody>";
	while($row = mysqli_fetch_assoc($result))
	{
		$table .= "<tr> <td>".$row['eaten_date']."</td> <td>".$row['food_name']."</td> <td>".$row['calories']."</td><td>".$row['protein']."</td><td>".$row['carbs']."</td> </tr>";
	}
	$table .= "</tbody></table>";
	echo $table;
	?>
	</div>
	<form action = "groupFL.php" method = "post">
	<h1>Add Food Log</h1>
	Food Name: <input type = "text" name = "Ffood_name" required>
	<br>
	Date Eaten: <input type = "date" name = "Featen_date" required>
	<br>
	Calories: <input type = "number" name = "Fcalories" required>
	<br>
	Protein: <input type = "number" name = "Fprotein" required>
	<br>
	Carbs: <input type = "number" name = "Fcarbs" required>
	<br>
	<input type="submit" value="Submit">
	<input type = "reset" value = "Reset">
	<br>
	<br>
	<!--<a href = "466ass8DBS.php">View Suppliers</a>-->
</form>

</body>
</html>
