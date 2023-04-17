<?php 
  $filename = "reporte-procedimientos-laboratorios-solicitados.xls";
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
  <th>FechaSolicitud</th>
  <th>Especialidad</th>
  <th>Profesional</th>
  <th>NroCita</th>
  <th>Paciente</th>
  <th>Documento</th>
  <th>Edad</th>
  <th>SEXO</th>
</tr>
<?php
foreach ($data as $row) {
?>
<tr>
<td><?php echo $row->tipo; ?></td>
<td><?php echo $row->nombre; ?></td>
<td><?php echo $row->fecha; ?></td>
<td><?php echo $row->especialidad; ?></td>
<td><?php echo $row->medico; ?></td>
<td><?php echo $row->idCita; ?></td>
 <td><?php echo $row->paciente; ?></td>
 <td><?php echo $row->document; ?></td>
 <td><?php echo $row->edad; ?></td>
 <td><?php echo $row->sexo; ?></td>
</tr>
<?php } ?>
</tbody>
</table>