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
    <form method="POST" action="<?php echo base_url('ocupacional/guardarHClinicaAfiliado'); ?>" id="frmHistorialClinicoAfiliado">
      <div class="row bg-black">
        <div class="col-sm text-center" style=" justify-content: center;align-items: center;">
          <h2>REGISTRO HISTORIA CLÍNICA OCUPACIONAL </h2>
        </div>
      </div>
 


      <div class="row mt-2">
        <div class="col">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-4 col-form-label">AFILIADO</label>
            <div class="col-sm-8">
              <select id="afiliado" name="afiliado" class="searchAfiliado form-control select2" style="width: 100%;" required>
              </select>
            </div>
          </div>
        </div>
        </div>


      <div class="row bg-info">
        <div class="col">
          <h2>ANTECEDENTES OCUPACIONALES</h2>
        </div>
      </div>

      <div class="row">
        <div class="col-sm">

        <table class="table" id="tblAntecedentesOcu">
  <thead>
    <tr>
      <th scope="col">EMPRESA</th>
      <th scope="col">AREA TRABAJO</th>
      <th scope="col">OCUPACIÓN</th>
      <th scope="col">FECHA</th>
      <th scope="col">TIEMPO</th>
      <th scope="col">EXPOSICIÓN OCUPACIONAL</th>
      <th scope="col">EPP</th>
      <td style="color:#28a745;"><button type="button" class="btn btn-outline-success button_agregar_antecedenteOcu" title="Agregar registro"> <i class="fas fa-plus"></i></button></td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <select class="form-control" name="cmbEmpresa[]" id="cmbEmpresa">
          <option value="">SELECCIONAR</option>
          <?php
            foreach ($empresas as $empresa) {
          ?>
            <option value="<?php echo $empresa->id; ?>"><?php echo $empresa->razonSocial; ?></option>
          <?php
            }
          ?>
        </select>
      </td>
      <td><input type="text" name="aTrabajo[]" class="form-control" requerid></td>
      <td>
        <select class="form-control" name="puesto[]">
          <option>SELECCIONAR</option>
          <?php
            foreach ($puestos as $puesto) {
          ?>
            <option value="<?php echo $puesto->id; ?>"><?php echo $puesto->descipcion; ?></option>
          <?php
            }
          ?>
        </select>
      </td>
      <td><input type="date" name="fecha[]" class="form-control" value="<?php echo date("Y-m-d"); ?>" requerid></td>
      <td><input type="text" name="tiempo[]" class="form-control" requerid></td>
      <td><input type="text" name="exposicionOcu[]" class="form-control" requerid></td>
      <td><input type="text" name="epp[]" class="form-control" requerid></td>
      <td><button type="button" class="btn btn-outline-danger button_eliminar_antecedenteOcu" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
    </tr>
  </tbody>
</table>
           
      </div>
      </div>



      <div class="row bg-info">
        <div class="col">
          <h2>ANTECEDENTES PATOLÓGICOS PERSONALES</h2>
        </div>
      </div>

      <div class="row">
        <div class="col-sm">
          <table class="table" id="antecedentesPatPersonales">
            <thead>
              <tr>
                <th colspan="2" scope="col" style="text-align: right;">Niega
                  <div class="form-check form-check-inline">
                    (&nbsp;<input class="form-check-input" type="checkbox" name="niegaPatologicosPersonal" value="1">)
                  </div>
                </th>
                <th style="color:#28a745; width: 10%;"><button type="button" class="btn btn-outline-success button_agregar_antecedentesPatPersonal" title="Agregar registro"> <i class="fas fa-plus"></i></button></th>
              </tr>
            </thead>
            <tbody>
            <tr>
                <td style="width: 20%;">
                  <select class="form-control" name="aPatologicosPersonal[]">
                    <option value="">SELECCIONAR</option>
                    <?php
                      foreach ($aPatologicosPersonales as $aPatologicosPersonal) {
                    ?>
                      <option value="<?php echo $aPatologicosPersonal->id; ?>"><?php echo $aPatologicosPersonal->descipcion; ?></option>
                    <?php
                      }
                    ?>
                  </select>
                </td>
                <td><input type="text" class="form-control" name="observacionApPerso[]" placeholder="Observación"></td>
                <td><button type="button" class="btn btn-outline-danger button_eliminar_antecedentesPatPersonal" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>

      <div class="row">
        <div class="col-sm">
          <table class="table" id="habitosNocivos">
            <thead>
              <tr>
                <th>HÁBITOS NOCIVOS</th>
                <th>TIPO</th>
                <th>CANTIDAD</th>
                <th>FRECUENCIA</th>
                <th style="color:#28a745;"><button type="button" class="btn btn-outline-success button_agregar_habitoNocivo" title="Agregar registro"> <i class="fas fa-plus"></i></button></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <select class="form-control" name="habitoNocivo[]">
                    <option value="">SELECCIONAR</option>
                    <option value="ALC">ALCOHOL</option>
                    <option value="TAB">TABACO</option>
                    <option value="DRO">DROGAS</option>
                  </select>
                </td>
                <td><input type="text" class="form-control" name="tipo[]"></td>
                <td><input type="text" class="form-control" name="cantidad[]"></td>
                <td><input type="text" class="form-control" name="frecuencia[]"></td>
                <td><button type="button" class="btn btn-outline-danger button_eliminar_habitoNocivo" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
              </tr>


            </tbody>
          </table>

        </div>
      </div>




      <div class="row">
        <div class="col-sm">

          <div class="form-group row">
            <label for="statictext" class="col-sm-2 col-form-label">MEDICAMENTOS</label>
            <div class="col">
              <input type="text" class="form-control" name="medicamento">
            </div>
          </div>
        </div>
      </div>








      <div class="row bg-info">
        <div class="col">
          <h2>ANTECEDENTES PATOLÓGICOS FAMILIARES</h2>
        </div>
      </div>

      <div class="row">
        <div class="col-sm">
          <table class="table">

            <tbody>
              <tr>
                <td>PADRE</td>
                <td><input type="text" class="form-control" name="padre"></td>
                <td>MADRE</td>
                <td><input type="text" class="form-control" name="madre"></td>
                <td>HERMANOS</td>
                <td><input type="text" class="form-control" name="hermanos"></td>
                <td>HIJOS VIVOS:</td>
                <td style="width: 6%;"><input type="text" class="form-control" name="hijoVivo"></td>
                <td>N°:</td>
                <td style="width: 5%;"><input type="text" class="form-control" name="numeroHv"></td>
                <td>HIJOS FALLECIDOS:</td>
                <td><input type="text" class="form-control" name="hijoFallecido"></td>
                <td>N°:</td>
                <td style="width: 5%;"><input type="text" class="form-control" name="numeroHf"></td>
              </tr>


            </tbody>
          </table>

        </div>
      </div>







      <div class="row">
        <div class="col-sm">
          <table class="table" id="TblAbsentismos">
            <thead>
              <tr>
                <td colspan="7"><strong>ABSENTISMO: ENFERMEDADES Y ACCIDENTES (Asociado a trabajo o no)</strong></td>
              </tr>
              <tr>
                <th rowspan="2">ENFERMEDAD , ACCIDENTE</th>
                <th></th>
                <th colspan="2" style="vertical-align:middle; text-align: center;">ASOCIADO AL TRABAJO</th>
                <th rowspan="2" style="vertical-align:middle; text-align: center;">AÑO</th>
                <th rowspan="2" style="vertical-align:middle; text-align: center;">DÍAS DE DESCANSO</th>
                <th style="color:#28a745;"><button type="button" class="btn btn-outline-success" title="Agregar registro" id="agregarAbsentismo"> <i class="fas fa-plus"></i></button></th>
              </tr>
              <tr>

                <th style="vertical-align:middle; text-align: center;">Observación</th>
                <th style="vertical-align:middle; text-align: center;">SI</th>
                <th style="vertical-align:middle; text-align: center;">NO</th>
                

              </tr>


            </thead>
            <tbody>
              <tr>
                <td style="width: 15%;">
                  <select class="form-control" name="accidente[]">
                    <option value="">SELECCIONAR</option>
                    <option value="ACC">Accidente</option>
                    <option value="XXX">xxx</option>
                  </select>
                </td>
                <td><input type="text" class="form-control" name="observacionAccidente[]"></td>
                <td align="center"><input class="form-check-input" type="radio" name="cbkOpc[]"  value="1" onclick="test('1', this.value)"></td>
                <td align="center"><input class="form-check-input" type="radio" name="cbkOpc[]"  value="0" onclick="test('1', this.value)" checked>
                      <input type="hidden" name="cbkOpciones[]" id="cbkOpciones1" value="0">
                </td>
                <td style="width: 7%;"><input type="text" class="form-control" name="anio[]"></td>
                <td style="width: 5%;"><input type="text" class="form-control" name="diasDescanso[]"></td>
                <td><button type="button" class="btn btn-outline-danger button_eliminar_absentismo" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
              </tr>


            </tbody>
          </table>

        </div>
      </div>


      <div class="row bg-info">
        <div class="col">
          <h2>EVALUACIÓN MÉDICA</h2>
        </div>
      </div>



      <div class="row">
        <div class="col-sm">

          <table class="table">

            <tbody>
              <tr>
                <td style="width: 10%;"><strong>ANAMNESIS:</strong></td>
                <td><textarea class="form-control" name="anamnesis" rows="3"></textarea></td>
              </tr>
              <tr>
                <td><strong>EXAMEN CLÍNICO:</strong></td>
                <td>
                    <table style="width: 100%;">
                      <tr>
                        <td>TALLA(m)</td>
                        <td><input type="text" class="form-control" name="talla_ec"></td>
                        <td>PESO (Kg.)</td>
                        <td><input type="text" class="form-control" name="peso_ec"></td>
                        <td>IMC</td>
                        <td><input type="text" class="form-control" name="imc_ec"></td>
                        <td>PERÍMETRO  ABDOMINAL</td>
                        <td><input type="text" class="form-control" name="pabdominal_ec"></td>
                      </tr>
                      <tr>
                        <td>F.RESP.</td>
                        <td><input type="text" class="form-control" name="fresp_ec"></td>
                        <td>F.CARD</td>
                        <td><input type="text" class="form-control" name="fcard_ec"></td>
                        <td>PA</td>
                        <td><input type="text" class="form-control" name="pa_ec"></td>
                        <td>TEMPERATURA</td>
                        <td><input type="text" class="form-control" name="temperatura_ec"></td>
                      </tr>
                      <tr>
                        <td>OTROS</td>
                        <td colspan="7"><input type="text" class="form-control" name="otros_ec"></td>
                      </tr>
                      </table>
                </td>
              </tr>
              <tr>
                <td><strong>ECTOSCOPÍA:</strong></td>
                <td><input type="text" class="form-control" name="ectoscopia"></td>
              </tr>
              <tr>
                <td><strong>ESTADO MENTAL:</strong></td>
                <td><input type="text" class="form-control" name="eMental"></td>
              </tr>

            </tbody>
          </table>

        </div>
      </div>



      <div class="row">
        <div class="col-sm">

          <table class="table" id="examenFisicos">
            <thead>
              <tr>
                <th colspan="3">EXAMEN FÍSICO</th>
              </tr>
              <tr>
                <th scope="col">ORGANO O SISTEMA</th>
                <th scope="col" style="text-align:center;">SIN HALLAZGO</th>
                <th scope="col">HALLAZGOS</th>
                <th style="color:#28a745; width:10%;"><button type="button" class="btn btn-outline-success button_agregar_examenFisicox" title="Agregar registro" id="agregarExamenFisico"><i class="fas fa-plus"></i> Agregar</button></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="width: 25%;">
                  <select class="form-control" name="osistemaEmedicas[]" onchange="mitest(this.value, 1)"> 
                    <option value="">SELECCIONAR</option>
               <!--      <?php
                      foreach ($osistemaEmedicas as $osistemaEmedicas) {
                    ?>
                      <option value="<?php echo $osistemaEmedicas->id; ?>"><?php echo $osistemaEmedicas->descipcion; ?></option>
                    <?php
                      }
                    ?> -->
                    <option value="1">CARDIOVASCULAR</option>
                    <option value="2">GENITOURINARIO</option>
                    <option value="3">RESPIRATORIO </option>
                    <option value="4">APARATO DIGESTIVO</option>
                    <option value="5">APARATO LOCOMOTOR</option>
                    <option value="6">BOCA</option>
                    <option value="7">CABELLO</option>
                    <option value="8">COLUMNA</option>
                    <option value="9">CUELLO</option>
                    <option value="10">FARINGE</option>
                    <option value="11">MARCHA</option>
                    <option value="12">MIEMBROS INFERIORES</option>
                    <option value="13">MIEMBROS SUPERIORES</option>
                    <option value="14">NARIZ</option>
                    <option value="15">OÍDOS</option>
                    <option value="16">OJOS Y ANEXOS</option>
                    <option value="17">PIEL</option>
                    <option value="18">SISTEMA LINFÁTICO</option>
                    <option value="19">SISTEMA NERVIOSO</option>
                  </select>
                </td>
                <td align="center" style="width: 15%;">
                  <input class="form-check-input" type="checkbox" value="1" name="sHallazgo[]" onclick="test_so('1', this.checked)">
                  <input type="hidden" name="cbkOpcOS[]" id="cbkOpcOS1" value="0">
                </td>
                <td>
                  <div id="textarea1">
                   <textarea class="form-control" name="hallazgo[]" rows="3"></textarea>
                  </div>
                  <div id="ojosAnexos1" style="display: none;">
                    <div class="container">
                      
                      <div class="row mt-2">
                        <div class="col-sm">
                        <strong>Agudeza Visual</strong>
                        </div>
                        <div class="col-sm">
                          O.D<input type="text" class="form-control" name="av_od[]">
                        </div>
                        <div class="col-sm">
                          O.I<input type="text" class="form-control" name="av_oi[]">
                        </div>
                      </div>

                      <div class="row mt-2">
                        <div class="col-sm">
                        <strong>Con Correctores</strong>
                        </div>
                        <div class="col-sm">
                        O.D <input type="text" class="form-control" name="cc_od[]">
                        </div>
                    
                        <div class="col-sm">
                          O.I<input type="text" class="form-control" name="cc_oi[]">
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-sm">
                        <strong>Fondo de Ojo</strong>
                        </div>
                        <div class="col-sm">
                        O.D <input type="text" class="form-control" name="fo_od[]">
                        </div>
                    
                        <div class="col-sm">
                          O.I<input type="text" class="form-control" name="fo_oi[]">
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-sm">
                        <strong>Visión de colores</strong>
                        </div>
                        <div class="col-sm">
                        O.D <input type="text" class="form-control" name="vc_od[]">
                        </div>
                    
                        <div class="col-sm">
                          O.I<input type="text" class="form-control" name="vc_oi[]">
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-sm">
                        <strong>Vis. Profundidad</strong>
                        </div>
                        <div class="col-sm">
                        Test Mosca <input type="text" class="form-control" name="testMosca[]">
                        </div>
                    
                        <div class="col-sm">
                        Circulos<input type="text" class="form-control" name="circulos[]">
                        </div>
                    
                        <div class="col-sm">
                        Animales<input type="text" class="form-control" name="animales[]">
                        </div>
                      </div>

                    </div>
                  </div>
                  </td>
                <td><button type="button" class="btn btn-outline-danger button_eliminar_examenFisico" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
              </tr>

            </tbody>
          </table>

        </div>
      </div>


      <div class="row bg-info">
        <div class="col">
          <h2>CONCLUSIONES DE EVALUACIÓN PSICOLÓGICA</h2>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <table class="table">

            <tbody>
              <tr>

                <td><textarea class="form-control" name="conclusinEPsicologica" rows="3"></textarea></td>
              </tr>

            </tbody>
          </table>
        </div>
      </div>


      <div class="row bg-info">
        <div class="col">
          <h2>CONCLUSIONES RADIOGRÁFICAS</h2>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <table class="table">
              
            <tbody>
              <tr>

                <td><textarea class="form-control" name="conclusionRadiografia" rows="3"></textarea></td>
              </tr>

            </tbody>
          </table>
        </div>
      </div>





      <div class="row bg-info">
        <div class="col">
          <h2>CONCLUSIÓN AUDIOMETRÍA</h2>
        </div>
      </div>



      <div class="row">
        <div class="col-sm">

          <table class="table" id="cAudimetrias">
            <thead>
              <tr>
                <th colspan="3" style="text-align: right;"><button type="button" class="btn btn-outline-success button_agregar_cAudimetria" title="Agregar registro"><i class="fas fa-plus"></i> Agregar</button></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="width: 25%;">
                  <select class="form-control" name="conceptoAudio[]">
                    <option value="">Seleccionar</option>
                  <?php
                    foreach ($conclusionaudiometrias as $conclusionaudiometria) {
                  ?>
                    <option value="<?php echo $conclusionaudiometria->id; ?>"><?php echo $conclusionaudiometria->descipcion; ?></option>
                  <?php
                    }
                  ?>
                  </select>
                </td>
                <td><input type="text" class="form-control" name="contenidoAudio[]"></td>
                <td><button type="button" class="btn btn-outline-danger button_eliminar_cAudimetria" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
              </tr>

            </tbody>
          </table>

        </div>
      </div>



      <div class="row bg-info">
        <div class="col">
          <h2>CONCLUSIÓN DE ESPIROMETRÍA</h2>
        </div>
      </div>
      <div class="row mt-2">
        <div class="col">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="noAplicaCEsperi" value="1">
            <label class="form-check-label" for="inlineCheckbox1"><strong>NO APLICA</strong></label>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-3 col-form-label">Resultado:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="resultadoEsperi" id="nombres">
            </div>
          </div>
        </div>
        <div class="col">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-3 col-form-label">FEV1:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="fev">
            </div>
          </div>
        </div>
        <div class="col">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-3 col-form-label">FVC:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="fvc">
            </div>
          </div>
        </div>




      </div>


      <div class="row bg-info">
        <div class="col">
          <h2>OTROS</h2>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <table class="table">

            <tbody>
              <tr>

                <td><textarea class="form-control" name="otros" rows="3"></textarea></td>
              </tr>

            </tbody>
          </table>
        </div>
      </div>



      <div class="row bg-info">
        <div class="col">
          <h2>Diagnóstico Médico Ocupacional | CIE - 10</h2>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <table class="table" id="cieDMocupacionales">
            <thead>
              <tr>
                <th colspan="4" style="text-align: right;"><button type="button" class="btn btn-outline-success button_agregar_cieDMocupacional" title="Agregar registro"><i class="fas fa-plus"></i> Agregar</button></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <select name="dMedicoOcupCie[]" class="form-control searchCie"></select>
                </td>
                <td><input type="text" class="form-control" name="descipcionCie[]"></td>
                <td><input type="text" class="form-control" name="conceptoCie[]"></td>
                <td><button type="button" class="btn btn-outline-danger button_eliminar_cieDMocupacional" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>



      <div class="row">
        <div class="col-sm-3">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-3 col-form-label">APTO:</label>
            <div class="col-sm-9">
              <input class="form-check-input" type="checkbox" value="1" name="aptoCieMocupa">
            </div>
          </div>
        </div>
        <div class="col">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-3 col-form-label">APTO CON RESTRICCIONES:</label>
            <div class="col-sm-9">
              <input class="form-check-input" type="checkbox" value="1" name="aptocRestriccionCieMocupa">
            </div>
          </div>
        </div>
        <div class="col-sm-3">

          <div class="form-group row">
            <label for="inputtext" class="col-sm-3 col-form-label">NO APTO:</label>
            <div class="col-sm-9">
              <input class="form-check-input" type="checkbox" value="1" name="noAptoCieMocupa">
            </div>
          </div>
        </div>




      </div>



      <div class="row bg-info">
        <div class="col">
          <h2>Recomendaciones</h2>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <table class="table">

            <tbody>
              <tr>

                <td><textarea class="form-control" name="recomendacion" rows="3"></textarea></td>
              </tr>

            </tbody>
          </table>
        </div>
      </div>


      <div class="row bg-info">
        <div class="col">
          <h2>Exámenes Auxiliares</h2>
        </div>
      </div>



      <div class="row">
        <div class="col-sm">

          <table class="table" id="cEAuxiliar">
            <thead>
              <tr>
                <th colspan="3" style="text-align: right;"><button type="button" class="btn btn-outline-success button_agregar_cEAuxiliar" title="Agregar registro"><i class="fas fa-plus"></i> Agregar</button></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="width: 25%;">
                  <select class="form-control" name="examenesAuxi[]">
                    <option value="">Seleccionar</option>
                    <option value="EOFT">Evaluación oftalmológica</option>
                    <option value="EPSI">Evaluación Psicológica</option>
             
                  </select>
                </td>
                <td><input type="text" class="form-control" name="contenidoAxi[]"></td>
                <td><button type="button" class="btn btn-outline-danger button_eliminar_cEAuxiliar" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
              </tr>

            </tbody>
          </table>

        </div>
      </div>


      <div class="row bg-info">
        <div class="col">
          <h2>Exámenes Laboratorio</h2>
        </div>
      </div>



      <div class="row">
        <div class="col-sm">

          <table class="table" id="cLaboratorio">
            <thead>
              <tr>
                <th colspan="3" style="text-align: right;"><button type="button" class="btn btn-outline-success button_agregar_cLaboratorio" title="Agregar registro"><i class="fas fa-plus"></i> Agregar</button></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="width: 35%;">
                  <select name="examenesLab[]" class="form-control searchExamen"></select>
                </td>
                <td><textarea name="comentarioLab[]" class="form-control" cols="3" rows="3"></textarea></td>
                <td style="width: 15%;"><button type="button" class="btn btn-outline-danger button_eliminar_cLaboratorio" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
              </tr>

            </tbody>
          </table>

        </div>
      </div>

 

      <div class="row">

        <div class="col-12">
          <button type="submit" name="btnRegistrarDatos" id="btnRegistrarDatos" class="btn btn-success btn-block">INGRESAR DATOS</button>
        </div>
      </div>

    </form>



    <br>


    <?php $this->load->view("scripts"); ?>
    <!-- Select2 -->
    <script src="<?php echo base_url('plugins/select2/js/select2.full.min.js'); ?>"></script>
    <script>

      function test(id, value) {
        $('#cbkOpciones' + id).val(value);
      
      }

      function test_so(id, value) {
        if(value) value = 1; else value = 0;
        $('#cbkOpcOS' + id).val(value);
      
      }

      $('.select2').select2();
    
      $('#agregarAbsentismo').click(function(){
        var nFilas = $("#TblAbsentismos tr").length;

        $("#TblAbsentismos>tbody").append(`<tr>
                <td style="width: 15%;">
                  <select class="form-control" name="accidente[]">
                    <option value="">SELECCIONAR</option>
                    <option value="ACC">Accidente</option>
                    <option value="XXX">xxx</option>
                  </select>
                </td>
                <td><input type="text" class="form-control" name="observacionAccidente[]"></td>
                <td align="center"><input class="form-check-input" type="radio" name="cbkOpc${nFilas}[]"   value="1" onclick="test(${nFilas}, this.value)"></td>
                <td align="center"><input class="form-check-input" type="radio" name="cbkOpc${nFilas}[]"  value="0" onclick="test(${nFilas}, this.value)" checked><input type="hidden" name="cbkOpciones[]" id="cbkOpciones${nFilas}" value="0"></td>
                <td style="width: 7%;"><input type="text" class="form-control" name="anio[]"></td>
                <td style="width: 5%;"><input type="text" class="form-control" name="diasDescanso[]"></td>
                <td><button type="button" class="btn btn-outline-danger button_eliminar_absentismo" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
              </tr>`);
      });
    
      //sistemas
      $('#agregarExamenFisico').click(function(){
        var nFilas = $("#examenFisicos tr").length;

        $("#examenFisicos>tbody").append(`<tr>
                <td style="width: 25%;">
                  <select class="form-control" name="osistemaEmedicas[]"   onchange="mitest(this.value, ${nFilas})" > 
                    <option value="">SELECCIONAR</option>
                    <option value="1">CARDIOVASCULAR</option>
                    <option value="2">GENITOURINARIO</option>
                    <option value="3">RESPIRATORIO </option>
                    <option value="4">APARATO DIGESTIVO</option>
                    <option value="5">APARATO LOCOMOTOR</option>
                    <option value="6">BOCA</option>
                    <option value="7">CABELLO</option>
                    <option value="8">COLUMNA</option>
                    <option value="9">CUELLO</option>
                    <option value="10">FARINGE</option>
                    <option value="11">MARCHA</option>
                    <option value="12">MIEMBROS INFERIORES</option>
                    <option value="13">MIEMBROS SUPERIORES</option>
                    <option value="14">NARIZ</option>
                    <option value="15">OÍDOS</option>
                    <option value="16">OJOS Y ANEXOS</option>
                    <option value="17">PIEL</option>
                    <option value="18">SISTEMA LINFÁTICO</option>
                    <option value="19">SISTEMA NERVIOSO</option>
                  </select>
                </td>
                <td align="center" style="width: 15%;"><input class="form-check-input" type="checkbox" value="1" name="sHallazgo[]" onclick="test_so(${nFilas}, this.checked)">
                <input type="hidden" name="cbkOpcOS[]" id="cbkOpcOS${nFilas}" value="0">
                </td>
                <td>
                  <div id="textarea${nFilas}">
                   <textarea class="form-control" name="hallazgo[]" rows="3"></textarea>
                  </div>

                  <div id="ojosAnexos${nFilas}" style="display: none;">
                    <div class="container">
                      
                      <div class="row mt-2">
                        <div class="col-sm">
                        <strong>Agudeza Visual</strong>
                        </div>
                        <div class="col-sm">
                          O.D<input type="text" class="form-control" name="av_od[]">
                        </div>
                        <div class="col-sm">
                          O.I<input type="text" class="form-control" name="av_oi[]">
                        </div>
                      </div>

                      <div class="row mt-2">
                        <div class="col-sm">
                        <strong>Con Correctores</strong>
                        </div>
                        <div class="col-sm">
                        O.D <input type="text" class="form-control" name="cc_od[]">
                        </div>
                    
                        <div class="col-sm">
                          O.I<input type="text" class="form-control" name="cc_oi[]">
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-sm">
                        <strong>Fondo de Ojo</strong>
                        </div>
                        <div class="col-sm">
                        O.D <input type="text" class="form-control" name="fo_od[]">
                        </div>
                    
                        <div class="col-sm">
                          O.I<input type="text" class="form-control" name="fo_oi[]">
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-sm">
                        <strong>Visión de colores</strong>
                        </div>
                        <div class="col-sm">
                        O.D <input type="text" class="form-control" name="vc_od[]">
                        </div>
                    
                        <div class="col-sm">
                          O.I<input type="text" class="form-control" name="vc_oi[]">
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-sm">
                        <strong>Vis. Profundidad</strong>
                        </div>
                        <div class="col-sm">
                        Test Mosca <input type="text" class="form-control" name="testMosca[]">
                        </div>
                    
                        <div class="col-sm">
                        Circulos<input type="text" class="form-control" name="circulos[]">
                        </div>
                    
                        <div class="col-sm">
                        Animales<input type="text" class="form-control" name="animales[]">
                        </div>
                      </div>

                    </div>
                  </div>
                  </td>
                <td><button type="button" class="btn btn-outline-danger button_eliminar_examenFisico" title="Quitar registro"> <i class="fa fa-times" aria-hidden="true"></i></button></td>
              </tr>`);
      });

    function mitest(valor, id){
      if(valor == '16')
      {
        $('#textarea' + id).hide();
        $('#ojosAnexos' + id).show();
      } else {
        $('#textarea' + id).show();
        $('#ojosAnexos' + id).hide();
      }
    }
  
    var frm = $('#frmHistorialClinicoAfiliado');
    $.validator.setDefaults({
      submitHandler: function () {
        
        Swal.fire({
        title: '¿ESTÁS SEGURO DE INGRESAR ESTA INFORMACIÓN?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Seguro!',
        cancelButtonText: 'Cancelar',
      }).then((result) => {
        
        if (result.value) {

        $.ajax({
          type: frm.attr('method'),
          url: frm.attr('action'),
          data: frm.serialize(),
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
                  window.location.replace("<?php echo base_url('ocupacional/registroDatos');?>");
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
    
    $('#frmHistorialClinicoAfiliado').validate({
        rules: {
          afiliado: {
            required: true
          },
          cliente: {
            required: true
          }
        },
        messages: {
          afiliado: {
            required: "Seleccione el afiliado"
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

      //ANTECEDENTES OCUPACIONALES
      var tbody = $('#tblAntecedentesOcu tbody');
      var fila_contenido = tbody.find('tr').first().html();
      //Agregar fila nueva.
      $('#tblAntecedentesOcu .button_agregar_antecedenteOcu').click(function(){
    
        var fila_nueva = $('<tr></tr>');
        fila_nueva.append(fila_contenido);
        tbody.append(fila_nueva);

      });

      //Eliminar fila.
      $('#tblAntecedentesOcu').on('click', '.button_eliminar_antecedenteOcu', function(){
        var nFilas = $("#tblAntecedentesOcu tr").length;
        
        if(nFilas > 2) $(this).parents('tr').eq(0).remove();
      });

      //ANTECEDENTES PATOLÓGICOS PERSONALES
      var tbodyApatoloPersonales = $('#antecedentesPatPersonales tbody');
      var fila_contenidoApatoloPersonales = tbodyApatoloPersonales.find('tr').first().html();
      //Agregar fila nueva.
      $('#antecedentesPatPersonales .button_agregar_antecedentesPatPersonal').click(function(){
    
        var fila_nueva = $('<tr></tr>');
        fila_nueva.append(fila_contenidoApatoloPersonales);
        tbodyApatoloPersonales.append(fila_nueva);

      });

      //Eliminar fila.
      $('#antecedentesPatPersonales').on('click', '.button_eliminar_antecedentesPatPersonal', function(){
        var nFilas = $("#antecedentesPatPersonales tr").length;
        
        if(nFilas > 2) $(this).parents('tr').eq(0).remove();
      });

      //habitos nocivos
      var tbodyHn = $('#habitosNocivos tbody');
      var fila_contenidoHn = tbodyHn.find('tr').first().html();
      //Agregar fila nueva.
      $('#habitosNocivos .button_agregar_habitoNocivo').click(function(){
    
        var fila_nueva = $('<tr></tr>');
        fila_nueva.append(fila_contenidoHn);
        tbodyHn.append(fila_nueva);

      });

      //Eliminar fila.
      $('#habitosNocivos').on('click', '.button_eliminar_habitoNocivo', function(){
        var nFilas = $("#habitosNocivos tr").length;
        
        if(nFilas > 2) $(this).parents('tr').eq(0).remove();
      });

      //ABSENTISMO
      //Eliminar fila.
      $('#TblAbsentismos').on('click', '.button_eliminar_absentismo', function(){
        var nFilas = $("#TblAbsentismos tr").length;

        if(nFilas > 4) $(this).parents('tr').eq(0).remove();
      });

      //EXAMEN FÍSICO
      var tbodyEfisico = $('#examenFisicos tbody');
      var fila_contenidoEfisico = tbodyEfisico.find('tr').first().html();
      //Agregar fila nueva.
      $('#examenFisicos .button_agregar_examenFisico').click(function(){
    
        var fila_nueva = $('<tr></tr>');
        fila_nueva.append(fila_contenidoEfisico);
        tbodyEfisico.append(fila_nueva);

      });

      //Eliminar fila.
      $('#examenFisicos').on('click', '.button_eliminar_examenFisico', function(){
        var nFilas = $("#examenFisicos tr").length;

        if(nFilas > 3) $(this).parents('tr').eq(0).remove();
      });

      //CONCLUSIÓN AUDIOMETRÍA
      var tbodyAudio = $('#cAudimetrias tbody');
      var fila_contenidoAudio = tbodyAudio.find('tr').first().html();
      //Agregar fila nueva.
      $('#cAudimetrias .button_agregar_cAudimetria').click(function(){
    
        var fila_nueva = $('<tr></tr>');
        fila_nueva.append(fila_contenidoAudio);
        tbodyAudio.append(fila_nueva);

      });

      //Eliminar fila.
      $('#cAudimetrias').on('click', '.button_eliminar_cAudimetria', function(){
        var nFilas = $("#cAudimetrias tr").length;

        if(nFilas > 2) $(this).parents('tr').eq(0).remove();
      });

      //Diagnóstico Médico Ocupacional | CIE 
      var tbodycieDmOcupa = $('#cieDMocupacionales tbody');
      var fila_contenidoCieDmoucpa= tbodycieDmOcupa.find('tr').first().html();
      //Agregar fila nueva.
      $('#cieDMocupacionales .button_agregar_cieDMocupacional').click(function(){
    
        var fila_nueva = $('<tr></tr>');
        fila_nueva.append(fila_contenidoCieDmoucpa);
        tbodycieDmOcupa.append(fila_nueva);

        initializeSelect2();

      });

      //Eliminar fila.
      $('#cieDMocupacionales').on('click', '.button_eliminar_cieDMocupacional', function(){
        var nFilas = $("#cieDMocupacionales tr").length;

        if(nFilas > 2) $(this).parents('tr').eq(0).remove();
      });

      

      //ANTECEDENTES examen auxiliares
      var tbody = $('#cEAuxiliar tbody');
      var fila_contenido = tbody.find('tr').first().html();
      //Agregar fila nueva.
      $('#cEAuxiliar .button_agregar_cEAuxiliar').click(function(){
    
        var fila_nueva = $('<tr></tr>');
        fila_nueva.append(fila_contenido);
        tbody.append(fila_nueva);

      });
 
      //Eliminar fila.
      $('#cEAuxiliar').on('click', '.button_eliminar_cEAuxiliar', function(){
        var nFilas = $("#cEAuxiliar tr").length;

        if(nFilas > 2) $(this).parents('tr').eq(0).remove();
      });


      //Diagnóstico examen laboratorios
      var tbodycieDmOcupa = $('#cLaboratorio tbody');
      var fila_contenidoCieDmoucpa= tbodycieDmOcupa.find('tr').first().html();
      //Agregar fila nueva.
      $('#cLaboratorio .button_agregar_cLaboratorio').click(function(){
    
        var fila_nueva = $('<tr></tr>');
        fila_nueva.append(fila_contenidoCieDmoucpa);
        tbodycieDmOcupa.append(fila_nueva);

        initializeSelect2();

      });

      //Eliminar fila.
      $('#cLaboratorio').on('click', '.button_eliminar_cLaboratorio', function(){
        var nFilas = $("#cLaboratorio tr").length;

        if(nFilas > 2) $(this).parents('tr').eq(0).remove();
      });

    initializeSelect2();

    

    function initializeSelect2() {
      $('.searchExamen').select2({
      width: '100%',
      language: "es",
      placeholder: 'Seleccionar',
      minimumInputLength: 3,
      maximumSelectionLength: 10,
      ajax: {
        url: '<?php echo base_url("searchExamen");?>',
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
    }
    

 
    $('.searchAfiliado').select2({
    language: "es",
    placeholder: 'BUSCAR AFILIADO',
    minimumInputLength: 3,
    maximumSelectionLength: 10,
    ajax: {
      url: '<?php echo base_url("ocupacional/searchAffiliate");?>',
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
      

 

/*    
  $('select[name="osistemaEmedicas[]"]').on('change', function() {

    alert(this.value);
    if(this.value == '16')
    {
      $('#textarea').hide();
      $('#ojosAnexos').show();
    } else {
      $('#textarea').show();
      $('#ojosAnexos').hide();
    }

}); */


    </script>
</body>

</html>