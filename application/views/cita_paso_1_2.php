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
      <li class="nav-item" style=";">
        <span style="vertical-align:middle;  "><span style="margin-left: 10px;font-size: 2.4rem;font-weight:bold;color: #004663;vertical-align:middle;  "> Consultar<span></span>
      </li>
      
    </ul>
    <div class="col-md-6 text-right">
    
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-info step-active" style="margin-left: 10px;border-radius: 20px;height: 40px;width: 40px;cursor: default;"> 1 </button>
            <button type="button" class="btn btn-info" style="margin-left: 10px;border-radius: 20px;height: 40px;width: 40px; cursor: default;"> 2 </button>
            <?php $this->load->view("logout"); ?>
        </div>
    </div>
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
      <form role="form" id="quickForm" method="get" action="<?=base_url('buscarEventos');?>">  
        <div class="card-body" >
        
        <div class="row">
            
              <div class="col-md-6">
              <div class="card-body">

          
                <?php if($rol == 1 || $rol == 4) { ?>
                <div class="form-group">
                    <label for="cmbUsuario">Paciente</label>
                    <select id="cmbUsuario" name="cmbUsuario" class="searchClient form-control select2" style="width: 100%;">
                     
                    </select>
                </div>
                <?php } ?>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Especialidad</label>
                    <select id="cbSpe" name="cbSpe" class="form-control select2" style="width: 100%;">
                      <option value="0">Seleccionar</option>                    
                      <?php foreach ($especialidades as $especialidad) { ?>
                          <option value="<?=$especialidad->idSpecialty;?>"><?=$especialidad->name;?></option>                    
                        <?php } ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Profesional</label>
                    <select id="cbDoc" name="cbDoc" class="form-control select2" style="width: 100%;">
                    <option value="0">Seleccionar</option>  
                  </select>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputFile">Fecha</label>
                    <div class="input-group date">
  			              <input type="text" id="fecha" name="fecha" class="form-control" value="<?php echo date('d/m/Y');?>"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
  			              <div class="input-group-append" >
                        <div class="input-group-text" id="btnPicker"><i class="fa fa-calendar"></i></div>
                      </div>
                      <button type="submit" class="btn btn-success" name="btnBuscar" style="max-width: 100%;padding: 6px 25px;" title="Buscar disponiblidad"> <i class="fa fa-search"></i></button>
			              </div>
		              </div>
                  
                  <div class="form-group">
                    <div id="listView"></div>
                  </div>
                </div>
                <!-- /.card-body -->
              <!-- form start -->
                <input type="hidden" name="tipoCita" id="tipoCita" value="<?php echo $tipoCita;?>">
                <input type="hidden" name="sucursal" id="sucursal" value="<?php if(isset($_GET["sucursal"])) echo $_GET["sucursal"]; else echo "";?>">
            
              </form>
               
              </div>
              <div class="col-md-6">
              <!-- THE CALENDAR -->
                <div id="calendar"></div>
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
  function detalle_citas_asignadas(fecha, especialidad) {
      var mywindow = window.open('<?php echo base_url('scheduled_appointments/') ?>' + fecha + '/' + especialidad, 'Imprimir', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=1100,height=650,left = 390,top = 50');

  }
  
  $('.select2').select2({language: "es"});

  var calendar;
  // page is now ready, initialize the calendar...

  function establecerCalendario()
  {
    var tipoCita = $('#tipoCita').val();
    var medico = $('#cbDoc').val();

    //var promedio = promedioMedico(medico);
  
    $('#calendar').html('');

      var fechaok = $('#fecha').val();
      var especialidad = $('#cbSpe').val();
      

      fechaok =  fechaok.replace('/', "-");
      fechaok =  fechaok.replace('/', "-");
    
      var fechaEjemplo = moment(fechaok, 'DD-MM-YYYY');
      fechaEjemplo = fechaEjemplo.format('YYYY-MM-DD');

      if (calendar) {
        calendar.destroy();
      }

      var Calendar = FullCalendar.Calendar;
      var Draggable = FullCalendarInteraction.Draggable;

      var containerEl = document.getElementById('external-events');
      var checkbox = document.getElementById('drop-remove');
      var calendarEl = document.getElementById('calendar');

      usuario = $('#cmbUsuario').val();
      if (usuario === undefined ) usuario = ''; else usuario = '&user=' + usuario;

      sucursal = $('#sucursal').val();
  
      if (sucursal == '' ) sucursal = ''; else sucursal = '&sucursal=' + sucursal;

      calendar = new Calendar(calendarEl, {
        
        plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
        locale: 'es',
        header    : {
          left  : 'prev,next',
          center: 'title',
          right : 'dayGridMonth,timeGridDay'
        },
        defaultDate: fechaEjemplo,
        'themeSystem': 'bootstrap',
        validRange: {
          start: "<?php echo date('Y-m-d');?>"
        },
        
        //Random default events
        events    : '<?=base_url('listarEventosMedicos');?>'+ '/' + especialidad + '/' + medico + '/' + fechaok+ '/' + tipoCita, 
/*         
         eventClick: function(info) {
          info.jsEvent.preventDefault(); // don't let the browser navigate
          console.log(info);
          var obj = JSON.parse(info.event.extendedProps.description);
          $("#modalTitle").html('<strong>Fecha de atención: ' + moment(obj.date, "YYYY-MM-DD").format("DD/MM/YYYY") + '</strong>');
          $("#modalDoctor").html('&nbsp;' + obj.doctor);
          $("#modalName").html('&nbsp;' + obj.title);
          $("#modalURL").attr("href", info.event.url + '?tipoCita=' + tipoCita + usuario + sucursal);
          $("#modalStart").text(obj.start_time);
          $("#modalEnd").text(obj.end_time);
          //$("#calificacion").html(viewCalificacion);
          $('#exampleModalCenter').modal('show')
           
        },  */
        
        eventRender: function(info) {
          var obj = JSON.parse(info.event.extendedProps.description);
          
          //info.el.setAttribute("data-toggle", "modal");
          //info.el.setAttribute("data-target", "#exampleModalCenter");
          //info.el.setAttribute("data-placement", "top");
         // info.el.setAttribute("title", obj.doctor + " " +obj.title+ " " + obj.start_time +" - " +obj.end_time);
          //data-toggle="modal" data-target="#exampleModalCenter"
          
          /*
          var tooltip = new Tooltip(info.el, {
            title: info.event.extendedProps.description,
            placement: 'top',
            trigger: 'hover',
            container: 'body'
          });
          */
          
        },
        editable  : true,
        droppable : true, // this allows things to be dropped onto the calendar !!!
        drop      : function(info) {
          // is the "remove after drop" checkbox checked?
          if (checkbox.checked) {
            // if so, remove the element from the "Draggable Events" list
            info.draggedEl.parentNode.removeChild(info.draggedEl);
          }
        } ,
        /*dateClick: function(info) {
      alert('clicked ' + info.dateStr);

       console.log(info)
          
    },
         select: function(info) {
      alert('selected ' + info.startStr + ' to ' + info.endStr);
    } */

      });

      calendar.render();
    }

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
 $('#fecha').datepicker({
    language: 'es',
    autoclose: true,
    startDate: "<?php echo date('d/m/Y');?>"
});

 $('#btnPicker').click(function () {
                $('#fecha').datepicker('show');
            });

    $("#fecha").val(moment().format('DD/MM/YYYY'));

    $("#cbDoc").prop('disabled', true);

    // Hacemos la lógica que cuando nuestro SELECT cambia de valor haga algo
    $("#cbSpe").change(function(){
        // Guardamos el select de cursos
        var doctores = $("#cbDoc");

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

    

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()

    var Calendar = FullCalendar.Calendar;
    var Draggable = FullCalendarInteraction.Draggable;

    var containerEl = document.getElementById('external-events');
    var checkbox = document.getElementById('drop-remove');
    var calendarEl = document.getElementById('calendar');
   
    var calendar = new Calendar(calendarEl, {
      plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
      locale: 'es',
      header    : {
        left  : 'prev,next',
        center: 'title',
        right : 'dayGridMonth,timeGridDay'
      },
      'themeSystem': 'bootstrap',
      validRange: {
        start: "<?php echo date('Y-m-d');?>"
      }
    });

    calendar.render();

    $.validator.setDefaults({
    submitHandler: function () {
      var idDoc, fecha, fechaConsulta;
      idDoc = $('#cbDoc').val();
      fecha = $('#fecha').val();
      usuario = $('#cmbUsuario').val();
      sucursal = $('#sucursal').val();

      if (usuario === undefined ) usuario = ''; else usuario = '?user=' + usuario;
      if (sucursal != '' ) sucursal = '?user=' + usuario; else sucursal = '';

      fechaConsulta = moment(fecha, "DD/MM/YYYY").format("YYYY-MM-DD")
      $.ajax({
        type: frm.attr('method'),
        url:  frm.attr('action')+'/'+idDoc+'/' + fechaConsulta + usuario,
        data: frm.serialize(),
        success: function (data) {
          $('#listView').html(data);

          establecerCalendario();
        
        },
        error: function (data) {
            alert(data);
        },
    });   
           
    }
  });
  $('#quickForm').validate({
    rules: {
      cmbUsuario: {
        required: true
      },
      cbSpe: {
        required: true,
        min: 1
      },
      cbDoc: {
        required: true,
        min: 1
      },
      fecha: {
        required: true,
      },
      
    },
    messages: {
      cmbUsuario: {
        required: 'Seleccione un paciente'
      },
      cbSpe: {
        required: "Seleccione una especialidad",
        min: "Seleccione una especialidad"
      },
      cbDoc: {
        required: "Seleccione a un profesional",
        min: "Seleccione al profesional"
      },
      fecha: {
        required: "Introduzca una fecha de reserva"
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
</script>

</body>
</html>

