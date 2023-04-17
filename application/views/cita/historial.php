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
  <title>SBCMedic | ACTUALIZAR CITA</title>
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

<body style="background: #2980B9;  /* fallback for old browsers */
background: -webkit-linear-gradient(to top, #FFFFFF, #6DD5FA, #2980B9);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to top, #FFFFFF, #6DD5FA, #2980B9); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

">
            <div class="container-fluid">
              <div class="row bg-black">
                <div class="col-sm text-center"  style=" justify-content: center;align-items: center;">
                  <h2>ACTUALIZAR CITA</h2>
                </div>
              </div>
              <form id="frmActualizarCita">
              <div class="row justify-content-md-center mt-2">
                <div class="col text-light">
                 <h3>ANAMNESIS</h3>
                </div>
                <div class="col text-info text-right text-light">
                 <h3>NRO CITA : <span class="badge badge-info" id="nroCita"><?php echo str_pad($this->uri->segment(2), 6, '0', STR_PAD_LEFT); ?></span></h3>
                </div>
              </div>
              <div class="row justify-content-md-center">
                <div class="col col-lg-4">
					<label for="tiempoEnfermedad">Tiempo de Enfermedad</label>
					<input type="text" id="tiempoEnfermedad" name="tiempoEnfermedad" class="form-control" value="<?php echo $historialMedico["tiempo_enfermedad"];?>" <?php if ($this->input->get("read-only")) echo "disabled"; ?> >
					
                  </div>
                <div class="col col-lg-8">
                  <label for="relato">Relato</label>
				          <textarea id="relato" name="relato" class="form-control" cols="2" rows="2" <?php if ($this->input->get("read-only")) echo "disabled"; ?>><?php echo $historialMedico["relato"];?></textarea>
                </div>
              </div> 
              <div class="row justify-content-md-center">
                  <div class="col col-lg-10">
                    <label for="fbiologicas">Funciones Biológicas</label>
                    <textarea id="fbiologicasComentario" name="fbiologicasComentario" class="form-control" cols="2" rows="2" <?php if ($this->input->get("read-only")) echo "disabled"; ?>><?php echo $historialMedico["funciones_biologicas_comentario"];?></textarea>
                  </div>
                  <div class="col col-lg-2">
                    <label for="pa">Normales</label>
                    <div class="custom-control custom-checkbox">
                      <div class="icheck-primary d-inline">
                        <input type="checkbox" name="normales" id="normales"  value="1" <?php echo $historialMedico["normales"]? "checked" : "";?> <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
                        <label for="normales">
                        </label>
                      </div>
                    </div>
                  </div>
              </div>
                            
              <div class="row justify-content-md-center">
                  <div class="col col-lg-2">
                    <label for="fbiologicas">Antecedes Patológicos</label>
                    <input type="checkbox" name="antecedentePatologico" id="antecedentePatologico" data-bootstrap-switch data-on-text="SI&nbsp;&nbsp;&nbsp;" data-off-text="NO" <?php echo $historialMedico["antecedes_patologico"]? "checked" : "";?> <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
                  </div>

                  <div class="col col-lg-2">
                    <label for="pa">Dislipidemia</label>
                    <div class="custom-control custom-checkbox">
                      <div class="icheck-primary d-inline">
                        <input type="checkbox" name="dislipidemia" id="dislipidemia"  value="1"  <?php echo $historialMedico["antecedes_patologico_dislipidemia"]? "checked" : "";?> <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
                        <label for="dislipidemia">
                        </label>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col col-lg-2">
                    <label for="pa">Diabetes</label>
                    <div class="custom-control custom-checkbox">
                      <div class="icheck-primary d-inline">
                        <input type="checkbox" name="diabetes" id="diabetes"  value="1"  <?php echo $historialMedico["antecedes_patologico_diabestes"]? "checked" : "";?> <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
                        <label for="diabetes">
                        </label>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col col-lg-2">
                    <label for="pa">HTA</label>
                    <div class="custom-control custom-checkbox">
                      <div class="icheck-primary d-inline">
                        <input type="checkbox" name="hta" id="hta"  value="1"  <?php echo $historialMedico["antecedes_patologico_hta"]? "checked" : "";?> <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
                        <label for="hta">
                        </label>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col col-lg-2">
                    <label for="pa">Asma</label>
                    <div class="custom-control custom-checkbox">
                      <div class="icheck-primary d-inline">
                        <input type="checkbox" name="asma" id="asma"  value="1"  <?php echo $historialMedico["antecedes_patologico_asma"]? "checked" : "";?> <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
                        <label for="asma">
                        </label>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col col-lg-2">
                    <label for="pa">Gastritis</label>
                    <div class="custom-control custom-checkbox">
                      <div class="icheck-primary d-inline">
                        <input type="checkbox" name="gastritis" id="gastritis"  value="1" <?php echo $historialMedico["antecedes_patologico_gastritis"]? "checked" : "";?> <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
                        <label for="gastritis">
                        </label>
                      </div>
                    </div>
                  </div>

              </div> 
			  
			 <?php if( $codigo_especialidad == 18) { ?>
              <div class="row justify-content-md-center mt-2" style="background-color: #8BC34A; color: #303F9F;">
                <div class="col col-lg-3">
                  <label for="fvr">FUR</label>
                  <input type="text" id="fur" name="fur"  class="form-control" <?php if ($this->input->get("read-only")) echo "disabled"; ?> value="<?php echo $historialMedico["fur"]; ?>">
                </div>
                <div class="col col-lg-3">
                  <label for="rc">RC</label>
                  <input type="text" id="rc" name="rc"  class="form-control" <?php if ($this->input->get("read-only")) echo "disabled"; ?> value="<?php echo $historialMedico["rc"]; ?>">
                </div>
                <div class="col col-lg-2">
                  <label for="gp">G_P</label>
                  <input type="text" id="gp" name="gp"  class="form-control" maxlength="4" <?php if ($this->input->get("read-only")) echo "disabled"; ?> value="<?php echo $historialMedico["gp"]; ?>">
                </div>
                <div class="col col-lg-4  mb-2">
                  <label for="mac">MAC</label>
                  <input type="text" id="mac" name="mac"  class="form-control" <?php if ($this->input->get("read-only")) echo "disabled"; ?> value="<?php echo $historialMedico["mac"]; ?>">
                </div>
              </div>
              <?php } ?>
 
              <div class="row justify-content-md-center mt-2">
                <div class="col col-lg-4 form-inline">
                  <label for="otros">Otros&nbsp;</label>
                  <input type="text" id="otrosap" name="otrosap"  class="form-control" style="width:70%" value="<?php echo $historialMedico["otros_antecedentesp"];?>" <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
                </div>
                <div class="col col-lg-8 form-inline">
                  <label for="otros">Antecedentes familiares</label>
                  &nbsp;<input type="text" id="otrosaf" name="otrosaf" value="<?php echo $historialMedico["otros_antecedentesf"];?>" class="form-control" <?php if ($this->input->get("read-only")) echo "disabled"; ?> >&nbsp;
                  <input type="checkbox" name="antecedesFamiliar" id="antecedesFamiliar" data-bootstrap-switch data-on-text="SI&nbsp;&nbsp;&nbsp;" data-off-text="NO" <?php echo $historialMedico["antecedes_familiar"]? "checked" : "";?> <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
                  
                </div>
              </div> 

              <div class="row justify-content-md-center mt-2">
                <div class="col col-lg-3">
                  <label for="nombre">Reacción adversas medicamentosas</label>
                    <input type="checkbox" id="relacionesam" name="relacionesam" data-bootstrap-switch data-on-text="SI&nbsp;&nbsp;&nbsp;" data-off-text="NO" <?php echo $historialMedico["relaciones_adversas"]? "checked" : "";?> <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
                  </div>
                  <div class="col col-lg-5">
                    <label for="medicamentos">Medicamentos</label>
                    <textarea id="medicamentos" name="medicamentos" class="form-control" cols="2" rows="2" <?php if ($this->input->get("read-only")) echo "disabled"; ?> ><?php echo $historialMedico["medicamentos"];?></textarea>
                  </div>
                  <div class="col col-lg-4">
                    <label for="otros">Otros</label>
                    <textarea id="otrosMedicamentos" name="otrosMedicamentos" class="form-control" cols="2" rows="2" <?php if ($this->input->get("read-only")) echo "disabled"; ?> ><?php echo $historialMedico["otros_medicamentos"];?></textarea>
                  </div>
              </div> 
              <div class="row justify-content-md-center mt-2">
                <div class="col-12">
                  <label for="medicamentosHabituales">Medicamentos habituales Actualmente:</label>
                  <textarea id="medicamentosHabituales" name="medicamentosHabituales" class="form-control" cols="2" rows="2" <?php if ($this->input->get("read-only")) echo "disabled"; ?> ><?php echo $historialMedico["medicamentoHabitual"];?></textarea>
                </div>
              </div>
			  
              <div class="row justify-content-md-center mt-2">
                <div class="col-12">
                  <label for="antecedenteQuirurgico">Antecedes Quirúrgicos :</label>
                  <textarea id="antecedenteQuirurgico" name="antecedenteQuirurgico" class="form-control" cols="2" rows="2" <?php if ($this->input->get("read-only")) echo "disabled"; ?> ><?php echo $historialMedico["antecedente_quirurgico"];?></textarea>
                </div>
              </div>
			  
              <div class="row justify-content-md-center mt-2">
                <div class="col text-light">
                 <h3>EXAMEN FÍSICO</h3>
                </div>
              </div>

              <div class="row justify-content-md-center">
                <div class="col col-lg-3">
                  <label for="pa">P.A</label>
                    <input id="pa" name="pa" class="form-control" value="<?php echo $examenFisicoCita["pa"];?>" <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
                  </div>
                <div class="col col-lg-2">
                  <label for="fc">F.C</label>
                  <input id="fc" name="fc" class="form-control" value="<?php echo $examenFisicoCita["fc"];?>" <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
                </div>
                <div class="col col-lg-2">
                  <label for="fr">F.R</label>
                  <input id="fr"  name="fr" class="form-control" value="<?php echo $examenFisicoCita["fr"];?>" <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
                </div>
                <div class="col col-lg-2">
                  <label for="tt">T<sup>°</sup></label>
                  <input id="tt" name="tt" class="form-control" value="<?php echo $examenFisicoCita["tt"];?>" <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
                </div>
                <div class="col col-lg-3">
                  <label for="sato">Sato2</label>
                  <input id="sato" name="sato" class="form-control" value="<?php echo $examenFisicoCita["sato"];?>" <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
                </div>
 
              </div> 
			  
			  <div class="row justify-content-md-center">
                <div class="col col-lg-4">
                  <label for="peso">Peso</label>
                  <input type="number" id="peso" name="peso" class="form-control" onblur="calculo_imc()" value="<?php echo $examenFisicoCita["peso"];?>" <?php if ($this->input->get("read-only")) echo "disabled"; ?> >
                </div>
                <div class="col col-lg-4">
                  <label for="talla">Talla</label>
                  <input type="number" id="talla" name="talla" class="form-control" min="1" onblur="calculo_imc()" value="<?php echo $examenFisicoCita["talla"];?>" <?php if ($this->input->get("read-only")) echo "disabled"; ?> >
                </div>
                <div class="col col-lg-4">
                  <label for="imc">IMC</label>
                  <input id="imc"  name="imc" class="form-control" readonly value="<?php echo $examenFisicoCita["imc"];?>" <?php if ($this->input->get("read-only")) echo "disabled"; ?> >
                </div>
              </div> 

              <div class="row justify-content-md-center mt-2">
                <div class="col-12">
                  <label for="egenarl">Examen General</label>
                  <textarea id="egenarl" name="egenarl" class="form-control" cols="2" rows="2" <?php if ($this->input->get("read-only")) echo "disabled"; ?>><?php echo $examenFisicoCita["egeneral"];?></textarea>
                </div>
              </div>

              <div class="row justify-content-md-center mt-2">
                <div class="col text-info">
                 <h3>DIAGNÓSTICO</h3>
                </div>
              </div>

            <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <table  id="lista_diagnostico" width="100%" class="table">
                      <thead>
                        <tr>
                          <td style="color:#28a745;width: 75%;"><strong>Diagnóstico</strong></td>
                          <td style="color:#28a745;"><strong>Tipo</strong></td>
                          <td style="color:#28a745;"><button type="button" class="btn btn-success button_agregar_diagnostico" title="Agregar registro" <?php if ($this->input->get("read-only")) echo "disabled"; ?>> <i class="fas fa-plus"></i></button></td>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                            <td>                                
                              <select name="diagnostico[]" class="form-control searchCie" <?php if ($this->input->get("read-only")) echo "disabled"; ?>></select>
                            </td>
                            <td>
                            <select name="tipoDiagnostico[]" class="form-control" <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
                              <option value="DEF">DEFINITIVO</option>
                              <option value="PRE">PRESUNTIVO</option>
                            </select>
                            </td>
                            <td><button type="button" class="btn btn-outline-danger button_eliminar_diagnostico" title="Quitar registro" <?php if ($this->input->get("read-only")) echo "disabled"; ?> > <i class="fa fa-times" aria-hidden="true"></i></button></td>
                        </tr>
                        <?php 
                          foreach ($citaDiagnostico->result() as $row) {
                        ?>
                        <tr>
                            <td>                                
                              <select name="diagnostico[]" class="form-control">
                                <option value="<?php echo $row->id;?>" selected <?php if ($this->input->get("read-only")) echo "disabled"; ?> ><?php echo $row->ci10."-".$row->descripcion;?></option>
                              </select>
                            </td>
                            <td>
                            <select name="tipoDiagnostico[]" class="form-control" <?php if ($this->input->get("read-only")) echo "disabled"; ?> >
                              <option value="NOD" <?php if($row->tipo == "DEF") echo "selected"; else  if($row->tipo == "PRE") echo "selected"; else "selected"; ?> >No definido</option>
                              <option value="DEF" <?php if($row->tipo == "DEF") echo "selected"; else  if($row->tipo == "PRE") echo "selected"; else "selected"; ?>>DEFINITIVO</option>
                              <option value="PRE" <?php if($row->tipo == "PRE") echo "selected"; else  if($row->tipo == "PRE") echo "selected"; else "selected"; ?>>PRESUNTIVO</option>
                            </select>
                            </td>
                            <td><button type="button" class="btn btn-outline-danger button_eliminar_diagnostico" title="Quitar registro" <?php if ($this->input->get("read-only")) echo "disabled"; ?>> <i class="fa fa-times" aria-hidden="true"></i></button></td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="row justify-content-md-center mt-2">
                <div class="col text-info">
                 <h3>PLAN DE TRABAJO</h3>
                </div>
              </div>
              <?php
                $ii = 0;
                $observacion = null;
                foreach ($ptratamiento->result() as $key => $row) {
                  $observacion = $row->observacion;
                  $key++;
              ?>
                <div class="row justify-content-md-center mt-1">
                  <div class="col col-lg-12">
                    <input name="planTratamiento[]" class="form-control" placeholder="<?php echo $key;?>.-" value="<?php echo $row->descripcion;?>" <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
                  </div>
                </div>
              <?php } ?>

              <?php
                for ($i=count($ptratamiento->result()); $i < 3; $i++) { 
                  $ii++
              ?>
                <div class="row justify-content-md-center mt-1">
                  <div class="col col-lg-12">
                    <input name="planTratamiento[]" class="form-control" placeholder="<?php echo count($ptratamiento->result()) + $ii;?>.-" <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
                  </div>
                </div>
              <?php } ?>
 
              <div class="row justify-content-md-center mt-1">
                <div class="col col-lg-12">
                    <textarea id="Observaciones" name="Observaciones" class="form-control" cols="2" rows="2" <?php if ($this->input->get("read-only")) echo "disabled"; ?>><?php echo $observacion;?></textarea>
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
                    <table  id="lista_recetas" width="100%" class="table">
                      <thead>
                        <tr>
                          <td style="color:#28a745;width: 40%;"><strong>Descripción / Presentación</strong></td>
                          <td style="color:#28a745; ;width: 8%;"><strong>Cantidad</strong></td>
                          <td style="color:#28a745;"><strong>Via</strong></td>
                          <td style="color:#28a745;"><strong>Dosificación</strong></td>
                          <td style="color:#28a745;"><strong>Tiempo Tratamiento</strong></td>
                          <td style="color:#28a745;"><button type="button" class="btn btn-success button_agregar_receta" title="Agregar registro" <?php if ($this->input->get("read-only")) echo "disabled"; ?>> <i class="fas fa-plus"></i></button></td>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                            <td>                                
                              <select name="recetas[]" class="form-control recetas" <?php if ($this->input->get("read-only")) echo "disabled"; ?>></select>
                            </td>
                            <td><input type="number" name="cantidad[]" class="form-control" min="1" <?php if ($this->input->get("read-only")) echo "disabled"; ?>></td>
                            <td><input name="via[]" class="form-control" <?php if ($this->input->get("read-only")) echo "disabled"; ?>></td>
                            <td><input name="dosificacion[]" class="form-control" <?php if ($this->input->get("read-only")) echo "disabled"; ?>></td>
                            <td><input name="tiempot[]" class="form-control" <?php if ($this->input->get("read-only")) echo "disabled"; ?>></td>
                            <td><button type="button" class="btn btn-outline-danger button_eliminar_receta" title="Quitar registro" <?php if ($this->input->get("read-only")) echo "disabled"; ?>> <i class="fa fa-times" aria-hidden="true"></i></button>
                              <input type="hidden" name="presentacion[]">
                            </td>
                        </tr>

                        <?php
                          if(count($dataReceta->result()) > 0) {
                            foreach ($dataReceta->result() as $row) {
                        ?>
                        <tr>
                          <td>
                            <?php if($row->idReceta) { ?>
                            <select name="recetas[]" class="form-control selectTwo">
                              <option value="<?php echo $row->idReceta;?>" ><?php echo $row->receta;?></option>
                            </select>
                            <input type="hidden" name="presentacion[]">
                            <?php } else { ?>
                              <input type="text" value="<?php echo $row->receta;?>" class="form-control" readonly>
                              <input type="hidden" name="recetas[]" value="<?php echo $row->nombre;?>" class="form-control" readonly>
                              <input type="hidden" name="presentacion[]" value="<?php echo $row->presentacion;?>">
                            <?php } ?>
                          </td>
						              <td><input type="number" name="cantidad[]" maxlength="2" class="form-control" value="<?php echo $row->cantidad;?>" <?php if ($this->input->get("read-only")) echo "disabled"; ?> ></td>
                          <td><input name="via[]" class="form-control" value="<?php echo $row->via;?>" <?php if ($this->input->get("read-only")) echo "disabled"; ?> ></td>
                          <td><input name="dosificacion[]" class="form-control" value="<?php echo $row->dosificacion;?>" <?php if ($this->input->get("read-only")) echo "disabled"; ?> ></td>
                          <td><input name="tiempot[]" class="form-control" value="<?php echo $row->tiempo_tratamiento;?>" <?php if ($this->input->get("read-only")) echo "disabled"; ?> ></td>
                          <td><button type="button" class="btn btn-outline-danger button_eliminar_receta" title="Quitar registro" <?php if ($this->input->get("read-only")) echo "disabled"; ?> > <i class="fa fa-times" aria-hidden="true"></i></button></td>
                        </tr>
                        <?php } } ?>
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
     
    
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="row justify-content-md-center mt-4">
                <div class="col text-info">
                  <h3>SOLICITUDES</h3>
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
                          <td style="color:#28a745;width: 50%;"><strong>Nombre</strong></td>
                          <td style="color:#28a745;"><strong>Especificaciones</strong></td>
                          <td style="color:#28a745;"><button type="button" class="btn btn-outline-success button_agregar_examenAux" title="Agregar registro" <?php if ($this->input->get("read-only")) echo "disabled"; ?> > <i class="fas fa-plus"></i></button></td>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                            <td>                                
                              <select name="nombreEm[]" class="form-control procedimientos" <?php if ($this->input->get("read-only")) echo "disabled"; ?>></select>
                            </td>
                            <td><input name="especificaciones[]" class="form-control" <?php if ($this->input->get("read-only")) echo "disabled"; ?>></td>
                            <td><button type="button" class="btn btn-outline-danger button_eliminar_examenAux" title="Quitar registro" <?php if ($this->input->get("read-only")) echo "disabled"; ?> > <i class="fa fa-times" aria-hidden="true"></i></button></td>
                        </tr>
                        <?php
                          if(count($dataExamenM->result()) > 0) {
                            foreach ($dataExamenM->result() as $row) {
                        ?>
                        <tr>
                          
                          <td>
							<input type="hidden" name="codigos[]" value="<?php echo $row->id;?>">
                            <?php if($row->codigoTipo) { ?>
                                <select name="nombreEm[]" class="form-control select" <?php if ($this->input->get("read-only")) echo "disabled"; ?> >
                                    <option value="<?php echo $row->codigoTipo;?>" selected><?php echo $row->nombre;?></option>
                                </select>
                            <?php } else { ?>
                                <input name="nombreEm[]" readonly class="form-control" value="<?php echo $row->nombre;?>" <?php if ($this->input->get("read-only")) echo "disabled"; ?> >
                            <?php } ?>
                        </td>
                          <td>
                            <?php if($row->codigoTipo) { ?>
                              <input name="especificaciones[]" class="form-control" value="<?php echo $row->especificaciones;?>" <?php if ($this->input->get("read-only")) echo "disabled"; ?> >
                            <?php } else { ?>
                            <input name="especificaciones[]" readonly class="form-control" value="<?php echo $row->especificaciones;?>" <?php if ($this->input->get("read-only")) echo "disabled"; ?> >
                            <?php } ?>
                          </td>
                          <td><button type="button" class="btn btn-outline-danger button_eliminar_examenAux" title="Quitar registro" <?php if ($this->input->get("read-only")) echo "disabled"; ?> > <i class="fa fa-times" aria-hidden="true"></i></button></td>
                        </tr>
                        <?php } } ?>
                          
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

        <div class="row justify-content-md-center mt-2">
          <div class="col text-info">
            <h3>DESCANSO MÉDICO <input type="checkbox" id="descansoMedico" name="descansoMedico" data-bootstrap-switch data-on-text="SI&nbsp;&nbsp;&nbsp;" data-off-text="NO" <?php echo isset($descansoMedico) ? "checked" : "";?> <?php if ($this->input->get("read-only")) echo "disabled"; ?> ></h3>
          </div>
        </div>

        <div class="row justify-content-md-center" id="descanso1" style="display:<?php echo $descansoMedico ? "show" : "none";?>;">
          <div class="col col-lg-4">
            <label for="tipoDescanso">Tipo de descanso(Total/Parcial)</label>
              <input name="tipoDescanso" class="form-control" value="<?php echo isset($descansoMedico["descripcionTipo"]) ? $descansoMedico["descripcionTipo"] : ""; ?>" <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
          </div>
          <div class="col col-lg-4">
            <label for="fechaDel">Del</label>
            <div class="input-group">
                <input type="date" name="fechaDel" class="form-control" id="fechaDel" value="<?php echo isset($descansoMedico["del"]) ? $descansoMedico["del"] : date("Y-m-d"); ?>" requerid <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
            </div>
          </div>
          <div class="col col-lg-4">
            <label for="fechaAl">Al</label>
            <div class="input-group">
              <input type="date" name="fechaAl" class="form-control" id="fechaAl" value="<?php echo isset($descansoMedico["al"]) ? $descansoMedico["al"] : date("Y-m-d"); ?>" requerid <?php if ($this->input->get("read-only")) echo "disabled"; ?>>
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
              <textarea name="recomendaciones" id="recomendaciones" cols="30" rows="3" class="form-control" <?php if ($this->input->get("read-only")) echo "disabled"; ?>><?php echo $historialMedico["recomendaciones"];?></textarea>
            </div>
          </div>
        </div>

        <div class="modal-footer text-center w-100">
          <div class="container">
            <div class="row w-100">
              <div class="col-xl-6 offset-xl-3 col-lg-6 offset-lg-3 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-12">
                <input type="hidden" name="idCitaUp" id="idCitaUp" value="<?php echo  $this->uri->segment(2);?>">
                <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo  $this->uri->segment(3);?>">
                  <?php 
                    if (empty($this->input->get("read-only"))) {
                    if($actualizarHClinica == "actualizar_historial_clinica" and ($rol == 1 || $rol == 2 || $rol == 7)) { 
                  ?>
                    <button type="button" id="btnActualizarHistorial" class="btn btn-outline-info" title="Actualizar cita"><i class="fas fa-sync"></i> Actualizar Historial</button>
                  <?php } } ?>
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
    title: '¿Estás seguro de Actualizar el historial?',
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
            url: "<?php echo base_url("uCita");?>",
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
                    window.location.reload();
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

  </script>
</body>

</html>