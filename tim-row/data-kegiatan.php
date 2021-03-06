<?php 
require('template/header.php');
$priode = mysqli_query($conn, "SELECT * FROM tb_priode_laporan ORDER BY id DESC");
$prd = mysqli_fetch_assoc($priode);

$pngrjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan WHERE anggota_id='$anggota_id'");

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

// Update Kegiatan
if (isset($_POST['subUpdate'])) {
  $id = $_POST['id'];
  $sasaran = $_POST['sasaran'];

  $query = "UPDATE tb_kegiatan SET sasaran='$sasaran' WHERE id='$id'";
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

// DELETE KEGIATAN
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $query = "DELETE FROM tb_kegiatan WHERE id='$id'";
  if (mysqli_query($conn, $query)) {
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
        <h4 class="page-title">Data Kegiatan</h4>
        <ol class="breadcrumb">
          <li>
            <a href="#">Kegiatan</a>
          </li>
          <li class="active">
            Data Kegiatan
          </li>
        </ol>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card-box table-responsive">
          <h4 class="m-t-0 header-title"><b>Data Kegiatan (Periode: <?= date('d M y', strtotime($prd['tanggal_mulai']))?> - <?= date('d M y', strtotime($prd['tanggal_akhir'])) ?>)</b></h4>
          <a href="#" class="m-t-10 btn btn-default add-kegiatan" data-toggle="modal" data-target="#modal-add-kegiatan" data-toggle1="tooltip" title="" data-original-title="Tambah Kegiatan Pengerjaan" data-id="<?= $dta['id'] ?>"><i class="md-my-library-add"></i> Tambah Kegiatan</a>

          <hr>

          <ul class="nav nav-tabs tabs">
            <li class="active tab">
              <a href="#home-2" data-toggle="tab" aria-expanded="false"> 
                <span class="visible-xs">
                  <i class="md-new-releases"></i>
                  <?php if ($new_kgt > 0) { ?>
                    <span class="badge badge-danger" id="badge-delivery" style="font-size: 8px; margin-bottom: 15px;"><?= $new_kgt ?></span> 
                  <?php } ?>
                </span> 
                <span class="hidden-xs"><i class="md-new-releases"></i> Kegiatan Baru
                  <?php if ($new_kgt > 0) { ?>
                    <span class="badge badge-danger" id="badge-delivery" style="font-size: 11px; margin-bottom: 10px;"><?= $new_kgt ?></span> 
                  <?php } ?>
                </span>
              </a> 
            </li> 
            <li class="tab"> 
              <a href="#profile-2" data-toggle="tab" aria-expanded="false"> 
                <span class="visible-xs"><i class="md-timelapse"></i></span> 
                <span class="hidden-xs"><i class="md-timelapse"></i> Sedang Diproses</span> 
              </a> 
            </li> 
            <li class="tab"> 
              <a href="#messages-2" data-toggle="tab" aria-expanded="false"> 
                <span class="visible-xs"><i class="md-play-install"></i></span> 
                <span class="hidden-xs"><i class="md-play-install"></i> Selesai Diproses</span> 
              </a> 
            </li>
            <li class="tab"> 
              <a href="#ditolak-2" data-toggle="tab" aria-expanded="false"> 
                <span class="visible-xs"><i class="md-cancel"></i></span> 
                <span class="hidden-xs"><i class="md-cancel"></i> Kegiatan Ditolak</span> 
              </a> 
            </li>
          </ul> 
          <div class="tab-content"> 
            <div class="tab-pane active" id="home-2"> 
              <div class="row">
                <?php $no = 1; foreach ($pngrjaan as $dta) {
                  $pengerjaan_id = $dta['id'];
                  $kegiatan = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE pengerjaan_id='$pengerjaan_id' AND status='new'");
                  foreach ($kegiatan as $kgt) {  ?>
                    <div class="col-lg-6">
                      <div class="portlet">
                        <div class="portlet-heading bg-primary">
                          <h3 class="portlet-title">
                            Data Kegiatan <?= $no ?>
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
                              <span class="col-sm-8">: <?= $dta['formulir'] ?></span>
                            </div>
                            <div class="row">
                              <b class="col-sm-4">Nomor Tiang </b>
                              <span class="col-sm-8">: <?= $dta['nomor_tiang'] ?></span>
                            </div>
                            <div class="row">
                              <b class="col-sm-4">Lokasi </b>
                              <span class="col-sm-8">: <?= $dta['lokasi'] ?></span>
                            </div>
                            <div class="row">
                              <b class="col-sm-4">Keterangan </b>
                              <span class="col-sm-8">: <?= $dta['keterangan'] ?></span>
                            </div>
                            <div class="row">
                              <b class="col-sm-4">Sasaran </b>
                              <span class="col-sm-8">: <?= $kgt['sasaran'] ?></span>
                            </div>
                            <div class="row">
                              <b class="col-sm-4">Waktu Mulai</b>
                              <span class="col-sm-8">: <?= date('d/m/y H:i', strtotime($kgt['waktu_mulai'])) ?></span>
                            </div>
                            <hr>
                            <a href="#" class="btn btn-success" id="btn-upload" data-toggle="modal" data-target="#modal-upload" data-toggle1="tooltip" title="" data-original-title="Upload Foto Kegiatan Untuk Menyelesaikan" data-id="<?= $kgt['id'] ?>"><i class="md-camera-alt"></i> Upload Foto & Selesaikan</a>
                            <div class="btn-group">
                              <button type="button" class="btn btn-primary dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-wrench"></i> Proses Kegiatan<span class="caret"></span></button>
                              <ul class="dropdown-menu" role="menu">
                                <li><a href="#" data-toggle="modal" data-target="#modal-edit<?= $kgt['id'] ?>"><i class="fa fa-edit"></i> Update</a></li>
                                <li><a href="#" data-toggle="modal" data-target="#modal-hapus<?= $kgt['id'] ?>" > <i class="fa fa-trash"></i> Hapus</a></li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php $no=$no+1; }
                  }
                  if ($no == 1) { ?>
                    <h4 class="text-center"><i>Tidak ada data</i></h4>
                  <?php } ?>
                </div>
              </div> 
              <div class="tab-pane" id="profile-2">
                <div class="row">
                  <?php $no = 1; foreach ($result as $dta) {
                    $pengerjaan_id = $dta['id'];
                    $kegiatan = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE pengerjaan_id='$pengerjaan_id' AND status='proccess'");
                    foreach ($kegiatan as $kgt) {  ?>
                      <div class="col-lg-6">
                        <div class="portlet">
                          <div class="portlet-heading bg-purple">
                            <h3 class="portlet-title">
                              Data Kegiatan <?= $no ?>
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
                                <span class="col-sm-8">: <?= $dta['formulir'] ?></span>
                              </div>
                              <div class="row">
                                <b class="col-sm-4">Nomor Tiang </b>
                                <span class="col-sm-8">: <?= $dta['nomor_tiang'] ?></span>
                              </div>
                              <div class="row">
                                <b class="col-sm-4">Lokasi </b>
                                <span class="col-sm-8">: <?= $dta['lokasi'] ?></span>
                              </div>
                              <div class="row">
                                <b class="col-sm-4">Keterangan </b>
                                <span class="col-sm-8">: <?= $dta['keterangan'] ?></span>
                              </div>
                              <div class="row">
                                <b class="col-sm-4">Sasaran </b>
                                <span class="col-sm-8">: <?= $kgt['sasaran'] ?></span>
                              </div>
                              <div class="row">
                                <b class="col-sm-4">Waktu Mulai</b>
                                <span class="col-sm-8">: <?= date('d/m/y H:i', strtotime($kgt['waktu_mulai'])) ?></span>
                              </div>
                              <div class="row">
                                <b class="col-sm-4">Waktu Selesai</b>
                                <span class="col-sm-8">: <?= date('d/m/y H:i', strtotime($kgt['waktu_selesai'])) ?></span>
                              </div>
                              <div class="row">
                                <b class="col-sm-4">Durasi Kegiatan</b>
                                <span class="col-sm-8">: <?= $kgt['durasi'] ?> Menit</span>
                              </div>
                              <div class="row">
                                <b class="col-sm-4">Foto Kegiatan</b>
                                <span class="col-sm-8">: <a href="#" data-toggle="modal" data-target="#modal-foto<?= $kgt['id'] ?>"><i class="fa fa-image"></i> Tampilkan Foto</a></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <?php $no=$no+1; }
                    }
                    if ($no == 1) { ?>
                      <h4 class="text-center"><i>Tidak ada data</i></h4>
                    <?php } ?>
                  </div>
                </div> 
                <div class="tab-pane" id="messages-2">
                  <div class="row">
                    <?php $no = 1; foreach ($result as $dta) {
                      $pengerjaan_id = $dta['id'];
                      $kegiatan = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE pengerjaan_id='$pengerjaan_id' AND status='accept'");
                      foreach ($kegiatan as $kgt) { 
                        $kegiatan_id = $kgt['id'];
                        $rating = mysqli_query($conn, "SELECT * FROM tb_rating WHERE kegiatan_id='$kegiatan_id'");
                        $rat = mysqli_fetch_assoc($rating);
                        ?>
                        <div class="col-lg-6">
                          <div class="portlet">
                            <div class="portlet-heading bg-success">
                              <h3 class="portlet-title">
                                Data Kegiatan <?= $no ?>
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
                                  <span class="col-sm-8">: <?= $dta['formulir'] ?></span>
                                </div>
                                <div class="row">
                                  <b class="col-sm-4">Nomor Tiang </b>
                                  <span class="col-sm-8">: <?= $dta['nomor_tiang'] ?></span>
                                </div>
                                <div class="row">
                                  <b class="col-sm-4">Lokasi </b>
                                  <span class="col-sm-8">: <?= $dta['lokasi'] ?></span>
                                </div>
                                <div class="row">
                                  <b class="col-sm-4">Keterangan </b>
                                  <span class="col-sm-8">: <?= $dta['keterangan'] ?></span>
                                </div>
                                <div class="row">
                                  <b class="col-sm-4">Sasaran </b>
                                  <span class="col-sm-8">: <?= $kgt['sasaran'] ?></span>
                                </div>
                                <div class="row">
                                  <b class="col-sm-4">Waktu Mulai</b>
                                  <span class="col-sm-8">: <?= date('d/m/y H:i', strtotime($kgt['waktu_mulai'])) ?></span>
                                </div>
                                <div class="row">
                                  <b class="col-sm-4">Waktu Selesai</b>
                                  <span class="col-sm-8">: <?= date('d/m/y H:i', strtotime($kgt['waktu_selesai'])) ?></span>
                                </div>
                                <div class="row">
                                  <b class="col-sm-4">Durasi Kegiatan</b>
                                  <span class="col-sm-8">: <?= $kgt['durasi'] ?> Menit</span>
                                </div>
                                <div class="row">
                                  <b class="col-sm-4">Foto Kegiatan</b>
                                  <span class="col-sm-8">: <a href="#" data-toggle="modal" data-target="#modal-foto<?= $kgt['id'] ?>"><i class="fa fa-image"></i> Tampilkan Foto</a></span>
                                </div>
                                <div class="row">
                                  <b class="col-sm-4">Rating</b>
                                  <span class="col-sm-8">: 
                                    <?php for ($i=1; $i <=5 ; $i++) { 
                                      if ($i <= $rat['rating']) { ?>
                                        <i class="fa fa-star text-warning"></i>
                                      <?php } else { ?>
                                        <i class="ti-star text-dark"></i>
                                      <?php }
                                    } ?>
                                  </span>
                                </div>
                                <div class="row">
                                  <b class="col-sm-4">Ulasan</b>
                                  <span class="col-sm-8">: <?= $rat['keterangan'] ?></span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <?php $no=$no+1; }
                      }
                      if ($no == 1) { ?>
                        <h4 class="text-center"><i>Tidak ada data</i></h4>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="tab-pane" id="ditolak-2">
                    <div class="row">
                      <?php $no = 1; foreach ($result as $dta) {
                        $pengerjaan_id = $dta['id'];
                        $kegiatan = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE pengerjaan_id='$pengerjaan_id' AND status='refuse'");
                        foreach ($kegiatan as $kgt) { 
                          $kegiatan_id = $kgt['id'];
                          $rating = mysqli_query($conn, "SELECT * FROM tb_rating WHERE kegiatan_id='$kegiatan_id'");
                          $rat = mysqli_fetch_assoc($rating);
                          ?>
                          <div class="col-lg-6">
                            <div class="portlet">
                              <div class="portlet-heading bg-danger">
                                <h3 class="portlet-title">
                                  Data Kegiatan <?= $no ?>
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
                                    <span class="col-sm-8">: <?= $dta['formulir'] ?></span>
                                  </div>
                                  <div class="row">
                                    <b class="col-sm-4">Nomor Tiang </b>
                                    <span class="col-sm-8">: <?= $dta['nomor_tiang'] ?></span>
                                  </div>
                                  <div class="row">
                                    <b class="col-sm-4">Lokasi </b>
                                    <span class="col-sm-8">: <?= $dta['lokasi'] ?></span>
                                  </div>
                                  <div class="row">
                                    <b class="col-sm-4">Keterangan </b>
                                    <span class="col-sm-8">: <?= $dta['keterangan'] ?></span>
                                  </div>
                                  <div class="row">
                                    <b class="col-sm-4">Sasaran </b>
                                    <span class="col-sm-8">: <?= $kgt['sasaran'] ?></span>
                                  </div>
                                  <div class="row">
                                    <b class="col-sm-4">Waktu Mulai</b>
                                    <span class="col-sm-8">: <?= date('d/m/y H:i', strtotime($kgt['waktu_mulai'])) ?></span>
                                  </div>
                                  <div class="row">
                                    <b class="col-sm-4">Waktu Selesai</b>
                                    <span class="col-sm-8">: <?= date('d/m/y H:i', strtotime($kgt['waktu_selesai'])) ?></span>
                                  </div>
                                  <div class="row">
                                    <b class="col-sm-4">Durasi Kegiatan</b>
                                    <span class="col-sm-8">: <?= $kgt['durasi'] ?> Menit</span>
                                  </div>
                                  <div class="row">
                                    <b class="col-sm-4">Foto Kegiatan</b>
                                    <span class="col-sm-8">: <a href="#" data-toggle="modal" data-target="#modal-foto<?= $kgt['id'] ?>"><i class="fa fa-image"></i> Tampilkan Foto</a></span>
                                  </div>
                                  <div class="row">
                                    <b class="col-sm-4">Alasan Penolakan</b>
                                    <span class="col-sm-8">: <?= $rat['keterangan'] ?></span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php $no=$no+1; }
                        }
                        if ($no == 1) { ?>
                          <h4 class="text-center"><i>Tidak ada data</i></h4>
                        <?php } ?>
                      </div>
                    </div>
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
                    <select class="form-control" id="select-formulir" required="">
                      <option value="">--Pilih Kompoen/Formulir--</option>
                      <?php foreach ($pngrjaan as $pkr) {
                        if (date('Y-m-d', strtotime($pkr['tggl_selesai'])) >= date('Y-m-d')) { ?>
                          <option value="<?= $pkr['id'] ?>"><?= $pkr['formulir'].' (Nomor Tiang: '.$pkr['nomor_tiang'].')' ?></option>
                        <?php }
                      } ?>
                    </select>
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

        <!-- modal upload -->
        <div class="modal" id="modal-upload" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myLargeModalLabel">Upload Foto Kegiatan</h4>
              </div>
              <div class="modal-body" style="padding: 20px 50px 0 50px">
                <form method="POST" action="#" id="formUpload" enctype="multipart/form-data">
                  <div class="form-group">
                    <label class="col-form-label">Foto Pengerjaan</label>
                    <div class="dropzone dropzone-previews" id="my-awesome-dropzone"></div>
                    <input type="hidden" name="kegiatan_id" id="kegiatan_id">
                    <a href="#" class="btn btn-sm btn-primary m-t-5" id="take-pic"><i class="fa fa-camera"></i> Ambil Gambar</a>
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Total Kerusakan</label>
                    <input type="number" name="total_kerusakan" id="total_kerusakan" class="form-control" required="" placeholder="Total Kerusakan...">
                  </div>
                  <div class="form-group">
                    <button type="submit" name="addKegiatan" class="btn btn-default">Upload & Selesaikan</button>
                    <button type="" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Batal</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>


        <?php foreach ($result as $dta) { 
          $pengerjaan_id = $dta['id'];
          $kegiatan = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE pengerjaan_id='$pengerjaan_id'");
          foreach ($kegiatan as $kgt) {  ?>
            <!-- modal edit -->
            <div class="modal fade" tabindex="-1" role="dialog" id="modal-edit<?= $kgt['id'] ?>">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Update Data Kegiatan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body" style="padding: 20px 50px 0 50px">
                    <form method="POST">
                      <div class="form-group">
                        <label class="col-form-label">Sasaran Pemeriksaan</label>
                        <input type="hidden" name="id" value="<?= $kgt['id'] ?>">
                        <textarea class="form-control" required="" placeholder="Sasaran Pemeriksaan..." name="sasaran" rows="5"><?= $kgt['sasaran'] ?></textarea>
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
            <div class="modal" id="modal-hapus<?= $kgt['id'] ?>" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Yakin ingin menghapus?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    Lanjutkan menghapus data kegiatan ini?
                  </div>
                  <div class="modal-footer bg-whitesmoke br">
                    <a href="data-kegiatan.php?delete=<?= $kgt['id'] ?>" role="button" class="btn btn-danger">Hapus</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- modal foto -->
            <div class="modal" id="modal-foto<?= $kgt['id'] ?>" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Foto Kegiatan</h4>
                  </div>
                  <div class="modal-body" id="set-media">
                    <img src="../assets/images/foto_kegiatan/<?= $kgt['foto_kegiatan'] ?>" class="img-responsive img-thumbnail" style="width: 100%; margin-bottom: 10px;">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">Tutup</button>
                  </div>
                </div>
              </div>
            </div>
          <?php }
        }
        require('template/footer.php');
        ?>
        <script type="text/javascript">
          Dropzone.autoDiscover = false;
          $(document).ready(function($) {
            $('.add-kegiatan').click(function() {
              $('#waktu_mulai').val("<?= date('Y-m-d H:i') ?>");
            });

            $('#select-formulir').change(function(event) {
              var id = $(this).val();
              $('#pengerjaan-id').val(id);
            });

            $('#btn-upload').click(function(event) {
              Dropzone.forElement(".dropzone-previews").removeAllFiles(true);
              formData = new FormData();
              chek = 0;

              var id = $(this).attr('data-id');
              $('#kegiatan_id').val(id);
            });

            var formData = new FormData();
            var chek = 0;

            $(".dropzone-previews").dropzone({ 
              url: "../controller.php",
              paramName: 'file',
              maxFilesize: 20,
              maxFiles: 1,
              timeout: 60000*120,
              acceptedFiles: 'image/*',
              dictDefaultMessage: 'Klik untuk memilih foto',
              addRemoveLinks: true,
              dictRemoveFile: '<a href="#" class="btn btn-link btn-sm"><i class="fa fa-trash"></i> Hapus</a>',
              accept: function(file, done) {
                if (file) {
                  console.log(file);
                  var dt = new Date(file.lastModifiedDate);
                  var month = dt.getMonth()+1;
                  var date = dt.getDate();
                  var year = dt.getFullYear();
                  var hours = dt.getHours();
                  var minutes = dt.getMinutes();
                  var seconds = dt.getSeconds();
                  if (month < 10) month = '0'+month;
                  if (dt.getDate() < 10) date = '0'+dt.getDate();
                  var tanggal = year+'-'+month+'-'+date+' '+hours+':'+minutes+':'+seconds;

                  formData.append('foto_kegiatan', file);
                  formData.append('tggl_foto', tanggal);
                  chek = chek + 1;
                }
                done();
              },
              error: function(file, error) {
                Swal.fire({
                  title: 'Terjadi Kesalahan',
                  text: error,
                  type: 'error'
                });
                $(file.previewElement).remove();
              },
              removedfile: function(file) {
                $(file.previewElement).remove();
                formData = new FormData();
                chek = 0;
              }

            });

            Webcam.set({
              width: 375,
              height: 300,
              image_format: 'jpeg',
              jpeg_quality: 90
            });

            $('#take-pic').click(function(event) {
              Swal.fire({
                html: `<div id="my_camera"></div>
                <div id="before-take">
                <a href="#" class="btn btn-primary btn-lg" id="get-pic"><i class="fa fa-camera"></i> Foto</a>
                </div>
                <div id="after-take" hidden="" style="margin-top: 10px;">
                <a href="#" class="btn btn-primary btn-lg" id="ret-pic"><i class="fa fa-undo"></i> Ganti</a>
                <a href="#" class="btn btn-success btn-lg" id="upl-pic"><i class="fa fa-upload"></i> Upload</a>
                </div>`,
                width: '400px',
                showCloseButton: true,
                showCancelButton: false,
                showConfirmButton: false,
                onClose: function () {
                  Webcam.reset();
                }
              });
              Webcam.attach('#my_camera');
            });

            $(document).on('click', '#get-pic', function(e) {
              e.preventDefault();
              Webcam.freeze();
              $(document).find('#before-take').attr('hidden', '');
              $(document).find('#after-take').removeAttr('hidden');
            });

            $(document).on('click', '#ret-pic', function(e) {
              e.preventDefault();
              Webcam.unfreeze();
              $(document).find('#before-take').removeAttr('hidden');
              $(document).find('#after-take').attr('hidden', '');
            });

            $(document).on('click', '#upl-pic', function(e) {
              e.preventDefault();
              Webcam.snap( function(data_uri) {
                $(document).find('#before-take').removeAttr('hidden');
                $(document).find('#after-take').attr('hidden', '');

                // Dropzone.forElement(".dropzone-previews").emit('thumbnail', file[0], data_uri);
                // Dropzone.forElement(".dropzone-previews").emit('complete', file[0]);
                var blob = dataURItoBlob(data_uri);
                blob.name = 'capture.jpg';
                blob.lastModifiedDate = new Date();
                Dropzone.forElement(".dropzone-previews").addFile(blob);
                swal.close();
              });
              Webcam.reset();
            });

            function dataURItoBlob(dataURI) {
              'use strict'
              var byteString, 
              mimestring 

              if(dataURI.split(',')[0].indexOf('base64') !== -1 ) {
                byteString = atob(dataURI.split(',')[1])
              } else {
                byteString = decodeURI(dataURI.split(',')[1])
              }

              mimestring = dataURI.split(',')[0].split(':')[1].split(';')[0]

              var content = new Array();
              for (var i = 0; i < byteString.length; i++) {
                content[i] = byteString.charCodeAt(i)
              }

              return new Blob([new Uint8Array(content)], {type: mimestring});
            }

            $('#formUpload').submit(function(event) {
              event.preventDefault();

              if (chek == 0) {
                Swal.fire({
                  title: 'Belum Ada File!',
                  text: 'Pastikan anda telah melampirkan foto',
                  type: 'warning'
                });
                return
              }

              formData.append('req', 'uploadFoto');        
              formData.append('kegiatan_id', $('#kegiatan_id').val());        
              formData.append('total_kerusakan', $('#total_kerusakan').val());

              $.ajax({
                url     : '../controller.php',
                method  : "POST",
                data    : formData,
                contentType : false,
                processData: false,
                success : function(data) {
                  console.log(data);
                  Swal.fire({
                    title: data.title,
                    text: data.message,
                    type: data.status
                  }).then(function() {
                    if (data.status == 'success') {
                      location.href= 'data-kegiatan.php';
                    }
                  });
                }
              });      
            });

            <?php if (isset($res) && $res['status'] == 'success') { ?>
              Swal.fire({
                title: 'Berhasil Ditambah',
                text: "<?= $res['message'] ?>",
                type: 'success'
              }).then(function() {
                location.href = 'data-kegiatan.php';
              });
            <?php } else if (isset($res) && $res['status'] == 'error') { ?>
              Swal.fire({
                title: 'Terjadi Kesalahan',
                text: "<?= $res['message'] ?>",
                type: 'error'
              });
            <?php }  else if (isset($res) && $res['status'] == 'delete') { ?>
              Swal.fire({
                title: 'Berhasil Dihapus',
                text: "<?= $res['message'] ?>",
                type: 'success'
              });
              window.history.pushState('', '', "data-kegiatan.php")
            <?php } else if (isset($res) && $res['status'] == 'update') { ?>
              Swal.fire({
                title: 'Berhasil Diupdate',
                text: "<?= $res['message'] ?>",
                type: 'success'
              });
              window.history.pushState('', '', "data-kegiatan.php")
            <?php } ?>
          });
        </script>