<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
<base href="consulta">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SBCMedic | Solicitar Examenes</title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo  base_url('img/favicon.ico');?>"/>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php $this->load->view("styles"); ?>
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
        <span style="vertical-align:middle;"> <span style="margin-left: 10px;font-size: 2.4rem;font-weight:bold;color: #004663;vertical-align:middle;  "> Solicitar Exámenes <span></span>
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
  

      <div class="container-fluid">

            <?php if($rol == 1 || $rol == 4) { ?>
      <form name="frmBusqueda" method="POST" action="<?php echo base_url("examenesMedicos");?>">
      <div class="row mb-2">
        <div class="col">
          <h3><strong>Paciente</strong></h3>
        </div>
        <div class="col">
          <select id="cmbUsuario" name="cmbUsuario" class="searchClient form-control select2" style="width: 100%;" required></select>
        </div>
        <div class="col">
          <button type="submit" class="btn btn-primary btn-md btn-block"><i class="fa fa-search"></i> Consultar</button>
        </div>
      </div>
      </form>
      <?php } ?>
        <div class="row mt-2">
          <div class="col">
             
              <table class="table table-hover">
                <thead>
                  <tr class="table-warning">
                    <th colspan="3"><h3>Examenes solicitados por el Médico</h3></th>
					 <?php if($rol == 1 || $rol == 4 || $rol == 5) { ?>
                  
                    <th style="text-align: right;">
						<?php if($rol == 1 || $rol == 4) { ?>
						<a class="btn btn-primary" href="<?php echo  base_url('gestionarExamenes/0');?>" role="button" target="_blank">Gestionar Exámenes Orion</a>
						<a class="btn btn-success" href="<?php echo  base_url('informe/index');?>" role="button" target="_blank">Gestionar Exámenes SBC</a>
						<?php } ?>
						
                    </th>
                    <?php } else { ?>
                    <th ></th>
                  <?php } ?>
                  </tr>
                  <tr class="table-active">
                    <th>#</th>
                    <th scope="col">ESPECIALIDAD</th>
                    <th scope="col">EXÁMEN</th>
                    <th scope="col">ESPECIFICACIONES</th>
                     
                  </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($examenes as $key => $examen) {
                      $key++;
                  ?>
                    <tr>
                      <td><?php echo $key;?></td>
                      <td><?php echo $examen->especialidad;?></td>
                      <td><?php echo $examen->examen;?></td>
                      <td><?php echo $examen->especificaciones;?></td>
                    </tr>
                  <?php
                    }
                  ?>
                </tbody>
              </table>
            </div>
        </div>
        <!-- /.row -->
 
      </div>
      <!-- /.container-fluid -->
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

<?php $this->load->view("scripts"); ?>

<script src="<?php echo base_url('plugins/select2/js/select2.full.min.js');?>"></script>
<script>
      

  $('.searchClient').select2({
    language: "es",
    placeholder: 'Seleccionar paciente',
    minimumInputLength: 3,
    maximumSelectionLength: 10,
    ajax: {
      url: '<?php echo base_url("searchClient");?>',
      type: 'POST',
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    },
    "language": {
      "noResults": function(){
          return "No se han encontrado resultados";
      },
      inputTooShort: function () {
      return 'Ingrese 3 o más caracteres.';
      }
    }
  });
 </script>
</body>
</html>

