<?php 
$filename = "reporte-nro-ordenes.xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$filename" );
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
header("Pragma: public");
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1">
<tbody>
<tr>
  <th>TIPO</th>
   <th>FechaRegistroSolicitud</th>
  <th>Nhistoriaclinica(dni)</th>
  <th>NombrePaciente</th>
  <th>ApellidoPaciente</th>
  <th>ActoMedico (idcita)</th>
  <th>Especialidad Origen</th>
  <th>Profesional Origen</th>
  <th>Concepto</th>
  <th>Pago</th>
  <th>Registrado</th>
  <th>Realizado</th>
  <th>NroOrden</th>
</tr>
<?php
foreach ($data as $row) {
?>
<tr>
<td><?php echo $row->tipo; ?></td>
<td><?php echo $row->fecha; ?></td>
<td><?php echo $row->nroDocument; ?></td>
<td><?php echo $row->nombrePaciente; ?></td>
<td><?php echo $row->apellidoPaciente; ?></td>
<td><?php echo $row->nroCita; ?></td>
<td><?php echo $row->especialidadOrigen; ?></td>
<td><?php echo $row->profesionalOrigen; ?></td>
<td><?php echo $row->procedimiento; ?></td>
<td><?php echo $row->pago; ?></td>
<td><?php echo $row->registrado; ?></td>
<td><?php echo $row->realizado; ?></td>
<td style='vnd.ms-excel.numberformat:@'><?php echo $row->nroOrden; ?></td>
</tr>
<?php } ?>
</tbody>
</table>