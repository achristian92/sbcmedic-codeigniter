<?php

defined('BASEPATH') or exit('No direct script access allowed');
?>

<!DOCTYPE html>

<html>



<head>

 

  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <link rel="shortcut icon" type="image/x-icon" href="<?php echo  base_url('img/favicon.ico'); ?>" />

  <title>SBCMedic | RESUMÉN PRUEBAS</title>

  <!-- Tell the browser to be responsive to screen width -->

  <meta name="viewport" content="width=device-width, initial-scale=1">

 

  <?php $this->load->view("styles"); ?>

  <!-- DataTables -->

  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">

  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">

  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">

  <!-- iCheck for checkboxes and radio inputs -->

  <link rel="stylesheet" href="<?php echo  base_url('plugins/icheck-bootstrap/icheck-bootstrap.min.css'); ?>">

</head>



<body style="background: #a8ff78;  /* fallback for old browsers */

background: -webkit-linear-gradient(to right, #78ffd6, #a8ff78);  /* Chrome 10-25, Safari 5.1-6 */

background: linear-gradient(to right, #78ffd6, #a8ff78); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */



">

  <br>

  <div class="container-fluid">

      <div class="row bg-black">

        <div class="col-sm text-center" style=" justify-content: center;align-items: center;">

          <h2>RESUMÉN PRUEBAS ANTÍGENO</h2>

        </div>

      </div>





      <div class="row mt-2">

        <div class="col-sm">

         



          <table id="gestion" class="display responsive  " style="width:100%;background-color:#B2EBF2;">

            <thead>

              <tr>

                <th></th>

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

                <th>HoraToma</th>
                <th>Precio Costo(S./): </th>
                


				<th>Pago</th>

                
				<th align="center">Realizado</th>

        <th>Cantidad de Antígenos</th>

                

                
<th>CostoPruebasAnt.</th>

				<th>CantidadPCR: </th>
                <th>CostoPruebasPCR: </th>
           <!--      <th>Pruebas Promocionales: </th>

                <th>Costo Promocionales: </th>
 -->
                <th>Costo Transponte: </th>

                <th>Descuento(S./): </th>

         

                

                <th>Email: </th>

                <th>Dirección: </th>

                <th>Teléfono: </th>
				<th>TipoBanco</th>

                <th>Motivo: </th>
                <th>Sede: </th>

              </tr>

            </thead>

            <tbody>

              <?php

              foreach ($registros as $clave => $valor) {

                $clave++;

              ?>

                <tr>

                  <td><?php echo $clave; ?></td>

                  <?php

                    if ($aPaciente_antigeno == "actualizar_paciente_antigeno") {

                  ?>

                  <td>
                    
                      <?php
                        if ($valor->id > 610  ) {
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

                  <td><?php echo date("d/m/Y",strtotime($valor->fecha));?></td>

                  <td><?php echo date("H:i",strtotime($valor->hora));?></td>

                  <td><span class="badge badge-success"><?php echo substr($valor->total, 0, 6); ?></span></td>

               

				        	
                  <td align="center" style="<?php if($valor->pago == 0) echo "background-color: #dd3e54;"; ?>"> 

<div class="custom-control custom-checkbox">

  <div class="icheck-primary d-inline">

    <input type="checkbox" name="pendiente<?php echo $clave; ?>" id="pendiente<?php echo $clave; ?>" value="<?php echo $valor->pago; ?>" <?php if($valor->pago ==1) echo "checked"; ?> onClick="marcarCkeckNew('<?php echo $valor->id; ?>', '<?php echo $clave; ?>')"  <?php if($idUsuario == 190 || $idUsuario == 2386 || $idUsuario == 6436 || $idUsuario == 8479) echo ""; else echo "disabled"; ?> >

    <label for="pendiente<?php echo $clave; ?>">

      <?php if($valor->pago ==1) echo "SI"; else echo "No"; ?>

    </label>

  </div>

</div></td>

                  <td align="center" style="<?php if($valor->realizado == 0) echo "background-color: #dd3e54;"; ?>"> 
                                <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#modal-warning" data-codigo="<?php echo $valor->id; ?>"   title="Grabar Resultado" <?php echo ($valor->realizado) ? "disabled" : ""; ?>><?php echo ($valor->realizado) ? "SI" : "NO"; ?></button>
         
                  
                  
                  </td>
                  
                  <td align="center"><?php echo $valor->cantidadP; ?></td>




					
				        	<td align="center"><?php echo $valor->costo_cantidadPrueba; ?></td>
					<td align="center"><?php echo $valor->cantidadPrueba_psr; ?></td>
				        	<td align="center"><?php echo $valor->costo_cantidadPrueba_psr; ?></td>

<!--                   <td><?php echo $valor->pPromocional; ?></td>

                  <td><?php echo $valor->costo_pruebaPromocional; ?></td> -->

                  <td><?php echo $valor->costo_transporte; ?></td>

                  <td><?php echo $valor->porcentajeDescuento; ?></td>

        

                  <td><?php echo $valor->email; ?></td>

                  <td><?php echo $valor->direccion; ?></td>

                  <td><?php echo $valor->telefono; ?></td>
				  <td><?php echo $valor->tipoBanco; ?></td>

                  <td><?php echo $valor->motivo; ?></td>
                  <td><?php echo $valor->sede; ?></td>

                </tr>

              <?php

              }

              ?>

            </tbody>

          </table>

        </div>

        

      </div>

       

      </div>



  </div>

  <!-- /.modal-dialog -->

  </div>

  <!-- /.modal -->



 

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
                  <select class="form-control comobSelect2" name="resultado" id="resultado">
                  <option value="" selected>Seleccionar</option>
                    <option value="1">SI</option>
                    <option value="0">NO</option>
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
              <input type="hidden" name="codPrincipal" id="codPrincipal">
              <input type="hidden" name="idResultado" id="idResultado">
              <input type="hidden" name="tipoPrueba" id="tipoPrueba">
              <input type="hidden" name="email" id="email">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="submit" id="btnGuardar" class="btn btn-primary">Guardar Resultado</button>
            </div>
          </div>
        </div>
      </div>
          <!-- /.modal-content -->



  <br>





 
  <!-- Select2 -->

 
 

  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

  <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>

  <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

  <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>

  <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
 

 
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="js/adminlte.min.js"></script>
<!-- Bootstrap 4 -->
 
<script src="js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- jquery-validation -->
<script src="plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="plugins/jquery-validation/additional-methods.min.js"></script>



  <!-- Select2 -->
  <script src="<?php echo base_url('plugins/select2/js/select2.full.min.js'); ?>"></script>
<!-- jquery-validation -->
<script src="plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="plugins/jquery-validation/additional-methods.min.js"></script>



  <script>
 
  $('.comobSelect2').select2();
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
                      window.location.replace("<?php echo base_url('gestionResumenPaciente'); ?>");
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
    $('#resultado').val('2');
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
  
  

    $('#gestion').DataTable( {

      "searching": true,

  /*     "order": [[ 3, "desc" ]], */

      "language": {

        "url": "<?php echo base_url("plugins/datatables-bs4/js/Spanish.json"); ?>"

      },

      responsive: true,

        dom: 'Bfrtip',

        buttons: [

            'excel'

        ]

    } )

 



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



    

                        window.location.replace("<?php echo base_url('gestionResumenPaciente'); ?>");

                      

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