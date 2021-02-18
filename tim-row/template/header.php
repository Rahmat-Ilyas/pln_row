<?php 
require('../config.php');

if (!isset($_SESSION['login_timrow'])) header("location: ../login.php");
$anggota_id = $_SESSION['anggota_id'];

$radius = mysqli_query($conn, "SELECT * FROM tb_radius");
$rds = mysqli_fetch_assoc($radius);

$anggota = mysqli_query($conn, "SELECT * FROM tb_anggota WHERE id='$anggota_id'");
$agt = mysqli_fetch_assoc($anggota);

$new_kgt = 0;
$gpkj = mysqli_query($conn, "SELECT * FROM tb_pengerjaan WHERE anggota_id='$anggota_id'");
foreach ($gpkj as $x) {
  $pengerjaan_id = $x['id'];
  $kgit = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE pengerjaan_id='$pengerjaan_id' AND status='new'");
  foreach ($kgit as $kg) {
    $new_kgt=$new_kgt+1;
  }
}

// Update Akun Anggota 
if (isset($_POST['update_akun'])) {
  $id = $_POST['id'];
  $nip = $_POST['nip'];
  $nama = $_POST['nama'];
  $telepon = $_POST['telepon'];
  $username = $_POST['username'];
  if ($_POST['password'])
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  else
    $password = $agt['password'];

  if ($_FILES['foto']['name'] != '') {
    $nama_foto = $_FILES['foto']['name'];
    $ext = pathinfo($nama_foto, PATHINFO_EXTENSION);
    $foto = str_replace(' ', '-', $nama)."-".time().".".$ext;
    $tmp = $_FILES['foto']['tmp_name'];
    move_uploaded_file($tmp, '../assets/images/anggota/'.$foto);
    $target = "../assets/images/anggota/".$agt['foto'];
    if (file_exists($target)) unlink($target);
  } else {
    $foto = $agt['foto'];
  }

  $query = "UPDATE tb_anggota SET nip='$nip', nama='$nama', telepon='$telepon', username='$username', password='$password', foto='$foto' WHERE id='$id'";

  if (mysqli_query($conn, $query)) {
    $res_updt_akun = [
      'status' => 'success',
      'message' => 'User berhasil diupdate',
    ];    
  } else { 
    $res_updt_akun = [
      'status' => 'error',
      'message' => mysqli_error($conn),
    ];
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
  <!-- App Favicon icon -->
  <link rel="shortcut icon" href="../assets/images/pln_fav.png">

  <title>Tim ROW - PLN UPT Makassar</title>

  <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
  <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
  <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
  <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
  <link href="assets/css/menu.css" rel="stylesheet" type="text/css" />
  <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />

  <!-- DataTables -->
  <link href="../assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
  <link href="../assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>

  <!-- Dropzone css -->
  <link href="../assets/plugins/dropzone/dropzone.css" rel="stylesheet" type="text/css" />

  <script src="assets/js/modernizr.min.js"></script>

</head>

<body>
  <header id="topnav">
    <div class="topbar-main">
      <div class="container">

        <!-- Logo container-->
        <div class="logo">
          <a href="index.html" class="logo">
            <span><img src="../assets/images/pln_logo.png" height="30"/> PLN UPT</span>
          </a>
        </div>

        <div class="menu-extras">
          <ul class="nav navbar-nav navbar-right pull-right">
            <li class="dropdown top-menu-item-xs">
              <a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
                <img src="../assets/images/anggota/<?= $agt['foto'] ?>" alt="user-img" class="img-circle">
              </a>
              <ul class="dropdown-menu">
                <li><h4 style="margin-left: 20px;"><?= $agt['nama'] ?></h4></li>
                <li class="divider"></li>
                <li><a href="javascript:void(0)" data-toggle="modal" data-target="#modal-profile"><i class="ti-user m-r-10 text-custom"></i> Profile</a></li>
                <li><a href="../logout.php"><i class="ti-power-off m-r-10 text-danger"></i> Logout</a></li>
              </ul>
            </li>
          </ul>
          <div class="menu-item">
            <!-- Mobile menu toggle-->
            <a class="navbar-toggle">
              <div class="lines">
                <span></span>
                <span></span>
                <span></span>
              </div>
            </a>
            <!-- End mobile menu toggle-->
          </div>
        </div>

      </div>
    </div>

    <div class="navbar-custom">
      <div class="container">
        <div id="navigation">
          <!-- Navigation Menu-->
          <ul class="navigation-menu">
            <li class="has-submenu">
              <a href="index.php"><i class="md md-dashboard"></i>Dashboard</a>
            </li>

            <li class="has-submenu">
              <a href="#"><i class="fa fa-suitcase"></i>Data Pengerjaan</a>
              <ul class="submenu">
                <li>
                  <a href="pengerjaan-baru.php">Pengerjaan Baru</a>
                </li>
                <li>
                  <a href="data-pengerjaan.php">Data Pengerjaan</a>
                </li>
              </ul>
            </li>

            <li class="has-submenu">
              <a href="data-kegiatan.php" class="waves-effect">
                <i class="fa fa-wrench">
                  <?php if ($new_kgt > 0) { ?>
                    <span class="badge badge-xs badge-danger" style="font-size: 11px;"><?= $new_kgt ?></span>
                  <?php } ?>
                </i>Data Kegiatan
              </a>
            </li>

            <li class="has-submenu">
              <a href="riwayat-pengerjaan.php" class="waves-effect"><i class="fa fa-history"></i> <span> Riwayat Pengerjaan </span> </a>
            </li>

          </ul>
        </div>
      </div> <!-- end container -->
    </div> <!-- end navbar-custom -->
  </header>
  <!-- End Navigation Bar-->

  <!-- MODAL EDIT ALAT-->
  <div class="modal" id="modal-profile" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="myLargeModalLabel">Profile</h4>
        </div>
        <div class="modal-body" style="padding: 20px 50px 0 50px">
          <div id="view-profil">
            <div class="profile-detail card-box">
              <div>
                <img src="../assets/images/anggota/<?= $agt['foto'] ?>" class="img-circle" alt="profile-image">
                <h4 class="text-uppercase font-600 m-t-20"><?= $agt['nama'] ?></h4>
                <h5 class="text-center">NIP: <?= $agt['nip'] ?></h5>
                <hr>

                <div class="text-left">
                  <p class="text-muted font-13"><strong>Nama Lengkap:</strong> <span><?= $agt['nama'] ?></span></p>
                  <p class="text-muted font-13"><strong>NIP:</strong> <span><?= $agt['nip'] ?></span></p>
                  <p class="text-muted font-13"><strong>Telepon:</strong> <span><?= $agt['telepon'] ?></span></p>
                  <p class="text-muted font-13"><strong>Username:</strong> <span><?= $agt['username'] ?></span></p>
                </div>


                <div class="button-list m-t-20">
                  <button type="button" class="btn btn-success" id="btn-edit-profil"> <i class="fa fa-user"></i> Update Profile</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true"> <i class="fa fa-long-arrow-left"></i> Tutup</button>
                </div>
              </div>
            </div>
          </div>
          <div id="edit-profil" hidden="">
            <form method="POST" action="#" enctype="multipart/form-data">
              <div class="form-group row">
                <label class="col-sm-4 col-form-label">Nama Lengkap</label>
                <div class="col-sm-8">
                  <input type="hidden" name="id" value="<?= $agt['id'] ?>">
                  <input type="text" class="form-control" required="" autocomplete="off" placeholder="Nama..." name="nama" value="<?= $agt['nama'] ?>">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-4 col-form-label">NIP</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" required="" autocomplete="off" placeholder="NIP..." name="nip" value="<?= $agt['nip'] ?>">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-4 col-form-label">Telepon</label>
                <div class="col-sm-8">
                  <input type="number" class="form-control" required="" autocomplete="off" placeholder="Telepon..." name="telepon" value="<?= $agt['telepon'] ?>">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-4 col-form-label">Foto</label>
                <div class="col-sm-8">
                  <input type="file" class="form-control" name="foto">
                  <img src="../assets/images/anggota/<?= $agt['foto'] ?>" class="m-t-5" style="width: 25px;"> <span class="m-t-5">| <?= $agt['foto'] ?></span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-4 col-form-label">Username</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" required="" autocomplete="off" placeholder="Username..." name="username" value="<?= $agt['username'] ?>">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-4 col-form-label">Password</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" autocomplete="off" placeholder="Masukkan password baru untuk mengupdate..." name="password">
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-4"></div>
                <div class="col-sm-8">
                  <button type="submit" name="update_akun" class="btn btn-default" id="upload">Update</button>
                  <a href="#" class="btn btn-primary" id="batal-update">Batal</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="wrapper">
    <div class="container">