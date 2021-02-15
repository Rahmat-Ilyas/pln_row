<?php 
require('template/header.php');
$pngrjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan WHERE anggota_id='$anggota_id'");

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
          <h4 class="m-t-0 header-title"><b>Data Kegiatan (01 Feb - 02 Mar)</b></h4>
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
              <a href="#messages-2" data-toggle="tab" aria-expanded="true"> 
                <span class="visible-xs"><i class="md-play-install"></i></span> 
                <span class="hidden-xs"><i class="md-play-install"></i> Selesai Diproses</span> 
              </a> 
            </li>
            <li class="tab"> 
              <a href="#messages-2" data-toggle="tab" aria-expanded="true"> 
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
                <p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt.Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim.</p> 
                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.</p> 
              </div> 
              <div class="tab-pane" id="messages-2">
                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.</p> 
                <p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt.Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim.</p> 
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
              <select class="form-control" id="select-formulir">
                <option>--Pilih Kompoen/Formulir--</option>
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
            </div>
            <div class="form-group">
              <label class="col-form-label">Total Kerusakan</label>
              <input type="number" name="total_kerusakan" id="total_kerusakan" class="form-control" required="" placeholder="Total Kerusakan...">
            </div>
            <div class="form-group">
              <button type="submit" name="addKegiatan" class="btn btn-default">Upload & Selesaikan</button>
              <button type="" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Batal</button>
            </div>
          </form>
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
                  } else if ($kgt['status'] == 'revisi') {
                    $status = 'Direvisi';
                    $color = 'warning';
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
                  <?php $no=$no+1; } ?>
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
        <?php } ?>
      });
    </script>