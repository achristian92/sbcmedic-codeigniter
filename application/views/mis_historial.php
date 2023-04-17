<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
<base href="consulta">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SBCMedic | Mis Historial</title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo  base_url('img/favicon.ico');?>"/>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- pace-progress -->
  <link rel="stylesheet" href="plugins/pace-progress/themes/black/pace-theme-flat-top.css">

  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">



  <!-- adminlte-->
  <link rel="stylesheet" href="css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
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

        label {
          color: rgba(46,175,109,1);
        }
      
        .rate {
          float: left;
          height: 46px;
          padding: 0 10px;
        }
        .rate:not(:checked) > input {
            position:absolute;
            top:-9999px;
        }
        .rate:not(:checked) > label {
            float:right;
            width:1em;
            overflow:hidden;
            white-space:nowrap;
            cursor:pointer;
            font-size:30px;
            color:#ccc;
        }
        .rate:not(:checked) > label:before {
            content: '★ ';
        }
        .rate > input:checked ~ label {
            color: #ffc700;    
        }
        .rate:not(:checked) > label:hover,
        .rate:not(:checked) > label:hover ~ label {
            color: #deb217;  
        }
        .rate > input:checked + label:hover,
        .rate > input:checked + label:hover ~ label,
        .rate > input:checked ~ label:hover,
        .rate > input:checked ~ label:hover ~ label,
        .rate > label:hover ~ input:checked ~ label {
            color: #c59b08;
        }

        .select2-container {
          width: 100% !important;
          padding: 0;
        }

  </style>
</head>
<body class="hold-transition sidebar-mini sidebar-collapse" style="background-image: url(img/fondo_body.png); height: 100%;  background-position: right;  background-repeat: no-repeat;  ">
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
        <span style="vertical-align:middle;  "><span style="margin-left: 10px;font-size: 2.4rem;font-weight:bold;color: #004663;vertical-align:middle;  "> Mi Historial<span></span>
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

      <?php if($consultarCita == "filtro_busqueda_cita") { ?>
      <form id="quickForm" method="post" action="<?php echo base_url('mihistorial');?>">
        <div class="row">
          <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
            <div class="form-group">
              <select id="cmbmedico" name="cmbmedico" class="form-control select2" style="width: 100%;">
                <option value="">Profesional</option>                    
                <?php foreach ($medicos as $medico) { ?>
                    <option value="<?=$medico->idDoctor;?>"><?=$medico->nombreMedico;?></option>                    
                  <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
            <div class="form-group">
              <select class="form-control searchClient" name="cliente" style="width: 100%;"></select>
            </div>
          </div>
          <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
            <div class="form-group">
              <input type="date" class="form-control" name="fecha" value="">
            </div>
          </div>
          <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-block" title="Buscar..."><i class="fa fa-search"></i> Consultar</button>
            </div>
          </div>
        </div>
        </form>
        <?php } ?>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <table id="historico" class="table table-bordered table-hover">
                  <thead>
                  <tr>
				    <th>NroCita</th>
                    <th>Tipo Cita</th>
				          	<?php if ($rol != 3) { ?><th>Paciente</th><?php } ?>
                    <th>Fecha Cita</th>
                    <th>Especialidad</th>
                    <?php //if ($rol != 2) { ?><th>Profesional</th><?php //} ?>
					<th>MotivoCita</th>
                    <th>Detalle</th>
                     
                    <?php if($actualizarHClinica == "actualizar_historial_clinica" and $rol != 3) { ?>
                      <th>Historia Clínica</th>
                    <?php } ?>
                    <th>Recetas/Exámenes</th>
                    <?php if($descargarHClinica == "descargar_historia_clinica" and $rol != 3) { ?>
                    <th>Historia Clínica</th>
                    <?php } ?>
                    
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                      $caracter = array(" ", "  ", "   ");
                      foreach ($resultados as $clave => $valor){
                        $clave++;
                        
                        $nombrePdf = strtolower($valor["tipoCita"])."-".strtolower(str_replace($caracter, "_",$valor["paciente"]))."-".strtolower(str_replace($caracter, "_", $valor["especialidad"]))."-".date("dmY",strtotime($valor["fechaCita"]));
                    ?>
                      <tr>
						<td><?php echo str_pad($valor["idCita"], 6, '0', STR_PAD_LEFT); ?></td>
                        <td></strong><?php echo $valor["tipoCita"];?></td>
						            <?php if ($rol != 3) { ?><td style="background-color: #e9edc9;"><strong><?php echo $valor["paciente"];?></strong></td><?php } ?>
                        <td style="color: #02c39a;"><strong><?php echo date("d/m/Y",strtotime($valor["fechaCita"]));?></strong></td>
                        <td style="background-color: #e9edc9;"><strong><?php echo $valor["especialidad"];?></strong></td>
                        <?php //if ($rol != 2) { ?><td style="background-color: #bde0fe;"><?php echo $valor["medico"];?></td><?php //} ?>
						<td></strong><?php echo $valor["motivoTipoCita"];?></td>
                        <td style="width:8%;">
                          
                          <a href="#" data-toggle="modal" data-target="#modal-detalle" data-tipo="<?php echo $valor["tipoCita"];?>" data-fecha="<?php echo date("d/m/Y",strtotime($valor["fechaCita"]));?>" data-hora="<?php echo $valor["horaCita"];?>" data-especialidad="<?php echo $valor["especialidad"];?>" data-medico="<?php echo $valor["medico"];?>" data-motivo="<?php echo $valor["motivo"];?>" data-direccion="<?php echo $valor["address"];?>" data-fono="<?php echo $valor["phone"];?>" data-email="<?php echo $valor["email"];?>" data-document="<?php echo $valor["document"];?>"><i class="fas fa-address-card" title="Ver detalle"></i></a>
                        </td>
                      
                        <?php if($actualizarHClinica == "actualizar_historial_clinica" and $rol != 3) { ?>
                          <td style="text-align: center;">
							<button type="button" class="btn btn-outline-info" title="Actualizar Historial" onclick="ventana_cita('<?php echo $valor["idCita"];?>', '<?php echo $valor["idUsuario"];?>', '<?php echo $valor["readonly"];?>')"><i class="fa fa-save" aria-hidden="true"></i></button>
							
						</td>
                        <?php } ?>
                        
                        <td style="text-align:center;">
                          <a href="<?php echo base_url('downloadPdf/'). $valor["idCita"]."/$nombrePdf"."/".$valor["idUsuario"];?>" target="_blank"><img src="<?php echo base_url("img/pdf-32.png");?>" alt="pdf-recetas" title="Descargar recetas, exámenes médicos"></a>
                        </td>
                        <?php if($descargarHClinica == "descargar_historia_clinica" and $rol != 3) { ?>
                        <td style="text-align:center;">
                          <a href="<?php echo base_url('downloadPdfHistorial/'). $valor["idCita"]."/".$valor["idUsuario"];?>" target="_blank"><img src="<?php echo base_url("img/pdf-32.png");?>" alt="pdf-recetas" title="Descargar historia clínica"></a>
                        </td>
                        <?php } ?>
 
                      </tr>
                    <?php
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

             
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <?php if($this->input->post("cliente") and ($rol == 1 || $rol == 4 || $rol == 2)){ ?>
        <div class="row">
          <div class="col">
            <table class="table table-hover">
              <thead>
              <tr class="table-success">
                  <th colspan="6"><h3><strong>Examenes realizados</strong></h3></th>
                </tr>
                <tr class="table-active">
                  <th>#</th>
                  <th scope="col">FECHA EXAMEN</th>
                  <th scope="col">TIPO</th>
                  <th scope="col">EXAMEN</th>
                  <th scope="col">STATUS</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              <?php
                  $codigoI = null;
                  foreach ($resultadosLab as $clave => $valor){
                    $clave++; 
                ?>
                  <tr>
                    <td><?php echo $clave;?></td>
                    <td><?php echo date("d/m/Y",strtotime($valor->fechaExamen)); ?></td>
                    <td><strong><?php echo $valor->tipo;?></strong></td>
                    <td style="color:blue"><strong><?php echo $valor->examen;?></strong></td>
                    <td><span class="badge badge-<?php echo ($valor->estado == "2")? "success": "warning";?>"><?php echo ($valor->estado == "2")? "Válidado": "En Proceso";?></span></td>
                    <td align="center">
                    <?php if ($valor->estado == 2 and $valor->codigo_interno != $codigoI) { ?>
                      <a class="btn btn-success" href='<?php echo  base_url("pdfinforme/$valor->codigo_interno/$valor->idUsuario/$valor->idExamen");?>' role="button" target="_blank" title="Ver Pdf">Ver Resultado <i class="far fa-file-pdf"></i></a>
                    <?php } ?>
                    </td>
                  </tr>
                <?php
                    $codigoI = $valor->codigo_interno;
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.row -->
        <?php } ?>
      </div>


      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  
        <!-- /.content-wrapper -->
        <div class="modal fade" id="modal-detalle">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header text-center">
              <h4 class="modal-title w-100">Detalle de la Cita</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row justify-content-md-center p-2">
                <div class="col col-lg-8">
                  <strong>Tipo de Cita:</strong> <span id="tipoCita"></span>
                </div>
              </div>
              <div class="row justify-content-md-center p-2">
                <div class="col col-lg-8">
                  <strong>Dirección:</strong> <span id="direccion"></span>
                </div>
              </div>
			  <div class="row justify-content-md-center p-2">
                <div class="col col-lg-8">
                  <strong>Email:</strong> <span id="email"></span>
                </div>
              </div>
              <div class="row justify-content-md-center p-2">
                <div class="col col-lg-8">
                  <strong>Nro Documento:</strong> <span id="dni"></span>
                </div>
              </div>
			  <div class="row justify-content-md-center p-2">
                <div class="col col-lg-8">
                  <strong>Teléfono:</strong> <span id="fono"></span>
                </div>
              </div>
              <div class="row justify-content-md-center p-2">
                <div class="col col-lg-8">
                <strong>Fecha de Cita:</strong> <span id="fechaCita"></span>
                </div>
              </div>
              <div class="row justify-content-md-center p-2">
                <div class="col col-lg-8">
                <strong>Hora de Cita:</strong> <span id="horaCita"></span>
                </div>
              </div>
              <div class="row justify-content-md-center p-2">
                <div class="col col-lg-8">
                <strong>Especialidad:</strong> <span id="especialidad"></span>
                </div>
              </div>
              <div class="row justify-content-md-center p-2">
                <div class="col col-lg-8">
                <strong>Profesional:</strong> <span id="medico"></span>
                </div>
              </div>
              <div class="row justify-content-md-center p-2">
                <div class="col col-lg-8">
                <strong>Observación Cita:</strong> <span id="motivo" class="text-info"></span>
                </div>
              </div>         
            </div>
            <div class="modal-footer text-center w-100">
              <div class="container">
                <div class="row w-100">
                  <div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4 col-sm-6 offset-sm-3 col-xl-3 offset-xl-5 col-6 offset-3"><button type="button" class="btn btn-outline-info" data-dismiss="modal" title="Cerrar ventana">Cerrar</button></div>
                </div>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

      <!-- /.content-wrapper -->
      <div class="modal fade" id="modal-warning">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tu opinión nos importa ¡Califíquenos!</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form role="form" id="formCalificacion" method="post" action="<?php echo base_url('gCalificacion');?>">
                <div class="form-group">
                  <div class="rate"><strong>Calificación general*</strong> 
                    <input type="radio" id="star5" name="rate" value="5"/>
                    <label for="star5" title="Excelente">5 stars</label>
                    <input type="radio" id="star4" name="rate" value="4"/>
                    <label for="star4" title="Bueno">4 stars</label>
                    <input type="radio" id="star3" name="rate" value="3"/>
                    <label for="star3" title="Normal">3 stars</label>
                    <input type="radio" id="star2" name="rate" value="2"/>
                    <label for="star2" title="Aceptable">2 stars</label>
                    <input type="radio" id="star1" name="rate" value="1"/>
                    <label for="star1" title="Insuficiente">1 star</label>
                  </div>
                  <label id="mensaje"></label>
                </div>
                <div class="form-group">
                  <textarea class="form-control" name="txtObservacion" id="txtObservacion" rows="3" placeholder="Observación" maxlength="200"></textarea>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
              <input type="hidden" name="idMedico" id="idMedico">
              <input type="hidden" name="idCita" id="idCita">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="submit" id="btnGuardar" class="btn btn-primary">Guardar Calificación</button>
            </div>
          </div>
          <!-- /.modal-content -->
          </form>
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->


      <div class="modal fade" id="modal-updateCita">
        <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="frmUpdateCita">
          <div class="modal-content">
            <div class="modal-body">
              <div class="row justify-content-md-center mt-2">
                <div class="col text-info">
                 <h3>ANAMNESIS</h3>
                </div>
              </div>
              <div class="row justify-content-md-center">
                <div class="col col-lg-4">
                  <label for="tiempoEnfermedad">Tiempo de Enfermedad</label>
                    <input id="tiempoEnfermedad" name="tiempoEnfermedad" class="form-control">
                  </div>
                <div class="col col-lg-8">
                  <label for="relato">Relato</label>
           
				  <textarea id="relato" name="relato" class="form-control" cols="2" rows="2"></textarea>
                </div>
              </div> 
              <div class="row justify-content-md-center">
                  <div class="col col-lg-10">
                    <label for="fbiologicas">Funciones Biológicas</label>
                    <input id="fbiologicasComentario" id="fbiologicasComentario" name="fbiologicasComentario" class="form-control">
                  </div>
                  <div class="col col-lg-2">
                    <label for="pa">Normales</label>
                    <div class="custom-control custom-checkbox">
                      <div class="icheck-primary d-inline">
                        <input type="checkbox" name="normales" id="normales"  value="1">
                        <label for="normales">
                        </label>
                      </div>
                    </div>
                  </div>
              </div>
                            
              <div class="row justify-content-md-center">
                  <div class="col col-lg-2">
                    <label for="fbiologicas">Antecedes Patológicos</label>
                    <input type="checkbox" name="antecedentePatologico" id="antecedentePatologico" checked data-bootstrap-switch data-on-text="SI&nbsp;&nbsp;&nbsp;" data-off-text="NO">
                  </div>

                  <div class="col col-lg-2">
                    <label for="pa">Dislipidemia</label>
                    <div class="custom-control custom-checkbox">
                      <div class="icheck-primary d-inline">
                        <input type="checkbox" name="dislipidemia" id="dislipidemia"  value="1">
                        <label for="dislipidemia">
                        </label>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col col-lg-2">
                    <label for="pa">Diabetes</label>
                    <div class="custom-control custom-checkbox">
                      <div class="icheck-primary d-inline">
                        <input type="checkbox" name="diabetes" id="diabetes"  value="1">
                        <label for="diabetes">
                        </label>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col col-lg-2">
                    <label for="pa">HTA</label>
                    <div class="custom-control custom-checkbox">
                      <div class="icheck-primary d-inline">
                        <input type="checkbox" name="hta" id="hta"  value="1">
                        <label for="hta">
                        </label>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col col-lg-2">
                    <label for="pa">Asma</label>
                    <div class="custom-control custom-checkbox">
                      <div class="icheck-primary d-inline">
                        <input type="checkbox" name="asma" id="asma"  value="1">
                        <label for="asma">
                        </label>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col col-lg-2">
                    <label for="pa">Gastritis</label>
                    <div class="custom-control custom-checkbox">
                      <div class="icheck-primary d-inline">
                        <input type="checkbox" name="gastritis" id="gastritis"  value="1">
                        <label for="gastritis">
                        </label>
                      </div>
                    </div>
                  </div>

              </div> 
 
              <div class="row justify-content-md-center mt-2">
                <div class="col col-lg-4 form-inline">
                  <label for="otros">Otros&nbsp;</label>
                  <input type="text" id="otrosap" name="otrosap"  class="form-control" style="width:70%">
                </div>
                <div class="col col-lg-8 form-inline">
                  <label for="otros">Antecedentes familiares</label>
                  &nbsp;<input type="text" id="otrosaf" name="otrosaf" class="form-control">&nbsp;
                  <input type="checkbox" name="antecedesFamiliar" id="antecedesFamiliar" checked data-bootstrap-switch data-on-text="SI&nbsp;&nbsp;&nbsp;" data-off-text="NO">
                  
                </div>
              </div> 

              <div class="row justify-content-md-center mt-2">
                <div class="col col-lg-3">
                  <label for="nombre">Reacción adversas medicamentosas</label>
                    <input type="checkbox" id="relacionesam" name="relacionesam" checked data-bootstrap-switch data-on-text="SI&nbsp;&nbsp;&nbsp;" data-off-text="NO">
                  </div>
                  <div class="col col-lg-5">
                    <label for="medicamentos">Medicamentos</label>
                    <input type="text" id="medicamentos" name="medicamentos" class="form-control">
                  </div>
                  <div class="col col-lg-4">
                    <label for="otros">Otros</label>
                    <input type="text" id="otrosMedicamentos" name="otrosMedicamentos" class="form-control">
                  </div>
              </div> 
              <div class="row justify-content-md-center mt-2">
                <div class="col-12">
                  <label for="egenarl">Medicamentos habituales Actualmente:</label>
                  <input id="medicamentosHabituales" name="medicamentosHabituales"  class="form-control">
                </div>
              </div>
              <div class="row justify-content-md-center mt-2">
                <div class="col text-info">
                 <h3>EXAMEN FÍSICO</h3>
                </div>
              </div>

              <div class="row justify-content-md-center">
                <div class="col col-lg-3">
                  <label for="pa">P.A</label>
                    <input id="pa" name="pa" class="form-control">
                  </div>
                <div class="col col-lg-2">
                  <label for="fc">F.C</label>
                  <input id="fc" name="fc" class="form-control">
                </div>
                <div class="col col-lg-2">
                  <label for="fr">F.R</label>
                  <input id="fr"  name="fr" class="form-control">
                </div>
                <div class="col col-lg-2">
                  <label for="tt">T<sup>°</sup></label>
                  <input id="tt" name="tt" class="form-control">
                </div>
                <div class="col col-lg-3">
                  <label for="sato">Sato2</label>
                  <input id="sato" name="sato" class="form-control">
                </div>
 
              </div> 

              <div class="row justify-content-md-center mt-2">
                <div class="col-12">
                  <label for="egenarl">Examen General</label>
                  <input id="egenarl" name="egenarl" class="form-control">
                </div>
              </div>

              <div class="row justify-content-md-center mt-2">
                <div class="col text-info">
                 <h3>DIAGNÓSTICO</h3>
                </div>
              </div>

              <div class="row justify-content-md-center">
                <div class="col ">
                    <select name="diagnostico[]" id="diagnostico" class="form-control searchCie" multiple="multiple"></select>
                  </div>
                 
              </div> 

              <div class="row justify-content-md-center mt-2">
                <div class="col text-info">
                 <h3>PLAN DE TRATAMIENTO</h3>
                </div>
              </div>

              <div class="row justify-content-md-center mt-1">
                <div class="col col-lg-12">
                    <input name="planTratamiento[]" id="planTratamiento1" class="form-control" placeholder="1.-">
                  </div>
              </div> 
              <div class="row justify-content-md-center mt-1">
                <div class="col col-lg-12">
                    <input name="planTratamiento[]" id="planTratamiento2" class="form-control" placeholder="2.-">
                  </div>
              </div>
              <div class="row justify-content-md-center mt-1">
                <div class="col col-lg-12">
                    <input name="planTratamiento[]" id="planTratamiento3" class="form-control" placeholder="3.-">
                  </div>
              </div>
              <div class="row justify-content-md-center mt-1">
                <div class="col col-lg-12">
                    <input id="observaciones" name="observaciones" class="form-control" placeholder="Observaciones" maxlength="150">
                  </div>
              </div> 

              <div class="row justify-content-md-center mt-2">
                <div class="col text-info">
                 <h3>RECETAS</h3>
                </div>
              </div>

              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <table  id="lista_recetas">
                      <thead>
                        <tr>
                          <td style="color:#28a745;"><strong>Nombre</strong></td>
                          <td style="color:#28a745;"><strong>Presentación</strong></td>
						  <td style="color:#28a745;"><strong>Cantidad</strong></td>
                          <td style="color:#28a745;"><strong>Vía</strong></td>
                          <td style="color:#28a745;"><strong>Dosificación</strong></td>
                          <td style="color:#28a745;"><strong>Tiempo tratamiento</strong></td>
                          <td style="color:#28a745;"><button type="button" class="btn btn-outline-success button_agregar_receta" title="Agregar registro"> <i class="fas fa-plus"></i></button></td>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><input name="nombre[]" id="nombre" class="form-control"></td>
                          <td><input name="presentacion[]" id="presentacion" class="form-control"></td>
						  <td><input name="cantidad[]" id="cantidad" maxlength="2" class="form-control"></td>
                          <td><input name="via[]" id="via" class="form-control"></td>
                          <td><input name="dosificacion[]" id="dosificacion" class="form-control"></td>
                          <td><input name="tiempot[]" id="tiempot" class="form-control"></td>
                          <td><button type="button" class="btn btn-outline-danger button_eliminar_receta" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="row justify-content-md-center mt-4">
                <div class="col text-info">
                  <h3>EXÁMENES AUXILIARES</h3>
                </div>
              </div>

              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <table  id="lista_examenAux" width="100%" class="table">
                      <thead>
                        <tr>
                          <td style="color:#28a745;"><strong>Nombre</strong></td>
                          <td style="color:#28a745;"><strong>Especificaciones</strong></td>
                          <td style="color:#28a745;"><strong>Especialidad</strong></td>
                          <td style="color:#28a745;"><button type="button" class="btn btn-outline-success button_agregar_examenAux" title="Agregar registro"> <i class="fas fa-plus"></i></button></td>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><input name="nombreEm[]" id="nombreEm" class="form-control"></td>
                          <td><input name="especificaciones[]" id="especificaciones" class="form-control"></td>
                          <td><input name="especialidad[]" id="especialidades" class="form-control"></td>
                          <td><button type="button" class="btn btn-outline-danger button_eliminar_examenAux" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="row justify-content-md-center mt-2">
                
                <div class="col-lg-8 offset-lg-2 text-info">
                <h3>Recomendaciones / Indicaciones</h3>
                </div>
                </div>

                <div class="row justify-content-md-center">
                  
                  <div class="col col-lg-12">
                    <div class="form-group">
                      <textarea name="recomendaciones" id="recomendaciones" cols="30" rows="3" class="form-control"></textarea>
                    </div>
                  </div>
                  
                </div>
              </div>

              <div class="modal-footer text-center w-100">
                <div class="container">
                  <div class="row w-100">
                    <div class="col-xl-6 offset-xl-3 col-lg-6 offset-lg-3 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-12">
                      <input type="hidden" name="idCitaUp" id="idCitaUp">
                      <input type="hidden" name="idUsuario" id="idUsuario">
					  <?php if($actualizarHClinica == "actualizar_historial_clinica" and ($rol == 1 || $rol == 2 || $rol == 7)) { ?>
                      <button type="button" id="btnUpdateCita" class="btn btn-outline-info" title="Guardar cita">Actualizar Historial</button>
					  <?php } ?>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Cerrar">Cancelar</button>
                    </div>
                  </div>
                </div>
            </div>
          </div>
          <!-- /.modal-content -->

        </form>
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
  
  
  
  </div>





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
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/jquery-validation/additional-methods.min.js"></script>
<!-- Select2 -->
<script src="<?php echo base_url('plugins/select2/js/select2.full.min.js');?>"></script>
<!-- Bootstrap Switch -->
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

<script>
  //recetas
	var tbodyReceta = $('#lista_recetas tbody');
	var fila_contenidoReceta = tbodyReceta.find('tr').first().html();
	//Agregar fila nueva.
	$('#lista_recetas .button_agregar_receta').click(function(){
		var fila_nuevaReceta = $('<tr></tr>');
		fila_nuevaReceta.append(fila_contenidoReceta);
		tbodyReceta.append(fila_nuevaReceta);
	});

	//Eliminar fila.
	$('#lista_recetas').on('click', '.button_eliminar_receta', function(){
    var nFilas = $("#lista_recetas tr").length;
	  
    if(nFilas > 2) $(this).parents('tr').eq(0).remove();
	});

  //examen medico
  var tbody = $('#lista_examenAux tbody');
	var fila_contenido = tbody.find('tr').first().html();
	//Agregar fila nueva.
	$('#lista_examenAux .button_agregar_examenAux').click(function(){
 
		var fila_nueva = $('<tr></tr>');
		fila_nueva.append(fila_contenido);
		tbody.append(fila_nueva);
	});
	//Eliminar fila.
	$('#lista_examenAux').on('click', '.button_eliminar_examenAux', function(){
    var nFilas = $("#lista_examenAux tr").length;
	  
    if(nFilas > 2) $(this).parents('tr').eq(0).remove();
	});

  $('.select2').select2();
  
  $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
  });

  $('input[name="antecedentePatologico"]').on('switchChange.bootstrapSwitch', function(e, data){
    if(data) {
      $("#dislipidemia").prop('disabled', false);
      $("#diabetes").prop('disabled', false);
      $("#hta").prop('disabled', false);
      $("#asma").prop('disabled', false);
      $("#gastritis").prop('disabled', false);
    } else {
      $("#dislipidemia").prop('checked', false);
      $("#diabetes").prop('checked', false);
      $("#hta").prop('checked', false);
      $("#asma").prop('checked', false);
      $("#gastritis").prop('checked', false);

      $("#dislipidemia").prop('disabled', true);
      $("#diabetes").prop('disabled', true);
      $("#hta").prop('disabled', true);
      $("#asma").prop('disabled', true);
      $("#gastritis").prop('disabled', true);
    }
  });

  $('input[name="ckbBolFac"]').on('switchChange.bootstrapSwitch', function(e, data){
      $.ajax({
      type: 'post',
      url: "<?php echo base_url("gBolFacStatus");?>",
      data: { idCita : $(this).attr('data'), status : data },
      success: function (respuesta) {
        if (!respuesta) alert('Error! No se guardo.');
      },
      error: function (respuesta) {
        if (respuesta) alert('Error! No se guardo.' + respuesta);
      }
    });
  });

  var frm = $('#formCalificacion');
  $.validator.setDefaults({
    submitHandler: function () {
    
      $("#btnGuardar").attr('disabled',true);
        Swal.fire({
          title: '¿Esta seguro de guardar la calificación?',
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
              type: frm.attr('method'),
              url: frm.attr('action'),
              data: frm.serialize(),
              success: function (data) {
                $('#modal-warning').modal('hide');
                if(data.status){  
                  Swal.fire({
                    icon: 'success',
                    timer: 5000,
                    title: 'Respuesta exitosa',
                    text: data.message,
                    onClose: () => {
                      window.location.replace("<?php echo base_url('mihistorial');?>");
                    }
                  })
                }else{
                  $("#btnGuardar").attr('disabled',false);
                  Swal.fire({
                    icon: 'error',
                    timer: 5000,
                    title: 'Error de validación',
                    text: data.message
                  })
                }
              },
              error: function (data) {
                $("#btnGuardar").attr('disabled',false);
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
         
        $("#btnGuardar").attr('disabled',false);
      
    }
  });

  $('#formCalificacion').validate({
    rules: {
      rate: {
          required: true
        }
      },
      messages: {
        rate: {
          required: 'Calificación es obligatorio'
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

  $("#star1").click(function(){
    $("#mensaje").text('Insuficiente');
  });
  
  $("#star2").click(function(){
    $("#mensaje").text('Aceptable');
  });

  $("#star3").click(function(){
    $("#mensaje").text('Normal');
  });

  $("#star4").click(function(){
    $("#mensaje").text('Bueno');
  });

  $("#star5").click(function(){
    $("#mensaje").text('Excelente');
  });

  $('#historico').DataTable({
    "paging": true,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
	"order": [[ 4, "desc" ]],
    "info": true,
    "autoWidth": false,
    "responsive": true,
    //"order": [[ 5, "desc" ]],
    "language": {
            "url": "<?php echo base_url("plugins/datatables-bs4/js/Spanish.json");?>"
    },
    /* "columnDefs": [
    { "width": "100px", "targets": 6 }
    ] */
  });
    
   $('#modal-detalle').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var tipoCita = button.data('tipo')
    var fechaCita = button.data('fecha')
    var horaCita = button.data('hora')
    var especialidad = button.data('especialidad')
    var medico = button.data('medico')
    var motivo = button.data('motivo')
    var fono = button.data('fono')
	var email = button.data('email')
    var direccion = button.data('direccion')
	var document = button.data('document')
    
    $('#tipoCita').text(tipoCita);
    $('#fechaCita').text(fechaCita);
    $('#horaCita').text(horaCita);
    $('#especialidad').text(especialidad);
    $('#medico').text(medico);
    $('#motivo').text(motivo);
    $('#fono').text(fono);
	$('#email').text(email);
    $('#direccion').text(direccion);
	$('#dni').text(document);
  });

  $('#modal-warning').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var idMedico = button.data('id')
    var idCita = button.data('cita')
    $('#idMedico').val(idMedico);
    $('#idCita').val(idCita);
  });
 
  $('#modal-warning').on('hide.bs.modal', function (event) {
    $('#txtObservacion').val('');
    $('#star1').prop( "checked", false );
    $('#star2').prop( "checked", false );
    $('#star3').prop( "checked", false );
    $('#star4').prop( "checked", false );
    $('#star5').prop( "checked", false );
    $('#mensaje').text('');
    $('#rate-error').text('');
    $('#idMedico').val('');
    $('#idCita').val('');
  });

  $('.searchClient').select2({
    language: "es",
    placeholder: 'Seleccionar al paciente',
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
      },
      searching: function() {
        return "Buscando..";
      }
    }
  });

  $('.searchCie').select2({
    language: "es",
    placeholder: 'Seleccionar',
    minimumInputLength: 3,
    maximumSelectionLength: 10,
    ajax: {
      url: '<?php echo base_url("searchCie10");?>',
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
  
  $('#modal-updateCita').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var idCita = button.data('id')
    var idUsuario = button.data('usuario')

    $.ajax({
          type: 'get',
          url: "<?php echo base_url("editarHistorial");?>",
          data: { idCita : idCita },
          success: function (data) {
            if(data.status){ 
              $('#idCitaUp').val(data.historialMedico.idCita);
              $('#idUsuario').val(idUsuario);
              $('#tiempoEnfermedad').val(data.historialMedico.tiempo_enfermedad);
              $('#relato').val(data.historialMedico.relato);
              $('#fbiologicasComentario').val(data.historialMedico.funciones_biologicas_comentario);
              $('#otrosap').val(data.historialMedico.otros_antecedentesp);
              $('#otrosaf').val(data.historialMedico.otros_antecedentesf);
              $('#medicamentos').val(data.historialMedico.medicamentos);
              $('#otrosMedicamentos').val(data.historialMedico.otros_medicamentos);
              $('#medicamentosHabituales').val(data.historialMedico.medicamentoHabitual);
			  if(data.examenFisicoCita) {
				  $('#pa').val(data.examenFisicoCita.pa);
				  $('#fc').val(data.examenFisicoCita.fc);
				  $('#fr').val(data.examenFisicoCita.fr);
				  $('#tt').val(data.examenFisicoCita.tt);
				  $('#sato').val(data.examenFisicoCita.sato);
				  $('#egenarl').val(data.examenFisicoCita.egeneral);
			  }
              $('#recomendaciones').val(data.historialMedico.recomendaciones);

              if(data.historialMedico.normales == 1) $('#normales').prop('checked', true); else $('#normales').prop('checked', false);

              if(data.historialMedico.antecedes_patologico == 0) $("#antecedentePatologico").bootstrapSwitch('state', false); else $("#antecedentePatologico").bootstrapSwitch('state', true);

              if(data.historialMedico.antecedes_patologico_dislipidemia == 1) $('#dislipidemia').prop('checked', true); else $('#dislipidemia').prop('checked', false);
              if(data.historialMedico.antecedes_patologico_diabestes == 1) $('#diabetes').prop('checked', true); else $('#diabetes').prop('checked', false);
              if(data.historialMedico.antecedes_patologico_hta == 1) $('#hta').prop('checked', true); else $('#hta').prop('checked', false);
              if(data.historialMedico.antecedes_patologico_asma == 1) $('#asma').prop('checked', true); else $('#asma').prop('checked', false);
              if(data.historialMedico.antecedes_patologico_gastritis == 1) $('#gastritis').prop('checked', true); else $('#gastritis').prop('checked', false);



              if(data.historialMedico.antecedes_familiar == 0) $("#antecedesFamiliar").bootstrapSwitch('state', false); else $("#antecedesFamiliar").bootstrapSwitch('state', true);

              if(data.historialMedico.relaciones_adversas == 0) $("#relacionesam").bootstrapSwitch('state', false); else $("#relacionesam").bootstrapSwitch('state', true);
 
              var studentSelect = $('#diagnostico');
              $.ajax({
                  type: 'POST',
                  url: '<?php echo base_url("searchCie10");?>',
                  data: { busqueda : data.citaDiagnostico },
                  dataType: 'json',
              }).then(function (data) {
                for (let index = 0; index < data.length; index++) {
                  var option = new Option(data[index].text, data[index].id, true, true);
                  studentSelect.append(option).trigger('change');
                }
              });

              $('#planTratamiento1').val(data.ptratamiento[0]);
              $('#planTratamiento2').val(data.ptratamiento[2]);
              $('#planTratamiento3').val(data.ptratamiento[4]);
              $('#observaciones').val(data.ptratamiento[1]);

              if(data.dataReceta.length > 0) {
                var element = data.dataReceta[0].split('_');
              
                $('#nombre').val(element[0]);
                $('#presentacion').val(element[1]);
				$('#cantidad').val(element[2]);
                $('#via').val(element[3]);
                $('#dosificacion').val(element[4]);
                $('#tiempot').val(element[5]);
              }
              
              for (let index = 1; index < data.dataReceta.length; index++) {
                var element = data.dataReceta[index].split('_');
                  
                $("#lista_recetas>tbody").append(`<tr>
                                      <td><input name="nombre[]" value="` + element[0] + `" class="form-control"></td>
                                      <td><input name="presentacion[]" value="` + element[1] + `" class="form-control"></td>
									  <td><input name="cantidad[]" maxlength="2" value="` + element[2] + `" class="form-control"></td>
                                      <td><input name="via[]" value="` + element[3] + `" class="form-control"></td>
                                      <td><input name="dosificacion[]" value="` + element[4] + `" class="form-control"></td>
                                      <td><input name="tiempot[]" value="` + element[5] + `" class="form-control"></td>
                                      <td><button type="button" class="btn btn-outline-danger button_eliminar_receta" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
                                    </tr>`);
              }
 
              if(data.dataExamenM.length > 0) {
                var element = data.dataExamenM[0].split('_');
                $('#nombreEm').val(element[0]);
                $('#especificaciones').val(element[1]);
                $('#especialidades').val(element[2]);
              }
              
              for (let index = 1; index < data.dataExamenM.length; index++) {
                var element = data.dataExamenM[index].split('_');
                  
                $("#lista_examenAux>tbody").append(`<tr>
                                      <td><input name="nombreEm[]" value="` + element[0] + `" class="form-control"></td>
                                      <td><input name="especificaciones[]" value="` + element[1] + `" class="form-control"></td>
                                      <td><input name="especialidad[]" value="` + element[2] + `" class="form-control"></td>
                                      <td><button type="button" class="btn btn-outline-danger button_eliminar_examenAux" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
                                    </tr>`);
              }

            }else{
              Swal.fire({
                icon: 'error',
                timer: 7000,
                title: 'Error de validación',
                text: data.message
              })
            }
          },
          error: function (data) {
            Swal.fire({
              icon: 'error',
              timer: 7000,
              title: 'Error interno',
              text: 'Ha ocurrido un error interno!'
            })
          },
        });
  });

  $('#modal-updateCita').on('hide.bs.modal', function (event) {

    $('#diagnostico').empty().trigger("change");

    $(this).find('form')[0].reset();

    $('#lista_recetas tbody tr').not(':first').remove();
    $('#lista_examenAux tbody tr').not(':first').remove();
  });

  $('#btnUpdateCita').on('click', function() {
    Swal.fire({
    title: '¿Estás seguro de Actualizar este historial?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, Seguro!',
    cancelButtonText: 'Cancelar',
    }).then((result) => {

      if (result.value) {
        var btnCerrar = $(this);

        var recomendaciones = $('#recomendaciones').val();
        var recetas = $('#recetas').val();
        var examenesAuxiliares = $('#examenesAuxiliares').val();

        $.ajax({
            type: 'POST',
            url: "<?php echo base_url("uCita");?>",
            data: $('#frmUpdateCita').serialize(),
            beforeSend: function () 
            {            
              btnCerrar.html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
              btnCerrar.removeClass("btn btn-outline-info");
              btnCerrar.addClass("btn btn-info");
              btnCerrar.prop('disabled', true);
            },
            success:function(response) {
              if(response.status){  
                $('#modal-updateCita').modal('hide');
                Swal.fire({
                  icon: 'success',
                  timer: 7000,
                  title: 'Respuesta exitosa',
                  text: response.message,
                  onClose: () => {
                    window.location.replace("<?php echo base_url('mihistorial');?>");
                  }
                })
              }else{
                Swal.fire({
                  icon: 'error',
                  timer: 7000,
                  title: 'Error de validación',
                  text: response.message
                })
              }
            },
            error: function (data) {
              Swal.fire({
                icon: 'error',
                timer: 7000,
                title: 'Error interno',
                text: 'Ha ocurrido un error interno!'
              })
            }
          });
        }
      })
  });
  
  function ventana_cita(cita, user, opc) {
    var mywindow = window.open('<?php echo base_url('update-appointment/') ?>' + cita + '/' + user +'?read-only=' + opc, 'Actulizar Cita', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=1000,height=700,left = 390,top = 50');
  }
</script>
</body>
</html>