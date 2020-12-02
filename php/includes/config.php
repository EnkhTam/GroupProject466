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

$opt = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
    ];
$ds = "mysql:host=$databaseHost;dbname=$databaseName";
try {
     $pdo = new \PDO($ds, $databaseUsername, $databasePassword, $opt);
} catch (PDOException $butt) {
     throw new PDOException($butt->getMessage(), (int)$butt->getCode());
}
/*try{
	$conn = new PDO("mysql:host=$databaseHost;dbname=$databaseName",$databaseUsername,$databasePassword);
}catch(PDOException $e){
	echo "There's been a connection oopsie: ".$e->getMessage();
}*/
?>
