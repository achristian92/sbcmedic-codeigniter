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
  <title>SBCMedic | GESTIÓN EXAMENES</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php $this->load->view("styles"); ?>
 
</head>

<body style="background: #a8ff78;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #78ffd6, #a8ff78);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #78ffd6, #a8ff78); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

">
  <div class="container">
    <form id="frmExamen" action="<?php echo base_url('informe/gNuevoLaboratorio');?>">
    <div class="row bg-black">
      <div class="col-sm text-center" style=" justify-content: center;align-items: center;">
        <h2><?php echo strtoupper($nombreExamen);?></h2>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col-sm">
        <div class="form-group">
          <label for="dato1"><?php echo $dato1;?></label>
           
		  
		  <textarea class="form-control" id="dato1" name="dato1" rows="3" placeholder="Dato <?php echo $dato1;?>"><?php echo (isset($resultados[0]->dato1))? $resultados[0]->dato1: "";?></textarea>
        </div>
      </div>
    </div>
    <?php if (isset($dato2)){?>
    <div class="row">
      <div class="col-sm">
        <div class="form-group">
          <label for="dato2"><?php echo $dato2;?></label>
          <input type="text" name="dato2" value="<?php echo (isset($resultados[0]->dato2))? $resultados[0]->dato2: "";?>" class="form-control" id="dato2" placeholder="Dato <?php echo $dato2;?>">
        </div>
      </div>
    </div>
    <?php } ?>
    
    <?php if (isset($dato3)){?>
    <div class="row">
      <div class="col-sm">
        <div class="form-group">
          <label for="dato3"><?php echo $dato3;?></label>
          <input type="text" name="dato3" value="<?php echo (isset($resultados[0]->dato3))? $resultados[0]->dato3: "";?>" class="form-control" id="dato3" placeholder="Dato <?php echo $dato3;?>">
        </div>
      </div>
    </div>
    <?php } ?>

    <div class="row mt-2">
      <div class="col-sm">
        <div class="form-group">
          <button type="submit" id="btnGuardar" class="btn btn-primary btn-block ml-1">REGISTRAR <?php echo strtoupper($nombreExamen);?></button>
          <input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>">
          <input type="hidden" name="idExamen"  value="<?php echo $idExamen;?>">
          <input type="hidden" name="idUsuario"  value="<?php echo $idUsuario;?>">
          <input type="hidden" name="idTipo"  value="<?php echo $idTipo;?>">		  <input type="hidden" name="idPerfil"  value="<?php echo $idPerfil;?>">
        </div>
      </div>
    </div>
    </form>
  </div>

  <?php $this->load->view("scripts"); ?>

  <script>
   
    var frm = $('#frmExamen');
    $.validator.setDefaults({
      submitHandler: function() {

        $("#btnGuardar").attr('disabled', true);
        Swal.fire({
          title: '¿Esta seguro de guardar el examen?',
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
                $('#modal-warning').modal('hide');
                if (data.status) {
                  Swal.fire({
                    icon: 'success',
                    timer: 5000,
                    title: 'Respuesta exitosa',
                    text: data.message,
                    onClose: () => {
                      opener.location.reload();
                      window.close();
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