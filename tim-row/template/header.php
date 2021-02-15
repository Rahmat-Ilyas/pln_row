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
                <li><a href="javascript:void(0)"><i class="ti-user m-r-10 text-custom"></i> Profile</a></li>
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


  <div class="wrapper">
    <div class="container">