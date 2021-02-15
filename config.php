<?php
session_start();
$conn = mysqli_connect("localhost","rahmat_ryu","","db_monitoring");

require('controller.php');

$kgiatn = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE status='new'");
foreach ($kgiatn as $dta) {
	$pgrjaan_id = $dta['pengerjaan_id'];
	$pgrjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan WHERE id='$pgrjaan_id'");
	$pgr = mysqli_fetch_assoc($pgrjaan);

	if (date('ymd', strtotime($pgr['tggl_selesai'])) < date('ymd')) {
		$id = $dta['id'];
		mysqli_query($conn, "UPDATE tb_kegiatan SET status='revuse' WHERE id='$id'");
	}
}
?>