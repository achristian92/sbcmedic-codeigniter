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
  <title>SBCMedic | REGISTRAR HISTORIAL CLÍNICO</title>
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
    <form method="POST" action="<?php echo base_url('ocupacional/saveAfiliado'); ?>" id="frmHClinico"  enctype="multipart/form-data">
      <div class="row bg-black">
        <div class="col-sm text-center" style=" justify-content: center;align-items: center;">
          <h2>EDITAR AFILIADO - CLÍNICA OCUPACIONAL </h2>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-4 col-form-label">EMPRESA</label>
            <div class="col-sm-8">
              <select class="form-control" name="cmbEmpresa" id="cmbEmpresa" required>
                <option value="">SELECCIONAR</option>
                <?php
                  foreach ($empresas as $empresa) {
                ?>
                  <option value="<?php echo $empresa->id; ?>" <?php echo ($historialEmpresa["codigoEmpresa"] == $empresa->id)? "selected" : "" ; ?> ><?php echo $empresa->razonSocial; ?></option>
                <?php
                  }
                ?>
              </select>
            </div>
          </div>
        </div>
        
 


      </div>

      <div class="row">
        <div class="col-sm">
          <div class="form-group">
            <label for="cmbTipoE">TIPO DE EVALUACIÓN</label>
            <select class="form-control" name="cmbTipoE" id="cmbTipoE" required>
                <option value="">SELECCIONAR</option>
                <?php
                  foreach ($tipoEvaluaciones as $tipoEvaluacion) {
                ?>
                  <option value="<?php echo $tipoEvaluacion->id; ?>" <?php echo ($historialEmpresa["idTipoEvaluacion"] == $tipoEvaluacion->id)? "selected" : "" ; ?> ><?php echo $tipoEvaluacion->descipcion; ?></option>
                <?php
                  }
                ?>
              </select>
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="cmbPuesto">PUESTO AL QUE POSTULA </label>
            <select class="form-control" name="cmbPuesto" id="cmbPuesto" required>
                <option value="">SELECCIONAR</option>
                <?php
                  foreach ($puestos as $puesto) {
                ?>
                  <option value="<?php echo $puesto->id; ?>" <?php echo ($historialEmpresa["idPuesto"] == $puesto->id)? "selected" : "" ; ?> ><?php echo $puesto->descipcion; ?></option>
                <?php
                  }
                ?>
              </select>
          </div>
        </div>
        
      </div>


      <div class="row">
        <div class="col-sm" style=" justify-content: center;align-items: center;">
          <h2>LUGAR DEL EXÁMEN</h2>
        </div>
      </div>

      <div class="row">
        <div class="col-sm">
          <div class="form-group">
            <label for="cmbDepartamento">DEPARTAMENTO</label>
            <select name="cmbDepartamento" id="cmbDepartamento" class="form-control select2" style="width: 100%;" required>
              <option value="">SELECCIONE</option>
                <?php foreach ($departamentos as $departamento) { ?>
                <option value="<?=$departamento->id;?>" <?php echo ($departamento->id == $miUbigeoEmpresa["department_id"])? "selected" : ""; ?> ><?=$departamento->name;?></option>                    
              <?php } ?>
            </select> 
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="cmbProvincia">PROVINCIA</label>
            <select  name="cmbProvincia" id="cmbProvincia" class="form-control select2" style="width: 100%;" required>
                      <?php
                        foreach ($provinciasEmp as $i => $provincia){
                        
                          $selected = '';
                          if ($provincia->id == $miUbigeoEmpresa["province_id"]){
                              $selected = 'selected';
                          }
                          
                          echo "<option value='$provincia->id' ".$selected." >$provincia->name</option>";

                        }
                    ?>
            </select>
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="hora">DISTRITO</label>
            <select name="cmbDistrito" id="cmbDistrito" class="form-control select2" style="width: 100%;" required>
                    <?php
                        foreach ($distritosEmp as $i => $distrito){
                        
                        $selected = '';
                        if ($distrito->id == $miUbigeoEmpresa["id"]){
                            $selected = 'selected';
                        }
                        
                        echo "<option value='$distrito->id' ".$selected." >$distrito->name</option>";
                      }
                    ?>
            </select>
          </div>
        </div>
      </div>

      <div class="row bg-info">
        <div class="col">
          <h2>FILIACIÓN DEL TRABAJADOR</h2>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-3 col-form-label">NOMBRES</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="nombres" id="nombres" value="<?php echo $afiliado["nombre"]; ?>" required>
            </div>
          </div>
        </div>
        <div class="col">

          <div class="form-group row">
            <label for="apellidos" class="col-sm-3 col-form-label">APELLIDOS</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="apellidos" id="apellidos" value="<?php echo $afiliado["apellido"]; ?>" required>
            </div>
          </div>
        </div>
        <div class="col-sm-3">

          <div class="form-group row">
            <label for="fechaNacimiento" class="col-sm-4 col-form-label">FECHA DE NACIMIENTO</label>
            <div class="col-sm-8">
              <input type="date" name="fechaNacimiento" class="form-control" value="<?php echo ($afiliado["fechaNacimiento"]) ? $afiliado["fechaNacimiento"] :date("Y-m-d"); ?>" requerid>
            </div>
          </div>
        </div>
        <div class="col-sm-3">

          <div class="form-group row">
            <label for="cmbEcivil" class="col-sm-4 col-form-label">ESTADO CIVIL </label>
            <div class="col-sm-8">
              <select class="form-control" id="cmbEcivil" name="cmbEcivil"  required>
                <option value="">SELECCIONE</option>
                <option value="SOL" <?php echo ($afiliado["estadoCivil"] == "SOL")? "selected" : ""; ?>>SOLTERO(A)</option>
                <option value="CAS" <?php echo ($afiliado["estadoCivil"] == "CAS")? "selected" : ""; ?>>CASADO(A)</option>
                <option value="DIV" <?php echo ($afiliado["estadoCivil"] == "DIV")? "selected" : ""; ?>>DIVORCIADO(A)</option>
              </select>
            </div>
          </div>
        </div>
 


      </div>




      <div class="row">
        <div class="col">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-4 col-form-label">DOCUMENTO IDENTIDAD</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="documento" name="documento" value="<?php echo $afiliado["documento"]; ?>" required>
            </div>
          </div>
        </div>
        <div class="col">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-3 col-form-label">DOMICILIO FISCAL</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="dFiscal" name="dFiscal" value="<?php echo $afiliado["domicilioFiscal"]; ?>" required>
            </div>
          </div>
        </div>
        <div class="col-sm-3">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-4 col-form-label">EMAIL</label>
            <div class="col-sm-8">
              <input type="email" class="form-control" id="email" name="email" value="<?php echo $afiliado["email"]; ?>" required>
            </div>
          </div>
        </div>
        <div class="col-sm-3">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-4 col-form-label">TELÉFONO</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $afiliado["telefono"]; ?>" required>
            </div>
          </div>
        </div>
 


      </div>


      <div class="row">
        <div class="col">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-2 col-form-label">GÉNERO</label>
            <div class="col-sm-10">
              <select class="form-control" name="genero" required>
                <option value="">SELECCIONE</option>
                <option value="M" <?php echo ($afiliado["genero"] == "M")? "selected" : ""; ?>>MASCULINO</option>
                <option value="F" <?php echo ($afiliado["genero"] == "F")? "selected" : ""; ?>>FEMENINO</option>
              </select>
            </div>
          </div>
        </div>
        <div class="col">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-2 col-form-label">FOTO</label>
            <div class="col-sm-10">
              <input name="archivo" id="archivo" type="file" class="form-control" accept="image/x-png, image/jpeg" />
            </div>
          </div>
        </div>
        
      </div>



      <div class="row">
        <div class="col">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-4 col-form-label">GRADO DE INSTRUCCIÓN</label>
            <div class="col-sm-8">
              <select class="form-control" id="cmbGinstruccion" name="cmbGinstruccion" required>
                <option value="">SELECCIONE</option>
                <option value="TEC" <?php echo ($afiliado["gradoInstruccion"] == "TEC")? "selected" : ""; ?> >TÉCNICO</option>
                <option value="SUP" <?php echo ($afiliado["gradoInstruccion"] == "SUP")? "selected" : ""; ?> >SUPERIORES</option>
                <option value="UNI" <?php echo ($afiliado["gradoInstruccion"] == "UNI")? "selected" : ""; ?> >UNIVERSITARIO</option>
                <option value="PRI" <?php echo ($afiliado["gradoInstruccion"] == "PRI")? "selected" : ""; ?> >PRIMARIOS</option>
                <option value="SEC" <?php echo ($afiliado["gradoInstruccion"] == "SEC")? "selected" : ""; ?> >SECUNDARIOS</option>
                <option value="SES" <?php echo ($afiliado["gradoInstruccion"] == "SES")? "selected" : ""; ?> >SIN ESTUDIOS</option>
              </select>
            </div>
          </div>
        </div>
        <div class="col">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-3 col-form-label">N° TOTAL DE HIJOS VIVOS</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="nTHVivos" value="<?php echo $afiliado["nThijovivo"]; ?>">
            </div>
          </div>
        </div>
        <div class="col">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-4 col-form-label">N° DE DEPENDIENTES</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="nDependiente" value="<?php echo $afiliado["nDependiente"]; ?>">
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-4 col-form-label">DEPARTAMENTO</label>
            <div class="col-sm-8">
              <select name="cmbDepartamentoAfi" id="cmbDepartamentoAfi" class="form-control select2" style="width: 100%;" required>
                <option value="">SELECCIONE</option>
                <?php foreach ($departamentos as $departamento) { ?>
                  <option value="<?=$departamento->id;?>" <?php echo ($departamento->id == $miUbigeo["department_id"])? "selected" : ""; ?> ><?=$departamento->name;?></option>                    
                <?php } ?>
              </select> 
            </div>
          </div>
        </div>
        <div class="col">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-3 col-form-label">PROVINCIA</label>
            <div class="col-sm-9">
              <select  name="cmbProvinciaAfi" id="cmbProvinciaAfi" class="form-control select2" style="width: 100%;" required>
                      <?php
                        foreach ($provincias as $i => $provincia){
                        
                          $selected = '';
                          if ($provincia->id == $miUbigeo["province_id"]){
                              $selected = 'selected';
                          }
                          
                          echo "<option value='$provincia->id' ".$selected." >$provincia->name</option>";

                        }
                      ?>
              </select>
            </div>
          </div>
        </div>
        <div class="col">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-4 col-form-label">DISTRITO</label>
            <div class="col-sm-8">
              <select  name="cmbDistritoAfi" id="cmbDistritoAfi" class="form-control select2" style="width: 100%;" required>
                <?php
                    foreach ($distritos as $i => $distrito){
                    
                    $selected = '';
                    if ($distrito->id == $miUbigeo["id"]){
                        $selected = 'selected';
                    }
                    
                    echo "<option value='$distrito->id' ".$selected." >$distrito->name</option>";
                  }
                ?>
              </select>
            </div>
          </div>
        </div>
      </div>
 



      <div class="row">

        <div class="col-12">
          <button type="submit" name="btnRegistrarDatos" id="btnRegistrarDatos"  class="btn btn-success btn-block">ACTUALIZAR AFILIADO</button>
          <input type="hidden" name="idAfiliado" value="<?php echo $afiliado["id"]; ?>">
          <input type="hidden" name="idAfiliado_ocupacional" value="<?php echo $historialEmpresa["id"]; ?>">
        </div>
      </div>

    </form>



    <br>


    <?php $this->load->view("scripts"); ?>
    <!-- Select2 -->
    <script src="<?php echo base_url('plugins/select2/js/select2.full.min.js'); ?>"></script>
    <script>
      $('.select2').select2();
    
      
  
      var frm = $('#frmHClinico');
      $.validator.setDefaults({
        submitHandler: function () {
          
          Swal.fire({
          title: '¿ESTÁS SEGURO DE ACTUALIZAR ESTE AFILIADO?',
          text: 'Una vez confirmado, no se podrá revertir.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Seguro!',
          cancelButtonText: 'Cancelar',
        }).then((result) => {
          
          if (result.value) {
            var formData = new FormData($("#frmHClinico")[0]);
          $.ajax({
            type: frm.attr('method'),
            url: frm.attr('action'),
            data: formData,
            contentType: false,
            processData: false,
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
                    window.location.replace("<?php echo base_url('ocupacional/editarAfiliado/'.$this->uri->segment(3));?>");
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
      
      $('#frmHClinico').validate({
          rules: {
            cmbEmpresa: {
              required: true
            },
            cmbTipoE: {
              required: true
            }
            ,
            cmbPuesto: {
              required: true
            }
            ,
            cmbDepartamento: {
              required: true
            }
            ,
            cmbProvincia: {
              required: true
            }
            ,
            cmbDistrito: {
              required: true
            }
            ,
            nombres: {
              required: true
            }
            ,
            apellidos: {
              required: true
            }
            ,
            cmbEcivil: {
              required: true
            }
            ,
            documento: {
              required: true
            }
            ,
            dFiscal: {
              required: true
            }
            ,
            email: {
              required: true
            }
            ,
            telefono: {
              required: true
            }
            ,
            cmbGinstruccion: {
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
            cmbEmpresa: {
              required: "Seleccione una empresa"
            }
            ,
            cmbTipoE: {
              required: "Seleccione un tipo de evluación"
            },
            cmbPuesto: {
              required: "Seleccione el puesto"
            },
            cmbDepartamento: {
              required: "Seleccione el departamento"
            },
            cmbProvincia: {
              required: "Seleccione la provincia"
            },
            cmbDistrito: {
              required: "Seleccione el distrito"
            },
            nombres: {
              required: "Ingrese el nombre"
            },
            apellidos: {
              required: "Ingrese el apellido"
            },
            cmbEcivil: {
              required: "Seleccione el estado civil"
            },
            documento: {
              required: "Ingrese el documento"
            },
            dFiscal: {
              required: "Ingrese el domicilio fiscal"
            },
            email: {
              required: "Ingrese el email"
            },
            telefono: {
              required: "Ingrese el teléfono"
            },
            cmbGinstruccion: {
              required: "Seleccione el Grado de instrucción"
            },
            cmbDepartamentoAfi: {
              required: "Seleccione departamento"
            },
            cmbProvinciaAfi: {
              required: "Seleccione la provincia"
            },
            cmbDistritoAfi: {
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
    
      $("#cmbDepartamentoAfi").change(function(){

        var provincias = $("#cmbProvinciaAfi");
        var distritos = $("#cmbDistritoAfi");

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


      $("#cmbProvinciaAfi").change(function(){


        var distritos = $("#cmbDistritoAfi");

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
    </script>
</body>

</html>