<?php 
require('template/header.php');

$result = mysqli_query($conn, "SELECT * FROM tb_kegiatan ORDER BY id DESC");

// DELETE KEGIATAN
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $query = "DELETE FROM tb_kegiatan WHERE id='$id'";
  if (mysqli_query($conn, $query)) {
    $get_kegiatan = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE id='$id'");
    $img = mysqli_fetch_assoc($get_kegiatan);
    if ($img['foto_kegiatan']) {
      $target = "../assets/images/foto_kegiatan/".$img['foto_kegiatan'];
      if (file_exists($target)) unlink($target);
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

?>
<div class="content">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <!-- Page-Title -->
        <div class="row">
          <div class="col-sm-12">
            <h4 class="page-title">Data Kegiatan</h4>
            <ol class="breadcrumb">
              <li>
                <a href="#">Kepala Devisi</a>
              </li>
              <li class="active">
                Pnengerjaan & Kegiatan
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
              <h4 class="m-t-0 header-title"><b>Data Kegiatan</b></h4>
              <hr>

              <table id="datatable" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th width="10">No</th>
                    <th width="100">Nama Pekerja</th>
                    <th>Komponen</th>
                    <th>Sasaran</th>
                    <th>No Tiang</th>
                    <th>Total Kerusakan</th>
                    <th>Durasi Pengerjaan</th>
                    <th>Status</th>
                    <th width="80">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1; foreach ($result as $dta) {
                      // Status
                    if ($dta['status'] == 'new') {
                      $status = 'Kegiatan Baru';
                      $color = 'primary';
                    } else if ($dta['status'] == 'proccess') {
                      $status = 'Sedang Diproses';
                      $color = 'info';
                    } else if ($dta['status'] == 'accept') {
                      $status = 'Selesai Diproses';
                      $color = 'success';
                    } else if ($dta['status'] == 'refuse') {
                      $status = 'Ditolak';
                      $color = 'danger';
                    }
                    $pgrjan_id = $dta['pengerjaan_id'];
                    $pngrjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan WHERE id='$pgrjan_id'");
                    $pgr = mysqli_fetch_assoc($pngrjaan);

                    $anggota_id = $pgr['anggota_id'];
                    $anggota = mysqli_query($conn, "SELECT * FROM tb_anggota WHERE id='$anggota_id'");
                    $agt = mysqli_fetch_assoc($anggota); ?>
                    <tr>
                      <td><?= $no ?></td>
                      <td><?= $agt['nama'] ?></td>
                      <td><?= $pgr['formulir'] ?></td>
                      <td><?= $dta['sasaran'] ?></td>
                      <td><?= $pgr['nomor_tiang'] ?></td>
                      <td><?= $dta['total_kerusakan'] ?> Kerusakan</td>
                      <td><?= $dta['durasi'] ?> Menit</td>
                      <td>
                        <span class="label label-table label-<?= $color ?>"><?= $status ?></span>
                      </td>
                      <td class="text-center">
                        <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-detail<?= $dta['id'] ?>" data-toggle1="tooltip" title="" data-original-title="Tampilkan Detail Kegiatan"><i class="fa fa-list"></i></a>
                        <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-hapus<?= $dta['id'] ?>" data-toggle1="tooltip" title="" data-original-title="Hapus Data Kegiatan"><i class="fa fa-trash"></i></a>
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

  <?php foreach ($result as $dta) { 
    $pgrjan_id = $dta['pengerjaan_id'];
    $pngrjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan WHERE id='$pgrjan_id'");
    $pgr = mysqli_fetch_assoc($pngrjaan);

    $anggota_id = $pgr['anggota_id'];
    $anggota = mysqli_query($conn, "SELECT * FROM tb_anggota WHERE id='$anggota_id'");
    $agt = mysqli_fetch_assoc($anggota); ?>

    <!-- modal refuse -->
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
            Lanjutkan menghapus data kegiatan ini?
          </div>
          <div class="modal-footer bg-whitesmoke br">
            <a href="data-kegiatan.php?delete=<?= $dta['id'] ?>" role="button" class="btn btn-danger">Hapus</a>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
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
                        <?php 
                      // Rating
                        $kegiatan_id = $dta['id'];
                        $rating = mysqli_query($conn, "SELECT * FROM tb_rating WHERE kegiatan_id='$kegiatan_id'");
                        $rat = mysqli_fetch_assoc($rating); 
                        if ($rat) {
                          $jum_rating = $rat['rating'];
                          $ket = $rat['keterangan'];
                        }
                        else {
                          $jum_rating = 0;
                          $ket = "Belum ada rating";
                        }
                        ?>
                        <b class="col-sm-4 p-0">Rating: </b>
                        <span class="col-sm-8 p-0">
                          <a href="#" data-toggle1="tooltip" title="" data-original-title="<?= $ket ?>">
                            <?php for ($i=1; $i <=5 ; $i++) { 
                              if ($i <= $jum_rating) { ?>
                                <i class="fa fa-star text-warning"></i>
                              <?php } else { ?>
                                <i class="ti-star text-dark"></i>
                              <?php }
                            } ?>
                          </a>
                        </span>
                      </li>
                      <li class="list-group-item row">
                        <b class="col-sm-4 p-0">Foto Kegiatan: </b>
                        <?php if ($dta['foto_kegiatan']) { ?>
                          <div class="col-sm-12 p-0">
                            <img style="width: 100%; height: auto;" src="../assets/images/foto_kegiatan/<?= $dta['foto_kegiatan'] ?>">
                          </div>
                        <?php } else { ?>
                          <span class="col-sm-8 p-0">
                            <i>Tidak ada foto</i>
                          </span>
                        <?php } ?>
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
        window.history.pushState('', '', "data-kegiatan.php")
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