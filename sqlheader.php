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
$sql = 'CREATE DATABASE z1865285';
if (mysqli_select_db($conn, 'z1865285')){
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
mysqli_select_db($conn,'z1865285');
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

mysqli_query($conn, "CREATE TABLE security_questions(
	sq_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	sq_text VARCHAR(350) NOT NULL
)");
mysqli_query($conn, "INSERT INTO security_questions(sq_id, sq_text)
			VALUES('1', 'What is your favorite pokemon?')");

mysqli_query($conn, "CREATE TABLE sq_user(
	squ_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_id INT(11) UNSIGNED NOT NULL,
	sq_id INT(11) UNSIGNED NOT NULL,
	answer VARCHAR(350) NOT NULL,
	FOREIGN KEY (user_id) REFERENCES user (user_id),
	FOREIGN KEY (sq_id) REFERENCES security_questions (sq_id)
)");
mysqli_query($conn, "INSERT INTO sq_user(squ_id, user_id, sq_id, answer)
			VALUES ('1','1','1','1')");

mysqli_query($conn, "CREATE TABLE pw_history(
	pwh_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_id INT(11) UNSIGNED NOT NULL,
	oldpw VARCHAR(16) NOT NULL,
	FOREIGN KEY (user_id) REFERENCES user (user_id)
)");
mysqli_query($conn, "INSERT INTO pw_history(pwh_id, user_id, oldpw)
			VALUES ('1','1','1')");

mysqli_query($conn, "CREATE TABLE settings(
	uset_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_id INT(11) UNSIGNED NOT NULL,
	wset_id INT(11) UNSIGNED NOT NULL DEFAULT 28,
	mset_id INT(11) UNSIGNED NOT NULL DEFAULT 33,
	FOREIGN KEY (user_id) REFERENCES user (user_id),
	FOREIGN KEY (wset_id) REFERENCES measurement_unit (unit_id),
	FOREIGN KEY (mset_id) REFERENCES measurement_unit (unit_id)
)");
mysqli_query($conn, "INSERT INTO settings(user_id, wset_id, mset_id)
			VALUES ('1','28','33')");

/*mysqli_query($conn, "CREATE TABLE nutrition(
	nut_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	nut_name VARCHAR(16) NOT NULL
	)");
mysqli_query($conn, "INSERT INTO nutrition()
			VALUES ('1','calories')");
mysqli_query($conn, "INSERT INTO nutrition()
			VALUES ('2','protein')");
mysqli_query($conn, "INSERT INTO nutrition()
			VALUES ('3','carbs')");
mysqli_query($conn, "INSERT INTO nutrition()
			VALUES ('4','fiber')");*/

mysqli_query($conn,"CREATE TABLE food(
	food_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	food_name VARCHAR(14) NOT NULL,
	serving_size INT NOT NULL,
	serving_unit_id INT UNSIGNED NOT NULL,
	calories INT,
	protein INT,
	carbs INT,
	fiber INT,
	FOREIGN KEY (serving_unit_id) REFERENCES measurement_unit (unit_id)
	)");
mysqli_query($conn, "INSERT INTO food (food_id, food_name, serving_size, serving_unit_id, calories, protein, carbs, fiber)
		VALUES ('1', 'testfood', '0', '30', '0', '0', '0','0')");

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

/*mysqli_query($conn, "CREATE TABLE nutlogs(
	nlog_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	flog_id INT(11) UNSIGNED,
	food_id INT(11) UNSIGNED,
	nut_id INT(11) UNSIGNED,
	unit_id INT(11) UNSIGNED,
	FOREIGN KEY (flog_id) REFERENCES foodlogs (flog_id),
	FOREIGN KEY (food_id) REFERENCES food (food_id),
	FOREIGN KEY (nut_id) REFERENCES nutrition (nut_id),
	FOREIGN KEY (unit_id) REFERENCES measurement_unit (unit_id)
)");
mysqli_query($conn, "INSERT INTO nutlogs (nlog_id, flog_id, food_id, nut_id, unit_id)
		VALUES ('1', '1', '1', '1','1')");*/

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
	hours INT,
	minutes INT,
	FOREIGN KEY (user_id) REFERENCES user (user_id)
)");
mysqli_query($conn, "INSERT INTO workoutlogs (wolog_id, user_id, workout_name, workout_date, calories_burned)
			VALUES ('1','1','idk','2010-01-01','1','1','1')");

?>
</head>
</html>
