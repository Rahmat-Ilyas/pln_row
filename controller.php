<?php 
// Add Data 
function add_data($table, $data) {
	global $conn;
	$get_field = mysqli_query($conn, "SHOW COLUMNS FROM $table");
	$value = [];
	foreach ($get_field as $dta) {
		if ($dta['Field'] == 'id') {
			$value[] = "NULL";
		} else if (array_key_exists($dta['Field'], $data)) {
			$key = $dta['Field'];
			$value[] = "'$data[$key]'";
		} else {
			if ($dta['Type'] == 'int') $value[] = "0";
			else $value[] = "NULL";
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

if (isset($_POST['req'])) {
	$conn = mysqli_connect("localhost","rahmat_ryu","","db_monitoring");
	header('Content-type: application/json');

	if ($_POST['req'] == 'uploadFoto') {
		$kegiatan_id = $_POST['kegiatan_id'];
		$total_kerusakan = $_POST['total_kerusakan'];
		$waktu_selesai = date('Y-m-d H:i:s', strtotime($_POST['tggl_foto']));

		$kegiatan = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE id='$kegiatan_id'");
		$kgt = mysqli_fetch_assoc($kegiatan);

		if (strtotime($kgt['waktu_mulai']) > strtotime($_POST['tggl_foto'])) {
			echo json_encode([
				'status' => 'warning',
				'title' => 'Upload Foto Baru',
				'message' => 'Foto yang anda upload terdeteksi sebagai foto lama. Harap upload foto bukti kegiatan pengerjaan yang sesuai.'
			]);
		} else {
			// SET DURASI
			$time_star = new DateTime($kgt['waktu_mulai']);
			$time_finish = new DateTime($waktu_selesai);
			$interval = date_diff($time_star, $time_finish);
			$durasi = $interval->i.'.'.$interval->s;

			// SET FOTO 
			$foto = $_FILES['foto_kegiatan']['name'];
			$ext = pathinfo($foto, PATHINFO_EXTENSION);
			$foto_kegiatan = 'foto_kegiatan-'.sprintf('%04s', $kegiatan_id).".".$ext;
			$file_tmp = $_FILES['foto_kegiatan']['tmp_name'];
			move_uploaded_file($file_tmp, 'assets/images/foto_kegiatan/'.$foto_kegiatan);

			$query = "UPDATE tb_kegiatan SET total_kerusakan='$total_kerusakan', waktu_selesai='$waktu_selesai', durasi='$durasi', foto_kegiatan='$foto_kegiatan', status='proccess' WHERE id='$kegiatan_id'";

			if (mysqli_query($conn, $query)) {
				echo json_encode([
					'status' => 'success',
					'title' => 'Telah Diselesaikan',
					'message' => 'Kegiatan pengerjaan berhasil diselesaikan. Laporan anda akan segera diproses oleh kepala devisi'
				]);
			} else {
				echo json_encode([
					'status' => 'error',
					'title' => 'Terjadi Kesalahan',
					'message' => mysqli_error($conn)
				]);
			}

		}
	}
}

?>