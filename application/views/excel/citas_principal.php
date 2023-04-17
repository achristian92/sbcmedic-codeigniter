<?php
$filename = "reporte-citas.xls";
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
  <th>NroCita</th>
  <th>UsuarioCreciónCita</th>
  <th>FechaCreacionCita</th>
  <th>OBS.Cita</th>
  <th>statusCita</th>
  <th>TipoCita</th>
  <th>FechaCita</th>
  <th>HoraCita</th>
  <th>Profesional</th>
  <th>Especialidad</th>
  <th>Paquete</th>
  <th>Procedimiento</th>
  <th>Pago</th>
  <th>FechaPago</th>
  <th>NroDocumentoPaciente</th>
  <th>NombrePaciente</th>
  <th>ApellidoPaciente</th>
  <th>EmailPaciente</th>
  <th>TeléfonoPaciente</th>
  <th>DistritoPaciente</th>
  <th>Tipo Motivo Cita</th>
  <th>CodigoInterno</th>
  <th>MotivoCancelar</th>
  <th>Turno</th>
  <th>CitaGratis</th>
</tr>
<?php
foreach ($data as $row) {
?>
<!-- <tr> -->
<td><?php echo $row->idCita; ?></td>
<td><?php echo $row->usuarioCreacion; ?></td>
<td><?php echo $row->fechaCreacion; ?></td>
<td><?php echo $row->motivoCita; ?></td>
<td><?php echo $row->statusCita; ?></td>
<td><?php echo $row->tipoCita; ?></td>
<td><?php echo $row->fechaCita; ?></td>
<td><?php echo $row->horaCita; ?></td>
<td><?php echo $row->nombreMedico; ?></td>
<td><?php echo $row->especialidad; ?></td>
<td><?php echo $row->paquete; ?></td>
<td><?php echo $row->procedimiento; ?></td>
<td><?php echo $row->statusPago; ?></td>
<td><?php echo $row->fechaModificar; ?></td>
<td><?php echo $row->nroDocumento; ?></td>
<td><?php echo $row->firstname; ?></td>
<td><?php echo $row->lastname; ?></td>
<td><?php echo $row->email; ?></td>
<td><?php echo $row->phone; ?></td>
<td><?php echo $row->distrito; ?></td>
<td><?php echo $row->motivoTipoCita; ?></td>
<td><?php echo $row->codigo_asignacion; ?></td>
<td><?php echo $row->motivoCancelar; ?></td>
<td><?php echo $row->turno; ?></td>
<td><?php echo $row->gratis; ?></td>
</tr>
<?php } ?>
</tbody>
</table>