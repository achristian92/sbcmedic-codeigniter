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
            <td colspan="2" style="text-align: center;"><h4><strong>PRUEBAS COVID</strong></h4></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;"><h4><strong><?php echo $resultados[0]["quienSolicito"]. " - NRO  ". str_pad($resultados[0]["nro"], 3, '0', STR_PAD_LEFT); ?></strong></h4></td>
        </tr>
        <tr>
          <td style="width: 40%;"><strong>FECHA TOMA</strong></td>
          <td style="text-align: center;"><?php echo date("d/m/Y",strtotime($resultados[0]["fecha"]));?></td>          
        </tr>
 
        <tr>
          <td><strong>SEDE</strong></td>
          <td style="text-align: center;"><?php echo $resultados[0]["sede"]; ?></td>          
        </tr>
        <?php if ($resultados[0]["cantidadPrueba"]) { ?>
        <tr>
          <td><strong>PRUEBA ANTÍGENOS</strong></td>
          <td style="text-align: center;"><strong><?php echo "[ ".$resultados[0]["cantidadPrueba"]." ]"; ?></strong> <?php echo number_format($resultados[0]["precioAnti"], 2); ?></td>          
        </tr>
        <?php } ?>
        <?php if ($resultados[0]["cantidadPrueba_psr"]) { ?>
        <tr>
          <td><strong>PRUEBA PCR</strong></td>
          <td style="text-align: center;"><strong><?php echo "[ ".$resultados[0]["cantidadPrueba_psr"]." ]"; ?></strong> <?php echo number_format($resultados[0]["precioPcr"], 2); ?></td>          
        </tr>
        <?php } ?>
        <?php if ($resultados[0]["costo_transporte"] > 0) { ?>
        <tr>
          <td><strong>TRANSPORTE</strong></td>
          <td style="text-align: center;"><strong><?php echo $resultados[0]["costo_transporte"]; ?></strong></td>          
        </tr>
        <?php } ?>
        <tr>
          <td><strong>PRECIO TOTAL (S./)</strong></td>
          <td style="text-align: center;"><strong><u><?php echo number_format($resultados[0]["precio"], 2); ?></u></strong></td>          
        </tr>
  
   
  
 
        <tr>
          <td><strong>TERMINALISTA</strong></td>
          <td style="text-align: center;"><?php echo $terminalista["firstname"]. " ". $terminalista["lastname"];?></td>          
        </tr>
  </table>
  
    <table style="width: 60%;  font-size: 10pt; font-family: 'Times New Roman', Times, serif;" CELLPADDING="5" CELLSPACING="5" border="0">
  <?php
                    foreach ($pacientes as $row) {
                       
                  ?>

    <tr>
      <td>Paciente</td>
      <td><?php echo $row->nombre. " / ".$row->documento;?></td>
    </tr>
 

                  <?php
                    }
                  ?>
                    </table>
					
					
  <table style="width: 60%;  font-size: 10pt; font-family: 'Times New Roman', Times, serif;" CELLPADDING="5" CELLSPACING="5" border="0">
    <tr>
      <td>FECHA: <strong><?php echo date("d/m/Y");?></strong></td>
      <td>HORA:  <strong><?php echo date("H:m");?></strong></td>
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