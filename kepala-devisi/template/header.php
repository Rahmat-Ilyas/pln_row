<?php 
require('../config.php');

if (!isset($_SESSION['login_kldevis'])) header("location: ../login.php");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
  <meta name="author" content="Coderthemes">

  <link rel="shortcut icon" href="../assets/images/pln_fav.png">

  <title>Tim ROW - PLN UPT Makassar</title>

  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="../assets/css/core.css" rel="stylesheet" type="text/css" />
  <link href="../assets/css/components.css" rel="stylesheet" type="text/css" />
  <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
  <link href="../assets/css/pages.css" rel="stylesheet" type="text/css" />
  <link href="../assets/css/responsive.css" rel="stylesheet" type="text/css" />

  <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
      <![endif]-->

      <script src="../assets/js/modernizr.min.js"></script>

    </head>

    <body class="fixed-left">

      <!-- Begin page -->
      <div id="wrapper">

        <!-- Top Bar Start -->
        <div class="topbar">

          <!-- LOGO -->
          <div class="topbar-left">
            <div class="text-center">
              <a href="index.html" class="logo">
                <i class="icon-c-logo"> <img src="../assets/images/pln_logo.png" height="40"/> </i>
                <span><img src="../assets/images/pln_logo.png" height="40"/> PLN UPT</span>
              </a>
            </div>
          </div>

          <!-- Button mobile view to collapse sidebar menu -->
          <div class="navbar navbar-default" role="navigation">
            <div class="container">
              <div class="">
                <div class="pull-left">
                  <button class="button-menu-mobile open-left waves-effect waves-light">
                    <i class="md md-menu"></i>
                  </button>
                  <span class="clearfix"></span>
                </div>


                <ul class="nav navbar-nav navbar-right pull-right">
                  <li class="dropdown top-menu-item-xs">
                    <a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true"><img src="../assets/images/users/avatar-1.jpg" alt="user-img" class="img-circle"> </a>
                    <ul class="dropdown-menu">
                      <li><a href="javascript:void(0)"><i class="ti-user m-r-10 text-custom"></i> Profile</a></li>
                      <li class="divider"></li>
                      <li><a href="../logout.php"><i class="ti-power-off m-r-10 text-danger"></i> Logout</a></li>
                    </ul>
                  </li>
                </ul>
              </div>
              <!--/.nav-collapse -->
            </div>
          </div>
        </div>
        <!-- Top Bar End -->


        <!-- ========== Left Sidebar Start ========== -->

        <div class="left side-menu">
          <div class="sidebar-inner slimscrollleft">
            <!--- Divider -->
            <div id="sidebar-menu">
              <ul>
                <li class="has_sub">
                  <a href="javascript:void(0);" class="waves-effect"><i class="ti-dashboard"></i> <span> Dashboard </span></a>
                </li>

                <li class="has_sub">
                  <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-table"></i> <span>Data Pengerjaan </span> <span class="menu-arrow"></span> </a>
                  <ul class="list-unstyled">
                    <li><a href="ui-buttons.html">Pengerjaan Baru</a></li>
                    <li><a href="ui-loading-buttons.html">Riwayat Pengerjaan</a></li>
                  </ul>
                </li>

                <li class="has_sub">
                  <a href="javascript:void(0);" class="waves-effect"><i class="ti-star"></i><span> Rating Pengerjaan</span> </a>
                </li>

                <li class="has_sub">
                  <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-file-text-o"></i><span> Laporan </span> </a>
                </li>

                <li class="has_sub">
                  <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-calendar"></i><span> Priode Laporan </span> </a>
                </li>

                <li class="has_sub">
                  <a href="set-radius.php" class="waves-effect"><i class="fa fa-map-o"></i><span> Set Radius </span> </a>
                </li>

                <li class="has_sub">
                  <a href="javascript:void(0);" class="waves-effect"><i class="ti-id-badge"></i> <span> Data Anggota </span> </a>
                </li>

              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="content-page">