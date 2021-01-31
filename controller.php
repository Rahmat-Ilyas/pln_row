<?php 
// Add Data 
function add_data($table, $data) {
	$get_field = mysqli_query($conn, "SHOW COLUMNS FROM $table");
	$field[] = 'NULL';
	foreach ($get_field as $dta) {
		$field[] = $dta['Field'];
		if (array_key_exists($dta['Field'], $data)) {
			echo "Key exists!";
		} else {
			echo "Key does not exist!";
		}
	}

	$query = "INSERT INTO tb_school VALUES (NULL, '$user_id', '$npsn', '$name_school', '$address', '$logo_school', '$school_level', '$status_school')";
}

?>