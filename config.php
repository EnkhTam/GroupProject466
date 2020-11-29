<?php
/* configuration for my personal phpmyadmin, pls ignore
$databaseHost = 'localhost';
$databaseUsername = 'root';
$databasePassword = '';

$conn = mysqli_connect($databaseHost, $databaseUsername, $databasePassword);

if(!$conn){
	die("connection failed: " . mysqli_connect_error());
}

echo "Connected successfully";//just to test, keep commented out on final version
/**/
?>

<?php
//I *should* be more careful abt this tbh but w/e, 
$databaseHost = 'courses';
$databaseUsername = 'z1865285';
$databasePassword = '2001Aug24';
$databaseName = 'z1865285';
$conn = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);
//$conn = mysqli_connect($databaseHost, $databaseUsername, $databasePassword);

if(!$conn){
        die("There's been a connection oopsie: " . mysqli_connect_error());
}
else{
	//echo "Connected successfully";
}
?>
