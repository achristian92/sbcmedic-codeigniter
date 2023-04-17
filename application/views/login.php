<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
<base href="<?=base_url();?>" target="_blank">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SBCMedic | Login</title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo  base_url('img/favicon.ico');?>"/>
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
						<p class="login-box-msg">
            </p>

						<form role="form" id="quickForm" method="post" action="<?php echo base_url('validar');?>">
							<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text" style="font-size: 1.5em;background: none; border: none;color: rgba(46,175,109,1);">
								<span class="fas fa-user"></span>
								</div>
							<input type="text" name="username" class="login-form-control" placeholder="Número de documento" autocomplete="off" maxlength="12">
							
							</div>
							</div>
							<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text" style="font-size: 1.5em;background: none; border: none;color: rgba(46,175,109,1);">
								<span class="fas fa-lock"></span>
								</div>
							<input type="password" name="password" class="login-form-control" placeholder="Contraseña" >
							
							</div>
							</div>
							<div class="row">
							<div class="col-12">
								<!-- 
								<div class="icheck-primary">
								<input type="checkbox" id="remember" class="form-check-input login-checkbox">
								<label for="remember">
									Recordar
								</label>
								</div>
								-->
							</div>
							<!-- /.col -->
							</div>
							<br/>
							<p class="mb-1 text-center w-100">
							<!-- <a href="forgot-password.html" style="color: #212529;font-weight: bold;">OLVIDASTE TU CONTRASEÑA</a> -->
							</p>
							<br/>
							<div class="row">
							<div class="col-2"></div>
							<div class="col-8">
								<button type="submit" class="btn btn-primary btn-block" style="background-color: rgba(46,175,109,1);">Ingresar</button>
							</div>
							<!-- /.col -->
							<div class="col-2"></div>
							</div>
              <div class="row">
              <div class="col-2"></div>
							<div class="col-8 mt-2 ">
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url('recuperar');?>" class="text-info"><strong>Recuperar contraseña</strong></a>
							</div>
							<!-- /.col -->
							<div class="col-2"></div>
              </div>
							<br/>
							<div class="row">
							<div class="col-1"></div>
							<div class="col-10">
								<a href="registro" class="btn btn-primary btn-block" >Si no tienes una cuenta Regístrate ahora</a>
							</div>
							<!-- /.col -->
							<div class="col-1"></div>
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

<script type="text/javascript">
$(document).ready(function () {
  <?php if($this->session->flashdata("token_expirado")): ?>
    Swal.fire({
                icon: 'warning',
                timer: 5000,
                title: 'Token no válido',
                text: '<?php echo $this->session->flashdata("token_expirado"); ?>'
              })
  <?php endif; ?>

const SuccessToast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onClose: (toast) => {
    //...
    window.location.replace("<?php echo base_url('cita');?>");
  }
});


const ErrorToast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onClose: (toast) => {
    //...
    
  }
})





var frm = $('#quickForm');

frm.submit(function (e) {

    e.preventDefault();

   
});

 


  $.validator.setDefaults({
    submitHandler: function () {
      $.ajax({
        type: frm.attr('method'),
        url: frm.attr('action'),
        data: frm.serialize(),
        success: function (data) {
          
          if(data.status){  
            Swal.fire({
              timer: 5000,
              text: 'Bienvenido a SBC MEDIC salud a tu alcance.',
              imageUrl: "<?php echo base_url('img/logo_sbcmedic.png')?>",
              showConfirmButton: false,
              onClose: (toast) => {
                window.location.replace(data.url);
              }
            });
          }else{
            Swal.fire({
              icon: 'error',
              timer: 5000,
              title: 'Error de validación',
              text: 'Hola, tu usuario y/o contraseña ingresados no son correctas. Por favor intenta nuevamente'
            })
          }
        },
        error: function (data) {
            alert('An error occurred.');
        },
    });          
    }
  });
  $('#quickForm').validate({
    rules: {
      username: {
        required: true
      },
      password: {
        required: true,
        minlength: 5
      }
    },
    messages: {
      username: {
        required: "Please enter a username"
      },
      password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long"
      },
     
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
