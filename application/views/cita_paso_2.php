<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
$this->db->select("left(min(start_time), 5) as horaInicio, left(max(end_time), 5) as horaFinal, date");
$this->db->from("availabilities");
$this->db->where("idDoctor", $idMedico);
$this->db->where("disponible", 1);
$this->db->where("date", $fecha);
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

//$time = $row_resultado['horaInicio'];
//$time2 = $row_resultado['horaFinal'];

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
//$hora = $tiempo->format('%H') * 12;

?><!DOCTYPE html>
<html>
<?php $this->load->view('head'); ?>
 
<body class="hold-transition sidebar-mini pace-primary" style="background-image: url(img/fondo_body.png); height: 100%;  background-position: right;  background-repeat: no-repeat;  ">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light bg_transparent" style="height: 100px;">
    <!-- Left navbar links -->
    <ul class="navbar-nav h-100 align-items-center col-md-6">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item" style=";">
        <span style="vertical-align:middle;  "><span style="margin-left: 10px;font-size: 2.4rem;font-weight:bold;color: #004663;vertical-align:middle;  "> Reservar Cita<span></span>
      </li>
      
    </ul>
    <div class="col-md-6 text-right">
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-info" style="margin-left: 10px;border-radius: 20px;height: 40px;width: 40px; cursor: default;"> 1 </button>
            <button type="button" class="btn btn-info step-active" style="margin-left: 10px;border-radius: 20px;height: 40px;width: 40px; cursor: default;"> 2 </button>
        </div>
    </div>
  </nav>
  <!-- /.navbar -->

  <?php $this->load->view('aside'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background: transparent;">
  

    <!-- Main content -->
    <section class="content" style="padding: 20px;">

      <!-- Default box -->
      <div class="card card-shadow">
      <div class="card-header">
      <div class="row">
       <div class="col-md-1">
          <button style="margin-top: 5px;" type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                <img src="img/logo_doctor.png" class=""/></button>
        </div>
        <div class="col-md-3 h-100 align-items-center" style="padding-top: 2px;">
          <span style="vertical-align:middle;  "> <h4 class="font-green"><strong>Confirmar Datos</strong></h4></span>
        </div>
      </div>
      </div>        
        <div class="card-body" >

        <form role="form" id="formPagoPresencial" method="post" action="<?php echo base_url('procesarPago');?>">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 col-form-label"><i class="icon icon-check"></i>Fecha :</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="inputEmail3" placeholder="Fecha" value="<?=date("d/m/Y",strtotime($fecha));?>" readonly style=" background-color:#219ebc; font-weight: bold;color:#f0efeb">
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group row">
                <label for="idtipoCita" class="col-sm-4 col-form-label"><i class="icon icon-check"></i>Tipo Motivo Cita :</label>
                <div class="col-sm-6">
                  <select class="form-control" name="idtipoCita" id="idtipoCita"  required>
                    <option value="">Seleccionar</option>
					          <?php
                        foreach ($motivoCita->result() as  $row) {
                          echo '<option value="'.$row->id.'">'.$row->nombre.'</option>';
                        }
                    ?> 
                  </select>
                </div>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 col-form-label"><i class="icon icon-check"></i>Tipo de Cita :</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="txtCita" placeholder="" value="<?php if($tipoCita =="CV") echo "Cita Virtual"; else if($tipoCita =="CP") echo "Cita Presencial"; else if($tipoCita =="PR") echo "Cita Procedimiento"; else echo "Cita Domiciliaria" ?>" readonly>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 col-form-label"><i class="icon icon-check"></i>Especialidad :</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="txtEspecialidad" placeholder="" value="<?=$especialidad;?>" readonly>
                </div>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 col-form-label"><i class="icon icon-check"></i>Profesional :</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="inputMedico" placeholder="" value="<?=$doctor;?>" readonly>
                </div>
              </div>
            </div>
            <?php if ($sucursal != 2) { ?>
              <div class="col-md-6">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-4 col-form-label"><i class="icon icon-check"></i>Precio (S/.) :</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="txtMonto" id="txtMonto" placeholder="" value="<?php echo $monto;?>" <?php echo ($rol == 1 || $rol == 4) ? "" : "readonly"; ?>   style=" background-color:#2a9d8f; font-weight: bold;color:#f0efeb">
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>


          <div class="row">
          <div class="col-md-6">
              <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 col-form-label"><p style="color:green; font-size: 18PX;">Virtual =></p> </label>
                <div class="col-sm-6">
                  <input class="form-control" type="checkbox" value="1" 
                   name="virtual">
                </div>
              </div>
            </div>
             
            <div class="col-md-6">
              <div class="form-group row">
                <label for="inputEmail3" class="col-sm-4 col-form-label"><p style="color:brown; font-size: 18PX;">GRATIS =></p> </label>
                <div class="col-sm-6">
                  <input class="form-control" type="checkbox" value="1" onclick="esGratis(this.checked)" name="gratis">
                </div>
              </div>
            </div>
             
           
          </div>
		  
 
          <div class="row">
      <div class="col"><u><strong>ASIGNACIÓN DE PROCEDIMIENTO</strong></u></div>
    </div>  
		   <div class="row">
            <div class="col-md-12">
            <div class="form-group row">
              <select name="procedimiento" id="procedimiento" class="form-control procedimientos" required></select>
            </div>
            </div>
			 
			
          </div>
		
  <?php if( $this->uri->segment(3) != 'add') { ?>
    <div class="row">
      <div class="col"><u><strong>ASIGNACIÓN DE HORARIOS</strong></u></div>
    </div>      
          <div class=" form-group row" style="padding: 2px;">

<div class="col-md-6">

  <select name="horarios[]" id="horarios" multiple class="form-control" style="width:300px; height: 200px; font-weight: bold;  " required>
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
        $this->db->where("idMedico", $idMedico);
        $this->db->where("status in(1, 0)");
        $this->db->where("fechaCita", $fecha);
        $this->db->where("RIGHT(horaCita, 5) >  '$time' ");

        $this->db->where("'$time' BETWEEN left(horaCita,5) and RIGHT(horaCita,5)");

        $query = $this->db->get();
        $row_resultadoHora = $query->row_array();
		
        $this->db->select("count(idAvailability) as cantidad");
        $this->db->from("availabilities");
        $this->db->where("idDoctor", $idMedico);
        $this->db->where("disponible", 2);
        $this->db->where("date", $fecha);
        $this->db->where("'$time' < left(end_time, 5)");
        $this->db->where("'$time' BETWEEN left(start_time,5) and left(end_time,5)");

        $query = $this->db->get();
        $row_resultadoHora2 = $query->row_array();
		
        $time3 = date('H:i', strtotime($time) + 60 * 5);

        if ($row_resultadoHora["cantidad"] > 0 || $row_resultadoHora2["cantidad"] > 0) {
          //$time = $time3;
          //continue;
		  $disabilitar = "disabled";
          $bcolor = "#607D8B";
          $color = "#F5F5F5";
        }

        if (date('H:i', time()) > $time and $fecha == date('Y-m-d', time())) {
          //$time = $time3;
          //continue;
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
    } ?>
    <!-- <span><button class="btn btn-medicine">08:20 - 08:50 a.m.</button></span> -->
  </select>
</div>
<div class="col-md-6 bg-info "  style="padding: 1rem;border: 2px solid #ccc;text-align: center; font-size: 23px;">
<br>
  <div><i class="far fa-clock"></i>&nbsp; => &nbsp;<u><strong id="cantidad">0</strong></u></div> 
  <div><i class="fas fa-hand-pointer"></i>&nbsp; => &nbsp;<strong id="horasview">0</strong></div> 
</div>

</div>

<?php } ?>
       
          
          <div class="row">
            <div class="col-md-12">
              <input type="text" class="form-control" placeholder="Observación de la cita" name="motivo" id="motivo" maxlength="100">
            </div>
          </div>


          <div class="row mt-2">
            <div class="col-md-6 offset-md-3 col-sm-6 offset-sm-3 offset-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                <?php if ($sucursal != 2) { ?>
                  <!-- <button id="btn_pagar" class="btn btn-secondary" title="Realizar el pago" disabled>
                  Pago Online <i class="fas fa-credit-card"></i></button>-->
                <?php if($tipoCita !="CV" || $rol == 4 || $rol == 1) { ?>
                    <button id="btn_pagarPresencial" class="btn btn-primary" title="Realizar el pago" disabled>Pago Presencial</i></button>
                <?php }   ?>
                <?php } else { ?>
                  <button id="btn_pagarPresencial" class="btn btn-warning" style="font-weight: bold;" title="Guardar cita" >CONFIRMAR CITA</i></button>
                <?php } ?>
              </div>
            </div>
          </div>
          <input type="hidden" name="idMedico"  id="idMedico" value="<?php echo $idMedico;?>">
          <input type="hidden" name="idEspecialidad"  id="idEspecialidad" value="<?php echo $idEspecialidad;?>">
          <input type="hidden" name="fechaCita" id="fechaCita" value="<?php echo $fecha;?>">
          <?php $horas = $inicio. " - " . $fin;?>
          <input type="hidden" name="horaCita" id="horaCita">
          <input type="hidden" name="idDisponible" id="idDisponible" value="<?php echo $idDisponible;?>">
          <input type="hidden" name="tipoCita" id="tipoCita" value="<?php echo $tipoCita;?>">
          <input type="hidden" name="precio" id="precio" value="<?php echo $monto;?>">
          <input type="hidden" name="user" id="user" value="<?php echo $user;?>">
          <input type="hidden" name="sucursal" id="sucursal" value="<?php echo $sucursal;?>">
		  <input type="hidden" name="precioTemporal" id="precioTemporal" value="<?php echo $monto;?>">
		  <input type="hidden" name="adicional" id="adicional" value="<?php echo $this->uri->segment(3) == 'add'? 1 : null; ?>">
        </form>
      </div>
      <!-- /.card -->
      <div class="card-body" >
        <div class="container">
          <div class="row">
            <div class="col-md-4 offset-md-4 col-lg-4 offset-lg-4 col-sm-8 offset-sm-2 col-xl-2 offset-xl-5 col-4 offset-4">
              <small><a href="<?=base_url('cita/buscar/').$tipoCita;?>" class="btn btn-info">VOLVER</a></small>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer bg_transparent">
    <div class="float-right d-none d-sm-block">
      <b>Versión</b> <?php echo $version["version"];?>
    </div>
    <strong>Copyright &copy; 2020 <a href="javascript:void(0)">SBCMedic</a>.</strong> Derechos Reservados.
  </footer>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- pace-progress -->
<script src="plugins/pace-progress/pace.min.js"></script>
<script src="plugins/waitme/waitMe.min.js"></script>
<!-- AdminLTE App -->
<script src="js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- jquery-validation -->
<script src="plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="plugins/jquery-validation/additional-methods.min.js"></script>

<!-- Incluye Culqi Checkout en tu sitio web-->
<!-- Incluyendo Culqi Checkout 
<script src="https://checkout.culqi.com/js/v3"></script>-->
<!-- Select2 -->
<script src="<?php echo base_url('plugins/select2/js/select2.full.min.js');?>"></script>
</body>

<script>
 $('.select2').select2();

//$("#btn_pagar").attr('disabled',false);
$("#btn_pagarPresencial").attr('disabled',false);

var frm = $('#formPagoPresencial');
  $.validator.setDefaults({
    submitHandler: function () {
 
        Swal.fire({
        title: '¿Estás seguro de registrar la cita?',
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
					          window.open("<?php echo base_url('cash-management/print/') ?>" + data.idCita);
                    window.location.replace("<?php echo base_url('miscitas');?>");
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
  
  $('#formPagoPresencial').validate({
    rules: {
 
      procedimiento: {
          required: true
        },	
        horarios: {
          required: true
        },	

      idtipoCita: {
          required: true
        },			
      ruc: {
          required: true
        },
      direccionruc: {
        required: true
      },
      razonSocial: {
        required: true
      },
      nombreCompleto: {
        required: true
      }
    },
      messages: {
 	  
        procedimiento: {
          required: 'El procedimieno es obligatorio.'
        },
        "horarios[]": {
          required: 'Seleccione algún horario.'
        },
		idtipoCita: {
          required: 'El motivo de cita es obligatorio.'
        },		
        ruc: {
          required: 'Número de ruc es obligatorio.'
        },
        direccionruc: {
          required: 'Direccíon es obligatorio.'
        },
        razonSocial: {
          required: 'Razón social es obligatorio.'
        },
        nombreCompleto: {
          required: 'Nombre es obligatorio.'
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

  var precio = $('#txtMonto').val();
  var producto = $('#inputMedico').val();
  var titulo = $('#txtEspecialidad').val();
  
  var idMedico = $('#idMedico').val();
  var idEspecialidad = $('#idEspecialidad').val();
  var fechaCita = $('#fechaCita').val();
  var horaCita = $('#horaCita').val();
  var idDisponible = $('#idDisponible').val();
  var tipoCita = $('#tipoCita').val();

  var nombreCompleto = $('#nombreCompleto').val();
  var razonSocial = $('#razonSocial').val();
  var ruc = $('#ruc').val();
  var direccion = $('#direccion').val();
  var tipoComprobante = $('#tipoComprobante').val();
  var user = $('#user').val();
  var sucursal = $('#sucursal').val();
  var distrito = $('#distrito').val();


  /*

 
  Culqi.publicKey = 'pk_live_9d5ad53069afb615';

  Culqi.settings({
    title: titulo,
    currency: 'PEN',
    description: producto,
    amount: precio*100
  });

  Culqi.options({
    //modal: false,
    style: {
      logo: "<?php echo base_url('img/logo_sbcmedic.png');?>",
    }
  });*/


 function verficarComprobante() {
    var opt = $('#comprobante').val();
    if (opt == "1") {
        
        if ($('#nombreCompleto').val() == '') alert('Ingrese el nombre completo en la boleta');

        return false;
     
    } else {
       
    }
    return false;
  }

  var opt = $('#comprobante').val();
  $('#btn_pagar').on('click', function(e) {
      // Abre el formulario con la configuración en Culqi.settings
      if ($('#nombreCompleto').val() != '') {

        Culqi.open();
        e.preventDefault();
      }  else {
        Culqi.close();
        return false;
      }
  });
    
function culqi() {
  if (Culqi.token) { // ¡Objeto Token creado exitosamente!
    $(document).ajaxStart(function(){
      run_waitMe();
    });

    var token = Culqi.token.id;
    var card_number = Culqi.token.card_number;
    var creation_date = Culqi.token.creation_date;
    var email = Culqi.token.email;
    var last_four = Culqi.token.last_four;
    var ip_country_code = Culqi.token.client.ip_country_code;
    var card_brand = Culqi.token.iin.card_brand;
    var card_category = Culqi.token.iin.card_category;
    var card_type = Culqi.token.iin.card_type;
    var motivo = $('#motivo').val();
    //var country_codeCard = Culqi.token.iin.issuer.country_code;

    var data = { 
          token: token, precio: precio*100, descripcion: producto, card_number: card_number, 
          creation_date: creation_date, email: email, last_four: last_four, 
          ip_country_code: ip_country_code, card_brand: card_brand, card_category: card_category, card_type: card_type,
          motivo: motivo, idMedico: idMedico, idEspecialidad: idEspecialidad, fechaCita: fechaCita, horaCita: horaCita, idDisponible: idDisponible, tipoCita: tipoCita, nombreCompleto: nombreCompleto, razonSocial: razonSocial, ruc: ruc, direccion: direccion, tipoComprobante: tipoComprobante, user: user, sucursal: sucursal, distrito: distrito
    };

    $.ajax({
      type: "POST",
      url: 'procesarPago',
      data: data,
      dataType: 'JSON',
      success: function(data) {
        
        var result = "";
        if(data.constructor == String){
            result = JSON.parse(data);
        }

        if(data.constructor == Object){
            result = JSON.parse(JSON.stringify(data));
        }
         
        if(result.object === 'charge'){
          alertaShowOK('El pago se realizo satisfactoriamente!. Se te envío un email con la información de la cita.', 'success', 'Transacción exitosa');
        }

        if(result.object === 'error'){
          alertaShow(result.merchant_message, 'error', 'Error');
        }
      },
      error: function(data) {
        alertaShow(data.responseText, 'error', 'Error de Transacción');
      },
      fail: function(data) {
        alertaShow(data.responseText, 'error', 'Error');
      }
    });
  } else { // ¡Hubo algún problema!
      alertaShow(Culqi.error.merchant_message, 'error', 'Error');
  }
};

  function run_waitMe(){
    $('body').waitMe({
      effect: 'timer',
      text: 'Procesando el pago...',
      bg: 'rgba(38,179,120,0.2)',
      color:'#06d6a0',
      fontSize : '28px'
    });
  }

  function alertaShowOK(message, tipo, titulo){
    Swal.fire({
      icon: tipo,
      title: titulo,
      timer: 7000,
      text: message,
      onClose: () => {
        window.location.replace("<?php echo base_url('miscitas');?>");
      }
    });

    $('body').waitMe('hide');
  }

  function alertaShow(message, tipo, titulo){
    Swal.fire({
      icon: tipo,
      title: titulo,
      text: message
    });

    $('body').waitMe('hide');
  }


  function seleccionado(){
    var opt = $('#tipoComprobante').val();
    if (opt == "BOL") {
        $('#divBoleta').show();
        $('#divFactura').hide();
     
    } else {
      $('#divBoleta').hide();
      $('#divFactura').show();
      //$('#divFactura').css('background-color', '#DADADA');
    }  
  }
  
  
  function esGratis(val){
    if(val){
      $('#txtMonto').val('0.00');
      $('#precio').val(0);
	  $("#btn_pagar").attr('disabled', true);
    } else {
      $('#txtMonto').val($('#precioTemporal').val());
      $('#precio').val($('#precioTemporal').val());
	  $("#btn_pagar").attr('disabled', false);
    }
  }
  
  $('.procedimientos').select2({
    width: '100%',
    language: "es",
    placeholder: 'Procedimientos',
    minimumInputLength: 2,
    maximumSelectionLength: 20,
    ajax: {
      url: '<?php echo base_url("searchProcedimientos");?>',
      type: 'POST',
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    },
    "language": {
      "noResults": function(){
          return "No se han encontrado resultados";
      },
      inputTooShort: function () {
      return 'Ingrese 3 o más caracteres.';
      }
    }
  });  


  
  $("select#horarios").change(function() {
    var values = $(this).val();

    var count = $("#horarios :selected").length;
     
      var minutos = restarHoras(values[0].substr(0, 5), values[values.length-1].substr(8, 5));
      var horas = values[0].substr(0, 5) + ' - ' + values[values.length-1].substr(8, 5);
      $("#cantidad").text(minutos);
      $("#horasview").text(horas);
      $("#horaCita").val(horas);
    
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
</body>
</html>

