<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SBCMedic | Reservar Cita</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=base_url('plugins/fontawesome-free/css/all.min.css');?>">
  <!-- adminlte-->
  <link rel="stylesheet" href="<?=base_url('css/adminlte.min.css');?>">
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

table.ablock   {
   width: 100%;
   border: 1px solid #000;
   border-spacing: 2px;
}

table.ablock td {
   width: 25%;
   text-align: left;
   vertical-align: top;
   border: 1px solid #000;
   border-collapse: collapse;
}


  </style>

</head>
<body class="hold-transition sidebar-mini pace-primary" style="background-image: url(img/fondo_body.png); height: 100%;  background-position: right;  background-repeat: no-repeat;  ">
<!-- Site wrapper -->
<div class="wrapper">
    
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

    <?=$contenido;?>
  </div>
  <!-- /.content-wrapper -->

  

 
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


</body>
</html>

