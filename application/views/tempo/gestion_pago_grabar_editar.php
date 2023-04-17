<?php
  defined('BASEPATH') or exit('No direct script access allowed');
  $route['payment-management/update-product'] = 'welcome/gestion_pago_actualizarProducto';
?>
<!DOCTYPE html>
<html>
<head>
  <base href="consulta">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo  base_url('img/favicon.ico'); ?>" />
  <title>SBCMedic | GESTIÓN EXÁMENES</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php $this->load->view("styles"); ?>

  <link rel="stylesheet" href="<?php echo base_url('plugins/icheck-bootstrap/icheck-bootstrap.min.css'); ?>">
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
            <h2>GESTIÓN DE PAGOS</h2>
          </div>
        </div>
          
        <div class="row mt-2">
          <div class="col-sm">
            <?php
              
              $montoTotal = 0;
              if($resumen->num_rows() >0 )
              { 
            ?>
              <table id="misCitas" class="table table-bordered table-hover">
                  <thead class="thead-dark">
                  <tr>
                    <th></th>
					          <th>Procedimiento</th>
                    <th style="text-align: center;">Monto(S/.)</th>
                    <th style="text-align: center;">Descuento(%)</th>
                    <th style="text-align: center;">Descuento(S/)</th>
                    <th>Precio</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php

                    foreach ($resumen->result() as $clave => $row) {
                      if($row->activo_nc)
                      {
                        $montoTotal = $montoTotal + $row->precio - $row->precio*$row->descuento_porcentaje/100;
                      }
                      
                  ?>
                      <tr>
                        <td>
                          <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="marca<?php echo $clave;?>" onclick="marcarProcedimiento('<?php echo $row->codigo_asignacion;?>', '<?php echo $this->uri->segment(3);?>', this.checked)" value="1" <?php echo $row->activo_nc ? "checked" : "";?>  <?php echo (!empty($resultadosNcredito["id"]) and $idUsuario == 1 and empty($resultadosNcredito["tipo"])) ? "" : "disabled"; ?> >
                          </div>
                        </td>
                        <td><?php echo $row->descripcion;?></td>
                        <td align="center" style="color:blue; width:19%;">
                            <input type="number" name="monto" maxlength="100" minlength="0" step="1" style="text-align: right; width: 50%;" title="Modificar Monto" min="0" value="<?php echo $row->precio ?? '0';?>"  readonly>
                             
                            
                            <input type="hidden" name="montoOrigen" value="<?php echo $row->precio;?>">
                            <input type="hidden" name="tipo" value="<?php echo $row->tipo;?>">
                            <input type="hidden" name="code" value="<?php echo $row->codigo_asignacion;?>">
                            <input type="hidden" name="idRegistro" value="<?php echo $row->id;?>">
                            <input type="hidden" name="idUsuario" value="<?php echo $row->idUsuario;?>">
                            <input type="hidden" name="fechaExamen" value="<?php echo $row->fechaExamen;?>">
                        </td>
                        
                        <td align="center">
                           
                            <input type="number" name="descuento" maxlength="100" minlength="0" step="1" style="text-align: right; width: 50%; background-color: <?php echo $row->descuento_porcentaje > 0 ? '#ffa6a6;' : '';?>" title="Agregar Descuento" min="0" value="<?php echo $row->descuento_porcentaje ?? '0';?>"  readonly>
                            
                            
                            <input type="hidden" name="monto" value="<?php echo $row->precio;?>">
                            <input type="hidden" name="tipo" value="<?php echo $row->tipo;?>">
                            <input type="hidden" name="code" value="<?php echo $row->codigo_asignacion;?>">
                            <input type="hidden" name="codeid" value="<?php echo $row->id;?>">
                        </td>
                        <td align="right"><?php echo $row->precio*$row->descuento_porcentaje/100;?></td>
                        <td align="center">
                          <?php echo $row->precio - $row->precio*$row->descuento_porcentaje/100;?>
                        </td>
                      
                       
                      </tr>
                  <?php } ?>
                    <tr>
                      <td align="right" colspan="5"><strong>TOTAL(S/.)</strong></td>
                      <td align="center"><strong><?php echo number_format($montoTotal, 2);?></strong></td>
                    </tr>
                  </tbody>
              </table>
              <?php } ?>
          </div>
        </div>
        
     
        
       
        <div class="row">
          <div class="col-sm">
              <table id="misCitas" class="table table-bordered table-hover">
                  <thead class="thead-dark">
                  <tr>
                    <th>Tipo de Pago</th>
                    <th colspan="2">Comprobante</th>
                  </tr>
                  </thead>
                  <tbody>
                    <tr>

                      <td>
                        <table border="0" width="100%" id="lista_recetas">
                          <thead>
                            <tr>
                              <td>Tipo</td>
                              <td>Monto</td>
                            </tr>
                          </thead>
                          <tbody>
                                <?php
                                    foreach ($resultadosGpago as $row) {
                                        $comprobante = $row->comprobante;
                                        $numeroComprobante = $row->nro_comprobante;
                                        $observacion = $row->observacion;
                                ?>
                                <tr>
                                    <td>
                                        <select class="form-control tipoPago" name="tipoPago[]" disabled>
                                        <?php
                                            foreach ($resultadosTipoPago as $registro) {
                                        ?>
                                        <option value="<?php echo $registro->id; ?>"  <?php echo $registro->id == $row->idTipoPago ? "selected" : ""; ?>><?php echo $registro->descripcion; ?></option>
                                        <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control montoTipoPago" name="montoTipoPago[]" step="any" min="0" value="<?php echo number_format($row->monto_pago, 2);?>" style="text-align: right;" readonly>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                      </table>
                    
                    </td>

                      
                      <td>
                        <table width="100%" border="0">
                          <tr>

                         <td>
                        <select class="form-control" id="tipoComprobante" name="tipoComprobante" disabled>
                          <option value="BOL" <?php echo $comprobante == "BOL" ? "selected" : ""; ?>>BOLETA</option>
                          <option value="FAC" <?php echo $comprobante == "FAC" ? "selected" : ""; ?>>FACTURA</option>
                        </select>
                      </td>
                      <td>
                        <input type="text" class="form-control" id="numeroComprobante" name="numeroComprobante" readonly value="<?php echo $numeroComprobante; ?>">
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <textarea name="observacion" cols="2" rows="2" class="form-control" placeholder="Observaciones" readonly><?php echo $observacion; ?></textarea>
                      </td>
                    </tr>
                    </table>


                    </td>
                  
                  </tbody>
              </table>
          </div>
        </div>
        <form id="frmGrabar" action="<?php echo base_url('payment-management/save-credit-note');?>" method="POST">

        <div class="row">
          <div class="col">
            <div class="form-group">
                <label for="nroNcredito">Nota de Crédito</label>
                <input type="text" class="form-control" name="nroNcredito" required placeholder="Nro nota de crédito" value="<?php echo $resultadosNcredito["numero"]?>"  <?php echo $resultadosNcredito["tipo"] != "" ? "disabled" : ""; ?>>
            </div>
            </div>
            <?php if($idUsuario == 1 ) { ?>                                
            <div class="col">
            <div class="form-group">
                <label for="nroNcredito">Motivo Nota de Crédito</label>
                <select name="tipoEA" id="" class="form-control" required  <?php echo $resultadosNcredito["tipo"] != "" ? "disabled" : ""; ?>>  
                  <option value="">Seleccionar</option>
                  <option value="EXT" <?php echo $resultadosNcredito["tipo"] == "EXT" ? "selected" : ""; ?> >Extorno</option>
                  <option value="ANU" <?php echo $resultadosNcredito["tipo"] == "ANU" ? "selected" : ""; ?> >Anulación</option>
                  <option value="EDE" <?php echo $resultadosNcredito["tipo"] == "EDE" ? "selected" : ""; ?> >Error de descripción</option>
                </select>
            </div>
            </div>
            <div class="col">
            <div class="form-group">
                <label for="nroNcredito">Código</label>
                <input type="password" class="form-control" name="codigo" required placeholder="Código" <?php echo $resultadosNcredito["tipo"] != "" ? "disabled" : ""; ?> >
            </div>
          </div>
          <?php } ?>

        </div>

        <div class="row mb-2">
            <?php if(empty($resultadosNcredito["id"]) and $idUsuario == 190) {  ?>
          <div class="col">
            <button type="submit" class="btn btn-primary btn-lg btn-block">GUARDAR NOTA CREDITO</button>
            <input type="hidden" name="codeInterno" value="<?php echo $this->uri->segment(3);?>">
          </div>
          <?php } ?>

          <?php if(!empty($resultadosNcredito["id"]) and $idUsuario == 1 and empty($resultadosNcredito["tipo"])) {  ?>
          <div class="col">
            <button type="submit" class="btn btn-primary btn-lg btn-block">GUARDAR</button>
            <input type="hidden" name="codeInterno" value="<?php echo $this->uri->segment(3);?>">
            <input type="hidden" name="idNcredito" value="<?php echo $resultadosNcredito["id"];?>">
          </div>
          <?php } ?>

          <div class="col">
            <button type="button" class="btn btn-secondary btn-lg btn-block" onclick="window.close()">CERRAR</button>
          </div>
        </div>
        </form>
      </div>

  <?php $this->load->view("scripts"); ?>
<!-- Select2 -->
<script src="<?php echo base_url('plugins/select2/js/select2.full.min.js');?>"></script>
  <script>
    
    function marcarProcedimiento(codeAsignacion, codeInterno, valor) {

      $.ajax({
              type: 'POST',
              url: '<?php echo base_url('payment-management/update-product');?>',
              data: { codeAsignacion: codeAsignacion, codeInterno: codeInterno, valor: valor} ,
              success: function(data) {
                if (data.status) {
                  location.reload();
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
                Swal.fire({
                  icon: 'error',
                  timer: 5000,
                  title: 'Error interno',
                  text: 'Ha ocurrido un error interno!'
                })
              }
            });
    }

    function grabar_monto(dato, id) {
      if(confirm("¡Presiona un botón para grabar!. Aceptar o Cancelar.")) {
      var frm = $('#frmPrecio' + id);
 
      $.ajax({
              type: 'POST',
              url: frm.attr('action'),
              data: frm.serialize(),
              success: function(data) {
                if (data.status) {
                  location.reload();
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
                Swal.fire({
                  icon: 'error',
                  timer: 5000,
                  title: 'Error interno',
                  text: 'Ha ocurrido un error interno!'
                })
              }
            });
      }
    }


    function grabar_descuento(dato, id) {
      if(confirm("¡Presiona un botón!. Aceptar o Cancelar.")) {
      var frm = $('#frmDescuento' + id);
 
      $.ajax({
              type: 'POST',
              url: frm.attr('action'),
              data: frm.serialize(),
              success: function(data) {
                if (data.status) {
                  location.reload();
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
                Swal.fire({
                  icon: 'error',
                  timer: 5000,
                  title: 'Error interno',
                  text: 'Ha ocurrido un error interno!'
                })
              }
            });
      }
    }

    function marcar_cita(id, value) {
      var marca = 0;
      if(value) marca = 1;

      $.ajax({                        
          type: "POST",                 
          url: "<?php echo base_url('update-option-print'); ?>",                     
          data: { id: id, marca: marca}, 
          success: function(data)             
          {
            //alert('Actualizado');
          }
      });
    }

    $("#ckbMonto").click(function() {
      if (this.checked) {
        $("#montoCita").prop('disabled', false);
      } else {
        $("#montoCita").prop('disabled', true);
      }
    });

    $('.tipoPago').on('change', function() {
      if(this.value == '2')
      {
        $('#tipoComprobante').removeAttr("required");
        $('#numeroComprobante').removeAttr("required");
        $('.montoTipoPago').val('0');
        $('#numeroComprobante').val('');
        $('#tipoComprobante').val("");
          
      } else {
        $('#tipoComprobante').prop("required", true);
        $('#numeroComprobante').prop("required", true);
        $('.montoTipoPago').val($('#montoCobrar').val());
      }
    });

  
    
    var frm = $('#frmGrabar');
    $.validator.setDefaults({
      submitHandler: function() {

        $("#btnGuardar").attr('disabled', true);
        Swal.fire({
          title: '¿ESTA SEGURO DE REGISTRARLO?',
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


    $('#frmGrabar').validate({
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




    $('.procedimientos2').select2({
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

  let montoCosto = 0;

  $('#procedimiento').on('select2:select', function (e) {
    var data = e.params.data.text.split('=');

    montoCosto = montoCosto + data[1]*1;
    $('#calculoCosto').text(montoCosto);
    
  });

  $('#procedimiento').on('select2:unselect', function (e) {
    var data = e.params.data.text.split('=');
    montoCosto = montoCosto - data[1]*1;
    $('#calculoCosto').text(montoCosto);
  });
  
  $('.searchExamen').select2({
      width: '100%',
      language: "es",
      placeholder: 'Seleccionar',
      minimumInputLength: 3,
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
        return 'Ingrese 3 o más caracteres.';
        }
      }
    });

    function print_link(tipo, user, cita, code) {
      var mywindow = window.open('<?php echo base_url('cash-management/print_add-new/') ?>' + tipo + '/' + user + '/' + cita + '/' + code + '?code=<?php echo $this->uri->segment(5); ?>', 'Imprimir', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=800,height=550,left = 390,top = 50');

    }


        //recetas
    var tbodyReceta = $('#lista_recetas tbody');
    var fila_contenidoReceta = tbodyReceta.find('tr').first().html();
    //Agregar fila nueva.
    $('#lista_recetas .button_agregar_receta').click(function(){
      var fila_nuevaReceta = $('<tr></tr>');
      fila_nuevaReceta.append(fila_contenidoReceta);
      tbodyReceta.append(fila_nuevaReceta);

      cargar_recetas();
    });

    //Eliminar fila receta.
    $('#lista_recetas').on('click', '.button_eliminar_receta', function(){
      var nFilas = $("#lista_recetas tr").length;
      
      if(nFilas > 2) $(this).parents('tr').eq(0).remove();
    });

    
  </script>
</body>

</html>