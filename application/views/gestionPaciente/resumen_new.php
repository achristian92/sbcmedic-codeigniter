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
  <link rel="stylesheet" href="<?php echo  base_url('plugins/icheck-bootstrap/icheck-bootstrap.min.css'); ?>">

</head>

<body style="background: #a8ff78;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #78ffd6, #a8ff78);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #78ffd6, #a8ff78); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

">
  <br>
  <div class="container">
      <div class="row bg-black">
        <div class="col-sm text-center" style=" justify-content: center;align-items: center;">
          <h2>RESUMÉN PRUEBAS COVID</h2>
        </div>
      </div>
      <form method="POST" action="<?php echo base_url('gestionBuscarPaciente-new'); ?>">
        <div class="row mt-2">
            <div class="col-sm-8">
                <input type="date" name="fechaBusqueda" class="form-control" value="<?php echo $this->input->post("fechaBusqueda") ? $this->input->post("fechaBusqueda") : date("Y-m-d"); ?>">
            </div>
            <div class="col-sm">
                <button type="submit" class="btn btn-success btn-block">Buscar...</button>
            </div>
        </div>
      </form>

      <div class="row mt-2">
        <div class="col-sm">
          <table id="gestion" class="table table-striped table-hover table-bordered" style="width:100%;background-color:#B2EBF2;">
            <thead>
            <tr>

            <?php

if ($aPaciente_antigeno == "actualizar_paciente_antigeno") {

?>

<th></th>

<?php

}

?>


<th>Paciente</th>
<th>Distrito</th>
<th>FechaToma</th>

<th>Precio Total(S./): </th>



<th>Pago</th>

<!-- 
<th align="center">Realizado</th>
 -->
<th></th>
<th>CantidadAntígenos</th>




<th>PrecioPruebasAnt.</th>

<th>CantidadPCR: </th>
<th>PrecioPruebasPCR: </th>
<th>PrecioTransponte: </th>

<th>Descuento(S./): </th>
 <th>Dirección:</th>
 <th>TipoPago:</th>
 <th>UsuarioRegistro:</th>
 <th>Sede:</th>
 <th>UsuarioPago:</th>
</tr>
            </thead>
            <tbody>
              <?php

              foreach ($registros as $clave => $valor) {
                $clave++;
              ?>
                 <tr>


<?php

  if ($aPaciente_antigeno == "actualizar_paciente_antigeno") {

?>

<td>
  
    <?php
      if (!$valor->realizado ) {
    ?>
  <a href="editarGestionPaciente/<?php echo $valor->id; ?>" target="_blank">Editar</a>

  <?php
      }
  ?>
</td>

<?php

  }

?>
  <td><?php echo $valor->nombres; ?></td>
<td><?php echo $valor->distrito; ?></td>

<td><?php echo $valor->fecha;?></td>


<td><span class="badge badge-success"><?php echo substr($valor->total, 0, 6); ?></span></td>



          
<td align="center" style="<?php if($valor->pago == 0) echo "background-color: green;"; ?>"> 

<div class="custom-control custom-checkbox">

<div class="icheck-primary d-inline">

<input type="checkbox" name="pendiente<?php echo $clave; ?>" id="pendiente<?php echo $clave; ?>" value="<?php echo $valor->pago; ?>" <?php if($valor->pago ==1) echo "checked"; ?> onClick="marcarCkeckNew('<?php echo $valor->id; ?>', '<?php echo $clave; ?>')" <?php if($valor->pago ==1) echo "disabled"; ?>  <?php if($idUsuario == 190 || $idUsuario == 228 || $idUsuario == 4212) echo ""; else echo "disabled"; ?> >

<label for="pendiente<?php echo $clave; ?>">

<?php if($valor->pago ==1) echo "SI"; else echo "No"; ?>

</label>

</div>

</div></td>


<td><?php if(date('Y-m-d', time()) == $valor->fecha) { ?>
	<a class="btn btn-warning" href="<?php echo base_url('cash-management-antigeno/print/').$valor->id; ?>" role="button" title="Imprimir Ticket" target="_blank"><i class="fas fa-print"></i></a>
<?php } ?>
</td>

<td align="center"><?php echo $valor->cantidadP; ?></td>




  
          <td align="center"><?php echo $valor->costo_cantidadPrueba; ?></td>
  <td align="center"><?php echo $valor->cantidadPrueba_psr; ?></td>
          <td align="center"><?php echo $valor->costo_cantidadPrueba_psr; ?></td>
 
          <td><?php echo $valor->costo_transporte; ?></td>

<td><?php echo $valor->porcentajeDescuento; ?></td>
<td><?php echo $valor->direccion; ?></td>
<td><?php echo $valor->tipoBanco; ?></td>
<td><?php echo $valor->usuarioRegistro; ?></td>
<td><?php echo $valor->sede; ?></td>
<td><?php echo $valor->usuarioPago. " / ". $valor->fechaPago; ?></td>
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
              <form role="form" id="formCalificacion" method="post" action="<?php echo base_url('gestionResumenRealizado-new'); ?>">

                <div class="form-group">
                  <label for="resultado">Realizado</label>
                  <select class="form-control" name="resultado" id="resultado">
                    <option value="1" selected>SI</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="especialidades">Especialidad</label>
                      <select class="form-control comobSelect2" required name="especialidades" id="especialidades" required>
                      <option value="">Especialidad</option>
                      <?php foreach ($especialidades as $especialidad) { ?>
                        <option value="<?=$especialidad->idSpecialty;?>" ><?=$especialidad->name;?></option>                    
                      <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                <select class="form-control comobSelect2" id="profesionales" name="profesionales" required>
                  <option value="">Profesional</option>
                </select>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
              <input type="hidden" name="codigo" id="codigo">
 
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="submit" id="btnGuardar" class="btn btn-primary">Guardar Resultado</button>
            </div>
          </div>
        </div>
      </div>
  <!-- /.modal-dialog -->
 



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
      $('.comobSelect2').select2();

    $('#gestion').DataTable({

      "responsive": true,
 	"order": [[ 3, "desc" ]],
      "language": {
        "url": "<?php echo base_url("plugins/datatables-bs4/js/Spanish.json"); ?>"
      }
    });
    
    $("#especialidades").change(function(){
        // Guardamos el select de cursos
        var doctores = $("#profesionales");

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
                 
                if (data.status) {
                  Swal.fire({
                    icon: 'success',
                    timer: 5000,
                    title: 'Respuesta exitosa',
                    text: data.message,
                    onClose: () => {
                      //window.location.replace("<?php echo base_url('gestionBuscarPaciente-new'); ?>");
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
            required: 'Seleccionar'
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
        var codigo = button.data('codigo');
        var tipoPrueba = button.data('tipoprueba');
        var codPrincipal = button.data('idprincipal');
    
        $('#idResultado').val(id);
        $('#codigo').val(codigo);
        $('#tipoPrueba').val(tipoPrueba);
        $('#resultado').val('1');
        $('#codPrincipal').val(codPrincipal);
  });
 
  $('#modal-warning').on('hide.bs.modal', function (event) {
    $('#idResultado').val('');
    $('#codPrincipal').val('');
    $('#tipoPrueba').val('');
    $('#email').val('');
    $('#codigo').val('');

    $(this).find('form')[0].reset();
    $(".comobSelect2").val('').trigger('change') ;
  });    




  function marcarCkeckNew(codigo, id) {



if ($('#pendiente' + id).prop("checked") == true) {

  $('#pendiente' + id).val(1);

  

} else {

  $('#pendiente' + id).val(0);

}



$.ajax({

          type: 'POST', 

          url: '<?php echo base_url('gestionResumenRealizado-pay')?>',

          data: { codigo: codigo, valor: $('#pendiente' + id).val()},

          success: function(data) {

            if (data.status) {

alert('Se proceso correctamente.');

              //location.reload();

                 // window.location.replace("<?php echo base_url('gestionResumenPaciente'); ?>");

                

            } else {

              alert('Error');

            }

          },

          error: function(data) {

            alert('Error');

          }

        });





}

  </script>
</body>

</html>