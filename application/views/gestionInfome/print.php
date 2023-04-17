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
  <title>SBCMedic | EXAMENES PRINT</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    table {
      border-collapse: collapse;
      font-size: 10pt;
      font-family: 'Times New Roman', Times, serif;
    }

    td,
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
    <table  class="table  " style="width:100%;">
      <tr>
        <td colspan="4" style="text-align: center;"><img src="<?php echo base_url('img/logo_sbcmedic.png');?>" alt="logo" width="50%"></td>
      </tr>
      <tr>
        <td><strong>PACIENTE</strong></td>
        <td><?php echo $paciente["paciente"]; ?></td>
        <td><strong>EDAD</strong></td>
        <td style="text"><?php echo $paciente["edad"]; ?></td>
      </tr>
      <tr>
        <td><strong>NRO DOCUMENTO</strong></td>
        <td><?php echo $paciente["document"]; ?></td>
        <td><strong>DIRECCIÓN</strong></td>
        <td><?php echo $paciente["address"]." / ". $paciente["distrito"]; ?></td>
      </tr>
    </table>
    <br>
    <table style="border-collapse: collapse; border-width: 4px; border-style: dashed" class="table table-striped table-hover table-bordered" style="width:100%;">
    <tr>
      <th>CÓDIGO INTERNO</th>
      <th>FECHA EXAMEN</th>
      <th>STATUS</th>
      <th>TIPO</th>
      <th>EXAMEN</th>
      <th>PRECIO</th>
      <!-- <th>SUBTOTAL(S./)</th> -->
    </tr>
    </thead>
    <tbody>
      <?php
      $codigoI = null;
      $filas = null;
      foreach ($resultados as $clave => $resultado) {
        $clave++;
      ?>
        <tr>
          <td style="text-align: center;"><?php echo $resultado->codigo_interno; ?></td>
          <td style="text-align: center;"><?php echo date("d/m/Y", strtotime($resultado->fechaExamen)); ?></td>
          <td><?php if ($resultado->estado == 0) echo "En Proceso";
              else if ($resultado->estado == 1) echo "Procesado";
              else echo "Envíado"; ?></td>
          <td><strong><?php echo $resultado->tipo; ?></strong></td>
          <td style="width:40%;"><strong><?php echo $resultado->examen; ?></strong></td>
          <td style="text-align: center;"><strong>S./ <?php echo $resultado->precio; ?></strong></td>

          <?php
          $filas++;
          if ($filas == 1) {
          ?>
          <!--   <td rowspan="<?php echo substr($resultado->cantidadFilas, 0, 2) * 1; ?>" style="text-align: center; vertical-align:middle;">S./ <strong><?php echo substr($resultado->cantidadFilas, 3); ?></strong><br>
            </td> -->
          <?php
          }

          if ($resultado->cantidadFilas == $filas)  $filas = 0;
          ?>
        </tr>
      <?php
      }
		if($resultado->costo_transporte > 0) {
      ?>
      <tr>
        <td colspan="5" style="text-align: right;"><strong>COSTO TRANSPORTE(S./)</strong></td>
        <td style="text-align: center;"><?php echo $resultado->costo_transporte; ?></td>
      </tr>
    <?php
		  }

      if($resultado->descuento > 0) {
    ?>
      <tr>
        <td colspan="5" style="text-align: right;"><strong>DESCUENTO(S./)</strong></td>
        <td style="text-align: center;">- <?php echo $resultado->descuento; ?></td>
      </tr>
    <?php
		  }
    ?>      
      <tr>
        <td colspan="5" style="text-align: right;"><strong>TOTAL(S./)</strong></td>
        <td style="text-align: center;"><?php echo number_format($resultado->costo_transporte + explode("-", $resultado->cantidadFilas)[1] - $resultado->descuento, 2); ?></td>
      </tr>

    </tbody>
  </table>
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