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
  <title>SBCMedic | GESTIÓN EXÁMENES ORION</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php $this->load->view("styles"); ?>
 
</head>

<body style="background: #a8ff78;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #78ffd6, #a8ff78);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #78ffd6, #a8ff78); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

">
  <div class="container">
    <div class="row bg-black">
      <div class="col-sm text-center" style=" justify-content: center;align-items: center;">
        <h2>GESTION DE EXÁMENES - ORION</h2>
      </div>
    </div>


    <div class="row mt-2">
      <div class="col-sm">
        <form action="<?php base_url('gestionarExamenes');?>" method="POST">
          <table id="gestion" class="table table-striped table-hover table-bordered" style="width:100%;background-color:#B2EBF2;">
            <thead>
              <tr>
                <td style="width: 13%;"><strong>NroPedido</strong></td>
                <td style="width: 15%;"><input type="text" class="form-control" name="codigoPedido"></td>
                <td style="width: 15%;" align="right"><strong>Paciente</strong></td>
                <td colspan="5"><select id="cmbUsuario" name="cmbUsuario" class="searchClient form-control select2" style="width: 100%;">
                      
                      </select></td>
                <td style="width: 15%;"><button type="submit" class="btn btn-primary"><i class="fab fa-searchengin"></i> Consultar</button></td>
              </tr>
          </table>
          <input type="hidden" name="userCode" value="" >
        </form>
          <table id="gestion" class="table table-striped table-hover table-bordered" style="width:100%;background-color:#B2EBF2;">
            <tr style="background-color: #11998e;">
              <th style="color:#FFF;" colspan="4">Paciente: 
               <h3><u><?php echo $usuario["paciente"]; ?></u></h3></th>
              <th width="20%" colspan="2" style="text-align: center;"><button type="button" class="btn btn-success btn-lg active" data-toggle="modal" data-target="#newExamen" data-usuario='<?php echo $usuario["idUsuario"]; ?>' <?php echo ($usuario["idUsuario"])?"": "disabled" ?> >Nuevo Examen</button></th>
            </tr>
            <tr>
              <th style="text-align: center;">#</th>
              <th>FECHA EXAMEN</th>
              <th>NRO PEDIDO</th>
              <th>EXAMEN</th>
              <th>STATUS</th>
              <th style="text-align: center;">VER INFORME</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $codigoI = null;
              foreach ($resultados as $clave => $resultado){
                $clave++;
            ?>
                  <tr>
                    <td align="center" style="width: 10%;"><?php echo $clave;?></td>
                    <td style="width: 15%; text-align: center;"><?php echo date("d/m/Y",strtotime($resultado["fechaExamen"])); ?></td>
                    <td style="width: 12%;"><strong><?php echo "PED-".str_pad($resultado["numeroPedido"], 5, '0', STR_PAD_LEFT);?></strong></td>
                    <td style="color:blue"><strong><?php echo $resultado["examen"];?></strong></td>
                    <td><span class="badge badge-<?php echo ($resultado["estado"] == "V")? "success": "warning";?>"><?php echo ($resultado["estado"] == "V")? "Válidado": "En Proceso";?></span></td>
                    <td align="center">
                      <?php if($resultado["estado"] == "V") { ?>
                        <a href='<?php echo base_url("verResultado/").$resultado['idOrden']?>' class="btn btn-outline-success" target="_blank"><i class="far fa-file-pdf"></i> Ver Resultado</a>
                      <?php } ?>
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
                <h4 class="modal-title text-center" id="newExamenLabel">SOLICITAR NUEVO EXAMÉN<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
           
                <div class="form-group">
                  <label for="exampleInputEmail1">Fecha del Examen</label>
                  <input type="date" name="fecha" class="form-control" value="<?php echo date("Y-m-d"); ?>" requerid>
                </div>

                <div class="form-group">
                  <label for="exampleInputEmail1">Médico Solicitante</label>
                  <select id="medicos" name="medicos" class="form-control" required>
					<option value="">SELECCIONAR</option>
                  <?php
                    foreach ($medicos as $medico) {
                  ?>
                    <option value="<?php echo $medico->idDoctor; ?>"><?php echo $medico->nombreMedico; ?></option>
                  <?php } ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="cmbExamenes">Examen</label>
                  <select id="cmbExamenes" name="cmbExamenes[]" class="form-control searchExamen" style="width: 100%;" multiple>
                  </select>
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


  <?php $this->load->view("scripts"); ?>
  <!-- Select2 -->
  <script src="<?php echo base_url('plugins/select2/js/select2.full.min.js');?>"></script>
  <script>
    var iduser = '<?php echo $idUsuario; ?>';
    $('.select2').select2();
    
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
            url: "<?php echo base_url("gNuevoExamenOrion");?>",
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
                    window.location.replace("<?php echo base_url('gestionarExamenes/');?>"+ $('#idUsuario').val());
                  }
                })
              }else{
                Swal.fire({
                  icon: 'error',
                  timer: 5000,
                  title: 'Error de validación',
                  text: 'Error: ' + data.message
                })
              }
            },
            error: function (data) {
              Swal.fire({
                icon: 'error',
                timer: 5000,
                title: 'Error interno',
                text: 'Ha ocurrido un error interno. NO SE PUEDE GUARDAR!.'  ,
                onClose: () => {
                  window.location.replace("<?php echo base_url('gestionarExamenes/');?>"+ $('#idUsuario').val());
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
          cmbProfesional: {
            required: true
          },
          cmbEspecialidad: {
            required: true
          },
          cmbExamen: {
            required: true
          }
        },
        messages: {
          cmbProfesional: {
            required: "Seleccione un profesional"
          },
          cmbEspecialidad: {
            required: "Seleccione una especialidad"
          },
          cmbExamen: {
            required: "Seleccione un examen"
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
      minimumInputLength: 3,
      maximumSelectionLength: 10,
      ajax: {
        url: '<?php echo base_url("searchExamenOrion");?>',
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

      $('#newExamen').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var idUsuario = button.data('usuario')
        
        $('#idUsuario').val(idUsuario);
      });
      

      $('#newExamen').on('hide.bs.modal', function (event) {
        
        $("#cmbExamenes").val('').trigger('change') ;
        $('.searchExamen').empty();

        $(this).find('form')[0].reset();

        $("#btnRegistrar").html('REGISTRAR EXAMEN');
        $("#btnRegistrar").removeClass("btn btn-primary btn-block ml-1");
        $("#btnRegistrar").addClass("btn btn-primary btn-block ml-1");
        $("#btnRegistrar").prop('disabled', false);
        
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
  </script>
</body>

</html>