<?php 
require('template/header.php');

$result = mysqli_query($conn, "SELECT * FROM tb_anggota");

$priode = mysqli_query($conn, "SELECT * FROM tb_priode_laporan ORDER BY id DESC");
$prd = mysqli_fetch_assoc($priode);
?>
<div class="content">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <!-- Page-Title -->
        <div class="row">
          <div class="col-sm-12">
            <h4 class="page-title">Arsip Laporan Anggota</h4>
            <ol class="breadcrumb">
              <li>
                <a href="#">Kepala Devisi</a>
              </li>
              <li>
                Kelola Laporan
              </li>
              <li class="active">
                Arsip Laporan
              </li>
            </ol>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="card-box table-responsive">
              <h4 class="m-t-0 header-title"><b>Data Laporan per periode</b></h4>
              <div class="panel-group" id="accordion-test-2"> 
                <?php $xn=1; foreach ($priode as $prd) { 
                  $priode_mulai = strtotime($prd['tanggal_mulai']);
                  $priode_akhir = strtotime($prd['tanggal_akhir']); ?>
                  <div class="panel panel-default"> 
                    <div class="panel-heading"> 
                      <h4 class="panel-title"> 
                        <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseOne<?= $prd['id'] ?>" aria-expanded="false" class="collapsed">
                          <?= $xn ?>. PERIODE LAPORAN: <?= date('d M Y', strtotime($prd['tanggal_mulai'])).' - '.date('d M Y', strtotime($prd['tanggal_akhir'])) ?>
                        </a> 
                      </h4> 
                    </div> 
                    <div id="collapseOne<?= $prd['id'] ?>" class="panel-collapse collapse <?php if ($xn == 1) echo 'in' ?>"> 
                      <div class="panel-body">
                        <h4 style="margin-top: -15px;"><u>Laporan Anggota</u></h4>
                        <table class="datatable table table-striped table-bordered">
                          <thead>
                            <tr>
                              <th width="10">No</th>
                              <th>NIP</th>
                              <th>Nama Anggota</th>
                              <th>Jumlah Pengerjaan</th>
                              <th>Jumlah Kegiatan</th>
                              <th width="270">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php $no = 1; foreach ($result as $dta) { 
                              $anggota_id = $dta['id'];
                              $pengerjaan = mysqli_query($conn, "SELECT * FROM tb_pengerjaan WHERE anggota_id='$anggota_id'");
                              $jum_pengerjaan = 0;
                              $jum_kegiatan = 0;
                              foreach ($pengerjaan as $pgr) {
                                $pgr_mulai = strtotime($pgr['tggl_mulai']);
                                $pgr_selesai = strtotime($pgr['tggl_selesai']);
                                if ($priode_mulai < $pgr_mulai && $priode_akhir > $pgr_mulai || $priode_mulai < $pgr_selesai && $priode_akhir > $pgr_selesai) {
                                  $jum_pengerjaan = $jum_pengerjaan + 1;

                                  $pgr_id = $pgr['id'];
                                  $kegiatan = mysqli_query($conn, "SELECT * FROM tb_kegiatan WHERE pengerjaan_id='$pgr_id' AND status='accept'");
                                  foreach ($kegiatan as $kgt) {
                                    $kgt_mulai = strtotime($kgt['waktu_mulai']);
                                    $kgt_selesai = strtotime($kgt['waktu_selesai']);
                                    if ($priode_mulai < $kgt_mulai && $priode_akhir > $kgt_mulai || $priode_mulai < $kgt_selesai && $priode_akhir > $kgt_selesai) {
                                      $jum_kegiatan = $jum_kegiatan + 1;
                                    }
                                  }
                                }
                              } 
                              if ($jum_kegiatan > 0) { ?>
                                <tr>
                                  <td><?= $no ?></td>
                                  <td><?= $dta['nip'] ?></td>
                                  <td><?= $dta['nama'] ?></td>
                                  <td><?= $jum_pengerjaan ?> Pengerjaan</td>
                                  <td><?= $jum_kegiatan ?> Kegiatan</td>
                                  <td class="text-center">
                                    <a href="#" class="btn btn-sm btn-info view-lapran" data-toggle="modal" data-target="#modal-view" data-id="<?= $dta['id'] ?>" prd-id="<?= $prd['id'] ?>"><i class="fa fa-eye"></i>&nbsp; Lihat Laporan</a>
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-pink dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-print"></i> Print Laporan <span class="caret"></span></button>
                                      <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                        <li>
                                          <a href="#" class="print-inspeksi" data-id="<?= $dta['id'] ?>" prd-id="<?= $prd['id'] ?>">
                                            Laporan Inspeksi
                                          </a>
                                        </li>
                                        <li>
                                          <a href="#" class="print-formulir" data-id="<?= $dta['id'] ?>" prd-id="<?= $prd['id'] ?>">
                                            Laporan Inspeksi Formulir
                                          </a>
                                        </li>
                                      </ul>
                                    </div>
                                  </td>
                                </tr>
                                <?php $no=$no+1; } 
                              }?>
                            </tbody>
                          </table>
                        </div> 
                      </div> 
                    </div>
                    <?php $xn = $xn + 1; 
                  } ?>
                </div> 
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL REVIEW LAPORAN -->
  <div class="modal" id="modal-view" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Preview Laporan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body" style="font-size: 12px;">
          <div class="m-t-0 m-b-20">
            <h3 class="text-center">LAPORAN INSPEKSI</h3>
            <div class="row"> 
              <div class="col-sm-1" style="width: 8%; float: left;">
                <img src="../assets/images/pln_logo.png" height="80">
              </div>
              <div class="col-sm-3" style="width: 35%; float: left;">
                <span>UNIT: </span><span class="unit"></span><br>
                <span>GARDU INDUK: </span><span class="gardu_induk"></span><br>
                <span>PENGHANTAR: -</span><br>
                <span>TOWER/JOIN: </span><span class="nomor_tiang"></span><br>
              </div>
              <div class="col-sm-3" style="width: 32%; float: left;">
                <span>GROUNDPATROL: </span><span class="anggota"></span><br>
                <span>PERIODE: </span><span class="priode"></span><br>
                <span>DARI: </span><span class="priode_mulai"></span><br>
                <span>SAMPAI: </span><span class="priode_akhir"></span><br>
              </div>
              <div class="col-sm-3" style="width: 25%; float: left;">
                <span>EXPORT TANGGAL: </span><span class="export"></span>      
              </div>
            </div>
            <table id="datatable" class="table table-striped table-bordered m-t-10">
              <thead>
                <tr>
                  <th width="10">No</th>
                  <th>UNIT</th>
                  <th>GARDU INDUK</th>
                  <th>FORMULIR</th>
                  <th>PERIODE</th>
                  <th>GROUNDPATROL</th>
                  <th>TANGGAL</th>
                  <th>TOTAL KERUSAKAN</th>
                  <th>DURASI (MENIT)</th>
                </tr>
              </thead>
              <tbody id="dtl_table">

              </tbody>
            </table>
          </div>
          <div style="border: dashed 3px;" class="m-t-10"></div>
          <div class="m-t-20">
            <h3 class="text-center">LAPORAN INSPEKSI FORMULIR MANDOR LINE</h3>
            <div class="row"> 
              <div class="col-sm-1" style="width: 8%; float: left;">
                <img src="../assets/images/pln_logo.png" height="80">
              </div>
              <div class="col-sm-3" style="width: 35%; float: left;">
                <span>UNIT: </span><span class="unit"></span><br>
                <span>GARDU INDUK: </span><span class="gardu_induk"></span><br>
                <span>PENGHANTAR: -</span><br>
                <span>TOWER/JOIN: </span><span class="nomor_tiang"></span><br>
              </div>
              <div class="col-sm-3" style="width: 32%; float: left;">
                <span>GROUNDPATROL: </span><span class="anggota"></span><br>
                <span>PERIODE: </span><span class="priode"></span><br>
                <span>DARI: </span><span class="priode_mulai"></span><br>
                <span>SAMPAI: </span><span class="priode_akhir"></span><br>
              </div>
              <div class="col-sm-3" style="width: 25%; float: left;">
                <span>EXPORT TANGGAL: </span><span class="export"></span>      
              </div>
            </div>
            <table id="datatable" class="table table-striped table-bordered m-t-10">
              <thead>
                <tr>
                  <th width="10">No</th>
                  <th>KOMPONEN/ FORMULIR</th>
                  <th>KETERANGAN</th>
                  <th>SASARAN PEMERIKSAAN</th>
                  <th>FOTO</th>
                </tr>
              </thead>
              <tbody id="dtl_table1">
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <!-- PRINT AREA -->
  <div id="inspeksi" class="bg-white" hidden="">
    <h2 class="text-center">LAPORAN INSPEKSI</h2>
    <div class="row"> 
      <div class="col-sm-1" style="width: 8%; float: left;">
        <img src="../assets/images/pln_logo.png" height="80">
      </div>
      <div class="col-sm-3" style="width: 35%; float: left;">
        <span>UNIT: </span><span class="unit"></span><br>
        <span>GARDU INDUK: </span><span class="gardu_induk"></span><br>
        <span>PENGHANTAR: -</span><br>
        <span>TOWER/JOIN: </span><span class="nomor_tiang"></span><br>
      </div>
      <div class="col-sm-3" style="width: 32%; float: left;">
        <span>GROUNDPATROL: </span><span class="anggota"></span><br>
        <span>PERIODE: </span><span class="priode"></span><br>
        <span>DARI: </span><span class="priode_mulai"></span><br>
        <span>SAMPAI: </span><span class="priode_akhir"></span><br>
      </div>
      <div class="col-sm-3" style="width: 25%; float: left;">
        <span>EXPORT TANGGAL: </span><span class="export"></span>      
      </div>
    </div>
    <table id="datatable" class="table table-striped table-bordered m-t-10">
      <thead>
        <tr>
          <th width="10">No</th>
          <th>UNIT</th>
          <th>GARDU INDUK</th>
          <th>FORMULIR</th>
          <th>PERIODE</th>
          <th>GROUNDPATROL</th>
          <th>TANGGAL</th>
          <th>TOTAL KERUSAKAN</th>
          <th>DURASI (MENIT)</th>
        </tr>
      </thead>
      <tbody id="dt_table">

      </tbody>
    </table>
  </div>

  <div id="inspeksi-formulir" class="bg-white" style="font-size: 11px;" hidden="">
    <h2 class="text-center">LAPORAN INSPEKSI FORMULIR MANDOR LINE</h2>
    <div class="row"> 
      <div class="col-sm-1" style="width: 8%; float: left;">
        <img src="../assets/images/pln_logo.png" height="80">
      </div>
      <div class="col-sm-3" style="width: 35%; float: left;">
        <span>UNIT: </span><span class="unit"></span><br>
        <span>GARDU INDUK: </span><span class="gardu_induk"></span><br>
        <span>PENGHANTAR: -</span><br>
        <span>TOWER/JOIN: </span><span class="nomor_tiang"></span><br>
      </div>
      <div class="col-sm-3" style="width: 32%; float: left;">
        <span>GROUNDPATROL: </span><span class="anggota"></span><br>
        <span>PERIODE: </span><span class="priode"></span><br>
        <span>DARI: </span><span class="priode_mulai"></span><br>
        <span>SAMPAI: </span><span class="priode_akhir"></span><br>
      </div>
      <div class="col-sm-3" style="width: 25%; float: left;">
        <span>EXPORT TANGGAL: </span><span class="export"></span>      
      </div>
    </div>
    <table id="datatable" class="table table-striped table-bordered m-t-10">
      <thead>
        <tr>
          <th width="10">No</th>
          <th>KOMPONEN/ FORMULIR</th>
          <th>KETERANGAN</th>
          <th>SASARAN PEMERIKSAAN</th>
          <th>FOTO</th>
        </tr>
      </thead>
      <tbody id="dt_table1">
      </tbody>
    </table>
  </div>

  <?php
  require('template/footer.php');
  ?>

  <script type="text/javascript">
    $(document).ready(function($) {
      $('.print-inspeksi').click(function(event) {
        var anggota_id = $(this).attr('data-id');
        var priode_id = $(this).attr('prd-id');
        $.ajax({
          url     : '../controller.php',
          method  : "POST",
          data    : { anggota_id: anggota_id, priode_id: priode_id, req: 'arsipInspeksi' },
          success : function(data) {
            $('.unit').text(data.unit);
            $('.gardu_induk').text(data.gardu_induk);
            $('.nomor_tiang').text(data.nomor_tiang);
            $('.anggota').text(data.anggota);
            $('.priode').text(data.priode);
            $('.priode_mulai').text(data.priode_mulai);
            $('.priode_akhir').text(data.priode_akhir);
            $('.export').text(data.export);
            $('#dt_table').html(data.dt_table);

            $('#inspeksi').printArea();
          }
        });
      });

      $('.print-formulir').click(function(event) {
        var anggota_id = $(this).attr('data-id');
        var priode_id = $(this).attr('prd-id');
        $.ajax({
          url     : '../controller.php',
          method  : "POST",
          data    : { anggota_id: anggota_id, priode_id: priode_id, req: 'arsipFormulir' },
          success : function(data) {
            $('.unit').text(data.unit);
            $('.gardu_induk').text(data.gardu_induk);
            $('.nomor_tiang').text(data.nomor_tiang);
            $('.anggota').text(data.anggota);
            $('.priode').text(data.priode);
            $('.priode_mulai').text(data.priode_mulai);
            $('.priode_akhir').text(data.priode_akhir);
            $('.export').text(data.export);
            $('#dt_table1').html(data.dt_table1);

            $('#inspeksi-formulir').printArea();
          }
        });
      });

      $('.view-lapran').click(function(event) {
        var anggota_id = $(this).attr('data-id');
        var priode_id = $(this).attr('prd-id');
        $.ajax({
          url     : '../controller.php',
          method  : "POST",
          data    : { anggota_id: anggota_id, priode_id: priode_id, req: 'arsipDetail' },
          success : function(data) {
            $('.unit').text(data.unit);
            $('.gardu_induk').text(data.gardu_induk);
            $('.nomor_tiang').text(data.nomor_tiang);
            $('.anggota').text(data.anggota);
            $('.priode').text(data.priode);
            $('.priode_mulai').text(data.priode_mulai);
            $('.priode_akhir').text(data.priode_akhir);
            $('.export').text(data.export);
            $('#dtl_table').html(data.dtl_table);
            $('#dtl_table1').html(data.dtl_table1);
          }
        });
      });

      <?php if (isset($response) && $response['status'] == 'success') { ?>
        Swal.fire({
          title: 'Berhasil Diupdte',
          text: "<?= $response['message'] ?>",
          type: 'success'
        }).then(function() {
          location.href = 'data-anggota.php';
        });
      <?php } else if (isset($response) && $response['status'] == 'error') { ?>
        Swal.fire({
          title: 'Terjadi Kesalahan',
          text: "<?= $response['message'] ?>",
          type: 'error'
        });
      <?php } ?>
    });
  </script>