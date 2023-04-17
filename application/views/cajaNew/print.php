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
  <title>SBCMedic | TICKET PRINT</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <style>
    table.recibo {
      border-collapse: collapse;
      font-size: 10pt;
      font-family: 'Times New Roman', Times, serif;
    }

    table.recibo td,
    th {
      border: 1px solid #999;
      padding: 0.5rem;
      text-align: left;
      font-size: 10pt;
      font-family: 'Times New Roman', Times, serif;
    }
  </style>
</head>

<body>
<div class="container">
         <div class="row">
            <div class="col-sm">
      <table  class="recibo" style="border-collapse: collapse; border-width: 3px; border-style: dashed; width:60%" class="table table-bordered" style="width:100%;">
        <tr>
            <td colspan="2" style="text-align: center;"><img src="<?php echo base_url('img/logo_sbcmedic.png');?>" alt="logo" width="50%"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;"><h4><strong><?php echo $resultados[0]["name"]. " - NRO CITA ". $this->uri->segment(3); ?></strong></h4></td>
        </tr>
        <tr>
          <td style="width: 40%;"><strong>FECHA DE LA CITA</strong></td>
          <td style="text-align: center;"><?php echo date("d/m/Y",strtotime($resultados[0]["fechaCita"]));?></td>          
        </tr>
        <tr>
          <td><strong>HORA DE LA CITA</strong></td>
          <td style="text-align: center;"><?php echo $resultados[0]["horaCita"]; ?></td>          
        </tr>
        <tr>
          <td><strong>MÉDICO</strong></td>
          <td style="text-align: center;"><?php echo $resultados[0]["medico"]; ?></td>          
        </tr>
   

        <?php if(!empty($resultados[0]["titulo"])) { ?>
        <tr>
          <td><strong>PROCEDIMIENTO PRINCIPAL</strong></td>
          <td style="text-align: center;"><u><i><strong><?php echo $resultados[0]["titulo"]; ?></strong></i></u></td>          
        </tr>
        <?php } ?>

        <?php if(strlen(trim($resultados[0]["descripcion"])) >0 ) { ?>
        <tr>
          <td><strong>MOTIVO CITA</strong></td>
          <td style="text-align: center;"><u><i><strong><?php echo $resultados[0]["descripcion"]; ?></strong></i></u></td>          
        </tr>
        <?php } ?>
        <tr>
          <td><strong>CONSULTORIO</strong></td>
          <td style="text-align: center;"></td>          
        </tr>
        <tr>
          <td><strong>PACIENTE</strong></td>
          <td style="text-align: center;"><?php echo $resultados[0]["paciente"]; ?></td>          
        </tr>
        <tr>
          <td><strong>HISTORIAL CLÍNICO</strong></td>
          <td style="text-align: center;"><?php echo $usuarioCita["document"] ; ?></td>          
        </tr>
        <tr>
          <td><strong>MONTO (S./)</strong></td>
          <td style="text-align: center;"><?php echo $resultados[0]["monto"] ; ?></td>          
        </tr>	
        <tr>
          <td><strong>TERMINALISTA</strong></td>
          <td style="text-align: center;"><?php echo $terminalista["firstname"]. " ". $terminalista["lastname"];?></td>          
        </tr>
  </table>
  <table style="width: 60%;  font-size: 10pt; font-family: 'Times New Roman', Times, serif;" CELLPADDING="5" CELLSPACING="5" border="0">
    <tr>
      <td>FECHA: <strong><?php echo date("d/m/Y");?></strong></td>
      <td>HORA:  <strong><?php echo date("H:m");?></strong></td>
    </tr>
    <tr>
      <td colspan="2" style="text-align: center;"><strong><u>LLEGAR 15 MINUTOS ANTES DE LA CITA Y CON SU DNI</u>.</strong></td>
    </tr>
  </table>

  <br>

      <table  class="recibo" style="border-collapse: collapse; border-width: 4px; border-style: dashed; width:60%" class="table table-striped table-hover table-bordered" style="width:100%;">
        <tr>
          <td colspan="2" style="text-align: center;"><h4><strong>TRIAJE</strong></h4></td>
        </tr>
        <tr>
          <td style="width: 40%;"><strong>PESO</strong></td>
          <td style="text-align: center;"></td>          
        </tr>
        <tr>
          <td><strong>TALLA</strong></td>
          <td style="text-align: center;"></td>          
        </tr>
        <tr>
          <td><strong>P. ABD.</strong></td>
          <td style="text-align: center;"></td>          
        </tr>
        <tr>
          <td><strong>FC.</strong></td>
          <td style="text-align: center;"></td>          
        </tr>
        <tr>
          <td><strong>FR.</strong></td>
          <td style="text-align: center;"></td>          
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td style="text-align: center;">&nbsp;</td>          
        </tr>
        <tr>
          <td><strong>HORA DE SALIDA</strong></td>
          <td style="text-align: center;"></td>          
        </tr>
  </table>
  </div>
</div>
</div>


</body>




<?php $this->load->view("scripts"); ?>
<!-- PrintArea -->

<script>
  window.print();
  window.onafterprint = function() {
    window.close(); 
  } 
</script>
</body>

</html>