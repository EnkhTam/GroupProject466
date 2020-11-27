<?php
include('config.php');

mysqli_select_db($conn,'z1865285');
///creates the tables for the database
mysqli_query($conn, "CREATE TABLE measurement_type(
		type_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
		type_name VARCHAR(30) NOT NULL
		)");

mysqli_query($conn, "CREATE TABLE measurement_unit(
	   unit_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	   type_id INT UNSIGNED NOT NULL,
	   unit_name VARCHAR(30) NOT NULL,
	   unit_sym VARCHAR(10) NOT NULL,
	   FOREIGN KEY (type_id) REFERENCES measurement_type(type_id)
		)");

mysqli_query($conn, "CREATE TABLE measurement_conv(
		type_id INT UNSIGNED NOT NULL,
	   from_unit_id INT UNSIGNED NOT NULL DEFAULT 0,
	   to_unit_id INT UNSIGNED NOT NULL DEFAULT 0,
	   from_unit_offset FLOAT NOT NULL,
	   mult FLOAT NOT NULL DEFAULT 1,
	   divi FLOAT NOT NULL DEFAULT 1,
	   to_unit_offset FLOAT NOT NULL,
	   PRIMARY KEY (type_id, from_unit_id, to_unit_id),
	   FOREIGN KEY (type_id) REFERENCES measurement_type(type_id),
	   FOREIGN KEY (from_unit_id) REFERENCES measurement_unit(unit_id),
	   FOREIGN KEY (to_unit_id) REFERENCES measurement_unit(unit_id)
	   )");

mysqli_query($conn, "INSERT INTO measurement_type(type_id, type_name) 
			VALUES(4, 'Mass')");

mysqli_query($conn, "INSERT INTO measurement_unit(unit_id, type_id, unit_name, unit_sym)
			VALUES (28, 4, 'Milligram', 'mg'), (29, 4, 'Gram', 'g'),
				   (30, 4, 'Kilogram', 'kg'), (31, 4, 'Tonne', 't'),
				   (32, 4, 'Ounce', 'oz'), (33, 4, 'Pound', 'lb'),
				   (34, 4, 'Stone', 's'), (35, 4, 'hundred weight', 'cwt'),
				   (36, 4, 'UK long ton', 'ton')");

mysqli_query($conn, "INSERT INTO measurement_conv(type_id, from_unit_id, to_unit_id, mult, divi)
			VALUES (4, 28, 29, 1, 1000), (4, 28, 30, 1, 1000000), (4, 28, 31, 1, 1000000000),
				   (4, 28, 32, 1, 28350), (4, 32, 33, 1, 16), (4, 32, 34, 1, 224),
				   (4, 32, 35, 1, 50802345), (4, 32, 36, 1, 35840)");

mysqli_query($conn, "INSERT INTO measurement_conv(type_id, from_unit_id, to_unit_id, from_unit_offset, mult, divi, to_unit_offset)
			SELECT DISTINCT measurement_conv.type_id,
							measurement_conv.to_unit_id,
							measurement_conv.from_unit_id,
							-measurement_conv.to_unit_offset,
							measurement_conv.divi,
							measurement_conv.mult,
							-measurement_conv.from_unit_offset
			FROM measurement_conv
			-- LEFT JOIN Used to assure that we dont try to insert already existing keys.
			LEFT JOIN measurement_conv AS existing
			ON measurement_conv.from_unit_id = existing.to_unit_id AND measurement_conv.to_unit_id = existing.from_unit_id
			WHERE existing.type_id IS NULL");

$consql = "INSERT IGNORE INTO measurement_conv(type_id, from_unit_id, to_unit_id, from_unit_offset, mult, divi, to_unit_offset)
			SELECT DISTINCT ffrom.type_id,
							ffrom.to_unit_id AS from_unit_id,
							tto.to_unit_id,
							-ffrom.to_unit_offset + ((tto.from_unit_offset) * ffrom.mult / ffrom.divi) AS from_unit_offset,
							ffrom.divi * tto.mult AS mult,
							ffrom.mult * tto.divi AS divi,
							tto.to_unit_offset - ((ffrom.from_unit_offset) * tto.mult / tto.divi) AS to_unit_offset
			FROM measurement_conv AS ffrom
			CROSS JOIN measurement_conv AS tto
			-- LEFT JOIN Used to assure that we dont try to insert already existing keys.
			LEFT JOIN measurement_conv AS existing
			ON ffrom.to_unit_id = existing.from_unit_id AND tto.to_unit_id = existing.to_unit_id
			WHERE existing.type_id IS NULL
				  AND ffrom.type_id = tto.type_id
				  AND ffrom.to_unit_id <> tto.to_unit_id
				  AND ffrom.from_unit_id = tto.from_unit_id";
$cnt = 0;
do{
	if(!(($conres = mysqli_query($conn, $consql)) > 0)){
		$cnt = mysqli_num_rows($conres);
	}
	if(!($cnt === 0) && ($cnt < 71) && ($cnt > 0)){
		continue;
	}
	else{
		break;
	}
}while(!($cnt === 0) && ($cnt < 71) && ($cnt > 0));
?>
