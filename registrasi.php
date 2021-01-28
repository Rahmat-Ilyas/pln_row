<?php 
require('config.php');

$response = '';
if (isset($_POST['submit'])) {
	$nip = $_POST['nip'];
	$nama = $_POST['nama'];
	$telepon = $_POST['telepon'];
	$username = $_POST['username'];
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

	$nama_foto = $_FILES['foto']['name'];
	$ext = pathinfo($nama_foto, PATHINFO_EXTENSION);
	$foto = str_replace(' ', '-', $nama)."-".time().".".$ext;
	$tmp = $_FILES['foto']['tmp_name'];

    // TAMBAH DATA 
	$query = "INSERT INTO tb_anggota VALUES (NULL, '$nip', '$nama', '$telepon', '$foto', '$username', '$password', 'new')";
	mysqli_query($conn, $query);
	if (mysqli_affected_rows($conn) > 0) {
		move_uploaded_file($tmp, 'assets/images/anggota/'.$foto);
		$response = 'success';
	} else {
		$response = 'error';
		$error = mysqli_error($conn);
	}	
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
	<meta name="author" content="Coderthemes">

	<link rel="shortcut icon" href="assets/images/pln_fav.png">

	<title>Register - PLN UPT Makassar</title>

	<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/core.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/components.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />

	<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and foto queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="assets/js/modernizr.min.js"></script>

</head>
<body>

	<div class="account-pages"></div>
	<div class="clearfix"></div>
	<div class="wrapper-page">
		<div class=" card-box">
			<div class="panel-heading m-b-0"> 
				<h3 class="text-center"><img src="assets/images/pln_logo.png" height="50"/> <strong>PLN UPT</strong></h3>
			</div>

			<div class="panel-body">
				<form class="form-horizontal m-t-20" method="POST" enctype="multipart/form-data">

					<div class="form-group ">
						<label class="col-xs-12">NIP</label>
						<div class="col-xs-12">
							<input class="form-control" type="text" required="" placeholder="NIP.." name="nip">
						</div>
					</div>

					<div class="form-group ">
						<label class="col-xs-12">Nama</label>
						<div class="col-xs-12">
							<input class="form-control" type="text" required="" placeholder="Nama.." name="nama">
						</div>
					</div>

					<div class="form-group ">
						<label class="col-xs-12">Telepon</label>
						<div class="col-xs-12">
							<input class="form-control" type="number" required="" placeholder="Telepon.." name="telepon">
						</div>
					</div>

					<div class="form-group">
						<label class="col-xs-12">Foto</label>
						<div class="col-xs-12">
							<input class="form-control" id="foto" type="file" required="" placeholder="Foto.." name="foto">
							<i class="text-danger cek-foto" hidden=""></i>
						</div>
					</div>

					<div class="form-group ">
						<label class="col-xs-12">Username</label>
						<div class="col-xs-12">
							<input class="form-control" type="text" required="" placeholder="Username.." name="username">
						</div>
					</div>

					<div class="form-group">
						<label class="col-xs-12">Password</label>
						<div class="col-xs-12">
							<input class="form-control" type="password" required="" placeholder="Password.." name="password">
						</div>
					</div>

					<div class="form-group text-center m-t-40">
						<div class="col-xs-12">
							<button class="btn btn-warning btn-block text-uppercase waves-effect waves-light" type="submit" name="submit">
								Daftar
							</button>
						</div>
					</div>

				</form>

			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 text-center">
				<p>
					Sudah punya akun?<a href="login.php" class="text-primary m-l-5"><b>Log In</b></a>
				</p>
			</div>
		</div>

	</div>

	<script>
		var resizefunc = [];
	</script>

	<!-- jQuery  -->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/detect.js"></script>
	<script src="assets/js/fastclick.js"></script>
	<script src="assets/js/jquery.slimscroll.js"></script>
	<script src="assets/js/jquery.blockUI.js"></script>
	<script src="assets/js/waves.js"></script>
	<script src="assets/js/wow.min.js"></script>
	<script src="assets/js/jquery.nicescroll.js"></script>
	<script src="assets/js/jquery.scrollTo.min.js"></script>


	<script src="assets/js/jquery.core.js"></script>
	<script src="assets/js/jquery.app.js"></script>

	<script src="assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>

	<script>
		$(document).ready(function($) {
			<?php if ($response == 'success') { ?>
				Swal.fire({
					title: 'Pendaftaran Selesai',
					text: 'Pendaftaran selesai. Akun anda akan diverifikasi oleh kepala devisi!',
					type: 'success'
				}).then(function() {
					location.href = 'login.php';
				});
			<?php } else if ($response == 'error') { ?>
				Swal.fire({
					title: 'Pendaftaran Gagal',
					text: "<?= $error ?>",
					type: 'error'
				});
			<?php } ?>

			// FILE VALIDASI
			$('#foto').change(function() {
				var foto = $('#foto').prop('files')[0];
				var check = 0;

				var ext = ['image/jpeg', 'image/png', 'image/bmp'];

				$.each(ext, function(key, val) {
					if (foto.type == val) check = check + 1;
				});

				if (check == 1) {
					$('.cek-foto').attr('hidden', '');
				} else {
					$('.cek-foto').removeAttr('hidden');
					$('.cek-foto').text('Format file tidak dibolehkan, pilih file lain');
					$(this).val('');
					return;
				}

				if (foto.size > 5000000) {
					$('.cek-foto').removeAttr('hidden');
					$('.cek-foto').text('Ukuran file minimal 5 Mb, pilih file lain');
					$(this).val('');
				}
			});
		});
	</script>

</body>
</html>