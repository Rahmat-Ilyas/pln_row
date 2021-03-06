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

// Tambah Kegiatan
if (isset($_POST['addKegiatan'])) {
  $_POST['status'] = 'new';
  $res = add_data('tb_kegiatan', $_POST);
}

// Update Pengerjaan
if (isset($_POST['subUpdate'])) {
  $id = $_POST['id'];
  $formulir = $_POST['formulir'];
  $nomor_tiang = $_POST['nomor_tiang'];
  $tggl_selesai = $_POST['tggl_selesai'];
  $keterangan = $_POST['keterangan'];

  $query = "UPDATE tb_pengerjaan SET formulir='$formulir', nomor_tiang='$nomor_tiang', tggl_selesai='$tggl_selesai', keterangan='$keterangan' WHERE id='$id'";
  if (mysqli_query($conn, $query)) {
    $res = [
      'status' => 'update',
      'message' => 'Data berhasil di diperbaharui',
    ];
  } else { 
    $res = [
      'status' => 'error',
      'message' => mysqli_error($conn),
    ];
  }
}

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

    $res = [
      'status' => 'delete',
      'message' => 'Data berhasil di hapus',
    ];
  } else { 
    $res = [
      'status' => 'error',
      'message' => mysqli_error($conn),
    ];
  }
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
          <h4 class="m-t-0 header-title"><b>Data Pengerjaan (Periode: <?= date('d M y', strtotime($prd['tanggal_mulai']))?> - <?= date('d M y', strtotime($prd['tanggal_akhir'])) ?>)</b></h4>
          <hr>
          <div class="row">
            <?php $no = 1; foreach ($result as $dta) {
              // Periode
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
                        <b class="col-sm-4">Waktu/Periode </b>
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
                        <div class="col-md-4 m-b-10">
                          <?php if ($sts_krj == 'Telah Berakhir') { ?>
                            <a href="#" class="btn btn-sm btn-block btn-success kegiatan-exp" data-toggle1="tooltip" title="" data-original-title="Tambah Kegiatan Pengerjaan"><i class="md-my-library-add"></i> Tambah Kegiatan</a>
                          <?php } else { ?>
                            <a href="#" class="btn btn-sm btn-block btn-success add-kegiatan" data-toggle="modal" data-target="#modal-add-kegiatan" data-toggle1="tooltip" title="" data-original-title="Tambah Kegiatan Pengerjaan" data-id="<?= $dta['id'] ?>"><i class="md-my-library-add"></i> Tambah Kegiatan</a>
                          <?php } ?>
                        </div>                       
                        <div class="col-md-4">
                          <a href="#" class="btn btn-sm btn-block btn-info" data-toggle="modal" data-target="#modal-detail<?= $dta['id'] ?>" data-toggle1="tooltip" title="" data-original-title="Detail Pengerjaan"><i class="md-assignment"></i> Detail Pengerjaan</a>
                        </div>
                        <div class="col-md-4">
                          <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-wrench"></i> Proses Pengerjaan<span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                              <li><a href="#" data-toggle="modal" data-target="#modal-edit<?= $dta['id'] ?>"><i class="fa fa-edit"></i> Update</a></li>
                              <li><a href="#" data-toggle="modal" data-target="#modal-hapus<?= $dta['id'] ?>" > <i class="fa fa-trash"></i> Hapus</a></li>
                            </ul>
                          </div>
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
            <hr>
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
    // Periode
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

    <!-- modal edit -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-edit<?= $dta['id'] ?>">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Update Data Pengerjaan</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="padding: 20px 50px 0 50px">
            <form method="POST">
              <div class="form-group">
                <label class="col-form-label">Formulir/Komponen</label>
                <input type="hidden" name="id" value="<?= $dta['id'] ?>">
                <input type="text" name="formulir" class="form-control" placeholder="Formulir/Komponen Pengerjaan.." required="" value="<?= $dta['formulir'] ?>">
              </div>
              <div class="form-group">
                <label class="col-form-label">Nomor Tiang</label>
                <input type="text" name="nomor_tiang" class="form-control" placeholder="Nomor Tiang.." required="" value="<?= $dta['nomor_tiang'] ?>">
              </div>
              <div class="form-group">
                <label class="col-form-label">Tanggal Selesai</label>
                <input type="date" name="tggl_selesai" class="form-control" placeholder="Tanggal Selesai.." required="" value="<?= date('Y-m-d', strtotime($dta['tggl_selesai'])) ?>">
              </div>
              <div class="form-group">
                <label class="col-form-label">Keterangan</label>
                <textarea class="form-control" required="" placeholder="Sasaran Pemeriksaan..." name="keterangan" rows="5"><?= $dta['keterangan'] ?></textarea>
              </div>
              <div class="form-group">
                <button type="submit" name="subUpdate" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              </div>
            </form>
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
        <?php } else if (isset($res) && $res['status'] == 'delete') { ?>
          Swal.fire({
            title: 'Berhasil Dihapus',
            text: "<?= $res['message'] ?>",
            type: 'success'
          }).then(function() {
            location.href = 'data-pengerjaan.php';
          });
        <?php } else if (isset($res) && $res['status'] == 'update') { ?>
          Swal.fire({
            title: 'Berhasil Diupdate',
            text: "<?= $res['message'] ?>",
            type: 'success'
          }).then(function() {
            location.href = 'data-pengerjaan.php';
          });
        <?php } ?>
      });
    </script>