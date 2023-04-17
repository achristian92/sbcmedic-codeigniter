<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
<base href="consulta">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SBCMedic | Administración Caja</title>
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

  label {
    color: rgba(46,175,109,1);
  }
 
  </style>
</head>
<body class="<?php if ($rol == 1) echo "hold-transition sidebar-mini sidebar-collapse"; else echo "hold-transition sidebar-mini pace-primary"; ?>">
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
        <span style="vertical-align:middle;  "><span style="margin-left: 10px;font-size: 2.4rem;font-weight:bold;color: #004663;vertical-align:middle;  "> Administración de Caja<span></span>
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
      <?php 
      if($consultarCita == "filtro_busqueda_cita") { ?>
      <form id="quickForm" method="post" action="<?php echo base_url('cash-management');?>">
        <div class="row">
          <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
            <div class="form-group">
              <label for="fechaInicial">Médicos</label>
              <select id="cmbmedico" name="cmbmedico" class="form-control select2" style="width: 100%;">
                <option value="">Todos</option>                    
                <?php foreach ($medicos as $medico) { ?>
                    <option value="<?=$medico->idDoctor;?>" <?php echo $this->input->post("cmbmedico") == $medico->idDoctor ? "selected" : ""; ?>><?=$medico->nombreMedico;?></option>                    
                  <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
            <div class="form-group">
              <label for="fechaInicial">Paciente</label>
              <select class="form-control searchClient" name="cliente" style="width: 100%;"></select>
            </div>
          </div>
          <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
            <div class="form-group">
              <label for="fechaInicial">Fecha Cita Inicial</label>
              <input type="date" class="form-control" name="fechaInicial" value="<?php echo $this->input->post("fechaInicial") ? $this->input->post("fechaInicial") : date("Y-m-d"); ?>">
            </div>
          </div>
          <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
            <div class="form-group">
              <label for="fechaFinal">Fecha Cita Final</label>
              <input type="date" class="form-control" name="fechaFinal" value="<?php echo $this->input->post("fechaFinal") ? $this->input->post("fechaFinal") : date("Y-m-d"); ?>">
            </div>
          </div>
          <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-block" title="Buscar..."><i class="fa fa-search"></i> Consultar</button>
            </div>
          </div>
        </div>
        </form>
        <?php } ?>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <table id="misCitas" class="table table-bordered table-hover">
                  <thead>
                  <tr>
					          <th>N°Cita</th>
                    <th>Tipo Cita</th>
					          <?php if ($rol != 3) { ?><th>Paciente</th><?php } ?>
                    <th>Fecha Cita</th>
                    <th>Hora Cita</th>
                    <th>Especialidad</th>
                    <th>Médico</th>
                    <?php if($realizarPago == "cambiar_status_pago" and $rol != 3) { ?>
                    <th>Pago realizado</th>
                    <?php } ?>
                    
                    <th>Agregar</th>
                    <th></th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                      foreach ($resultados as $clave => $valor){
                        $clave++;
                    ?>
                      <tr>
                        <td><?php echo str_pad($valor['idCita'], 6, '0', STR_PAD_LEFT); ?></td>
                        <td style="color: <?php echo ($valor["idCitaTipo"] == "CV")?"#e56b6f;" : "";?>"></strong><?php echo $valor["tipoCita"];?></td>
						            <?php if ($rol != 3) { ?><td style="background-color: #e9edc9;"><strong><?php echo $valor["paciente"];?></strong></td><?php } ?>
                        <td style="color: #02c39a;"><strong><?php echo date("d/m/Y",strtotime($valor["fechaCita"]));?></strong></td>
                        <td><?php echo $valor["horaCita"];?></td>
                        <td style="background-color: #e9edc9;"><strong><?php echo $valor["especialidad"];?></strong></td>
                        <td style="background-color: #e9edc9;"><?php echo $valor["medico"];?></td>
                        <?php if($realizarPago == "cambiar_status_pago" and $rol != 3) { ?>
                        <td>
                          <input type="checkbox" name="ckbPago" data-bootstrap-switch data-off-color="danger" data-on-color="success" data-on-text="SI&nbsp;&nbsp;&nbsp;" data-off-text="NO" data="<?php echo $valor["idPago"];?>"  code="<?php echo $valor["codigo_asignacion"];?>" <?php  if($valor["statusPago"] == 1) echo "checked='checked'"; else echo ""; ?> <?php if( $idUsuario == 8687 || $idUsuario == 6436 || $idUsuario == 3914 || $idUsuario == 48 || $idUsuario == 10817) echo ""; else echo "disabled"; ?> <?php if($valor["statusPago"] == 1) echo "disabled"; ?>/>
                        </td>
                        <?php } ?>

                        
                        <td><a href="javascript:void(0)" class="btn btn-outline-success" id="agregarPago" onclick="addPago_link('<?php echo $valor['idUsuario']; ?>', '<?php echo $valor['idCita']; ?>', '<?php echo $valor['codigo_asignacion'] ? $valor['codigo_asignacion'] : 0; ?>')" title="Agregar Pago"><i class="fas fa-plus-circle"></i> Agregar Pago</a>
                        </td>
                       <td>
                       <?php if( $valor['idCita'] > 7769) { ?>
                         <a href="javascript:void(0)" id="imprime" onclick="print_link_new('<?php echo $valor['idCita']; ?>')" title="Imprimir Examen"><i class="fas fa-print"></i> Imprimir</a>
                        
                        <?php } else { ?>
                          <a href="javascript:void(0)" id="imprime" onclick="print_link('<?php echo $valor['idCita']; ?>')" title="Imprimir Examen"><i class="fas fa-print"></i> Imprimir</a>
                        <?php } ?>

                        </td>
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


      <!-- /.content-wrapper -->
      <div class="modal fade" id="modal-detalle">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header text-center">
              <h4 class="modal-title w-100">Detalle de la Cita</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row justify-content-md-center p-2">
                <div class="col col-lg-8">
                  <strong>Tipo de Cita:</strong> <span id="tipoCita"></span>
                </div>
              </div>
              <div class="row justify-content-md-center p-2">
                <div class="col col-lg-8">
                  <strong>Dirección:</strong> <span id="direccion"></span>
                </div>
              </div>
              <div class="row justify-content-md-center p-2">
                <div class="col col-lg-8">
                  <strong>Teléfono:</strong> <span id="fono"></span>
                </div>
              </div>
              <div class="row justify-content-md-center p-2">
                <div class="col col-lg-8">
                <strong>Fecha de Cita:</strong> <span id="fechaCita"></span>
                </div>
              </div>
              <div class="row justify-content-md-center p-2">
                <div class="col col-lg-8">
                <strong>Hora de Cita:</strong> <span id="horaCita"></span>
                </div>
              </div>
              <div class="row justify-content-md-center p-2">
                <div class="col col-lg-8">
                <strong>Especialidad:</strong> <span id="especialidad"></span>
                </div>
              </div>
              <div class="row justify-content-md-center p-2">
                <div class="col col-lg-8">
                <strong>Profesional:</strong> <span id="medico"></span>
                </div>
              </div>
              <div class="row justify-content-md-center p-2">
                <div class="col col-lg-8">
                <strong>Motivo Cita:</strong> <span id="motivo" class="text-info"></span>
                </div>
              </div>         
            </div>
            <div class="modal-footer text-center w-100">
              <div class="container">
                <div class="row w-100">
                  <div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4 col-sm-6 offset-sm-3 col-xl-3 offset-xl-5 col-6 offset-3"><button type="button" class="btn btn-outline-success" data-dismiss="modal" title="Cerrar ventana">Cerrar</button></div>
                </div>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

      <!-- /.content-wrapper -->
 
      
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

  $('.select2').select2({
    width: '100%'
  });

  $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
  });

  $('input[name="ckbPago"]').on('switchChange.bootstrapSwitch', function(e, data){
      $.ajax({
      type: 'post',
      url: "<?php echo base_url("gpagoStatus");?>",
      data: { idPago : $(this).attr('data'), status : data , code :  $(this).attr('code') },
      success: function (respuesta) {
        if (!respuesta) alert('Error! No se guardo.');
      },
      error: function (respuesta) {
        if (respuesta) alert('Error! No se guardo.' + respuesta);
      }
    });
	
	window.location.reload();
  });

  $('input[name="descansoMedico"]').on('switchChange.bootstrapSwitch', function(e, data){
     if(data){
      $('#descanso1').show();
      $('#descanso2').show();
     } else {
      $('#descanso1').hide();
      $('#descanso2').hide();
     }
  });

  $('#misCitas').DataTable({
    "paging": true,
	  "order": [[ 0, "desc" ]],
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

  function initializeSelect2() {
    $('.searchExamen').select2({
      width: '100%',
      language: "es",
      placeholder: 'Seleccionar',
      minimumInputLength: 3,
      maximumSelectionLength: 10,
      ajax: {
        url: '<?php echo base_url("searchExamen");?>',
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
  }

  function print_link_new(usuario) {
      var mywindow = window.open('<?php echo base_url('cash-management-records/print/') ?>' + usuario, 'Imprimir', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=800,height=650,left = 390,top = 50');

    }
  
  function print_link(usuario) {
    var mywindow = window.open('<?php echo base_url('cash-management/print/') ?>' + usuario, 'Imprimir', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=800,height=650,left = 390,top = 50');

  }


  function addPago_link(usuario, cita, code) {
      var mywindow = window.open('<?php echo base_url('cash-management/addPay/') ?>' + usuario + '/' + cita + '/' + code, 'Imprimir', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=1150,height=650,left = 390,top = 50');

    }

    function cancelarCita(id, idDisponible) {

        Swal.fire({
        title: '¿Estás seguro de CANCELAR esta cita?',
        text: 'Una vez confirmado, no se podrá revertir.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Seguro!',
        cancelButtonText: 'Cancelar',
        }).then((result) => {
          if (result.value) {

            $.ajax({
              type: 'post',
              url: "<?php echo base_url("gCancelarCita");?>",
              data: { idCita : id, idDisponible: idDisponible },
              success: function (data) {
                if(data.status){  
                  
                  Swal.fire({
                    icon: 'success',
                    timer: 7000,
                    title: 'Respuesta exitosa',
                    text: data.message,
                    onClose: () => {
                      window.location.replace("<?php echo base_url('cash-management');?>");
                    }
                  })
                }else{
                  Swal.fire({
                    icon: 'error',
                    timer: 7000,
                    title: 'Error de validación',
                    text: data.message
                  })
                }
              },
              error: function (data) {
                Swal.fire({
                  icon: 'error',
                  timer: 7000,
                  title: 'Error interno',
                  text: 'Ha ocurrido un error interno!'
                })
              },
            });
          }
        });
    }

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
          return "Buscando..";
        }
      }
    });
</script>
</body>
</html>