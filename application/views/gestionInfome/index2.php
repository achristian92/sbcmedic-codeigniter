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
  <title>SBCMedic | GESTIÓN EXÁMENES SBC</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php $this->load->view("styles"); ?>
   <style>
    .modal-header{
        background-color:#28b17b;
		color: #fff;
		font-weight: bold;
    }
  </style>
 
</head>

<body style="background: #a8ff78;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #78ffd6, #a8ff78);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #78ffd6, #a8ff78); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

">
  <div class="container">
    <div class="row bg-black">
      <div class="col-sm text-center" style=" justify-content: center;align-items: center;">
        <h2>GESTION DE EXÁMENES - SBC</h2>
      </div>
    </div>


    <div class="row mt-2">
      <div class="col-sm">
        <form action="<?php base_url('informe/index');?>" method="POST">
          <table id="gestion" class="table table-striped table-hover table-bordered" style="width:100%;background-color:#B2EBF2;">
            <thead>
              <tr>
                <td style="width: 13%;"><strong>Código Interno</strong></td>
                <td style="width: 15%;"><input type="text" class="form-control" name="codigoInterno"></td>
                <td style="width: 15%;" align="right"><strong>Paciente</strong></td>
                <td colspan="6"><select id="cmbUsuario" name="cmbUsuario" class="searchClient form-control select2" style="width: 100%;">
                      
                      </select></td>
                <td style="width: 15%;"><button type="submit" class="btn btn-primary">Consultar</button></td>
				<td style="width: 10%;"><a class="btn btn-dark" href="<?php echo base_url('informe/resumen'); ?>" role="button" target="_blank">Resumen</a></td>
              </tr>
          </table>
        </form>
          <table id="gestion" class="table table-striped table-hover table-bordered" style="width:100%;background-color:#B2EBF2;">
            <tr style="background-color: #11998e;">
              <th>Paciente</th>
              <th colspan="8" style="color:#FFF;"><a href=""  data-toggle="modal" data-target="#infoPaciente" data-usuario='<?php echo $idUsuario; ?>' <?php echo ($usuario)?"": "disabled" ?> style="color:honeydew;"><?php echo $usuario; ?></a></th>
            </tr>
            <tr>
              <th></th>
              <th>CÓDIGO INTERNO</th>
              <th>FECHA EXAMEN</th>
              <th>TIPO</th>
              <th>EXAMEN</th>
              <th>STATUS</th>
              <th></th>
              <th width="20%" colspan="2" style="text-align: center;"><button type="button" class="btn btn-success btn-lg active" data-toggle="modal" data-target="#newExamen" data-usuario='<?php echo $idUsuario; ?>' <?php echo ($usuario)?"": "disabled" ?> >Nuevo Examen</button></th>
            </tr>
          </thead>
          <tbody>
            <?php
              $codigoI = "";
              foreach ($resultados as $clave => $resultado){
                $clave++;
            ?>
                <tr>
                  <td><?php echo $clave;?></td>
                  <td><?php echo $resultado->codigo_interno;?></td>
                  <td><?php echo date("d/m/Y",strtotime($resultado->fechaExamen));?></td>
                  <td style="color:blue"><strong><?php echo $resultado->tipo;?></strong></td>
                  <td style="color:blue"><strong><?php echo $resultado->examen;?></strong></td>
                  <td><span class="badge badge-<?php if ($resultado->estado == 0) echo "warning"; else if ($resultado->estado == 1) echo "success"; else echo "primary"; ?>"><?php if ($resultado->estado == 0) echo "En Proceso"; else if ($resultado->estado == 1) echo "Procesado"; else echo "Envíado"; ?></span></td>

                  <td style="text-align: center;">
                    <?php if ($resultado->estado != 2) { ?>
                    <a class="btn btn-<?php echo ($resultado->idTipo)? "primary" : "success"; ?>" href="javascript:popUp('<?php echo base_url('informe/formulario/'.$resultado->idExamen)."/".$resultado->id;?>?usuario=<?php echo $resultado->idUsuario;?>&idTipo=<?php echo $resultado->idTipo;?>&idPerfil=<?php echo $resultado->idPerfil;?>&nuevo=<?php echo $resultado->nuevo;?>')" role="button" title="Modificar Datos"><i class="fas fa-external-link-alt"></i></a>
                    <?php } ?>
                  </td>
                  <td align="center">
                    <?php if ($resultado->estado >0 and $resultado->codigo_interno != $codigoI) { ?>
                      <a class="btn btn-warning" href='<?php echo  base_url("pdfinforme/$resultado->codigo_interno/$resultado->idUsuario/$resultado->idExamen");?>' role="button" target="_blank" title="Ver Pdf">Validar <i class="far fa-eye"></i></a>
                    <?php } ?>
                  </td>
                  <td>
                  <?php
                    if(($resultado->codigo_interno != $codigoI) && $resultado->estado) {
                      $codigoI = $resultado->codigo_interno;
                      if ($resultado->estado == 1) { ?>
                      <button type="button" class="btn btn-danger" onclick="enviar_pdf('<?php echo $resultado->idUsuario;?>', '<?php echo $resultado->codigo_interno;?>', '<?php echo $resultado->id;?>', '<?php echo $resultado->idExamen;?>')" title="Enviar informe pdf" id="btnEnvio<?php echo $resultado->id;?>"><i class="far fa-file-pdf"></i> Enviar Informe</button>
                  <?php 
                      } 
                    } 
                  ?>
            
                  </td>
                </tr>
            <?php
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
       
  </div>
        <!-- Modal -->
        <div class="modal fade" id="newExamen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <form name="frmExamen" id="frmExamen">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header d-block">
                <h4 class="modal-title text-center" id="newExamenLabel">SOLICITAR NUEVO EXAMEN<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
                <?php if($rol == 1 || $rol == 4 || $rol == 5) { ?>
                <div class="form-group">
                  <label for="exampleInputEmail1">Código Interno</label>
                  <input type="text" name="codigoInterno" class="form-control">
                </div>
                <?php } ?>
                <div class="form-group">
                  <label for="exampleInputEmail1">Fecha del Examen</label>
                  <input type="date" name="fecha" id="fecha" class="form-control" value="<?php echo date("Y-m-d"); ?>" requerid>
                </div>


                <div class="form-group">
                  <label for="cmbExamenes">Examen => &nbsp;&nbsp;S./ <span style="font-size: 18px;" class="badge badge-warning" id="calculoCosto">0</span></label>
                  <select id="cmbExamenes" name="cmbExamenes[]" class="form-control searchExamen" style="width: 100%;" multiple required>
                  </select>
                </div>

<!-- 
                <div class="form-group">
                  <label for="costoTransporte">Costo Transporte</label>
                  <input type="number" name="costoTransporte" class="form-control" value="0" min="0">
                </div> -->
				        <div class="row">
                  <div class="col">Descuento % <input type="number" id="porcentaje" name="porcentaje"  class="form-control" value="0" min="0" style="text-align: right;"></div>
                  <div class="col">Resultado %(S/.) <input type="number" id="descuento" name="descuento" class="form-control" value="0" style="text-align: right;" readonly></div>
                  
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" id="btnRegistrar" class="btn btn-primary btn-block ml-1">REGISTRAR EXAMEN</button>
                <input type="hidden" name="idUsuario" id="idUsuario">
                <input type="hidden" name="idexamenrealizado" id="idexamenrealizado">
              </div>
            </div>
          </div>
          </form>
        </div>
        <!-- /.Modal -->
		
		<!-- Modal paciente -->
        <div class="modal fade" id="infoPaciente"  tabindex="-1"  aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header d-block">
                <h4 class="modal-title text-center" id="newExamenLabel">Información del Paciente<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="nombres">Nombres</label>
                  <input type="text" id="nombres" class="form-control" readonly>
                </div>
                <div class="form-group">
                  <label for="nrodocumento">NRO Documento</label>
                  <input type="text" id="nrodocumento" class="form-control" readonly>
                </div>


                <div class="form-group">
                  <label for="telefono">Teléfono</label>
                  <input type="text" id="telefono" class="form-control" readonly>
                </div>

                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="text" id="email" class="form-control" readonly>
                </div>

                <div class="form-group">
                  <label for="email">Fecha de Nacimiento</label>
                  <input type="text" id="fechaNacimiento" class="form-control" readonly>
                </div>
                 
       
            </div>
          </div>
        </div>
        <!-- /.Modal -->

  <?php $this->load->view("scripts"); ?>
  <!-- Select2 -->
  <script src="<?php echo base_url('plugins/select2/js/select2.full.min.js');?>"></script>
  <script>
    var iduser = '<?php echo $idUsuario; ?>';
    $('.select2').select2();
    
  //enviar pdf

  function enviar_pdf(idUsuario, idCodigo, id, idExamen) {
    Swal.fire({
            title: '¿Esta seguro de Enviar el Informe PDF?',
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
                url: "<?php echo base_url("informe/enviarInforme");?>",
                data : { idUsuario : idUsuario, idCodigo: idCodigo, idExamen: idExamen },
                beforeSend: function () 
                {            
                  $("#btnEnvio"+ id).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
                  $("#btnEnvio"+ id).addClass("btn btn-primary");
                  $("#btnEnvio"+ id).prop('disabled', true);
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
                        window.location.replace("<?php echo base_url('informe/index?cmbUsuario=');?>" + iduser);
                      }
                    })
                  } else {
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

    }
    
    
    var frm = $('#frmExamen');
      $.validator.setDefaults({
        submitHandler: function () {
          
          Swal.fire({
          title: '¿ESTÁS SEGURO DE REGISTRAR EL EXAMEN?',
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
            type: 'POST',
            url: "<?php echo base_url("gNuevoExamen");?>",
            data: $('#frmExamen').serialize(),
            beforeSend: function () 
            {            
              $("#btnRegistrar").html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
              $("#btnRegistrar").removeClass("btn btn-primary btn-block ml-1");
              $("#btnRegistrar").addClass("btn btn-primary btn-block ml-1");
              $("#btnRegistrar").prop('disabled', true);
            },
            success: function (data) {
              if(data.status){  
                
                Swal.fire({
                  icon: 'success',
                  timer: 5000,
                  title: 'Respuesta exitosa',
                  text: data.message,
                  onClose: () => {
                    window.location.replace("<?php echo base_url('informe/index?cmbUsuario=');?>"+ $('#idUsuario').val());
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
                text: 'Ha ocurrido un error interno. NO SE PUEDE GUARDAR!',
                onClose: () => {
                    window.location.replace("<?php echo base_url('informe/index?cmbUsuario=');?>"+ $('#idUsuario').val());
                }
              })
            },
        }); 

      }
      }); 

      }
    });
    
    $('#frmExamen').validate({
        rules: {
          "cmbExamenes[]": {
            required: true
          },
          cmbEspecialidad: {
            required: true
          }
        },
        messages: {
          "cmbExamenes[]": {
            required: "Examen(s) es requerido."
          },
          cmbEspecialidad: {
            required: "Seleccione una especialidad"
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
    
    $('.searchExamen').select2({
      width: '100%',
      language: "es",
      placeholder: 'Seleccionar',
      minimumInputLength: 2,
      maximumSelectionLength: 20,
      ajax: {
        url: '<?php echo base_url("searchExamen");?>',
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
        return 'Ingrese 2 o más caracteres.';
        }
      }
    });

	let montoCosto = 0;
	
	$('#cmbExamenes').on('select2:select', function (e) {
	  var data = e.params.data.text.split('=');

	  montoCosto = montoCosto + data[1]*1;
	  $('#calculoCosto').text(montoCosto);
	  
	});

	$('#cmbExamenes').on('select2:unselect', function (e) {
	  var data = e.params.data.text.split('=');
	  montoCosto = montoCosto - data[1]*1;
	  $('#calculoCosto').text(montoCosto);
	});

      $('#newExamen').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var idUsuario = button.data('usuario')
        
        $('#idUsuario').val(idUsuario);
		montoCosto = 0;
      });
      

      $('#newExamen').on('hide.bs.modal', function (event) {
        
        $("#cmbExamenes").val('').trigger('change') ;
        $('.searchExamen').empty();
		$('#calculoCosto').text('0');

        $(this).find('form')[0].reset();

      });

      function popUp(URL) {
          window.open(URL, 'Nombre de la ventana', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=700,height=580,left = 390,top = 50');
      }
    

      $('.searchClient').select2({
        language: "es",
        placeholder: 'Seleccionar',
        minimumInputLength: 3,
        maximumSelectionLength: 10,
        ajax: {
          url: '<?php echo base_url("searchClient");?>',
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
	  
	   $('#infoPaciente').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var idUsuario = button.data('usuario')
        
        
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url("infoPaciente");?>",
            data: { idUsuario: idUsuario },
           
            success: function (data) {
              if(data.status){  
                $('#nombres').val(data.info.nombres);
                $('#nrodocumento').val(data.info.document);
                $('#telefono').val(data.info.phone);
                $('#email').val(data.info.email);
                $('#fechaNacimiento').val(data.info.birthdate);
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
                text: 'Ha ocurrido un error interno.',
                
              })
            },
        }); 
          
      });
  </script>
</body>

</html>