<?php 
require('template/header.php');

$result = mysqli_query($conn, "SELECT * FROM tb_anggota");

$priode = mysqli_query($conn, "SELECT * FROM tb_priode_laporan ORDER BY id DESC");
$prd = mysqli_fetch_assoc($priode);
$priode_mulai = strtotime($prd['tanggal_mulai']);
$priode_akhir = strtotime($prd['tanggal_akhir']);
?>
<div class="content">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <!-- Page-Title -->
        <div class="row">
          <div class="col-sm-12">
            <h4 class="page-title">Cetak Laporan Anggota</h4>
            <ol class="breadcrumb">
              <li>
                <a href="#">Kepala Devisi</a>
              </li>
              <li>
                Kelola Laporan
              </li>
              <li class="active">
                Cetak Laporan
              </li>
            </ol>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="card-box table-responsive">
              <h4 class="m-t-0 header-title"><b>Data Laporan per priode: <?= date('d M', strtotime($priode_mulai)) ?> - <?= date('d M', strtotime($priode_akhir)) ?></b></h4>

              <table id="datatable" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th width="10">No</th>
                    <th>NIP</th>
                    <th>Nama Anggota</th>
                    <th>Jmlh Pengerjaan</th>
                    <th>Jmlh Kegiatan</th>
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
                          <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-edit<?= $dta['id'] ?>"><i class="fa fa-list"></i>&nbsp; Detail Laporan</a>
                          <div class="btn-group">
                            <button type="button" class="btn btn-pink dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-print"></i> Print Laporan <span class="caret"></span></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                              <li>
                                <a href="#" id="print-inspeksi">
                                  <i class="fa fa-file-excel-o"></i> Laporan Inspeksi
                                </a>
                              </li>
                              <li>
                                <a href="#" id="print-formulir">
                                  <i class="fa fa-file-pdf-o"></i> Laporan Inspeksi Formulir
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
        </div>
      </div>
    </div>
  </div>

  <div id="inspeksi" class="bg-white" hidden="">
    <h2 class="text-center">LAPORAN INSPEKSI</h2>
    <div class="row"> 
      <div class="col-sm-1" style="width: 8%; float: left;">
        <img src="../assets/images/pln_logo.png" height="80">
      </div>
      <div class="col-sm-3" style="width: 35%; float: left;">
        <span>UNIT: UPT SISTEM SULSELRABAR</span><br>
        <span>GARDU INDUK: GUDANG UPT SULSELRABAR</span><br>
        <span>PENGHANTAR: -</span><br>
        <span>TOWER/JOIN: 2</span><br>
      </div>
      <div class="col-sm-3" style="width: 32%; float: left;">
        <span>GROUNDPATROL: RUSLAN</span><br>
        <span>PRIODE: -</span><br>
        <span>DARI: 01-05-2020</span><br>
        <span>SAMPAI: 31-05-2020</span><br>
      </div>
      <div class="col-sm-3" style="width: 25%; float: left;">
        <span>EXPORT TANGGAL: <?= date('d-m-Y') ?></span>      
      </div>
    </div>
    <table id="datatable" class="table table-striped table-bordered">
      <thead>
        <tr>
          <th width="10">No</th>
          <th>UNIT</th>
          <th>GARDU INDUK</th>
          <th>FORMULIR</th>
          <th>PRIODE</th>
          <th>GROUNDPATROL</th>
          <th>TANGGAL</th>
          <th>TOTAL KERUSAKAN</th>
          <th>DURASI (MENIT)</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>UPT SISTEM SULSELRABAR</td>
          <td>GUDANG UPT SULSELRABAR</td>
          <td>TOWE DAN SPAN</td>
          <td>TRIWULAN</td>
          <td>RUSLAN</td>
          <td>21-MAY-20</td>
          <td>1</td>
          <td>6.57</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div id="inspeksi-formulir" class="bg-white" hidden="">
    <h2 class="text-center">LAPORAN INSPEKSI FORMULIR MANDOR LINE</h2>
    <table id="datatable" class="table table-striped table-bordered">
      <thead>
        <tr>
          <th width="10">No</th>
          <th>KOMPONEN/ FORMULIR</th>
          <th>KETERANGAN</th>
          <th>SASARAN PEMERIKSAAN</th>
          <th>FOTO</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Tangga Panjat</td>
          <td>Komponen ini berfun untuk memberikan sesuatu yang sangat melakuannv</td>
          <td>Periksa kondisi untuk panjat</td>
          <td>
            <img src="../assets/images/pln_logo.png" width="200">
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <?php
  require('template/footer.php');
  ?>

  <script type="text/javascript">
    $(document).ready(function($) {
      $('#print-inspeksi').click(function(event) {
        $('#inspeksi').printArea();
      });

      $('#print-formulir').click(function(event) {
        $('#inspeksi-formulir').printArea();
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