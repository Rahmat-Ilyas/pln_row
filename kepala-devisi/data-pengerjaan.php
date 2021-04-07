<?php 
require('template/header.php');

// DELETE PENGERJAAN
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $query = "DELETE FROM tb_pengerjaan WHERE id='$id'";
  if (mysqli_query($conn, $query)) {
    $kegiatan = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE pengerjaan_id='$id'");
    foreach ($kegiatan as $kgt) {
      $kgt_id = $kgt['id'];
      if ($kgt['foto_kegiatan']) {
        $target = "../assets/images/foto_kegiatan/".$kgt['foto_kegiatan'];
        if (file_exists($target)) unlink($target);
      }
      mysqli_query($conn, "DELETE FROM tb_kegiatan WHERE id='$kgt_id'");
    }

    $response = [
      'status' => 'delete',
      'message' => 'Data berhasil di hapus',
    ];
  } else { 
    $response = [
      'status' => 'error',
      'message' => mysqli_error($conn),
    ];
  }
}

$priode = mysqli_query($conn, "SELECT * FROM tb_priode_laporan ORDER BY id DESC");
$prd = mysqli_fetch_assoc($priode);

$pngrjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan ORDER BY id DESC");

?>
<div class="content">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <!-- Page-Title -->
        <div class="row">
          <div class="col-sm-12">
            <h4 class="page-title">Data Pengerjaan</h4>
            <ol class="breadcrumb">
              <li>
                <a href="#">Kepala Devisi</a>
              </li>
              <li class="active">
                Penengerjaan & Kegiatan
              </li>
              <li class="active">
                Data Pengerjaan
              </li>
            </ol>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="card-box table-responsive">
              <h4 class="m-t-0 header-title"><b>Data Pengerjaan</b></h4>
              <hr>

              <table id="datatable" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th width="10">No</th>
                    <th width="100">Nama Pekerja</th>
                    <th>Komponen</th>
                    <th>Nomoe Tiang</th>
                    <th>Waktu/Priode</th>
                    <th>Lokasi</th>
                    <th>Total Kegiatan</th>
                    <th>Status Pengerjaan</th>
                    <th width="60">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1; foreach ($pngrjaan as $dta) { 
                    // Priode
                    $tggl_mulai = new DateTime($dta['tggl_mulai']);
                    $tggl_selesai = new DateTime($dta['tggl_selesai']);
                    $tggl = $tggl_mulai->diff($tggl_selesai)->days;
                    if ($tggl >= 25) $priode = 'Bulanan';
                    else if ($tggl >= 6) $priode = 'Mingguan';
                    else if ($tggl >= 0) $priode = 'Harian';
                    // Total Kegiatan
                    $pengerjaan_id = $dta['id'];
                    $kegiatan = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE pengerjaan_id='$pengerjaan_id'");
                    $total_kegiatan = mysqli_num_rows($kegiatan);
                    // Status
                    if (date('Y-m-d', strtotime($dta['tggl_selesai'])) >= date('Y-m-d')) {
                      $sts_krj = 'Sedang Belangsung';
                      $clr_sts = 'success';
                    } else {
                      $sts_krj = 'Telah Berakhir';
                      $clr_sts = 'danger';
                    } 
                    // Get Anggota
                    $anggota_id = $dta['anggota_id'];
                    $anggota = mysqli_query($conn, "SELECT * FROM tb_anggota WHERE id='$anggota_id'");
                    $agt = mysqli_fetch_assoc($anggota); ?>
                    <tr>
                      <td><?= $no ?></td>
                      <td><?= $agt['nama'] ?></td>
                      <td><?= $dta['formulir'] ?></td>
                      <td><?= $dta['nomor_tiang'] ?></td>
                      <td>
                        <?= date('d M y', strtotime($dta['tggl_mulai'])).' - '.date('d M y', strtotime($dta['tggl_selesai'])) ?> (<?= $priode ?>)
                      </td>
                      <td><?= $dta['lokasi'] ?></td>
                      <td><?= $total_kegiatan ?> Kegiatan</td>
                      <td>
                        <span class="label label-table label-<?= $clr_sts ?>"><?= $sts_krj ?></span>
                      </td>
                      <td class="text-center">
                        <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-detail<?= $dta['id'] ?>" data-toggle1="tooltip" title="" data-original-title="Tampilkan Detail Pengerjaan"><i class="fa fa-list"></i></a>
                        <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-hapus<?= $dta['id'] ?>" data-toggle1="tooltip" title="" data-original-title="Hapus Data Pengerjaan"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>
                    <?php $no=$no+1; } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php foreach ($pngrjaan as $dta) { 
    // Priode
    $tggl_mulai = new DateTime($dta['tggl_mulai']);
    $tggl_selesai = new DateTime($dta['tggl_selesai']);
    $tggl = $tggl_mulai->diff($tggl_selesai)->days;
    if ($tggl >= 25) $priode = 'Bulanan';
    else if ($tggl >= 6) $priode = 'Mingguan';
    else if ($tggl >= 0) $priode = 'Harian';
    // Total Kegiatan
    $pengerjaan_id = $dta['id'];
    $kegiatan = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE pengerjaan_id='$pengerjaan_id'");
    $total_kegiatan = mysqli_num_rows($kegiatan); 
    // Get Anggota
    $anggota_id = $dta['anggota_id'];
    $anggota = mysqli_query($conn, "SELECT * FROM tb_anggota WHERE id='$anggota_id'");
    $agt = mysqli_fetch_assoc($anggota); ?>

    <!-- modal detail -->
    <div class="modal" id="modal-detail<?= $dta['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title" id="myLargeModalLabel">Detail Pengerjan</h4>
          </div>
          <div class="modal-body" style="padding: 0px 20px 0 20px">
            <h4><b>Data Pekerja/Anggota</b></h4>
            <dl class="row mb-0">
              <div class="col-sm-2">
                <img height="80" width="80" src="../assets/images/anggota/<?= $agt['foto'] ?>" class="img-circle">
              </div>
              <div class="col-sm-10">
                <dt class="col-sm-3">Nama Lengkap</dt>
                <dd class="col-sm-9">: <?= $agt['nama'] ?></dd> 

                <dt class="col-sm-3">NIP</dt>
                <dd class="col-sm-9">: <?= $agt['nip'] ?></dd> 

                <dt class="col-sm-3">Telepon</dt>
                <dd class="col-sm-9">: <?= $agt['telepon'] ?></dd> 
              </div>
            </dl>
            <hr>
            <h4><b>Data Pengerjaan</b></h4>
            <dl class="row mb-0">
              <div class="col-sm-6">
                <dt class="col-sm-5">Unit Pengerjaan</dt>
                <dd class="col-sm-7">: <?= $dta['unit'] ?></dd> 

                <dt class="col-sm-5">Gardu Induk</dt>
                <dd class="col-sm-7">: <?= $dta['gardu_induk'] ?></dd> 

                <dt class="col-sm-5">Komponen</dt>
                <dd class="col-sm-7">: <?= $dta['formulir'] ?></dd> 

                <dt class="col-sm-5">Nomor Tiang</dt>
                <dd class="col-sm-7">: <?= $dta['nomor_tiang'] ?></dd> 
              </div>
              <div class="col-sm-6">
                <dt class="col-sm-5">Waktu Pengerjaan</dt>
                <dd class="col-sm-7">: 
                  <?= date('d M y', strtotime($dta['tggl_mulai'])).' - '.date('d M y', strtotime($dta['tggl_selesai'])) ?> (<?= $priode ?>)
                </dd>

                <dt class="col-sm-5">Lokasi</dt>
                <dd class="col-sm-7">: <?= $dta['lokasi'] ?></dd> 

                <dt class="col-sm-5">Total Kegiatan</dt>
                <dd class="col-sm-7">: <?= $total_kegiatan ?> Kegiatan</dd> 

                <dt class="col-sm-5">Keterangan</dt>
                <dd class="col-sm-7">: <?= $dta['keterangan'] ?></dd> 
              </div>
            </dl>
            <hr>
            <h4 class="m-b-30"><b>Data Kegiatan</b></h4>
            <table class="table table-bordered" id="tableDetail" style="margin-top: -15px; font-size: 13px;">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Sasaran</th>
                  <th>Waktu Mulai</th>
                  <th>Waktu Selesai</th>
                  <th>Durasi</th>
                  <th>Total Kerusakan</th>
                  <th>Status</th>
                  <th width="100">Rating</th>
                </tr>
              </thead>
              <tbody>
                <?php $no=1; foreach ($kegiatan as $kgt) { 
                  // Rating
                  $kegiatan_id = $kgt['id'];
                  $rating = mysqli_query($conn, "SELECT * FROM tb_rating WHERE kegiatan_id='$kegiatan_id'");
                  $rat = mysqli_fetch_assoc($rating); 
                  if ($rat) $jum_rating = $rat['rating'];
                  else $jum_rating = 0; 
                  // Status
                  if ($kgt['status'] == 'new') {
                    $status = 'Kegiatan Baru';
                    $color = 'primary';
                    $ket = 'Belum ada rating';
                  } else if ($kgt['status'] == 'proccess') {
                    $status = 'Sedang Diproses';
                    $color = 'info';
                    $ket = 'Belum ada rating';
                  } else if ($kgt['status'] == 'accept') {
                    $status = 'Selesai Diproses';
                    $color = 'success';
                    $ket = $rat['keterangan'];
                  } else if ($kgt['status'] == 'refuse') {
                    $status = 'Ditolak';
                    $color = 'danger';
                    $ket = $rat['keterangan'];
                  }
                  ?>
                  <tr>
                    <td><?= $no ?></td>
                    <td><?= $kgt['sasaran'] ?></td>
                    <td><?= $kgt['waktu_mulai'] ?></td>
                    <td>
                      <?= $kgt['waktu_selesai'] ? $kgt['waktu_selesai'] : '-' ?>
                    </td>
                    <td>
                      <?= $kgt['durasi'] ? $kgt['durasi'] : '-' ?>
                    </td>
                    <td><?= $kgt['total_kerusakan'] ?></td>
                    <td class="text-center" style="">
                      <span class="label label-table label-<?= $color ?>"><?= $status ?></span>
                    </td>
                    <td>
                      <a href="#" data-toggle1="tooltip" title="" data-original-title="<?= $ket ?>">
                        <?php for ($i=1; $i <=5 ; $i++) { 
                          if ($i <= $jum_rating) { ?>
                            <i class="fa fa-star text-warning"></i>
                          <?php } else { ?>
                            <i class="ti-star text-dark"></i>
                          <?php }
                        } ?>
                      </a>
                    </td>
                  </tr>
                  <?php $no=$no+1; } 
                  if ($no == 1) { ?>
                    <tr>
                      <td colspan="8" class="text-center"><i>Tidak ada kegiatan</i></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">Tutup</button>
            </div>
          </div>
        </div>
      </div>

      <!-- modal hapus -->
      <div class="modal modal-hapus" id="modal-hapus<?= $dta['id'] ?>" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Yakin ingin menghapus?</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              Semua data kegiatan di pengerjaan ini akan ikut terhapus! (<?= $total_kegiatan ?> Kegiatan)
            </div>
            <div class="modal-footer">
              <a href="data-pengerjaan.php?delete=<?= $dta['id'] ?>" role="button" class="btn btn-danger">Hapus</a>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
          </div>
        </div>
      </div>
    <?php } 
    require('template/footer.php');
    ?>

    <script type="text/javascript">
      $(document).ready(function($) {
        <?php if (isset($response) && $response['status'] == 'success') { ?>
          Swal.fire({
            title: 'Berhasil Diproses',
            text: "<?= $response['message'] ?>",
            type: 'success'
          }).then(function() {
            location.href = 'kegiatan-baru.php';
          });
        <?php } else if (isset($response) && $response['status'] == 'error') { ?>
          Swal.fire({
            title: 'Terjadi Kesalahan',
            text: "<?= $response['message'] ?>",
            type: 'error'
          });
        <?php } else if (isset($response) && $response['status'] == 'delete') { ?>
          Swal.fire({
            title: 'Berhasil Dihapus',
            text: "<?= $response['message'] ?>",
            type: 'success'
          });
          window.history.pushState('', '', "data-pengerjaan.php")
        <?php } ?>
      });
    </script>