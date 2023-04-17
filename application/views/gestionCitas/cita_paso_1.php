<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<?php $this->load->view('head'); ?>
<style>
 
.fc-today {
    background: #fff !important;
    color: #000;
}
</style>
<body class="hold-transition sidebar-mini pace-primary" style="background-image: url(img/fondo_body.png); height: 100%;  background-position: right;  background-repeat: no-repeat;  ">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light bg_transparent" style="height: 100px;">
    <!-- Left navbar links -->
    <ul class="navbar-nav h-100 align-items-center col-md-6">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item">
        <span style="vertical-align:middle;  "><span style="margin-left: 10px;font-size: 2.4rem;font-weight:bold;color: #004663;vertical-align:middle;  "> GENERAR CITA<span></span>
      </li>
      
    </ul>
 
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
    <section class="content"  >


      <!-- Default box -->
      <div class="card card-shadow">
      <form role="form" id="quickForm" method="post" action="<?=base_url('buscarEventosHoras');?>" onsubmit="return false;">  
        <div class="card-body">
          <div class="row">
            <div class="col form-group ">
              <label for="codepro">Procedimientos</label>
              <textarea id="codepro" name="codepro" class="form-control" readonly style="background-color: #3F51B5; color: wheat; font-weight: bold; font-size: 18px;" required><?php echo $procedimientos; ?></textarea>
            </div>
			<!--
            <div class="col form-group ">
              <label for="codepro">Tipo Cita</label>
              <select name="motivoCita" id="motivoCita" class="form-control">
                  <option value="">Seleccionar</option>
                <?php foreach ($motivoCitas as $motivoCita) { ?>
                  <option value="<?php echo $motivoCita->id;?>"><?php echo $motivoCita->nombre;?></option>                    
                <?php } ?>
              </select>
            </div>-->
          </div>

          <div class="row">



            <div class="col mt-2">
              <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Procedimientos Extras</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Exámenes de Laboratorio</a>
                </li>
              </ul>

              <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                  <div class="row">
                    <div class="col border border-info">
                    <select name="procedimientosExtra[]" id="procedimientosExtra" class="form-control procedimientos" multiple></select>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                  <div class="row">
                    <div class="col-8 border border-success">
                      <select name="laboratorios[]" id="laboratorios" class="form-control laboratorios" multiple style="background-color: green;"></select>
                    </div>
                    <div class="col border-success">
                  <input type="date" name="fecha" id="fecha" class="form-control" value="<?php echo date("Y-m-d"); ?>" requerid style="background-color: green; color:honeydew">
                    </div>
                  </div>
                  
                  
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-2">
            <div class="form-group col-6">
              <label for="cmbEspecialidad">Especialidad</label>
              <select id="cmbEspecialidad" name="cmbEspecialidad" class="form-control select2" style="width: 100%;">
                <?php foreach ($especialidades as $especialidad) { ?>
                  <option value="<?=$especialidad->idSpecialty;?>" <?php echo $this->input->get("service") == $especialidad->idSpecialty? "selected" : ""; ?> ><?=$especialidad->name;?></option>                    
                <?php } ?>
              </select>
            </div>
            <div class="form-group col-6">
              <label for="cmbMedico">Profesional</label>
              <select id="cmbMedico" name="cmbMedico" class="form-control select2" style="width: 100%;">
                  <option value="">Seleccionar</option>
                <?php foreach ($profesionales as $profesional) { ?>
                  <option value="<?=$profesional->idDoctor;?>"><?=$profesional->nombreMedico;?></option>                    
                <?php } ?>
              </select>
              </select>
            </div>
          </div>

          <div class="row mt-2">
            <div class="col-3">
              <label for="presencial">Presencial</label>
              <input type="radio" id="presencial" class="form-control" name="opcionCita" value="1" disabled checked onclick="comprobar()">
            </div>
            <div class="col-3">
              <label for="gratis">Gratis</label>
              <input type="radio" id="gratis" class="form-control" name="opcionCita" value="gratis" disabled onclick="comprobar()">
            </div>
            <div class="col-3">
              <label for="virtual">Virtual</label>
              <input type="radio" id="virtual" class="form-control" name="opcionCita" value="virtual" disabled onclick="comprobar()">
            </div>
            <div class="col-3">
              <label for="adicional">Adicional</label>
              <input type="radio" id="adicional" class="form-control" name="opcionCita" value="adicional" disabled  onclick="comprobar()">
            </div>
          </div>

          <div class="row">
            <div class="col">
              <label for="fechaCitaAsignar">Fecha de la Cita</label>
              <div class="input-group date">
                <input type="text" id="fechaCitaAsignar" name="fechaCitaAsignar" class="form-control" value="<?php echo date('d/m/Y');?>"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                <div class="input-group-append" >
                  <div class="input-group-text" id="btnPicker"><i class="fa fa-calendar"></i></div>
                </div>
                <button type="submit" class="btn btn-success" name="btnBuscar" style="max-width: 100%;padding: 6px 25px;" title="Buscar disponiblidad"> <i class="fa fa-search"></i></button>
                
              </div>
            </div>
          </div>
        
        <div class="row">
            
              <div class="col">
              <div class="card-body">
                  <div class="form-group">
                    <div id="listView"></div>
                  </div>
                </div>
                <!-- /.card-body -->
              <!-- form start -->
              <input type="hidden" name="tipoCita" id="tipoCita" value="CP">
              <input type="hidden" name="user" value="<?php echo $this->input->get("user");?>">
              <input type="hidden" name="codeOne" value="<?php echo $this->input->get("codeOne");?>">
              <input type="hidden" name="codigoProcedimiento" value="<?php echo $codigoProcedimiento;?>">
              <input type="hidden" name="ids" value="<?php echo $ids;?>">
              </form>
               
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
<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
                <div class="row row-no-gutters">
                  <div class="col-md-1 col-sm-6">
                  <button style="margin-top: 5px;" type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <img src="img/logo_doctor.png" class=""/></button>
                  </div>
                  <div class="col-md-6 col-sm-6">
                    <span class="font-green" style="display:block;"><strong id="modalDoctor">...</strong></span>
                    <span class="font-green" id="modalName">...</span>
                  </div>
                  <div class="col-md-2 col-sm-6">
                    <span><img src="img/logo_cv.png" /> <span class="font-green">CV</span></span>
                  </div>
                  <div class="col-md-3 col-sm-6">
                    <div id="calificacion">
                      <span><img src="img/logo_star.png" width="20px" /> </span>
                      <span><img src="img/logo_star.png" width="20px"/> </span>
                      <span><img src="img/logo_star.png" width="20px"/> </span>
                      <span><img src="img/logo_empty_star.png" width="20px"/> </span>
                      <span><img src="img/logo_empty_star.png" width="20px"/> </span>
                    </div>
                  </div>
                </div> 
                
               
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" data-dismiss="modal" title="Cerrar Ventana">Cerrar</button>
        <a id="modalURL" href="#" class="btn btn-outline-success" title="Aceptar Horario"><span id="modalStart">...</span> - <span id="modalEnd">...</span></a>
      </div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/moment/moment.min.js"></script>
<!--<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- date-range-picker -->
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" charset="UTF-8"></script>
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<script src="<?php echo base_url('plugins/select2/js/i18n/es.js');?>"></script>

<!-- fullCalendar 2.2.5 -->
<script src='plugins/fullcalendar/locales/es.js'></script>
<script src="plugins/fullcalendar/main.min.js"></script>
<script src="plugins/fullcalendar-daygrid/main.min.js"></script>
<script src="plugins/fullcalendar-timegrid/main.min.js"></script>
<script src="plugins/fullcalendar-interaction/main.min.js"></script>
<script src="plugins/fullcalendar-bootstrap/main.min.js"></script>
<!-- pace-progress -->
<script src="plugins/pace-progress/pace.min.js"></script>
<!-- AdminLTE App -->
<script src="js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- jquery-validation -->
<script src="plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="plugins/jquery-validation/additional-methods.min.js"></script>


<!-- Page script -->
<script> 
  $('.select2').select2();

  function detalle_citas_asignadas(fecha, especialidad) {
      var mywindow = window.open('<?php echo base_url('scheduled_appointments/') ?>' + fecha + '/' + especialidad, 'Imprimir', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=1100,height=650,left = 390,top = 50');
  }

 

  function detalle_fechas_disponibles(doctor) {
    var mywindow = window.open('<?php echo base_url('available-dates/') ?>' + doctor + '?fechaBusqueda=' + $('#fechaCitaAsignar').val(), 'Imprimir', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=770,height=600,left = 390,top = 50');
  }

  $('.select2').select2({language: "es"});

  $('[name="motivoCita"]').on('change', function(e) {
    e.preventDefault();
    $(this).closest('form')
           .trigger('submit')
  });

  $('[name="fecha"]').on('change', function(e) {
    e.preventDefault();
    $(this).closest('form')
           .trigger('submit')
  });

  $('[name="laboratorios[]"]').on('change', function(e) {
    e.preventDefault();
    $(this).closest('form')
           .trigger('submit')
  });

  $('[name="procedimientosExtra[]"]').on('change', function(e) {
    e.preventDefault();
    $(this).closest('form')
           .trigger('submit')
  });

  $('[name="fechaCitaAsignar"]').on('change', function(e) {
    e.preventDefault();
    $(this).closest('form')
           .trigger('submit')
  });

  $('[name="opcionCita"]').on('change', function(e) {
    e.preventDefault();
    $(this).closest('form')
           .trigger('submit')
  });

  $('#procedimientos').on('change', function(e) {
    e.preventDefault();
    $(this).closest('form')
           .trigger('submit')
  });

  $('#cmbMedico').on('change', function(e) {
    e.preventDefault();
    $(this).closest('form')
           .trigger('submit')
  });
           
   

    function promedioMedico(idMedico) {

      $.ajax({
        url:   '<?=base_url('calificacionMedico');?>/'+ idMedico,
        success: function(respuesta) {
          var viewCalificacion = '';
          if(respuesta >0) {
            for (i = 1; i <= respuesta; i++) {
              viewCalificacion += '<span><img src="img/logo_star.png" width="20px"/> </span>';
            }
          }

          for (i = 1; i <= 5 - respuesta; i++) {
            viewCalificacion += '<span><img src="img/logo_empty_star.png" width="20px"/> </span>';
                
          }
          
          $("#calificacion").html(viewCalificacion);
        },
        error: function() {
              alert("No se ha podido obtener la información");
        }
      });
  
    }
      
    $(function () {
      const SuccessToast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onClose: (toast) => {
      //...
      window.location.replace("<?php echo base_url('cita');?>");
    }
  });


const ErrorToast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onClose: (toast) => {
    //...
    
  }
})

var frm = $('#quickForm');

frm.submit(function (e) {

    e.preventDefault();

   
});
 
    //Date range picker
 $('#fechaCitaAsignar').datepicker({
    language: 'es',
    autoclose: true,
    startDate: "<?php echo date('d/m/Y');?>"
});

 $('#btnPicker').click(function () {
                $('#fechaCitaAsignar').datepicker('show');
            });

    $("#fechaCitaAsignar").val(moment().format('DD/MM/YYYY'));

    //$("#cmbMedico").prop('disabled', true);

    // Hacemos la lógica que cuando nuestro SELECT cambia de valor haga algo
    $("#cmbEspecialidad").change(function(){
        // Guardamos el select de cursos
        var doctores = $("#cmbMedico");

        // Guardamos el select de alumnos
        var especialidades = $(this);

        if($(this).val() != '')
        {
            $.ajax({

                url:   '<?=base_url('doctores');?>/'+especialidades.val(),
                type:  'GET',
                dataType: 'json',
                beforeSend: function () 
                {
                    especialidades.prop('disabled', true);
                },
                success:  function (r) 
                {
                  especialidades.prop('disabled', false);

                    // Limpiamos el select
                    doctores.find('option').remove();
                    doctores.append('<option value="">Seleccionar</option>');

                    $(r).each(function(i, v){ // indice, valor
                      doctores.append('<option value="' + v.idDoctor + '">' + v.title +' '+ v.lastname + ' ' + v.firstname + '</option>');
                    })

                    doctores.prop('disabled', false);
                },
                error: function()
                {
                    alert('Ocurrio un error en el servidor ..');
                    especialidades.prop('disabled', false);
                }
            });
        }
        else
        {
            doctores.find('option').remove();
            doctores.prop('disabled', true);
         
            doctores.append('<option value="">Seleccionar</option>');
        }
    });
 

    $.validator.setDefaults({
    submitHandler: function () {
      var idDoc, fecha, fechaConsulta;
      idDoc = $('#cmbMedico').val();
      fecha = $('#fechaCitaAsignar').val();
      usuario = $('#cmbUsuario').val();

      if (usuario === undefined ) usuario = ''; else usuario = '?user=' + usuario;

      fechaConsulta = moment(fecha, "DD/MM/YYYY").format("YYYY-MM-DD");

      $.ajax({
        type: frm.attr('method'),
        url:  frm.attr('action'),
        data: frm.serialize(),
        success: function (data) {
          $( "#presencial" ).prop( "disabled", false );
          $( "#gratis" ).prop( "disabled", false );
          $( "#virtual" ).prop( "disabled", false );
          $( "#adicional" ).prop( "disabled", false );

          $('#listView').html(data);

        },
        error: function (data) {
            alert(data);
        },
    });   
           
    }
  });
  $('#quickForm').validate({
    rules: {
       
      cmbEspecialidad: {
        required: true,
        min: 1
      },
      cmbMedico: {
        required: true,
        min: 1
      },
      codepro: {
        required: true,
      },
      fechaCitaAsignar: {
        required: true,
      },
      motivoCita: {
        required: true,
      },
      
    },
    messages: {
 
      cmbEspecialidad: {
        required: "Seleccione una especialidad",
        min: "Seleccione una especialidad"
      },
      cmbMedico: {
        required: "Seleccione a un profesional",
        min: "Seleccione al profesional"
      },
      codepro: {
        required: "Debe asignar un procedimiento"
      },
      fechaCitaAsignar: {
        required: "Introduzca una fecha de reserva"
      },
      motivoCita: {
        required: "Seleccione un tipo de cita"
      }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });

  });
 
  $('.searchClient').select2({
    language: "es",
    placeholder: 'Seleccionar',
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

  
  $('.procedimientos').select2({
      width: '100%',
      language: "es",
      placeholder: 'Seleccionar',
      minimumInputLength: 3,
      maximumSelectionLength: 20,
      ajax: {
        url: '<?php echo base_url("searchProcedimientos");?>',
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

    $('.laboratorios').select2({
      width: '100%',
      language: "es",
      placeholder: 'Seleccionar Exámenes',
      minimumInputLength: 2,
      maximumSelectionLength: 20,
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
        return 'Ingrese 2 o más caracteres.';
        }
      }
    });
</script>

</body>
</html>

