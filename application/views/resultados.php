<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
<base href="consulta">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SBCMedic | Resultados</title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo  base_url('img/favicon.ico');?>"/>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php $this->load->view("styles"); ?>
  <style>
    .custom-file-input ~ .custom-file-label::after {
        content: "Elegir...";
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
        <span style="vertical-align:middle;"> <span style="margin-left: 10px;font-size: 2.4rem;font-weight:bold;color: #004663;vertical-align:middle;  "> Resultados<span></span>
      </li>
      
    </ul>
    <?php $this->load->view("logout"); ?>  
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
      <?php if($rol == 1 || $rol == 4 || $rol == 2 || $rol == 7 || $rol == 5) { ?>
      <form name="frmBusqueda" method="POST" action="<?php echo base_url("resultados");?>">
      <div class="row mb-2">
        <div class="col">
          <h3><strong>Paciente</strong></h3>
        </div>
        <div class="col">
          <select id="cmbUsuario" name="cmbUsuario" class="searchClient form-control select2" style="width: 100%;" required></select>
        </div>
        <div class="col">
          <button type="submit" class="btn btn-primary btn-md btn-block"><i class="fa fa-search"></i> Consultar</button>
        </div>
      </div>
      </form>
      <?php } ?>
       <div class="row">
          <div class="col">
            <table class="table table-hover">
              <thead>
              <tr class="table-success">
                  <th colspan="7"><h3><strong>Examenes realizados en Laboratorio</strong></h3></th>
                </tr>
                <tr class="table-active">
                  <th>#</th>
                  <th scope="col">FECHA EXÁMEN</th>
				  <th scope="col">CÓDIGO</th>
                  <th scope="col">TIPO</th>
                  <th scope="col">EXÁMEN</th>
                  <th scope="col">STATUS</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              <?php
                  $codigoI = null;
                  foreach ($resultados as $clave => $valor){
                    $clave++; 
                ?>
                  <tr>
                    <td><?php echo $clave;?></td>
                    <td><?php echo date("d/m/Y",strtotime($valor->fechaExamen)); ?></td>
					<td><?php echo $valor->codigo_interno;?></td>
                    <td><strong><?php echo $valor->tipo;?></strong></td>
                    <td style="color:blue"><strong><?php echo $valor->examen;?></strong></td>
                    <td><span class="badge badge-<?php echo ($valor->estado == "2")? "success": "warning";?>"><?php echo ($valor->estado == "2")? "Válidado": "En Proceso";?></span></td>
                    <td align="center">
                    <?php if ($valor->estado > 1 and $valor->codigo_interno != $codigoI) { ?>
                      <a class="btn btn-success" href='<?php echo  base_url("pdfinforme/$valor->codigo_interno/$valor->idUsuario/$valor->idExamen");?>' role="button" target="_blank" title="Ver Pdf">Ver Resultado <i class="far fa-file-pdf"></i></a>
                    <?php } ?>
                    </td>
                  </tr>
                <?php
                    $codigoI =  ($valor->estado > 1)?  $valor->codigo_interno : "";
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.row -->

        
 



        <div class="row">
          <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">OTROS RESULTADOS: <u>Adjuntar(subir) mi resultado</u></h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="<?php echo base_url('subirResultado');?>" enctype="multipart/form-data" id="frmFile">
                  <div class="card-body">
                  <?php if ($rol != 3) { ?>
                    <div class="form-group">
                      <label for="descripcion">Paciente</label>
                      <select class="form-control searchClient" name="cliente" style="width: 100%;"></select>
                    </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <input type="text" class="form-control" name="descripcion">
                      </div>
                      <div class="form-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" id="customFileLang" name="uploadedFile" lang="es" required accept="image/jpeg,image/jpg,image/png,application/pdf,application/doc,application/ms-doc,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                          <label class="custom-file-label" for="customFileLang">Seleccionar Archivo</label>
                        </div>
                      </div>
                      <button type="submit" id="btnSubir" class="btn btn-success btn-lg btn-block">SUBIR RESULTADO</button>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <p style="font-size: 20px;text-align: center;color: #ef233c;">Si no puedes subir tu resultado, enviarlo a <strong style="text-decoration: underline">resultados@sbcmedic.com</strong> indicando tus nombres y apellidos.</p>
                  </div>
                </form>
              </div>
              <!-- /.card -->
          </div>
        </div> 
        <?php if($registrosSubidos->num_rows() >0 ){ ?>
        <div class="row">
          <div class="col-12">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <?php if ($rol != 3) { ?>
                  <th scope="col">Paciente</th>
                  <?php } ?>
                  <th scope="col">Descripción</th>
                  <th scope="col">Archivo</th>
                  <th scope="col">Fecha Creación</th>
				  <?php if($eliminarRegistroResultados == "eliminar_registro_resultadosUpload") { ?>
				  <th scope="col"></th>
				  <?php } ?>
                </tr>
              </thead>
              <tbody>
              <?php
                  foreach ($registrosSubidos->result() as $clave => $row) {
                    $clave++;
                ?>
                  <tr>
                    <td><?php echo $clave; ?></td>
                    <?php if ($rol != 3) { ?>
                    <td><?php echo $row->paciente; ?></td>
                    <?php } ?>
                    <td><?php echo $row->descripcion; ?></td>
                    <td><a href="<?php echo base_url('files/resultados/'). $row->nombreArchivo;?>" target="_blank"><img src="<?php if(substr($row->nombreArchivo, -3) == "pdf") echo base_url('img/pdf-32.png');  else echo base_url('files/resultados/'). $row->nombreArchivo;?>" alt="resultadoImagen"  width="30px" height="30px"></a> <?php echo $row->nombreArchvioShow; ?></td>
                    <td><?php echo date("d/m/Y H:m",strtotime($row->fechaCreacion)); ?></td>
					<?php if($eliminarRegistroResultados == "eliminar_registro_resultadosUpload") { ?>
					<td><button type="button" id="delete<?php echo $row->id; ?>" class="btn btn-danger" title="Eliminar Registro" onclick="eliminar_registro('<?php echo $row->id; ?>')">Eliminar</button></td>
					<?php } ?>
                  </tr>
                <?php
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
        <?php } ?>

      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
      <!-- /.content-wrapper -->
     
  <footer class="main-footer bg_transparent">
    <div class="float-right d-none d-sm-block">
      <b>Versión</b> <?php echo $version["version"];?>
    </div>
    <strong>Copyright &copy; 2020 <a href="javascript:void(0)">SBCMedic</a>.</strong> Derechos Reservados.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<?php $this->load->view("scripts"); ?>
<!-- Select2 -->
<script src="<?php echo base_url('plugins/select2/js/select2.full.min.js');?>"></script>

<script>
   $('.custom-file-input').on('change', function() { 
    let fileName = $(this).val().split('\\').pop(); 
    $(this).next('.custom-file-label').addClass("selected").html(fileName); 
  });
  
  $('.searchClient').select2({
    language: "es",
    placeholder: 'Seleccionar paciente',
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

  var frm = $('#frmFile');
  $.validator.setDefaults({
    submitHandler: function () {
      
      Swal.fire({
      title: '¿Estás seguro de subir el resultado?',
      text: 'Una vez confirmado, no se podrá revertir.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Seguro!',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      
      if (result.value) {
        var formData = new FormData($("#frmFile")[0]);

        $.ajax({
          type: frm.attr('method'),
          url: frm.attr('action'),
          data: formData,
          cache:false,
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
                  window.location.replace("<?php echo base_url('resultados');?>");
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

  
  $('#frmFile').validate({
      rules: {
        uploadedFile: {
          required: true
        },
        cliente: {
          required: true
        }
      },
      messages: {
        uploadedFile: {
          required: "Seleccione un archivo"
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
	
    function eliminar_registro(codigo) {
      Swal.fire({
        title: '¿Esta seguro de Eliminar este registro?',
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
            url: "<?php echo base_url("delete-record-result");?>",
            data : { codigo: codigo },
            beforeSend: function () 
            {            
              $("#delete"+ codigo).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
              $("#delete"+ codigo).addClass("btn btn-primary");
              $("#delete"+ codigo).prop('disabled', true);
            },
            success: function(data) {
              if (data.status) {
                Swal.fire({
                  icon: 'success',
                  timer: 5000,
                  title: 'Respuesta exitosa',
                  text: data.message,
                  onClose: () => {
                    window.location.reload();
                  }
                })
              } else {
                Swal.fire({
                  icon: 'error',
                  timer: 5000,
                  title: 'Error de validación',
                  text: data.message
                })
              }
            },
            error: function(data) {
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
    }	
</script>
</body>
</html>