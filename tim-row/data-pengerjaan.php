<?php 
require('template/header.php');
$pngrjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan WHERE anggota_id='$anggota_id'");
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
          <h4 class="m-t-0 header-title"><b>Data Pengerjaan (01 Feb - 02 Mar)</b></h4>

          <table id="datatable" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th width="10">No</th>
                <th>Komponen</th>
                <th>Keterangan</th>
                <th>No Tiang</th>
                <th>Tggl Pengerjaan</th>
                <th>Priode</th>
                <th>Lokasi</th>
                <th>Total Kegiatan</th>
                <th width="50">Aksi</th>
              </tr>
            </thead>


            <tbody>
              <?php $no=1; foreach ($pngrjaan as $dta) {
                // Priode
                $tggl_mulai = new DateTime($dta['tggl_mulai']);
                $tggl_selesai = new DateTime($dta['tggl_selesai']);
                $tggl = $tggl_mulai->diff($tggl_selesai)->days;
                if ($tggl >= 25) $priode = 'Bulanan';
                else if ($tggl >= 7) $priode = 'Mingguan';
                else if ($tggl >= 1) $priode = 'Harian';
                // Total Kegiatan
                $pengerjaan_id = $dta['id'];
                $kegiatan = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE pengerjaan_id='$pengerjaan_id'");
                $total_kegiatan = mysqli_num_rows($kegiatan);
                ?>
                <tr>
                  <td><?= $no ?></td>
                  <td id="get-formulir"><?= $dta['formulir'] ?></td>
                  <td><?= $dta['keterangan'] ?></td>
                  <td id="get-tiang"><?= $dta['nomor_tiang'] ?></td>
                  <td>
                    <?= date('d M', strtotime($dta['tggl_mulai'])).' - '.date('d M', strtotime($dta['tggl_selesai'])) ?>
                  </td>
                  <td><?= $priode ?></td>
                  <td><?= $dta['lokasi'] ?></td>
                  <td><?= $total_kegiatan ?> Kegiatan</td>
                  <td class="text-center" style="width: 120px;">
                    <a href="#" class="btn btn-sm btn-success add-kegiatan" data-toggle="modal" data-target="#modal-add-kegiatan" data-toggle1="tooltip" title="" data-original-title="Tambah Kegiatan Pengerjaan" data-id="<?= $dta['id'] ?>"><i class="md-my-library-add"></i></a>
                    <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-dta" data-toggle1="tooltip" title="" data-original-title="Detail Pengerjaan"><i class="md-assignment"></i></a>
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

  <!-- modal tambah kegiatan -->
  <div class="modal" id="modal-add-kegiatan" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="myLargeModalLabel">Tambah Data Kegiatan</h4>
        </div>
        <div class="modal-body" style="padding: 20px 50px 0 50px">
          <form id="fromAlat" action="#" enctype="multipart/form-data">
            <div class="form-group">
              <label class="col-form-label">Waktu Mulai (Defaut)</label>
              <input type="hidden" name="pengerjaan_id" id="pengerjaan-id">
              <div class="row">
                <div class="col-sm-4">
                  <input type="date" class="form-control" required=""name="tgl_mulai" value="<?= date('Y-m-d') ?>" disabled="">
                </div>
                <div class="col-sm-3">
                  <input type="time" class="form-control" required=""name="jam_mulai" value="<?= date('H:i') ?>" disabled="">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-form-label">Komponen/Formulir</label>
              <div class="row">
                <div class="col-sm-8">
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
              <button type="submit" name="simpanAlat" class="btn btn-default" id="upload">Simpan</button>
              <button type="" class="btn btn-primary" id="batal" data-dismiss="modal" aria-hidden="true">Batal</button>
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
      $('.add-kegiatan').click(function() {
        var id = $(this).attr('data-id');
        var formulir = $(this).parents('tr').find('#get-formulir').text();
        var tiang = $(this).parents('tr').find('#get-tiang').text();
        
        $('#pengerjaan-id').val(id);
        $('#set-formulir').val(formulir);
        $('#set-tiang').val('Nomor Tiang: '+tiang);
      });
    });
  </script>