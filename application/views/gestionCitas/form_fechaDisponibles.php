<?php
defined('BASEPATH') OR exit('No direct script access allowed');

  $medico = ($this->input->post("profesionales"))? $this->input->post("profesionales") :$this->uri->segment(2);
  $fecha = null;
  if($this->input->get("fechaBusqueda"))
  {
    $porciones = explode("/", $this->input->get("fechaBusqueda"));
    $fecha = $porciones[2]."-".$porciones[1]."-".$porciones[0];
  }

  $fechaConsulta = $this->input->post("fechaBusqueda") ?  $this->input->post("fechaBusqueda") : $fecha;

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

?><!DOCTYPE html>
<html>
<head>
<base href="https://sbcmedic.com/consulta/" >
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SBCMedic | Reservar Cita</title>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo  base_url('img/favicon.ico');?>"/>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php $this->load->view("styles"); ?>
  
     
  <!-- fullCalendar -->
  <link rel="stylesheet" href="plugins/fullcalendar/main.min.css">
  <link rel="stylesheet" href="plugins/fullcalendar-daygrid/main.min.css">
  <link rel="stylesheet" href="plugins/fullcalendar-timegrid/main.min.css">
  <link rel="stylesheet" href="plugins/fullcalendar-bootstrap/main.min.css">
  <style>
 
 .fc-today {
     background: #fff !important;
     color: #000;
 }

 html, body {
      margin: 0;
      padding: 0;
      font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
      font-size: 14px;
    }

 #calendar {
  max-width: 500px;
      max-height: 200px;
      margin: 10px auto;
    }

 </style>
</head>
 

<body >
<div class="container">
  <?php if ($this->uri->segment(2) == 0) { ?>
<form action="<?php echo base_url('available-dates')."/0";?>" method="POST">
  <div class="row mt-2">
  
    <div class="col">
     
    <select class="form-control comobSelect2" required name="especialidades" id="especialidades" required>
      <option value="">Especialidad</option>
      <?php foreach ($especialidades as $especialidad) { ?>
        <option value="<?=$especialidad->idSpecialty;?>" <?php echo $this->input->post("especialidades") == $especialidad->idSpecialty  ? "selected" : ""; ?> ><?=$especialidad->name;?></option>                    
      <?php } ?>
    </select>
    </div>
    <div class="col">
    
    <select class="form-control comobSelect2" id="profesionales" name="profesionales">
      <option value="">Profesional</option>
      <?php foreach ($profesionales as $profesional) { ?>
        <option value="<?=$profesional->idDoctor;?>" <?php echo $this->input->post("profesionales") == $profesional->idDoctor  ? "selected" : ""; ?> ><?=$profesional->nombreMedico;?></option>                    
      <?php } ?>
    </select>
    </div>
    <div class="col">
      <input type="date" name="fechaBusqueda" class="form-control" value="<?php echo $this->input->post("fechaBusqueda") ?  $this->input->post("fechaBusqueda") : ""; ?>">
      </div>
      <div class="col-2">
      <button type="submit" class="btn btn-primary my-1">Consultar</button>
      </div>

      
  </div>
</form>
<?php } ?>
  <div class="row mt-2">
    <div class="col">
      

    <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Calendario</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Fechas</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="horarios-tab" data-toggle="tab" href="#horarios" role="tab" aria-controls="horarios" aria-selected="false">Horas Disponibles</a>
  </li>
 
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
  <div id='calendar'></div>
  </div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
      <table class="table table-striped" style="font-size: 11px;">      <thead>        <tr style="font-size: 14px;">          <th scope="col">Fecha</th>          <th scope="col">Inicio</th>          <th scope="col">Final</th>          <th scope="col">Turno</th>          <th scope="col">Área</th>          <th scope="col">Profesional</th>          <th></th>        </tr>      </thead>      <tbody>        <?php foreach ($registros as $row) { ?>          <tr style="font-weight: bold; background-color: <?php if ($row->area == "CE") echo "#bfa095;"; else if ($row->area == "CE-COVID") echo "#ffffb0;"; ?>">            <td><?php echo date("d/m/Y",strtotime($row->date));?></td>            <td><?=$row->inicio;?></td>            <td><?=$row->final;?></td>            <td><?=$row->turno;?></td>            <td><?=$row->area;?></td>            <td><?=$row->profesional;?></td>        </tr>                          <?php } ?>            </tbody>    </table>
  </div>

  <div class="tab-pane fade" id="horarios" role="tabpanel" aria-labelledby="horarios-tab">
  <div class="form-group row" style="padding: 10px;">
    <div class="col-3">
      Fecha de Consulta
    </div>
    <div class="col">
      <strong>
      <?php echo $fechaConsulta;?></strong>
    </div>
  </div>
  <div class="form-group row" style="padding: 10px;">
    <div class="col">
      <select name="horarios[]" id="horarios" multiple class="form-control" style="font-size: 16px; width:100%; height: 450%; font-weight: bold;" required>
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

                if ($row_resultadoHora3["cantidad"] == 0 || $row_resultadoHora["cantidad"] > 0 || $row_resultadoHora2["cantidad"] > 0) {
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
 
  </div>
  </div>
  
  </div>
</div>


    </div>
  </div>
</div>


<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>

 
<!-- Bootstrap 4 -->
<script src="plugins/moment/moment.min.js"></script>
<!--<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
 
<!-- fullCalendar 2.2.5 -->
<script src='plugins/fullcalendar/locales/es.js'></script>
<script src="plugins/fullcalendar/main.min.js"></script>
<script src="plugins/fullcalendar-daygrid/main.min.js"></script>
<script src="plugins/fullcalendar-timegrid/main.min.js"></script>
<script src="plugins/fullcalendar-interaction/main.min.js"></script>
<script src="plugins/fullcalendar-bootstrap/main.min.js"></script>
 
<script src="<?php echo base_url('plugins/select2/js/select2.full.min.js'); ?>"></script>

<!-- Page script -->
<script>

$("#especialidades").change(function(){
        // Guardamos el select de cursos
        var doctores = $("#profesionales");

        // Guardamos el select de alumnos
        var especialidades = $(this);

        if($(this).val() != '')
        {
            $.ajax({

                url:   '<?=base_url('doctores');?>/'+especialidades.val(),
                type:  'GET',
                dataType: 'json',
                beforeSend: function () 
                {
                    especialidades.prop('disabled', true);
                },
                success:  function (r) 
                {
                  especialidades.prop('disabled', false);

                    // Limpiamos el select
                    doctores.find('option').remove();
                    doctores.append('<option value="">Seleccionar</option>');
                    $(r).each(function(i, v){ // indice, valor
                      doctores.append('<option value="' + v.idDoctor + '">' + v.title +' '+ v.lastname + ' ' + v.firstname + '</option>');
                    })

                    doctores.prop('disabled', false);
                },
                error: function()
                {
                    alert('Ocurrio un error en el servidor ..');
                    especialidades.prop('disabled', false);
                }
            });
        }
        else
        {
            doctores.find('option').remove();
            doctores.prop('disabled', true);
         
            doctores.append('<option value="">Seleccionar</option>');
        }
    });

 $('.comobSelect2').select2();
    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()

    var Calendar = FullCalendar.Calendar;
    var Draggable = FullCalendarInteraction.Draggable;

    var containerEl = document.getElementById('external-events');
    var checkbox = document.getElementById('drop-remove');
    var calendarEl = document.getElementById('calendar');
   
    var calendar = new Calendar(calendarEl, {
      plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
      locale: 'es',
      header    : {
        left  : 'prev,next',
        center: 'title',
        right : 'dayGridMonth'
      },
      'themeSystem': 'bootstrap',
      validRange: {
        start: "<?php echo date('Y-m-d');?>"
      },

      events    : '<?=base_url('listarEventos-medicos');?>'+ '/<?php echo $medico;?>' , 
    });

    calendar.render();
 
</script>

</body>
</html>

