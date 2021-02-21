<?php 
require('template/header.php');

$result = mysqli_query($conn, "SELECT * FROM tb_priode_laporan ORDER BY id DESC");
$prd = mysqli_fetch_assoc($result);

$pengerjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan");
$priode_mulai = strtotime($prd['tanggal_mulai']);
$priode_akhir = strtotime($prd['tanggal_akhir']);
$data_pgrjaan = [];
foreach ($pengerjaan as $pgr) {
  $pgr_mulai = strtotime($pgr['tggl_mulai']);
  $pgr_selesai = strtotime($pgr['tggl_selesai']);
  if ($priode_mulai < $pgr_mulai && $priode_akhir > $pgr_mulai || $priode_mulai < $pgr_selesai && $priode_akhir > $pgr_selesai) {
    $data_pgrjaan[] = $pgr;
  }
}
?>

<div class="row">
  <div class="col-sm-12">
    <!-- Page-Title -->
    <div class="row">
      <div class="col-sm-12">
        <h4 class="page-title">Riwayat Pengerjaan</h4>
        <ol class="breadcrumb">
          <li>
            <a href="#">Tim ROW</a>
          </li>
          <li class="active">
            Riwayat Pengerjaan
          </li>
        </ol>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card-box">
          <h4 class="m-t-0 header-title"><b>Data Riwayat Penerjaan & Kegiatan</b></h4>
          <hr>
          <div class="panel-group" id="accordion-test-2"> 
            <?php $no=1; foreach ($result as $res) { 
              if ($res['id'] != $prd['id']) { ?>
                <div class="panel panel-default"> 
                  <div class="panel-heading"> 
                    <h4 class="panel-title"> 
                      <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseOne<?= $res['id'] ?>" aria-expanded="false" class="collapsed">
                        <?= $no ?>. PRIODE LAPORAN: <?= date('d M Y', strtotime($res['tanggal_mulai'])).' - '.date('d M Y', strtotime($res['tanggal_akhir'])) ?>
                      </a> 
                    </h4> 
                  </div> 
                  <div id="collapseOne<?= $res['id'] ?>" class="panel-collapse collapse <?php if ($no == 1) echo 'in' ?>"> 
                    <div class="panel-body">
                      <h4 style="margin-top: -15px;"><u>Data Pengerjaan</u></h4>
                      <table class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th width="10">No</th>
                            <th>Komponen</th>
                            <th>Nomoe Tiang</th>
                            <th>Waktu/Priode</th>
                            <th>Lokasi</th>
                            <th>Total Kegiatan</th>
                            <th>Keterangan</th>
                            <th>Detail</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                          $pengerjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan");
                          $x = 1;
                          $jum_pengerjaan = 0;
                          $jum_kegiatan = 0;
                          $priode_mulai = strtotime($res['tanggal_mulai']);
                          $priode_akhir = strtotime($res['tanggal_akhir']);
                          foreach ($pengerjaan as $pgr) {
                            $pgr_mulai = strtotime($pgr['tggl_mulai']);
                            $pgr_selesai = strtotime($pgr['tggl_selesai']);
                            if ($priode_mulai < $pgr_mulai && $priode_akhir > $pgr_mulai || $priode_mulai < $pgr_selesai && $priode_akhir > $pgr_selesai) { 
                              // Priode
                              $tggl_mulai = new DateTime($pgr['tggl_mulai']);
                              $tggl_selesai = new DateTime($pgr['tggl_selesai']);
                              $tggl = $tggl_mulai->diff($tggl_selesai)->days;
                              if ($tggl >= 25) $priode = 'Bulanan';
                              else if ($tggl >= 6) $priode = 'Mingguan';
                              else if ($tggl >= 0) $priode = 'Harian';
                              // Total Kegiatan
                              $pengerjaan_id = $pgr['id'];
                              $kegiatan = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE pengerjaan_id='$pengerjaan_id'");
                              $total_kegiatan = mysqli_num_rows($kegiatan); ?>
                              <tr>
                                <td><?= $x ?></td>
                                <td><?= $pgr['formulir'] ?></td>
                                <td><?= $pgr['nomor_tiang'] ?></td>
                                <td>
                                  <?= date('d M', strtotime($pgr['tggl_mulai'])).' - '.date('d M', strtotime($pgr['tggl_selesai'])) ?> (<?= $priode ?>)
                                </td>
                                <td><?= $pgr['lokasi'] ?></td>
                                <td><?= $total_kegiatan ?> Kegiatan</td>
                                <td><?= $pgr['keterangan'] ?></td>
                                <td>
                                  <a href="#" class="btn btn-sm btn-block btn-info" data-toggle="modal" data-target="#modal-detail<?= $pgr['id'] ?>" data-toggle1="tooltip" title="" data-original-title="Detail Pengerjaan"><i class="md-assignment"></i> Detail Pengerjaan</a>
                                </td>
                              </tr>
                              <?php $x = $x + 1; 
                            }
                          } 
                          if ($x == 1) { ?>
                            <tr>
                              <td colspan="8" class="text-center"><i>Tidak ada pengerjaan</i></td>
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div> 
                  </div> 
                </div>
                <?php $no = $no + 1; 
              } 
            } ?>
          </div> 
        </div>
      </div>
    </div>
  </div>
</div>

<?php foreach ($pengerjaan as $dta) { 
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
  $total_kegiatan = mysqli_num_rows($kegiatan); ?>

  <!-- modal detail -->
  <div class="modal" id="modal-detail<?= $dta['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="myLargeModalLabel">Detail Pengerjan</h4>
        </div>
        <div class="modal-body" style="padding: 20px 20px 0 20px">
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
                <?= date('d M', strtotime($dta['tggl_mulai'])).' - '.date('d M', strtotime($dta['tggl_selesai'])) ?> (<?= $priode ?>)
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
        </div>
      </div>
    </div>
  <?php }
  require('template/footer.php');
  ?>

