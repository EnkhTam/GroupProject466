<!--file to go at the top of almost every page so this stuff doesn'target
	need to be copy-pasted a bunch-->
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type = "text/css" href = "custom.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<link rel = "stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel = "stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/plug-ins/1.10.21/api/sum().js"></script>
<link rel= "stylesheet" type = "text/css" href = "custom.css">
<link rel = "icon" href="cursor.gif">

<script>

</script>

</head>

<body>
<?php
//the purpose of this page is an addition so that the user can see if their information is right
include('config.php');
//uses cookies for the user that secretly never expire
$user = $_COOKIE['firstname'];
$username = $_COOKIE['username'];
setcookie('firstname', $user);
mysqli_select_db($conn, "z1865285");
include('sqlheader.php');

echo'
<div class = "top">
<br><br><br><br><br>
		<div class = "welcome"><h2> Welcome, '.$user.'</h2></div>
		<center>
		<!--img src ="question.gif"-->
		<center>
</div>
<!--//displays the link bar-->
<div class = "nbar">
<nav class="navbar navbar-inverse">
	  <div class="container-fluid">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>                        
		  </button>
		</div>
		
		<div class="collapse navbar-collapse" id="myNavbar">
		  
		  <ul class="nav navbar-nav">
			<li><a href="userinfo.php">Your Info</a></li>
			<li><a href="food.php">Food Log</a></li>
			<li><a href="workouts.php">Workout Log</a></li>
			<li><a href="index.php">Log Out</a></li>
		  </ul>
		  
		  <!-- useless but fun things lol-->
		  <ul class="nav navbar-nav navbar-right">
			<li><a href="http://cachemonet.com"><span class="glyphicon glyphicon-heart-empty"></span></a></li>
			<li><a href="http://beesbeesbees.com"><span class="glyphicon glyphicon-fire"></span></a></li>
			<li><a href="http://eelslap.com"><span class="glyphicon glyphicon-send"></span></a></li>
		  </ul>
		</div>
	  </div>
	</nav>	
	</div>

</body>
</html>';
