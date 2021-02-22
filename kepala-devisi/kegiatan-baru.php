<?php 
require('template/header.php');

$result = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE status='proccess' ORDER BY id DESC");

// SET ACCEPT
if (isset($_POST['submitRating'])) {
  $kegiatan_id = $_POST['kegiatan_id'];
  $rating = $_POST['rating'];
  $keterangan = $_POST['keterangan'];

  mysqli_query($conn, "UPDATE tb_kegiatan SET status='accept' WHERE id='$kegiatan_id'");
  $query = "INSERT INTO tb_rating VALUES(NULL, '$kegiatan_id', '$rating', '$keterangan')";

  if (mysqli_query($conn, $query)) {
    $response = [
      'status' => 'success',
      'message' => 'Kegiatan berhasil di Accept',
    ];    
  } else { 
    $response = [
      'status' => 'error',
      'message' => mysqli_error($conn),
    ];
  }
}

// SET REFUSE
if (isset($_POST['submitRefuse'])) {
  $kegiatan_id = $_POST['kegiatan_id'];
  $rating = 0;
  $keterangan = $_POST['keterangan'];

  mysqli_query($conn, "UPDATE tb_kegiatan SET status='refuse' WHERE id='$kegiatan_id'");
  $query = "INSERT INTO tb_rating VALUES(NULL, '$kegiatan_id', '$rating', '$keterangan')";

  if (mysqli_query($conn, $query)) {
    $response = [
      'status' => 'success',
      'message' => 'Kegiatan telah di Tolak',
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
            <h4 class="page-title">Kegiatan Baru</h4>
            <ol class="breadcrumb">
              <li>
                <a href="#">Kepala Devisi</a>
              </li>
              <li class="active">
                Pnengerjaan & Kegiatan
              </li>
              <li class="active">
                Kegiatan Baru
              </li>
            </ol>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="card-box table-responsive">
              <h4 class="m-t-0 header-title"><b>Kegiatan Baru Menunggu Diproses</b></h4>

              <table id="datatable" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Foto Kegiatan</th>
                    <th width="100">Nama Pekerja</th>
                    <th>Komponen</th>
                    <th>Sasaran</th>
                    <th>No Tiang</th>
                    <th>Total Kerusakan</th>
                    <th>Durasi Pengerjaan</th>
                    <th>Detail</th>
                    <th width="50">Proses</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($result as $dta) {
                    $pgrjan_id = $dta['pengerjaan_id'];
                    $pngrjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan WHERE id='$pgrjan_id'");
                    $pgr = mysqli_fetch_assoc($pngrjaan);

                    $anggota_id = $pgr['anggota_id'];
                    $anggota = mysqli_query($conn, "SELECT * FROM tb_anggota WHERE id='$anggota_id'");
                    $agt = mysqli_fetch_assoc($anggota); ?>
                    <tr>
                      <td>
                        <a href="#" data-toggle="modal" data-target="#modal-foto<?= $dta['id'] ?>">
                          <img height="50" width="50" src="../assets/images/foto_kegiatan/<?= $dta['foto_kegiatan'] ?>">
                        </a>
                      </td>
                      <td><?= $agt['nama'] ?></td>
                      <td><?= $pgr['formulir'] ?></td>
                      <td><?= $dta['sasaran'] ?></td>
                      <td><?= $pgr['nomor_tiang'] ?></td>
                      <td><?= $dta['total_kerusakan'] ?> Kerusakan</td>
                      <td><?= $dta['durasi'] ?> Menit</td>
                      <td class="text-center">
                        <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-detail<?= $dta['id'] ?>" data-toggle1="tooltip" title="" data-original-title="Tampilkan Detail Kegiatan"><i class="fa fa-list"></i></a>
                      </td>
                      <td>
                        <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-accept<?= $dta['id'] ?>" data-toggle1="tooltip" title="" data-original-title="Accept Kegiatan"><i class="fa fa-check-square"></i></a>
                        <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-refuse<?= $dta['id'] ?>" data-toggle1="tooltip" title="" data-original-title="Tolak Kegiatan"><i class="fa fa-times-circle"></i></a>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php foreach ($result as $dta) { 
  $pgrjan_id = $dta['pengerjaan_id'];
  $pngrjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan WHERE id='$pgrjan_id'");
  $pgr = mysqli_fetch_assoc($pngrjaan);

  $anggota_id = $pgr['anggota_id'];
  $anggota = mysqli_query($conn, "SELECT * FROM tb_anggota WHERE id='$anggota_id'");
  $agt = mysqli_fetch_assoc($anggota); ?>

  <!-- modal accept -->
  <div class="modal modal-accept" id="modal-accept<?= $dta['id'] ?>" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Berikan Rating Sebelum Accept Kegiatan Ini</h4>
        </div>
        <div class="modal-body" id="set-media">
          <form method="POST" class="formRating" data-id="<?= $dta['id'] ?>">
            <div class="text-center">
              <label>Berikan Rating</label>
            </div>
            <div class="text-center" style="cursor: pointer;" data-id="<?= $dta['id'] ?>">
              <i class="fa fa-star-o fa-3x rating" id="rat-1<?= $dta['id'] ?>" val-rating="1"></i>
              <i class="fa fa-star-o fa-3x rating" id="rat-2<?= $dta['id'] ?>" val-rating="2"></i>
              <i class="fa fa-star-o fa-3x rating" id="rat-3<?= $dta['id'] ?>" val-rating="3"></i>
              <i class="fa fa-star-o fa-3x rating" id="rat-4<?= $dta['id'] ?>" val-rating="4"></i>
              <i class="fa fa-star-o fa-3x rating" id="rat-5<?= $dta['id'] ?>" val-rating="5"></i>
            </div>
            <input type="hidden" name="kegiatan_id" id="kegiatan_id" value="<?= $dta['id'] ?>">
            <input type="hidden" name="rating" id="value-rating<?= $dta['id'] ?>">
            <textarea class="form-control m-t-10 m-b-10 keterangan" id="keterangan<?= $dta['id'] ?>" name="keterangan" placeholder="Masukkan keterangan rating.."></textarea>
            <button type="submit" name="submitRating" class="btn btn-primary waves-effect btn-block">Accepted</button>
            <a href="#" class="btn btn-inverse btn-block batal-proccess" data-dismiss="modal">Batal</a>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- modal refuse -->
  <div class="modal modal-refuse" id="modal-refuse<?= $dta['id'] ?>" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="myModalLabel">Tolak Kegiatan?</h4>
        </div>
        <div class="modal-body" id="set-media">
          <form method="POST" class="formRefuse" data-id="<?= $dta['id'] ?>">
            <div class="text-center">
              <label>Alasan Penolakan</label>
            </div>
            <input type="hidden" name="kegiatan_id" id="kegiatan_id" value="<?= $dta['id'] ?>">
            <textarea class="form-control m-t-10 m-b-10 keterangan" id="keterangan<?= $dta['id'] ?>" name="keterangan" placeholder="Masukkan alasan penolakan.." required=""></textarea>
            <button type="submit" name="submitRefuse" class="btn btn-danger waves-effect btn-block">Tolak Kegiatan</button>
            <a href="#" class="btn btn-inverse btn-block batal-proccess" data-dismiss="modal">Batal</a>
          </form>
        </div>
      </div>
    </div>
  </div>


  <!-- modal detail -->
  <div class="modal" id="modal-detail<?= $dta['id'] ?>" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="myModalLabel">Detail Kegiatan</h4>
        </div>
        <div class="modal-body">
          <div class="panel-group panel-group-joined" id="accordion-test"> 
            <div class="panel panel-default"> 
              <div class="panel-heading"> 
                <h4 class="panel-title"> 
                  <a data-toggle="collapse" data-parent="#accordion-test" href="#collapseOne<?= $dta['id'] ?>" class="collapsed">
                    Data Anggota/Pekerja
                  </a> 
                </h4> 
              </div> 
              <div id="collapseOne<?= $dta['id'] ?>" class="panel-collapse collapse"> 
                <div class="panel-body">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item row">
                      <b class="col-sm-4 p-0">Foto Anggota: </b>
                      <span class="col-sm-8 p-0">
                        <img height="100" width="100" src="../assets/images/anggota/<?= $agt['foto'] ?>">
                      </span>
                    </li>
                    <li class="list-group-item row">
                      <b class="col-sm-4 p-0">Nama Lengkap: </b>
                      <span class="col-sm-8 p-0"><?= $agt['nama'] ?></span>
                    </li>
                    <li class="list-group-item row">
                      <b class="col-sm-4 p-0">NIP: </b>
                      <span class="col-sm-8 p-0"><?= $agt['nip'] ?></span>
                    </li>
                    <li class="list-group-item row">
                      <b class="col-sm-4 p-0">Telepon: </b>
                      <span class="col-sm-8 p-0"><?= $agt['telepon'] ?></span>
                    </li>
                  </ul>
                </div> 
              </div> 
            </div> 
            <div class="panel panel-default"> 
              <div class="panel-heading"> 
                <h4 class="panel-title"> 
                  <a data-toggle="collapse" data-parent="#accordion-test" href="#collapseTwo<?= $dta['id'] ?>">
                    Data Pekerjaan
                  </a> 
                </h4> 
              </div> 
              <div id="collapseTwo<?= $dta['id'] ?>" class="panel-collapse collapse"> 
                <div class="panel-body">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item row">
                      <b class="col-sm-4 p-0">Formulir/Komponen: </b>
                      <span class="col-sm-8 p-0 row"><?= $pgr['formulir'] ?></span>
                    </li>
                    <li class="list-group-item row">
                      <b class="col-sm-4 p-0">Nomor Tiang: </b>
                      <span class="col-sm-8 p-0 row"><?= $pgr['nomor_tiang'] ?></span>
                    </li>
                    <li class="list-group-item row">
                      <b class="col-sm-4 p-0">Keterangan: </b>
                      <span class="col-sm-8 p-0"><?= $pgr['keterangan'] ?></span>
                    </li>
                    <li class="list-group-item row">
                      <b class="col-sm-4 p-0">Priode Pengerjaan: </b>
                      <span class="col-sm-8 p-0"><?= date('d/m/Y', strtotime($pgr['tggl_mulai'])).' - '.date('d/m/Y', strtotime($pgr['tggl_selesai'])) ?></span>
                    </li>
                    <li class="list-group-item row">
                      <b class="col-sm-4 p-0">Lokasi: </b>
                      <span class="col-sm-8 p-0"><?= $pgr['lokasi'] ?></span>
                    </li>
                    <li class="list-group-item row">
                      <div class="col-sm-12 p-0" id="mapView<?= $dta['id'] ?>" style="height: 200px;"></div>
                    </li>
                  </ul>
                </div> 
              </div> 
            </div> 
            <div class="panel panel-default"> 
              <div class="panel-heading"> 
                <h4 class="panel-title"> 
                  <a data-toggle="collapse" data-parent="#accordion-test" href="#collapseThree<?= $dta['id'] ?>" class="collapsed">
                    Data Kegiatan
                  </a> 
                </h4> 
              </div> 
              <div id="collapseThree<?= $dta['id'] ?>" class="panel-collapse collapse in"> 
                <div class="panel-body">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item row">
                      <b class="col-sm-4 p-0">Sasaran: </b>
                      <span class="col-sm-8 p-0 row"><?= $dta['sasaran'] ?></span>
                    </li>
                    <li class="list-group-item row">
                      <b class="col-sm-4 p-0">Total Kerusakan: </b>
                      <span class="col-sm-8 p-0 row"><?= $dta['total_kerusakan'] ?></span>
                    </li>
                    <li class="list-group-item row">
                      <b class="col-sm-4 p-0">Waktu Mulia: </b>
                      <span class="col-sm-8 p-0"><?= $dta['waktu_mulai'] ?></span>
                    </li>
                    <li class="list-group-item row">
                      <b class="col-sm-4 p-0">Waktu Selesai: </b>
                      <span class="col-sm-8 p-0"><?= $dta['waktu_selesai'] ?></span>
                    </li>
                    <li class="list-group-item row">
                      <b class="col-sm-4 p-0">Durasi Pengerjaan: </b>
                      <span class="col-sm-8 p-0"><?= $dta['durasi'] ?> Menit</span>
                    </li>
                    <li class="list-group-item row">
                      <b class="col-sm-4 p-0">Foto Kegiatan: </b>
                      <div class="col-sm-12 p-0">
                        <img style="width: 100%; height: auto;" src="../assets/images/foto_kegiatan/<?= $dta['foto_kegiatan'] ?>">
                      </div>
                    </li>
                  </ul>
                </div> 
              </div> 
            </div> 
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <!-- modal foto -->
  <div class="modal" id="modal-foto<?= $dta['id'] ?>" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="myModalLabel">Foto Kegiatan</h4>
        </div>
        <div class="modal-body" id="set-media">
          <img src="../assets/images/foto_kegiatan/<?= $dta['foto_kegiatan'] ?>" class="img-responsive img-thumbnail" style="width: 100%; margin-bottom: 10px;">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
<?php } 
require('template/footer.php');
?>

<script type="text/javascript">
  $(document).ready(function($) {
    $('.modal-accept').modal({
      backdrop: 'static',
      keyboard: false,
      show: false
    });

    $('.rating').click(function(event) {
      var id = $(this).parent('div').attr('data-id');
      var valRating = $(this).attr('val-rating');

      $('.rating').removeClass('fa-star').addClass('fa-star-o').removeClass('text-warning');
      for (var i = 1; i <= valRating; i++) {
        $('#rat-'+i+id).removeClass('fa-star-o').addClass('fa-star').addClass('text-warning');
      }
      $('#value-rating'+id).val(valRating);

      if (valRating == 1) $('#keterangan'+id).val('Pengerjaan tidak memuaskan');
      if (valRating == 2) $('#keterangan'+id).val('Pengerjaan kurang memuaskan');
      if (valRating == 3) $('#keterangan'+id).val('Pengerjaan lumanyan');
      if (valRating == 4) $('#keterangan'+id).val('Pengerjaan bagus');
      if (valRating == 5) $('#keterangan'+id).val('Pengerjaan sangat bagus');
    });

    $('.keterangan').focus(function(event) {
      $(this).val('');
    });

    $('.batal-proccess').click(function(event) {
      $('.rating').removeClass('fa-star').addClass('fa-star-o').removeClass('text-warning');
      $('.keterangan').val('');
    });

    $('.formRating').submit(function(e) {
      id = $(this).attr('data-id');
      if ($('#value-rating'+id).val() == '' || $('#keterangan'+id).val() == '') {
        e.preventDefault();
        Swal.fire({
          title: 'Lengkapi Data',
          text: 'Berikan rating dan keterangan!',
          type: 'warning'
        })
      }
    });

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
    <?php } ?>
  });

  function initialize() {
    <?php foreach ($result as $dta) { 
      $pgrjan_id = $dta['pengerjaan_id'];
      $pngrjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan WHERE id='$pgrjan_id'");
      $pgr = mysqli_fetch_assoc($pngrjaan);

      $anggota_id = $pgr['anggota_id'];
      $anggota = mysqli_query($conn, "SELECT * FROM tb_anggota WHERE id='$anggota_id'");
      $agt = mysqli_fetch_assoc($anggota); ?>

      var lng = <?= $pgr['longitude'] ?>;
      var lat = <?= $pgr['latitude'] ?>;
      var lokasi = "<?= $pgr['lokasi'] ?>";
      var nama = "<?= $agt['nama'] ?>";

      var propertiPeta = {
        center:new google.maps.LatLng(lat,lng), 
        zoom:15,
        mapTypeId:google.maps.MapTypeId.ROADMAP
      };
      var peta = new google.maps.Map(document.getElementById("mapView<?= $dta['id'] ?>"), propertiPeta);
      var marker = new google.maps.Marker({
        position: new google.maps.LatLng(lat,lng),
        map: peta
      });
      var contentString = '<b>Nama Pekerja: ' + nama + '</b><p>Lokasi: ' + lokasi + '</p>';
      var infowindow = new google.maps.InfoWindow({
        content: contentString
      });
      infowindow.open(peta, marker);
    <?php } ?>
  }
  google.maps.event.addDomListener(window, 'load', initialize);
</script>