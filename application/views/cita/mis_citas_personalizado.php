<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
<base href="consulta">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SBCMedic | Busqueda de Citas Pendientes</title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo  base_url('img/favicon.ico');?>"/>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- pace-progress -->
  <link rel="stylesheet" href="plugins/pace-progress/themes/black/pace-theme-flat-top.css">

  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">



  <!-- adminlte-->
  <link rel="stylesheet" href="css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
   
</head>
<body class="hold-transition sidebar-mini sidebar-collapse" style="font-size: 14px;">
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
        <span style="vertical-align:middle;  "><span style="margin-left: 10px;font-size: 2.4rem;font-weight:bold;color: #004663;vertical-align:middle;  "> Busqueda de Citas Pendientes(Sin atender)<span></span>
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
      <form id="quickForm" method="post" action="<?php echo base_url('mis-citas-personalizado');?>">
        <div class="row">
          <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
            <div class="form-group">
              <select id="cmbmedico" name="cmbmedico" class="form-control select2" style="width: 100%;">
                <option value="">Profesional</option>                    
                <?php foreach ($medicos as $medico) { ?>
                    <option value="<?=$medico->idDoctor;?>"><?=$medico->nombreMedico;?></option>                    
                  <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
            <div class="form-group">
              <select class="form-control searchClient" name="cliente" style="width: 100%;"></select>
            </div>
          </div>
          <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
            <div class="form-group">
              <input type="date" class="form-control" name="fecha" value="<?php echo $this->input->post("fecha") ? $this->input->post("fecha") : date("Y-m-d"); ?>">
            </div>
          </div>
          <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-block" title="Buscar..."><i class="fa fa-search"></i> Consultar</button>
            </div>
          </div>
        </div>
        </form>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <table id="misCitas" class="table table-bordered table-hover">
                  <thead>
                  <tr>
					<th>NroCita</th>
                    <th>TipoCita</th>
				    <th>Paciente</th>
                    <th>FechaCita</th>
                    <th>HoraCita</th>
					<th>Pago?</th>
                    <th>Especialidad</th>
                    <th>Profesional</th>
                    <th>MotivoCita</th>
                    <th>Procedimiento</th>
                    
                    
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                      foreach ($resultados as $clave => $valor){
                        $clave++;
                    ?>
                      <tr>
					              <td><?php echo str_pad($valor['idCita'], 6, '0', STR_PAD_LEFT);;?></td>
                        <td style="color: <?php echo ($valor["virtual"] == 1)?"#e56b6f;" : "";?>"></strong><?php echo $valor["tipoCita"];?></td>
                        <td style="background-color: #e9edc9;"><strong><?php echo $valor["paciente"];?></strong></td> 
                        <td style="color: #02c39a;"><strong><?php echo date("d/m/Y",strtotime($valor["fechaCita"]));?></strong></td>
                        <td style="color: #02c39a;"><strong><?php echo substr($valor["horaCita"], 0, 5);?></strong></td>
						                        <td>
                          <input type="checkbox" name="ckbPago" data-bootstrap-switch data-off-color="danger" data-on-color="success" data-on-text="SI&nbsp;&nbsp;&nbsp;" data-off-text="NO" disabled <?php  echo $valor["statusPago"] ?  "checked='checked'" : ""; ?> />
                        </td>
                        <td style="background-color: #e9edc9;"><strong><?php echo $valor["especialidad"];?></strong></td>
                        <td style="background-color: #bde0fe;"><?php echo $valor["medico"];?></td>
                        <td style="background-color: #bde0fe;"><?php echo $valor["motivoTipoCita"];?></td>
                        <td style="background-color: #bde0fe;"><?php echo $valor["descripcion"];?></td>

                        <!--    <td>
                          <button type="button" class="btn btn-outline-info" title="Actualizar Historial" onclick=ventana_cita(<?php echo $valor["idCita"];?>) ><i class="far fa-eye"></i></button>
                        </td> -->

                      </tr>
                    <?php
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

 
      
  </div>

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
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- jquery-validation -->
<script src="plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="plugins/jquery-validation/additional-methods.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- Select2 -->
<script src="<?php echo base_url('plugins/select2/js/select2.full.min.js');?>"></script>
<script src="<?php echo base_url('plugins/inputmask/min/jquery.inputmask.bundle.min.js');?>"></script>

<script>
    $('#misCitas').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "language": {
        "url": "<?php echo base_url("plugins/datatables-bs4/js/Spanish.json");?>"
      }
    });

  $("input[data-bootstrap-switch]").each(function(){
    $(this).bootstrapSwitch('state', $(this).prop('checked'));
  });

  $('select').select2();

  $('.searchClient').select2({
    language: "es",
    placeholder: 'Seleccionar al paciente',
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
      },
      searching: function() {
        return "Buscando...";
      }
    }
  });

  function ventana_cita(cita) {
    var mywindow = window.open('<?php echo base_url('update-appointment/') ?>' + cita, 'Actulizar Cita', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=1000,height=700,left = 390,top = 50');
  }
</script>
</body>
</html>