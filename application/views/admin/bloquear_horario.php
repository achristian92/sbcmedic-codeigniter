<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html> 
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SBCMedic | Bloquear Horarios</title>
  <?php $this->load->view("styles"); ?>
</head>
<body class="hold-transition sidebar-mini pace-primary" style="background-image: url(<?php echo base_url('img/fondo_body.png');?>); height: 100%;  background-position: right;  background-repeat: no-repeat;  ">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light bg_transparent" style="height: 100px;">
 
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?php echo base_url('admin/schedule');?>" class="nav-link">Aperturar Horarios</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">|</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?php echo base_url('admin/bloquearHorario');?>" class="nav-link">Bloquear Horario</a>
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
    <section class="content">
      <div class="container-fluid">
        <!-- form start -->
        <form role="form" id="formAperturarHorario" method="post" action="<?php echo base_url('admin/gbloqueoHorario');?>">
          <div class="row">
            <div class="col-4 form-group">
              <label for="cmbEspecialista">Especialidad</label>
              <select id="cmbEspecialista" name="cmbEspecialista" class="form-control select2" style="width: 100%;">
                <option value="">Seleccionar</option>                    
                <?php foreach ($especialidades as $especialidad) { ?>
                    <option value="<?=$especialidad->idSpecialty;?>"><?=$especialidad->name;?></option>                    
                  <?php } ?>
              </select>
            </div>
            <div class="col-4 form-group">
              <label for="cmbMedico">Profesional</label>
              <select id="cmbMedico" name="cmbMedico" class="form-control select2" style="width: 100%;">
                <option value="">Seleccionar</option>  
              </select>
            </div>
            <div class="col-4">
              <label for="fecha">Tipo de Cita</label>
              <select class="form-control" name="tipoCita">
              
                <option value="CP">Presencial</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-4">
              <label for="horaInicio">Hora Inicio</label>
              <input type="time" id="horaInicio" name="horaInicio" class="form-control" value="08:00"  required>
            </div>
            <div class="col-4">
              <label for="horaFinal">Hora Final</label>
              <input type="time" id="horaFinal" name="horaFinal" class="form-control" value="17:00" required>
            </div>
            <div class="col-4 form-group">
              <label for="monto">Bloquear</label>
              <input type="checkbox" class="form-control" name="bloquear" value="2" checked>
            </div>
          </div>

          <div class="row">
            <div class="col-4">
            
            <div class="form-group">
                  <label>Fecha Inicio:</label>

                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="date" name="fechaInicio" class="form-control" value="<?php echo date("Y-m-d");?>">
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
            </div>

            <div class="col-4">
            <div class="form-group">
                  <label>Fecha Final:</label>

                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="date" name="fechaFin" class="form-control" value="<?php echo date("Y-m-d");?>">
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
            </div>

        
            <div class="col-4 form-group">
                <label for="monto">&nbsp;</label>
                <button type="submit" id="btnGuardar" class="form-control btn btn-dark">Bloquear Horario</button>
            </div>
          </div>
        </form>
        <!-- /.form -->
       
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
   
  <footer class="main-footer bg_transparent">
    <div class="float-right d-none d-sm-block">
      <b>Versión</b> <?php echo $version["version"];?>
    </div>
    <strong>Copyright &copy; 2020 <a href="javascript:void(0)">SBCMedic</a>.</strong> Derechos Reservados.
  </footer>
</div>
<!-- ./wrapper -->
<?php $this->load->view('scripts'); ?>
<!-- Select2 -->
<script src="<?php echo base_url('plugins/select2/js/select2.full.min.js');?>"></script>
<script src="<?php echo base_url('plugins/inputmask/min/jquery.inputmask.bundle.min.js');?>"></script>
 
<script>

  $('[data-mask]').inputmask();

  var frm = $('#formAperturarHorario');

  frm.submit(function (e) {
      e.preventDefault();
  });
  
  //Initialize Select2 Elements
  $('.select2').select2()
  
  $("#cmbMedico").prop('disabled', true);

  // Hacemos la lógica que cuando nuestro SELECT cambia de valor haga algo
  $("#cmbEspecialista").change(function(){
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
        success:  function (respuesta) 
        {
          especialidades.prop('disabled', false);
            // Limpiamos el select
            doctores.find('option').remove();

            if (respuesta.length > 0) {
              $(respuesta).each(function(i, v){ // indice, valor
                doctores.append('<option value="' + v.idDoctor + '">' + v.title +' '+ v.lastname + ' ' + v.firstname + '</option>');
              });
              doctores.prop('disabled', false);
            } else {
              doctores.append('<option value="">Seleccionar</option>');
              doctores.prop('disabled', true);
            }
        },
        error: function()
        {
            especialidades.prop('disabled', false);
        }
    });
    } else {
      doctores.find('option').remove();
      doctores.prop('disabled', true);
    
      doctores.append('<option value="">Seleccionar</option>');
    }
  });

  $.validator.setDefaults({
    submitHandler: function () {
      Swal.fire({
      title: '¿Estás seguro de Bloquear este horario?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Seguro!',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      
      if (result.value) {
        $.ajax({
          type: frm.attr('method'),
          url:  frm.attr('action'),
          data: frm.serialize(),
          beforeSend: function () 
            {            
              //$('#btnGuardar').html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
              //$('#btnGuardar').removeClass("btn btn-outline-dark");
              //$('#btnGuardar').addClass("btn btn-dark");
              //$('#btnGuardar').prop('disabled', true);
            },
          success: function (data) {

            if(data.status){  
              
              Swal.fire({
                icon: 'success',
                timer: 5000,
                title: 'Respuesta exitosa',
                text: data.message,
                onClose: () => {
                  //window.location.replace("<?php echo base_url('admin/bloquearHorario');?>");
                }
              })
            }else{
              Swal.fire({
                icon: 'error',
                timer: 5000,
                title: 'Error de validación',
                text: data.message
              })
            }
          
          },
          error: function (data) {
            Swal.fire({
              icon: 'error',
              timer: 5000,
              title: 'Error interno',
              text: data.status + ' (' + data.statusText + ')',
              onClose: () => {
                  window.location.replace("<?php echo base_url('admin/bloquearHorario');?>");
              }
            })
          },
        });
      }
    });  
    }
  });

  $('#formAperturarHorario').validate({
    rules: {
      cmbEspecialista: {
        required: true,
        min: 1
      },
      cmbMedico: {
        required: true,
        min: 1
      },
      fechaInicio: {
        required: true,
      },
      fechaFin: {
        required: true,
      }
    },
    messages: {
      cmbEspecialista: {
        required: "Seleccione una especialidad",
        min: "Seleccione una especialidad"
      },
      cmbMedico: {
        required: "Seleccione a un profesional",
        min: "Seleccione a un profesional"
      },
      fechaInicio: {
        required: "Introduzca la fecha  de inicio"
      },
      fechaFin: {
        required: "Introduzca la fecha  de fin "
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

</script>
</body>
</html>

