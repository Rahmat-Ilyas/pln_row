<?php 
require('template/header.php');

$response = '';
if (isset($_POST['submit'])) {
  $lokasi_center = $_POST['lokasi_center'];
  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];
  $luas_daerah = $_POST['luas_daerah'];
  $radius = $_POST['radius'];

  $query = mysqli_query($conn, "UPDATE tb_radius SET lokasi_center='$lokasi_center', latitude='$latitude', longitude='$longitude', luas_daerah='$luas_daerah', radius='$radius'");

  if ($query) {
    $response = 'success';
  }
}

$radius = mysqli_query($conn, "SELECT * FROM tb_radius");
$rds = mysqli_fetch_assoc($radius);

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
            <h4 class="page-title">Set Radius</h4>
            <ol class="breadcrumb">
              <li>
                <a href="#">Kepala Devisi</a>
              </li>
              <li class="active">
                Set Radius
              </li>
            </ol>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="card-box">
              <h4 class="m-b-20 header-title text-center"><b>Atur Radius Lokasi Pengerjaan</b></h4>
              <div class="row">
                <div class="col-md-12">
                  <form class="form-horizontal" role="form" method="POST">
                    <div class="form-group">
                      <div class="col-md-12">
                        <div id="map" style="height: 350px;"></div>
                        <input type="hidden" name="latitude" id="latitude" value="<?= $rds['latitude'] ?>">
                        <input type="hidden" name="longitude" id="longitude" value="<?= $rds['longitude'] ?>">
                      </div>
                      <label class="col-md-4"></label>
                      <span class="col-md-5 text-info">*Klik map untuk memilih lokasi center</span>
                    </div>

                    <div class="form-group">
                      <label class="col-md-4 control-label">Detail Lokasi</label>
                      <div class="col-md-5">
                        <textarea class="form-control" id="lokasi_center" rows="2" placeholder="Detail Lokasi.." name="lokasi_center" required=""><?= $rds['lokasi_center'] ?></textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-md-4 control-label">Luas Daerah Pengerjaan (Km<sup>2</sup>)</label>
                      <div class="col-md-5">
                        <input type="number" step=".01" class="form-control" id="luas_daerah" placeholder="Luas Daerah Pengerjaan (Km2).." name="luas_daerah" required="" value="<?= $rds['luas_daerah'] ?>">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-md-4 control-label">Radius Pengerjaan (Km</sup>)</label>
                      <div class="col-md-5">
                        <input type="number" step=".01" class="form-control" id="radius" placeholder="Radius Pengerjaan.." name="radius" required="" value="<?= $rds['radius'] ?>">
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-4"></div>
                      <div class="col-md-5">
                        <button type="submit" class="btn waves-effect waves-light btn-primary" name="submit">Submit</button>
                      </div>
                    </div>
                  </form>
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

<script>
  $(document).ready(function($) {
    <?php if ($response == 'success') { ?>
      Swal.fire({
        title: 'Update Berhasil',
        text: 'Radius pengerjaan berhasil di perbaharui!',
        type: 'success'
      });
    <?php } ?>
  });
</script>