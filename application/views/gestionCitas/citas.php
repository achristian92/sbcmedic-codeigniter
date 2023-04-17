<?php
  defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>

<head>
  <base href="consulta">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo  base_url('img/favicon.ico'); ?>" />
  <title>SBCMedic | Citas</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php $this->load->view("styles"); ?>
   <style>
    .select2-container .select2-selection--multiple .select2-selection__choice {
      max-width: 100%;
      box-sizing: border-box;
      white-space: normal;
      word-wrap: break-word;
    }
  </style>
</head>

<body style="background: #a8ff78;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #78ffd6, #a8ff78);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #78ffd6, #a8ff78); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

">
      <div class="container-fluid">
        <div class="row bg-black">
          <div class="col-sm text-center"  style=" justify-content: center;align-items: center;">
            <h2>CITAS : <?php echo $paciente;?></h2>
          </div>
        </div>


 
          <div class="row mt-2">
            <div class="col-sm">
              <table id="misCitas" class="table table-bordered table-hover">
                <thead class="thead-dark">
                <tr>
                  <th>Procedimiento</th>
                  <th style="text-align: center;">Monto (S/.)</th>
                  <th></th>
                </tr>
                </thead>
                <tbody>
                  <form action="<?php echo base_url('modify-price'); ?>" method="POST" onsubmit="return confirm('¿Realmente desea moficar el precio?');">
                  <?php
                    foreach ($registroEditar as $registro){
                  ?>
                    <tr>
                      <td><?php echo $registro->descripcion; ?></td>
                      <td style="width: 20%;"><input type="number" style="text-align: right;" class="form-control" name="monto" placeholder="0.00" value="<?php echo $registro->precio; ?>" step="0.01"></td>
                      <td style="width: 20%;">
                        <button type="submit" class="btn btn-success">MODIFICAR</button>
                        <input type="hidden" name="montoOrigen" value="<?php echo $registro->precio; ?>">
                        <input type="hidden" name="idRegistro" value="<?php echo $registro->id; ?>">
                        <input type="hidden" name="code" value="<?php echo $this->uri->segment(3); ?>">
                      </td>
                    </tr>
                  <?php
                    }
                  ?>
                  </form>
                </tbody>
              </table>

            </div>
          </div>
          <div class="row mt-2">
            <div class="col-sm">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th scope="col">N°Cita</th>
                    <th scope="col">FechaCita</th>
                    <th scope="col">HoraCita</th>
                    <th scope="col">StatusCita</th>
                    <th scope="col">Profesional</th>
                    <th scope="col">Especialidad</th>
                    <th scope="col">Procedimiento</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    foreach ($resultados as $row){
                  ?>
                  <tr>
                    <th cope="row"><?php echo $row["idCita"];?></th>
                    <td><?php echo $row["fechaCita"];?></td>
                    <td><?php echo $row["horaCita"];?></td>
                    <td><?php echo $row["estadoCita"];?></td>
                    <td><?php echo $row["medico"];?></td>
                    <td><?php echo $row["medico"];?></td>
                    <td><?php echo $row["procedimiento"];?></td>
                    <td><a href="javascript:void(0)" id="imprime" onclick="print_link('<?php echo $row['idCita']; ?>')" title="Imprimir Examen"><i class="fas fa-print  fa-2x"></i></a></td>
                  </tr>
                  <?php
                      }
                  ?>
                </tbody>
              </table>
            </div>
      
        
      </div>
      </div>

  <?php $this->load->view("scripts"); ?>
<!-- Select2 -->
<script src="<?php echo base_url('plugins/select2/js/select2.full.min.js');?>"></script>
  <script>
    function print_link(cita) {
      var mywindow = window.open('<?php echo base_url('cash-management-records/print/') ?>' + cita, 'Imprimir', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=800,height=650,left = 390,top = 50');
    }

    
    $('.procedimientos').select2({
    width: '100%',
    language: "es",
    placeholder: 'Seleccionar',
    minimumInputLength: 2,
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
 

  
  var frm = $('#frmExamen');
    $.validator.setDefaults({
      submitHandler: function() {

        $("#btnGuardar").attr('disabled', true);
        Swal.fire({
          title: '¿Esta seguro de registralo?',
          text: "No podrás revertir esto!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, de acuerdo!',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              type: 'POST',
              url: frm.attr('action'),
              data: frm.serialize(),
              beforeSend: function() {
                $("#btnGuardar").html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
                $("#btnGuardar").addClass("btn btn-primary");
                $("#btnGuardar").prop('disabled', true);
              },
              success: function(data) {
                if (data.status) {
                  Swal.fire({
                    icon: 'success',
                    timer: 5000,
                    title: 'Respuesta exitosa',
                    text: data.message,
                    onClose: () => {
                      //opener.location.reload();
                      location.reload();
                      //window.close();
                    }
                  })
                } else {
                  $("#btnGuardar").attr('disabled', true);
                  Swal.fire({
                    icon: 'error',
                    timer: 5000,
                    title: 'Error de validación',
                    text: data.message
                  })
                }
              },
              error: function(data) {
                $("#btnGuardar").attr('disabled', false);
                Swal.fire({
                  icon: 'error',
                  timer: 5000,
                  title: 'Error interno',
                  text: 'Ha ocurrido un error interno!'
                })
              }
            });
          }
        })

        $("#btnGuardar").attr('disabled', false);

      }
    });


    $('#frmExamen').validate({
      rules: {
        resultado: {
          required: true
        }
      },
      messages: {
        resultado: {
          required: 'Calificación es obligatorio'
        }
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });

    
  </script>
</body>

</html>