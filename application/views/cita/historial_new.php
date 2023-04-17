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
  <title>SBCMedic | NUEVA HISTORIAL</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php $this->load->view("styles"); ?>
 <link rel="stylesheet" href="<?php echo base_url('plugins/icheck-bootstrap/icheck-bootstrap.min.css');?>">
    <style>
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

<body style="background: #11998e;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #38ef7d, #11998e);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #38ef7d, #11998e); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
">
            <div class="container-fluid">
            
              <div class="row bg-black">
                <div class="col-sm text-center"  style=" justify-content: center;align-items: center;">
                  <h2>NUEVO HISTORIAL</h2>
                </div>
              </div>

              <div class="row justify-content-md-center mt-2">
                <div class="col text-light">
                 <h5><i class="fas fa-restroom" title="Nombre del Paciente"></i> : <?php echo $usuarioCita["paciente"]." / " ; ?> <span class="badge badge-info" style="color: #000;"><i class="far fa-calendar-alt" title="Edad del Paciente"></i> <?php echo $usuarioCita["edad"]; ?> Años</span></h5>
                </div>
                <div class="col-4 text-info text-right text-light">
                 <h3>NRO CITA : <span class="badge badge-success" id="nroCita"><?php echo str_pad($this->uri->segment(2), 6, '0', STR_PAD_LEFT); ?></span></h3>
                </div>
              </div>
              <div class="row justify-content-md-center mt-1">
                <div class="col text-light">
                 <h5><i class="fas fa-first-aid" title="Procedimiento a realizar"></i> : <u> <?php echo $procedimiento; ?> </u></h5>
                </div>
                
              </div>
              <form id="frmActualizarCita">
              <div class="row mt-2">
                <div class="col">
                  <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">GENERAL</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">EXAMEN FÍSICO</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">DIAGNÓSTICO</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="pills-plantratamiento-tab" data-toggle="pill" href="#pills-plantratamiento" role="tab" aria-controls="pills-plantratamiento" aria-selected="false">PLAN DE TRABAJO</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="pills-recetas-tab" data-toggle="pill" href="#pills-recetas" role="tab" aria-controls="pills-recetas" aria-selected="false">RECETAS</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="pills-examenesauxiliares-tab" data-toggle="pill" href="#pills-examenesauxiliares" role="tab" aria-controls="pills-examenesauxiliares" aria-selected="false">SOLICITUDES</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="pills-descansomedico-tab" data-toggle="pill" href="#pills-descansomedico" role="tab" aria-controls="pills-descansomedico" aria-selected="false">DESCANSO MÉDICO</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="pills-recomendaciones-tab" data-toggle="pill" href="#pills-recomendaciones" role="tab" aria-controls="pills-recomendaciones" aria-selected="false">RECOMENDACIONES</a>
                    </li>
					<!--	
					<li class="nav-item">
                      <a class="nav-link" id="pills-interconsultas-tab" data-toggle="pill" href="#pills-interconsultas" role="tab" aria-controls="pills-miHistoria" aria-selected="false" title="Interconsultas">INTERCONSULTAS</a> 
                    </li>
					-->
                    <li class="nav-item">
                      <a class="nav-link" id="pills-miHistoria-tab" data-toggle="pill" href="#pills-miHistoria" role="tab" aria-controls="pills-miHistoria" aria-selected="false" title="Historial del Paciente">HISTORIA <span class="badge badge-warning">(<?php echo count($miHistoria);?>)</span></a> 
 
                    </li>
                  </ul>
                <div class="tab-content" id="pills-tabContent">
                  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">


                  <div class="row justify-content-md-center mt-2">
                <div class="col text-light">
                  <h3>ANAMNESIS</h3>
                </div>
              </div>
              <div class="row justify-content-md-center">
                <div class="col col-lg-4">
                  <label for="tiempoEnfermedad">Tiempo de Enfermedad</label>
					<input type="text" id="tiempoEnfermedad" name="tiempoEnfermedad" class="form-control">
                  </div>
                <div class="col col-lg-8">
					<label for="relato">Relato</label>
				    <textarea id="relato" name="relato" class="form-control" cols="2" rows="2"></textarea>
                </div>
              </div> 
              <div class="row justify-content-md-center">
                <div class="col col-lg-10">
                  <label for="fbiologicasComentario">Funciones Biológicas</label>
                  <textarea id="fbiologicasComentario" name="fbiologicasComentario" class="form-control" cols="2" rows="2"></textarea>
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
                  <input type="checkbox" name="antecedentePatologico" id="antecedentePatologico" data-bootstrap-switch data-on-text="SI&nbsp;&nbsp;&nbsp;" data-off-text="NO">
                </div>
                <div class="col col-lg-2">
                  <label for="pa">Dislipidemia</label>
                  <div class="custom-control custom-checkbox">
                    <div class="icheck-primary d-inline">
                      <input type="checkbox" name="dislipidemia" id="dislipidemia"  value="1" disabled>
                      <label for="dislipidemia">
                      </label>
                    </div>
                  </div>
                </div>
                <div class="col col-lg-2">
                  <label for="pa">Diabetes</label>
                  <div class="custom-control custom-checkbox">
                    <div class="icheck-primary d-inline">
                      <input type="checkbox" name="diabetes" id="diabetes"  value="1" disabled>
                      <label for="diabetes">
                      </label>
                    </div>
                  </div>
                </div>
                
                <div class="col col-lg-2">
                  <label for="pa">HTA</label>
                  <div class="custom-control custom-checkbox">
                    <div class="icheck-primary d-inline">
                      <input type="checkbox" name="hta" id="hta"  value="1" disabled> 
                      <label for="hta">
                      </label>
                    </div>
                  </div>
                </div>
                
                <div class="col col-lg-2">
                  <label for="pa">Asma</label>
                  <div class="custom-control custom-checkbox">
                    <div class="icheck-primary d-inline">
                      <input type="checkbox" name="asma" id="asma"  value="1" disabled>
                      <label for="asma">
                      </label>
                    </div>
                  </div>
                </div>
                
                <div class="col col-lg-2">
                  <label for="pa">Gastritis</label>
                  <div class="custom-control custom-checkbox">
                    <div class="icheck-primary d-inline">
                      <input type="checkbox" name="gastritis" id="gastritis"  value="1" disabled>
                      <label for="gastritis">
                      </label>
                    </div>
                  </div>
                </div>
              </div> 
			  
			  <?php if( $codigo_especialidad == 18) { ?>
              <div class="row justify-content-md-center mt-2"  style="background-color: #8BC34A; color: #303F9F;">
                <div class="col col-lg-3">
                  <label for="fur">FUR</label>
                  <input type="text" id="fur" name="fur"  class="form-control">
                </div>
                <div class="col col-lg-3">
                  <label for="rc">RC</label>
                  <input type="text" id="rc" name="rc"  class="form-control">
                </div>
                <div class="col col-lg-2">
                  <label for="gp">G_P</label>
                  <input type="text" id="gp" name="gp"  class="form-control" maxlength="4">
                </div>
                <div class="col col-lg-4  mb-2">
                  <label for="mac">MAC</label>
                  <input type="text" id="mac" name="mac"  class="form-control">
                </div>
              </div>
              <?php } ?>
 
              <div class="row justify-content-md-center mt-2">
                <div class="col col-lg-4 form-inline">
                  <label for="otros">Otros&nbsp;</label>
                  <input type="text" id="otrosap" name="otrosap"  class="form-control" style="width:70%">
                </div>
                <div class="col col-lg-8 form-inline">
                  <label for="otros">Antecedentes familiares</label>
                  &nbsp;<input type="text" id="otrosaf" name="otrosaf" class="form-control">&nbsp;
                  <input type="checkbox" name="antecedesFamiliar" id="antecedesFamiliar" data-bootstrap-switch data-on-text="SI&nbsp;&nbsp;&nbsp;" data-off-text="NO">
                </div>
              </div> 

              <div class="row justify-content-md-center mt-2">
                <div class="col col-lg-3">
                  <label for="nombre">Reacción adversas medicamentosas</label>
                    <input type="checkbox" id="relacionesam" name="relacionesam" data-bootstrap-switch data-on-text="SI&nbsp;&nbsp;&nbsp;" data-off-text="NO">
                </div>
                <div class="col col-lg-5">
                  <label for="medicamentos">Medicamentos</label>
                  <textarea id="medicamentos" name="medicamentos" class="form-control" cols="2" rows="2"></textarea>
                </div>
                <div class="col col-lg-4">
                  <label for="otrosMedicamentos">Otros</label>
                  <textarea id="otrosMedicamentos" name="otrosMedicamentos" class="form-control" cols="2" rows="2"></textarea>
                </div>
              </div> 
              <div class="row justify-content-md-center mt-2">
                <div class="col-12">
                  <label for="medicamentosHabituales">Medicamentos habituales Actualmente:</label>
                  <textarea id="medicamentosHabituales" name="medicamentosHabituales" class="form-control" cols="2" rows="2"></textarea>
                </div>
              </div>
			  <div class="row justify-content-md-center mt-2">
                <div class="col-12">
                  <label for="antecedenteQuirurgico">Antecedes Quirúrgicos :</label>
                <textarea id="antecedenteQuirurgico" name="antecedenteQuirurgico" class="form-control" cols="2" rows="2"></textarea>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
              <div class="row justify-content-md-center mt-2">
                <div class="col text-light">
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

              <div class="row justify-content-md-center">
                <div class="col col-lg-4">
                  <label for="peso">Peso</label>
                  <input type="number" id="peso" name="peso" class="form-control" onblur="calculo_imc()">
                </div>
                <div class="col col-lg-4">
                  <label for="talla">Talla</label>
                  <input type="number" id="talla" name="talla" class="form-control" min="1" onblur="calculo_imc()">
                </div>
                <div class="col col-lg-4">
                  <label for="imc">IMC</label>
                  <input id="imc"  name="imc" class="form-control" readonly>
                </div>
              </div> 

              <div class="row justify-content-md-center mt-2">
                <div class="col-12">
                  <label for="egenarl">Examen General</label>
                  <textarea id="egenarl" name="egenarl" class="form-control" cols="4" rows="4" placeholder="Observaciones del Examen general"></textarea>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
              <div class="row justify-content-md-center mt-2">
                <div class="col text-info">
                 <h3>DIAGNÓSTICO CIE 10</h3>
                </div>
              </div>

              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <table  id="lista_diagnostico" width="100%" class="table">
                      <thead>
                        <tr>
                          <td style="color:#28a745;width: 75%;"><strong>Diagnóstico</strong></td>
                          <td style="color:green;"><strong>Tipo</strong></td>
                          <td style="color:#28a745;"><button type="button" class="btn btn-success button_agregar_diagnostico" title="Agregar registro"> <i class="fas fa-plus"></i></button></td>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                            <td>                                
                              <select name="diagnostico[]" class="form-control searchCie"></select>
                            </td>
                            <td>
                            <select name="tipoDiagnostico[]" class="form-control">
                              <option value="DEF" selected>DEFINITIVO</option>
                              <option value="PRE">PRESUNTIVO</option>
                            </select>
                            </td>
                            <td><button type="button" class="btn btn-outline-danger button_eliminar_diagnostico" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
			  
            </div>
            <div class="tab-pane fade" id="pills-plantratamiento" role="tabpanel" aria-labelledby="pills-plantratamiento-tab">
              <div class="row justify-content-md-center mt-2">
                <div class="col text-info">
                 <h3>PLAN DE TRABAJO</h3>
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
                    <textarea id="observaciones" name="observaciones" class="form-control" cols="4" rows="4" placeholder="Observaciones del Plan de tratamiento"></textarea>
                  </div>
              </div> 
            </div>

            <div class="tab-pane fade" id="pills-recetas" role="tabpanel" aria-labelledby="pills-recetas-tab">
              <div class="row justify-content-md-center mt-2">
                <div class="col text-info">
                 <h3>RECETAS MEDICAS</h3>
                </div>
              </div>

              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <table  id="lista_recetas" width="100%" class="table">
                      <thead>
                        <tr>
                          <td style="color:#28a745;width: 40%;"><strong>Descripción / Presentación</strong></td>
                          <td style="color:#28a745; ;width: 8%;"><strong>Cantidad</strong></td>
                          <td style="color:#28a745;"><strong>Via</strong></td>
                          <td style="color:#28a745;"><strong>Dosificación</strong></td>
                          <td style="color:#28a745;"><strong>Tiempo Tratamiento</strong></td>
                          <td style="color:#28a745;"><button type="button" class="btn btn-success button_agregar_receta" title="Agregar registro"> <i class="fas fa-plus"></i></button></td>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                            <td> 
                              <select name="recetas[]" class="form-control recetas"></select>
                            </td>
                            <td><input type="number" name="cantidad[]" class="form-control" min="1" step="1"  oninput="this.value = Math.round(this.value);"></td>
                            <td><input name="via[]" class="form-control"></td>
                            <td><input name="dosificacion[]" class="form-control"></td>
                            <td><input name="tiempo_tratamiento[]" class="form-control"></td>
                            <td><button type="button" class="btn btn-outline-danger button_eliminar_receta" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
                        </tr>
                      </tbody>
                    </table>

                    <table width="100%" class="table">
                      <thead>
						<tr>
							<th colspan="6">Otros</th>
						</tr>
                        <tr>
                          <td><strong>Nombre</strong></td>
                          <td><strong>Presentación</strong></td>
                          <td><strong>Cantidad</strong></td>
                          <td><strong>Vía</strong></td>
                          <td><strong>Dosificación</strong></td>
                          <td><strong>Tiempo tratamiento</strong></td>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                            <td><input name="nombretwo[]" class="form-control"></td>
                            <td><input name="presentaciontwo[]" class="form-control"></td>
                            <td><input name="cantidadtwo[]" maxlength="2" class="form-control"></td>
                            <td><input name="viatwo[]" class="form-control"></td>
                            <td><input name="dosificaciontwo[]" class="form-control"></td>
                            <td><input name="tiempottwo[]" class="form-control"></td>
                        </tr>
    
                        <tr>
                            <td><input name="nombretwo[]" class="form-control"></td>
                            <td><input name="presentaciontwo[]" class="form-control"></td>
                            <td><input name="cantidadtwo[]" maxlength="2" class="form-control"></td>
                            <td><input name="viatwo[]" class="form-control"></td>
                            <td><input name="dosificaciontwo[]" class="form-control"></td>
                            <td><input name="tiempottwo[]" class="form-control"></td>
                        </tr>
    
                        <tr>
                            <td><input name="nombretwo[]" class="form-control"></td>
                            <td><input name="presentaciontwo[]" class="form-control"></td>
                            <td><input name="cantidadtwo[]" maxlength="2" class="form-control"></td>
                            <td><input name="viatwo[]" class="form-control"></td>
                            <td><input name="dosificaciontwo[]" class="form-control"></td>
                            <td><input name="tiempottwo[]" class="form-control"></td>
                        </tr>
                            <tr>
                            <td><input name="nombretwo[]" class="form-control"></td>
                            <td><input name="presentaciontwo[]" class="form-control"></td>
                            <td><input name="cantidadtwo[]" maxlength="2" class="form-control"></td>
                            <td><input name="viatwo[]" class="form-control"></td>
                            <td><input name="dosificaciontwo[]" class="form-control"></td>
                            <td><input name="tiempottwo[]" class="form-control"></td>
                        </tr>
    
                        <tr>
                            <td><input name="nombretwo[]" class="form-control"></td>
                            <td><input name="presentaciontwo[]" class="form-control"></td>
                            <td><input name="cantidadtwo[]" maxlength="2" class="form-control"></td>
                            <td><input name="viatwo[]" class="form-control"></td>
                            <td><input name="dosificaciontwo[]" class="form-control"></td>
                            <td><input name="tiempottwo[]" class="form-control"></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

 
            </div>

            <div class="tab-pane fade" id="pills-examenesauxiliares" role="tabpanel" aria-labelledby="pills-examenesauxiliares-tab">
              <div class="row justify-content-md-center mt-4">
                <div class="col text-info">
                  <h3><strong>SOLICITUDES: Procedimientos, Exámenes de Laboratorio</strong></h3>
                </div>
               
              </div>

              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <table  id="lista_examenAux" width="100%" class="table">
                      <thead>
                        <tr>
                          <td style="color:#28a745;width: 50%;"><strong>Nombre</strong></td>
                          <td style="color:#28a745;"><strong>Especificaciones</strong></td>
                          <td style="color:#28a745;"><button type="button" class="btn btn-success button_agregar_examenAux" title="Agregar registro"> <i class="fas fa-plus"></i></button></td>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                            <td>                                
                              <select name="procedimiento[]" class="form-control procedimientos"></select>
                            </td>
                            <td><input name="especificaciones[]" class="form-control"></td>
                            <td><button type="button" class="btn btn-outline-danger button_eliminar_examenAux" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <div class="tab-pane fade" id="pills-descansomedico" role="tabpanel" aria-labelledby="pills-descansomedico-tab">

              <div class="row justify-content-md-center mt-2">
                <div class="col text-info">
                  <h3>DESCANSO MÉDICO <input type="checkbox" id="descansoMedico" name="descansoMedico" data-bootstrap-switch data-on-text="SI&nbsp;&nbsp;&nbsp;" data-off-text="NO"></h3>
                </div>
              </div>

              <div class="row justify-content-md-center" id="descanso1" style="display:none;">
                <div class="col col-lg-4">
                  <label for="tipoDescanso">Tipo de descanso(Total/Parcial)</label>
                    <input name="tipoDescanso" class="form-control">
                </div>
                <div class="col col-lg-4">
                  <label for="fechaDel">Del</label>
                  <div class="input-group">
                      <input type="date" name="fechaDel" class="form-control" id="fechaDel" value="<?php echo date("Y-m-d"); ?>" requerid>
                  </div>
                </div>
                <div class="col col-lg-4">
                  <label for="fechaAl">Al</label>
                  <div class="input-group">
                    <input type="date" name="fechaAl" class="form-control" id="fechaAl" value="<?php echo date("Y-m-d"); ?>" requerid>
                  </div>
                </div>
              </div> 
            </div>

            <div class="tab-pane fade" id="pills-recomendaciones" role="tabpanel" aria-labelledby="pills-recomendaciones-tab">
              <div class="row justify-content-md-center mt-2">
                <div class="col-lg-8 offset-lg-2">
                <h3>Recomendaciones / Indicaciones</h3>
                </div>
              </div>

              <div class="row justify-content-md-center">
                <div class="col col-lg-12">
                  <div class="form-group">
                    <textarea name="recomendaciones" id="recomendaciones" cols="30" rows="8" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>
<!--
			  <div class="tab-pane fade" id="pills-interconsultas" role="tabpanel" aria-labelledby="pills-interconsultas-tab">
              <div class="row justify-content-md-center mt-2">
                <div class="col text-info">
                 <h3>INTERCOSULTAS</h3>
                </div>
              </div>

              <div class="row justify-content-md-center">
                <div class="col ">
                  <select name="interconsultas[]" id="interconsultas" class="form-control select" multiple="multiple">
                  <?php
                    //foreach ($interconsultas as $interconsulta) {
                  ?>
                    <option value="<?php echo $interconsulta->idSpecialty;?>"><?php echo $interconsulta->name;?></option>
                  <?php
                   // }
                  ?>
                  </select>
                </div>
              </div> 
            </div> -->
			
            <div class="tab-pane fade" id="pills-miHistoria" role="tabpanel" aria-labelledby="pills-miHistoria-tab">
              <div class="row justify-content-md-center mt-2">
                <div class="col">
                  <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                      <tr>
                        <th scope="col">Tipo</th>
                        <th scope="col">Código</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Profesional</th>
                        <th scope="col">Procedimientos / Exámenes de Laboratorio</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        foreach ($miHistoria as $row) {
                      ?>
                      <tr style="background-color: <?php echo $row->tipo == "PRO" ? "#F5F5F5" : "#607D8B";?>;">
                        <td><?php echo $row->tipo;?></td>
                        <td scope="row"><?php echo str_pad($row->idCita, 6, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo date("d/m/Y",strtotime($row->fechaCita));?></td>
                        <td><?php echo $row->medico;?></td>
                        <td><?php echo $row->tipo == "LAB" ? $row->nombreProcedimiento : $row->especialidad;?></td>
                        <td style="vertical-align:middle;">
                          <?php if($row->tipo == "LAB" and $row->estado == 2) { ?>
                            <a href="javascript:ventana_cita('<?php echo $row->idCita; ?>', '<?php echo $row->tipo; ?>', '<?php echo $row->idUsuario; ?>')" title="Ver detalle de la cita"><i class="fa fa-eye" aria-hidden="true" style="font-size:36px"></i></a>
                          <?php } else if($row->tipo == "PRO") { ?>
                            <a href="javascript:ventana_cita('<?php echo $row->idCita; ?>', '<?php echo $row->tipo; ?>', '<?php echo $row->idUsuario; ?>')" title="Ver detalle de la cita"><i class="fa fa-eye" aria-hidden="true" style="font-size:36px"></i></a>
                          <?php } ?>
                        </td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


        
        <div class="modal-footer text-center w-100">
          <div class="container">
            <div class="row w-100">
              <div class="col-xl-6 offset-xl-3 col-lg-6 offset-lg-3 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-12">
                <input type="hidden" name="idCitaAdd" id="idCitaAdd" value="<?php echo  $this->uri->segment(2);?>">
                <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo  $this->uri->segment(3);?>">
                  <?php if($guardarHclinica == "guardar_historia_clinica" and ($rol == 1 || $rol == 2 || $rol == 7)) { ?>
                    <button type="button" id="btnActualizarHistorial" class="btn btn-success" title="Guardar cita"><i class="fas fa-save"></i> Guardar Historial</button>
                  <?php } ?>
                <button type="button" class="btn btn-light" data-dismiss="modal" title="Cerrar ventana" onclick="window.close();"><i class="fas fa-times-circle"></i> Cerrar</button>
                
              </div>
            </div>
          </div>
        </div>        
        </form>
    </div>

  <?php $this->load->view("scripts"); ?>
  <!-- Select2 -->
  <script src="<?php echo base_url('plugins/select2/js/select2.full.min.js');?>"></script>
  <script src="<?php echo base_url('plugins/bootstrap-switch/js/bootstrap-switch.min.js');?>"></script>
  
  <script>
	$('.select').select2();
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
    
 

    //recetas
    var tbodyReceta = $('#lista_recetas tbody');
    var fila_contenidoReceta = tbodyReceta.find('tr').first().html();
    //Agregar fila nueva.
    $('#lista_recetas .button_agregar_receta').click(function(){
      var fila_nuevaReceta = $('<tr></tr>');
      fila_nuevaReceta.append(fila_contenidoReceta);
      tbodyReceta.append(fila_nuevaReceta);
	  
	  cargar_recetas();
    });

    //Eliminar fila receta.
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

      cargar_procedimientos();
    });
    //Eliminar fila examen medico.
    $('#lista_examenAux').on('click', '.button_eliminar_examenAux', function(){
      var nFilas = $("#lista_examenAux tr").length;
      
      if(nFilas > 2) $(this).parents('tr').eq(0).remove();
    });
	
	//diagnóstico
    var tbodyD = $('#lista_diagnostico tbody');
    var fila_contenidoD = tbodyD.find('tr').first().html();
    //Agregar fila nueva.
    $('#lista_diagnostico .button_agregar_diagnostico').click(function(){
  
      var fila_nuevaD = $('<tr></tr>');
      fila_nuevaD.append(fila_contenidoD);
      tbodyD.append(fila_nuevaD);

      cargar_cie();
    });
    //Eliminar fila diagnóstico.
    $('#lista_diagnostico').on('click', '.button_eliminar_diagnostico', function(){
      var nFilas = $("#lista_diagnostico tr").length;
      
      if(nFilas > 2) $(this).parents('tr').eq(0).remove();
    });
	

    $('input[name="descansoMedico"]').on('switchChange.bootstrapSwitch', function(e, data){
      if(data){
        $('#descanso1').show();
        $('#descanso2').show();
      } else {
        $('#descanso1').hide();
        $('#descanso2').hide();
      }
    });


    
  $('#btnActualizarHistorial').on('click', function() {
    Swal.fire({
    title: '¿Estás seguro de Crear el historial?',
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
            data: $('#frmActualizarCita').serialize(),
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
                    window.opener.location.reload();
                    window.close();
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
  
  
  $('.recetas').select2({
      width: '100%',
    language: "es",
    placeholder: 'Seleccionar',
    minimumInputLength: 3,
    maximumSelectionLength: 20,
    ajax: {
      url: '<?php echo base_url("search-recipes");?>',
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

  function cargar_recetas(){
    $('.recetas').select2({
      width: '100%',
    language: "es",
    placeholder: 'Seleccionar',
    minimumInputLength: 3,
    maximumSelectionLength: 20,
    ajax: {
      url: '<?php echo base_url("search-recipes");?>',
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

  $('.searchCie').select2({
      language: "es",
      placeholder: 'Seleccionar',
      minimumInputLength: 3,
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
  
  function cargar_cie(){
    $('.searchCie').select2({
        language: "es",
        placeholder: 'Seleccionar',
        minimumInputLength: 3,
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
  }
  
  
  function cargar_procedimientos(){
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

  function calculo_imc() {
    var peso =  $('#peso').val();
    var talla =  $('#talla').val();
    var imc = 0;

    if(talla > 0) {
      imc = peso/Math.pow(talla,2);
      imc = imc.toFixed(5); 
    }
    

    $('#imc').val(imc);
  }
  
    function ventana_cita(cita, tipo, user) {
		if(tipo == 'LAB')
		{
		  window.open('<?php echo base_url('pdfinforme/') ?>' + cita + '/' + user + '/0?read-only=true', 'Historial', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=1000,height=700,right = 990,top = 50');
		} else {
		  window.open('<?php echo base_url('update-appointment/') ?>' + cita + '/0' + '?read-only=true', 'Historial', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=1000,height=700,right = 990,top = 50');
		}
	}
  
  function ventana_cita2(cita) {
    window.open('<?php echo base_url('update-appointment/') ?>' + cita + '/0' + '?read-only=true', 'Historial', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=1000,height=700,right = 990,top = 50');
  }
  </script>
</body>

</html>