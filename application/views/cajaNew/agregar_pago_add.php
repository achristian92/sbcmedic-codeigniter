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
            <h2>AGREGAR PAGOS - NRO CITA : <?php echo str_pad($this->uri->segment(4), 6, '0', STR_PAD_LEFT); ?></h2>
          </div>
        </div>
        <form id="frmExamen" action="<?php echo base_url('cash-management-save-new');?>">
          <div class="row mt-2">
<!--      
            <div class="col-sm">
                <label for="farmacia">Farmacia</label>
                <input type="number" class="form-control" name="farmacia" value="<?php echo $farmacia["precio"]?? '0.00' ; ?>" min="0">
                <small id="emailHelp" class="form-text text-muted">Precio Total (S/.)</small>
            </div> -->
          </div>
          <div class="row mt-2">
            <div class="col-sm">
            
                <div class="form-group">
                  <label for="Procedimiento">Procedimientos Adicionales<!--  => &nbsp;&nbsp;S./ <span style="font-size: 18px;" class="badge badge-warning" id="calculoCosto">0</span> --></label>
                  <select name="procedimiento[]" id="procedimiento" class="form-control procedimientos">
                    <option value="">Seleccionar</option>
                    <?php
                      foreach ($resultadosPro as $row) {
                    ?>
                      <option value="<?php echo $row->id;?>"><?php echo $row->descripcion;?></option>
                    <?php } ?>
                  </select>
                </div>
 
                <button type="submit" id="btnGuardar" class="btn btn-primary btn-block ml-1">REGISTRAR PROCEDIMIENTOS </button>
                <input type="hidden" value="<?php echo $this->uri->segment(3); ?>" name="usuario">
                <input type="hidden" value="<?php echo $this->uri->segment(4); ?>" name="idCita">
                <input type="hidden" value="<?php echo $this->uri->segment(5); ?>" name="code">
              
            </div>
          </div>
          </form>
        <div class="row mt-2">
          <div class="col-sm">
            <?php if($resumen->num_rows() >0 ){ ?>
              <table id="misCitas" class="table table-bordered table-hover">
                  <thead class="thead-dark">
                  <tr>
					 <th>Principal</th>
					          <th>MotivoCita</th>
					          <th>Fecha</th>
                    <th style="text-align: center;">Monto (S/.)</th>
                    <th style="text-align: center;">Descuento (S/.)</th>
                   
                    <th></th>
                    <th style="width: 20%; text-align: center;"></th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                    $montoTotal = 0;

                    foreach ($resumen->result() as $clave => $row) {
                      $montoTotal = $montoTotal + $row->precio;
                  ?>
                      <tr>
                        <td><strong><?php echo $row->descripcion;?></strong></td>
						<td><?php echo $row->procedimientoTwo;?></td>
                        <td style="width: 15%;"><?php echo date("d/m/Y H:i",strtotime($row->fecha));?></td>
                        <td align="center" style="color:blue;"><strong><?php echo number_format($row->precio, 2);?></strong></td>
                        <td align="center">
                          <form action="<?php echo base_url('cash-management-save-discount-new');?>" method="POST" id="frmDescuento<?php echo $clave;?>" onsubmit="return confirm('¿Realmente desea agregar el Descuento?');">
                            <input type="number" name="descuento" step="0.01" style="text-align: right; width: 50%; background-color: <?php echo $row->descuento > 0 ? '#ffa6a6;' : '';?>x" title="Agregar Descuento" min="0" value="<?php echo $row->descuento ?? '0';?>" <?php echo $row->concepto == "Farmacia" ? "disabled" : "" ;?>>
                            <button type="submit" <?php echo $row->concepto == "Farmacia" ? "disabled" : "" ;?> title="Aplicar descuento">Aplicar</button>
                            <input type="hidden" name="tipo" value="<?php echo $row->concepto;?>">
                            
                            <input type="hidden" name="idCita" value="<?php echo $row->idCita;?>">
                            <input type="hidden" name="usuario" value="<?php echo $row->idUsuario;?>">
                            <input type="hidden" value="<?php echo $this->uri->segment(5); ?>" name="code">
                            <input type="hidden" name="codeid" value="<?php echo $row->id;?>">
                          </form>
                        </td>
						<!--
                        <td align="center">
                          <form action="<?php echo base_url('cash-management-delete-new');?>" method="POST" id="frmEliminar<?php echo $clave;?>" onsubmit="return confirm('¿Realmente desea Eliminarlo?');">
                            <button type="submit" class="btn btn-danger" title="Eliminar registro"><i class="fas fa-minus-circle"></i></button>
                            <input type="hidden" name="tipo" value="<?php echo $row->concepto;?>">
                            <input type="hidden" name="idCita" value="<?php echo $row->idCita;?>">
                            <input type="hidden" name="usuario" value="<?php echo $row->idUsuario;?>">
                            <input type="hidden" name="procedimiento" value="<?php echo $row->codigo_procedimiento;?>">
                            <input type="hidden" value="<?php echo $this->uri->segment(5); ?>" name="code">
                            <input type="hidden" name="codeid" value="<?php echo $row->id;?>">
                          </form>
                        </td>-->
                        <td>
                          <div class="custom-control custom-checkbox">
                            <div class="icheck-primary d-inline">
                              <input type="checkbox" id="marca<?php echo $clave;?>" value="1" onclick="marcar_cita('<?php echo $row->id; ?>', this.checked)" <?php echo $row->marca == 1 ? "checked" : ""; ?> > 
                              <label for="marca<?php echo $clave;?>">
                              </label>
                            </div>
                          </div>
                        </td>
                        <td align="center"><a href="javascript:void(0)" id="imprime" onclick="print_link('<?php echo $row->concepto; ?>', '<?php echo $row->idUsuario; ?>', '<?php echo $row->idCita; ?>', '<?php echo $row->id; ?>')" title="Imprimir Examen" target="_blank"><i class="fas fa-print"></i> Imprimir</a></td>
                       
                      </tr>
                  <?php } ?>
                    <tr>
                      <td align="right" colspan="3"><strong>TOTAL(S/.)</strong></td>
                      <td align="center"><strong><?php echo number_format($montoTotal, 2);?></strong></td>
                      <td colspan="3"></td>
                    </tr>
                  </tbody>
              </table>
              <?php } ?>
          </div>
        </div>
        
      </div>

  <?php $this->load->view("scripts"); ?>
<!-- Select2 -->
<script src="<?php echo base_url('plugins/select2/js/select2.full.min.js');?>"></script>
  <script>

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

    $('select').select2();
    
    var frm = $('#frmExamen');
    $.validator.setDefaults({
      submitHandler: function() {

        $("#btnGuardar").attr('disabled', true);
        Swal.fire({
          title: '¿Esta seguro de registralo?',
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
                      //opener.location.reload();
                      location.reload();
                      //window.close();
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
    
  </script>
</body>

</html>