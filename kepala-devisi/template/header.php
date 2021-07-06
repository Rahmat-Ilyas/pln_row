<?php 
require('../config.php');

if (!isset($_SESSION['login_kldevis'])) header("location: ../login.php");

$kegiatan = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE status='proccess'");
$kgt_new = mysqli_num_rows($kegiatan);

$anggota_new = mysqli_query($conn, "SELECT * FROM tb_anggota WHERE status='new'");
$agt_new = mysqli_num_rows($anggota_new);

$kp_devisi = mysqli_query($conn, "SELECT * FROM tb_kepaladevisi");
$kdv = mysqli_fetch_assoc($kp_devisi);

// Update Akun Anggota 
if (isset($_POST['update_akun'])) {
  $id = $_POST['id'];
  $nip = $_POST['nip'];
  $nama = $_POST['nama'];
  $username = $_POST['username'];
  if ($_POST['password'])
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  else
    $password = $kdv['password'];

  $query = "UPDATE tb_kepaladevisi SET nip='$nip', nama='$nama', username='$username', password='$password' WHERE id='$id'";

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

  <link rel="shortcut icon" href="../assets/images/pln_fav.png">

  <title>Tim ROW - PLN UPT Makassar</title>

  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="../assets/css/core.css" rel="stylesheet" type="text/css" />
  <link href="../assets/css/components.css" rel="stylesheet" type="text/css" />
  <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
  <link href="../assets/css/pages.css" rel="stylesheet" type="text/css" />
  <link href="../assets/css/responsive.css" rel="stylesheet" type="text/css" />

  <!-- DataTables -->
  <link href="../assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
  <link href="../assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>

  <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
      <![endif]-->

      <script src="../assets/js/modernizr.min.js"></script>

    </head>

    <body class="fixed-left">

      <!-- Begin page -->
      <div id="wrapper">

        <!-- Top Bar Start -->
        <div class="topbar">

          <!-- LOGO -->
          <div class="topbar-left">
            <div class="text-center">
              <a href="index.html" class="logo">
                <i class="icon-c-logo"> <img src="../assets/images/pln_logo.png" height="40"/> </i>
                <span><img src="../assets/images/pln_logo.png" height="40"/> PLN UPT</span>
              </a>
            </div>
          </div>

          <!-- Button mobile view to collapse sidebar menu -->
          <div class="navbar navbar-default" role="navigation">
            <div class="container">
              <div class="">
                <div class="pull-left">
                  <button class="button-menu-mobile open-left waves-effect waves-light">
                    <i class="md md-menu"></i>
                  </button>
                  <span class="clearfix"></span>
                </div>


                <ul class="nav navbar-nav navbar-right pull-right">
                  <li class="dropdown top-menu-item-xs">
                    <a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true"><img src="../assets/images/users/avatar-1.png" alt="user-img" class="img-circle"> </a>
                    <ul class="dropdown-menu">
                      <li><a href="javascript:void(0)" data-toggle="modal" data-target="#modal-profile"><i class="ti-user m-r-10 text-custom"></i> Profile</a></li>
                      <li class="divider"></li>
                      <li><a href="../logout.php"><i class="ti-power-off m-r-10 text-danger"></i> Logout</a></li>
                    </ul>
                  </li>
                </ul>
              </div>
              <!--/.nav-collapse -->
            </div>
          </div>
        </div>
        <!-- Top Bar End -->


        <!-- ========== Left Sidebar Start ========== -->

        <div class="left side-menu" style="background-color: #fff;">
          <div class="sidebar-inner slimscrollleft">
            <!--- Divider -->
            <div id="sidebar-menu">
              <ul>
                <li class="has_sub">
                  <a href="index.php" class="waves-effect"><i class="ti-dashboard"></i> <span> Dashboard </span></a>
                </li>

                <li class="has_sub">
                  <a href="javascript:void(0);" class="waves-effect">
                    <i class="fa fa-table"></i>
                    <span>Pengerjaan & Kegiatan</span> 
                    <?php if ($kgt_new > 0) {  ?>
                      <span class="badge badge-xs badge-danger pull-right" style="font-size: 11px; margin-top: -28px;"><?= $kgt_new ?></span>
                    <?php } else { ?>
                      <span class="menu-arrow"></span>
                    <?php } ?>
                  </a>
                  <ul class="list-unstyled">
                    <li>
                      <a href="kegiatan-baru.php">Kegiatan Baru
                        <?php if ($kgt_new > 0) {  ?>
                          <span class="badge badge-xs badge-danger pull-right" style="font-size: 11px;"><?= $kgt_new ?></span>
                        <?php } ?>
                      </a>
                    </li>
                    <li><a href="data-kegiatan.php">Data Kegiatan</a></li>
                    <li><a href="data-pengerjaan.php">Data Pengerjaan</a></li>
                  </ul>
                </li>

                <li class="has_sub">
                  <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-file-text-o"></i><span> Laporan </span> <span class="menu-arrow"></span></a>
                  <ul class="list-unstyled">
                    <li><a href="cetak-laporan.php">Cetak Laporan</a></li>
                    <li><a href="arsip-laporan.php">Arsip Laporan</a></li>
                  </ul>
                </li>

                <li class="has_sub">
                  <a href="priode-laporan.php" class="waves-effect"><i class="fa fa-calendar"></i><span> Periode Laporan </span> </a>
                </li>

                <li class="has_sub">
                  <a href="set-radius.php" class="waves-effect"><i class="fa fa-map-o"></i><span> Set Radius </span> </a>
                </li>

                <li class="has_sub">
                  <a href="data-anggota.php" class="waves-effect">
                    <i class="ti-id-badge"></i>
                    <span> Data Anggota</span>
                    <?php if ($agt_new > 0) {  ?>
                      <span class="badge badge-xs badge-danger" style="font-size: 11px; margin-top: -20px;"><?= $agt_new ?></span>
                    <?php } ?>
                  </a>
                </li>

              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="content-page">

          <!-- MODAL EDIT ALAT-->
          <div class="modal" id="modal-profile" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="modal-title" id="myLargeModalLabel">Update Profile</h4>
                </div>
                <div class="modal-body" style="padding: 20px 50px 0 50px">
                  <div id="edit-profil">
                    <form method="POST" action="#" enctype="multipart/form-data">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Nama Lengkap</label>
                        <div class="col-sm-8">
                          <input type="hidden" name="id" value="<?= $kdv['id'] ?>">
                          <input type="text" class="form-control" required="" autocomplete="off" placeholder="Nama..." name="nama" value="<?= $kdv['nama'] ?>">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">NIP</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" required="" autocomplete="off" placeholder="NIP..." name="nip" value="<?= $kdv['nip'] ?>">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Username</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" required="" autocomplete="off" placeholder="Username..." name="username" value="<?= $kdv['username'] ?>">
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
                          <a href="#" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Batal</a>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>