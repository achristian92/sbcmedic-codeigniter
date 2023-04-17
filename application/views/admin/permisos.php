<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html> 
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SBCMedic | Gestión Usuarios</title>
  <?php $this->load->view("styles"); ?>
</head>
<body class="hold-transition sidebar-mini pace-primary" style="background-image: url(<?php echo base_url('img/fondo_body.png');?>); height: 100%;  background-position: right;  background-repeat: no-repeat;  ">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light bg_transparent" style="height: 100px;">
    <!-- Left navbar links -->
    <ul class="navbar-nav h-100 align-items-center">
      <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
      <span style="margin-left: 10px;font-size: 2.4rem;font-weight:bold;color: #004663;vertical-align:middle;  "> Permisos<span></span>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
  <?php $this->load->view('aside'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background: transparent;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div>
          <div class="col-sm-6">
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
          <div class="row">
            <div class="col">
              <h3>Asignar Usuario -> Rol</h3>
            </div>
          </div>
          <div class="row">
            <div class="col-4 form-group">
              <label for="cmbUsuarios">Usuario</label>
              <select id="cmbUsuarios" name="cmbUsuarios" class="form-control select2" style="width: 100%;">
                <option value="">Seleccionar</option>                    
                <?php foreach ($usuarios as $usuario) { ?>
                    <option value="<?=$usuario->idUser;?>"><?=$usuario->nombreUsuario;?></option>                    
                  <?php } ?>
              </select>
            </div>
            <div class="col-4 form-group">
              <label for="roles">Rol</label>
              <select id="roles" name="roles" class="form-control" style="width: 100%;">
                <option value="">Seleccionar</option>                    
                <?php foreach ($roles as $rol) { ?>
                    <option value="<?=$rol->id;?>"><?=$rol->descripcion;?></option>                    
                  <?php } ?>
              </select>
            </div>
            <div class="col-4 form-group">
              <label for="cmbMedico">&nbsp;</label>
              <button type="button"  id="btnPermisoRol" class="form-control btn btn-outline-primary">Asignar Usuario->Rol</button>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col">
            <h3>Asignar Rol -> Permisos</h3>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <label for="cmbRoles">Rol</label>
              <select id="cmbRoles" name="cmbRoles" class="form-control" style="width: 100%;">
                <option value="">Seleccionar</option>
                <?php foreach ($roles as $rol) { ?>
                    <option value="<?=$rol->id;?>"><?=$rol->descripcion;?></option>                    
                  <?php } ?>
              </select>
            </div>
            <div class="col-4">
              <label for="cmbPermisos">Permisos</label>
              <select id="cmbPermisos" name="cmbPermisos[]" class="form-control select2" style="width: 100%;"  multiple="multiple">
                <?php foreach ($permisos as $permiso) { ?>
                    <option value="<?=$permiso->id;?>"><?=$permiso->descripcion;?></option>                    
                <?php } ?>
              </select>
            </div>
            <div class="col-4 form-group">
              <label for="cmbMedico">&nbsp;</label>
              <button type="button" id="btnGuardarPermisoRol" class="form-control btn btn-outline-primary">Asignar Rol->Permisos</button>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col">
              <h3>Asignar Usuario -> Profesional</h3>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <label for="cmbUsuariosMed">Usuario</label>
              <select id="cmbUsuariosMed" name="cmbUsuariosMed" class="form-control select2" style="width: 100%;">
                <option value="">Seleccionar</option>
                <?php foreach ($usuarios as $usuario) { ?>
                    <option value="<?=$usuario->idUser;?>"><?=$usuario->nombreUsuario;?></option>                    
                <?php } ?>
              </select>
              
            </div>
            <div class="col-4">
              <label for="cmbMedico">Profesional</label>
              <select id="cmbMedico" name="cmbMedico" class="form-control" style="width: 100%;">
                <option value="">Seleccionar</option>
                <?php foreach ($medicos as $medico) { ?>
                    <option value="<?=$medico->idDoctor;?>"><?=$medico->nombreMedico;?></option>                    
                  <?php } ?>
              </select>
            </div>
            <div class="col-4 form-group">
              <label for="cmbMedico">&nbsp;</label>
              <button type="button" id="btnAsginarMedicoUser" class="form-control btn btn-outline-primary">Asignar Usuario->Profesional</button>
            </div>
          </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
   
  <footer class="main-footer bg_transparent">
    <div class="float-right d-none d-sm-block">
      <b>Versión</b> <?php echo $version["version"];?>
    </div>
    <strong>Copyright &copy; 2020 <a href="javascript:void(0)">SBCMedic</a>.</strong> Derechos Reservados.
  </footer>
</div>
<!-- ./wrapper -->
<?php $this->load->view('scripts'); ?>
<!-- Select2 -->
<script src="<?php echo base_url('plugins/select2/js/select2.full.min.js');?>"></script>
 
<script>
  //Initialize Select2 Elements
  $('.select2').select2({
    placeholder: 'Permisos',
    allowClear: true
  });
  
  $("#cmbUsuarios").change(function(){
    var usuario = $(this);

    if($(this).val() != '')
    {
      $.ajax({
        url:   '<?=base_url('admin/usuarioRol');?>/' + usuario.val(),
        type:  'GET',
        dataType: 'json',
        beforeSend: function () 
        {
          $("#roles option").each(function(){
              $("#roles option[value='" +  $(this).val() + "']").attr("selected", false);
          });
        },
        success:  function (respuesta) 
        {
          if (respuesta > 0) {
            $('#roles').val(respuesta);
          } else {
              alert('Error de usuario');
          }
        },
        error: function()
        {
          alert('Error interno');
        }
      });
    } else {
      $("#roles option[value='']").attr("selected", true);
    }
  });

  $('#btnPermisoRol').click(function() {
    var usuario = $('#cmbUsuarios').val();
    var rol = $('#roles').val();

    if(usuario != '' && rol != '')
    {
      $.ajax({
        url:   '<?=base_url('admin/gUsuarioRol');?>',
        type:  'POST',
        data: { usuario: usuario, rol : rol } ,
        dataType: 'json',
        beforeSend: function () 
        {
          $('#btnPermisoRol').prop('disabled', true);
        },
        success:  function (respuesta) 
        {
          if (respuesta.status) {
            Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: 'Se guardo correctamente.',
              showConfirmButton: false,
              timer: 1500
            });
            $('#btnPermisoRol').prop('disabled', false);
          } else {
              alert('Error de usuario');
          }
        },
        error: function()
        {
          alert('Error interno');
        }
      });
    }
  });

  $("#cmbRoles").change(function(){
    var rol = $(this);

    if($(this).val() != '')
    {
      $.ajax({
        url:   '<?=base_url('admin/rolPermiso');?>/' + rol.val(),
        type:  'GET',
        dataType: 'json',
        success:  function (respuesta) 
        {
          var Values = new Array();
          $.each(respuesta.data, function(index) {
            Values.push(respuesta.data[index].idPermiso);
          });

          $('#cmbPermisos').val(Values).trigger('change');
        },
        error: function()
        {
          alert('Error interno');
        }
      });
    } else {
      $('#cmbPermisos').val([]).trigger('change');
    }
  });

  $('#btnGuardarPermisoRol').click(function() {
    var permisos = $('#cmbPermisos').val();
    var rol = $('#cmbRoles').val();

    if(rol != '' && permisos != '')
    {
      $.ajax({
        url:   '<?=base_url('admin/gRolPermiso');?>',
        type:  'POST',
        data: { rol: rol, permisos : permisos } ,
        dataType: 'json',
        beforeSend: function () 
        {
          $('#btnGuardarPermisoRol').prop('disabled', true);
        },
        success:  function (respuesta) 
        {
          if (respuesta.status) {
            Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: 'Se guardo correctamente.',
              showConfirmButton: false,
              timer: 1500
            });
            $('#btnGuardarPermisoRol').prop('disabled', false);
          } else {
              alert('Error de rol');
          }
        },
        error: function()
        {
          alert('Error interno');
        }
      });
    }
  });

  $("#cmbUsuariosMed").change(function(){
    var usuario = $(this);

    if($(this).val() != '')
    {
      $.ajax({
        url:   '<?=base_url('admin/medicoUsuario');?>/' + usuario.val(),
        type:  'GET',
        dataType: 'json',
        beforeSend: function () 
        {
          $("#cmbMedico option").each(function(){
              $("#cmbMedico option[value='" +  $(this).val() + "']").attr("selected", false);
          });
        },
        success:  function (respuesta) 
        {
          if (respuesta > 0) {
            $('#cmbMedico').val(respuesta);
          } else {
            $("#cmbMedico option[value='']").attr("selected", true);
          }
        },
        error: function()
        {
          alert('Error interno');
        }
      });
    } else {
      $("#cmbMedico option[value='']").attr("selected", true);
    }
  });

  $('#btnAsginarMedicoUser').click(function() {
    var usuario = $('#cmbUsuariosMed').val();
    var medico = $('#cmbMedico').val();

    if(usuario != '' && medico != '')
    {
      $.ajax({
        url:   '<?=base_url('admin/gMedicoUsuario');?>',
        type:  'POST',
        data: { usuario: usuario, medico : medico } ,
        dataType: 'json',
        beforeSend: function () 
        {
          $('#btnAsginarMedicoUser').prop('disabled', true);
        },
        success:  function (respuesta) 
        {
          if (respuesta.status) {
            Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: 'Se guardo correctamente.',
              showConfirmButton: false,
              timer: 1500
            });
            $('#btnAsginarMedicoUser').prop('disabled', false);
          } else {
              alert('Error de usuario');
          }
        },
        error: function()
        {
          alert('Error interno');
        }
      });
    }
  });
</script>
</body>
</html>

