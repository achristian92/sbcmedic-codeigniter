<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
<base href="<?=base_url();?>" target="_blank">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SBCMedic | Registro de Usuario</title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo  base_url('img/favicon.ico');?>"/>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  
  <!-- Theme style -->
  <link rel="stylesheet" href="css/adminlte.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.css">
  
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
  label {
    color: rgba(46,175,109,1);
  }
  </style>
</head>
<body class="hold-transition sidebar-mini" style="background-image: url(img/bg_registro.png); height: 100%;  background-position: center;  background-repeat: no-repeat;  background-size: cover;">
<div class="wrapper">
 
  <!-- Content Wrapper. Contains page content -->
  <div class="">
    

    <!-- Main content -->
    
    <section class="content">
      <!-- form start -->
      <form role="form" id="quickForm" method="post" action="<?php echo base_url('guardar');?>">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6 card-body">

          <div class="row">
              
              <div class="col-md-3">
                <div class="form-group">
                    <label for="exampleInputPassword1">1. Tipo *</label>
                    <select class="form-control select2" name="type" id="type" style="width: 100%;">
                      <?php
                        foreach ($tipoDocumento as $i => $documento)
                          echo '<option value="'.$i.'">'.$documento.'</option>';
                      ?>
                  </select>
                  </div>
              </div>
              <div class="col-md-3">
              <div class="form-group">
                    <label for="exampleInputPassword1">2. Documento *</label>
                    <input type="text" name="document" class="form-control" id="iDoc" placeholder="" autocomplete="off" maxlength="12">
                  </div>
              </div>
              <div class="col-md-6">
              <div class="form-group">
                    <label for="exampleInputPassword1">3. Correo Electrónico *</label>
                    <input type="email" name="email" class="form-control" id="iEma" placeholder="">
                  </div>
              </div>
            </div>

            <!-- jquery validation -->
            <div class="row">
            <div class="col-md-6 ">
              <!-- /.card-header -->
               
                  <div class="form-group">
                    <label for="exampleInputEmail1">4. Nombre Completo *</label>
                    <input type="text" name="firstname" class="form-control" id="iNam">
                  </div>
                                                                                   
        
               
                </div>
            
            <!-- /.card -->
           
            <!-- jquery validation -->
            <div class="col-md-6">
             
              <!-- /.card-header -->
             
               
                  <div class="form-group">
                    <label for="exampleInputEmail1">5. Apellido Completo *</label>
                    <input type="text" name="lastname" class="form-control" id="iLas">
                  </div>
                 
            </div>

            </div>

            <div class="row">
              <div class="col-md-3">
              <div class="form-group">
                    <label for="exampleInputPassword1">6. Whatsapp *</label>
                    <input type="text" name="phone" class="form-control" id="iPho" placeholder="Celular" maxlength="9">
                  </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                    <label for="exampleInputPassword1">7. Sexo *</label>
                    <select class="form-control select2" name="sex" style="width: 100%;">
                    <option selected="selected" value="M">Masculino</option>
                    <option value="F">Femenino</option>                    
                  </select>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputPassword1">8. Fecha de Nacimiento *</label>
                    <input type="date" name="birthdate" class="form-control" value="<?php echo date("Y-m-d"); ?>" requerid>

<!--                     <div class="input-group date"  data-target-input="nearest">
                        <input id="reservationdate" autocomplete="off" type="text" name="birthdate" data-target="#reservationdate"/>
                        <div class="input-group-append" >
                            <div class="input-group-text" id="btnPicker"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div> -->
                  </div>
              </div>
              
              
            </div>
            <div class="row">
              <div class="col-md-6">
                
                <div class="form-group">
                    <label for="exampleInputPassword1">9. Departamento *</label>

                    <select id="cbDep" class="form-control select2" name="department" style="width: 100%;">
                    <option value="">--Seleccione--</option>
                    <?php foreach ($departamentos as $departamento) { ?>
                      <option value="<?=$departamento->id;?>" <?php echo ($departamento->id == 15) ? "selected" : "";?> ><?=$departamento->name;?></option>                    
                    <?php } ?>
                    </select> 
                </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputPassword1">10. Provincia *</label>                    
                    <select id="cbProv" class="form-control select2" name="province" style="width: 100%;">
                      <option value="">--Seleccione--</option>
					  <?php foreach ($provincias as $provincia) { ?>
                      <option value="<?=$provincia->id;?>" <?php echo ($provincia->id == 1501) ? "selected" : "";?> ><?=$provincia->name;?></option>                    
                      <?php } ?>
                    </select>
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
              <div class="form-group">
                    <label for="exampleInputPassword1">11. Distrito *</label>                                     
                    <select id="cbDist" class="form-control select2" name="district" style="width: 100%;">
                      <option value="">--Seleccione--</option>
					  <?php foreach ($distritos as $distrito) { ?>
                      <option value="<?=$distrito->id;?>" ><?=$distrito->name;?></option>                    
                      <?php } ?>
                    </select>
                </div>
              </div>
              <div class="col-md-6">
              <div class="form-group">
                    <label for="exampleInputPassword1">12. Dirección *</label>
                    <input type="text" name="address" class="form-control" id="iAdd" placeholder="">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
              <div class="form-group">
                    <label for="exampleInputPassword1">13. Canal de Venta *</label>                                     
                    <select id="canalVenta" class="form-control select2" name="canalVenta" style="width: 100%;">
                      <option value="">--Seleccione--</option>
                      <?php foreach ($canalesVentas as $canalVenta) { ?>
                        <option value="<?=$canalVenta->id;?>"><?=$canalVenta->nombre;?></option>                    
                      <?php } ?>
                    </select>
                </div>
              </div>
           
            </div>

            <div class="row">
              <div class="col-md-6">
              </div>
              <div class="col-md-6">
              </div>
            </div>
            <!-- /.card -->
            </div>
          <!-- right column -->
          <div class="col-md-6">

          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
        <div class="row">
        <div class="col-md-2">
        <div class="form-group text-center" style="padding: .375rem .75rem;font-size: 1rem;">
            <label for="exampleInputPassword1" style="color: #004663;">CONTRASEÑA *</label>
            </div>
        </div>
        <div class="col-md-4">
        <div class="form-group" style="padding: .375rem 1.75rem;font-size: 1rem;">
        <input type="password" name="password" class="form-control" id="password" placeholder="">
                  </div>
        </div>
        <div class="col-md-6">
        </div>
        </div>
        <div class="row">
        <div class="col-md-2">
        <div class="form-group text-center " style="padding: .375rem .75rem;font-size: 1rem;">
            <label for="exampleInputPassword1" style="color: #004663;">REPETIR CONTRASEÑA *</label>
            </div>
        </div>
        <div class="col-md-4">
        <div class="form-group" style="padding: .375rem 1.75rem;font-size: 1rem;">
        <input type="password" name="password_again" class="form-control" id="password_again" placeholder="">
                  </div>
        </div>
        <div class="col-md-6">
        </div>
        </div>
        <div class="row">
        <div class="col-md-4">
        <div class="form-group mb-0" style="padding: .375rem 1.75rem;">
                    <div class="custom-control custom-checkbox" >
                      <input type="checkbox" name="terms" class="custom-control-input" id="exampleCheck1" checked>
                      <label class="custom-control-label" for="exampleCheck1" style="color: #004663;font-size: .75em;padding-top: 5px;">He leído y Acepto los <a  data-toggle="modal" data-target="#exampleModalCenter" href="#">Términos y Condiciones</a>.</label>
                    </div>
                  </div>
        </div>
        <div class="col-md-2 text-center">
        <div class="form-group mb-0">
                    <button type="submit" id="btnRegistrar" class="btn btn-primary" style="font-weight:bold;">CONTINUAR</button>
                  </div>
        </div>
        <div class="col-md-6">
        </div>
        </div>
      </div><!-- /.container-fluid -->
      </form>
    </section>
    
    <!-- /.content -->
  </div>
 
  <!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header text-center" >
        <h5 class="modal-title w-100" id="exampleModalLongTitle"><strong>SBCmedic - Términos y condiciones</strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <strong>INFORMACIÓN RELEVANTE</strong><br><br>
        <p class="text-justify"> 
          Bienvenidos a SBCmedic que es operado por Soluciones Medico Quirujicas del Perú, empresa ubicada en calle Ignacio Mariátegui 137, Barranco, Lima, Perú. Favor leer estos Términos y Condiciones cuidadosamente antes de utilizar el sitio
          <br><br>
            <strong>Condiciones de Uso - Médico Virtual</strong>
          <br>
          Los presentes Términos y Condiciones (en adelante, Términos y Condiciones) regulan el uso de la Plataforma de Médico Virtual (en adelante, la Plataforma), mediante el cual SBCmedic (en adelante, IPRESS) presta el servicio de médico virtual (en adelante, el Servicio).

          <br>
          El acceso y utilización de la Plataforma atribuye al visitante la condición de usuario (en adelante, el Usuario) e implica su declaración expresa de conocer y aceptar plenamente todas las disposiciones, normas, instrucciones, responsabilidades y políticas contenidas en los presentes Términos y Condiciones. En consecuencia, el Usuario debe leer detenidamente los presentes Términos y Condiciones cada vez que acceda a la Plataforma pues éstos podrían sufrir variaciones sin previo aviso.
          <br><strong>(Habilitar sección para mostrar T&C)</strong>
        </p>
        <p class="text-justify">
          <br><strong>Finalidad del Servicio</strong><br><br>
          La finalidad del Servicio de Médico Virtual es asesorar u orientar al Usuario cuando su salud está alterada por condiciones médicas, síntomas o procesos clínicos de baja complejidad y de reciente inicio, es decir, dolencias que presentan síntomas claros de una patología frecuente, de menos de 7 días de evolución desde su inicio, y que no representan un riesgo para la vida, mejorando así la accesibilidad a un servicio de salud dinámico y ágil en el que el Usuario podrá interactuar a través de medios tecnológicos con un profesional de la salud (en adelante, Médico Consultor).
          <br><br>
          El Médico Consultor es un profesional de la salud que realizará la anamnesis al Usuario (recopilación de información) respecto a los síntomas y/o antecedentes de importancia; y quien, además, cuenta con las competencias para indicar el tratamiento en los casos que corresponda.
          <br><br>
          Asimismo, el Médico Consultor tiene la facultad de decidir sobre aquellos casos que precisan más investigación (examen físico completo o pruebas auxiliares de ayuda al diagnóstico) o requieren de atención en un servicio médico de emergencias. En esos casos, el Usuario será invitado a continuar la consulta en las sedes de LA IPRESS
          <br><br>
          El objetivo del Servicio es evitar la automedicación o polifarmacia realizada por los propios pacientes, boticas o farmacias, gracias a la superación de barreras económicas y de accesibilidad por la distancia, tiempo de movilización, entre otros, con el fin de brindar de manera oportuna una adecuada orientación médica a través de un Médico Consultor.
          <br><br>
          Finalmente, ese Servicio no tiene como finalidad sustituir la consulta médica presencial (incluye examen físico), la cual podrá ser requerida por el Médico Consultor para los casos que éste considere pertinente de acuerdo a la anamnesis al Usuario.
        </p>
        <p class="text-justify">
          <strong>Condiciones de uso del Servicio</strong><br><br>
          <ul>
            <li type="square">
              Será prestado a través del sitio web y aplicación móvil de  la IPRESS, cuyo uso está sujeto a sus Términos y Condiciones, los mismos que el Usuario declara haber leído y aceptado.
            </li>
            <li type="square">
              Disponible únicamente en el horario de lunes a viernes SABADO de 8:00 a.m. a 8 p.m. No estará disponible días festivos ni feriados.
            </li>
            <li type="square">
              Disponible sólo para consultas de medicina general y las especialidades de la programación.
            </li>
            <li type="square">
              Sólo podrá ser usado por el Usuario en calidad de paciente, es decir, la persona que recibirá la atención médica será el mismo Usuario. En las atenciones de pediatría, el menor debe estar acompañado, por lo menos, de uno de sus padres o de su representante legal, de ser aplicable, o de un adulto autorizado por cualquiera de ellos, bajo la responsabilidad del Usuario.
            </li>
            <li type="square">
              El perfil de atenciones médicas que se ha establecido atender por este canal corresponde a las enfermedades sub agudas y de baja complejidad. En ese sentido, sin que la lista resulte absolutamente limitante, los diagnósticos de manejo por el Servicio son:
              <ul>
                <li>
                    Síntomas con sospecha de enfermedad aguda de las vías respiratorias altas (rinofaringitis, faringitis, laringitis, amigdalitis, sinusitis, alergias estacionales) para mayores de 7 años, sin aparente complicación.
                  </li>
                  <li>
                    Síntomas con sospecha de enfermedad aguda gastrointestinal no Quirujicas sin sospecha de deshidratación (diarreas agudas, dispepsias) sin aparente complicación y solo en adultos.
                  </li>
                  <li>
                  Síntomas con sospecha de enfermedad aguda dermatológica de baja complejidad (quemaduras leves, irritaciones leves, alergias de contacto y otras).
                  </li>
                  <li>
                    Mialgias (dolores musculares) y otros malestares agudos del sistema músculo esquelético sin sospecha de complejidad.
                  </li>
                  <li>
                    Síntomas urinarios agudos sin sospecha de complejidad.
                  </li>
                  <li>
                    Orientación médica pediátrica.
                  </li>
                  <li>
                    El Médico Consultor podría determinar si el Usuario requiere de una atención médica de emergencia o si es indispensable el examen físico para el abordaje del cuadro clínico o pruebas auxiliares de ayuda al diagnóstico.
                  </li>
                </ul>
            </li>
          </ul>
        </p>
       
        <p class="text-justify">
          <strong>Procedimiento de Uso</strong><br><br>
          <ul>
            <li type="square">
              El Usuario ingresa a la Plataforma a través del sitio web de LA IPRESS y crea un usuario y contraseña. 
            </li>
            <li type="square">
              El Usuario inicia sesión en la Plataforma y solicita el Servicio en la sección Médico Virtual. Automáticamente, la solicitud será ingresada a una lista de espera por orden de ingreso.
            </li>
            <li type="square">
              El Usuario efectúa el pago en línea a través de la Plataforma utilizando como medio de pago una tarjeta de débito o crédito, según el operador disponible.
            </li>
            <li type="square">
              Una vez realizado el pago, se enviará un correo electrónico de confirmación al Usuario, adjuntando el recibo electrónico correspondiente al pago del Servicio y datos de confirmación.
            </li>
            <li type="square">
              De acuerdo al orden de la lista de espera (programaciones confirmadas), se inicia el servicio en línea a través de una video llamada. Durante la entrevista, el Médico Consultor podría determinar que la consulta no puede ser atendida por ese medio y que requiere de una evaluación médica presencial y/o de una intervención de una especialidad específica. En estos casos, el Médico Consultor orientará al Usuario que solicite un servicio Médico a Domicilio o acuda a una de las sedes de IPRESS, u otro centro médico de preferencia del Usuario.
            </li>
            <li type="square">
              Si el Usuario realizó el pago y se presentan problemas de conectividad durante la espera para ser atendido, el Usuario debe ingresar nuevamente a la Plataforma en donde encontrará su cita disponible, pero se le asignará un nuevo tiempo de espera. Luego, se le enviará una alerta cuando el Médico Consultor se encuentre disponible nuevamente para iniciar el vídeo llamado. Si el Usuario no estuviera disponible, LA IPRESS  lo contactará a los datos de contacto registrados en la Plataforma para confirmar la continuación del Servicio; de lo contrario, se procederá con la devolución del pago.
            </li>
            <li type="square">
              De ser aplicable de acuerdo al criterio del Médico Consultor, se podrá indicar descanso médico, el cual no podrá superar las 48 horas debido a la tipología y la baja complejidad de atención. Para solicitar el descanso médico en soporte físico, el Usuario deberá comunicarlo durante el video llamado al Médico Consultor, para que al cierre del servicio un personal administrativo de IPRESS se comunique con el Usuario para coordinar y acordar el lugar y la hora donde será recogido el documento físico. En caso resulte aplicable, el descanso médico podrá ser entregado a través de correo electrónico al Usuario, el cual incluirá las medidas de seguridad que acrediten la autenticidad e integridad del documento electrónico.
            </li>
            <li type="square">
              En caso el servicio incluya la entrega de medicamentos a domicilio, IPRESS se pondrá en contacto con el Usuario, una vez finalizada la atención, a fin de coordinar la entrega de medicamentos, teniendo en cuenta las zonas de reparto y cobertura detalladas a continuación.
            </li>
          </ul>
        </p>
        <p class="text-justify"> 
            <strong>Zonas de Reparto y Cobertura de Medicamentos</strong>
          <br><br>
            El servicio sólo está disponible en Lima Metropolitana; sin embargo, existen restricciones y limitaciones en las zonas de reparto de medicamentos (cobertura: Chorrillos, Barranco, Surco, Miraflores, San Juan de Miraflores)
            <br>
            Asimismo, las restricciones y limitaciones detalladas en el párrafo precedente podrían sufrir variaciones sin previo aviso en situaciones de caso fortuito, fuerza mayor u otras de características similares.
            <br>
            En caso de restricciones o no cobertura del servicio de farmacia, LA IPRESS se pondrá en contacto con el Usuario a fin de indicarle donde debe dirigirse para recoger las medicinas que el Médico Consultor le haya indicado.
        </p>
             
      </div>
      <div class="modal-footer">
        <div class="container">
          <div class="row w-100">
            <div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4 col-sm-6 offset-sm-3 col-xl-3 offset-xl-5 col-6 offset-3"><button type="button" class="btn btn-outline-success" data-dismiss="modal">Estoy de acuerdo</button></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- jquery-validation -->
<script src="plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="plugins/jquery-validation/additional-methods.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- date-range-picker -->
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" charset="UTF-8"></script>
<!-- AdminLTE App -->
<script src="js/adminlte.min.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="js/demo.js"></script>
<script type="text/javascript">
	$("#iDoc").blur(function(){
		var pass = $(this).val(); 
		$("#password").val(pass);
		$("#password_again").val(pass);
	});

$('#iDocxxx').blur(function(){
    if($('#type').val() == 1 && this.value.length == 8)
    {
      $.ajax("<?php echo base_url('consult-dni');?>",   
      {            
        dataType: 'json',
        type: 'POST',
        data: { dni: this.value },
        beforeSend: function () 
        {            
          $("#iNam").prop('disabled', true);
          $("#iLas").prop('disabled', true);
          //$("#iAdd").prop('disabled', true);
        },
        success: function (data, status, xhr) {    // success callback function
    
          if(data.status && data.usuario) {
			  $('#iNam').val(data.usuario.nombres);
			  
	 
			if(data.usuario.apellidoPaterno != null){
				 
				$('#iLas').val(data.usuario.apellidoPaterno + ' ' + data.usuario.apellidoMaterno);
			}
               
            // $('#iNam').val(data.usuario.data.nombres);
            // $('#iLas').val(data.usuario.data.apellido_paterno + ' ' + data.usuario.data.apellido_materno);
            // $('#iAdd').val(data.usuario.data.domicilio_direccion);

            $("#iNam").prop('disabled', false);
            $("#iLas").prop('disabled', false);
            //$("#iAdd").prop('disabled', false);
			$("#btnRegistrar").prop('disabled', false);
          } else {
			$("#btnRegistrar").prop('disabled', false);
			if(data.usuario) {
              alert(data.usuario);
              $("#btnRegistrar").prop('disabled', true);
            }
			
            $('#iNam').val('');
            $('#iLas').val('');
            //$('#iAdd').val('');
            
            $("#iNam").prop('disabled', false);
            $("#iLas").prop('disabled', false);
            //$("#iAdd").prop('disabled', false);
          }
        }
      });
    }
});

$(document).ready(function () {

const SuccessToast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onClose: (toast) => {
    //...
    window.location.replace("<?php echo base_url();?>");
  }
});


const ErrorToast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onClose: (toast) => {
    //...
    
  }
})





var frm = $('#quickForm');

frm.submit(function (e) {

    e.preventDefault();

   
});

  $('.select2').select2();

  //Date range picker
 $('#reservationdate').datepicker({
    language: 'es'
});

 $('#btnPicker').click(function () {
                //alert('clicked');
                $('#reservationdate').datepicker('show');
            });
  
$("#cbDep").change(function(){

    var provincias = $("#cbProv");
    var distritos = $("#cbDist");

    var departamentos = $(this);

    if($(this).val() != '')
    {
        $.ajax({

            url:   'provincias/'+departamentos.val(),
            type:  'GET',
            dataType: 'json',
            beforeSend: function () 
            {
                departamentos.prop('disabled', true);
                provincias.prop('disabled', true);
                distritos.prop('disabled', true);
            },
            success:  function (r) 
            {
              departamentos.prop('disabled', false);

                // Limpiamos el select
                provincias.find('option').remove();
                
                provincias.append('<option value="">--Seleccione--</option>');                    
                $(r).each(function(i, v){ // indice, valor
                  provincias.append('<option value="' + v.id + '">' + v.name + '</option>');
                })

                provincias.prop('disabled', false);  
                distritos.prop('disabled', false);              
            },
            error: function()
            {
                alert('Ocurrio un error en el servidor ..');
                departamentos.prop('disabled', false);
            }
        });
    }
    else
    {
        provincias.find('option').remove();
        distritos.find('option').remove();
        
        provincias.append('<option value="">--Seleccione--</option>');
        distritos.append('<option value="">--Seleccione--</option>');
    }
});

$("#cbProv").change(function(){


var distritos = $("#cbDist");

var provincias = $(this);

if($(this).val() != '')
{
    $.ajax({

        url:   'distritos/'+provincias.val(),
        type:  'GET',
        dataType: 'json',
        beforeSend: function () 
        {            
            provincias.prop('disabled', true);
            distritos.prop('disabled', true);
        },
        success:  function (r) 
        {
          provincias.prop('disabled', false);

            // Limpiamos el select
            
            distritos.find('option').remove();
            distritos.append('<option value="">--Seleccione--</option>');                    
            $(r).each(function(i, v){ // indice, valor
              distritos.append('<option value="' + v.id + '">' + v.name + '</option>');
            })

            distritos.prop('disabled', false);                
        },
        error: function()
        {
            alert('Ocurrio un error en el servidor ..');
            provincias.prop('disabled', false);
        }
    });
}
else
{
    distritos.find('option').remove();    
    distritos.append('<option value="">--Seleccione--</option>');
}
});


  $.validator.setDefaults({
    submitHandler: function () {
      $("#btnRegistrar").attr('disabled',true);
      $.ajax({
        type: frm.attr('method'),
        url: frm.attr('action'),
        data: frm.serialize(),
        success: function (data) {
          
          if(data.status){  
            Swal.fire({
              icon: 'success',
              timer: 5000,
              title: 'Respuesta exitosa',
              text: 'Se registro el usuario correctamente.',
              onClose: () => {
                window.location.replace("<?php echo base_url('login');?>");
              }
            })
          }else{
            $("#btnRegistrar").attr('disabled',false);
            Swal.fire({
              icon: 'error',
              timer: 5000,
              title: 'Error de validación',
              text: data.message
            })
          }
        },
        error: function (data) {
            $("#btnRegistrar").attr('disabled',false);
            Swal.fire({
              icon: 'error',
              timer: 5000,
              title: 'Error de validación',
              text: data.message
            })
        },
    });          
    }
  });

  $.validator.addMethod("alfanumOespacio", function(value, element) {
      if($("#type" ).val() == 1){
        return /^[0-9]{8}$/i.test(value);
      } else {
        return /^[A-Za-z0-9]{9}$/i.test(value);
		//return /^[0-9]{9}$/i.test(value);
      }
      
      return true;
       
    }, "Ingrese un documento válido.");

  $('#quickForm').validate({
    rules: {
      firstname: {
        required: true,
        normalizer: function( value ) {
           return $.trim(value);
        }
      },
      lastname: {
        required: true,
        normalizer: function( value ) {
           return $.trim(value);
        }
      },
      document: {
        required: true,
        alfanumOespacio: true
      },
      email: {
        required: true,
        email: true,
      },
      canalVenta: {
        required: true
      },
      phone: {
        required: true,
        number: true,
        minlength: 9
      },
      address: {
        required: true
      },
      department: {
        required: true
      },
      province: {
        required: true
      },
      district: {
        required: true
      },
      password: {
        required: true,
        minlength: 6
      },
      password_again: {
        equalTo: "#password"
      },
      terms: {
        required: true
      },
    },
    messages: {
      firstname: {
        required: "Ingrese el nombre"
      },
      lastname: {
        required: "Ingrese el apellido"
      },
      document: {
        required: "Ingrese el documento"
      },
      canalVenta: {
        required: "Seleccione el canal de venta"
      },
      email: {
        required: "Ingrese una dirección de correo electrónico",
        email: "Por favor, introduce una dirección de correo electrónico válida"
      },
      phone: {
        required: "Ingrese el teléfono",
        number: "Teléfono no válido",
        minlength: "El teléfono debe ser de 9 dígitos"
      },
      address: {
        required: "Ingrese la dirección"
      },
      department: {
        required: "Ingrese el departamento"
      },
      province: {
        required: "Ingrese la provincia"
      },
      district: {
        required: "Ingrese el distrito"
      },
      password: {
        required: "Por favor ingrese una contraseña",
        minlength: "Tu contraseña debe tener al menos 6 caracteres"
      },
      password_again: {
        equalTo: "Las contraseñas deben coincidir"
        
      },
      terms: "Acepta nuestros términos"
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
});
</script>
</body>
</html>
