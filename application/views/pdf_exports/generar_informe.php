<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
<title>Informe-Laboratorio</title>
  <style>
    @page { margin: 180px 50px; }
    #header { position: fixed; left: 0px; top: -155px; right: 0px; height: 150px; background-color: #FFFFFF; text-align: center; }
    #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 50px; background-color: #FFFFFF; text-align: center;  }
    #footer .page:after { content: counter(page, upper-roman); }
    
    .row {
      margin-right: -15px;
      margin-left: -15px;
    }

    .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
      position: relative;
      min-height: 1px;
      padding-right: 15px;
      padding-left: 15px;
    }

    .col-lg-12 {
        width: 100%;
    }

    .text-center {
      text-align: center;
    }

    body {
      font-family: Helvetica, Arial, sans-serif;
      font-size: 12px;
      line-height: 1.42857143;
      color: #333;
      background-color: #fff;
    }
  
    table.receta {
      font-family: arial, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }

    table.recetatd, th {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }

    table.receta tr:nth-child(even) {
      background-color: #dddddd;
    }

    div.page_break {
        page-break-before: always;
    }
 
  </style>
</head>
<body>
  <div id="header" style="height: 20%;">
    <img src="<?php echo base_url('img/logo_sbcmedic_lab.jpg');?>" >
  </div>
  <div id="footer">
  <strong>Jr. Ignacio Mariátegui 157 Barranco</strong><br> <a href="https://www.sbcmedic.com" target="_blank" style="color:#27CB82;"><strong>www.sbcmedic.com</strong></a>
  </div>

  <div id="content">
  <?php if($bioquimicas->num_rows() > 0){ ?>
    <table style="width:100%; border-collapse: collapse;">
      <tr>
        <td colspan="4" align="center"><h2>INFORME / RESULTADO</h2></td>
      </tr>
      <tr>
        <td style="width: 12%;"><h3>Paciente:</h3></td>
        <td><?php echo $dataUsuario['paciente']; ?></td>
        <td style="text-align: right;"><h3>MUESTRA:</h3></td>
        <td  style="width: 12%;">SUERO</td>
      </tr>
    </table>
   
      <p><h3><u>BIOQUÍMICO</u></h3></p>
      <table class="receta">
        <tr style="background-color:#616161; color:#fff">
          <th>Examen Realizado</th>
          <th style="text-align: center;">Resultado</th>
          <th style="text-align: center;">Rango Referencial</th>
          <th>Unidades</th>
        </tr>
        <?php
			$perfil66 = null;
			$perfil67 = null;
			$perfil73 = null;
			
			foreach ($bioquimicas->result() as $key => $bioquimica) {
        ?>
        <tr>
           <td><?php echo $bioquimica->nombre;?> <?php if($bioquimica->idPerfil == 334) echo "*"; else  if($bioquimica->idPerfil == 335) echo "**";  else  if($bioquimica->idPerfil == 73) echo "***"; ?></td>
           <?php if ($bioquimica->idExamen == 8) {?>
            <td>
              <table>
                <tr>
                  <td>Proteínas :</td>
                  <td><?php echo $bioquimica->dato1;?></td>
                </tr>
                <tr>
                  <td>Albumina: </td>
                  <td><?php echo $bioquimica->dato2;?></td>
                </tr>
              </table>
            </td>
            <?php } else if ($bioquimica->idExamen == 9) {?>
            <td>
              <table>
                <tr>
                  <td>B. Total :</td>
                  <td><?php echo $bioquimica->dato1;?></td>
                </tr>
                <tr>
                  <td>B. Directa: </td>
                  <td><?php echo $bioquimica->dato2;?></td>
                </tr>
                <tr>
                  <td>B. Indirecta: </td>
                  <td><?php echo $bioquimica->dato3;?></td>
                </tr>
              </table>
            </td>
            <?php } else if ($bioquimica->idExamen == 13) {?>
              <td>
                <table>
                  <tr>
                    <td>Glucosa Basal: </td>
                    <td align="center"><?php echo $bioquimica->dato1;?></td>
                  </tr>
                  <tr>
                    <td>Glucosa 60 Minutos: </td>
                    <td align="center"><?php echo $bioquimica->dato2;?></td>
                  </tr>
                  <tr>
                    <td>Glucosa 120 Minutos: </td>
                    <td align="center"><?php echo $bioquimica->dato3;?></td>
                  </tr>
                </table>
              </td>
            <?php } else if ($bioquimica->idExamen == 19) {?>
              <td>
                <table>
                  <tr>
                    <td colspan="2"><?php echo ($bioquimica->dato5 == 0)? "Negativo" : "Positivo";?></td>
                  </tr>
                  <?php if ($bioquimica->dato2){ ?>
                  <tr>
                    <td>PCR: </td>
                    <td><?php echo $bioquimica->dato2;?></td>
                  </tr>
                  <?php } ?>
                </table>
              </td>
           <?php } else { ?>
            <td align="center"><?php echo $bioquimica->dato1;?></td>
           <?php } if ($bioquimica->idExamen == 13) {?>
            <td>
                <table align="center">
                  <tr>
                    <td>60 - 110</td>
                  </tr>
                  <tr>
                    <td>&lt; 200</td>
                  </tr>
                  <tr>
                    <td>&lt; 140</td>
                  </tr>
                </table>
            </td>
           <?php } else { ?>
            <td align="center"><pre><?php echo $bioquimica->valor_referencial;?></pre></td>
           <?php } if ($bioquimica->idExamen == 9 || $bioquimica->idExamen == 13) {?>
            <td>
                <table align="center">
                  <tr>
                    <td>mg/dl</td>
                  </tr>
                  <tr>
                    <td>mg/dl</td>
                  </tr>
                  <tr>
                    <td>mg/dl</td>
                  </tr>
                </table>
            </td>
           <?php } else { ?>
            <td align="center"><?php echo $bioquimica->unidades;?></td>
          <?php
              } 
              
				$fechaEnvio = $bioquimica->fecha_envio;
				if($bioquimica->idPerfil == 334) $perfil66 = 334;
				if($bioquimica->idPerfil == 335) $perfil73 = 335;
            } 
          ?>
		  
          </tr>
		  <?php if($perfil66 == 334){ ?>
            <tr style="background-color:#b2ebf2;">
              <td colspan="4"> <strong>* PERFIL LIPÍDICO: COLESTEROL/TRIGLICERIDOS/HDL COLESTEROL/LDL COLESTEROL</strong></td>
            </tr>
          <?php } if($perfil73 == 335){ ?>
            <tr style="background-color:#b2ebf2;">
              <td colspan="4"> <strong>*** PERFIL DIABETES</strong></td>
            </tr>			
          <?php } ?>
        </table>
    
    <?php } else { ?>

    <table style="width:100%; border-collapse: collapse;">
      <tr>
        <td colspan="4" align="center"><h2>INFORME / RESULTADO</h2></td>
      </tr>
      <tr>
        <td style="width: 12%;"><h3>Paciente:</h3></td>
        <td><?php echo $dataUsuario['paciente']; ?></td>
      </tr>
    </table>
    <?php } ?>
    <?php if($orinas->num_rows() > 0){ ?>
 

      
      <p><h3><u>ORINA</u></h3></p>
      <table class="receta">
        <tr style="background-color:#616161; color:#fff">
          <th>Examen Realizado</th>
          <th style="text-align: center;">Resultado</th>
          <th style="text-align: center;">Rango Referencial</th>
          <th style="text-align: center;">Unidades</th>
        </tr>
        <?php
          foreach ($orinas->result() as $key => $orina) {
        ?>
        <tr>
           <td><?php echo $orina->nombre;?></td>
           <?php if ($orina->idExamen == 22) {?>
              <td>
                <table>
                  <tr>
                    <td>MC: </td>
                    <td><?php echo $orina->dato1;?></td>
                  </tr>
                  <tr>
                    <td>TAS: </td>
                    <td><?php echo $orina->dato2;?></td>
                  </tr>
                </table>
              </td>
            <?php } else { ?>
            <td align="center"><?php echo $orina->dato1;?></td>
            <?php }  if ($orina->idExamen == 22) {?>
              <td>
                <table align="center">
                  <tr>
                    <td>&lt; 20</td>
                  </tr>
                  <tr>
                    <td>&lt; 30</td>
                  </tr>
                </table>
              </td>
           <?php } else { ?>
            <td align="center"><?php echo $orina->valor_referencial;?></td>
            <?php }  if ($orina->idExamen == 22) {?>
              <td>
                <table align="center">
                  <tr>
                    <td>mg/l</td>
                  </tr>
                  <tr>
                    <td>mg/g</td>
                  </tr>
                </table>
              </td>
           <?php } else { ?>
            <td align="center"><?php echo $orina->unidades;?></td>
            <?php } ?>
        </tr>
    <?php 
      $fechaEnvio = $orina->fecha_envio;
      } }  
    ?>
    </table>
 
    <?php if($completoOrinas->num_rows() > 0) { ?>
 
    <p><h3><u>EXAMEN COMPLETO DE ORINA</u></h3></p>
      <table class="receta">
        <tr>
          <th colspan="4"><u>EXAMEN FÍSICO</u></th>
        </tr>
        <?php
          foreach ($completoOrinas->result() as $key => $completoOrina) {
        ?>
        <tr>
            <th style="width: 25%;">Color: </th>
            <td align="center"><?php echo $completoOrina->color;?></td>
            <th>Aspecto: </th>
            <td align="center"><?php echo $completoOrina->aspecto;?></td>
          </tr>
          <tr>
            <th>PH: </th>
            <td align="center"><?php echo $completoOrina->ph;?></td>
            <th>Densidad: </th>
            <td align="center"><?php echo $completoOrina->densidad;?></td>
        </tr>
        <tr>
          <th colspan="4"><u>EXAMEN BIOQUÍMICO</u></th>
        </tr>
        <tr>
          <th style="width: 25%;">Nitritos: </th>
          <td align="center"><?php echo ($completoOrina->nitrito == 0) ? "Negativo" : "Positivo";?></td>
          <th>Urobilinogeno: </th>
          <td align="center"><?php echo ($completoOrina->urobilinogeno == 0) ? "Negativo" : "Positivo";?></td>
        </tr>
        <tr>
          <th>Glucosa: </th>
          <td align="center"><?php echo ($completoOrina->glucosa == 0) ? "Negativo" : "Positivo";?></td>
          <th>Sangre: </th>
          <td align="center"><?php echo ($completoOrina->sangre == 0) ? "Negativo" : "Positivo";?></td>
        </tr>
        <tr>
          <th>Proteínas: </th>
          <td align="center"><?php echo ($completoOrina->proteina == 0) ? "Negativo" : "Positivo";?></td>
          <th>Cetonas: </th>
          <td align="center"><?php echo ($completoOrina->cetona == 0) ? "Negativo" : "Positivo";?></td>
        </tr>
        <tr>
          <th>Bilirrubinas: </th>
          <td align="center"><?php echo ($completoOrina->bilirrubina == 0) ? "Negativo" : "Positivo";?></td>
          <td colspan="2"></td>
        </tr>
        <tr>
          <th colspan="4"><u>EXAMEN MICROSCÓPICO</u></th>
        </tr>
        <tr>
          <th style="width: 25%;">Cel. Epiteliales: </th>
          <td align="center"><?php echo $completoOrina->cepitelial;?></td>
          <th>Leucocitos: </th>
          <td align="center"><?php echo $completoOrina->leucocito;?></td>
        </tr>
        <tr>
          <th style="width: 25%;">Hematíes: </th>
          <td align="center"><?php echo $completoOrina->hematie;?></td>
          <th>Gérmenes: </th>
          <td align="center"><?php echo $completoOrina->germen;?></td>
        </tr>
		 <tr>
          <th style="width: 25%;">Observaciones: </th>
          <td colspan="3"><?php echo $completoOrina->observacion;?></td>
           
        </tr>
    <?php 
        $fechaEnvio = $completoOrina->fecha_envio;
      }
    ?>
    </table>
    <?php }   ?>


    <?php if($hematologias->num_rows() > 0 ) { ?>
   
   
      <p><h3><u>HEMATOLOGÍA</u></h3></p>
      <table class="receta">
        <tr style="background-color:#616161; color:#fff">
          <th>Examen Realizado</th>
          <th style="text-align: center;">Resultado</th>
          <th style="text-align: center;">Rango Referencial</th>
          <th>Unidades</th>
        </tr>
        <?php
          foreach ($hematologias->result() as $key => $hematologia) {
        ?>
        <tr>
           <td><?php echo $hematologia->nombre;?></td>
           <td align="center"><?php echo $hematologia->dato1;?></td>
           <td align="center"><pre><?php echo $hematologia->valor_referencial;?></pre></td>
           <td align="center" style="width: 15%;"><?php echo $hematologia->unidades;?></td>
        </tr>
        <?php 
          $fechaEnvio = $hematologia->fecha_envio;
          } 
        ?>
      </table>

      <?php } if($hemogramas->num_rows() > 0 ) { ?>
      <p><h4><u>HEMOGRAMA COMPLETO</u></h4></p>


      <table class="receta">
        <tr style="background-color:#616161; color:#fff">
          <th>Examen Realizado</th>
          <th style="text-align: center;">Resultado</th>
          <th style="text-align: center;">Rango Referencial</th>
          <th>Unidades</th>
        </tr>
        <?php
          foreach ($hemogramas->result() as $key => $hemograma) {
          if($hemograma->leu != ""){ 
        ?>
        <tr>
          <td style="width: 20%;">Leucocitos</td>
          <td align="center"><?php echo $hemograma->leu;?></td>
          <td align="center">4.16 - 10.57</td>
          <td align="center">mil/mm3</td>
        </tr>
        <?php } if($hemograma->eri != ""){  ?>
        <tr>
          <td>Eritrocitos</td>
          <td align="center"><?php echo $hemograma->eri;?></td>
          <td align="center">3.88 - 5.60</td>
          <td align="center">mil/mm3</td>
        </tr>
        <?php } if($hemograma->hb != ""){  ?>
        <tr>
          <td>Hemoglobina</td>
          <td align="center"><?php echo $hemograma->hb;?></td>
          <td align="center">14 - 16</td>
          <td align="center">g/dl</td>
        </tr>
        <?php } if($hemograma->htc != ""){  ?>
        <tr>
          <td>Hematocitos</td>
          <td align="center"><?php echo $hemograma->htc;?></td>
          <td align="center">42% - 50%</td>
          <td align="center">%</td>
        </tr>
        <?php } if($hemograma->vcm != ""){  ?>
        <tr>
          <td>VCM: </td>
          <td align="center"><?php echo $hemograma->vcm;?></td>
          <td align="center">80 - 100 </td>
          <td align="center">um3</td>
        </tr>
        <?php } if($hemograma->hcm != ""){  ?>
        <tr>
          <td>HCM: </td>
          <td align="center"><?php echo $hemograma->hcm;?></td>
          <td align="center">27 - 32</td>
          <td align="center">pg</td>
        </tr>
        <?php } if($hemograma->ccmh != ""){  ?>
        <tr>
          <td>CCMH: </td>
          <td align="center"><?php echo $hemograma->ccmh;?></td>
          <td align="center">32 - 36</td>
          <td align="center">g/dl</td>
        </tr>
        <?php } if($hemograma->plaq != ""){  ?>
        <tr>
          <td>Plaquetas</td>
          <td align="center"><?php echo $hemograma->plaq;?></td>
          <td align="center">150 - 450</td>
          <td align="center">mil/mm3</td>
        </tr>
        <?php } if($hemograma->mielocito != ""){  ?>
        <tr>
          <td>Mielocitos</td>
          <td align="center"><?php echo $hemograma->mielocito;?></td>
          <td align="center">0</td>
          <td align="center">%</td>
        </tr>
        <?php } if($hemograma->metamielocito != ""){  ?>
        <tr>
          <td>Metamielocitos</td>
          <td align="center"><?php echo $hemograma->metamielocito;?></td>
          <td align="center"> 0</td>
          <td align="center">%</td>
        </tr>
        <?php } if($hemograma->abastonado != ""){  ?>
        <tr>
          <td>Abastonados</td>
          <td align="center"><?php echo $hemograma->abastonado;?></td>
          <td align="center">0 - 5</td>
          <td align="center">%</td>
        </tr>
        <?php } if($hemograma->segmentado != ""){  ?>
        <tr>
          <td>Segmentados</td>
          <td align="center"><?php echo $hemograma->segmentado;?></td>
          <td align="center">55 - 75</td>
          <td align="center">%</td>
        </tr>
        <?php } if($hemograma->eosinofilo != ""){  ?>
        <tr>
          <td>Eosinofilos</td>
          <td align="center"><?php echo $hemograma->eosinofilo;?></td>
          <td align="center">0 - 4</td>
          <td align="center">%</td>
        </tr>
        <?php } if($hemograma->basofilo != ""){  ?>
        <tr>
          <td>Basofilos</td>
          <td align="center"><?php echo $hemograma->basofilo;?></td>
          <td align="center">0 - 2</td>
          <td align="center">%</td>
        </tr>
        <?php } if($hemograma->linfocito != ""){  ?>
        <tr>
          <td>Linfocitos</td>
          <td align="center"><?php echo $hemograma->linfocito;?></td>
          <td align="center">25 - 35</td>
          <td align="center">%</td>
        </tr>
        <?php } if($hemograma->monocito != ""){  ?>
        <tr>
          <td>Monocitos</td>
          <td align="center"><?php echo $hemograma->monocito;?></td>
          <td align="center">0 - 8</td>
          <td align="center">%</td>
        </tr>
        <?php } if($hemograma->observaciones != ""){  ?>
        <tr>
          <td>Observaciones</td>
          <td colspan="3"><p style="text-align: justify; margin: 2px; padding: 2px;"><?php echo htmlentities(strip_tags($hemograma->observaciones));?></p></td>
        </tr>
         
    <?php 
        } 
        $fechaEnvio = $hemograma->fecha_envio;
    
      } 
    ?>
    </table>



    <?php } if($pSimples->num_rows() > 0 ) { ?>

 
    <p><h3><u>HECES</u></h3></p>
      <table class="receta">
        <?php
          foreach ($pSimples->result() as $key => $pSimple) {
        ?>
        <tr>
            <td colspan="4">RESULTADO:</td>
        </tr>
        <tr>
          <th style="width: 25%;">Color:</th>
          <td align="center"><?php echo $pSimple->color;?></td>
          <th>Aspecto:</th>
          <td align="center"><?php echo $pSimple->aspecto;?></td>
        </tr>
          <tr>
            <th>Moco:</th>
            <td align="center"><?php echo ($pSimple->moco == 0)? "Negativo" : "Positivo";?></td>
            <th>Sangre:</th>
            <td align="center"><?php echo ($pSimple->sangre == 0)? "Negativo" : "Positivo";?></td>
        </tr>
        <tr>
            <td colspan="4">RESULTADO:</td>
        </tr>
        <tr>
            <td colspan="4">EXAMEN MICROSCÓPICO:</td>
        </tr>
        <tr>
          <th style="width: 25%;">Muestra Única</th>
          <td colspan="3"><?php echo $pSimple->muestra1;?></td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;<?php echo $pSimple->observacion;?></td>
        </tr>

    <?php 
      $fechaEnvio = $pSimple->fecha_envio;
      }
    ?>
    </table>


    <?php } if($pSeriados->num_rows() > 0 ) { ?>
    <p><h3><u>HECES SERIADO</u></h3></p>
      <table class="receta">
        <?php
          foreach ($pSeriados->result() as $key => $pSeriado) {
        ?>
        <tr>
            <td colspan="4">RESULTADO:</td>
        </tr>
        <tr>
          <th style="width: 25%;">Color:</th>
          <td align="center"><?php echo $pSeriado->color;?></td>
          <th>Aspecto:</th>
          <td align="center"><?php echo $pSeriado->aspecto;?></td>
        </tr>
          <tr>
            <th>Moco:</th>
            <td align="center"><?php echo ($pSeriado->moco == 0)? "Negativo" : "Positivo";?></td>
            <th>Sangre:</th>
            <td align="center"><?php echo ($pSeriado->sangre == 0)? "Negativo" : "Positivo";?></td>
        </tr>
        <tr>
            <td colspan="4">EXAMEN MICROSCÓPICO:</td>
        </tr>
        <tr>
            <td style="width: 13%;">Muestra N° 1</td>
            <td colspan="3"><?php echo $pSeriado->muestra1;?></td>
        </tr>
        <tr>
            <td>Muestra N° 2</td>
            <td colspan="3"><?php echo $pSeriado->muestra2;?></td>
        </tr>
        <tr>
            <td>Muestra N° 3</td>
            <td colspan="3"><?php echo $pSeriado->muestra3;?></td>
        </tr>
    

    <?php 
        $fechaEnvio = $pSeriado->fecha_envio;
      }
    ?>
    </table>

	
	    <?php } if($datosThevenon->num_rows() > 0 ) { ?>
    <p><h3><u>THEVENON</u></h3></p>
      <table class="receta">
        <?php
          foreach ($datosThevenon->result() as $key => $datoTHEVENON) {
        ?>
        <tr>
            <td colspan="2">RESULTADO:</td>
            <td colspan="2"><?php echo $datoTHEVENON->dato1;?></td>
        </tr>
    
   
    

    <?php 
        $fechaEnvio = $datoTHEVENON->fecha_envio;
      }
    ?>
    </table>
	
	

    <?php } if($rInflamatorias->num_rows() > 0 ) { ?>
    
    <p><h3><u>REACCIÓN INFLAMATORIA</u></h3></p>
      <table class="receta">
        <?php
          foreach ($rInflamatorias->result() as $key => $rInflamatoria) {
        ?>
        <tr>
            <td colspan="4">RESULTADO:</td>
        </tr>
        <tr>
          <th style="width: 25%;">Color </th>
          <td align="center"><?php echo $rInflamatoria->color;?></td>
          <th>Consistencia</th>
          <td align="center"><?php echo $rInflamatoria->aspecto;?></td>
        </tr>
          <tr>
            <th>Moco</th>
            <td align="center"><?php echo ($rInflamatoria->moco == 0)? "Negativo" : "Positivo";?></td>
            <th>Sangre</th>
            <td align="center"><?php echo ($rInflamatoria->sangre == 0)? "Negativo" : "Positivo";?></td>
        </tr>
        <tr>
            <td colspan="4">EXAMEN MICROSCÓPICO:</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;<?php echo $rInflamatoria->muestra1;?></td>
        </tr>
  

    <?php 
        $fechaEnvio = $rInflamatoria->fecha_envio;
      }  
    ?>
    </table>

    <?php } if($datosTestGrahams->num_rows() > 0 ) { ?>
    <p><h3><u>TEST DE GRAHAM</u></h3></p>
      <table class="receta">
        <?php
          foreach ($datosTestGrahams->result() as $datosTestGraham) {
        ?>
        <tr>
            <td colspan="2">RESULTADO:</td>
        </tr>
        <tr>
          <td>EXAMEN MICROSCÓPICO</td>
          <td align="center"><?php echo ($datosTestGraham->dato5 == 0)? "Negativo" : "Positivo";?></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;<?php echo $datosTestGraham->dato4;?></td>
        </tr>
  

    <?php
        $fechaEnvio = $datosTestGraham->fecha_envio;
      }   
    ?>
    </table>

    <?php } if($examenDirectoHongos->num_rows() > 0 ) { ?>
    <p><h3><u>KOH</u></h3></p>
      <table class="receta">
        <?php
          foreach ($examenDirectoHongos->result() as $examenDirectoHongo) {
        ?>
        <tr>
            <td colspan="2">RESULTADO:</td>
        </tr>
        <tr>
          <td style="width:20%;">Muestra</td>
          <td align="center"><?php echo $examenDirectoHongo->dato1;?></td>
        </tr>
        <tr>
          <td>Examen Microscópico</td>
          <td align="center"><?php echo ($examenDirectoHongo->dato5 == 0)? "Negativo" : "Positivo";?></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;<?php echo $examenDirectoHongo->dato4;?></td>
        </tr>
  

        <?php
            $fechaEnvio = $examenDirectoHongo->fecha_envio;
          }   
        ?>
    </table>
    <?php } if($urocultivos->num_rows() > 0 ) { ?>
    <p><h3><u>UROCULTIVO</u></h3></p>
      <table class="receta">
        <tr style="background-color:#616161; color:#fff">
          <th>Examen Realizado</th>
          <th style="text-align: center;">Resultado</th>
          <th style="text-align: center;">Rango Referencial</th>
          <th>Unidades</th>
        </tr>
        <?php
          foreach ($urocultivos->result() as $urocultivo) {
        ?>
 
        <tr>
          <td>Células Epiteliales</td>
          <td align="center"><?php echo $urocultivo->color;?></td>
          <td align="center">Escasas X Campo</td>
          <td align="center"></td>
        </tr>
        <tr>
          <td>Leucocitos</td>
          <td align="center"><?php echo $urocultivo->aspecto;?></td>
          <td align="center">0 -1 X Campo</td>
          <td align="center"></td>
        </tr>
        <tr>
          <td>Hematies</td>
          <td align="center"><?php echo $urocultivo->moco;?></td>
          <td align="center">0 -1 X Campo</td>
          <td align="center"></td>
        </tr>
        <tr>
          <td>Gérmenes</td>
          <td align="center"><?php echo $urocultivo->sangre;?></td>
          <td align="center">Escasas X Campo</td>
          <td align="center"></td>
        </tr>
        <tr>
          <td>Recuento de UFC/ML Coloniales</td>
          <td align="center"><?php echo $urocultivo->muestra1;?></td>
          <td align="center">< 100,000</td>
          <td align="center">ufc/ml</td>
        </tr>
        <tr>
          <td>Coloración Gram</td>
          <td align="center"><?php echo $urocultivo->muestra2;?></td>
          <td align="center">No se observa presencia de bacterias Gram(+) ni Gram(-)</td>
          <td align="center"></td>
        </tr>
        <tr>
          <td>Informe Resultado</td>
          <td colspan="3">&nbsp;<pre><strong><?php echo urls_amigables($urocultivo->observacion);?></strong></pre></td>
        </tr>

        <?php
            $fechaEnvio = $urocultivo->fecha_envio;
          }   
        ?>
    </table>

    <?php } if($coprocultivos->num_rows() > 0 ) { ?>


    
    <p><h3><u>COPROCULTIVO</u></h3></p>
      <table class="receta">
        <?php
          foreach ($coprocultivos->result() as $coprocultivo) {
        ?>
        <tr>
            <td colspan="2">RESULTADO:</td>
        </tr>
        <tr>
          <td style="width:15%;">Color</td>
          <td align="center"><?php echo $coprocultivo->color;?></td>
          <td style="width:15%;">Consistencia</td>
          <td align="center"><?php echo $coprocultivo->aspecto;?></td>
        </tr>
        <tr>
          <td style="width:15%;">Moco</td>
          <td align="center"><?php echo ($coprocultivo->moco == 0)? "Negativo" : "Positivo";?></td>
          <td style="width:15%;">Sangre</td>
          <td align="center"><?php echo ($coprocultivo->sangre == 0)? "Negativo" : "Positivo";?></td>
        </tr>
        <tr>
          <td style="width:20%;">Examen Microscópico</td>
          <td colspan="3"><?php echo $coprocultivo->muestra1;?></td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;<?php echo $coprocultivo->observacion;?></td>
        </tr>
  

        <?php
            $fechaEnvio = $coprocultivo->fecha_envio;
          }   
        ?>
    </table>

    <?php } if($aglutinaciones->num_rows() > 0) { ?>
    <p><h3><u>AGLUTINACIONES</u></h3></p>
      <table class="receta">
        <?php
          foreach ($aglutinaciones->result() as $aglutinacion) {
        ?>
        <tr>
            <td colspan="6">RESULTADO:</td>
        </tr>
        <tr>
          <td style="width:15%;">Ag. Somatico 'O'</td>
          <td align="center"><?php echo ($aglutinacion->somatico == 0)? "Negativo" : "Positivo";?></td>
          <td><?php echo $aglutinacion->obs_somatico;?></td>
          <td style="width:15%;">Ag. Flagelar 'H'</td>
          <td align="center"><?php echo ($aglutinacion->flagelar == 0)? "Negativo" : "Positivo";?></td>
          <td><?php echo $aglutinacion->obs_flagelar;?></td>
        </tr>
        <tr>
          <td style="width:15%;">Ag. Paratifico 'A'</td>
          <td align="center"><?php echo ($aglutinacion->paratificoa == 0)? "Negativo" : "Positivo";?></td>
          <td><?php echo $aglutinacion->obs_paratificoa;?></td>
          <td style="width:15%;">Ag. Paratifico 'B'</td>
          <td align="center"><?php echo ($aglutinacion->paratificob == 0)? "Negativo" : "Positivo";?></td>
          <td><?php echo $aglutinacion->obs_paratificob;?></td>
        </tr>
        <tr>
          <td style="width:15%;">Brucella</td>
          <td align="center"><?php echo ($aglutinacion->brucella == 0)? "Negativo" : "Positivo";?></td>
          <td><?php echo $aglutinacion->obs_brucella;?></td>
          <td colspan="3"></td>
        </tr>

        <?php
            $fechaEnvio = $aglutinacion->fecha_envio;
          }   
        ?>
    </table>


 

    <?php } if($inmunologias->num_rows() > 0) { ?>

    <p><h3><u>INMUNOLOGÍA</u></h3></p>
      <table class="receta">
        <tr style="background-color:#616161; color:#fff">
          <th>Examen Realizado</th>
          <th style="text-align: center;">Resultado</th>
          <th style="text-align: center;">Rango Referencial</th>
          <th>Unidades</th>
        </tr>
        <?php
			$perfil74 = null;
          foreach ($inmunologias->result() as $inmunologia) {
        ?>
        <tr>
          <td><?php echo $inmunologia->nombre;?> <?php if($inmunologia->idPerfil == 339) echo "*"; ?></td>
            <?php if ($inmunologia->idExamen == 59) {?>
            <td align="center"><?php echo ($inmunologia->dato5 == 0)? "Negativo" : "Positivo";?></td>
            <?php } else if ($inmunologia->idExamen == 58 || $inmunologia->idExamen == 61) {?>
            <td align="center"><?php echo ($inmunologia->dato5 == 0)? "No Reactivo" : "Reactivo";?></td>
           <?php } else {  ?>
            <td align="center"><?php echo $inmunologia->dato1;?></td>
           <?php }   ?>
          <td align="center"><pre><?php echo $inmunologia->valor_referencial;?></pre></td>
          <td align="center"><?php echo $inmunologia->unidades;?></td>
        </tr>

        <?php
            $fechaEnvio = $inmunologia->fecha_envio;
			if($inmunologia->idPerfil == 339) $perfil74 = 339;
          }   
        ?>
		<?php if($perfil74 == 339){ ?>
			<tr style="background-color:#b2ebf2;">
				<td colspan="5"> <strong>* PERFIL TIROIDEO (DOSAJE DE TSH, FT4 LIBRE)</strong></td>
			</tr>
		<?php } ?>
    </table>
	
	<?php } if($papanicolaus->num_rows() > 0) { ?>
	<?php  if($inmunologias->num_rows() > 0 || $aglutinaciones->num_rows() > 0 || $urocultivos->num_rows() > 0 || $datosTestGrahams->num_rows() > 0 || $rInflamatorias->num_rows() > 0 || $datosThevenon->num_rows() > 0 || $pSeriados->num_rows() > 0 || $pSimples->num_rows() > 0 || $hematologias->num_rows() > 0 || $completoOrinas->num_rows() > 0 || $orinas->num_rows() > 0  || $bioquimicas->num_rows() > 0 || $hemogramas->num_rows() > 0) { ?>
	 
<div class="page_break"></div>
<?php } ?>
	<p><h3><u>PAPANICOLAOU</u></h3></p>
      <table class="receta">
        <tr style="background-color:#e5e5e5; color:#000">
          <th style="width: 50%;">Tipo de Muestra</th>
          <th>Convencional</th>
        </tr>
        <?php
          foreach ($papanicolaus->result() as $papanicolau) {
        ?>
        <tr>
          <td>&nbsp;&nbsp;&nbsp;Calidad de la Muestra</td>
          <td>&nbsp;<?php echo $papanicolau->calidad_muestra;?></td>
          </tr>
        <tr>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Flora de Doderlein</td>
          <td>&nbsp;<?php echo $papanicolau->flora_doderlein ==1 ? "Presente" : "Ausente";?></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Polimorfonucleares</td>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $papanicolau->polimorfonucleares;?></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hematíes</td>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $papanicolau->hematies;?></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Filamentos Mucoides</td>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $papanicolau->filamentos_mucoides;?></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Candida Albicans</td>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $papanicolau->candida_albicans;?></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gardnerella Vaginalis</td>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $papanicolau->gardnerella_vaginalis;?></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Herpes</td>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $papanicolau->herpes;?></td>
        </tr>
        <?php if(strlen($papanicolau->resultados) > 0) { ?>
        <tr style="background-color:#a9dfe6; color:#000">
          <td>&nbsp;Resultados</td>
          <td><p><?php echo htmlentities(strip_tags($papanicolau->resultados));?></p></td>
        </tr>
        <?php } if(strlen($papanicolau->observaciones) > 0) { ?>
        <tr style="background-color:#757575; color:#fff">
          <td>&nbsp;Observaciones</td>
          <td><p><?php echo htmlentities(strip_tags($papanicolau->observaciones));?></p></td>
        </tr>

        <?php
            }
            $fechaEnvio = $papanicolau->fecha_envio;
          } 
        ?>
    </table>
	
	

    <?php } if($biopsias->num_rows() > 0) { ?>

	<p><h3><u>Biopsia Pequeña</u></h3></p>
	  <table class="receta">
		<?php
		  foreach ($biopsias->result() as $biopsia) {
		?>
		<tr style="background-color:#616161; color:#fff;">
		  <th style="width: 35%;"><strong>Muestra Remitida</strong></th>
		  <td>&nbsp;<?php echo $biopsia->dato1;?></td>
		</tr>
		<tr>
		  <td><strong>Conclusión</strong></td>
		  <td><pre><p><?php echo htmlentities(strip_tags($biopsia->dato4));?></p></pre></td>
		</tr>
		<tr>
		  <td><strong>Examen Macroscópico</strong></td>
		  <td><p><?php echo htmlentities(strip_tags($biopsia->observacion));?></p></td>
		</tr>

		<?php
			$fechaEnvio = $biopsia->fecha_envio;
		  } 
		?>
	</table>

	<?php } if($coprologicoFuncionales->num_rows() > 0) { ?>

	<p><h3><u>COPROLÓGICO FUNCIONAL </u></h3></p>
	  <table class="receta">
		<?php
		  foreach ($coprologicoFuncionales->result() as $cfuncional) {
		?>
		<tr style="background-color:#616161; color:#fff;">
		  <th colspan="2" style="width: 25%;"><strong>Muestra de Heces</strong></th>
		</tr>
		<tr>
		  <td><strong>Examen Macroscópico</strong></td>
		  <td><p><?php echo htmlentities(strip_tags($cfuncional->dato4));?></p></td>
		</tr>
		<tr>
		  <td><strong>Examen Microscópico</strong></td>
		  <td><p><?php echo htmlentities(strip_tags($cfuncional->observacion));?></p></td>
		</tr>

		<?php
			$fechaEnvio = $cfuncional->fecha_envio;
		  } 
		?>
	</table>
	
		<?php } if($vdrls->num_rows() > 0) { ?>

		<p><h3><u>VDRL</u></h3></p>
		  <table class="receta">
			  <tr style="background-color:#616161; color:#fff">
				<th>Examen Realizado</th>
				<th style="text-align: center;">Resultado</th>
				
				<th>Observaciones</th>
			  </tr>
			<?php
			  foreach ($vdrls->result() as $vdrl) {
			?>
			<tr>
			  <td>&nbsp;<strong>VDRL</strong></td>
			  <td align="center">&nbsp;<?php echo $vdrl->dato1 == 1? "Reactivo" : "No reactivo";?></td>
		 
			  <td><p><?php echo htmlentities(strip_tags($vdrl->observacion));?></p></td>
			</tr>
			<?php
 
				
				$fechaEnvio = $vdrl->fecha_envio;
			}  
			?>
		</table>


    	
	<?php } if($secrecionVaginales->num_rows() > 0) { ?>

      <p><h3><u>CULTIVO DE SECRECIÓN VAGINAL</u></h3></p>
      <table class="receta">
      <tr style="background-color:#616161; color:#fff">
        <th>Examen Realizado</th>
        <th style="text-align: center;">Resultado</th>
      </tr>
      <?php
        foreach ($secrecionVaginales->result() as $secrecionVaginal) {
      ?>
      <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;CELULAS</td>
        <td align="center">&nbsp;<?php echo $secrecionVaginal->celulas? $secrecionVaginal->celulas : "-";?></td>
      </tr>
      <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;LEUCOCITOS</td>
        <td align="center">&nbsp;<?php echo $secrecionVaginal->leucocitos? $secrecionVaginal->leucocitos : "-";?></td>
      </tr>
      <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;TRICHOMONAS</td>
        <td align="center">&nbsp;<?php echo $secrecionVaginal->trichomonas? $secrecionVaginal->trichomonas : "-";?></td>
      </tr>
      <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;LEVADURAS</td>
        <td align="center">&nbsp;<?php echo $secrecionVaginal->levaduras? $secrecionVaginal->levaduras : "-";?></td>
      </tr>
      <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;FLORA DODERLEIN</td>
        <td align="center">&nbsp;<?php echo $secrecionVaginal->fdoderlein? $secrecionVaginal->fdoderlein : "-";?></td>
      </tr>
      <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;GERMENES</td>
        <td align="center">&nbsp;<?php echo $secrecionVaginal->germenes? $secrecionVaginal->germenes : "-";?></td>
      </tr>
      <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;SE AISLO</td>
        <td align="center">&nbsp;<?php echo $secrecionVaginal->seaislo? $secrecionVaginal->seaislo : "-";?></td>
      </tr>
      <?php if(strlen($secrecionVaginal->observacion) > 0) { ?>
      <tr>
        <td colspan="2">&nbsp;<strong>OBSERVACIONES:</strong><p style="text-align: justify;"><?php echo htmlentities(strip_tags($secrecionVaginal->observacion));?></p></td>
      </tr>
      <?php
      }
        $fechaEnvio = $secrecionVaginal->fecha_envio;
      }  
      ?>
    </table>

	
		<?php } if($plantillaTotales->num_rows() > 0) { ?>


          <?php
            foreach ($plantillaTotales->result() as $clave => $plantillaTotal) {
              $clave++;
          ?>
          <p><h3><u><?php echo ($plantillaTotal->dato1)? $plantillaTotal->dato1 : $plantillaTotal->nombre;?></u></h3></p>
          <table class="receta">
          <tr style="background-color:#616161; color:#fff">
            <th style="text-align: center;" colspan="2">RESULTADOS</th>
          </tr>
          <tr>
            <td colspan="2" style="padding: 10;"><pre><strong><?php echo urls_amigables($plantillaTotal->dato4);?></strong></pre></td>
          </tr>
          <?php if(strlen($plantillaTotal->observacion) > 0) { ?>
          <tr>
            <td colspan="2" style="padding: 2;"><strong>OBSERVACIONES:</strong><p style="text-align: justify;"><?php echo htmlentities(strip_tags($plantillaTotal->observacion));?></p></td>
          </tr>
		  <?php } ?>
          </table>
		  
          <?php if($plantillaTotales->num_rows() > $clave) { ?>
				<br> <br>
          <?php
            }
           //   <div class="page_break"></div>

            $fechaEnvio = $plantillaTotal->fecha_envio;
        
          }  
          ?>
		  
    <?php }  ?>
      <table style="width: 100%;">
      <tr>
        <td style="width:30%;"></td>
        <td rowspan="2" style="width:30%;"></td>
        <td rowspan="2" style="text-align:center;"><img src="<?php echo base_url('img/firma/torres_gallo15334.jpg');?>" alt="Imagen doctor"></td>
      </tr>
      <tr>
        <td style="width:30%;text-align:center;vertical-align:bottom; padding:0px;margin:0px;background-color:#fff;"><strong>
        <?php 
          if($fechaEnvio){
             echo date("d/m/Y",strtotime($fechaEnvio));
          } else { 
           echo date("d/m/Y"); 
          }
       ?>
        </strong></td>
      </tr>
      <tr>
        <td style="width:30%;text-align:center;border-top: 1px solid #333333;">Fecha de emisión</td>
        <td></td>
        <td style="text-align:center;border-top: 1px solid #333333;">Firma</td>
      </tr>
    </table>
  </div>
</body>
</html>