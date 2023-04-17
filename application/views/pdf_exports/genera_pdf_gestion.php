<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>resultado ANTÍGENO</title>
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

    <p><h4 style="text-align: center">PRUEBA DE ANTÍGENO CUALITATIVA PARA SARS COV-2(COVID-19)</h4></p>

    <ul>
      <li> <strong>Descripción del Análisis</strong> : Prueba de inmunoensayo cromatográfico para la detección directa y cualitativa de antígenos SARS.COV-2  en Hisopado Nasal.</li>
      <li> <strong>Tipo de muestra</strong> : Hisopado Nasal.</li>
    </ul>

<br>
    <table style="width: 80%;margin: 0 auto;" cellspacing="2" cellpadding="4">
      <tr style="background-color:#8BC34A">
        <td><strong>Examen</strong></td>
        <td style="text-align: center;"><strong>Resultado</strong></td>
        <td><strong>Valores referenciales</strong></td>
      </tr>
      <tr>
        <td>Prueba de antígeno para SARS COV-2 </td>
        <td style="text-align: center;background-color:#b6f1d7"><strong><?php echo $infoGestionPaciente["resultado"];?></strong></td>
        <td>POSITIVO – NEGATIVO</td>
      </tr>
      <tr>
        <td style="text-align:center;" colspan="3"><img src="<?php echo base_url('img/firma/torres_gallo15334.jpg');?>" alt="Imagen doctor"></td>
      </tr>


    </table>
 


    <table style="width: 100%;margin: 0 auto;" cellspacing="3" cellpadding="5">
      <tr>
        <td colspan="6" style="border-bottom: 1px solid #333333;"><strong>AUTORIZACIÓN DEL CENTRO DE SALUD</strong></td>
      </tr>
      <tr>
        <td colspan="6">Código único de IPRESS 11850</td>
      </tr>
      <tr>
        <td colspan="6" style="border-bottom: 1px solid #333333;"><strong>DATOS DE LA PRUEBA COVID-19 ANTIGENO</strong></td>
      </tr>
      <tr>
        <td>Fabricante	BIOCREDIT</td>
        <td>Marca Comercial	On Step Rapid Test</td>
        <td>Marca Comercial	On Step Rapid Test</td>
        <td>LOTE Nº	H073043SD</td>
        <td>DILUENT HBS002107</td>
      </tr>
 


    </table>
 

 
  </div>

  <div id="footer">
    <p class="page" style="color:#20AA6C;"><strong>SBC MEDIC</strong></p><p class="page"><strong>www.sbcmedic.com</strong></p>

  </div>
</body>
</html>