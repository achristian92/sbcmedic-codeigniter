<?php
  defined('BASEPATH') or exit('No direct script access allowed');

  $user = $this->input->post('user');
  $motivoCita = $this->input->post('motivoCita');

  $fecha = str_replace("/", "-", $this->input->post('fechaCitaAsignar'));
  $fechaConsulta =  date("Y-m-d", strtotime($fecha));

  $fechaExamen = str_replace("/", "-", $this->input->post('fecha'));
  $fechaExamen =  date("Y-m-d", strtotime($fechaExamen));

  $especialidad = $this->input->post('cmbEspecialidad');
  $medico = $this->input->post('cmbMedico');

  $procedimientosPrincipal = $this->input->post('codigoProcedimiento');
  $procedimientos = $this->input->post('codigoProcedimiento');
   
  if($this->input->post('procedimientosExtra'))
  {
    $procedimientos = "";
    foreach ($this->input->post('procedimientosExtra') as $value) {
      $procedimientos = $procedimientos.$value.",";
      
    }
    
    $procedimientos = substr($procedimientos, 0, -1);
  }

  $exmanenes = "";

  if($this->input->post('laboratorios'))
  {
    foreach ($this->input->post('laboratorios') as $value) {
      $exmanenes = $exmanenes.$value.",";
    }
    
    $exmanenes = substr($exmanenes, 0, -1);
  }

  $this->db->select("left(min(start_time), 5) as horaInicio, left(max(end_time), 5) as horaFinal, date");
  $this->db->from("availabilities");
  $this->db->where("idDoctor", $medico);
  $this->db->where("disponible", 1);
  $this->db->where("date", $fechaConsulta);
  $this->db->group_by("date");

  $query = $this->db->get();
  $row_resultado = $query->row_array();

  if(isset($row_resultado['date'])){
    $oldstatus = $row_resultado['date'];
  } else {
    $oldstatus = null;
  }
      
  $fechaInicio = strtotime($oldstatus);
  $fechaFin = $fechaInicio;

  if(isset($row_resultado['horaInicio'])){
    $time = $row_resultado['horaInicio'];
  } else {
    $time = null;
  }

  if(isset($row_resultado['horaFinal'])){
    $time2 = $row_resultado['horaFinal'];
  } else {
    $time2 = null;
  }
      
  $apertura = new DateTime($oldstatus . " " . $time);
  $cierre = new DateTime($oldstatus . " " . $time2);

  $tiempo = $apertura->diff($cierre);

  $hora = null;

  $hora = $tiempo->format('%H%i') * 12;

?>
<div class="row mt-1">
  <div class="col">
    <button type="button" class="btn btn-outline-info btn-block" onclick="detalle_citas_asignadas('<?php echo $fechaConsulta; ?>', '<?php echo $especialidad; ?>')" title="Ver Citas Asignadas"><i class="fas fa-laptop-medical"></i> VER Citas YA Programadas <i class="far fa-eye"></i></button>
  </div>
  <div class="col">
    <button type="button" class="btn btn-outline-success btn-block" onclick="detalle_fechas_disponibles('<?php echo $medico; ?>')" title="Ver Horarios de profesionales"><i class="fas fa-calendar-alt"></i> Ver Horarios Dispoinbles <i class="far fa-eye"></i></button>
  </div>
</div>

<form action="<?php echo base_url('procesarPago');?>" method="POST" id="frmGenearCita">
  <div class="form-group row" style="padding: 10px;">
    <div class="col-md-6">
      <select name="horarios[]" id="horarios" multiple class="form-control" style="width:300px; height: 200px; font-weight: bold;" required>
        <?php
          $disabilitar = null;
          $bcolor = null;
          $color = null;

          for ($i = $fechaInicio; $i <= $fechaFin; $i += 86400) {
            $fecha = date("Y-m-d", $i);
            $dia = date("w", strtotime($fecha));

            if ($dia == 0) continue;

            for ($ii = 0; $ii <= $hora; $ii++) {

                if ($time == $time2) {

                  $time = $time;
                  continue;
                }

                $this->db->select("count(idCita) as cantidad");
                $this->db->from("cita");
                $this->db->where("idMedico", $medico);
                $this->db->where("status in(1, 0)");
                $this->db->where("fechaCita", $fecha);
                $this->db->where("RIGHT(horaCita, 5) >  '$time' ");

                $this->db->where("'$time' BETWEEN left(horaCita,5) and RIGHT(horaCita,5)");

                $query = $this->db->get();
                $row_resultadoHora = $query->row_array();
            
                $this->db->select("count(idAvailability) as cantidad");
                $this->db->from("availabilities");
                $this->db->where("idDoctor", $medico);
                $this->db->where("disponible", 2);
                $this->db->where("date", $fecha);
                $this->db->where("'$time' < left(end_time, 5)");
                $this->db->where("'$time' BETWEEN left(start_time,5) and left(end_time,5)");

                $query = $this->db->get();
                $row_resultadoHora2 = $query->row_array();
				
				$this->db->select("count(idAvailability) as cantidad");
                $this->db->from("availabilities");
                $this->db->where("idDoctor", $medico);
                $this->db->where("disponible", 1);
                $this->db->where("date", $fecha);
                $this->db->where("left(start_time, 5) = '$time'");

                $query = $this->db->get();
                $row_resultadoHora3 = $query->row_array();
				
            
                $time3 = date('H:i', strtotime($time) + 60 * 5);

                if ($row_resultadoHora3["cantidad"] == 0 ||  $row_resultadoHora["cantidad"] > 0 || $row_resultadoHora2["cantidad"] > 0) {
                  $disabilitar = "disabled";
                  $bcolor = "#607D8B";
                  $color = "#F5F5F5";
                }

                if (date('H:i', time()) > $time and $fecha == date('Y-m-d', time())) {
                
                $disabilitar = "disabled";
                $bcolor = "#607D8B";
                $color = "#F5F5F5";
            }
        ?>
          <option value="<?php echo $time . " - " . $time3; ?>" <?php echo $disabilitar; ?> style="background-color: <?php echo $bcolor; ?>; color: <?php echo $color; ?>" ><?php echo $time . " - " . $time3; ?></option>
        <?php
              $time = $time3;
          
              $disabilitar = "";
              $bcolor = "";
              $color = "";
            }
          } 
        ?>
        <!-- <span><button class="btn btn-medicine">08:20 - 08:50 a.m.</button></span> -->
      </select>
    </div>
    <div class="col-md-6 bg-info "  style="padding: 1rem;border: 2px solid #ccc;text-align: center; font-size: 23px;">
      <br>
      <div><i class="far fa-clock"></i>&nbsp; => &nbsp;<u><strong id="cantidad">0</strong></u></div> 
      <div><i class="fas fa-hand-pointer"></i>&nbsp; => &nbsp;<strong id="horasview">0</strong></div> 
    </div>
  </div>
  <div class="row">
    <div class="col">
      <textarea name="observaciones" class="form-control" cols="2" rows="2" placeholder="Observaciones"></textarea>
    </div>
  </div>
  <div class="row mt-2">
    <div class="col ">
      <button type="submit" class="btn btn-success btn-lg btn-block" title="Asignar Horarios">GENERAR CITA</button>
      <input type="hidden" name="fecha" value="<?php echo $fechaExamen; ?>">
      <input type="hidden" name="exmanenes" value="<?php echo $exmanenes; ?>">
      <input type="hidden" name="profesional" value="<?php echo $medico; ?>">
      <input type="hidden" name="tipoCita" value="CP">
      <input type="hidden" name="user" value="<?php echo $user; ?>">
      <input type="hidden" name="fechaCita" value="<?php echo $fechaConsulta; ?>">
      <input type="hidden" name="idEspecialidad" value="<?php echo $especialidad; ?>">
      <input type="hidden" name="procedimientos" value="<?php echo $procedimientos; ?>">
      <input type="hidden" name="opcionCita" value="<?php echo $this->input->post('opcionCita'); ?>">
      <input type="hidden" name="ids" value="<?php echo $this->input->post('ids'); ?>">
      <input type="hidden" id="codeOne" name="codeOne" value="<?php echo $this->input->post('codeOne'); ?>">
      <input type="hidden"name="procedimientosPrincipal" value="<?php echo $procedimientosPrincipal; ?>">
      <input type="hidden"name="motivoCita" value="<?php echo $motivoCita; ?>">
    </div>
  </div>
</form>
<script>
  comprobar();

  $("select#horarios").change(function() {
    var values = $(this).val();

    var count = $("#horarios :selected").length;
     
    var minutos = restarHoras(values[0].substr(0, 5), values[values.length-1].substr(8, 5));
    var horas = values[0].substr(0, 5) + ' - ' + values[values.length-1].substr(8, 5);
    $("#cantidad").text(minutos);
    $("#horasview").text(horas);
    $("#horaCita").val(horas);
  });

  function comprobar() {
    if( $('#adicional').is(':checked') ) {
      $('#horarios').prop("required", false);
    } else {
      $('#horarios').prop("required", true);
    }
  }
  
  var frm = $('#frmGenearCita');
 
  $.validator.setDefaults({
    submitHandler: function () {
      
        Swal.fire({
        title: '¿ESTÁS SEGURO DE GENERAR LA CITA?',
        text: 'Una vez confirmada tu reserva, no se podrán realizar cambios.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Seguro!',
        cancelButtonText: 'Cancelar',
      }).then((result) => {
        if (result.value) {
          $("#btn_pagarPresencial").attr('disabled',true);
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
                  text: data.message,
                  onClose: () => {
                    window.open("<?php echo base_url('cash-management-records/print/') ?>" + data.idCita + '?code=' + data.code + '&codPrimary=' + data.codPrincipal);
                    window.close();
                    //window.location.replace("<?php echo base_url('miscitas');?>");
                  }
                })
              }else{
                $("#btn_pagarPresencial").attr('disabled',false);
                Swal.fire({
                  icon: 'error',
                  timer: 5000,
                  title: 'Error de validación',
                  text: data.message
                })
              }
            },
            error: function (data) {
              $("#btn_pagarPresencial").attr('disabled',false);
              Swal.fire({
                icon: 'error',
                timer: 5000,
                title: 'Error interno',
                text: 'Ha ocurrido un error interno!'
              })
            },
          });
        }
      })
    }
  });
  
  $('#frmGenearCita').validate({
    rules: {
      horarios: {
        required: true
        } 
    },
      messages: {
        "horarios[]": {
          required: 'Seleccione un horario.'
        }       
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

  function restarHoras(inicioHora, finalHora) {

    inicio = inicioHora;
    fin = finalHora;

    inicioMinutos = parseInt(inicio.substr(3,2));
    inicioHoras = parseInt(inicio.substr(0,2));

    finMinutos = parseInt(fin.substr(3,2));
    finHoras = parseInt(fin.substr(0,2));

    transcurridoMinutos = finMinutos - inicioMinutos;
    transcurridoHoras = finHoras - inicioHoras;

    if (transcurridoMinutos < 0) {
      transcurridoHoras--;
      transcurridoMinutos = 60 + transcurridoMinutos;
    }

    horas = transcurridoHoras.toString();
    minutos = transcurridoMinutos.toString();

    if (horas.length < 2) {
      horas = "0"+horas;
    }

    if (horas.length < 2) {
      horas = "0"+horas;
    }

    return  horas+":"+minutos;
  }
</script>