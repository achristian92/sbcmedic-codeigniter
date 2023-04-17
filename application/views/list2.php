<?php
  defined('BASEPATH') OR exit('No direct script access allowed');

  $tipoCita = $this->input->get('tipoCita');
  $user = $this->input->get('user');
  $sucursal = $this->input->get('sucursal');

  $user = ($user)? "&user=$user" : "";
  $sucursal = ($sucursal)? "&sucursal=$sucursal" : "";
  
  $fecha = str_replace("/", "-", $this->input->get('fecha'));
  $fecha =  date("Y-m-d", strtotime($fecha));

  $especialidad = $this->input->get('cbSpe');
  $doctor = $this->input->get('cbDoc');
  $doctor = "&doctor=$doctor" ;
 
?>
<div class="row">
  <div class="col">
    <a class="btn btn-warning btn-block" href="<?=base_url('cita/confirmar/add?tipoCita=').$tipoCita.$user.$sucursal.$doctor;?>" role="button"><strong>Dar Adicional</strong></a>
  </div>
</div>

	<div class="row mt-1">
	  <div class="col">
		<button type="button" class="btn btn-outline-info btn-block" onclick="detalle_citas_asignadas('<?php echo $fecha;?>', '<?php echo $especialidad;?>')" title="Ver Citas Asignadas"><i class="fas fa-laptop-medical"></i> VER Citas YA Programadas <i class="far fa-eye"></i></button> 
	  </div>
	</div>
	<?php
	  if (count($disponibilidades) >0) {
		foreach($disponibilidades as $idDoc => $disponibilidad) {
	?>
    <div class="row row-no-gutters">
      <div class="col-md-1 col-sm-6">
      <button style="margin-top: 5px;" type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
            <img src="img/logo_doctor.png" class=""/></button>
      </div>
      <div class="col-md-6 col-sm-6">
        <span class="font-green" style="display:block;"><strong>&nbsp;<?=$disponibilidad['doctor'];?></strong></span>
        <span class="font-green">&nbsp;<?=$disponibilidad['especialidad'];?></span>
      </div>
      <!--
      <div class="col-md-2 col-sm-6">
        <span><img src="img/logo_cv.png" /> <span class="font-green">CV</span></span>
      </div>
      -->
      <div class="col-md-5 col-sm-6 text-center">
        <?php
            if($promedio >0) {
              for ($i = 1; $i <= $promedio; $i++) {
        ?>
            <span><img src="img/logo_star.png" width="20px"/> </span>
        <?php
              }
            }

            for ($i = 1; $i <= 5 - $promedio; $i++) {
        ?>
            <span><img src="img/logo_empty_star.png" width="20px"/> </span>
        <?php
            }
        ?>                    
      </div>
    </div> 
    <div class="row" style="padding: 20px;">        
      <div class="col-md-12">
        <?php foreach($disponibilidad['horarios'] as $idDisp => $horario) { ?>                  
        <span><a href="<?=base_url('cita/confirmar/'.$idDisp."?tipoCita=" ).$tipoCita.$user.$sucursal;?>" class="btn btn-outline-success" title="Aceptar horario"><?=substr($horario['inicio'],0,5);?> - <?=substr($horario['fin'], 0, 5);?></a></span>                    
        <?php } ?>
        <!-- <span><button class="btn btn-medicine">08:20 - 08:50 a.m.</button></span> -->
      </div>
    </div>
    <?php 
          }
      } else {
  ?>
    <strong><p class="text-danger">La fecha seleccionada No cuenta con horarios disponibles. Por favor elija una próxima en el siguiente calendario.</p></strong>
  <?php 
      }
  ?>