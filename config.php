<?php
session_start();
$conn = mysqli_connect("localhost","rahmat_ryu","","db_monitoring");

require('controller.php');

$pgrjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan");
foreach ($pgrjaan as $dta) {
	# code...
}
?>