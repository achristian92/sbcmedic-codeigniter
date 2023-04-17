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
  <title>SBCMedic | REGISTRAR AFILIADO EXAMEN-LABORATORIO</title>
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
  <div class="container-fluid">
    <form method="POST" action="<?php echo base_url('ocupacional/guardarHClinicaAfiliado'); ?>" id="frmHistorialClinicoELab">
      <div class="row bg-black">
        <div class="col-sm text-center" style=" justify-content: center;align-items: center;">
          <h2>REGISTRO EXAMEN AUXILIAR / LABORATORIO</h2>
        </div>
      </div>
 


      <div class="row mt-2">
        <div class="col">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-4 col-form-label">AFILIADO</label>
            <div class="col-sm-8">
              <select id="afiliado" name="afiliado" class="searchAfiliado form-control select2" style="width: 100%;" required>
              </select>
            </div>
          </div>
        </div>
        </div>
 


      <div class="row bg-info">
        <div class="col">
          <h2>Exámenes Auxiliares</h2>
        </div>
      </div>



      <div class="row">
        <div class="col-sm">

          <table class="table" id="cEAuxiliar">
            <thead>
              <tr>
                <th colspan="3" style="text-align: right;"><button type="button" class="btn btn-outline-success button_agregar_cEAuxiliar" title="Agregar registro"><i class="fas fa-plus"></i> Agregar</button></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="width: 25%;">
                  <select class="form-control" name="examenesAuxi[]">
                    <option value="">Seleccionar</option>
                    <option value="EOFT">Evaluación oftalmológica</option>
                    <option value="EPSI">Evaluación Psicológica</option>
             
                  </select>
                </td>
                <td><input type="text" class="form-control" name="contenidoAudio[]"></td>
                <td><button type="button" class="btn btn-outline-danger button_eliminar_cEAuxiliar" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
              </tr>

            </tbody>
          </table>

        </div>
      </div>


      <div class="row bg-info">
        <div class="col">
          <h2>Exámenes Laboratorio</h2>
        </div>
      </div>



      <div class="row">
        <div class="col-sm">

          <table class="table" id="cLaboratorio">
            <thead>
              <tr>
                <th colspan="3" style="text-align: right;"><button type="button" class="btn btn-outline-success button_agregar_cLaboratorio" title="Agregar registro"><i class="fas fa-plus"></i> Agregar</button></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="width: 35%;">
                  <select name="examenesLab[]" class="form-control searchExamen"></select>
                </td>
                <td><textarea name="comentarioExamen[]" class="form-control" cols="3" rows="3"></textarea></td>
                <td style="width: 15%;"><button type="button" class="btn btn-outline-danger button_eliminar_cLaboratorio" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
              </tr>

            </tbody>
          </table>

        </div>
      </div>

 

      <div class="row">

        <div class="col-12">
          <button type="submit" name="btnRegistrarDatos" id="btnRegistrarDatos" class="btn btn-success btn-block">INGRESAR EXAMEN / LABORATORIO</button>
        </div>
      </div>

    </form>



    <br>


    <?php $this->load->view("scripts"); ?>
    <!-- Select2 -->
    <script src="<?php echo base_url('plugins/select2/js/select2.full.min.js'); ?>"></script>
    <script>
 
    var frm = $('#frmHistorialClinicoELab');
    $.validator.setDefaults({
      submitHandler: function () {
        
        Swal.fire({
        title: '¿ESTÁS SEGURO DE INGRESAR ESTA INFORMACIÓN?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Seguro!',
        cancelButtonText: 'Cancelar',
      }).then((result) => {
        
        if (result.value) {

        $.ajax({
          type: frm.attr('method'),
          url: frm.attr('action'),
          data: frm.serialize(),
          beforeSend: function () 
          {            
            $("#btnSubir").html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
            $("#btnSubir").removeClass("btn btn-success");
            $("#btnSubir").addClass("btn btn-success");
            $("#btnSubir").prop('disabled', true);
          },
          success: function (data) {
            if(data.status){  
              
              Swal.fire({
                icon: 'success',
                timer: 5000,
                title: 'Respuesta exitosa',
                text: data.message,
                onClose: () => {
                  window.location.replace("<?php echo base_url('ocupacional/registroDatos');?>");
                }
              })
            }else{
              //$("#btnSubir").attr('disabled',false);
              Swal.fire({
                icon: 'error',
                timer: 5000,
                title: 'Error de validación',
                text: data.message
              })
            }
          },
          error: function (data) {
            //$("#btnSubir").attr('disabled',false);
            Swal.fire({
              icon: 'error',
              timer: 5000,
              title: 'Error interno',
              text: 'Ha ocurrido un error interno!'
            })
          },
      }); 

    }
      }); 

      }
    });
    
    $('#frmHistorialClinicoELab').validate({
        rules: {
          afiliado: {
            required: true
          },
          cliente: {
            required: true
          }
        },
        messages: {
          afiliado: {
            required: "Seleccione el afiliado"
          },
          cliente: {
            required: "Seleccione al paciente"
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

      //ANTECEDENTES OCUPACIONALES
      var tbody = $('#cEAuxiliar tbody');
      var fila_contenido = tbody.find('tr').first().html();
      //Agregar fila nueva.
      $('#cEAuxiliar .button_agregar_cEAuxiliar').click(function(){
    
        var fila_nueva = $('<tr></tr>');
        fila_nueva.append(fila_contenido);
        tbody.append(fila_nueva);

      });
 
      //Eliminar fila.
      $('#cEAuxiliar').on('click', '.button_eliminar_cEAuxiliar', function(){
        var nFilas = $("#cEAuxiliar tr").length;

        if(nFilas > 2) $(this).parents('tr').eq(0).remove();
      });


      //Diagnóstico Médico Ocupacional | CIE 
      var tbodycieDmOcupa = $('#cLaboratorio tbody');
      var fila_contenidoCieDmoucpa= tbodycieDmOcupa.find('tr').first().html();
      //Agregar fila nueva.
      $('#cLaboratorio .button_agregar_cLaboratorio').click(function(){
    
        var fila_nueva = $('<tr></tr>');
        fila_nueva.append(fila_contenidoCieDmoucpa);
        tbodycieDmOcupa.append(fila_nueva);

        initializeSelect2();

      });

      //Eliminar fila.
      $('#cLaboratorio').on('click', '.button_eliminar_cLaboratorio', function(){
        var nFilas = $("#cLaboratorio tr").length;

        if(nFilas > 2) $(this).parents('tr').eq(0).remove();
      });

      

    initializeSelect2();

    

    function initializeSelect2() {

      $('.searchExamen').select2({
      width: '100%',
      language: "es",
      placeholder: 'Seleccionar',
      minimumInputLength: 3,
      maximumSelectionLength: 10,
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
    }
    

 
    $('.searchAfiliado').select2({
    language: "es",
    placeholder: 'BUSCAR AFILIADO',
    minimumInputLength: 3,
    maximumSelectionLength: 10,
    ajax: {
      url: '<?php echo base_url("ocupacional/searchAffiliate");?>',
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
      

 

/*    
  $('select[name="osistemaEmedicas[]"]').on('change', function() {

    alert(this.value);
    if(this.value == '16')
    {
      $('#textarea').hide();
      $('#ojosAnexos').show();
    } else {
      $('#textarea').show();
      $('#ojosAnexos').hide();
    }

}); */


    </script>
</body>

</html>