<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
?>
 
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
<title>Recetas-Exámenes-Médico</title>
  <style>
    @page { margin: 180px 50px; }
    #header { position: fixed; left: 0px; top: -155px; right: 0px; height: 150px; background-color: #FFFFFF; text-align: center; }
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
    <img src="<?php echo base_url('img/logo_sbcmedic.png');?>" width="36%">

    <table style="width:100%; border-collapse: collapse;">
      <tr>
        <td><h3>Paciente: <?php echo $paciente["paciente"]; ?></h3></td>
        <td style="text-align: right;"><h3>Edad: <?php echo $paciente["edad"]; ?></h3></td>
        <td style="text-align: right;"><h3>Nro de documento: <?php echo $paciente["document"]; ?></h3></td>
      </tr>
    </table>
    <br>
  </div>

  <div id="content">
     
    <?php if($diagnostico->num_rows() >0 ){ ?>
      <p><h3>DIAGNÓSTICO</h3></p>
      <table class="receta">
        <tr style="background-color:#616161; color:#fff">
          <th>Código</th>
          <th>Descripción</th>
		  <th style="text-align: center;">Tipo</th>
        </tr>
        <?php
          foreach ($diagnostico->result() as $key => $row) {
        ?>
        <tr>
          <td style="text-align: center;"><?php echo $row->ci10;?></td>
          <td><?php echo $row->descripcion;?></td>
		  <td style="text-align: center;"><?php echo $row->nombreTipo;?></td>
        </tr>
        <?php } ?>
      </table>
      <br>
    <?php } 
      if($dataReceta->num_rows() >0 ) {
    ?>
    <p><h3>RECETA MÉDICA</h3></p>
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
      <pre style="white-space: pre-wrap;"><p><?php echo urls_amigables($historialMedico["recomendaciones"]);?></p></pre>
    <?php 
		}
		
		if($diagnostico->num_rows() >0  || $dataReceta->num_rows() >0) {
	?>
    <br>
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
	<?php 
		}
	?>
    <?php if($dataExamenM->num_rows() >0 ){ ?>
      <?php if($diagnostico->num_rows() >0  || $dataReceta->num_rows() >0){ ?><div class="page_break"></div><?php } ?>
      <p><h3>EXÁMENES AUXILIARES</h3></p>
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

    <?php if(isset($descansoMedico["dias"]) and strlen($descansoMedico["dias"]) >0 ) { ?>
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