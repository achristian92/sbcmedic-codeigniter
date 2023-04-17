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
  <title>SBCMedic | REGISTRAR EMPRESA</title>
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
    <form method="POST" name="frmEmpresa" id="frmEmpresa">
      <div class="row bg-black">
        <div class="col-sm text-center" style=" justify-content: center;align-items: center;">
          <h2>REGISTRO EMPRESA - CLÍNICA OCUPACIONAL </h2>
        </div>
      </div>

        
      <div class="row">
        <div class="col-sm">
          <div class="form-group">
            <label for="cmbTipoE">RAZÓN SOCIAL</label>
            <input type="text" class="form-control" name="razonSocial" id="razonSocial">
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="cmbPuesto">ACTIVIDAD ECONÓMICA</label>
            <input type="text" class="form-control" name="actividadEconomica">
          </div>
        </div>
        
        <div class="col-sm">
          <div class="form-group">
            <label for="cmbPuesto">LUGAR DE TRABAJO</label>
            <input type="text" class="form-control" name="lugarTrabajo">
          </div>
        </div>
        
      </div>

      <div class="row">
        <div class="col-sm">
          <div class="form-group">
            <label for="cmbDepartamento">DEPARTAMENTO</label>
            <select name="cmbDepartamento" id="cmbDepartamento" class="form-control select2" style="width: 100%;" required>
              <option value="">SELECCIONE</option>
              <?php foreach ($departamentos as $departamento) { ?>
                <option value="<?=$departamento->id;?>"><?=$departamento->name;?></option>                    
              <?php } ?>
            </select> 
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="cmbProvincia">PROVINCIA</label>
            <select  name="cmbProvincia" id="cmbProvincia" class="form-control select2" style="width: 100%;" required>
              <option value="">SELECCIONE</option>
            </select>
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="hora">DISTRITO</label>
            <select name="cmbDistrito" id="cmbDistrito" class="form-control select2" style="width: 100%;" required>
              <option value="">SELECCIONE</option>
            </select>
          </div>
        </div>
      </div>
 



      <div class="row">

        <div class="col-12">
          <button type="submit" name="btnRegistrarDatos" id="btnRegistrarDatos"  class="btn btn-success  btn-lg active btn-block">REGISTRAR EMPRESA</button>
        </div>
      </div>

    </form>

 
    <div class="row mt-2">
          <div class="col">
            
              <table class="table table-hover">
                <thead>
                  <tr class="table-active">
                    <th>#</th>
                    <th scope="col">Razón Social</th>
                    <th scope="col">Actividad Económica</th>
                    <th scope="col">Lugar de Trabajo</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($empresas as $key => $empresa) {
                      $key++;
                  ?>
                    <tr>
                      <td><?php echo $key;?></td>
                      <td><?php echo $empresa->razonSocial;?></td>
                      <td><?php echo $empresa->actividadEconomica;?></td>
                      <td><?php echo $empresa->lugarTrabajo;?></td>
                      <td>
                        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#editarEmpresa" data-idempresa='<?php echo $empresa->id;?>' data-iddistrito='<?php echo $empresa->idUbicacion;?>'>EDITAR EMPRESA</button>
                      </td>
                    </tr>
                  <?php
                    }
                  ?>
                </tbody>
              </table>
            </div>
        </div>

        <!-- Modal edit Empresa-->
        <div class="modal fade" id="editarEmpresa" tabindex="-1">
          <form name="frmEmpresaEdit" id="frmEmpresaEdit">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header d-block">
                <h4 class="modal-title text-center" id="newExamenLabel">EDITAR EMPRESA<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
 
                <div class="form-group row">
                  <label for="hematocrito" class="col-sm-4 col-form-label">Razón Social</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="razonSocialEdit" id="razonSocialEdit">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="hemoglobina" class="col-sm-4 col-form-label">Actividad Económica</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="aEconomicaEdit" id="aEconomicaEdit">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-4 col-form-label">Lugar Trabajo</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="lTrabajoEdit" id="lTrabajoEdit">
                  </div>
                </div>
                 
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-4 col-form-label">Departamento</label>
                  <div class="col-sm-8">
                    <select id="cmbDepartamentoEdit" class="form-control select2" name="cmbDepartamentoEdit" style="width: 100%;">
                      <option value="">--Seleccione--</option>
                      <?php foreach ($departamentos as $departamento) { ?>
                        <option value="<?=$departamento->id;?>"><?=$departamento->name;?></option>                    
                      <?php } ?>
                    </select> 
                  </div>
                </div>
                 
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-4 col-form-label">Provincia</label>
                  <div class="col-sm-8">
                    <select id="cmbProvinciaEdit" class="form-control select2" name="cmbProvinciaEdit" style="width: 100%;">
                      <option value="">--Seleccione--</option>
                    </select>
                  </div>
                </div>
                 
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-4 col-form-label">Distrito</label>
                  <div class="col-sm-8">
                  <select id="cmbDistritoEdit" class="form-control select2" name="cmbDistritoEdit" style="width: 100%;">
                      <option value="">--Seleccione--</option>
                    </select>
                  </div>
                </div>
                 
      
                
              </div>
              <div class="modal-footer">
                <button type="submit" id="btnRegistrarHema" class="btn btn-primary btn-block ml-1">ACTUALIZAR EMPRESA</button>
                <input type="hidden" name="idEmpresa" id="idEmpresa">
              </div>
            </div>
          </div>
          </form>
        </div>
        <!-- /.Modal -->

    <?php $this->load->view("scripts"); ?>
    <!-- Select2 -->
    <script src="<?php echo base_url('plugins/select2/js/select2.full.min.js'); ?>"></script>
    <script>
      $('.select2').select2();
    
      
  
      var frm = $('#frmEmpresa');
      $.validator.setDefaults({
        submitHandler: function () {
          
          Swal.fire({
          title: '¿ESTÁS SEGURO DE REGISTRAR LA EMPRESA?',
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
            url:  '<?php echo base_url('ocupacional/guardarEmpresa');?>',
            data: $('#frmEmpresa').serialize(),
            beforeSend: function () 
            {            
              $("#btnRegistrarDatos").html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
              $("#btnRegistrarDatos").removeClass("btn btn-success");
              $("#btnRegistrarDatos").addClass("btn btn-success");
              $("#btnRegistrarDatos").prop('disabled', true);
            },
            success: function (data) {
              if(data.status){  
                
                Swal.fire({
                  icon: 'success',
                  timer: 5000,
                  title: 'Respuesta exitosa',
                  text: data.message,
                  onClose: () => {
                    window.location.replace("<?php echo base_url('ocupacional/registroEmpresa');?>");
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
      
      $('#frmEmpresa').validate({
          rules: {
            razonSocial: {
              required: true
            },
            actividadEconomica: {
              required: true
            }
            ,
            lugarTrabajo: {
              required: true
            }
            ,
            cmbDepartamentoAfi: {
              required: true
            }
            ,
            cmbProvinciaAfi: {
              required: true
            }
            ,
            cmbDistritoAfi: {
              required: true
            }
          },
          messages: {
            razonSocial: {
              required: "Ingrese la razón social"
            }
            ,
            actividadEconomica: {
              required: "Ingrese la actividad E."
            },
            lugarTrabajo: {
              required: "Ingrese el Lugar de trabajo"
            },
            cmbDepartamento: {
              required: "Seleccione el departamento"
            },
            cmbProvincia: {
              required: "Seleccione la provincia"
            },
            cmbDistrito: {
              required: "Seleccione el distrito"
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
  
      var frm = $('#frmEmpresaEdit');
      $.validator.setDefaults({
        submitHandler: function () {
          
          Swal.fire({
          title: '¿ESTÁS SEGURO DE ACTUALIZAR LA EMPRESA?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Seguro!',
          cancelButtonText: 'Cancelar',
        }).then((result) => {
          
          if (result.value) {

          $.ajax({
            type:'POST',
            url: '<?php echo base_url("ocupacional/editarSaveEmpresa");?>',
            data: frm.serialize(),
            beforeSend: function () 
            {            
              $("#btnRegistrarDatos").html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
              $("#btnRegistrarDatos").removeClass("btn btn-success");
              $("#btnRegistrarDatos").addClass("btn btn-success");
              $("#btnRegistrarDatos").prop('disabled', true);
            },
            success: function (data) {
              if(data.status){  
                
                Swal.fire({
                  icon: 'success',
                  timer: 5000,
                  title: 'Respuesta exitosa',
                  text: data.message,
                  onClose: () => {
                    window.location.replace("<?php echo base_url('ocupacional/registroEmpresa');?>");
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
      
      $('#frmEmpresaEdit').validate({
          rules: {
            razonSocial: {
              required: true
            },
            actividadEconomica: {
              required: true
            }
            ,
            lugarTrabajo: {
              required: true
            }
            ,
            cmbDepartamentoAfi: {
              required: true
            }
            ,
            cmbProvinciaAfi: {
              required: true
            }
            ,
            cmbDistritoAfi: {
              required: true
            }
          },
          messages: {
            razonSocial: {
              required: "Ingrese la razón social"
            }
            ,
            actividadEconomica: {
              required: "Ingrese la actividad E."
            },
            lugarTrabajo: {
              required: "Ingrese el Lugar de trabajo"
            },
            cmbDepartamento: {
              required: "Seleccione el departamento"
            },
            cmbProvincia: {
              required: "Seleccione la provincia"
            },
            cmbDistrito: {
              required: "Seleccione el distrito"
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
        

      $("#cmbDepartamento").change(function(){

        var provincias = $("#cmbProvincia");
        var distritos = $("#cmbDistrito");

        var departamentos = $(this);

        if($(this).val() != '')
        {
            $.ajax({

                url:   '<?php echo base_url("provincias/")?>' + departamentos.val(),
                type:  'GET',
                dataType: 'json',
                beforeSend: function () 
                {
                    departamentos.prop('disabled', true);
                    provincias.prop('disabled', true);
                    distritos.prop('disabled', true);
                },
                success:  function (r) 
                {
                  departamentos.prop('disabled', false);

                    // Limpiamos el select
                    provincias.find('option').remove();
                    
                    provincias.append('<option value="">SELECCIONE</option>');                    
                    $(r).each(function(i, v){ // indice, valor
                      provincias.append('<option value="' + v.id + '">' + v.name + '</option>');
                    })

                    provincias.prop('disabled', false);  
                    distritos.prop('disabled', false);              
                },
                error: function()
                {
                    alert('Ocurrio un error en el servidor ..');
                    departamentos.prop('disabled', false);
                }
            });
        }
        else
        {
            provincias.find('option').remove();
            distritos.find('option').remove();
            
            provincias.append('<option value="">SELECCIONE</option>');
            distritos.append('<option value="">SELECCIONE</option>');
        }
      });


      $("#cmbProvincia").change(function(){


        var distritos = $("#cmbDistrito");

        var provincias = $(this);

        if($(this).val() != '')
        {
            $.ajax({

                url:   '<?php echo base_url("distritos/")?>' + provincias.val(),
                type:  'GET',
                dataType: 'json',
                beforeSend: function () 
                {            
                    provincias.prop('disabled', true);
                    distritos.prop('disabled', true);
                },
                success:  function (r) 
                {
                  provincias.prop('disabled', false);

                  // Limpiamos el select
                  
                  distritos.find('option').remove();
                  distritos.append('<option value="">SELECCIONE</option>');
                                    
                  $(r).each(function(i, v){ // indice, valor
                    distritos.append('<option value="' + v.id + '">' + v.name + '</option>');
                  })

                  distritos.prop('disabled', false);                
                },
                error: function()
                {
                    alert('Ocurrio un error en el servidor ..');
                    provincias.prop('disabled', false);
                }
            });
        }
        else
        {
            distritos.find('option').remove();    
            distritos.append('<option value="">--Seleccione--</option>');
        }
      });


      
      $("#cmbDepartamentoEdit").change(function(){

      var provincias = $("#cmbProvinciaEdit");
      var distritos = $("#cmbDistritoEdit");

      var departamentos = $(this);

      if($(this).val() != '' && provincias.val() !='')
      {
          $.ajax({

              url:   '<?php echo base_url("provincias/")?>' + departamentos.val(),
              type:  'GET',
              dataType: 'json',
              beforeSend: function () 
              {
                  departamentos.prop('disabled', true);
                  provincias.prop('disabled', true);
                  distritos.prop('disabled', true);
              },
              success:  function (r) 
              {
                departamentos.prop('disabled', false);

                  // Limpiamos el select
                  provincias.find('option').remove();
                  
                  provincias.append('<option value="0">SELECCIONE</option>');                    
                  $(r).each(function(i, v){ // indice, valor
                    provincias.append('<option value="' + v.id + '">' + v.name + '</option>');
                  })

                  provincias.prop('disabled', false);  
                  distritos.prop('disabled', false);              
              },
              error: function()
              {
                  alert('Ocurrio un error en el servidor ..');
                  departamentos.prop('disabled', false);
              }
          });
      }
      else
      {
          provincias.find('option').remove();
          distritos.find('option').remove();
          
          provincias.append('<option value="0">SELECCIONE</option>');
          distritos.append('<option value="">SELECCIONE</option>');
      }
      });


      $("#cmbProvinciaEdit").change(function(){


        var distritos = $("#cmbDistritoEdit");

        var provincias = $(this);
        if($(this).val() != '' && distritos.val() !='')
        {
            $.ajax({

                url:   '<?php echo base_url("distritos/")?>' + provincias.val(),
                type:  'GET',
                dataType: 'json',
                beforeSend: function () 
                {            
                    provincias.prop('disabled', true);
                    distritos.prop('disabled', true);
                },
                success:  function (r) 
                {
                  provincias.prop('disabled', false);

                  // Limpiamos el select
                  
                  distritos.find('option').remove();
                  distritos.append('<option value="0">SELECCIONE</option>');
                                    
                  $(r).each(function(i, v){ // indice, valor
                    distritos.append('<option value="' + v.id + '">' + v.name + '</option>');
                  })

                  distritos.prop('disabled', false);
                
                },
                error: function()
                {
                    alert('Ocurrio un error en el servidor ..');
                    provincias.prop('disabled', false);
                }
            });
        }
        else
        {
            distritos.find('option').remove();    
            distritos.append('<option value="0">--Seleccione--</option>');
        }
        });


 




      $('#editarEmpresa').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var idEmpresa = button.data('idempresa')
        
        $('#idEmpresa').val(idEmpresa);


    $.ajax({
            type:'GET',
            url: '<?php echo base_url("ocupacional/editarEmpresa");?>' +'/'+ idEmpresa,
 
            success: function (data) {

              if(data.ubicacion.department_id){  
                
                $('#razonSocialEdit').val(data.razonSocial);
                $('#aEconomicaEdit').val(data.actividadEconomica);
                $('#lTrabajoEdit').val(data.lugarTrabajo);

                $('#cmbDepartamentoEdit').val(data.ubicacion.department_id).trigger('change');
                
               $('#cmbProvinciaEdit').find('option').remove();
                  
               $('#cmbProvinciaEdit').append('<option value="">SELECCIONE</option>');                    
                $(data.provincias).each(function(i, v){ // indice, valor
                  $('#cmbProvinciaEdit').append('<option value="' + v.id + '">' + v.name + '</option>');
                })
                
                $('#cmbProvinciaEdit').val(data.ubicacion.province_id).trigger('change');

               $('#cmbDistritoEdit').find('option').remove();
                  
               $('#cmbDistritoEdit').append('<option value="">SELECCIONE</option>');                    
                $(data.distritos).each(function(i, v){ // indice, valor
                  $('#cmbDistritoEdit').append('<option value="' + v.id + '">' + v.name + '</option>');
                })
                
                  $('#cmbDistritoEdit').val(data.idDistrict).trigger('change');

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

  });
      
  $('#editarEmpresa').on('hide.bs.modal', function (event) {
 
    $('#cmbProvinciaEdit').find('option').remove();
    $('#cmbProvinciaEdit').append('<option value="">SELECCIONE</option>');  
    $('#cmbDistritoEdit').find('option').remove();
    $('#cmbDistritoEdit').append('<option value="">SELECCIONE</option>');  

    $(this).find('form')[0].reset();

});
     
    </script>
</body>

</html>