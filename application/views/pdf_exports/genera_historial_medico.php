<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Historial Clínica</title>
    <style>
      @page { margin: 180px 50px; }
      #header { position: fixed; left: 0px; top: -155px; right: 0px; height: 150px; background-color: #FFFFFF; text-align: left; }
      #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 150px; background-color: lightblue; }
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

      table.receta th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
      }

      table.receta tr:nth-child(even) {
        background-color: #dddddd;
      }

      table.historial {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
      }

      table.historial th {
        text-align: center;
        padding: 3px;
      }

      table.historial td {
        border: 1px solid #9b9b9b;
        text-align: left;
        padding: 3px;
      }

      div.page_break {
        page-break-before: always;
      }

      span.grey {
        background: #01B670 ;
        border-radius: 0.8em;
        -moz-border-radius: 0.8em;
        -webkit-border-radius: 0.8em;
        color: #fff;
        display: inline-block;
        font-weight: bold;
        margin-right: 15px;
        text-align: center;
        width: 1.8em; 
      }

      .content-table {
      border: 2px solid #009879;
      border-collapse: collapse;
      margin: 25px 0;
      font-size: 0.9em;
      min-width: 400px;
      border-radius: 5px 5px 0 0;
      overflow: hidden;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
      margin-left: auto;
      margin-right: auto;
      width:60%;
    }

    .content-table thead tr {
      background-color: #009879;
      color: #ffffff;
      text-align: left;
      font-weight: bold;
    }

    .content-table th,
    .content-table td {
      padding: 12px 15px;
    }

    .content-table tbody tr {
      border-bottom: 1px solid #dddddd;
    }

    .content-table tbody tr:nth-of-type(even) {
      background-color: #f3f3f3;
    }

    .content-table tbody tr:last-of-type {
      border-bottom: 2px solid #009879;
    }

    .content-table tbody tr.active-row {
      font-weight: bold;
      color: #009879;
    }
    </style>
</head>
<body>
  <div id="header">
    <table border="0" width="100%">
      <tr>
        <td rowspan="3"><img src="<?php echo base_url('img/logo_sbcmedic.png');?>"></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><h2>HCN°</h2></td>
        <td style="border: black 1px solid;text-align: center;"><h2><?php echo $paciente["document"]; ?></h2></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td style="text-align: center;"><?php echo str_pad($historialMedico["numeroCorrelativo"], 5,"0", STR_PAD_LEFT);?></td>
      </tr>
    </table>
  </div>

  <div id="content">
    <table class="historial">
      <thead>
        <tr style="background-color:#616161; color:#fff">
          <th colspan="6" style="font-size: 14px;">HISTORIA CLÍNICA</th>
      </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="2"  >Paciente: <?php echo $paciente["paciente"]; ?></td>
          <td style="width:7%;">Fecha</td>
          <td style="width:10%;text-align: center;"><?php echo date("d/m/Y",strtotime($infoCita["fechaUsuarioCierre"]));?></td>
          <td style="width:12%;">Hora de atención</td>
          <td style="width:8%;text-align: center;"><?php echo date("H:i",strtotime($infoCita["fechaUsuarioCierre"]));?></td>
        </tr>
        <tr>
          <td colspan="2"  >DNI: <?php echo $paciente["document"]; ?></td>
          <td style="width:7%;">Edad</td>
          <td style="width:10%;text-align: center;"><?php echo $paciente["edad"]; ?></td>
          <td style="width:10%;">Sexo</td>
          <td style="width:8%;text-align: center;"><?php echo $paciente["sex"]; ?></td>
        </tr>
        <tr>
          <td colspan="2">Fecha de Nacimiento: <?php echo date("d/m/Y",strtotime($paciente["birthdate"])); ?></td>
          <td style="width:7%;">Teléfono</td>
          <td colspan="3" style="width:10%;"><?php echo $paciente["phone"]; ?></td>
        </tr>
        <tr>
          <td colspan="2">Dirección: <?php echo $paciente["address"]; ?></td>
          <td style="width:7%;">Distrito</td>
          <td colspan="3" style="width:10%;"><?php echo $paciente["distrito"]; ?></td>
        </tr>
        <tr>
            <td colspan="6"><strong>Anamnesis</strong></td>
        </tr>
        <tr>
          <td  style="width:13%;">Tiempo de enfermedad </td>
          <td style="width:25%;"><?php echo $historialMedico["tiempo_enfermedad"]; ?></td>
          <td  colspan="4">Relato: <?php echo $historialMedico["relato"]; ?></td>
        </tr>
        <tr>
          <td  colspan="4">Funciones biológicas: <?php echo $historialMedico["funciones_biologicas_comentario"] ; ?></td>
          <td style="width:10%;">Normales</td>
          <td style="text-align: center;vertical-align: middle;">&nbsp;<input type="checkbox"<?php echo ($historialMedico["normales"] == 0)? "" : "checked='checked'"; ?>/></td>
        </tr>
        <tr>
          <td  colspan="6">Antecedentes patológicos <span class="grey"><?php echo ($historialMedico["antecedes_patologico"] == 0)? "NO" : "SI"; ?></span> &nbsp;
            <input type="radio" id="male" name="gender" value="male" <?php echo ($historialMedico["antecedes_patologico_dislipidemia"] == 1)? "checked" : ""; ?> > &nbsp;Dislipidemia &nbsp;
            <input type="radio" id="male" name="gender" value="male" <?php echo ($historialMedico["antecedes_patologico_diabestes"] == 1)? "checked" : ""; ?> > &nbsp;Diabetes &nbsp;
            <input type="radio" id="male" name="gender" value="male" <?php echo ($historialMedico["antecedes_patologico_hta"] == 1)? "checked" : ""; ?>> &nbsp;HTA &nbsp;
            <input type="radio" id="male" name="gender" value="male" <?php echo ($historialMedico["antecedes_patologico_asma"] == 1)? "checked" : ""; ?>> &nbsp;Asma&nbsp;
            <input type="radio" id="male" name="gender" value="male" <?php echo ($historialMedico["antecedes_patologico_gastritis"] == 1)? "checked" : ""; ?> >
            <label for="male">Gastritis</label>
          </td>
        </tr>
        <tr>
          <td colspan="2">Otros: <?php echo $historialMedico["otros_antecedentesp"]; ?></td>
          <td colspan="4" style="height: 25px;">Antecedentes familiares: <span class="grey"><?php echo ($historialMedico["antecedes_familiar"] == 0)? "NO" : "SI"; ?></span><?php echo $historialMedico["otros_antecedentesf"]; ?></td>
        </tr>
        <tr>
          <td style="height: 25px;">Reacción adversas medicamentosas: <span class="grey"><?php echo ($historialMedico["relaciones_adversas"] == 0)? "NO" : "SI"; ?></span></td>
          <td colspan="3">Medicamentos: <?php echo $historialMedico["medicamentos"]; ?></td>
          <td colspan="2">Otros: <?php echo $historialMedico["otros_medicamentos"]; ?></td>
        </tr>
        <tr>
          <td style="height: 25px;" colspan="6">Medicamentos habituales Actualmente: <strong><?php echo $historialMedico["medicamentoHabitual"]; ?></strong></td>
        </tr>
        <tr>
            <td colspan="6"><strong>Examen Físico</strong></td>
        </tr>
        <tr>
          <td>PA: <strong><?php echo $examenFisico["pa"]; ?></strong></td>
          <td>F.C: <strong><?php echo $examenFisico["fc"]; ?></strong></td>
          <td>F.R: <strong><?php echo $examenFisico["fr"]; ?></strong></td>
          <td>T<sup>°</sup>: <strong><?php echo $examenFisico["tt"]; ?></strong></td>
          <td colspan="2">Sato2: <strong><?php echo $examenFisico["sato"]; ?></strong></td>
        </tr>
		        <tr>
          <td>Peso: <strong><?php echo $examenFisico["peso"]; ?></strong></td>
          <td>Talla: <strong><?php echo $examenFisico["talla"]; ?></strong></td>
          <td colspan="4">IMC: <strong><?php echo $examenFisico["imc"]; ?></strong></td>
 
        </tr>
        <tr>
          <td colspan="6">Examen General:  <strong><?php echo urls_amigables($examenFisico["egeneral"]); ?></strong> </td>
        </tr>
      </tbody>
    </table>
    <?php if($diagnostico->num_rows() >0 ){ ?>
      <p><h4>Diagnóstico CIE-10</h4></p>
      <table class="receta">
        <tr style="background-color:#616161; color:#fff">
          <th>Código</th>
          <th>Descripción</th>
		  <th>Tipo</th>
        </tr>
      <?php
        foreach ($diagnostico->result() as $key => $row) {
      ?>
        <tr>
          <td style="text-align: center;"><?php echo $row->ci10;?></td>
          <td><?php echo $row->descripcion;?></td>
		   <td><?php echo $row->nombreTipo;?></td>
        </tr>
      <?php } ?>
      </table>
    <?php if($diagnostico->num_rows() >0) { ?> <div class="page_break"></div> <?php } ?>

  <?php } if($ptratamiento->num_rows() >0 ){ ?>
  <p><h4>Plan de tratamiento</h4></p>
  <table class="receta">
    <tr style="background-color:#616161; color:#fff">
      <th>#</th>
      <th>Descripción</th>
    </tr>
    <?php
      $observaciones = "";
      foreach ($ptratamiento->result() as $key => $row) {
        $observaciones = $row->observacion;
        $key++;
    ?>
    <tr>
      <td width="5%" style="text-align: center;"><?php echo $key;?></td>
      <td><?php echo $row->descripcion;?></td>
    </tr>
  <?php } ?>
    <tr>
      <td colspan="2">Observacion: <strong><?php echo $observaciones;?></strong></td>
    </tr>
  </table>
 
  <?php } 
    if($dataReceta->num_rows() >0 ) {
  ?>
    <p><h3>Receta Médica</h3></p>
    <table class="receta">
      <tr style="background-color:#616161; color:#fff">
        <th></th>
        <th>Nombre</th>
        <th>Presentación</th>
		<th>Cantidad</th>
        <th>Vía</th>
        <th>Dosificación</th>
        <th>Tiempo de tratamiento</th>
      </tr>
      <?php
        foreach ($dataReceta->result() as $key => $row) {
          $key++;
      ?>
      <tr>
        <td style="text-align: center;"><?php echo $key;?></td>
        <td><?php echo $row->nombre;?></td>
        <td><?php echo $row->presentacion;?></td>
		<td style="text-align: center;"><?php echo $row->cantidad;?></td>
        <td style="text-align: center;"><?php echo $row->via;?></td>
        <td><?php echo $row->dosificacion;?></td>
        <td><?php echo $row->tiempo_tratamiento;?></td>
      </tr>
      <?php } ?>
    </table>
    <?php }  ?>

    <?php if(strlen($historialMedico["recomendaciones"]) > 0) { ?>
      <br>
      <br>
      <br>
      <p><h3>RECOMENDACIONES / INDICACIONES</h3></p>
      <pre style="white-space: pre-wrap;"><p><?php echo $historialMedico["recomendaciones"];?></p></pre>
    <?php } ?>
    <br>
    <?php if($dataExamenM->num_rows() >0 ){ ?>
	    <br>
      <p><h3>Exámenes Auxiliares</h3></p>
      <table class="receta">
        <tr style="background-color:#616161; color:#fff">
          <th></th>
          <th>Nombre</th>
          <th>Especificaciones</th>
          <th>Especialidad</th>
        </tr>
        <?php
          foreach ($dataExamenM->result() as $key => $row) {
            $key++;
        ?>
        <tr>
          <td style="text-align: center;"><?php echo $key;?></td>
          <td><?php echo $row->nombre;?></td>
          <td><?php echo $row->especificaciones;?></td>
          <td><?php echo $row->especialidad;?></td>
        </tr>
        <?php } ?>
      </table>
    <?php } ?>
    <table style="width: 100%;">
      <tr>
        <td style="width:30%;"></td>
        <td rowspan="2" style="width:30%;"></td>
        <td rowspan="2" style="text-align:center;"><img src="<?php echo base_url('img/firma/'.$infoCita["nombreMedicoImg"].'.jpg');?>" alt="Imagen doctor"></td>
      </tr>
      <tr>
        <td style="width:30%;text-align:center;vertical-align:bottom; padding:0px;margin:0px;"><strong><?php echo date("d/m/Y",strtotime($infoCita["fechaUsuarioCierre"]));?></strong></td>
      </tr>
      <tr>
        <td style="width:30%;text-align:center;border-top: 1px solid #333333;">Fecha de emisión</td>
        <td></td>
        <td style="text-align:center;border-top: 1px solid #333333;">Firma</td>
      </tr>
    </table>

    
    <?php if(strlen($descansoMedico["dias"]) >0 ){ ?>
      <div class="page_break"></div>

      <table class="content-table">
        <thead>
          <tr>
            <th colspan="2" style="text-align: center;font-size:15px;">DESCANSO MÉDICO</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td width="30%">Paciente:</td>
            <td><?php echo $paciente["paciente"]; ?></td>
          </tr>
          <tr>
            <td>H.C.N°:</td>
            <td><strong><?php echo $paciente["document"]; ?></strong></td>
          </tr>
          <tr class="active-row">
            <td>Diagnóstico:</td>
            <td><?php echo $descansoMedico["diagnostico"]; ?></td>
          </tr>
          <tr>
            <td>Tipo de descanso:</td>
            <td><?php echo $descansoMedico["descripcionTipo"]; ?></td>
          </tr>
          <tr>
            <td>Días:</td>
            <td><?php echo $descansoMedico["dias"]; ?></td>
          </tr>
          <tr>
            <td>Fecha / Hora:</td>
            <td><?php echo date("d/m/Y H:m",strtotime($infoCita["fechaUsuarioCierre"]));?></td>
          </tr>
          <tr class="active-row">
            <td>Del: </td>
            <td><?php echo date("d/m/Y",strtotime($descansoMedico["del"]));?> &nbsp;&nbsp;Al:&nbsp; <?php echo date("d/m/Y",strtotime($descansoMedico["al"]));?></td>
          </tr>
          <tr>
            <td>Médico:</td>
            <td><?php echo $descansoMedico["medico"]; ?> &nbsp;&nbsp;CMP:&nbsp; <?php echo $descansoMedico["cmp"]; ?></td>
          </tr>
          <tr>
            <td>Especialidad:</td>
            <td><?php echo $descansoMedico["especialidad"]; ?></td>
          </tr>
        </tbody>
      </table>

      <table style="width: 100%;">
        <tr>
          <td style="width:30%;"></td>
          <td rowspan="2" style="width:30%;"></td>
          <td rowspan="2" style="text-align:center;"><img src="<?php echo base_url('img/firma/'.$infoCita["nombreMedicoImg"].'.jpg');?>" alt="Imagen doctor"></td>
        </tr>
        <tr>
          <td style="width:30%;text-align:center;vertical-align:bottom; padding:0px;margin:0px;"><strong><?php echo date("d/m/Y",strtotime($infoCita["fechaUsuarioCierre"]));?></strong></td>
        </tr>
        <tr>
          <td style="width:30%;text-align:center;border-top: 1px solid #333333;">Fecha de emisión</td>
          <td></td>
          <td style="text-align:center;border-top: 1px solid #333333;">Firma</td>
        </tr>
      </table>
    <?php } ?>
  </div>
</body>
</html>