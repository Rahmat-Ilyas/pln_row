<?php 
require('config.php');

if (isset($_SESSION['login_kldevis'])) header("location: kepala-devisi/");
if (isset($_SESSION['login_timrow'])) header("location: tim-row/");

$password = null;
$username = null;
$err_user = false;
$err_pass = false;

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $kldevisi = mysqli_query($conn, "SELECT * FROM tb_kepaladevisi WHERE username = '$username'");
    $get = mysqli_fetch_assoc($kldevisi);

    if ($get) {
        $get_password = $get['password'];
        if (password_verify($password, $get_password)) {
            $_SESSION['login_kldevis'] = $get_password;
            header("location: kepala-devisi/");
            exit();
        } else $err_pass = true;
    } else {
        $timrow = mysqli_query($conn, "SELECT * FROM tb_anggota WHERE username = '$username'");
        $get = mysqli_fetch_assoc($timrow);
        if ($get && $get['status'] == 'active') {
            $get_password = $get['password'];
            $get_id = $get['id'];
            if (password_verify($password, $get_password)) {
                $_SESSION['login_timrow'] = $get_password;
                $_SESSION['anggota_id'] = $get_id;
                header("location: tim-row/");
                exit();
            } else $err_pass = true;
        } else $err_user = true;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <link rel="shortcut icon" href="assets/images/pln_fav.png">

    <title>Login Page - PLN UPT Makassar</title>

    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="assets/js/modernizr.min.js"></script>

</head>
<body>

    <div class="account-pages"></div>
    <div class="clearfix"></div>
    <div class="wrapper-page">
       <div class=" card-box">
        <div class="panel-heading m-b-0"> 
            <h3 class="text-center"><img src="assets/images/pln_logo.png" height="50"/> <strong>PLN UPT</strong></h3>
        </div> 


        <div class="panel-body">
            <form class="form-horizontal m-t-20" method="POST">

                <div class="form-group ">
                    <div class="col-xs-12">
                        <input class="form-control" type="text" required="" placeholder="Username" name="username">
                        <?php if ($err_user == true) { ?>
                            <div class="text-danger">Username tidak ditemukan atau belum aktif</div>  
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12">
                        <input class="form-control" type="password" required="" placeholder="Password" name="password">
                        <?php if ($err_pass == true) { ?>
                            <div class="text-danger">Password tidak sesuai</div>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group text-center m-t-40">
                    <div class="col-xs-12">
                        <button class="btn btn-warning btn-block text-uppercase waves-effect waves-light" name="login" type="submit">Log In</button>
                    </div>
                </div>
            </form> 

        </div>   
    </div>
    <div class="row">
        <div class="col-sm-12 text-center">
            <p>Daftar anggota Tim Rows? <a href="registrasi.php" class="text-primary m-l-5"><b>Disini</b></a></p>

        </div>
    </div>
</div>




<script>
    var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/detect.js"></script>
<script src="assets/js/fastclick.js"></script>
<script src="assets/js/jquery.slimscroll.js"></script>
<script src="assets/js/jquery.blockUI.js"></script>
<script src="assets/js/waves.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/jquery.nicescroll.js"></script>
<script src="assets/js/jquery.scrollTo.min.js"></script>


<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

</body>
</html>