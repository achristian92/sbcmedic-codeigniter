<?php
defined('BASEPATH') or exit('No direct script access allowed');

$tipoCita = $this->input->get('tipoCita');
$user = $this->input->get('user');
$sucursal = $this->input->get('sucursal');

$sucursal = ($sucursal) ? "&sucursal=$sucursal" : "";

$fecha = str_replace("/", "-", $this->input->get('fecha'));
$fechaConsulta =  date("Y-m-d", strtotime($fecha));

$especialidad = $this->input->get('cbSpe');
$doctor = $this->input->get('cbDoc');
$doctor = "&doctor=$doctor";


$this->db->select("count(idAvailability)  as total");
$this->db->from("availabilities");
$this->db->where("idDoctor", $this->input->get('cbDoc'));
$this->db->where("disponible", 1);
$this->db->where("date", $fechaConsulta);
$this->db->group_by("date");

$query = $this->db->get();
$row_resultado = $query->row_array();
 
?>
<div class="row">
  <div class="col">
    <a class="btn btn-warning btn-block" href="<?= base_url('cita/confirmar/add?tipoCita=') . $tipoCita . "&user=" . $user . $sucursal . $doctor; ?>" role="button"><strong>Dar Adicional</strong></a>
  </div>
</div>

<div class="row mt-1">
  <div class="col">
    <button type="button" class="btn btn-outline-info btn-block" onclick="detalle_citas_asignadas('<?php echo $fechaConsulta; ?>', '<?php echo $especialidad; ?>')" title="Ver Citas Asignadas"><i class="fas fa-laptop-medical"></i> VER Citas YA Programadas <i class="far fa-eye"></i></button>
  </div>
</div>
<?php
		$row_resultado = $query->row_array();
        
		if(isset($row_resultado['total'])){
			$oldstatus = $row_resultado['total'];
		} else {
			$oldstatus = 0;
		}
		
  if ($oldstatus > 0) {
?>

<form action="<?= base_url('cita/confirmar/true') ?>" method="POST">

  
  <div class="row mt-2">
    <div class="col ">
      <button type="submit" class="btn btn-success btn-lg btn-block" title="Asignar Horarios">Continuar</button>
      <input type="hidden" name="fecha" value="<?php echo $fechaConsulta; ?>">
      <input type="hidden" name="profesional" value="<?php echo $this->input->get('cbDoc'); ?>">
      <input type="hidden" name="tipoCita" value="CP">
      <input type="hidden" name="user" value="<?php echo $user; ?>">

    </div>
  </div>
  <?php
  }
?>
</form>