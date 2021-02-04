<?php 
// Add Data 
function add_data($table, $data) {
	global $conn;
	$get_field = mysqli_query($conn, "SHOW COLUMNS FROM $table");
	$value[] = 'NULL';
	foreach ($get_field as $dta) {
		if (array_key_exists($dta['Field'], $data)) {
			$key = $dta['Field'];
			$value[] = "'$data[$key]'";
		}
	}

	$field = implode(", ", $value);
	$query = "INSERT INTO $table VALUES ($field)";
	mysqli_query($conn, $query);

	if (mysqli_affected_rows($conn) > 0) {
		$response = [
			'status' => 'success',
			'message' => 'Pengerjaan baru berhasil ditambahkan. Silahkan isi data pengerjaan!',
		];
	} else { 
		$response = [
			'status' => 'error',
			'message' => mysqli_error($conn),
		];
	}
	return $response;
}

function update_data($table, $data) {
	global $conn;

	
}

?>