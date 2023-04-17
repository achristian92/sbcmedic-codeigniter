<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
<base href="consulta">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo  base_url('img/favicon.ico');?>"/>
  <title>SBCMedic | Mi Perfil</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- pace-progress -->
  <link rel="stylesheet" href="plugins/pace-progress/themes/black/pace-theme-flat-top.css">
  <!-- adminlte-->
  <link rel="stylesheet" href="css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <style>
  .align-items-center {
    -ms-flex-align: center!important;
    align-items: center!important;
}

/*This is modifying the btn-primary colors but you could create your own .btn-something class as well*/
.btn-primary {
    color: #fff;
    background-color: #5996be;
    border-color: #357ebd; /*set the color you want here*/
}
.btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary {
    color: #fff;
    background-color: #004862;
    border-color: #285e8e; /*set the color you want here*/
}

.btn-info {
    color: #fff;
    background-color: #30b873;
    border-color: #357ebd; /*set the color you want here*/
}
.btn-info:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary {
    color: #fff;
    background-color: #004862;
    border-color: #285e8e; /*set the color you want here*/
}

.btn-appointment {
    color: #fff;
    background-color: #5996be;
    border-color: #357ebd; /*set the color you want here*/
}
.btn-appointment:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary {
    color: #fff;
    background-color: #004862;
    border-color: #285e8e; /*set the color you want here*/
}

.btn-medicine {
    color: #fff;
    background-color: #004761;
    border-color: #357ebd; /*set the color you want here*/
}
.btn-medicine:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary {
    color: #fff;
    background-color: #5996be;
    border-color: #285e8e; /*set the color you want here*/
}

[class*=sidebar-dark-] .sidebar a {
    color: #fff;
}

[class*=sidebar-dark-] .sidebar a:hover {
    color: #c2c7d0; 
}

.one-edge-shadow {
  border-radius: 32px;
	-webkit-box-shadow: 0 8px 6px -6px black;
	   -moz-box-shadow: 0 8px 6px -6px black;
	        box-shadow: 0 8px 6px -6px black;
}

.one-edge-shadow:hover {
  border-radius: 32px;
	-webkit-box-shadow: 2px 12px 10px -6px black;
	   -moz-box-shadow: 2px 12px 10px -6px black;
	        box-shadow: 2px 12px 10px -6px black;
}

.bg_transparent {
  background-color: transparent;
}


  </style>

<style>
  label {
    color: rgba(46,175,109,1);
  }
  </style>
</head>
<body class="hold-transition sidebar-mini pace-primary" style="background-image: url(img/fondo_body.png); height: 100%;  background-position: right;  background-repeat: no-repeat;  ">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light bg_transparent" style="height: 100px;">
    <!-- Left navbar links -->
    <ul class="navbar-nav h-100 align-items-center">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item" style=";">
        <span style="vertical-align:middle;  "><span style="margin-left: 10px;font-size: 2.4rem;font-weight:bold;color: #004663;vertical-align:middle;  "> Mi Perfil<span></span>
      </li>
    </ul>

	<?php $this->load->view("logout"); ?>
  
  </nav>
  <!-- /.navbar -->

  <?php $this->load->view('aside'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background: transparent;">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
      <!-- form start -->
      <form role="form" id="formActualizarPerfil" method="post" action="<?php echo base_url('actualiZarDatos');?>">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12 card-body">
            <!-- jquery validation -->
            <div class="row">
            <div class="col-md-6 ">
              <!-- /.card-header -->
               
                <div class="form-group">
                  <label for="idFirstname">Nombres *</label>
                  <input type="text" name="firstname" class="form-control" id="idFirstname" value="<?php echo $firstname;
?>">
                </div>
                                                                                   
                </div>
            
            <!-- /.card -->
           
            <!-- jquery validation -->
            <div class="col-md-6">
                <div class="form-group">
                  <label for="idLastname">Apellidos *</label>
                  <input type="text" name="lastname" class="form-control" id="idLastname" value="<?php echo $lastname;?>">
                </div>
            </div>

            </div>
            <div class="row">
              
              <div class="col-md-3">
                <div class="form-group">
                    <label for="idTipo">Tipo *</label>
                    <select class="form-control select2" name="tipo" style="width: 100%;" id="idTipo">
                      <?php
                        foreach ($tipoDocumento as $id => $documento)
                        {
                          $selected = '';
                          if ($id == $idTypeDocument){
                              $selected = 'selected';
                          }
                          
                          echo "<option value='$id' ".$selected." >$documento</option>";
                        }
                      ?>
                    </select>
                  </div>
              </div>
              <div class="col-md-3">
              <div class="form-group">
                    <label for="idDocumento">Documento *</label>
                    <input type="text" name="documento" class="form-control" id="idDocumento" style=" background-color:#f28482; font-weight: bold;" value="<?php echo $username;?>" disabled>
                    <input type="hidden" name="usuario" value="<?php echo $username;?>">
                  </div>
              </div>
              <div class="col-md-6">
              <div class="form-group">
                    <label for="idEmail">Correo Electrónico *</label>
                    <input type="email" name="email" class="form-control" id="idEmail" value="<?php echo $email;?>">
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
              <div class="form-group">
                    <label for="idTelefono">Télefono *</label>
                    <input type="text" name="phone" class="form-control" id="idTelefono" value="<?php echo $phone;?>">
                  </div>
              </div>
			  
              <div class="col-md-3">
                <div class="form-group">
                    <label for="idSexo">Sexo *</label>
                    <select class="form-control select2" name="sex" style="width: 100%;" id="idSexo">
                    <option <?php echo $sex == "M" ? "selected" : "";?> value="M">Masculino</option>
                    <option <?php echo $sex == "F" ? "selected" : "";?> value="F">Femenino</option>                    
                  </select>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                    <label for="idFechanacimiento">Fecha de Nacimiento *</label>
                    <input type="date" name="birthdate" class="form-control" id="idFechanacimiento" value="<?php echo $birthdate;?>" requerid>
                  </div>
              </div>
              
            </div>
            <div class="row">
              <div class="col-md-6">
              <div class="form-group">
                    <label for="idDireccion">Dirección *</label>
                    <input type="text" name="address" class="form-control" id="idDireccion" value="<?php echo $address;?>">
                  </div>
              </div>
              <div class="col-md-6">
              <div class="form-group">
                    <label for="idDepartamento">Departamento  *</label>
                    <select class="form-control select2" name="departamento" style="width: 100%;" id="idDepartamento">
                    <?php
                          foreach ($departamentos as $i => $departamento){
                          
                            $selected = '';
                            if ($departamento->id == $miUbigeo["department_id"]){
                                $selected = 'selected';
                            }
                            
                            echo "<option value='$departamento->id' ".$selected." >$departamento->name</option>";
                        }
                      ?>
                    </select> 
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                    <label for="idProvincia">Provincia *</label>                    
                    <select id="idProvincia" class="form-control select2" name="province" style="width: 100%;">
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
              <div class="col-md-6">
                <div class="form-group">
                    <label for="idDistrito">Distrito *</label>  
                    <select class="form-control select2" name="distrito" style="width: 100%;" id="idDistrito">                                  
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
            <div class="row">
              <div class="col-md-6">
              </div>
              <div class="col-md-6">
              </div>
            </div>
            <!-- /.card -->
            </div>
          <!-- right column -->
           
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-md-2">
            <div class="form-group text-center" style="padding: .375rem .75rem;font-size: 1rem;">
              <label for="idPassword" style="color: #004663;">Constraseña *</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group" style="padding: .375rem 1.75rem;font-size: 1rem;">
              <input type="password" name="password" class="form-control" id="password">
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group text-center" style="padding: .375rem .75rem;font-size: 1rem;">
              <label for="idRepassword" style="color: #004663;">Re-contraseña *</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group" style="padding: .375rem 1.75rem;font-size: 1rem;">
              <input type="password" name="repassword" class="form-control" id="idRepassword">
            </div>
          </div>
        </div>
        </div>
        <div class="row mb-2">
           
          <div class="col-sm-4 offset-sm-4 col-md-4 offset-md-4 col-lg-4 offset-lg-4 col-xl-3 offset-xl-5 col-6 offset-4">
            <button type="submit" id="btnActualizar" class="btn btn-outline-success">ACTUALIZAR DATOS</button>
          </div>
        </div>
      </div><!-- /.container-fluid -->
      </form>
    </section>
    <!-- /.content -->
    <footer class="main-footer bg_transparent">
      <div class="float-right d-none d-sm-block">
        <b>Versión</b> <?php echo $version["version"];?>
      </div>
      <strong>Copyright &copy; 2020 <a href="javascript:void(0)">SBCMedic</a>.</strong> Derechos Reservados.
    </footer>

  </div>
  <!-- /.content-wrapper -->



  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- pace-progress -->
<script src="plugins/pace-progress/pace.min.js"></script>
<!-- AdminLTE App -->
<script src="js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- jquery-validation -->
<script src="plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="plugins/jquery-validation/additional-methods.min.js"></script>

<script>

var frm = $('#formActualizarPerfil');
  $.validator.setDefaults({
    submitHandler: function () {
      
      $("#btnActualizar").attr('disabled',true);
      $.ajax({
        type: frm.attr('method'),
        url: frm.attr('action'),
        data: frm.serialize(),
        success: function (data) {
          if(data.status){  
            
            Swal.fire({
              icon: 'success',
              timer: 5000,
              title: 'Respuesta exitosa',
              text: data.message,
              onClose: () => {
                window.location.replace("<?php echo base_url('miperfil');?>");
              }
            })
          }else{
            $("#btnActualizar").attr('disabled',false);
            Swal.fire({
              icon: 'error',
              timer: 5000,
              title: 'Error de validación',
              text: data.message
            })
          }
        },
        error: function (data) {
          $("#btnActualizar").attr('disabled',false);
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
  
  $('#formActualizarPerfil').validate({
      rules: {
        email: {
          required: true,
          email: true,
        },
        firstname: {
          required: true,
          minlength: 3,
          maxlength: 100
        },
        lastname: {
          required: true,
          minlength: 3,
          maxlength: 100
        }
        ,
        phone: {
          required: true,
          minlength: 3,
          maxlength: 10
        },
        birthdate: {
          required: true,
          minlength: 3,
          maxlength: 10
        },
        password: {
          minlength: 6,
          maxlength: 15
        },
        repassword: {
          minlength: 6,
          maxlength: 15,
          equalTo: "#password"
        },
        province: {
          required: true
        },
        distrito: {
          required: true
        }
      },
      messages: {
        email: {
          required: "Ingrese el email válido",
          email: "Por favor ingrese un email válido"
        },
        firstname: {
          minlength: "Debe ingresar al menos 3 caracteres",
          maxlength: "Demasiados caracteres",
          required: "Ingrese un nombre válido"
          
        },
        lastname: {
          minlength: "Debe ingresar al menos 3 caracteres",
          maxlength: "Demasiados caracteres",
          required: "Ingrese un apellido válido"
          
        },
        phone: {
          minlength: "Debe ingresar al menos 7 caracteres",
          required: "Ingrese el teléfono válido"
          
        },
        birthdate: {
          required: "Ingrese una fecha válida",
          email: "Por favor ingrese un email válido"
        },
        address: {
          minlength: "Debe ingresar al menos 10 caracteres",
          required: "Ingrese el apellido válido"
          
        },
        password: {
          maxlength: "Demasiados caracteres",
          minlength: "Ingrese 6 caracteres mínimo"
        },
        repassword: {
          maxlength: "Demasiados caracteres",
          minlength: "Ingrese 6 caracteres mínimo",
          equalTo: "Ingrese la misma contraseña que la anterior"
        },
        province: {
          required: "Seleccione alguna provincia"
        },
        distrito: {
          required: "Seleccione alguna distrito"
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


  $("#idDepartamento").change(function(){

    var provincias = $("#idProvincia");
    var distritos = $("#idDistrito");

    var departamentos = $(this);

    if($(this).val() != '')
    {
      $.ajax({

          url:   'provincias/'+departamentos.val(),
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
              distritos.find('option').remove();
              provincias.append('<option value="">Seleccionar</option>');                    
              $(r).each(function(i, v){ // indice, valor
                provincias.append('<option value="' + v.id + '">' + v.name + '</option>');
              })

              provincias.prop('disabled', false);                
          },
          error: function()
          {
              alert('Ocurrio un error en el servidor ..');
              departamentos.prop('disabled', false);
          }
      });
    } else {
        provincias.find('option').remove();
        provincias.prop('disabled', true);
        distritos.prop('disabled', true);
    }
  });

  
$("#idProvincia").change(function(){
  
  var distritos = $("#idDistrito");
  var provincias = $(this);

    if($(this).val() != '')
    {
        $.ajax({

            url:   'distritos/'+provincias.val(),
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
                distritos.append('<option value="">Seleccionar</option>');                    
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
        distritos.prop('disabled', true);
    }
  });
</script>
</body>
</html>

