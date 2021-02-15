<?php 
require('template/header.php');

$pgr_all = mysqli_query($conn, "SELECT * FROM tb_pengerjaan");
$ttl_pgr = mysqli_num_rows($pgr_all);

$kgt_all = mysqli_query($conn, "SELECT * FROM tb_kegiatan");
$ttl_kgt = mysqli_num_rows($kgt_all);

$agt_all = mysqli_query($conn, "SELECT * FROM tb_anggota WHERE status='active'");
$ttl_agt = mysqli_num_rows($agt_all);

$get_plap = mysqli_query($conn, "SELECT * FROM tb_priode_laporan ORDER BY id DESC");
$prd_lap = mysqli_fetch_assoc($get_plap);
?>
<!-- Start content -->
<div class="content">
  <div class="container">

    <!-- Page-Title -->
    <div class="row">
      <div class="col-sm-12">
        <!-- Page-Title -->
        <div class="row">
          <div class="col-sm-12">
            <h4 class="page-title">Dashboard</h4>
            <ol class="breadcrumb">
              <li>
                <a href="#">Kepala Devisi</a>
              </li>
              <li class="active">
                Dashboard
              </li>
            </ol>
          </div>
        </div>
        
        <div class="row">
          <div class="col-sm-12">
            <div class="card-box widget-inline">
              <div class="row">
                <div class="col-lg-3 col-sm-6">
                  <div class="widget-inline-box text-center">
                    <h3><i class="text-primary fa fa-suitcase"></i> <b data-plugin="counterup"><?= $ttl_pgr ?></b></h3>
                    <h4 class="text-muted">Total Pengerjaan</h4>
                  </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                  <div class="widget-inline-box text-center">
                    <h3><i class="text-custom fa fa-wrench"></i> <b data-plugin="counterup"><?= $ttl_kgt ?></b></h3>
                    <h4 class="text-muted">Total Kegiatan</h4>
                  </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                  <div class="widget-inline-box text-center">
                    <h3><i class="text-pink fa fa-users"></i> <b data-plugin="counterup"><?= $ttl_agt ?></b></h3>
                    <h4 class="text-muted">Jumlah Anggota</h4>
                  </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                  <div class="widget-inline-box text-center b-0">
                    <h3><i class="text-purple fa fa-calendar"></i> <b data-plugin="counterup"><?= date('d/m/y', strtotime($prd_lap['tanggal_stor'])) ?></b></h3>
                    <h4 class="text-muted">Laporan Diserahkan</h4>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-12">
            <div class="card-box">
              <div class="row">
                <div class="col-md-12">
                  <div class="text-center slider-bg m-b-0">
                    <div class="panel-body p-0">
                      <div class="item">
                        <h3><span class="text-custom font-600">Hey! Selamat Datang</span></h3>
                        <p class="small"><?= date('d F, Y') ?></p>
                        <p class="m-t-30"><em>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</em></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>




  </div> <!-- container -->

</div> <!-- content -->

<?php 
require('template/footer.php');
?>

