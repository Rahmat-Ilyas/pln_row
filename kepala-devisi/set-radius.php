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

  var latitude = document.getElementById('latitude').value;
  var longitude = document.getElementById('longitude').value;
  var lokasi_center = document.getElementById('lokasi_center').value;
  var luas_daerah = document.getElementById('luas_daerah').value;
  var radius = document.getElementById('radius').value;
  var marker;

  // Rumus Radius/Jari-Jari
  var ls = luas_daerah*7/22;
  var radius_fix = Math.sqrt(ls * 1e+6);

  // Rumus Luas 
  var luas_fix = 3.14 * radius_fix * radius_fix;

  function initialize() {
    var lsd = document.getElementById('luas_daerah');
    var rad = document.getElementById('radius');
    var center = { lat: parseFloat(latitude), lng: parseFloat(longitude) };
    // Create the map.
    var map = new google.maps.Map(document.getElementById("map"), {
      zoom: 11,
      center: center,
      mapTypeId: "terrain",
    });

    marker = new google.maps.Marker({
      position: center,
      map,
    });

    var cityCircle = new google.maps.Circle({
      strokeColor: "#81c868",
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: "#81c868",
      fillOpacity: 0.35,
      clickable: false,
      map,
      center: center,
      radius: radius_fix,
    });

    google.maps.event.addDomListener(lsd, 'keyup', function(e) {
      luas_daerah = this.value;
      ls = luas_daerah*7/22;
      radius_fix = Math.sqrt(ls * 1e+6);
      rad.value = (radius_fix/1000).toFixed(2);
      cityCircle.setMap(null);
      marker.setPosition(center);
      cityCircle = new google.maps.Circle({
        strokeColor: "#81c868",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#81c868",
        fillOpacity: 0.35,
        clickable: false,
        map,
        center: center,
        radius: radius_fix,
      });
    });

    google.maps.event.addDomListener(rad, 'keyup', function(e) {
      radius = this.value;
      luas_daerah = 3.14 * radius * radius;
      ls = luas_daerah*7/22;
      radius_fix = Math.sqrt(ls * 1e+6);
      lsd.value = luas_daerah.toFixed(2);
      cityCircle.setMap(null);
      marker.setPosition(center);
      cityCircle = new google.maps.Circle({
        strokeColor: "#81c868",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#81c868",
        fillOpacity: 0.35,
        clickable: false,
        map,
        center: center,
        radius: radius_fix,
      });
    });

    google.maps.event.addListener(map, 'click', function(event) {
      center = event.latLng;
      document.getElementById('latitude').value = event.latLng.lat();
      document.getElementById('longitude').value = event.latLng.lng();
      cityCircle.setMap(null);
      cityCircle = new google.maps.Circle({
        strokeColor: "#81c868",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#81c868",
        fillOpacity: 0.35,
        clickable: false,
        map,
        center: event.latLng,
        radius: radius_fix,
      });

      if( marker ){
        marker.setPosition(center);
      } else {
        marker = new google.maps.Marker({
          position: event.latLng,
          map: this
        });
      }
    });
  }
</script>