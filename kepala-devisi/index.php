<?php 
require('template/header.php');
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
            <h4 class="page-title">Dashboard</h4>
            <ol class="breadcrumb">
              <li>
                <a href="#">Kepala Devisi</a>
              </li>
              <li class="active">
                Dashboard
              </li>
            </ol>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="card-box">
              <div class="row">
                <div class="col-md-12">
                  <div class="text-center slider-bg m-b-0">
                    <div class="panel-body p-0">
                      <div class="item">
                        <h3><span class="text-custom font-600">Hey! Selamat Datang</span></h3>
                        <p class="small"><?= date('d F, Y') ?></p>
                        <p class="m-t-30"><em>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</em></p>
                      </div>
                    </div>
                  </div>
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

