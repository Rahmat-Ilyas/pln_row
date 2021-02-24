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

	if ($_POST['req'] == 'laporanInspeksi') {
		$priode = mysqli_query($conn, "SELECT * FROM tb_priode_laporan ORDER BY id DESC");
		$prd = mysqli_fetch_assoc($priode);
		$priode_mulai = strtotime($prd['tanggal_mulai']);
		$priode_akhir = strtotime($prd['tanggal_akhir']);

		$anggota_id = $_POST['anggota_id'];
		$anggota = mysqli_query($conn, "SELECT * FROM tb_anggota WHERE id='$anggota_id'");
		$agt = mysqli_fetch_assoc($anggota);
		$pengerjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan WHERE anggota_id='$anggota_id'");
		$nomor_tiang = [];
		$dt_table = '';
		$no = 1;
		foreach ($pengerjaan as $pgr) {
			$pgr_mulai = strtotime($pgr['tggl_mulai']);
			$pgr_selesai = strtotime($pgr['tggl_selesai']);
			if ($priode_mulai < $pgr_mulai && $priode_akhir > $pgr_mulai || $priode_mulai < $pgr_selesai && $priode_akhir > $pgr_selesai) {
				$pgr_id = $pgr['id'];
				$kegiatan = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE pengerjaan_id='$pgr_id' AND status='accept' ORDER BY id DESC");
				foreach ($kegiatan as $kgt) {
					$kgt_mulai = strtotime($kgt['waktu_mulai']);
					$kgt_selesai = strtotime($kgt['waktu_selesai']);
					if ($priode_mulai < $kgt_mulai && $priode_akhir > $kgt_mulai || $priode_mulai < $kgt_selesai && $priode_akhir > $kgt_selesai) {
						// Priode
						$tggl_mulai = new DateTime($pgr['tggl_mulai']);
						$tggl_selesai = new DateTime($pgr['tggl_selesai']);
						$tggl = $tggl_mulai->diff($tggl_selesai)->days;
						if ($tggl >= 25) $priode = 'Bulanan';
						else if ($tggl >= 6) $priode = 'Mingguan';
						else if ($tggl >= 0) $priode = 'Harian';

						// data table
						$dt_table .= '
						<tr>
						<td>'.$no.'</td>
						<td>'.$pgr['unit'].'</td>
						<td>'.$pgr['gardu_induk'].'</td>
						<td>'.$pgr['formulir'].'</td>
						<td>'.$priode.'</td>
						<td>'.$agt['nama'].'</td>
						<td>'.date('d-m-y', strtotime($kgt['waktu_mulai'])).'</td>
						<td>'.$kgt['total_kerusakan'].'</td>
						<td>'.$kgt['durasi'].'</td>
						</tr>';

						$no=$no+1;
					}
				}
				$nomor_tiang[] = $pgr['nomor_tiang'];
			}
		} 

		echo json_encode([
			'unit' => $pgr['unit'],
			'gardu_induk' => $pgr['gardu_induk'],
			'nomor_tiang' => implode(', ', $nomor_tiang),
			'anggota' => $agt['nama'],
			'priode' => date('d-m-y', $priode_mulai).' - '.date('d-m-y', $priode_akhir),
			'priode_mulai' => date('d-m-Y', $priode_mulai),
			'priode_akhir' => date('d-m-Y', $priode_akhir),
			'export' => date('d-m-Y'),
			'dt_table' => $dt_table
		]);
	}

	if ($_POST['req'] == 'inspeksiFormulir') {
		$priode = mysqli_query($conn, "SELECT * FROM tb_priode_laporan ORDER BY id DESC");
		$prd = mysqli_fetch_assoc($priode);
		$priode_mulai = strtotime($prd['tanggal_mulai']);
		$priode_akhir = strtotime($prd['tanggal_akhir']);

		$anggota_id = $_POST['anggota_id'];
		$anggota = mysqli_query($conn, "SELECT * FROM tb_anggota WHERE id='$anggota_id'");
		$agt = mysqli_fetch_assoc($anggota);
		$pengerjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan WHERE anggota_id='$anggota_id'");
		$nomor_tiang = [];
		$dt_table = '';
		$no = 1;
		foreach ($pengerjaan as $pgr) {
			$pgr_mulai = strtotime($pgr['tggl_mulai']);
			$pgr_selesai = strtotime($pgr['tggl_selesai']);
			if ($priode_mulai < $pgr_mulai && $priode_akhir > $pgr_mulai || $priode_mulai < $pgr_selesai && $priode_akhir > $pgr_selesai) {
				$pgr_id = $pgr['id'];
				$kegiatan = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE pengerjaan_id='$pgr_id' AND status='accept' ORDER BY id DESC");
				foreach ($kegiatan as $kgt) {
					$kgt_mulai = strtotime($kgt['waktu_mulai']);
					$kgt_selesai = strtotime($kgt['waktu_selesai']);
					if ($priode_mulai < $kgt_mulai && $priode_akhir > $kgt_mulai || $priode_mulai < $kgt_selesai && $priode_akhir > $kgt_selesai) {
						// Priode
						$tggl_mulai = new DateTime($pgr['tggl_mulai']);
						$tggl_selesai = new DateTime($pgr['tggl_selesai']);
						$tggl = $tggl_mulai->diff($tggl_selesai)->days;
						if ($tggl >= 25) $priode = 'Bulanan';
						else if ($tggl >= 6) $priode = 'Mingguan';
						else if ($tggl >= 0) $priode = 'Harian';

						// data table
						$dt_table .= '
						<tr>
						<td>'.$no.'</td>
						<td>'.$pgr['formulir'].'</td>
						<td>'.$pgr['keterangan'].'</td>
						<td>'.$kgt['sasaran'].'</td>
						<td>
						<img src="../assets/images/foto_kegiatan/'.$kgt['foto_kegiatan'].'" width="200">
						</td>
						</tr>';

						$no=$no+1;
					}
				}
				$nomor_tiang[] = $pgr['nomor_tiang'];
			}
		} 

		echo json_encode([
			'unit' => $pgr['unit'],
			'gardu_induk' => $pgr['gardu_induk'],
			'nomor_tiang' => implode(', ', $nomor_tiang),
			'anggota' => $agt['nama'],
			'priode' => date('d-m-y', $priode_mulai).' - '.date('d-m-y', $priode_akhir),
			'priode_mulai' => date('d-m-Y', $priode_mulai),
			'priode_akhir' => date('d-m-Y', $priode_akhir),
			'export' => date('d-m-Y'),
			'dt_table1' => $dt_table
		]);
	}

	if ($_POST['req'] == 'detailLaporan') {
		$priode = mysqli_query($conn, "SELECT * FROM tb_priode_laporan ORDER BY id DESC");
		$prd = mysqli_fetch_assoc($priode);
		$priode_mulai = strtotime($prd['tanggal_mulai']);
		$priode_akhir = strtotime($prd['tanggal_akhir']);

		$anggota_id = $_POST['anggota_id'];
		$anggota = mysqli_query($conn, "SELECT * FROM tb_anggota WHERE id='$anggota_id'");
		$agt = mysqli_fetch_assoc($anggota);
		$pengerjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan WHERE anggota_id='$anggota_id'");
		$nomor_tiang = [];
		$dtl_table = '';
		$dtl_table1 = '';
		$no = 1;
		foreach ($pengerjaan as $pgr) {
			$pgr_mulai = strtotime($pgr['tggl_mulai']);
			$pgr_selesai = strtotime($pgr['tggl_selesai']);
			if ($priode_mulai < $pgr_mulai && $priode_akhir > $pgr_mulai || $priode_mulai < $pgr_selesai && $priode_akhir > $pgr_selesai) {
				$pgr_id = $pgr['id'];
				$kegiatan = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE pengerjaan_id='$pgr_id' AND status='accept' ORDER BY id DESC");
				foreach ($kegiatan as $kgt) {
					$kgt_mulai = strtotime($kgt['waktu_mulai']);
					$kgt_selesai = strtotime($kgt['waktu_selesai']);
					if ($priode_mulai < $kgt_mulai && $priode_akhir > $kgt_mulai || $priode_mulai < $kgt_selesai && $priode_akhir > $kgt_selesai) {
						// Priode
						$tggl_mulai = new DateTime($pgr['tggl_mulai']);
						$tggl_selesai = new DateTime($pgr['tggl_selesai']);
						$tggl = $tggl_mulai->diff($tggl_selesai)->days;
						if ($tggl >= 25) $priode = 'Bulanan';
						else if ($tggl >= 6) $priode = 'Mingguan';
						else if ($tggl >= 0) $priode = 'Harian';

						// data table
						$dtl_table .= '
						<tr>
						<td>'.$no.'</td>
						<td>'.$pgr['unit'].'</td>
						<td>'.$pgr['gardu_induk'].'</td>
						<td>'.$pgr['formulir'].'</td>
						<td>'.$priode.'</td>
						<td>'.$agt['nama'].'</td>
						<td>'.date('d-m-y', strtotime($kgt['waktu_mulai'])).'</td>
						<td>'.$kgt['total_kerusakan'].'</td>
						<td>'.$kgt['durasi'].'</td>
						</tr>';

						$dtl_table1 .= '
						<tr>
						<td>'.$no.'</td>
						<td>'.$pgr['formulir'].'</td>
						<td>'.$pgr['keterangan'].'</td>
						<td>'.$kgt['sasaran'].'</td>
						<td>
						<img src="../assets/images/foto_kegiatan/'.$kgt['foto_kegiatan'].'" width="150">
						</td>
						</tr>';

						$no=$no+1;
					}
				}
				$nomor_tiang[] = $pgr['nomor_tiang'];
			}
		} 

		echo json_encode([
			'unit' => $pgr['unit'],
			'gardu_induk' => $pgr['gardu_induk'],
			'nomor_tiang' => implode(', ', $nomor_tiang),
			'anggota' => $agt['nama'],
			'priode' => date('d-m-y', $priode_mulai).' - '.date('d-m-y', $priode_akhir),
			'priode_mulai' => date('d-m-Y', $priode_mulai),
			'priode_akhir' => date('d-m-Y', $priode_akhir),
			'export' => date('d-m-Y'),
			'dtl_table' => $dtl_table,
			'dtl_table1' => $dtl_table1,
		]);
	}
}

?>