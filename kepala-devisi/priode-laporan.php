<?php 
require('template/header.php');
/*
NOTE: 
* jika priode mulai lbh kecil dri tggl mulai dan priode akhir lebih besar tanggal mulai.
exp: priode = 1 - 5 pgrjaan = 2 - 3 [] 1<2 && 5>2 || 1<3 && 5>3

* Falidasi
-Jika tggl akhir priode old > tggl input = falid
-Jika input tggl awal > input tggl akhir = falid
-Jika input tggl akhir > input tggl store = falid
-Jika update priode dan tggl akhir priode > tanggal sekarang = warning
exp: jika tggl akhir priode 2, tggl mulai input 1 = falid
*/

$result = mysqli_query($conn, "SELECT * FROM tb_priode_laporan ORDER BY id DESC");
$prd = mysqli_fetch_assoc($result);

$pengerjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan");
$jum_pengerjaan = 0;
$jum_kegiatan = 0;
$priode_mulai = strtotime($prd['tanggal_mulai']);
$priode_akhir = strtotime($prd['tanggal_akhir']);
foreach ($pengerjaan as $pgr) {
  $pgr_mulai = strtotime($pgr['tggl_mulai']);
  $pgr_selesai = strtotime($pgr['tggl_selesai']);
  if ($priode_mulai < $pgr_mulai && $priode_akhir > $pgr_mulai || $priode_mulai < $pgr_selesai && $priode_akhir > $pgr_selesai) {
    $jum_pengerjaan = $jum_pengerjaan + 1;
  }
}

$kegiatan = mysqli_query($conn, "SELECT * FROM tb_kegiatan");
foreach ($kegiatan as $kgt) {
  $kgt_mulai = strtotime($kgt['waktu_mulai']);
  $kgt_selesai = strtotime($kgt['waktu_selesai']);
  if ($priode_mulai < $kgt_mulai && $priode_akhir > $kgt_mulai || $priode_mulai < $kgt_selesai && $priode_akhir > $kgt_selesai) {
    $jum_kegiatan = $jum_kegiatan + 1;
  }
}

if (isset($_POST['updtPriode'])) {
  $tanggal_mulai = $_POST['tanggal_mulai'];
  $tanggal_akhir = $_POST['tanggal_akhir'];
  $tanggal_stor = $_POST['tanggal_stor'];

  mysqli_query($conn, "INSERT INTO tb_priode_laporan VALUES (NULL, '$tanggal_mulai', '$tanggal_akhir', '$tanggal_stor')");

  if (mysqli_affected_rows($conn) > 0) {
    $response = [
      'status' => 'success',
      'message' => 'Priode laporan berhasil di update',
    ];    
  } else { 
    $response = [
      'status' => 'error',
      'message' => mysqli_error($conn),
    ];
  }
}
?>
<div class="content">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <!-- Page-Title -->
        <div class="row">
          <div class="col-sm-12">
            <h4 class="page-title">Priode Laporan</h4>
            <ol class="breadcrumb">
              <li>
                <a href="#">Kepala Devisi</a>
              </li>
              <li class="active">
                Priode Laporan
              </li>
            </ol>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="card-box table-responsive">
              <div class="row">
                <div class="col-lg-5">
                  <div class="panel panel-border panel-primary">
                    <div class="panel-heading">
                      <h3 class="panel-title">Priode Saat Ini</h3>
                    </div>
                    <div class="panel-body row">
                      <b class="col-sm-5">Tanggal Mulai</b>
                      <span class="col-sm-7">: <?= date('d F Y', strtotime($prd['tanggal_mulai'])) ?></span>
                      <b class="col-sm-5">Tanggal Akhir</b>
                      <span class="col-sm-7">: <?= date('d F Y', strtotime($prd['tanggal_akhir'])) ?></span>
                      <b class="col-sm-5">Tanggal Store</b>
                      <span class="col-sm-7">: <?= date('d F Y', strtotime($prd['tanggal_stor'])) ?></span>
                      <b class="col-sm-5">Jumlah Pengerjaan</b>
                      <span class="col-sm-7">: <?= $jum_pengerjaan ?> Pengerjaan</span>
                      <b class="col-sm-5">Jumlah Kegiatan</b>
                      <span class="col-sm-7">: <?= $jum_kegiatan ?> Kegiatan</span>
                      <div class="col-sm-12 m-t-10">
                        <button class="btn btn-primary" id="btn-update"><i class="fa fa-calendar"></i> Perbaharui Priode Laporan</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <h4 class="m-t-0 header-title"><b>Data Priode Sebelumnya</b></h4>
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th width="10">No</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Akhir</th>
                    <th>Tanggal Store</th>
                    <th>Total Pengerjaan</th>
                    <th>Total Kegiatan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no=1; foreach ($result as $res) {
                    if ($res['id'] != $prd['id']) { 
                      $pengerjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan");
                      $jum_pengerjaan = 0;
                      $jum_kegiatan = 0;
                      $priode_mulai = strtotime($res['tanggal_mulai']);
                      $priode_akhir = strtotime($res['tanggal_akhir']);
                      foreach ($pengerjaan as $pgr) {
                        $pgr_mulai = strtotime($pgr['tggl_mulai']);
                        $pgr_selesai = strtotime($pgr['tggl_selesai']);
                        if ($priode_mulai < $pgr_mulai && $priode_akhir > $pgr_mulai || $priode_mulai < $pgr_selesai && $priode_akhir > $pgr_selesai) {
                          $jum_pengerjaan = $jum_pengerjaan + 1;
                        }
                      }

                      $kegiatan = mysqli_query($conn, "SELECT * FROM tb_kegiatan");
                      foreach ($kegiatan as $kgt) {
                        $kgt_mulai = strtotime($kgt['waktu_mulai']);
                        $kgt_selesai = strtotime($kgt['waktu_selesai']);
                        if ($priode_mulai < $kgt_mulai && $priode_akhir > $kgt_mulai || $priode_mulai < $kgt_selesai && $priode_akhir > $kgt_selesai) {
                          $jum_kegiatan = $jum_kegiatan + 1;
                        }
                      } ?>
                      <tr>
                        <td><?= $no ?></td>
                        <td>
                          <?= date('d/m/Y', strtotime($res['tanggal_mulai'])) ?>
                        </td>
                        <td>
                          <?= date('d/m/Y', strtotime($res['tanggal_akhir'])) ?>
                        </td>
                        <td>
                          <?= date('d/m/Y', strtotime($res['tanggal_stor'])) ?>
                        </td>
                        <td><?= $jum_pengerjaan ?> Pengerjaan</td>
                        <td><?= $jum_kegiatan ?> Kegiatan</td>
                      </tr>
                      <?php $no=$no+1; 
                    } 
                  } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- modal update priode -->
<div class="modal" id="updt-priode" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="myLargeModalLabel">Update Priode Laporan</h4>
      </div>
      <div class="modal-body" style="padding: 20px 50px 0 50px">
        <form method="POST" action="#">
          <div class="form-group">
            <label class="col-form-label">Tanggal Mulai</label>
            <input type="date" class="form-control" id="tanggal_mulai" required="" name="tanggal_mulai" value="<?= date('Y-m-d') ?>">
          </div>
          <div class="form-group">
            <label class="col-form-label">Tanggal Akhir</label>
            <input type="date" class="form-control" id="tanggal_akhir" required="" name="tanggal_akhir">
          </div>
          <div class="form-group">
            <label class="col-form-label">Tanggal Stor Laporan</label>
            <input type="date" class="form-control" id="tanggal_stor" required="" name="tanggal_stor">
          </div>
          <div class="form-group">
            <button type="submit" name="updtPriode" class="btn btn-default">Simpan</button>
            <button type="" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php 
require('template/footer.php');
?>

<script type="text/javascript">
  $(document).ready(function($) {
    var cek;
    $('#btn-update').click(function(e) {
      cek = false;
      e.preventDefault();
      var thisDate = "<?= strtotime(date('Y-m-d')) ?>";
      var priodeLast = "<?= strtotime(date('Y-m-d', strtotime($prd['tanggal_akhir']))) ?>";
      if (priodeLast > thisDate) {
        Swal.fire({
          title: 'Priode Sebelumnya Belum Berakhir!',
          text: "Priode sebelumnya belum berakhir. Jika tetap melanjutak maka tanggal akhir untuk priode sebelumnya akan otomatis di update menjadi tanggal saat priode di update.",
          type: 'warning',
          showCancelButton: true,
          cancelButtonText: 'Batal',
          confirmButtonText: 'Lanjutkan',
          preConfirm: () => {
            $('#updt-priode').modal('show');
            cek = true;
          }
        });
      } else {
        $('#updt-priode').modal('show');
      }
    });

    // Falidasi Tanggal
    $('#tanggal_mulai').change(function() {
      var tn = "<?= date('Y-m-d', strtotime($prd['tanggal_akhir'])) ?>";
      if (cek == true) tn = "<?= date('Y-m-d') ?>";
      var tm = $('#tanggal_mulai').val();
      if (tn > tm) {
        Swal.fire({
          title: 'Tanggal Falid',
          text: 'Pastikan tanggal yang di pilih tidak melebihi tanggal priode sebelumnya!',
          type: 'error'
        });
        $('#tanggal_mulai').val("<?= date('Y-m-d') ?>");
      }
    });
    $('#tanggal_akhir').change(function() {
      var tm = $('#tanggal_mulai').val();
      var ta = $('#tanggal_akhir').val();
      if (tm > ta) {
        Swal.fire({
          title: 'Tanggal Falid',
          text: 'Tanggal akhir tidak boleh kurang dari tanggal mulai!',
          type: 'error'
        });
        $('#tanggal_akhir').val('');
      }
    });
    $('#tanggal_stor').change(function() {
      var ta = $('#tanggal_akhir').val();
      var ts = $('#tanggal_stor').val();
      if (ta == ''){
        Swal.fire({
          title: 'Input Tanggal Akhir',
          text: 'Pastikan input tanggal akhir terlebih dahulu!',
          type: 'warning'
        });
        $('#tanggal_stor').val('')
      }
      if (ta > ts) {
        Swal.fire({
          title: 'Tanggal Falid',
          text: 'Tanggal stor tidak boleh kurang dari tanggal akhir!',
          type: 'error'
        });
        $('#tanggal_stor').val('');
      }
    });

    <?php if (isset($response) && $response['status'] == 'success') { ?>
      Swal.fire({
        title: 'Berhasil Ditambah',
        text: "<?= $response['message'] ?>",
        type: 'success'
      }).then(function() {
        location.href = 'priode-laporan.php';
      });
    <?php } else if (isset($response) && $response['status'] == 'error') { ?>
      Swal.fire({
        title: 'Terjadi Kesalahan',
        text: "<?= $response['message'] ?>",
        type: 'error'
      });
    <?php } ?>
  });
</script>