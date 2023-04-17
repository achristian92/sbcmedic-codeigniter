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
  <title>SBCMedic | ENVÍO DE RESULTADOS</title>
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
  <br>
  <div class="container">
      <div class="row bg-black">
        <div class="col-sm text-center" style=" justify-content: center;align-items: center;">
          <h2>ENVÍO DE RESULTADOS</h2>
        </div>
      </div>
      <form method="POST" action="<?php echo base_url('gestionBuscarPaciente'); ?>">
        <div class="row mt-2">
            <div class="col-sm-4">
                <input type="date" name="fechaBusqueda" class="form-control" value="<?php echo $this->input->post("fechaBusqueda") ? $this->input->post("fechaBusqueda") : date("Y-m-d"); ?>">
            </div>
            <div class="col-sm-4">
                <input type="text" name="nombreDni" class="form-control" value="<?php echo $this->input->post("nombreDni") ? $this->input->post("nombreDni") : "" ?>" placeholder="Nombre, apellido, NroDocumento">
            </div>
            <div class="col-sm-4">
                <button type="submit" class="btn btn-success btn-block">Buscar...</button>
            </div>
        </div>
      </form>

      <div class="row mt-2">
        <div class="col-sm">
          <table id="gestion" class="table table-striped table-hover table-bordered" style="width:100%;background-color:#B2EBF2;">
            <thead>
              <tr>
   
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Dni/C.Extranjería</th>
                <th>pasaporte</th>
                <th>Edad</th>
                <th>Fecha Toma</th>
                <th></th>
                <th>Tipo</th>
                <th>Resultado</th>
                <th>Email:</th>
                <th>Eliminar:</th>
                <th>UsuarioEnvío:</th>
              </tr>
            </thead>
            <tbody>
              <?php

              foreach ($registros as $clave => $valor) {
                $clave++;
              ?>
                <tr style="background-color:<?php if ($valor->tipo_prueba == 2) echo "#ffa6a6"; else if($valor->tipo_prueba == 1 and $valor->resultado == 0) echo "#76c893"; else echo ""; ?>">
         
                  <td><?php echo $valor->nombre; ?></td>
                  <td><?php echo $valor->apellido; ?></td>
                  <td><?php echo $valor->dni; ?></td>
                  <td><?php echo $valor->pasaporte; ?></td>
                  <td align="center" style="<?php if($valor->edad <= 5) echo "background-color:#ffb703";?>"><?php echo $valor->edad; ?></td>
                  <td><?php echo $valor->fecha;?></td>
                  
        
                    <td>
                      <?php if($valor->resultado ==0){ ?>
                      <button type="button" class="btn btn-outline-<?php echo $valor->tipo_prueba == 2 ? "danger": "success" ; ?>" data-toggle="modal" data-target="#modal-warning" data-tipoprueba="<?php echo $valor->tipo_prueba; ?>" data-id="<?php echo $valor->id; ?>" data-email="<?php echo $valor->email; ?>"  data-idprincipal="<?php echo $valor->codPrincipal; ?>" title="Grabar Resultado" <?php if($valor->email == "" || $valor->edad == 0 || (!$valor->dni and !$valor->pasaporte)) echo "disabled"; ?> <?php if($rol ==7 ||  $rol ==2) echo  "disabled"; ?> >Grabar</button>
                      <?php } else { ?>
                        <a class="btn btn-outline-primary" href='<?php echo $valor->nombre_pdf !=""  ? base_url("view-resultPdf/$valor->nombre_pdf"): "javascript:void(0);"; ?>' role="button" target='<?php echo $valor->nombre_pdf != "" ?"_blank" : ""; ?>'><i class="fas fa-file-pdf"></i> Ver</a>
                      <?php }  ?>
                    </td>
                    <td style="color:<?php echo $valor->tipo_prueba == 2 ? "red" : "green"; ?>"><?php echo $valor->tipo_prueba == 2 ? "Molecular" : "Antígeno"; ?></td>
                    <td><strong><?php if($valor->resultado == 1) echo "Positivo"; else if($valor->resultado == 2) echo "Negativo"; else echo ""; ?></strong></td>
     
                    <td style="font-size:10px;"><?php echo $valor->email; ?></td>
                    
                  <td style="text-align: center;">
					<?php if($valor->resultado == 0){ ?>
					<button type="button" id="btn_cancelar_cita" class="btn btn-danger" title="Cancelar resultado" onClick="cancelarResultado('<?php echo $valor->id; ?>', '<?php echo $valor->codPrincipal; ?>')" <?php if($rol ==7 ||  $rol ==2) echo  "disabled"; ?> ><i class="fas fa-window-close"></i><?php }  ?></button>
                  
                </td>
                <td style="font-size:10px;"><?php echo $valor->usuarioEnvio; ?></td>
                </tr>
              <?php
              }
              ?>
            </tbody>
          </table>
        </div>
        
      </div>
       
      </div>




      <div class="modal fade" id="modal-warning">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">GRABAR RESULTADO</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form role="form" id="formCalificacion" method="post" action="<?php echo base_url('gResultadoPaciente'); ?>">

                <div class="form-group">
                  <label for="resultado">Valores referenciales</label>
                  <select class="form-control" name="resultado" id="resultado"> 
                    <option value="1">POSITIVO</option>
                    <option value="2" selected>NEGATIVO</option>
                  </select>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
              <input type="hidden" name="codPrincipal" id="codPrincipal">
              <input type="hidden" name="idResultado" id="idResultado">
              <input type="hidden" name="tipoPrueba" id="tipoPrueba">
              <input type="hidden" name="email" id="email">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="submit" id="btnGuardar" class="btn btn-primary">Guardar Resultado</button>
            </div>
          </div>
          <!-- /.modal-content -->
    </form>
  </div>
  <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->



  <br>


  <?php $this->load->view("scripts"); ?>
  <!-- Select2 -->
  <script src="<?php echo base_url('plugins/select2/js/select2.full.min.js'); ?>"></script>
  <!-- DataTables -->
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  
  <script>
    $('#gestion').DataTable({
      "lengthChange": false,
    "searching": false,
    "ordering": true,
      "responsive": true,
	  "order": [[ 5, "desc" ]],
      "language": {
        "url": "<?php echo base_url("plugins/datatables-bs4/js/Spanish.json"); ?>"
      }
    });

    $('#distrito').select2();

  function cancelarResultado(id, principal) {

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
          data: { idGestion : id, idPrincipal : principal },
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
                      location.reload();
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
    var tipoPrueba = button.data('tipoprueba');
    var codPrincipal = button.data('idprincipal');

    $('#idResultado').val(id);
    $('#email').val(email);
    $('#tipoPrueba').val(tipoPrueba);
    $('#resultado').val('2');
    $('#codPrincipal').val(codPrincipal);
  });
 
  $('#modal-warning').on('hide.bs.modal', function (event) {
 
    $('#idResultado').val('');
    $('#codPrincipal').val('');
    $('#tipoPrueba').val('');
    $('#email').val('');

  });

  </script>
</body>

</html>