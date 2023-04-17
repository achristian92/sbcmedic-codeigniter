<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
<base href="consulta">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SBCMedic | Mis Citas</title>
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
 
 
    .modal-header{
      background-color:#df4a59;
		  color: #fff;
		  font-weight: bold;
    }

    

    .select2-selection--single {
  height: 100% !important;
}
.select2-selection__rendered{
  word-wrap: break-word !important;
  text-overflow: inherit !important;
  white-space: normal !important;
}


  </style>
</head>
<body class="hold-transition sidebar-mini sidebar-collapse" style="font-size: 14px;">
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
        <span style="vertical-align:middle;  "><span style="margin-left: 10px;font-size: 2.4rem;font-weight:bold;color: #004663;vertical-align:middle;  "> Mis Citas<span></span>
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
      <form id="quickForm" method="post" action="<?php echo base_url('miscitas');?>">
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
                <table id="misCitas" class="table table-bordered table-hover">
                  <thead>
                  <tr>
					<th>NroCita</th>
                    <th>Tipo Cita</th>
				
					<?php if($permisoVidoLlamada == "video_llamada") { ?>
                    <th>Video Llamada</th>
					<th>Código Sala</th>
                    <?php } ?>
					<?php if ($rol != 3) { ?><th>Paciente</th><?php } ?>
                    <th>Fecha Cita</th>
                    <th>Hora Cita</th>
                    <th>Especialidad</th>
                    <?php if ($rol != 2) { ?><th>Profesional</th><?php } ?>
                    
                    
				 
                    <th>MotivoCita</th>
                   

                    <?php if($realizarPago == "cambiar_status_pago" and $rol != 3) { ?>
                    <th>Pago realizado</th>
                    <?php } ?>

                    <?php if($cerrarCita == "guardar_historia_clinica" and $rol != 3) { ?>
                      <th>Historia Clínica</th>
                    <?php } ?>
					
					 
                    <?php if($cerrarCitaRapida == "cerrar_cita") { ?>
                        <th>Cita Atendida</th>
                    <?php } ?>
                    <?php if($permisoCancelarCita == "cancelar_cita") { ?>
                      <th>Anular Cita</th>
                    <?php } ?>
					<th>Detalle</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                      foreach ($resultados as $clave => $valor){
                        $clave++;
                    ?>
                      <tr>
					    <td><?php echo str_pad($valor["idCita"], 6, '0', STR_PAD_LEFT); ?></td>
                        <td style="color: <?php echo ($valor["virtual"] == 1)?"#e56b6f;" : "";?>"></strong><?php echo $valor["tipoCita"];?>
							<strong style="color: green;"><?php if($valor["gratis"]) echo "/Gratis" ?></strong>
						</td>
						            
                        
						<?php if($permisoVidoLlamada == "video_llamada") { ?>
                        <td style="text-align: center;">
                          <?php if($valor["virtual"] == 1 and ($valor["tiempoVideo"] >=0 and $valor["tiempoVideo"] <=30) ) {?>
                            <a href="javascript:abrirVentana('<?php echo $version["urlVideoCam"];?>')" title="Iniciar video llamada"><i class="fa fa-video fa-w-18 fa-2x"></i></a>
                          <?php } else { ?>
                            <i class="fa fa-video fa-w-18 fa-2x" title="No se puede realizar video llamada"></i>
                          <?php } ?>
						  
						  
                        </td>
						<td style="background-color: #96c93d; color: #302b63">
							<strong>
							<?php if($valor["virtual"] == 1 and $valor["tiempoVideo"] <=40) {  echo $valor["codigoSala"];?>
							<?php } ?>
							</strong>
                        </td>
                        <?php } ?>
						
						<?php if ($rol != 3) { ?><td style="background-color: #e9edc9;"><strong><?php echo $valor["paciente"];?></strong></td><?php } ?>
						<td style="color: #02c39a;"><strong><?php echo date("d/m/Y",strtotime($valor["fechaCita"]));?></strong></td>
						
                        <td style="color: #02c39a;"><strong><?php echo substr($valor["horaCita"], 0, 5);?></strong></td>
                        
                        <td style="background-color: #e9edc9;"><strong><?php echo $valor["especialidad"];?></strong></td>
                        
						            <?php if ($rol != 2) { ?><td style="background-color: #bde0fe;"><?php echo $valor["medico"];?></td><?php } ?>
                        

						<td style="background-color: #bde0fe;"><?php echo $valor["motivoTipoCita"];?></td>

                        <?php if($realizarPago == "cambiar_status_pago" and $rol != 3) { ?>
                        <td>
                          <input type="checkbox" name="ckbPago" data-bootstrap-switch data-off-color="danger" data-on-color="success" data-on-text="SI&nbsp;&nbsp;&nbsp;" data-off-text="NO" data="<?php echo $valor["idPago"];?>" <?php  if($valor["statusPago"] == 1) echo "checked='checked'"; else echo ""; ?>  <?php //if($verStatusPago == "ver_status_pago") echo "disabled"; ?>  disabled/>
                        </td>
                        <?php } ?>

                        <?php if($cerrarCita == "guardar_historia_clinica" and $rol != 3) { ?>
                          <td style="text-align: center;">
							 
						 
							<button type="button" class="btn btn-outline-success" title="Crear Historial" onclick="ventana_cita('<?php echo $valor["idCita"];?>', '<?php echo $valor["idUsuario"];?>')"  <?php if((date('Y-m-d', time()) < $valor["fechaCita"]) || ($valor["statusPago"] == 0 and !$valor["gratis"])   ) echo "disabled"; ?> ><i class="fa fa-save" aria-hidden="true"></i></button>
							 
						  
						  </td>
                        <?php } ?>
						

						

						
                        
                           <td style="text-align: center;">
							<?php if($cerrarCitaRapida == "cerrar_cita" and $valor["statusPago"] == 1 || $valor["gratis"] == 1) { ?>
						   <button type="button" id="btn_cerrar_cita" class="btn btn-warning" title="Cita Atendida" onClick="cerrarCita('<?php echo $valor["idCita"];?>')"><i class="far fa-clone"></i></button>
							<?php } ?>
						   </td>
                        
						<?php if($permisoCancelarCita == "cancelar_cita") { ?>
                          <td style="text-align: center;">
						  
							
							<button type="button" id="btn_cancelar_cita" class="btn btn-danger" data-toggle="modal" data-target="#cancelarCita" data-idcita='<?php echo $valor["idCita"];?>' data-idhorario='<?php echo $valor["idAvailability"];?>' title="Cancelar Cita"><i class="far fa-window-close"></i></button>
							
						  </td>
                        <?php } ?>
						<td>
                         
                          <a href="#" data-toggle="modal" data-target="#modal-detalle" data-tipo="<?php echo $valor["tipoCita"];?>" data-fecha="<?php echo date("d/m/Y",strtotime($valor["fechaCita"]));?>" data-hora="<?php echo $valor["horaCita"];?>" data-especialidad="<?php echo $valor["especialidad"];?>" data-medico="<?php echo $valor["medico"];?>" data-motivo="<?php echo $valor["motivo"];?>" data-direccion="<?php echo $valor["address"];?>" data-fono="<?php echo $valor["phone"];?>" data-document="<?php echo $valor["document"];?>" data-nomprocedimiento="<?php echo $valor["nomProcedimiento"];?>"><i class="fas fa-address-card" title="Ver detalle"></i></a>
                        </td>
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
                <strong>Procedimiento:</strong> <span id="procedimiento" class="text-info"></span>
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
                  <div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4 col-sm-6 offset-sm-3 col-xl-3 offset-xl-5 col-6 offset-3"><button type="button" class="btn btn-outline-success" data-dismiss="modal" title="Cerrar ventana">Cerrar</button></div>
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
      <div class="modal fade" id="modal-cerrarCita" data-backdrop="static" data-keyboard="false" tabindex="-1"   aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="frmCerrarCita">
          <div class="modal-content">
            <div class="modal-body">

              <div class="row justify-content-md-center mt-2">
                <div class="col text-info">
                 <h3>ANAMNESIS</h3>
                </div>
                <div class="col text-info text-center">
                 <h3>NRO CITA : <span class="badge badge-success" id="nroCita">Success</span></h3>
                </div>
              </div>
              <div class="row justify-content-md-center">
                <div class="col col-lg-4">
                  <label for="tiempoEnfermedad">Tiempo de Enfermedad</label>
                    <input name="tiempoEnfermedad" class="form-control">
                  </div>
                <div class="col col-lg-8">
                  <label for="relato">Relato</label>
                 
				  <textarea name="relato" class="form-control" cols="2" rows="2"></textarea>
                </div>
              </div> 

              <div class="row justify-content-md-center">
                  <div class="col col-lg-10">
                    <label for="fbiologicas">Funciones Biológicas</label>
 
                    <input name="fbiologicasComentario" class="form-control">
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
                  <input type="text" name="otrosap" class="form-control" style="width:70%">
                </div>
                <div class="col col-lg-8 form-inline">
                  <label for="otros">Antecedentes familiares</label>
                  &nbsp;<input type="text" name="otrosaf" class="form-control">&nbsp;
                  <input type="checkbox" name="antecedesFamiliar" id="antecedesFamiliar" checked data-bootstrap-switch data-on-text="SI&nbsp;&nbsp;&nbsp;" data-off-text="NO">
                  
                </div>
              </div> 

              <div class="row justify-content-md-center mt-2">
                <div class="col col-lg-3">
                  <label for="nombre">Reacción adversas medicamentosas</label>
                    <input type="checkbox" name="relacionesam" checked data-bootstrap-switch data-on-text="SI&nbsp;&nbsp;&nbsp;" data-off-text="NO">
                  </div>
                  <div class="col col-lg-5">
                    <label for="medicamentos">Medicamentos</label>
                    <input type="text" name="medicamentos" class="form-control">
                  </div>
                  <div class="col col-lg-4">
                    <label for="otros">Otros</label>
                    <input type="text" name="otrosMedicamentos" class="form-control">
                  </div>
              </div> 
              <div class="row justify-content-md-center mt-2">
                <div class="col-12">
                  <label for="egenarl">Medicamentos habituales Actualmente:</label>
                  <input name="medicamentosHabituales" class="form-control">
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
                    <input name="pa" class="form-control">
                  </div>
                <div class="col col-lg-2">
                  <label for="fc">F.C</label>
                  <input name="fc" class="form-control">
                </div>
                <div class="col col-lg-2">
                  <label for="fr">F.R</label>
                  <input name="fr" class="form-control">
                </div>
                <div class="col col-lg-2">
                  <label for="tt">T<sup>°</sup></label>
                  <input name="tt" class="form-control">
                </div>
                <div class="col col-lg-3">
                  <label for="sato">Sato2</label>
                  <input name="sato" class="form-control">
                </div>
              </div> 

              <div class="row justify-content-md-center mt-2">
                <div class="col-12">
                  <label for="egenarl">Examen General</label>
                  <input name="egenarl" class="form-control">
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
                    <input name="planTratamiento[]" class="form-control" placeholder="1.-">
                  </div>
              </div> 
              <div class="row justify-content-md-center mt-1">
                <div class="col col-lg-12">
                    <input name="planTratamiento[]" class="form-control" placeholder="2.-">
                  </div>
              </div>
              <div class="row justify-content-md-center mt-1">
                <div class="col col-lg-12">
                    <input name="planTratamiento[]" class="form-control" placeholder="3.-">
                  </div>
              </div>
              <div class="row justify-content-md-center mt-1">
                <div class="col col-lg-12">
                    <input name="observaciones" class="form-control" placeholder="Observaciones" maxlength="150">
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
                          <td><input name="nombre[]" class="form-control"></td>
                          <td><input name="presentacion[]" class="form-control"></td>
						  <td><input name="cantidad[]" maxlength="2" class="form-control"></td>
                          <td><input name="via[]" class="form-control"></td>
                          <td><input name="dosificacion[]" class="form-control"></td>
                          <td><input name="tiempot[]" class="form-control"></td>
                          <td><button type="button" class="btn btn-outline-danger button_eliminar_receta" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="row justify-content-md-center mt-2">
                <div class="col text-info">
                  <h3>EXÁMENES AUXILIARES</h3>
                </div>
                <div class="col text-info text-right">
                  <span class="badge badge-info">LAB: Laboratorio / PRO: Procedimiento</span>
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
                          <td style="color:#28a745;"><button type="button" class="btn btn-outline-success button_agregar_examenAux" title="Agregar registro"> <i class="fas fa-plus"></i></button></td>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <select name="procedimiento[]" class="form-control procedimientos select2">
                            </select>
                          </td>
                          <td><input name="especificaciones[]" class="form-control"></td>
                          <td><button type="button" class="btn btn-outline-danger button_eliminar_examenAux" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="row justify-content-md-center mt-2">
                <div class="col text-info">
                  <h3>DESCANSO MÉDICO <input type="checkbox" id="descansoMedico" name="descansoMedico" data-bootstrap-switch data-on-text="SI&nbsp;&nbsp;&nbsp;" data-off-text="NO"></h3>
                </div>
              </div>


              <div class="row justify-content-md-center" id="descanso2" style="display:none;">
                <div class="col col-lg-4">
                  <label for="pa">Tipo de descanso(Total/Parcial)</label>
                    <input name="tipoDescanso" class="form-control">
                  </div>
                <div class="col col-lg-4">
                  <label for="fc">Del</label>
                  <div class="input-group">
                     <input type="date" name="fechaDel" class="form-control" id="fechaDel" value="<?php echo date("Y-m-d"); ?>" requerid>
                  </div>
                </div>
                <div class="col col-lg-4">
                  <label for="fc">Al</label>
                  <div class="input-group">
                    <input type="date" name="fechaAl" class="form-control" id="fechaAl" value="<?php echo date("Y-m-d"); ?>" requerid>
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
                      <input type="hidden" name="idCita" id="idCita">
                      <input type="hidden" name="idUsuario" id="idUsuario">
                      <button type="button" id="btnCerrarCita" class="btn btn-outline-success" title="Guardar cita">Guardar Historial</button>
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



      		<!-- Modal cancelar cita -->
          <div class="modal fade" id="cancelarCita"  tabindex="-1"  aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header d-block">
                <h4 class="modal-title text-center" id="newExamenLabel">ANULAR CITA<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="nombres" style="color: #302b63;">Motivo</label>
                  <select class="form-control select2" name="idMotivoCancelar" style="width: 100%;" id="idMotivoCancelar">
                    <?php
                      foreach ($motivosCancelaciones as $motivoCancelacion) {
                    ?>
                    <option value="<?php echo $motivoCancelacion->id; ?>"><?php echo $motivoCancelacion->descripcion; ?></option>
                    <?php
                      }
                    ?>
                  </select>

                </div>
                <div class="form-group">
                  <label for="nrodocumento" style="color: #302b63;">Observación</label>
                  <textarea name="motivoCancelar" id="motivoCancelar"  class="form-control" cols="4" rows="4"></textarea>
                </div>
 
       
            </div>
            <div class="modal-footer justify-content-between">
              <input type="hidden" name="idCitaCancelar" id="idCitaCancelar">
              <input type="hidden" name="idHorarioCancelar" id="idHorarioCancelar">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="button" id="motivoCancelarCita" class="btn btn-outline-danger" onClick="cancelarCita()">Guardar</button>
            </div>
          </div>
        </div>
        <!-- /.Modal -->
      
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
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- Select2 -->
<script src="<?php echo base_url('plugins/select2/js/select2.full.min.js');?>"></script>
<script src="<?php echo base_url('plugins/inputmask/min/jquery.inputmask.bundle.min.js');?>"></script>

<script>
  $('[data-mask]').inputmask();
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

     test();

	});
	//Eliminar fila.
	$('#lista_examenAux').on('click', '.button_eliminar_examenAux', function(){
    var nFilas = $("#lista_examenAux tr").length;
	  
    if(nFilas > 2) $(this).parents('tr').eq(0).remove();
	});

  $('select').select2({
    width: '100%'
  });

  $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
  });
/*
  $('input[name="ckbPago"]').on('switchChange.bootstrapSwitch', function(e, data){
	  
		if(data){
		  $("#btn_cierre").prop('disabled', false);
		} else {
		  $("#btn_cierre").prop('disabled', true);
		}
		
      $.ajax({
      type: 'post',
      url: "<?php echo base_url("gpagoStatus");?>",
      data: { idPago : $(this).attr('data'), status : data },
      success: function (respuesta) {
        if (!respuesta) alert('Error! No se guardo.');
      },
      error: function (respuesta) {
        if (respuesta) alert('Error! No se guardo.' + respuesta);
      }
    });
  }); */

  $('input[name="descansoMedico"]').on('switchChange.bootstrapSwitch', function(e, data){
     if(data){
     
      $('#descanso2').show();
     } else {

      $('#descanso2').hide();
     }
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

  $('#misCitas').DataTable({
    "paging": true,
	"order": [[ 0, "desc" ]],
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
	"language": {
      "url": "<?php echo base_url("plugins/datatables-bs4/js/Spanish.json");?>"
    }
  });


  $('#btnCerrarCita').on('click', function() {
    Swal.fire({
    title: '¿Estás seguro de guardar este historial?',
    text: 'Una vez confirmado, no se podrá revertir.',
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
            url: "<?php echo base_url("gCita");?>",
            data: $('#frmCerrarCita').serialize(),
            beforeSend: function () 
            {            
              btnCerrar.html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
              btnCerrar.removeClass("btn btn-outline-success");
              btnCerrar.addClass("btn btn-success");
              btnCerrar.prop('disabled', true);
            },
            success:function(response) {
              if(response.status){  
                $('#modal-cerrarCita').modal('hide');
                Swal.fire({
                  icon: 'success',
                  timer: 7000,
                  title: 'Respuesta exitosa',
                  text: response.message,
                  onClose: () => {
                    window.location.replace("<?php echo base_url('miscitas');?>");
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

  function cancelarCita() {

    Swal.fire({
    title: '¿Estás seguro de ANULAR esta cita?',
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
          type: 'post',
          url: "<?php echo base_url("gCancelarCita");?>",
          data: { 
              idCita : $('#idCitaCancelar').val(), 
              idDisponible: $('#idHorarioCancelar').val(),
              idMotivoCancelar: $('#idMotivoCancelar').val(),
              motivoCancelar: $('#motivoCancelar').val() 
          },
          beforeSend: function () 
            {            
              $("#motivoCancelarCita").html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
              $("#motivoCancelarCita").removeClass("btn btn-outline-danger");
              $("#motivoCancelarCita").addClass("btn btn-danger");
              $("#motivoCancelarCita").prop('disabled', true);
            },
          success: function (data) {
            if(data.status){  
              
              Swal.fire({
                icon: 'success',
                timer: 7000,
                title: 'Respuesta exitosa',
                text: data.message,
                onClose: () => {
                  //window.location.replace("<?php echo base_url('miscitas');?>");
				  location.reload();
                }
              })
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
      }
    });
  }

  function cerrarHistorial(id, idDisponible) {

    Swal.fire({
    title: '¿Estás seguro de cerrar la Cita?',
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
          type: 'post',
          url: "<?php echo base_url("gCloseHistory");?>",
          data: { idCita : id, idDisponible: idDisponible },
          success: function (data) {
            if(data.status){  
              
              Swal.fire({
                icon: 'success',
                timer: 7000,
                title: 'Respuesta exitosa',
                text: data.message,
                onClose: () => {
                  window.location.replace("<?php echo base_url('miscitas');?>");
                }
              })
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
      }
    });
  }
  
  $('#cancelarCita').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var idCita = button.data('idcita')
    var idHorario = button.data('idhorario');
 
    $('#idCitaCancelar').val(idCita);
    $('#idHorarioCancelar').val(idHorario);
  });

  $('#cancelarCita').on('hide.bs.modal', function (event) {
    $('#idCitaCancelar').val('');
    $('#idHorarioCancelar').val('');
    
    $('#motivoCancelar').val('');
    //$("#idMotivoCancelar").val('').trigger('change') ;
    
  });
  
  $('#modal-cerrarCita').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var idCita = button.data('id')
    var idUsuario = button.data('usuario')
    $('#idCita').val(idCita);
    $('#idUsuario').val(idUsuario);
    $('#nroCita').html(idCita);
    $(".procedimientos").val('').trigger('change') ;
  });

  $('#modal-cerrarCita').on('hide.bs.modal', function (event) {
 
    $("#diagnostico").val('').trigger('change') ;
    $(".procedimientos").val('').trigger('change') ;

    $(this).find('form')[0].reset();
 
    $('#lista_recetas tbody tr').not(':first').remove();
    $('#lista_examenAux tbody tr').not(':first').remove();
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
    var direccion = button.data('direccion')
	var document = button.data('document')
    var nomprocedimiento = button.data('nomprocedimiento');
  


    $('#tipoCita').text(tipoCita);
    $('#fechaCita').text(fechaCita);
    $('#horaCita').text(horaCita);
    $('#especialidad').text(especialidad);
    $('#medico').text(medico);
    $('#motivo').text(motivo);
    $('#fono').text(fono);
    $('#direccion').text(direccion);
	$('#dni').text(document);
	$('#procedimiento').text(nomprocedimiento);
  });
  
  function abrirVentana(url) {
    var w = 800;
    var h = 500;
    var left = Number((screen.width/2)-(w/2));
    var tops = Number((screen.height/2)-(h/2));

    window.open(url, 'nuevo', 'directories=0,toolbar=no, directories=no, location=no, menubar=no, scrollbars=no, statusbar=no, width='+w+', height='+h+', top='+tops+', left='+left);
  }

  $('.searchCie').select2({
    width: '100%',
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
        return "Buscando...";
      }
    }
  });

  
  $('.procedimientos').select2({
    language: "es",
    placeholder: 'Seleccionar',
    minimumInputLength: 2,
    maximumSelectionLength: 20,
    ajax: {
      url: '<?php echo base_url("search-procedimiento-lab");?>',
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

  function test(){
    $('.procedimientos').select2({
      width: '100%',
    language: "es",
    placeholder: 'Seleccionar',
    minimumInputLength: 2,
    maximumSelectionLength: 20,
    ajax: {
      url: '<?php echo base_url("search-procedimiento-lab");?>',
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
  
  
  
  function cerrarCita(id) {

    Swal.fire({
    title: '¿Estás seguro de cerrar la Cita?',
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
          type: 'post',
          url: "<?php echo base_url("gCloseHistory");?>",
          data: { idCita : id },
          success: function (data) {
            if(data.status){  
              
              Swal.fire({
                icon: 'success',
                timer: 7000,
                title: 'Respuesta exitosa',
                text: data.message,
                onClose: () => {
                  window.location.replace("<?php echo base_url('miscitas');?>");
                }
              })
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
      }
    });
  }  
  
 

  
  function ventana_cita(cita, usuario) {
    var mywindow = window.open('<?php echo base_url('new-appointment/') ?>' + cita + '/'+ usuario, 'Nueva Cita', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=1000,height=700,left = 390,top = 50');
  }   
</script>
</body>
</html>