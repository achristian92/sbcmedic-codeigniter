<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
<base href="<?=base_url();?>" target="_blank">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SBCMedic | Cambiar Contraseña</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <style>
  html,body {
    height:100%;
}

.login-form-control {
    display: block;
    width: 100%;
    height: calc(2.25rem + 2px);
    padding: .375rem .75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: transparent;
    background-clip: padding-box;
	border: none;
    border-bottom: 1px solid rgba(46,175,109,1);
    /*border-radius: .25rem;*/
    box-shadow: inset 0 0 0 transparent;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}

.login-checkbox {
	background-color: #fff;
    opacity: 0.9!important;
	/* Double-sized Checkboxes */
	-ms-transform: scale(1.5); /* IE */
  -moz-transform: scale(1.5); /* FF */
  -webkit-transform: scale(1.5); /* Safari and Chrome */
  -o-transform: scale(1.5); /* Opera */
  transform: scale(1.5);
  padding: 10px;
}

.form-check-input {    
	margin-top: 5px !important;
    margin-left: 5px !important;;
}

  
  </style>
</head>
<body class="hold-transition login-page" style="background-image: url(img/bg_login.png); height: 100%;  background-position: center;  background-repeat: no-repeat;  background-size: cover;">
<div class="h-100 w-100">
    <div class="row h-100">
		<div class="d-none d-sm-block col-md-3" style="background-color: rgba(46,175,109,0.9);">
		<div class="login-logo" style="margin: 0 auto;padding-top: 30px;">
						<a href="<?=base_url('login');?>"><img src="img/logo_salud.png" /></a>
					</div>
		<div class="login-logo" style="margin: 0 auto;padding-top: 30px;">
						<a href="<?=base_url('login');?>"><img src="img/logo_side.png" /></a>
					</div>
		</div>
		<div class="col-xs-12 col-md-6" style="background-color: rgba(242,242,242,0.7);">
					<div class="login-box" style="margin: 0 auto;padding-top: 30px;">
					<div class="login-logo">
						<a href="<?=base_url('login');?>"><img src="img/logo_sbcmedic.png" /></a>
					</div>
					<!-- /.login-logo -->
					<div class="">
						<div class="">
						<p class="login-box-msg"></p>
            <p style="text-align:justify">
              <strong>
                Ingrese una nueva constraseña.
              </strong>
            </p>
						<form role="form" id="formCambioPassword" method="post" action="<?php echo base_url('confirmarpassword');?>">
              <input type="hidden" name="token"value="<?php echo $token;?>">
              <div class="card-body">
                  <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" id="password" maxlength="15">
                  </div>
                  <div class="form-group">
                    <label for="repassword">Repetir Contraseña</label>
                    <input type="password" name="repassword" class="form-control" placeholder="Repetir contraseña" id="repassword" maxlength="15">
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" id="btnCambiarC" class="btn btn-primary btn-block" style="background-color: rgba(46,175,109,1);">Cambiar Contraseña</button>
              </div>
						</form>
						</div>
						<!-- /.login-card-body -->
					</div>
					</div>
					<!-- /.login-box -->
		</div>
		<div class="d-none d-sm-block col-md-3" style="">
		
		</div>
	</div>
</div>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- jquery-validation -->
<script src="plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="plugins/jquery-validation/additional-methods.min.js"></script>
<!-- AdminLTE App -->
<script src="js/adminlte.min.js"></script>

<script>
$(document).ready(function () {

  var frm = $('#formCambioPassword');
  $.validator.setDefaults({
    submitHandler: function () {
      $("#btnCambiarC").attr('disabled',true);
      
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
                window.location.replace("<?php echo base_url('cita');?>");
              }
            })
          } else {
            $("#btnCambiarC").attr('disabled',false);
            Swal.fire({
              icon: 'error',
              timer: 5000,
              title: 'Error de validación',
              text: data.message
            })
          }
        },
        error: function (data) {
          $("#btnCambiarC").attr('disabled',false);
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
  
  $('#formCambioPassword').validate({
      rules: {
        password: {
          required: true,
          minlength: 6,
          maxlength: 15
        },
        repassword: {
          required: true,
          minlength: 6,
          maxlength: 15,
          equalTo: "#password"
        }
      },
      messages: {
        password: {
          required: "Ingrese la contraseña",
          minlength: "Ingrese 6 caracteres mínimo"
        },
        repassword: {
          required: "Ingrese la repetición de contraseña",
          minlength: "Ingrese 6 caracteres mínimo",
          equalTo: "Ingrese la misma contraseña que la anterior"
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
  });
</script>

</body>
</html>
