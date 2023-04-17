<?php 
  $filename = "reporte-examenes-laboratorio.xls";
  header("Content-Type: application/vnd.ms-excel;");
  header("Content-Disposition: attachment; filename=$filename" );
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
  header("Pragma: public");
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1">
<tbody>
<tr>
  <th>CodigoInterno</th>
  <th>FechaExamen</th>
  <th>Exámenes</th>
  <th>NombrePaciente</th>
  <th>Precio</th>
  <th>Transporte</th>
  <th>Descuento</th>
  <th>StatusPago</th>
</tr>
<?php
foreach ($data as $row) {
?>
<tr>
<td><?php echo $row->codigo_interno; ?></td>
<td><?php echo $row->fechaExamen; ?></td>
<td><?php echo $row->examenes; ?></td>
<td><?php echo $row->paciente; ?></td>
<td><?php echo $row->precio; ?></td>
<td><?php echo $row->transporte; ?></td>
<td><?php echo $row->descuento; ?></td>
<td><?php echo $row->statusPago; ?></td>
 
</tr>
<?php } ?>
</tbody>
</table>