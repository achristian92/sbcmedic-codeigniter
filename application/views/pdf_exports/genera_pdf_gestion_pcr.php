<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>resultado PCR</title>
  <style>
    @page { margin: 150px 50px; }
    #header { position: fixed; left: 0px; top: -60; right: 0px; height: 20px; background-color: #FFFFFF; }
    #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 150px; text-align: center;}

    body {
      font-family: Helvetica, Arial, sans-serif;
      font-size: 12px;
      line-height: 1.42857143;
      color: #333;
      background-color: #fff;
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
      width:100%;
    }

    .content-table thead tr {
      background-color: #009879;
      color: #ffffff;
      text-align: left;
      font-weight: bold;
    }

    .content-table th,
    .content-table td {
      padding: 15px 15px;
    }

    .content-table tbody tr {
      border-bottom: 1px solid #dddddd;
    }

    .content-table tbody tr:nth-of-type(even) {
      background-color: #b6f1d7;
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
    <img src="<?php echo base_url('img/logo_sbcmedic.png');?>" width="25%">
  </div>

  <div id="content">
     
    <p><h3 style="text-align: center">RESULTADOS</h3></p>
    <table class="content-table">
      <tr>
        <td width="15%"><strong>Paciente:</strong></td>
        <td><?php echo $infoGestionPaciente["cliente"];?></td>
        <td width="18%"><strong>Pasaporte:</strong></td>
        <td width="15%"><?php echo $infoGestionPaciente["pasaporte"];?></td>
      </tr>
      <tr>
        <td><strong>DNI/Carné de Extranjería:</strong></td>
        <td><?php echo $infoGestionPaciente["dni"];?></td>
        <td><strong>Fecha de toma:</strong></td>
        <td><?php echo date("d/m/Y",strtotime($infoGestionPaciente["fecha"]));?></td>
      </tr>
      <tr>

        <td><strong>Edad:</strong> <?php echo $infoGestionPaciente["edad"];?></td>
        <td><strong>Fecha de Nacimiento:</strong> <?php echo date("d/m/Y",strtotime($infoGestionPaciente["fechaNacimiento"]));?></td>
        <td><strong>Médico:</strong></td>
        <td>Particular</td>
      </tr>
    </table>

    <p><h4 style="text-align: center">PRUEBA MOLECULAR SARS - COV - 2 X PCR</h4></p>

    <ul>
      <li style="text-align: justify;"> <strong>METODOLOGÍA</strong> : Basado en la técnica de reacción en cadena de la polimerasa en tiempo real (RT-PCR) para la detección de los genes N y ORF1ab del nuevo coronavirus (2019nCov) (Sars-Cov-2) y un control endógeno Ribonucleasa P para el monitoreo de la colección de la muestra.</li>
	  <li> <strong>ANÁLISIS</strong> : Este sistema de PCR tiene una sensibilidad de 500 copias/ml y una especificidad 100%.</li> 
      <li> <strong>TIPO DE MUESTRA</strong> : Hisopado Orofaríngeo / Nasofaríngeo.</li>
    </ul>

<br>
    <table style="width: 95%;margin: 0 auto;" cellspacing="2" cellpadding="4">
      <tr style="background-color:#8BC34A">
        <td><strong>Examen</strong></td>
        <td style="text-align: center;"><strong>Resultado</strong></td>
        <td><strong>Valores referenciales</strong></td>
        <td><strong>Fecha Validación</strong></td>
      </tr>
      <tr>
        <td>BIOLOGÍA MOLECULAR / SARS - COV - 2 X PCR</td>
        <td style="text-align: center;background-color:<?php echo $infoGestionPaciente["resultado"] == "NEGATIVO" ? "#b6f1d7": "#EE9DA6"; ?>"><strong><?php echo $infoGestionPaciente["resultado"];?></strong></td>
        <td>POSITIVO – NEGATIVO</td>
        <td align="center"><?php echo date("d/m/Y H:m"); ?></td>
      </tr>
 
      <tr>
        <td style="text-align:center;" colspan="4"><img src="<?php echo base_url('img/firma/torres_gallo15334.jpg');?>" alt="Imagen doctor"></td>
      </tr>
    </table>
 
  </div>

  <div id="footer">
    <p class="page" style="color:#20AA6C;"><strong>SBC MEDIC</strong></p><p class="page"><strong>www.sbcmedic.com</strong></p>

  </div>
</body>
</html>