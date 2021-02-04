<?php 
require('template/header.php');
?>

<div class="row">
  <div class="col-sm-12">
    <!-- Page-Title -->
    <div class="row">
      <div class="col-sm-12">
        <h4 class="page-title">Laporan Pengerjaan</h4>
        <ol class="breadcrumb">
          <li>
            <a href="#">Pengerjaan</a>
          </li>
          <li class="active">
            Laporan Pengerjaan
          </li>
        </ol>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card-box table-responsive">
          <h4 class="m-t-0 header-title"><b>Data Pengerjaan</b></h4>

          <table id="datatable" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>No</th>
                <th>Komponen</th>
                <th>Keterangan</th>
                <th>Sasaran</th>
                <th>Laporan</th>
                <th>Aksi</th>
              </tr>
            </thead>


            <tbody>
              <tr>
                <td>1</td>
                <td>System Architect</td>
                <td>Edinburgh</td>
                <td>61</td>
                <td style="width: 100px;">
                  <a href="#" class="btn btn-sm btn-success"><i class="fa fa-file-text"></i>&nbsp; Buat Laporan</a>
                </td>
                <td class="text-center" style="width: 120px;">
                  <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal" data-toggle1="tooltip" title="" data-original-title="Detail"><i class="fa fa-eye"></i></a>
                  <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal" data-toggle1="tooltip" title="" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                  <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal" data-toggle1="tooltip" title="" data-original-title="Hapus"><i class="fa fa-trash"></i></a>
                </td>
              </tr>
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