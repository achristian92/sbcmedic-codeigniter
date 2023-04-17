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
  <th>NroDocumento</th>
  <th>NombrePaciente</th>
  <th>ApellidoPaciente</th>
  <th>EmailPaciente</th>
  <th>TeléfonoPaciente</th>
  <th>Distrito</th>
  <th>DirecciónPaciente</th>
  <th>TipoCita</th>
  <th>FechaCita</th>
  <th>HoraCita</th>
  <th>Profesional</th>
  <th>Especialidad</th>
  <th>MontoPago</th>
  <th>statusPago</th>
  <th>OBS.Cita</th>
  <th>statusCita</th>
  <th>UsuarioCreciónCita</th>
  <th>FechaCreacionCita</th>
  <th>FechaPago</th>
  <th>UsuarioPago</th>
  <th>Tipo Motivo Cita</th>
  <th>Procedimiento</th>
  <th>MotivoCancelar</th>
  <th>CanalVenta</th>
  <th>Turno</th>
  <th>tipoPaciente</th>
</tr>
<?php
foreach ($data as $row) {
?>
<tr>
<td><?php echo $row->nroDocumento; ?></td>
<td><?php echo $row->firstname; ?></td>
<td><?php echo $row->lastname; ?></td>
<td><?php echo $row->email; ?></td>
<td><?php echo $row->phone; ?></td>
<td><?php echo $row->distrito; ?></td>
<td><?php echo $row->address; ?></td>
<td><?php echo $row->tipoCita; ?></td>
<td><?php echo $row->fechaCita; ?></td>
<td><?php echo $row->horaCita; ?></td>
<td><?php echo $row->nombreMedico; ?></td>
<td><?php echo $row->especialidad; ?></td>
<td><?php echo $row->monto; ?></td>
<td><?php echo $row->statusPago; ?></td>
<td><?php echo $row->motivoCita; ?></td>
<td><?php echo $row->statusCita; ?></td>
<td><?php echo $row->usuarioCreacion; ?></td>
<td><?php echo $row->fechaCreacion; ?></td>
<td><?php echo $row->fechaModificar; ?></td>
<td><?php echo $row->usuarioPago; ?></td>
<td><?php echo $row->motivoTipoCita; ?></td>
<td><?php echo $row->procedimiento; ?></td>
<td><?php echo $row->motivoCancelar; ?></td>
<td><?php echo $row->canalVenta; ?></td>
<td><?php echo $row->turno; ?></td>
<td><?php echo $row->tipoPaciente; ?></td>
 
</tr>
<?php } ?>
</tbody>
</table>