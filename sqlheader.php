<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel= "stylesheet" type = "text/css" href = "custom.css">
</head>
<body>
<?php
//the purpose of this page is an addition so that the user can see if their information is right
include('config.php');
$sql = 'CREATE DATABASE fitness';
if (mysqli_select_db($conn, 'fitness')){
//	echo "database exists";
}
else{
	//this code is for when the database doesn't exist
	if (mysqli_query($conn,$sql)){
//		echo "Database Created Successfully";
	}
	else{
		echo "Error creating database: ".mysqli_error($conn);
	}
}
mysqli_select_db($conn,'fitness');
///creates the tables for the database

//Putting unit conversion sql in another file just bc there's so much
include('consql.php');

mysqli_query($conn,"CREATE TABLE user(
	user_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	birth_date DATE NOT NULL,
	first_name VARCHAR(14) NOT NULL,
	last_name VARCHAR(16) NOT NULL,
	user_name VARCHAR(14) NOT NULL,
	pass_word VARCHAR(16) NOT NULL,
	email VARCHAR(350) NOT NULL
	)");
mysqli_query($conn, "INSERT INTO user (user_id, birth_date, first_name, last_name, user_name, pass_word, email)
			VALUES ('1', '2000-01-01', 'test0', 'test0', 'test0','test0','test0')");

mysqli_query($conn,"CREATE TABLE food(
	food_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	food_name VARCHAR(14) NOT NULL,
	serving_size INT NOT NULL,
	serving_unit_id INT UNSIGNED NOT NULL,
	calories INT,
	protein INT,
	carbs INT,
	FOREIGN KEY (serving_unit_id) REFERENCES measurement_unit (unit_id)
	)");
mysqli_query($conn, "INSERT INTO food (food_id, food_name, serving_size, serving_unit_id, calories, protein, carbs)
		VALUES ('1', 'testfood', '0', '30', '0', '0', '0')");

mysqli_query($conn, "CREATE TABLE foodlogs(
	flog_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_id INT(11) UNSIGNED,
	food_id INT(11) UNSIGNED,
	eaten_date DATE NOT NULL,
	servings INT NOT NULL,
	FOREIGN KEY (user_id) REFERENCES user (user_id),
	FOREIGN KEY (food_id) REFERENCES food (food_id)
	)");
mysqli_query($conn, "INSERT INTO foodlogs (flog_id, user_id, food_id, eaten_date, servings)
		VALUES ('1', '1', '1', '2010-01-01','1')");

mysqli_query($conn, "CREATE TABLE weightlogs(
	weight_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_id INT(11) UNSIGNED NOT NULL,
	weight INT NOT NULL,
	weight_date DATE NOT NULL,
	unit_id INT UNSIGNED NOT NULL,
	FOREIGN KEY (user_id) REFERENCES user (user_id),
	FOREIGN KEY (unit_id) REFERENCES measurement_unit (unit_id)
)");
mysqli_query($conn, "INSERT INTO weightlogs (weight_id, user_id, weight, weight_date, unit_id)
		VALUES ('1','1', '1','2010-01-01','29')");

mysqli_query($conn, "CREATE TABLE workoutlogs(
	wolog_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_id INT(11) UNSIGNED,
	workout_name VARCHAR(30),
	workout_date DATE NOT NULL,
	calories_burned INT NOT NULL,
	FOREIGN KEY (user_id) REFERENCES user (user_id)
)");
mysqli_query($conn, "INSERT INTO workoutlogs (wolog_id, user_id, workout_name, workout_date, calories_burned)
			VALUES ('1','1','idk','2010-01-01','1')");

?>
</head>
</html>
