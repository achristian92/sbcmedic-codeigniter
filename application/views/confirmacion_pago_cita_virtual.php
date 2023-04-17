<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

    <!-- Main content -->
    <section class="content">
    <table width="100%" border="0" cellspacing="4" cellpadding="4">
    <tr>
        <td align="center">
        <span style="vertical-align:middle;  "><img height="100px" src="<?php echo base_url('img/logo_sbcmedic.png')?>" style="position: relative;z-index:5;margin-right: -10px;" /></span> 
        </td>
    </tr>
    <tr>
        <td colspan="2">Estimado paciente,</td>
    </tr>
    <tr>
        <td colspan="2">Queremos recordarle que el día <?php echo date("d/m/Y",strtotime($fechaCita));?> a las <?php echo $horaCita;?>(Hr Lima Perú), cuenta con una consulta médica con el doctor(a) <?php echo $medico;?> en la especialidad de <?php echo $especialista;?>. <br></td>
    </tr>
    <?php if($tipoCita == "CV") { ?>
    <tr>
        <td colspan="2">Para ello le recomendamos:</td>
    </tr>
    <tr>
        <td colspan="2">1.- Conectarse a su cita 10 minutos antes. <br></td>
    </tr>
    <tr>
        <td colspan="2">2.- Para un mejor calidad de audio el uso de auriculares.</td>
    </tr>
    <tr>
        <td colspan="2">3.- Destinar un espacio tranquilo para que la consulta sea de mejor calidad.</td>
    </tr>
    <tr>
        <td colspan="2">4.- El uso de una conexión estable de internet.</td>
    </tr>
    <tr>
        <td colspan="2">Si encuentra dificultades previas al servicio médico, contactar con nuestra central telefónica a fin de buscar una solución o caso contrario una reprogramación, llamar al <strong>(01)5868056</strong>.</td>
    </tr>
	<tr>
        <td colspan="2" style="color:#00b670;font-size: 150%;"><ins>PASO PARA TU CONSULTA VIRTUAL</ins></td>
    </tr>
		<tr>
        <td colspan="2">
			<ol>
				<li>Accede a tu plataforma web ingresando con tu DNI y contraseña.</li>
				<li>Ingresa a tu consulta desde del enlace enviado al correo o ingresando desde la  plataforma web desde la pestaña <strong>“Mis citas”</strong>.</li>
				<li>Ubica la cita que deseas ingresar y selecciona el icono de video llamada.</li>
				<li>Ingresa el nombre que deseas mostrar y el nombre de la sala destinado <strong>(que encontraras en este correo de confirmación de cita)</strong>.</li>
				<li>Activa tu micrófono y cámara para una consulta mucho más eficiente.</li>
				<li>Finaliza tu consulta cerrando la sesión o pantalla.</li>
				<li>Revisa las recomendaciones, recetas y solicitud de exámenes de tu consulta  desde la pestaña de <strong>“Historial de citas”</strong>.</li>
			</ol>
		</td>
    </tr>
 
    <tr>
		<td style="text-align:center;font-family:arial;
font-weight:bolder;
font-size:.9rem;
text-decoration:underline;
background-color:#2AB07E;
color:black;width: 50%;">Nombre de la sala: <strong><?php echo substr($usuario["document"], 0, 4);?></strong></td>
        <td style="text-align:center;font-family:arial;
font-weight:bolder;
font-size:.9rem;
text-decoration:underline;
background-color:#2AB07E;width: 50%;"><a href="<?php echo base_url('miscitas');?>" target="_blank" style="color:white;">ENLACE A LA CONSULTA</a></td>
    </tr>
	
    <?php } ?>
	<tr>
        <td colspan="2"><p class="bg-info"><h4>Para ello: Es recomendable estar presente 15 minutos previos a su cita para una mejor atención.</p>
</h4></td>
    </tr>
    </table>
	
    <table class="ablock">
        <tr>
            <td>• Nombre del paciente:</td><td><?php echo $usuario["paciente"];?></td>
        </tr>
        <tr>
            <td>• Modo de consulta:</td><td><?php echo $tipoConsulta;?></td>
        </tr>
        <tr>
            <td>• Fecha de consulta:</td><td><?php echo date("d/m/Y",strtotime($fechaCita));?></td>
        </tr>
        <tr>
            <td>• Hora de consulta:</td><td><?php echo $horaCita;?></td>
        </tr>
        <tr>
            <td>• Especialidad:</td><td><?php echo $especialista;?></td>
        </tr>
        <tr>
            <td>• Médico(a): </td><td><?php echo $medico;?></td>
        </tr>
        <?php if($reference_code) { ?>
        <tr>
            <td>• Código de refencia de pago:</td><td style="text-align:left;color: #ff7575;"><strong><?php echo $reference_code;?></strong></td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="2" style="text-align:center"><strong>* La consulta podrá ser reprogramado hasta con 24 horas de anticipación al servicio. 
			<?php if($reference_code) { ?> Caso contrario se aplicará el débito del servicio como brindado.<?php } ?></strong></td>
        </tr>
    </table>    
     
    </section>
    <!-- /.content -->
  