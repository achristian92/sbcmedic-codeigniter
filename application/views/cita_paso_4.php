<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
<base href="https://www.sbcmedic.com/consulta/" target="_blank">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SBCMedic | Reservar Cita</title>
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
  <link rel="stylesheet" href="css/icomoon.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
  .align-items-center {
    -ms-flex-align: center!important;
    align-items: center!important;
}
.align-text-bottom {
    vertical-align: text-bottom!important;
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

.btn-secondary {
    color: #fff;
    background-color: #22b573;
    border-color: #357ebd; /*set the color you want here*/
}
.btn-secondary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary {
    color: #fff;
    background-color: #004862;
    border-color: #285e8e; /*set the color you want here*/
}

.btn-info {
    color: #fff;
    background-color: #90b9d4;
    border-color: #004862; /*set the color you want here*/
}
.btn-info:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary {
    color: #fff;
    background-color: #004862;
    border-color: #285e8e; /*set the color you want here*/
}

.btn-appointment {
    color: #004761;
    background-color: #fff;
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
  border-radius: 145px;
	-webkit-box-shadow: 0 8px 6px -6px black;
	   -moz-box-shadow: 0 8px 6px -6px black;
	        box-shadow: 0 8px 6px -6px black;
}

.card-shadow {
  border-radius: 16px;
	-webkit-box-shadow: 0 8px 6px -6px black;
	   -moz-box-shadow: 0 8px 6px -6px black;
	        box-shadow: 0 8px 6px -6px black;
}


h1, h2, h3, h4, h5, h6 {
    
    
}

.card {

  border: 1px solid rgba(52,172,113,.9);
}

.font-green{
  color: #34ac71;
}

.font-blue {
  color: #004862;
}

.step-active {
  background-color: #22b573;
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
    <ul class="navbar-nav h-100 align-items-center col-md-6">
      <li class="nav-item" style=";">
        <span style="vertical-align:middle;  "><a class="nav-link" data-widget="pushmenu" href="#" role="button" style="height: auto;"><img src="img/logo_cruz.png" /> <span style="margin-left: 10px;font-size: 2.4rem;font-weight:bold;color: #004663;vertical-align:middle;  "> Reservar Cita<span></a></span>
      </li>
      
    </ul>
    <div class="col-md-6 text-right">
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-info" style="margin-left: 10px;border-radius: 20px;height: 40px;width: 40px;"> 1 </button>
            <button type="button" class="btn btn-info" style="margin-left: 10px;border-radius: 20px;height: 40px;width: 40px;"> 2 </button>
            <button type="button" class="btn btn-info step-active" style="margin-left: 10px;border-radius: 20px;height: 40px;width: 40px;"> 3 </button>
            <button type="button" class="btn btn-info" style="margin-left: 10px;border-radius: 20px;height: 40px;width: 40px;"> 4 </button>
        </div>
    </div>

   

  
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4" style=" background-image: repeating-linear-gradient(to bottom, #22b473, #5897bc);">
    <!-- Brand Logo -->
    <div class="row align-items-center" style="height: 100px;padding: 20px;">
    <div class="col-md-4 text-center">    
      <img src="img/logo_aside.png"
           alt="AdminLTE Logo"
           class=""
           style="opacity: .9">
    </div>
    <div class="col-md-8">
      <span class="brand-link brand-text font-weight-light" style="border:none;padding: .1rem .2rem;">Cesar Lopez</span>
      <span class="brand-link brand-text font-weight-light" style="border:none;padding: .1rem .2rem;">DNI 70359899</span>
    </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
     

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          
          <li class="nav-item">
          <a href="../widgets.html" class="btn btn-primary w-100 mt-2 text-left">
              <img src="img/logo_nueva_cita.png" />
              <span class="ml-2">NUEVA CITA</span>
            </a>
          </li>
          <li class="nav-item">
          <a href="../widgets.html" class="btn btn-primary w-100 mt-2 text-left">
          <img src="img/logo_mis_citas.png" />
          <span class="ml-2">Mis Citas</span>                              
            </a>
          </li>         
          <li class="nav-item">
          <a href="../widgets.html" class="btn btn-primary w-100 mt-2 text-left">
          <img src="img/logo_historial.png" />             
          <span class="ml-2">Historial</span>                              
            </a>
          </li>
          <li class="nav-item">
          <a href="../widgets.html" class="btn btn-primary w-100 mt-2 text-left">
          <img src="img/logo_solicitar_examenes.png" />               
          <span class="ml-2">Solicitar Exámenes</span>                              
            </a>
          </li>
          <li class="nav-item">
          <a href="../widgets.html" class="btn btn-primary w-100 mt-2 text-left">
          <img src="img/logo_resultados.png" />               
          <span class="ml-2">Resultados</span>
            </a>
          </li>
          <li class="nav-item">
          <a href="../widgets.html" class="btn btn-primary w-100 mt-2 text-left">
          <img src="img/logo_farmacia.png" />               
          <span class="ml-2">Farmacia</span>
            </a>
          </li>
         
          
      
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

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
    <section class="content" style="padding: 20px;">

      <!-- Default box -->
      <div class="card card-shadow">
      <div class="card-header">
      <div class="row">
       <div class="col-md-2">
          <button style="margin-top: 5px;" type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                <img src="img/logo_sbcmedic.png" style="width:100%;" class=""/></button>
        </div>
        <div class="col-md-10 h-100 align-items-center" style="padding-top: 10px;">
        <ul class="nav nav-pills ml-auto p-2" style="float: right;">
                  <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Boleta</a></li>
                  <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Factura</a></li>                                    
                </ul>
        </div>
      </div>
      </div>        
        <div class="card-body" >
        <div class="tab-content">
          <div class="tab-pane active" id="tab_1">
              <div class="form-group row">        
                <label for="inputEmail3" class="col-sm-4 col-form-label"><i class="icon icon-check"></i>1. Nombre Completo :</label>
                <div class="col-sm-8">
                   <input type="text" class="form-control" id="inputEmail3" placeholder="" value="Martes 23 de Octubre de 2020" readonly>
                </div>
              </div>
              <div class="form-group row">        
                <label for="inputEmail3" class="col-sm-4 col-form-label"><i class="icon icon-check"></i>2. Concepto :</label>
                <div class="col-sm-8">
                   <input type="text" class="form-control" id="inputEmail3" placeholder="" value="Martes 23 de Octubre de 2020" readonly>
                </div>
              </div>
              <div class="form-group row">        
                <label for="inputEmail3" class="col-sm-4 col-form-label"><i class="icon icon-check"></i>3. Precio :</label>
                <div class="col-sm-8">
                   <input type="text" class="form-control" id="inputEmail3" placeholder="" value="Martes 23 de Octubre de 2020" readonly>
                </div>
              </div>
              <div class="form-group row">        
                <div for="inputEmail3" class="col-sm-4 col-form-label"></div>
                <div class="col-sm-8">
                <span><a href="cita/paso/2" class="btn btn-primary">Ir a Pagar <i class="icon icon-search"></i></a></span>
                </div>
              </div>
          
          </div>
                  <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
              <div class="form-group row">        
                <label for="inputEmail3" class="col-sm-4 col-form-label"><i class="icon icon-check"></i>1. Razón Social :</label>
                <div class="col-sm-8">
                   <input type="text" class="form-control" id="inputEmail3" placeholder="" value="Martes 23 de Octubre de 2020" readonly>
                </div>
              </div>
              <div class="form-group row">        
                <label for="inputEmail3" class="col-sm-4 col-form-label"><i class="icon icon-check"></i>2. Número RUC :</label>
                <div class="col-sm-8">
                   <input type="text" class="form-control" id="inputEmail3" placeholder="" value="Martes 23 de Octubre de 2020" readonly>
                </div>
              </div>
              <div class="form-group row">        
                <label for="inputEmail3" class="col-sm-4 col-form-label"><i class="icon icon-check"></i>3. Dirección :</label>
                <div class="col-sm-8">
                   <input type="text" class="form-control" id="inputEmail3" placeholder="" value="Martes 23 de Octubre de 2020" readonly>
                </div>
              </div>
              <div class="form-group row">        
                <label for="inputEmail3" class="col-sm-4 col-form-label"><i class="icon icon-check"></i>4. Precio :</label>
                <div class="col-sm-8">
                   <input type="text" class="form-control" id="inputEmail3" placeholder="" value="Martes 23 de Octubre de 2020" readonly>
                </div>
              </div>
              <div class="form-group row">        
                <div for="inputEmail3" class="col-sm-4 col-form-label"></div>
                <div class="col-sm-8">
                <span><a href="cita/paso/2" class="btn btn-primary">Ir a Pagar <i class="icon icon-search"></i></a></span>
                </div>
              </div>
             </div>
          </div>
          

        </div>
        <!-- /.card-body -->
        
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer bg_transparent" style="padding: 20px;">
    <div class="row">
    <div class="col-md-6 text-left">
    <span><button class="btn btn-secondary">VOLVER</button></span>
    </div>
    <div class="col-md-6 text-right">
    <span><a href="cita/paso/2" class="btn btn-primary">CONTINUAR</a></span>
    </div>
    </div>
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

