<?php
include('config.php');

mysqli_select_db($conn,'z1865285');

mysqli_query($conn, "INSERT INTO user (user_id, birth_date, first_name, last_name, user_name, pass_word, email, wset_id, mset_id)
			VALUES ('1', '2000-01-01', 'test0', 'test0', 'test0','test0','test0', 33, 28)");

mysqli_query($conn, "INSERT INTO user (user_id, birth_date, first_name, last_name, user_name, pass_word, email, wset_id, mset_id)
			VALUES ('2', '2003-03-02', 'Knives', 'Chau', 'chau-down','iheartcad','tastemysteel@email.com', 33, 28)");
mysqli_query($conn, "INSERT INTO pw_history(pwh_id, user_id, oldpw)
			VALUES('2','2','iheartcad')");

mysqli_query($conn, "INSERT INTO user (user_id, birth_date, first_name, last_name, user_name, pass_word, email, wset_id, mset_id)
			VALUES ('3', '1996-09-06', 'Todd', 'Ingram', 've-gone','chickenisntvegan','tellthecleaninglady@monday.com', 33, 28)");
mysqli_query($conn, "INSERT INTO pw_history(pwh_id, user_id, oldpw)
			VALUES('3','3','chickenisntvegan')");



mysqli_query($conn, "INSERT INTO food (food_id, food_name, serving_size, serving_unit_id, calories, protein, carbs, fiber)
		VALUES ('1', 'testfood', '0', '30', '0', '0', '0','0')");

mysqli_query($conn, "INSERT INTO food (food_id, food_name, serving_size, serving_unit_id, calories, protein, carbs, fiber)
		VALUES ('2', 'Enemy Blood', '60', '32', '20', '60', '5','5')");

mysqli_query($conn, "INSERT INTO food (food_id, food_name, serving_size, serving_unit_id, calories, protein, carbs, fiber)
		VALUES ('3', 'Tendies', '50', '32', '400', '20', '30','10')");

mysqli_query($conn, "INSERT INTO food (food_id, food_name, serving_size, serving_unit_id, calories, protein, carbs, fiber)
		VALUES ('4', 'Minecraft Steak', '10', '32', '600', '80', '3','10')");



mysqli_query($conn, "INSERT INTO security_questions(sq_id, sq_text)
			VALUES('4', 'When was the last time you felt truly happy?')");

mysqli_query($conn, "INSERT INTO security_questions(sq_id, sq_text)
			VALUES('5', 'Would you smooch a robot?')");

mysqli_query($conn, "INSERT INTO security_questions(sq_id, sq_text)
			VALUES('6', 'Why did my wife leave me?')");
?>