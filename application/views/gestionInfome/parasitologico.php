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
    <form id="frmExamen" action="<?php echo base_url('informe/gNuevoLaboratorioParasotologico');?>">
    <div class="row bg-black">
      <div class="col-sm text-center" style=" justify-content: center;align-items: center;">
        <h2>
          <?php if ($idExamen == 34){ ?>
            REACCION INFLAMATORIA
            <?php } else if ($idExamen == 37){ ?>
            UROCULTIVO
            <?php } else if ($idExamen == 38){ ?>
            COPROCULTIVO
            <?php } else { ?>
            PARASITOLÓGICO <?php echo ($idExamen == 31)? "SIMPLE" : "SERIADO"; ?>
          <?php } ?>
        </h2>
      </div>
    </div>

  
    <?php  if($idExamen == 31 || $idExamen == 32 || $idExamen == 34 || $idExamen == 37 || $idExamen == 38) {  ?>
      <div class="row mt-2">
      <div class="col-sm">
        <div class="form-group">
          <label for="color"><?php echo ($idExamen == 37)? "Células Epiteliales" : "Color"; ?></label>
          <input type="text" name="color" class="form-control" id="color" placeholder="<?php echo ($idExamen == 37)? "Células Epiteliales" : "Color"; ?>" value="<?php echo (isset($parasitologico[0]->color))? $parasitologico[0]->color: "";?>">
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
          <label for="aspecto"><?php echo ($idExamen == 37)? "Leucocitos" : "Aspecto"; ?></label>
          <input type="text" name="aspecto" class="form-control" id="aspecto" placeholder="<?php echo ($idExamen == 37)? "Leucocitos" : "Aspecto"; ?>" value="<?php echo (isset($parasitologico[0]->aspecto))? $parasitologico[0]->aspecto: "";?>">
        </div>
      </div>

    </div>

    <?php  } if($idExamen == 37) {  ?>
      <div class="row">
      <div class="col-sm">
        <div class="form-group">
          <label for="moco">Hematies</label>
          <input type="text" name="moco" class="form-control" placeholder="Hematies" value="<?php echo (isset($parasitologico[0]->moco))? $parasitologico[0]->moco: "";?>">
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
          <label for="sangre">Gérmenes</label>
          <input type="text" name="sangre" class="form-control" placeholder="Gérmenes" value="<?php echo (isset($parasitologico[0]->sangre))? $parasitologico[0]->sangre: "";?>">
        </div>
      </div>
      </div>
 
    <?php } else {  ?>

      <div class="row">
      <div class="col-sm">
        <div class="form-group">
          <label for="moco">Moco</label>
          <select class="form-control" name="moco">
              <option value="0" <?php echo (isset($parasitologico[0]->moco) and $parasitologico[0]->moco == 0)? "selected" : "";?>>Negativo</option>
              <option value="1" <?php echo (isset($parasitologico[0]->moco) and $parasitologico[0]->moco == 1)? "selected" : "";?>>Positivo</option>
          </select>
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
          <label for="sangre">Sangre</label>
          <select class="form-control" name="sangre">
            <option value="0" <?php echo (isset($parasitologico[0]->sangre) and $parasitologico[0]->sangre == 0)? "selected" : "";?>>Negativo</option>
            <option value="1" <?php echo (isset($parasitologico[0]->sangre) and $parasitologico[0]->sangre == 1)? "selected" : "";?>>Positivo</option>
        </select>
        </div>
      </div>
      </div>
    <?php } if($idExamen == 37) {  ?>
    <div class="row">
      <div class="col-sm">
        <div class="form-group">
          <label for="muestra1">Recuento de UFC/ML Coloniales</label>
          <input type="text" name="muestra1" class="form-control" id="muestra1" placeholder="Recuento de UFC/ML" value="<?php echo (isset($parasitologico[0]->muestra1))? $parasitologico[0]->muestra1: "";?>">
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
          <label for="muestra2">Coloración Gram</label>
          <input type="text" name="muestra2" class="form-control" id="muestra2" placeholder="Coloración Gram" value="<?php echo (isset($parasitologico[0]->muestra2))? $parasitologico[0]->muestra2: "";?>">
        </div>
      </div>
    </div>

    <?php } if($idExamen == 31 || $idExamen == 34) { ?>
    <div class="row">
      <div class="col-sm">
        <div class="form-group">
          <label for="muestra1">Muestra Única</label>
          <input type="text" name="muestra1" class="form-control" id="muestra1" placeholder="Muestra Única" value="<?php echo (isset($parasitologico[0]->muestra1))? $parasitologico[0]->muestra1: "";?>">
        </div>
      </div>
    </div>
    <?php } else if($idExamen == 38) { ?>
    <div class="row">
      <div class="col-sm">
        <div class="form-group">
          <label for="leucocitos">Leucocitos</label>
          <input type="text" name="muestra1" class="form-control" id="leucocitos" placeholder="Leucocitos" value="<?php echo (isset($parasitologico[0]->muestra1))? $parasitologico[0]->muestra1: "";?>">
        </div>
      </div>
    </div>
    <?php } else if($idExamen == 32) {  ?>
    <div class="row">
      <div class="col-sm">
        <div class="form-group">
          <label for="muestra1">Muestra Nro 1</label>
          <input type="text" name="muestra1" class="form-control" id="muestra1" placeholder="Muestra 1" value="<?php echo (isset($parasitologico[0]->muestra1))? $parasitologico[0]->muestra1: "";?>">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm">
        <div class="form-group">
          <label for="muestra2">Muestra Nro  2</label>
          <input type="text" name="muestra2" class="form-control" id="muestra2" placeholder="Muestra 2" value="<?php echo (isset($parasitologico[0]->muestra2))? $parasitologico[0]->muestra2: "";?>">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm">
        <div class="form-group">
          <label for="muestra3">Muestra Nro 3</label>
          <input type="text" name="muestra3" class="form-control" id="muestra3" placeholder="Muestra 3" value="<?php echo (isset($parasitologico[0]->muestra3))? $parasitologico[0]->muestra3: "";?>">
        </div>
      </div>
    </div>
    <?php }  if($idExamen == 31 || $idExamen == 37 || $idExamen == 38) { ?>

    <div class="row">
      <div class="col-sm">
        <div class="form-group">
          <label for="dato1">Observación</label>
          <textarea name="observacion" id="" cols="3" rows="3" class="form-control" placeholder="Observación"><?php echo (isset($parasitologico[0]->observacion))? $parasitologico[0]->observacion: "";?></textarea>
        </div>
      </div>
    </div>
    <?php }  ?>
  

    <div class="row">
      <div class="col-sm">
        <div class="form-group">
          <button type="submit" id="btnGuardar" class="btn btn-primary btn-block ml-1">REGISTRAR <?php if ($idExamen == 34){ ?>
            REACCION INFLAMATORIA
            <?php } else if ($idExamen == 37){ ?>
            UROCULTIVO
            <?php } else if ($idExamen == 38){ ?>
            COPROCULTIVO
            <?php } else { ?>
            PARASITOLÓGICO <?php echo ($idExamen == 31)? "SIMPLE" : "SERIADO"; ?>
          <?php } ?></button>
          <input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>">
          <input type="hidden" name="idExamen"  value="<?php echo $idExamen;?>">
          <input type="hidden" name="idUsuario"  value="<?php echo $idUsuario;?>">
          <input type="hidden" name="idTipo"  value="<?php echo $idTipo;?>">
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