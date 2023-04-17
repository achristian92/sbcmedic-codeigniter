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
        <span style="vertical-align:middle;"> <span style="margin-left: 10px;font-size: 2.4rem;font-weight:bold;color: #004663;vertical-align:middle;  "> Resultados <?php echo $usuarioSearch; ?><span></span>
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

       <div class="row">
          <div class="col">
            <table class="table table-hover">
              <thead>
                <tr class="table-success">
                  <th colspan="6"><h3><strong>Examenes realizados</strong></h3></th>
 
                </tr>
                <tr class="table-active">
                  <th>#</th>
                  <th scope="col">FECHA EXÁMEN</th>
                  <th scope="col">TIPO</th>
                  <th scope="col">EXÁMEN</th>
                  <th scope="col">STATUS</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              <?php
                  foreach ($resultados as $clave => $valor){
                    $clave++; 
                ?>
                  <tr>
                    <td><?php echo $clave;?></td>
                    <td><?php echo date("d/m/Y",strtotime($valor["fechaExamen"])); ?></td>
                    <td><strong><?php echo $valor["tipo"];?></strong></td>
                    <td style="color:blue"><strong><?php echo $valor["examen"];?></strong></td>
                    <td><span class="badge badge-<?php echo ($valor["estado"] == "V")? "success": "warning";?>"><?php echo ($valor["estado"] == "V")? "Válidado": "En Proceso";?></span></td>
                    <td align="center">
                      <?php if($valor["estado"] == "V") { ?>
                        <a href='<?php echo base_url("verResultado/").$valor['idOrden']?>' class="btn btn-outline-success" target="_blank"><i class="far fa-file-pdf"></i> Ver Resultado</a>
                      <?php } ?>
                    </td>
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

</body>
</html>

