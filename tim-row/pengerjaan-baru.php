<?php 
require('template/header.php');

$radius = mysqli_query($conn, "SELECT * FROM tb_radius");
$rds = mysqli_fetch_assoc($radius);

if (isset($_POST['submit'])) {
  var_dump($_POST); exit();

}
?>

<div class="row">
  <div class="col-sm-12">
    <!-- Page-Title -->
    <div class="row">
      <div class="col-sm-12">
        <h4 class="page-title">Pengerjaan Baru</h4>
        <ol class="breadcrumb">
          <li>
            <a href="#">Pengerjaan</a>
          </li>
          <li class="active">
            Pengerjaan Baru
          </li>
        </ol>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card-box">
          <h4 class="m-b-20 header-title text-center"><b>Input Data Pengerjaan</b></h4>
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" role="form" method="POST">
                <div class="form-group">
                  <label class="col-md-4 control-label">Unit Pengajuan</label>
                  <div class="col-md-5">
                    <input type="hidden" name="anggota_id" value="<?= $anggota_id ?>">
                    <select class="form-control" name="unit">
                      <option>UPT SISTEM MAKASSAR</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-4 control-label">Gardu Induk</label>
                  <div class="col-md-5">
                    <select class="form-control" name="gardu_induk">
                      <option>GARDU UTP MAKASSAR</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-4 control-label">Formulir/Komponen</label>
                  <div class="col-md-5">
                    <input type="text" class="form-control" placeholder="Formulir/Komponen Pengerjaan.." name="formulir" required="">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-4 control-label">Nomor Tiang</label>
                  <div class="col-md-5">
                    <input type="number" class="form-control" placeholder="Nomor Tiang.." name="nomor_tiang" required="">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-4 control-label">Keterangan</label>
                  <div class="col-md-5">
                    <textarea class="form-control" rows="2" placeholder="Keterangan.." name="keterangan" required=""></textarea>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-4 control-label">Tanggal Mulai</label>
                  <div class="col-md-5">
                    <input type="date" class="form-control" name="tggl_mulai" value="<?= date('Y-m-d') ?>" required="">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-4 control-label">Tanggal Selesai</label>
                  <div class="col-md-5">
                    <input type="date" class="form-control" name="tggl_selesai" value="<?= date('Y-m-d') ?>" required="">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-4 control-label">Lokasi Pengerjaan</label>
                  <div class="col-md-5">
                    <div id="map" style="height: 300px;"></div>
                    <input type="hidden" required="" name="latitude" id="latitude">
                    <input type="hidden" required="" name="longitude" id="longitude">
                    <small class="text-secondary"><i>*Klik map untuk memilih lokasi.</i></small><br>
                    <a href="#" class="btn btn-default btn-sm m-t-5" id="location"><i class="fa fa-map-marker"></i>&nbsp;&nbsp;Pilih Lokasi Sekarang</a>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-4 control-label">Detail Lokasi</label>
                  <div class="col-md-5">
                    <textarea class="form-control" rows="2" placeholder="Detail Lokasi.." name="lokasi" required=""></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-4"></div>
                  <div class="col-md-5">
                    <button type="submit" name="submit" class="btn waves-effect waves-light btn-primary">Submit</button>
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

<?php 
require('template/footer.php');
?>