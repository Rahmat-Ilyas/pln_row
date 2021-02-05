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
                  <td><?= $dta['formulir'] ?></td>
                  <td><?= $dta['keterangan'] ?></td>
                  <td><?= $dta['nomor_tiang'] ?></td>
                  <td>
                    <?= date('d M', strtotime($dta['tggl_mulai'])).' - '.date('d M', strtotime($dta['tggl_selesai'])) ?>
                  </td>
                  <td><?= $priode ?></td>
                  <td><?= $dta['lokasi'] ?></td>
                  <td><?= $total_kegiatan ?> Kegiatan</td>
                  <td class="text-center" style="width: 120px;">
                    <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-add" data-toggle1="tooltip" title="" data-original-title="Tambah Kegiatan Pengerjaan"><i class="md-my-library-add"></i></a>
                    <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-dta" data-toggle1="tooltip" title="" data-original-title="Kelola Data Kegiatan"><i class="md-assignment"></i></a>
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

<?php 
require('template/footer.php');
?>
<script type="text/javascript">
  $(document).ready(function($) {

  });
</script>