<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
  <meta name="author" content="Coderthemes">
  <!-- App Favicon icon -->
  <link rel="shortcut icon" href="../assets/images/pln_fav.png">

  <title>Tim ROW - PLN UPT Makassar</title>

  <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
  <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
  <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
  <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
  <link href="assets/css/menu.css" rel="stylesheet" type="text/css" />
  <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />

  <!-- DataTables -->
  <link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
  <link href="assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>

  <script src="assets/js/modernizr.min.js"></script>

</head>

<body>
  <header id="topnav">
    <div class="topbar-main">
      <div class="container">

        <!-- Logo container-->
        <div class="logo">
          <a href="index.html" class="logo">
            <span><img src="../assets/images/pln_logo.png" height="30"/> PLN UPT</span>
          </a>
        </div>

        <div class="menu-extras">
          <ul class="nav navbar-nav navbar-right pull-right">
            <li class="dropdown top-menu-item-xs">
              <a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true"><img src="assets/images/users/avatar-1.jpg" alt="user-img" class="img-circle"> </a>
              <ul class="dropdown-menu">
                <li><a href="javascript:void(0)"><i class="ti-user m-r-10 text-custom"></i> Profile</a></li>
                <li class="divider"></li>
                <li><a href="../logout.php"><i class="ti-power-off m-r-10 text-danger"></i> Logout</a></li>
              </ul>
            </li>
          </ul>
          <div class="menu-item">
            <!-- Mobile menu toggle-->
            <a class="navbar-toggle">
              <div class="lines">
                <span></span>
                <span></span>
                <span></span>
              </div>
            </a>
            <!-- End mobile menu toggle-->
          </div>
        </div>

      </div>
    </div>

    <div class="navbar-custom">
      <div class="container">
        <div id="navigation">
          <!-- Navigation Menu-->
          <ul class="navigation-menu">
            <li class="has-submenu">
              <a href="index.php"><i class="md md-dashboard"></i>Dashboard</a>
            </li>

            <li class="has-submenu">
              <a href="javascript:void(0);" class="waves-effect"><i class="ti-truck"></i> <span> Pengerjaan </span></a>
              <ul class="submenu">
                <li><a href="pengerjaan-baru.php">Pengerjaan Baru</a></li>
                <li><a href="laporan-pengerjaan.php">Laporan Pengerjaan</a></li>
                <li><a href="ui-loading-buttons.html">Riwayat Pengerjaan</a></li>
              </ul>
            </li>

            <li class="has-submenu">
              <a href="javascript:void(0);" class="waves-effect"><i class="ti-star"></i><span> Rating </span> </a>
            </li>

            <li class="has-submenu">
              <a href="javascript:void(0);" class="waves-effect"><i class="ti-id-badge"></i> <span> Data Anggota </span> </a>
            </li>

          </ul>
        </div>
      </div> <!-- end container -->
    </div> <!-- end navbar-custom -->
  </header>
  <!-- End Navigation Bar-->


  <div class="wrapper">
    <div class="container">