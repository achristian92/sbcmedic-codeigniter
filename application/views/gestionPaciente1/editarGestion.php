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
  <title>SBCMedic | EDITAR PACIENTE</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php $this->load->view("styles"); ?>
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="<?php echo  base_url('plugins/icheck-bootstrap/icheck-bootstrap.min.css'); ?>">

</head>

<body style="background: #a8ff78;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #78ffd6, #a8ff78);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #78ffd6, #a8ff78); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
">
  <br>
  <div class="container">
    <form method="POST" action="<?php echo base_url('actualizarGestionPaciente'); ?>" id="frmPaciente">
      <div class="row bg-black">
        <div class="col-sm text-center" style=" justify-content: center;align-items: center;">
          <h2>REGISTRO DE INFORMACIÓN DEL PACIENTE</h2>
        </div>
      </div>


      <div class="row">
        <div class="col-sm">
          <div class="form-group">
            <label for="fecha">Quién Solícito</label>
            <input type="text" name="qsolicito" class="form-control" value="<?php echo $quienSolicito;?>" requerid>
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="hora">Email</label>
            <input type="email" name="emalqs" class="form-control" value="<?php echo $email;?>" required>
          </div>
        </div>
      </div>

 



     <div class="row">
        <div class="col-sm">
          <div class="table-responsive">
            <table id="lista_examenAux" width="100%" class="table" cellspacing="1" cellpadding="1">
              <thead>
                <tr>
                  <td><strong>Nombres</strong></td>
                  <td><strong>Apellidos</strong></td>
                  <td><strong>DNI/C.Extranjería</strong></td>
                  <td><strong>Email</strong></td>
                  <td><strong>Passaporte</strong></td>
                  <td><strong>FechaNacimiento</strong></td>
                  <td><strong>PCR</strong></td>
                  <td><strong>Envíado</strong></td>
                  <td><button type="button" class="btn btn-outline-success button_agregar_examenAux" title="Agregar registro" id="agregarFila"> <i class="fas fa-plus"></i></button></td>
                </tr>
              </thead>
              <tbody>
                  <?php foreach ($registros_gpc as $clave => $gpc) { ?>
                    <tr>
                      <td><input name="nombre[]" class="form-control" value="<?php echo $gpc->nombre;?>"></td>
                      <td><input name="apellido[]" class="form-control" value="<?php echo $gpc->apellido;?>"></td>
                      <td width="11%"><input name="dni[]" maxlength="13" class="form-control" value="<?php echo $gpc->dni;?>"></td>
                      <td><input type="email" name="email[]" class="form-control" value="<?php echo $gpc->email;?>"></td>
                      <td width="11%"><input name="pasaporte[]" class="form-control" value="<?php echo $gpc->pasaporte;?>"></td>
                      <td width="12%"><input type="date" name="fechaNacimiento[]" value="<?php echo $gpc->fechaNacimiento;?>" class="form-control" requerid></td>

                       <td> 
                        <div class="custom-control custom-checkbox">
                          <div class="icheck-primary d-inline">
                            <input type="checkbox" name="pcr[]" id="pcr<?php echo $clave;?>" value="<?php echo $gpc->tipo_prueba;?>" <?php if($gpc->tipo_prueba == 2) echo "checked";?>  onClick="marcarCkeckPcr('<?php echo $clave;?>')">
                            <input type="hidden" name="pcrs[]" id="pcrs<?php echo $clave;?>" value="<?php echo $gpc->tipo_prueba;?>">
                            <label for="pcr<?php echo $clave;?>"></label>
                          </div>
                        </div>
 
                      </td>  
                      <td> 
                        <div class="custom-control custom-checkbox">
                          <div class="icheck-primary d-inline">
                            <input type="checkbox" name="consultaMedica[]" id="consultaMedica<?php echo $clave;?>" value="<?php echo $gpc->resultado;?>" <?php if($gpc->resultado == 1 || $gpc->resultado == 2) echo "checked";?>  onClick="marcarCkeckEnviado('<?php echo $clave;?>')">
                            <input type="hidden" name="consultaMedicas[]" id="consultaMedicas<?php echo $clave;?>" value="<?php echo $gpc->resultado;?>">
                            <label for="consultaMedica<?php echo $clave;?>"></label>
                          </div>
                        </div>
 
                      </td>
                      <td></td>
                    </tr>
                  <?php } ?>

              </tbody>
            </table>

          </div>

        </div>
      </div>






      <div class="row">
      <div class="col-sm">
          <div class="form-group">
            <label for="telefono">Sede</label>
            <select class="form-control" name="sede">
              <option value="SBC" <?php echo ($sede == "SBC")? "selected": "";?>>SBCmedic</option>
              <option value="DOM" <?php echo ($sede == "DOM")? "selected": "";?>>Domiciliaria</option>
              <option value=""  <?php echo ($sede == "")? "selected": "";?>>Sede</option>
            </select>
          </div>
        </div>
        
        <div class="col-sm">
          <div class="form-group">
            <label for="telefono">Télefono</label>
            <input type="text" name="telefono" class="form-control" id="telefono" value="<?php echo $telefono;?>">
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="distrito">Distrito </label>
            <select class="form-control select2" name="distrito" style="width: 100%;" id="distrito">
              <?php
              foreach ($distritos as $distrito) {
                $selected = '';
                if ($distrito->id == $idDistrito){
                    $selected = 'selected';
                }

                echo "<option value='$distrito->id' ".$selected." >$distrito->nombre</option>";
              }
              ?>
            </select>

          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" name="direccion" class="form-control" id="direccion" value="<?php echo $direccion;?>">
          </div>
        </div>
      </div>


      <div class="row">
        <div class="col-sm">
          <div class="form-group">
            <label for="fecha">Fecha de Toma</label>
            <input type="date" name="fecha" class="form-control" id="fecha" value="<?php echo $fecha;?>" requerid>
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="hora">Hora</label>
            <input type="time" id="hora" name="hora" class="form-control" value="<?php echo $hora;?>" required>
          </div>
        </div>
      </div>

      <div class="row">
                <div class="col-12 form-group">
                  <div class="table-responsive">
                    <table  id="lista_recetas" width="100%" class="table">
                      <thead>
                        <tr>
                          <td style="color:#28a745;width: 40%;"><strong>Tipo de Prueba</strong></td>
                          <td style="color:#28a745; width: 20%;"><strong>Cantidad</strong></td>
                          <td style="color:#28a745;" colspan="2"><strong>Precio</strong></td>
                        </tr>
                      </thead>
                      <tbody>
                     
                        <?php foreach ($registros_pruebas->result() as $row) { ?>
                          <tr>
                              <td>                                
                                <select name="pruebas[]" class="form-control">
                                <?php if ($row->tipo == 1) { ?>
                                  <option value="1" >Prueba ANTIGENO</option>
                                  <?php } else { ?>
                                  <option value="2">Prueba PCR</option>
                                  <?php } ?>
                                </select>
                              </td>
                              <td><div class="form-group"><input type="number" name="cantidad[]" class="form-control" min="1" style="text-align: right;" value="<?php echo $row->cantidad; ?>"></div></td>
                              <td><div class="form-group"><input type="number" name="precio[]" class="form-control" style="text-align: right;" onblur="costoTotal()" value="<?php echo $row->precio; ?>" step="any" min="0"></div></td>
                              <td><button type="button" class="btn btn-outline-danger button_eliminar_receta" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button>
                                <input type="hidden" name="idUnico[]"  value="<?php echo $row->id; ?>">
                              </td>
                          </tr>
                        <?php 
                              }
                          if($registros_pruebas->num_rows() < 2 ){ 
                        ?>
                            <tr>
                              <td>                                
                                <select name="pruebas[]" class="form-control">
                                  <option value="">Seleccionar</option>
                                  <option value="1">Prueba ANTIGENO</option>
                                  <option value="2">Prueba PCR</option>
                                </select>
                              </td>
                              <td><div class="form-group"><input type="number" name="cantidad[]" class="form-control" min="1" style="text-align: right;"></div></td>
                              <td colspan="2"><div class="form-group"><input type="number" name="precio[]" class="form-control" style="text-align: right;" onblur="costoTotal()" step="any" min="0"></div>
                              <input type="hidden" name="idUnico[]"  value="0">
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


      <div class="row">
        <div class="col-sm">
          <div class="form-group">
            <label for="transporte">Transporte</label>
            <select class="form-control" id="transporte" name="transporte">
              <option value="">Seleccionar</option>
              <option value="1" <?php if($transporte == "1") echo "selected";?> >Aplicar costo movilidad</option>
            </select>
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="cTransporte">Costo Transporte</label>
            <input type="number" name="cTransporte" class="form-control" id="cTransporte" style=" background-color:#f2cc8f; font-weight: bold;text-align: right;" value="<?php echo $costo_transporte;?>" <?php if($costo_transporte  >0) echo ""; else echo "disabled"; ?> min="0" step="any">
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm">
          <div class="form-group">
            <label for="porcentajeD">Monto de descuento(S/.)</label>
            <input type="number" id="porcentajeD" name="porcentajeD" class="form-control" value="<?php echo $porcentajeDescuento;?>" min="0" step="any" style="text-align: right;">
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="tCosto">Total Precio(S/.)</label>
            <input type="text" name="tCosto" class="form-control" id="tCosto" style=" background-color:#03A9F4;color:#FFF; font-weight: bold;text-align: right;" readonly value="<?php echo substr($totalCosto, 0, 6);?>">
          </div>
        </div>
      </div>


      <div class="row">
        <div class="col-sm">
          <div class="form-group">
            <label for="tipoBanco">Medio de Pago</label>
            <select class="form-control" name="tipoBanco">
              <option value="BCP" <?php if($tipo_banco == "BCP") echo "selected";?> >Banco de Crédito</option>
              <option value="BBVA" <?php if($tipo_banco == "BBVA") echo "selected";?> >BBVA Continental</option>
              <option value="SCOTBANK" <?php if($tipo_banco == "SCOTBANK") echo "selected";?> >Scotiabank</option>
              <option value="INTERB" <?php if($tipo_banco == "INTERB") echo "selected";?> >Interbank</option>
			  <option value="EFEC" <?php if($tipo_banco == "EFEC") echo "selected";?> >Efectivo</option>
            </select>
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="motivo">Motivo</label>
            <input type="text" name="motivo" class="form-control" id="motivo" value="<?php echo $motivo;?>">
          </div>
        </div>
      </div>

      <div class="row">

        <div class="col-12">
          <button type="submit" name="btnRegistrarDatos" id="btnRegistrarDatos"  class="btn btn-success btn-block">ACTUALIZAR DATOS</button>
          <input type="hidden" name="codigoGestion" value="<?php echo $this->uri->segment(2);?>">
        </div>
      </div>

    </form>



    <br>


    <?php $this->load->view("scripts"); ?>
    <!-- Select2 -->
    <script src="<?php echo base_url('plugins/select2/js/select2.full.min.js'); ?>"></script>
    <script>

                //recetas
    var tbodyReceta = $('#lista_recetas tbody');
    var fila_contenidoReceta = tbodyReceta.find('tr').first().html();
    //Agregar fila nueva.
    $('#lista_recetas .button_agregar_receta').click(function(){
      var fila_nuevaReceta = $('<tr></tr>');
      fila_nuevaReceta.append(fila_contenidoReceta);
      tbodyReceta.append(fila_nuevaReceta);
    });

    //Eliminar fila receta.
    $('#lista_recetas').on('click', '.button_eliminar_receta', function(){
      var nFilas = $("#lista_recetas tr").length;
      
      if(nFilas > 2) $(this).parents('tr').eq(0).remove();
      costoTotal();
    });


      $("#agregarFila").click(function() {

        var nFilas = $("#lista_examenAux tr").length;

        $('#lista_examenAux tr:last').after(`
    <tr>
      <td><input name="nombre[]" class="form-control"></td>
      <td><input name="apellido[]" class="form-control"></td>
      <td width="11%"><input name="dni[]" maxlength="13" class="form-control"></td>
      <td><input type="email" name="email[]" class="form-control"></td>
      <td width="11%"><input name="pasaporte[]" class="form-control"></td>
      <td width="12%"><input type="date" name="fechaNacimiento[]" class="form-control" value="<?php echo date("Y-m-d"); ?>" requerid></td>

      <td>
        <div class="custom-control custom-checkbox">
          <div class="icheck-primary d-inline">
            <input type="checkbox" class="form-check-input" name="pcr[]"  id="pcr` + nFilas + `" onClick="marcarCkeckPcr('` + nFilas + `')" value="2">
            <input type="hidden" name="pcrs[]" class="form-control" id="pcrs` + nFilas + `" value="1">
            <label for="pcr` + nFilas + `"></label>
          </div>
        </div>
     </td>

      <td>
     
        <div class="custom-control custom-checkbox">
          <div class="icheck-primary d-inline">
            <input type="checkbox" class="form-check-input" name="consultaMedica[]"  id="consultaMedica` + nFilas + `" onClick="marcarCkeckEnviado('` + nFilas + `')" value="1">
            <input type="hidden" name="consultaMedicas[]" class="form-control" id="consultaMedicas` + nFilas + `" value="0">
            <label for="consultaMedica` + nFilas + `"></label>
          </div>
        </div>
        </td>
      <td><button type="button" class="btn btn-outline-danger button_eliminar_examenAux" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
  </tr>`);




      });

      //Eliminar fila.
      $('#lista_examenAux').on('click', '.button_eliminar_examenAux', function() {
        var nFilas = $("#lista_examenAux tr").length;

        if (nFilas > 2) $(this).parents('tr').eq(0).remove();
      });



      function marcarCkeckEnviado(id) {
        if ($('#consultaMedica' + id).prop("checked") == true) {
          $('#consultaMedicas' + id).val(1);
        } else {
          $('#consultaMedicas' + id).val(0);
        }
      }

      function marcarCkeckPcr(id) {
        if ($('#pcr' + id).prop("checked") == true) {
          $('#pcrs' + id).val(2);
          
        } else {
          $('#pcrs' + id).val(1);
        }
      }


      $('#distrito').select2();


      function costoTotal() {
        var precio = 0;
        $("input[name='precio[]']").each(function(indice, elemento) {

          precio = precio*1 + $(elemento).val()*1;
          
      });

        var cTransporte = parseFloat($("#cTransporte").val());
        var porcentajeD = parseFloat($("#porcentajeD").val());
        var total, calculoP = 0;

 
        if (isNaN(cTransporte)) cTransporte = 0;
        if (isNaN(porcentajeD)) porcentajeD = 0;
 
        total = (precio + cTransporte - porcentajeD).toFixed(2);

        $("#tCosto").val(total);
      }

	  $("#quantity").on('keyup change click', function () {
		  var cantidad = $("#quantity").val();
		  
		  if(cantidad >0 && $("#tipoAntigeo").val() !=''){
			$("#cPrueba").attr('disabled', false);

			if (cantidad == '1') {
			  $("#cPrueba").val('99');
			} else if (cantidad == '2') {
			  $("#cPrueba").val('198');
			} else if (cantidad == '3') {
			  $("#cPrueba").val('297');
			} else if (cantidad == '4') {
			  $("#cPrueba").val('396');
			} else if (cantidad == '5') {
			  $("#cPrueba").val('495');
			} else {
			  $("#cPrueba").val('');
			}

		  } else {
			$("#cPrueba").val('');
			$("#cPrueba").attr('disabled', true);
		  }

		  costoTotal();
		});

    $("#quantityPsr").on('keyup change click', function () {
      var cantidadPsr = $("#quantityPsr").val();
      
      if(cantidadPsr >0 && $("#tipoPsr").val() !=''){
        $("#cPruebaPsr").attr('disabled', false);

        if (cantidadPsr == '1') {
          $("#cPruebaPsr").val('220');
        } else if (cantidadPsr == '2') {
          $("#cPruebaPsr").val('440');
        } else if (cantidadPsr == '3') {
          $("#cPruebaPsr").val('600');
        } else if (cantidadPsr == '4') {
          $("#cPruebaPsr").val('800');
        } else if (cantidadPsr == '5') {
          $("#cPruebaPsr").val('1000');
        } else {
          $("#cPruebaPsr").val('');
        }

      } else {
        $("#cPruebaPsr").val('');
        $("#cPruebaPsr").attr('disabled', true);
      }

      costoTotal();
    });
		 

      $('#pPromocionales').change(function() {
        var pPromocionales = $(this).val();
        if (pPromocionales != '') {
          $("#cpromocionales").attr('disabled', false);

          if (pPromocionales == 'pacmd') {
            $("#cpromocionales").val('209');
          } else {
            $("#cpromocionales").val('239');
          }
        } else {
          $("#cpromocionales").val('');
          $("#cpromocionales").removeClass("is-invalid");
          $("#cpromocionales").attr('disabled', true);
        }

        costoTotal();
      });

      $('#transporte').change(function() {
        var transporte = $(this).val();
        if (transporte != '') {
          $("#cTransporte").attr('disabled', false);

        } else {
          $("#cTransporte").val('');
          $("#cTransporte").removeClass("is-invalid");
          $("#cTransporte").attr('disabled', true);
        }

        costoTotal();
      });

      
      $('#tipoAntigeo').change(function(e) {

        if(this.value == ''){
          $("#quantity").val('0');
          $("#cPrueba").val('0');
        }
        costoTotal();
      });

      $('#tipoPsr').change(function(e) {
        if(this.value == ''){
          $("#quantityPsr").val('0');
          $("#cPruebaPsr").val('0');
        }
        costoTotal();
      });

      $('#porcentajeD').change(function() {
        costoTotal();
      });

      $('#cPrueba').change(function() {
        costoTotal();
      });

      $('#cpromocionales').change(function() {
        costoTotal();
      });

      $('#cTransporte').change(function() {
        costoTotal();
      });

      var frm = $('#frmPaciente');
      $.validator.setDefaults({
        submitHandler: function() {
          Swal.fire({
            title: '¿Estás seguro de Actualizar este paciente?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Seguro!',
            cancelButtonText: 'Cancelar',
          }).then((result) => {

            if (result.value) {

              $("#btnRegistrarDatos").attr('disabled', true);
              $.ajax({
                type: frm.attr('method'), 
                url: frm.attr('action'),
                data: frm.serialize(),
                beforeSend: function () 
                {            
                  $("#btnRegistrarDatos").html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
                  $("#btnRegistrarDatos").addClass("btn btn-success");
                  $("#btnRegistrarDatos").prop('disabled', true);
                },
                success: function(data) {
                  if (data.status) {

                    Swal.fire({
                      icon: 'success',
                      timer: 5000,
                      title: 'Respuesta exitosa',
                      text: data.message,
                      onClose: () => {
						                          window.open("<?php echo base_url('cash-management-antigeno/print/') ?>" + data.idgestion);
                    window.close();
                        window.location.replace("<?php echo base_url('gestionResumenPaciente'); ?>");
                      }
                    })
                  } else {
                    $("#btnRegistrarDatos").attr('disabled', false);
                    Swal.fire({
                      icon: 'error',
                      timer: 5000,
                      title: 'Error de validación',
                      text: data.message
                    })
                  }
                },
                error: function(data) {
                  $("#btnRegistrarDatos").attr('disabled', false);
                  Swal.fire({
                    icon: 'error',
                    timer: 5000,
                    title: 'Error interno',
                    text: 'Ha ocurrido un error interno!'
                  })
                }
              });
            }
          });
        }
      });

      $('#frmPaciente').validate({
        rules: {
          qsolicito: {
            required: true
          },
          emalqs: {
            required: true,
            email: true
          },
          nombreApe: {
            required: true,
            minlength: 3
          },
          email: {
            required: true,
            email: true,
          },
          telefono: {
            required: true
          },
          dni: {
            required: true
          },
          fechaNacimiento: {
            required: true
          },
          direccion: {
            required: true
          },
          fecha: {
            required: true
          },
          hora: {
            required: true
          },
          cPrueba: {
            required: true,
            pattern: /^((\d+(\\.\d{0,2})?)|((\d*(\.\d{1,2}))))$/
          },
          cpromocionales: {
            required: true,
            pattern: /^((\d+(\\.\d{0,2})?)|((\d*(\.\d{1,2}))))$/
          },
          cTransporte: {
            required: true,
            pattern: /^((\d+(\\.\d{0,2})?)|((\d*(\.\d{1,2}))))$/
          },
          qsolcitop: {
            required: true
          },
          porcentajeD: {
            number: true
          },
          tCosto: {
            required: true
          }
        },
        messages: {
          qsolicito: {
            required: "Ingrese los nombres y apellidos"
          },
          tCosto: {
            required: "Es necesario un costo"
          },
          emalqs: {
            required: "Ingrese el email válido",
            email: "Por favor ingrese un email válido"
          },
          email: {
            required: "Ingrese el email válido",
            email: "Por favor ingrese un email válido"
          },
          nombreApe: {
            minlength: "Debe ingresar al menos 3 caracteres",
            required: "Ingrese los nombres y apellidos"

          },
          telefono: {
            required: "Ingrese algún teléfono"
          },
          dni: {
            required: "Ingrese el DNI"
          },
          fechaNacimiento: {
            required: "Ingrese la fecha de nacimiento"
          },
          direccion: {
            required: "Ingrese la dirección"

          },
          fecha: {
            required: "Ingrese una fecha válida"
          },
          hora: {
            required: "Ingrese una hora válida"
          },
          cPrueba: {
            required: "Ingrese el costo",
            pattern: "Formato inválido"
          },
          cpromocionales: {
            required: "Ingrese el costo",
            pattern: "Formato inválido"
          },
          cTransporte: {
            required: "Ingrese el costo de transporte",
            pattern: "Formato inválido"
          },
          qsolcitop: {
            required: "Ingrese quién solicitó la prueba"
          },
          porcentajeD: {
            digits: "Solo números enteros"
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