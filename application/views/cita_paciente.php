<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
<base href="consulta">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SBCMedic | Reservar Cita</title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo  base_url('img/favicon.ico');?>"/>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- pace-progress -->
  <link rel="stylesheet" href="plugins/pace-progress/themes/black/pace-theme-flat-top.css">
  <!-- adminlte-->
  <link rel="stylesheet" href="css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
  .align-items-center {
    -ms-flex-align: center!important;
    align-items: center!important;
}

/*This is modifying the btn-primary colors but you could create your own .btn-something class as well*/
.btn-primary {
    color: #fff;
    background-color: #5996be;
    border-color: #357ebd; /*set the color you want here*/
}
.btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary {
    color: #fff;
    background-color: #004862;
    border-color: #285e8e; /*set the color you want here*/
}

.btn-info {
    color: #fff;
    background-color: #30b873;
    border-color: #357ebd; /*set the color you want here*/
}
.btn-info:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary {
    color: #fff;
    background-color: #004862;
    border-color: #285e8e; /*set the color you want here*/
}

.btn-reg {
    color: #fff;
    background-color: #d814fa;
    border-color: #CDE522; /*set the color you want here*/
}
.btn-reg:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary {
    color: #fff;
    background-color: #E040FB;
    border-color: #CDE522; /*set the color you want here*/
}

.btn-appointment {
    color: #fff;
    background-color: #5996be;
    border-color: #357ebd; /*set the color you want here*/
}
.btn-appointment:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary {
    color: #fff;
    background-color: #004862;
    border-color: #285e8e; /*set the color you want here*/
}

.btn-medicine {
    color: #fff;
    background-color: #004761;
    border-color: #357ebd; /*set the color you want here*/
}
.btn-medicine:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary {
    color: #fff;
    background-color: #5996be;
    border-color: #285e8e; /*set the color you want here*/
}

[class*=sidebar-dark-] .sidebar a {
    color: #fff;
}

[class*=sidebar-dark-] .sidebar a:hover {
    color: #c2c7d0; 
}

.one-edge-shadow {
  border-radius: 32px;
	-webkit-box-shadow: 0 8px 6px -6px black;
	   -moz-box-shadow: 0 8px 6px -6px black;
	        box-shadow: 0 8px 6px -6px black;
}

.one-edge-shadow:hover {
  border-radius: 32px;
	-webkit-box-shadow: 2px 12px 10px -6px black;
	   -moz-box-shadow: 2px 12px 10px -6px black;
	        box-shadow: 2px 12px 10px -6px black;
}

.bg_transparent {
  background-color: transparent;
}


  </style>
</head>
<body class="hold-transition sidebar-mini pace-primary" style="background-image: url(img/fondo_body.png); height: 100%;  background-position: right;  background-repeat: no-repeat;  ">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light bg_transparent" style="height: 100px;">
    <!-- Left navbar links -->
    <ul class="navbar-nav h-100 align-items-center">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item" style=";">
        <span style="vertical-align:middle;"><span style="margin-left: 10px;font-size: 2.4rem;font-weight:bold;color: #004663;vertical-align:middle;  "> Reservar Cita<span></span>
      </li>
    </ul>

    <?php $this->load->view("logout"); ?>
  </nav>
  <!-- /.navbar -->
  <?php $this->load->view('aside'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background: transparent;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div>
          <div class="col-sm-6">
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
    <?php if($rol == 1 || $rol == 4) { ?>
     <div class="row w-100 h-100 align-items-center" style="margin-bottom: 20px;">
     <div class="col-md-2 text-center"></div>
      <div class="col-md-8 text-center">
      <span style="vertical-align:middle;">
        <a href="registro" target="_blank" style="height: auto;"><span class="btn btn-reg w-45 mt-1" style="padding-left:10px;font-size: 2rem;font-weight:bold;color: #fff "> Registrar Paciente<span></a>
      </div>      
      <div class="col-md-2 text-center"></div>
     </div>
     <?php } ?>
     <div class="row w-100 h-100 align-items-center" style="margin-bottom: 20px;">
     <div class="col-md-2 text-center"></div>
      <div class="col-md-8 text-center">
      <span style="vertical-align:middle;  "><a href="https://api.whatsapp.com/send?phone=51919446233&text=Hola, necesetio una cita." target="_blank" style="height: auto;"><img height="130px" src="img/logo_nueva_cita_virtual.png" style="position: relative;z-index:5;margin-right: -10px;" class="one-edge-shadow br-32"/></span> <span  class="btn btn-info w-50 mt-2 text-left" style="padding-left:30px;padding-right:10px;z-index:1;font-size: 2rem;font-weight:bold;color: #fff;vertical-align:middle;  "><i class="fab fa-whatsapp"></i> Solicitar Cita<span></a>
      </div>      
      <div class="col-md-2 text-center"></div>
     </div>
 
     
    
 
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer bg_transparent">
    <div class="float-right d-none d-sm-block">
      <b>Versión</b> <?php echo $version["version"];?>
    </div>
    <strong>Copyright &copy; 2020 <a href="javascript:void(0)">SBCMedic</a>.</strong> Derechos Reservados.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- pace-progress -->
<script src="plugins/pace-progress/pace.min.js"></script>
<!-- AdminLTE App -->
<script src="js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="js/demo.js"></script>
</body>
</html>

