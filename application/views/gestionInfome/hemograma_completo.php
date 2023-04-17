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
    <form id="frmExamen" action="<?php echo base_url('informe/gNuevoHemograma');?>">
    <div class="row bg-black">
      <div class="col-sm text-center" style=" justify-content: center;align-items: center;">
        <h2>HEMOGRAMA COMPLETO</h2>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col-sm">
        <div class="form-group">
          <label for="leu">LEU</label>
          <input type="text" name="leu" value="<?php echo (isset($hemograma[0]->leu))? $hemograma[0]->leu: "";?>" class="form-control" id="leu" placeholder="LEU">
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
          <label for="dato1">ERI</label>
          <input type="text" name="eri" value="<?php echo (isset($hemograma[0]->eri))? $hemograma[0]->eri: "";?>" class="form-control" id="eri" placeholder="ERI">
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
          <label for="hb">HB</label>
          <input type="text" name="hb" value="<?php echo (isset($hemograma[0]->hb))? $hemograma[0]->hb: "";?>" class="form-control" id="hb" placeholder="HB">
        </div>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col-sm">
        <div class="form-group">
          <label for="htc">HTC</label>
          <input type="text" name="htc" value="<?php echo (isset($hemograma[0]->htc))? $hemograma[0]->htc: "";?>" class="form-control" id="htc" placeholder="HTC">
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
          <label for="vcm">VCM</label>
          <input type="text" name="vcm" value="<?php echo (isset($hemograma[0]->vcm))? $hemograma[0]->vcm: "";?>" class="form-control" id="vcm" placeholder="VCM">
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
          <label for="hcm">HCM</label>
          <input type="text" name="hcm" value="<?php echo (isset($hemograma[0]->hcm))? $hemograma[0]->hcm: "";?>" class="form-control" id="hcm" placeholder="HCM">
        </div>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col-sm">
        <div class="form-group">
          <label for="ccmh">CCMH</label>
          <input type="text" name="ccmh" value="<?php echo (isset($hemograma[0]->ccmh))? $hemograma[0]->ccmh: "";?>" class="form-control" id="ccmh" placeholder="CCMH">
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
          <label for="plaq">PLAQ</label>
          <input type="text" name="plaq" value="<?php echo (isset($hemograma[0]->plaq))? $hemograma[0]->plaq: "";?>" class="form-control" id="plaq" placeholder="Plaq">
        </div>
      </div>
    </div>
 
    <div class="row mt-2">
      <div class="col-sm">
        <div class="form-group">
          <label for="mielocito">Mielocitos</label>
          <input type="text" name="mielocito" value="<?php echo (isset($hemograma[0]->mielocito))? $hemograma[0]->mielocito: "";?>" class="form-control" id="mielocito" placeholder="Mielocitos">
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
          <label for="metamielocito">Metamielocitos</label>
          <input type="text" name="metamielocito" value="<?php echo (isset($hemograma[0]->metamielocito))? $hemograma[0]->metamielocito: "";?>" class="form-control" id="metamielocito" placeholder="Metamielocitos">
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
          <label for="abastonado">Abastonados</label>
          <input type="text" name="abastonado" value="<?php echo (isset($hemograma[0]->abastonado))? $hemograma[0]->abastonado: "";?>" class="form-control" id="abastonado" placeholder="Abastonados">
        </div>
      </div>
    </div>
 
    <div class="row mt-2">
      <div class="col-sm">
        <div class="form-group">
          <label for="segmentado">Segmentados</label>
          <input type="text" name="segmentado" value="<?php echo (isset($hemograma[0]->segmentado))? $hemograma[0]->segmentado: "";?>" class="form-control" id="segmentado" placeholder="Segmentados">
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
          <label for="eosinofilo">Eosinofilos</label>
          <input type="text" name="eosinofilo" value="<?php echo (isset($hemograma[0]->eosinofilo))? $hemograma[0]->eosinofilo: "";?>" class="form-control" id="eosinofilo" placeholder="Eosinofilos">
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
          <label for="basofilo">Basofilos</label>
          <input type="text" name="basofilo" value="<?php echo (isset($hemograma[0]->basofilo))? $hemograma[0]->basofilo: "";?>" class="form-control" id="basofilo" placeholder="Basofilos">
        </div>
      </div>
    </div>
 
    <div class="row mt-2">
      <div class="col-sm">
        <div class="form-group">
          <label for="linfocito">Linfocitos</label>
          <input type="text" name="linfocito" value="<?php echo (isset($hemograma[0]->linfocito))? $hemograma[0]->linfocito: "";?>" class="form-control" id="linfocito" placeholder="Linfocitos">
        </div>
      </div>
      <div class="col-sm">
        <div class="form-group">
          <label for="monocito">Monocitos</label>
          <input type="text" name="monocito" value="<?php echo (isset($hemograma[0]->monocito))? $hemograma[0]->monocito: "";?>" class="form-control" id="monocito" placeholder="Monocitos">
        </div>
      </div>
    </div>
    <div class="row mt-2">
      <div class="col">
        <div class="form-group">
          <label for="observaciones">Observaciones</label>
          <textarea name="observaciones" id="" cols="2" rows="2" class="form-control" placeholder="observaciones"><?php echo (isset($hemograma[0]->observaciones))? $hemograma[0]->observaciones: "";?></textarea>
        </div>
      </div>
    </div>
	
    <div class="row mt-2">
      <div class="col-sm">
        <div class="form-group">
          <button type="submit" id="btnGuardar" class="btn btn-primary btn-block ml-1">REGISTRAR HEMOGRAMA COMPLETO</button>
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
          title: '¿Esta seguro de guardar el exámen?',
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