<?php 
require('template/header.php');
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
              <form class="form-horizontal" role="form">
                <div class="form-group">
                  <label class="col-md-4 control-label">Tanngal Pengajuan</label>
                  <div class="col-md-5">
                    <input type="date" class="form-control" value="<?= date('Y-m-d'); ?>" readonly="">
                  </div>
                </div>
                <div class="form-group">
                  <h3 class="col-md-12 text-center">Atur Lokasi Pengerjaan</h3>
                  <div class="col-md-12">
                    <div id="map" style="float: left; height: 450px; width: 50%;"></div>
                    <div class="m-b-10" id="pano" style="float: left; height: 450px; width: 50%;"></div>
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-4 control-label">Detail Lokasi</label>
                  <div class="col-md-5">
                    <textarea class="form-control" rows="2" placeholder="Detail Lokasi"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-4 control-label">Nomor Tiang</label>
                  <div class="col-md-5">
                    <input type="number" class="form-control" placeholder="Nomor Tiang">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-4 control-label">Komponen Pengerjaan</label>
                  <div class="col-md-5">
                    <input type="text" class="form-control" placeholder="Komponen Pengerjaan">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-4 control-label">Keterangan</label>
                  <div class="col-md-5">
                    <textarea class="form-control" rows="2" placeholder="Keterangan"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-4 control-label">Sasaran Pengerjaan</label>
                  <div class="col-md-5">
                    <textarea class="form-control" rows="2" placeholder="Sasaran Pengerjaan"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-4 control-label">Tanggal Mulai</label>
                  <div class="col-md-5">
                    <input type="date" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-4 control-label">Tanggal Selesai</label>
                  <div class="col-md-5">
                    <input type="date" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-4"></div>
                  <div class="col-md-5">
                    <button type="submit" class="btn waves-effect waves-light btn-primary">Submit</button>
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