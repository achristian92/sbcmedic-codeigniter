<?php 
  $filename = "reporte-procedimientos-consolidado.xls";
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
  <th>Concepto</th>
  <th>Cantidad</th>
  <th>MontoTotal</th>
  
</tr>
<?php
foreach ($data as $row) {
?>
<tr>
<td><?php echo $row->codigo_procedimiento; ?></td>
<td><?php echo $row->descripcion; ?></td>
<td><?php echo $row->cantidad; ?></td>
<td><?php echo $row->monto; ?></td>
 

 
</tr>
<?php } ?>
</tbody>
</table>