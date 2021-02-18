<?php 
require('template/header.php');
$priode = mysqli_query($conn, "SELECT * FROM tb_priode_laporan ORDER BY id DESC");
$prd = mysqli_fetch_assoc($priode);

$pngrjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan WHERE anggota_id='$anggota_id' ORDER BY id DESC");

$priode_mulai = strtotime($prd['tanggal_mulai']);
$priode_akhir = strtotime($prd['tanggal_akhir']);
$result = [];
foreach ($pngrjaan as $pgr) {
  $pgr_mulai = strtotime($pgr['tggl_mulai']);
  $pgr_selesai = strtotime($pgr['tggl_selesai']);
  if ($priode_mulai < $pgr_mulai && $priode_akhir > $pgr_mulai || $priode_mulai < $pgr_selesai && $priode_akhir > $pgr_selesai) {
    $result[] = $pgr;
  }
}

if (isset($_POST['addKegiatan'])) {
  $_POST['status'] = 'new';
  $res = add_data('tb_kegiatan', $_POST);
}
?>

<div class="row">
  <div class="col-sm-12">
    <!-- Page-Title -->
    <div class="row">
      <div class="col-sm-12">
        <h4 class="page-title">Data Pengerjaan</h4>
        <ol class="breadcrumb">
          <li>
            <a href="#">Pengerjaan</a>
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
          <h4 class="m-t-0 header-title"><b>Data Pengerjaan (Priode: <?= date('d M y', strtotime($prd['tanggal_mulai']))?> - <?= date('d M y', strtotime($prd['tanggal_akhir'])) ?>)</b></h4>
          <hr>

          <div class="row">
            <?php $no = 1; foreach ($result as $dta) {
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
              } ?>
              <div class="col-lg-6">
                <div class="portlet">
                  <div class="portlet-heading bg-primary">
                    <h3 class="portlet-title">
                      Data Pengerjaan <?= $no ?>
                    </h3>
                    <div class="portlet-widgets">
                      <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                      <span class="divider"></span>
                      <a data-toggle="collapse" data-parent="#accordion1" href="#bg-primary<?= $no ?>"><i class="ion-minus-round"></i></a>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <div id="bg-primary<?= $no ?>" class="panel-collapse collapse in">
                    <div class="portlet-body">
                      <div class="row">
                        <b class="col-sm-4">Komponen </b>
                        <span class="col-sm-8">: 
                          <span id="get-formulir"><?= $dta['formulir'] ?></span>
                        </span>
                      </div>
                      <div class="row">
                        <b class="col-sm-4">Nomor Tiang </b>
                        <span class="col-sm-8">: 
                          <span id="get-tiang"><?= $dta['nomor_tiang'] ?></span>
                        </span>
                      </div>
                      <div class="row">
                        <b class="col-sm-4">Waktu/Priode </b>
                        <span class="col-sm-8">: <?= date('d M', strtotime($dta['tggl_mulai'])).' - '.date('d M', strtotime($dta['tggl_selesai'])) ?> (<?= $priode ?>)</span>
                      </div>
                      <div class="row">
                        <b class="col-sm-4">Lokasi </b>
                        <span class="col-sm-8">: <?= $dta['lokasi'] ?></span>
                      </div>
                      <div class="row">
                        <b class="col-sm-4">Total Kegiatan </b>
                        <span class="col-sm-8">: <?= $total_kegiatan ?> Kegiatan</span>
                      </div>
                      <div class="row">
                        <b class="col-sm-4">Keterangan </b>
                        <span class="col-sm-8">: <?= $dta['keterangan'] ?></span>
                      </div>
                      <div class="row">
                        <b class="col-sm-4">Sataus </b>
                        <span class="col-sm-8">: <span class="label label-table label-<?= $clr_sts ?>"><?= $sts_krj ?></span></span>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-md-6 m-b-10">
                          <?php if ($sts_krj == 'Telah Berakhir') { ?>
                            <a href="#" class="btn btn-sm btn-block btn-success kegiatan-exp" data-toggle1="tooltip" title="" data-original-title="Tambah Kegiatan Pengerjaan"><i class="md-my-library-add"></i> Tambah Kegiatan</a>
                          <?php } else { ?>
                            <a href="#" class="btn btn-sm btn-block btn-success add-kegiatan" data-toggle="modal" data-target="#modal-add-kegiatan" data-toggle1="tooltip" title="" data-original-title="Tambah Kegiatan Pengerjaan" data-id="<?= $dta['id'] ?>"><i class="md-my-library-add"></i> Tambah Kegiatan</a>
                          <?php } ?>
                        </div>                       
                        <div class="col-md-6">
                          <a href="#" class="btn btn-sm btn-block btn-info" data-toggle="modal" data-target="#modal-detail<?= $dta['id'] ?>" data-toggle1="tooltip" title="" data-original-title="Detail Pengerjaan"><i class="md-assignment"></i> Detail Pengerjaan</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php $no=$no+1; }
              if ($no == 1) { ?>
                <h4 class="text-center"><i>Tidak ada data</i></h4>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- modal tambah kegiatan -->
  <div class="modal" id="modal-add-kegiatan" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="myLargeModalLabel">Tambah Data Kegiatan</h4>
        </div>
        <div class="modal-body" style="padding: 20px 50px 0 50px">
          <form method="POST" action="#" enctype="multipart/form-data">
            <div class="form-group">
              <label class="col-form-label">Waktu Mulai (Defaut)</label>
              <input type="hidden" name="pengerjaan_id" id="pengerjaan-id">
              <div class="row">
                <div class="col-sm-5">
                  <input type="text" class="form-control" required="" value="<?= date('d/m/Y H:i') ?>" readonly="">
                  <input type="hidden" class="form-control" required="" name="waktu_mulai" id="waktu_mulai">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-form-label">Komponen/Formulir</label>
              <div class="row">
                <div class="col-sm-8 m-b-5">
                  <input type="text" class="form-control" required="" id="set-formulir" readonly="">
                </div>
                <div class="col-sm-4">
                  <input type="text" class="form-control" required="" id="set-tiang" readonly="">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-form-label">Sasaran Pemeriksaan</label>
              <textarea class="form-control" required="" placeholder="Sasaran Pemeriksaan..." name="sasaran" rows="5"></textarea>
            </div>
            <div class="form-group">
              <button type="submit" name="addKegiatan" class="btn btn-default">Simpan</button>
              <button type="" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Batal</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php foreach ($result as $dta) { 
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
                  } else if ($kgt['status'] == 'revuse') {
                    $status = 'Ditolak';
                    $color = 'danger';
                    $ket = 'Laporan kegiatan ditolak, tidak ada rating untuk kegiatan ini';
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
    <script type="text/javascript">
      $(document).ready(function($) {
        $('.add-kegiatan').click(function() {
          var id = $(this).attr('data-id');
          var formulir = $(this).parents('.portlet-body').find('#get-formulir').text();
          var tiang = $(this).parents('.portlet-body').find('#get-tiang').text();

          $('#pengerjaan-id').val(id);
          $('#set-formulir').val(formulir);
          $('#set-tiang').val('Nomor Tiang: '+tiang);

          $('#waktu_mulai').val("<?= date('Y-m-d H:i') ?>");
        });

        $('.kegiatan-exp').click(function(e) {
          Swal.fire({
            title: 'Pengerjaan Telah Berakhir',
            text: "Tidak dapat menambah kegiatan karena priode pengerjaan telah berakhir",
            type: 'warning'
          });
        });

        <?php if (isset($res) && $res['status'] == 'success') { ?>
          Swal.fire({
            title: 'Berhasil Ditambah',
            text: "<?= $res['message'] ?>",
            type: 'success'
          }).then(function() {
            location.href = 'data-pengerjaan.php';
          });
        <?php } else if (isset($res) && $res['status'] == 'error') { ?>
          Swal.fire({
            title: 'Terjadi Kesalahan',
            text: "<?= $res['message'] ?>",
            type: 'error'
          });
        <?php } ?>
      });
    </script>