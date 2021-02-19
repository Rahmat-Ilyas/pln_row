<?php 
require('template/header.php');

$result = mysqli_query($conn, "SELECT * FROM tb_anggota");

if (isset($_POST['submit'])) {
  $id = $_POST['id'];
  $status = $_POST['status'];
  if ($_POST['password'] != '') {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $query = "UPDATE tb_anggota SET status='$status', password='$password' WHERE id='$id'";
  } else {
    $query = "UPDATE tb_anggota SET status='$status' WHERE id='$id'";
  }

  if (mysqli_query($conn, $query)) {
    $response = [
      'status' => 'success',
      'message' => 'User berhasil diupdate',
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
            <h4 class="page-title">Data Anggota</h4>
            <ol class="breadcrumb">
              <li>
                <a href="#">Kepala Devisi</a>
              </li>
              <li class="active">
                Data Anggota
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
                    <th width="50">Foto</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Telepon</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($result as $dta) { ?>
                    <tr>
                      <td>
                        <img height="50" width="50" src="../assets/images/anggota/<?= $dta['foto'] ?>" class="img-circle">
                      </td>
                      <td><?= $dta['nip'] ?></td>
                      <td><?= $dta['nama'] ?></td>
                      <td><?= $dta['telepon'] ?></td>
                      <td><?= $dta['username'] ?></td>
                      <td>
                        <?php
                        if ($dta['status'] == 'new') {
                          $status = 'Baru Mendaftar';
                          $color = 'default';
                        } else if ($dta['status'] == 'active') {
                          $status = 'Anggota Aktif';
                          $color = 'success';
                        }  else if ($dta['status'] == 'suspended') {
                          $status = 'Suspended';
                          $color = 'danger';
                        }
                        $set_status = [['new', 'Baru Mendaftar'], ['active', 'Anggota Aktif'], ['suspended', 'Suspended']];
                        ?>
                        <span class="label label-table label-<?= $color ?>"><?= $status ?></span>
                      </td>
                      <td style="width: 100px;">
                        <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-edit<?= $dta['id'] ?>"><i class="fa fa-user"></i>&nbsp; Update Akun</a>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php foreach ($result as $dta) { ?>
  <!-- MODAL EDIT -->
  <div class="modal fade" tabindex="-1" role="dialog" id="modal-edit<?= $dta['id'] ?>">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Modal Heading</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST">
          <div class="modal-body px-5" style="margin-bottom: -20px;">
            <div class="form-group row">
              <label class="col-sm-3">Status Anggota</label>
              <div class="col-sm-9">
                <input type="hidden" name="id" value="<?= $dta['id'] ?>">
                <select class="form-control" name="status">
                  <?php foreach ($set_status as $sts) { 
                    if ($dta['status'] == $sts[0]) $select = 'selected';
                    else $select = ''; ?>
                    <option value="<?= $sts[0] ?>" <?= $select ?>><?= $sts[1] ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3">Update Password</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="password" autocomplete="off" placeholder="Update Password..">
                <span><i>*Masukkan password baru untuk mengupdate password</i></span>
              </div>
            </div>
          </div>
          <div class="modal-footer bg-whitesmoke br">
            <button type="submit" name="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          </div>
        </form>
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