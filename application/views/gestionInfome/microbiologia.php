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
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

</head>

<body style="background: #a8ff78;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #78ffd6, #a8ff78);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #78ffd6, #a8ff78); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

">
  <div class="container">
    <div class="row bg-black">
      <div class="col-sm text-center" style=" justify-content: center;align-items: center;">
        <h2>MICROBIOLOGÍA</h2>
      </div>
    </div>

    <div class="row">
      <div class="col-sm" style=" justify-content: center;align-items: center;">
        <h3><u>EXAMEN FÍSICO</u></h3>
      </div>
    </div>
 

    <div class="row mt-2">
        <div class="col-sm">
          <div class="form-group">
            <label for="telefono">Color</label>
            <input type="text" name="telefono" class="form-control" id="telefono">
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="distrito">Aspecto</label>
            <input type="text" name="telefono" class="form-control" id="telefono">

          </div>
        </div>
     
      </div>
 

    <div class="row mt-2">
        <div class="col-sm">
          <div class="form-group">
            <label for="telefono">PH</label>
            <input type="text" name="telefono" class="form-control" id="telefono">
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="distrito">Densidad</label>
            <input type="text" name="telefono" class="form-control" id="telefono">

          </div>
        </div>
     
      </div>


      <div class="row mt-2">
      <div class="col-sm" style=" justify-content: center;align-items: center;">
        <h3><u>EXAMEN QUÍMICO</u></h3>
      </div>
    </div>

    <div class="row">
        <div class="col-sm">
          <div class="form-group">
            <label for="telefono">Nitritos</label>
            <select class="form-control" name="nitritos">
                <option value="0">Negativo</option>
                <option value="1">Positivo</option>
            </select>
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="distrito">Glucosa</label>
            <select class="form-control" name="nitritos">
                <option value="0">Negativo</option>
                <option value="1">Positivo</option>
            </select>

          </div>
        </div>
     
      </div>

    <div class="row mt-2">
        <div class="col-sm">
          <div class="form-group">
            <label for="telefono">Proteinas</label>
            <select class="form-control" name="nitritos">
                <option value="0">Negativo</option>
                <option value="1">Positivo</option>
            </select>
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="distrito">Bilirrubinas</label>
            <select class="form-control" name="nitritos">
                <option value="0">Negativo</option>
                <option value="1">Positivo</option>
            </select>

          </div>
        </div>
     
      </div>

    <div class="row mt-2">
        <div class="col-sm">
          <div class="form-group">
            <label for="telefono">Urobilinogeno</label>
            <select class="form-control" name="nitritos">
                <option value="0">Negativo</option>
                <option value="1">Positivo</option>
            </select>
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="distrito">Sangre</label>
            <select class="form-control" name="nitritos">
                <option value="0">Negativo</option>
                <option value="1">Positivo</option>
            </select>

          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="distrito">Cetonas</label>
            <select class="form-control" name="nitritos">
                <option value="0">Negativo</option>
                <option value="1">Positivo</option>
            </select>

          </div>
        </div>


       
         
     
      </div>


 

      <div class="row mt-2">
      <div class="col-sm" style=" justify-content: center;align-items: center;">
        <h3><u>EXAMEN MICROSCÓPICO</u></h3>
      </div>
    </div>

      <div class="row">
        <div class="col-sm">
          <div class="form-group">
            <label for="telefono">Cel. Epiteliales</label>
            <input type="text" name="telefono" class="form-control" id="telefono">
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="distrito">Leucocitos</label>
            <input type="text" name="telefono" class="form-control" id="telefono">

          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="distrito">Hematies</label>
            <input type="text" name="telefono" class="form-control" id="telefono">

          </div>
        </div>
     
      </div>

 
      <div class="row mt-2">
        <div class="col-sm">
          <div class="form-group">
            <label for="telefono">Germes</label>
            <input type="text" name="telefono" class="form-control" id="telefono">
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="distrito">Fil. Mucoides</label>
            <input type="text" name="telefono" class="form-control" id="telefono">

          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="distrito">Cristales</label>
            <input type="text" name="telefono" class="form-control" id="telefono">

          </div>
        </div>
     
      </div>


      <div class="row mt-2">
        <div class="col-sm">
          <div class="form-group">
            <button type="submit" id="btnRegistrarHema" class="btn btn-primary btn-block ml-1">REGISTRAR MICROBIOLOGÍA</button>
          </div>
        </div>


     
      </div>
 


     
      </div>
  
   


</div>


    
  </div>



 


  <?php $this->load->view("scripts"); ?>
  <!-- Select2 -->
  <script src="<?php echo base_url('plugins/select2/js/select2.full.min.js'); ?>"></script>
  <!-- DataTables -->
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script type="text/javascript">
    function popUp(URL) {
        window.open(URL, 'Nombre de la ventana', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=700,height=500,left = 390,top = 50');
    }
 
    $('#gestion').DataTable({

      "responsive": true,
      "language": {
        "url": "<?php echo base_url("plugins/datatables-bs4/js/Spanish.json"); ?>"
      }
    });

    $('#distrito').select2();

  function cancelarResultado(id) {

    Swal.fire({
    title: '¿Estás seguro de CANCELAR esta resultado?',
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
          url: "<?php echo base_url("gCancelarResultado");?>",
          data: { idGestion : id},
          success: function (data) {
            if(data.status){  
              
              Swal.fire({
                icon: 'success',
                timer: 7000,
                title: 'Respuesta exitosa',
                text: data.message,
                onClose: () => {
                  window.location.replace("<?php echo base_url('gestionBuscarPaciente');?>");
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

    var frm = $('#formCalificacion');
    $.validator.setDefaults({
      submitHandler: function() {
 
        $("#btnGuardar").attr('disabled', true);
        Swal.fire({
          title: '¿Esta seguro de guardar el resultado?',
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
              type: frm.attr('method'),
              url: frm.attr('action'),
              data: frm.serialize(),
              beforeSend: function () 
              {            
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
                      window.location.replace("<?php echo base_url('gestionBuscarPaciente'); ?>");
                    }
                  })
                } else {
                  $("#btnGuardar").attr('disabled', false);
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

    
  $('#formCalificacion').validate({
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

  $('#modal-warning').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var email = button.data('email');

    $('#idResultado').val(id);
    $('#email').val(email);
    $('#resultado').val('2');
  });
 
  $('#modal-warning').on('hide.bs.modal', function (event) {
 
    $('#idResultado').val('');
    $('#email').val('');

  });

  </script>
</body>

</html>