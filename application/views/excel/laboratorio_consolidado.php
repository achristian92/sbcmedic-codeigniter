<?php 
  $filename = "reporte-examenes-laboratorio-detallado.xls";
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
  <th>Tipo</th>
  <th>Concepto</th>
  <th>Cantidad</th>
  <th>Monto</th>
  <th>Descuento</th>
  <th>Paciente</th>
	<th>FechaExamen</th>
	<th>FechaPago</th>
</tr>
<?php
foreach ($data as $row) {
?>
<tr>
<td><?php echo $row->tipo; ?></td>
<td><?php echo $row->nombre; ?></td>
<td><?php echo $row->cantidad; ?></td>
<td><?php echo $row->monto; ?></td>
<td><?php echo $row->descuento; ?></td>
<td><?php echo $row->paciente; ?></td>
 <td><?php echo $row->fechaExamen; ?></td>
 <td><?php echo $row->fechaModificar; ?></td>
</tr>
<?php } ?>
</tbody>
</table>