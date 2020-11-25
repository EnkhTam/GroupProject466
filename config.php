<?php
/*$databaseHost = 'localhost';
$databaseUsername = 'root';
$databasePassword = '';

$conn = mysqli_connect($databaseHost, $databaseUsername, $databasePassword);

/*if(!$conn){
	die("connection failed: " . mysqli_connect_error());
}

echo "Connected successfully";*/

?>

<?php
$databaseHost = 'courses';
$databaseUsername = 'z1865285';
$databasePassword = '2001Aug24';
//$databaseName = 'z1865285';
//$conn = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);
$conn = mysqli_connect($databaseHost, $databaseUsername, $databasePassword);

if(!$conn){
        die("connection failed: " . mysqli_connect_error());
}

//echo "Connected successfully";
/*
$databaseHost = 'localhost';
$databaseUsername = 'root';
$databasePassword = '';

$conn = mysqli_connect($databaseHost, $databaseUsername, $databasePassword);

if(!$conn){
        die("connection failed: " . mysqli_connect_error());
}

echo "Connected successfully";
*/
?>
