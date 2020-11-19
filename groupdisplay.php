<!DOCTYPE html>
<!--Eden Maxey and Aurora Harkleroad 5/23/18-->
<html>
<head>
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
	<li><a href='groupFIRSTPAGE.php'>Log Out</a></li>
</ul>";
//if($user_type == 'student'){
$result = mysqli_query($conn, "SELECT * from user where user_name = '$username'");
//a while loop is set up to create a display
while($row = mysqli_fetch_array($result))
  {
  echo "<br /><div class= 'info'>";
  echo "First Name: ".$row['first_name'] . " <br>  Last Name: " . $row['last_name']. "  <br>  Birth Date: " . $row['birth_date']. "   <br>User ID: " . $row['user_no'];
  echo "</div><br />";
  }
//}
/*else if($user_type == 'staff'){
$result = mysqli_query($conn, "SELECT * from staff where user_name = '$username'");
//a while loop is set up to create a display
while($row = mysqli_fetch_array($result))
  {
  echo "<br /><div class = 'info'>";
  echo "First Name: ".$row['first_name'] . " <br>  Last Name: " . $row['last_name']. " <br>  Birth Date: " . $row['birth_date']. "   <br>Staff ID: " . $row['staff_no'];
  echo "</div><br />";
  }*/
//}
?>
</body>
</html>
