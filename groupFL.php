<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script src ="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="DATAheadercss.css" media="screen">
  <link rel="stylesheet" href="DATAbodycss.css" media="screen">
  
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
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
include_once ('config.php');

$usql = "SELECT user_no FROM user WHERE user_name = '$username'";
$uresult = mysqli_query($conn,$usql);
$uno = mysqli_fetch_assoc($uresult);

$prep = mysqli_prepare($conn, "INSERT INTO food (user_no, eaten_date, food_name, calories, protein, carbs)
			VALUES (?,?,?,?,?,?)");
mysqli_stmt_bind_param($prep, "idsiii", $uno['user_no'], $Featen_date, $Ffood_name, $Fcalories, $Fprotein, $Fcarbs);
$Ffood_name = ($_POST["Ffood_name"]);
$Featen_date = ($_POST["Featen_date"]);
$Fcalories = ($_POST["Fcalories"]);
$Fcarbs = ($_POST["Fcarbs"]);
$Fprotein = ($_POST["Fprotein"]);
mysqli_stmt_execute($prep);
mysqli_stmt_close($prep);
mysqli_select_db($conn,'fitness');
$sql = "SELECT * FROM food JOIN user ON food.user_no = user.user_no WHERE user_name = '$username'";
$result = mysqli_query($conn,$sql);?>

<script>
$(document).ready(function() {
	$('#food').DataTable( {
		/*"processing": true,
		"serverside": true,*/
	} );
} );
</script>
</head>
<body>
<?php
echo "<center><h1>Food Log</h1></br>";
echo '<p id="date_filter">
    <span id="date-label-from" class="date-label">From: </span><input class="date_range_filter date" type="text" id="datepicker_from" />
    <span id="date-label-to" class="date-label">To:<input class="date_range_filter date" type="text" id="datepicker_to" />
</p>'
$table = "<table id = 'food'>";
$table .= "<thead><tr><th>Date Eaten</th><th>Name</th><th>Calories</th><th>Protein</th><th>Carbs</th></tr></thead><tbody>";
while($row = mysqli_fetch_array($result))
{
	$table .= "<tr> <td>".$row['eaten_date']."</td> <td>".$row['food_name']."</td> <td>".$row['calories']."</td><td>".$row['protein']."</td><td>".$row['carbs']."</td> </tr>";

}
$table .= "</tbody></table>";
echo $table;
?>
</body>
</html>