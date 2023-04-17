<?php 
$filename = "reporte-pagos.xls";
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
<th>Tipo</th>
<th>NroDocumentoPaciente</th>
  <th>NombrePaciente</th>
  <th>ApellidoPaciente</th>
  <th>Concepto</th>
  <th>Monto</th>
  <th>Descuento</th>
  <th>Transporte</th>
  <th>Pago</th>
  <th>FechaPago</th>
  <th>UsuarioPago</th>
    <th>MontoPagoSinIGV</th>
    <th>MontoPagoConIGV</th>
  <th>CostoProcedimiento</th>
  <th>MontoProfesional</th>
  <th>CodigoInterno</th>
  <th>NroCita</th>
   <th>Paquete</th>
</tr>
<?php
foreach ($data as $row) {
?>
<tr>
<td><?php echo $row->tipo; ?></td>
<td style='vnd.ms-excel.numberformat:@'><?php echo $row->document; ?></td>
<td><?php echo $row->firstname; ?></td>
<td><?php echo $row->lastname; ?></td>
<td><?php echo $row->procedimiento; ?></td>
<td><?php echo $row->precio; ?></td>
<td><?php echo $row->descuento; ?></td>
<td><?php echo $row->transporte; ?></td>
<td><?php echo $row->pago; ?></td>
<td><?php echo $row->fechaPago; ?></td>
<td><?php echo $row->usuarioPago; ?></td>
<td><?php echo $row->montoSinIgv; ?></td>
<td><?php echo $row->montoConIgv; ?></td>
<td></td>
<td></td>
<td><?php echo $row->codigo_asignacion; ?></td>
 
 <td style='vnd.ms-excel.numberformat:@'><?php echo $row->citas; ?></td>
 <td><?php echo $row->paquete; ?></td>
</tr>
<?php } ?>
</tbody>
</table>