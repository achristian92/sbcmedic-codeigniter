<?php 
$filename = "reporte-covid.xls";
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
  <th>Paciente</th>
  <th>Distrito</th>
  <th>FechaToma</th>
  <th>Pago</th>
    <th>UsuarioPago</th>
  <th>FechaPago</th>
  <th>CantidadAntígenos</th>
  <th>PrecioPruebasAntígenos</th>
  <th>CantidadPCR:</th>
  <th>PrecioPruebasPCR:</th>
  <th>PrecioTransponte:</th>
  <th>Descuento(S./):</th>
  <th>Precio Total(S./):</th>
  <th>Dirección:</th>
  <th>Teléfono:</th>
  <th>TipoPago:</th>
  <th>Observación:</th>
  <th>Sede:</th>
  <th>UsuarioRegistro:</th>
  <th>FechaRegistro:</th>
</tr>
<?php
foreach ($data as $row) {
?>
<tr>
<td><?php echo $row->nombres; ?></td>
<td><?php echo $row->distrito; ?></td>
<td><?php echo $row->fecha; ?></td>
 
<td><?php echo $row->pago; ?></td>
<td><?php echo $row->usuarioPago; ?></td>
<td><?php echo $row->fechaPago; ?></td>
<td><?php echo $row->cantidadPrueba_psr; ?></td>
<td><?php echo $row->costo_cantidadPrueba; ?></td>
<td><?php echo $row->cantidadPrueba_psr; ?></td>
<td><?php echo $row->costo_cantidadPrueba_psr; ?></td>
<td><?php echo $row->costo_transporte; ?></td>
<td><?php echo $row->porcentajeDescuento; ?></td>
<td><?php echo $row->total; ?></td>
<td><?php echo $row->direccion; ?></td>
<td><?php echo $row->telefono; ?></td>
<td><?php echo $row->tipoBanco; ?></td>
<td><?php echo $row->motivo; ?></td>
<td><?php echo $row->sede; ?></td>
<td><?php echo $row->usuarioRegistro; ?></td>
<td><?php echo $row->fechaCreacion; ?></td>
 
 
 
</tr>
<?php } ?>
</tbody>
</table>