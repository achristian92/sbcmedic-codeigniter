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
    <form id="frmExamen" action="<?php echo base_url('informe/gNuevoLaboratorioOrinaCompleto');?>">
    <div class="row bg-black">
      <div class="col-sm text-center" style=" justify-content: center;align-items: center;">
        <h2>EXAMEN COMPLETO DE ORINA</h2>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col-sm">
        <div class="form-group">
          <label for="color">Color</label>
          <input type="text" name="color" class="form-control" placeholder="Color" value="<?php echo (isset($orinaCompleto[0]->color))? $orinaCompleto[0]->color: "";?>">
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
          <label for="aspecto">Aspecto</label>
          <input type="text" name="aspecto" class="form-control" placeholder="Aspecto" value="<?php echo (isset($orinaCompleto[0]->aspecto))? $orinaCompleto[0]->aspecto: "";?>">
        </div>
      </div>

    </div>

    <div class="row">
      <div class="col-sm">
        <div class="form-group">
          <label for="ph">PH</label>
          <input type="text" name="ph" class="form-control" placeholder="PH" value="<?php echo (isset($orinaCompleto[0]->ph))? $orinaCompleto[0]->ph: "";?>">
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
          <label for="densidad">Densidad</label>
          <input type="text" name="densidad" class="form-control" placeholder="Densidad" value="<?php echo (isset($orinaCompleto[0]->densidad))? $orinaCompleto[0]->densidad: "";?>">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm">
        <div class="form-group">
            <label for="nitrito">Nitritos</label>
            <select class="form-control" name="nitrito">
                <option value="0" <?php echo (isset($orinaCompleto[0]->nitrito) and $orinaCompleto[0]->nitrito == 0)? "selected" : "";?>>Negativo</option>
                <option value="1" <?php echo (isset($orinaCompleto[0]->nitrito) and $orinaCompleto[0]->nitrito == 1)? "selected" : "";?>>Positivo</option>
            </select>
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
            <label for="urobilinogeno">Urobilinogeno</label>
            <select class="form-control" name="urobilinogeno">
                <option value="0" <?php echo (isset($orinaCompleto[0]->urobilinogeno) and $orinaCompleto[0]->urobilinogeno == 0)? "selected" : "";?>>Negativo</option>
                <option value="1" <?php echo (isset($orinaCompleto[0]->urobilinogeno) and $orinaCompleto[0]->urobilinogeno == 1)? "selected" : "";?>>Positivo</option>
            </select>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm">
        <div class="form-group">
            <label for="glucosa">Glucosa</label>
            <select class="form-control" name="glucosa">
                <option value="0" <?php echo (isset($orinaCompleto[0]->glucosa) and $orinaCompleto[0]->glucosa == 0)? "selected" : "";?>>Negativo</option>
                <option value="1" <?php echo (isset($orinaCompleto[0]->glucosa) and $orinaCompleto[0]->glucosa == 1)? "selected" : "";?>>Positivo</option>
            </select>
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
            <label for="sangre">Sangre</label>
            <select class="form-control" name="sangre">
                <option value="0" <?php echo (isset($orinaCompleto[0]->sangre) and $orinaCompleto[0]->sangre == 0)? "selected" : "";?>>Negativo</option>
                <option value="1" <?php echo (isset($orinaCompleto[0]->sangre) and $orinaCompleto[0]->sangre == 1)? "selected" : "";?>>Positivo</option>
            </select>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm">
        <div class="form-group">
            <label for="proteina">Proteínas</label>
            <select class="form-control" name="proteina">
                <option value="0" <?php echo (isset($orinaCompleto[0]->proteina) and $orinaCompleto[0]->proteina == 0)? "selected" : "";?>>Negativo</option>
                <option value="1" <?php echo (isset($orinaCompleto[0]->proteina) and $orinaCompleto[0]->proteina == 1)? "selected" : "";?>>Positivo</option>
            </select>
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
            <label for="cetona">Cetonas</label>
            <select class="form-control" name="cetona">
                <option value="0" <?php echo (isset($orinaCompleto[0]->cetona) and $orinaCompleto[0]->cetona == 0)? "selected" : "";?>>Negativo</option>
                <option value="1" <?php echo (isset($orinaCompleto[0]->cetona) and $orinaCompleto[0]->cetona == 1)? "selected" : "";?>>Positivo</option>
            </select>
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
            <label for="bilirrubina">Bilirrubinas</label>
            <select class="form-control" name="bilirrubina">
                <option value="0" <?php echo (isset($orinaCompleto[0]->bilirrubina) and $orinaCompleto[0]->bilirrubina == 0)? "selected" : "";?>>Negativo</option>
                <option value="1" <?php echo (isset($orinaCompleto[0]->bilirrubina) and $orinaCompleto[0]->bilirrubina == 1)? "selected" : "";?>>Positivo</option>
            </select>
        </div>
      </div>
    </div>
    

    <div class="row">
      <div class="col-sm">
        <div class="form-group">
          <label for="cepitelial">Cel. Epiteliales</label>
          <input type="text" name="cepitelial" class="form-control" placeholder="Cel. Epiteliales" value="<?php echo (isset($orinaCompleto[0]->cepitelial))? $orinaCompleto[0]->cepitelial: "";?>">
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
          <label for="leucocito">Leucocitos</label>
          <input type="text" name="leucocito" class="form-control" placeholder="Leucocitos" value="<?php echo (isset($orinaCompleto[0]->leucocito))? $orinaCompleto[0]->leucocito: "";?>">
        </div>
      </div>

    </div>

    <div class="row">
      <div class="col-sm">
        <div class="form-group">
          <label for="hematie">Hematies</label>
          <input type="text" name="hematie" class="form-control" placeholder="Hematies" value="<?php echo (isset($orinaCompleto[0]->hematie))? $orinaCompleto[0]->hematie: "";?>">
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
          <label for="germen">Gérmenes</label>
          <input type="text" name="germen" class="form-control" placeholder="Gérmenes" value="<?php echo (isset($orinaCompleto[0]->germen))? $orinaCompleto[0]->germen: "";?>">
        </div>
      </div>
    </div>

	<div class="row">
      <div class="col-sm">
        <div class="form-group">
          <label for="hematie">Observaciones</label>
          <textarea name="observacion" id="" cols="3" rows="3" class="form-control" placeholder="Observación"><?php echo (isset($orinaCompleto[0]->observacion))? $orinaCompleto[0]->observacion: "";?></textarea>
        </div>
      </div>
   
    </div>


    <div class="row">
      <div class="col-sm">
        <div class="form-group">
          <button type="submit" id="btnGuardar" class="btn btn-primary btn-block ml-1">REGISTRAR EXAMEN COMPLETO DE ORINA</button>
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