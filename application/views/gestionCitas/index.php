<?php
defined('BASEPATH') or exit('No direct script access allowed');
echo $this->input->post("cmbEspecialista");
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo  base_url('img/favicon.ico'); ?>" />
  <title>SBCMedic | Gestión Citas</title>
  <?php $this->load->view("styles"); ?>
  <link rel="stylesheet" href="<?php echo base_url('plugins/icheck-bootstrap/icheck-bootstrap.min.css'); ?>">
  <style>
    fieldset {
      background-color: rgba(111, 66, 193, 0.3);
      border-radius: 4px;
    }

    legend {
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 4px;
      color: var(--purple);
      font-size: 17px;
      font-weight: bold;
      padding: 3px 5px 3px 7px;
      width: auto;
    }

    .modal-header {
      background-color: #28b17b;
      color: #fff;
      font-weight: bold;
    }
	
	.select2-selection--single {
	  height: 100% !important;
	}
	.select2-selection__rendered{
	  word-wrap: break-word !important;
	  text-overflow: inherit !important;
	  white-space: normal !important;
	}
  </style>
</head>

<body class="hold-transition sidebar-mini sidebar-collapse" style="font-size: 14px;">
  <!-- Site wrapper -->
  <div class="wrapper">
    <?php $this->load->view('aside'); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background: transparent;">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
                     <form role="form" id="frmSolicitudes" method="POST" action="<?php echo base_url('record-requests'); ?>">

            <fieldset class="col-12">
              <legend>Datos del Paciente</legend>
              <!-- form start -->
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="fecha">Busqueda por: Nombres / Apellidos / Nro Documento</label>
                    <select id="paciente" name="paciente" class="form-control searchClient" style="width: 100%;" required>
                      <?php if (isset($paciente["idUsuario"])) { ?>
                        <option value="<?php echo $paciente["idUsuario"]; ?>"><?php echo $paciente["firstname"] . ", " . $paciente["lastname"]; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-3 col-12">
                  <div class="form-group">
                    <label for="fecha">Nombres</label>
                    <input type="text" id="nombres" name="nombres" class="form-control" value="<?php echo isset($paciente["firstname"]) ? $paciente["firstname"] : ""; ?>">
                  </div>
                </div>
                <div class="col-xl-3 col-12">
                  <div class="form-group">
                    <label for="fecha">Apellidos</label>
                    <input type="text" id="apellidos" name="apellidos" class="form-control" value="<?php echo isset($paciente["lastname"]) ? $paciente["lastname"] : ""; ?>">
                  </div>
                </div>

                <div class="col-xl-2 col-12 form-group">
                  <label for="fecha">N° Documento</label>
                  <input type="text" id="nroDocumento" name="nroDocumento" class="form-control" value="<?php echo isset($paciente["document"]) ? $paciente["document"] : ""; ?>" maxlength="16">

                  <input type="hidden" id="nDocumentoOld" name="nDocumentoOld" value="<?php echo isset($paciente["document"]) ? $paciente["document"] : ""; ?>">
				  
                </div>
                <div class="col-xl-2 col-12 form-group">
                  <label for="fecha">Fecha Nacimiento</label>
                  <input type="date" id="fechaNacimiento" name="fechaNacimiento" class="form-control" value="<?php echo isset($paciente["birthdate"]) ? $paciente["birthdate"] : ""; ?>" >
                </div>
                <div class="col-xl-2 col-12 form-group">
                  <label for="fecha">Edad</label>
                  <input type="text" id="edad" name="edad" class="form-control" value="<?php echo isset($paciente["edad"]) ? $paciente["edad"] : ""; ?>" readonly>
                </div>
              </div>

              <div class="row">
                <div class="col-xl-5 col-12 form-group">
                  <label for="fecha">Dirección</label>
                  <input type="text" id="direccion" name="direccion" class="form-control" value="<?php echo isset($paciente["address"]) ? $paciente["address"] . " / " . $paciente["distrito"] : ""; ?>" readonly>
                </div>
                <div class="col-xl-3 col-12 form-group">
                  <label for="fecha">Email</label>
                  <input type="text" id="email" name="email" class="form-control" value="<?php echo isset($paciente["email"]) ? $paciente["email"] : ""; ?>" >
                </div>
                <div class="col-xl-2 col-12 form-group">
                  <label for="fecha">Teléfono</label>
                  <input type="text" id="telefono" name="telefono" class="form-control" value="<?php echo isset($paciente["phone"]) ? $paciente["phone"] : ""; ?>" >
                </div>
                <div class="col-xl-2 col-12 form-group">
                  <br>
                  <button type="button" class="btn btn-outline-primary btn-block" title="Grabar información del paciente" id="btnGuardar" <?php echo isset($paciente["idUsuario"]) ? "" : "disabled"; ?> ><i class="far fa-save"></i> Guardar</button>
                </div>

              </div>
              <!-- /.row -->
            </fieldset>
            <!-- form start -->

            <div class="row mt-1">
              <div class="col">
                <div class="form-group">
                  <select name="procedimiento[]" id="procedimiento" class="form-control procedimientos" multiple="multiple" style="height: 500px;"></select>
                </div>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-3">
                <!--      <div class="form-group">
                  <input type="number" id="cantidad_sesiones" name="cantidad_sesiones" class="form-control" min="0" max="10"  placeholder="Cantidad">
              </div> -->
                <a class="btn btn-dark btn-lg btn-block" href="#" role="button" onclick="detalle_fechas_disponibles('0')" >Ver Horarios Profesionales</a>
              </div>
              <div class="col">
                <button button type="submit" class="btn btn-success btn-lg btn-block">REGISTRAR SOLICITUDES</button>
              </div>
            </div>
          </form>

          <form role="form" id="frmSolicitudes" method="POST" action="<?php echo base_url('check-request-records'); ?>">
            <div class="row mt-2 bg-light">
              <div class="col-6 form-group">
                <label for="cmbEspecialista">Tipo de Servicio</label>
                <select id="cmbEspecialista" name="cmbEspecialista" class="form-control select2" style="width: 100%;">
                  <option value="">Todos</option>
                  <?php foreach ($especialidades as $especialidad) { ?>
                    <option value="<?= $especialidad->idSpecialty; ?>" <?php echo ($this->input->post("cmbEspecialista") == $especialidad->idSpecialty) ? "selected" : ""; ?>><?= $especialidad->name; ?></option>
                  <?php } ?>
                </select>
              </div>

              <div class="col-3 form-group">
                <label for="fecha">Fecha de creación</label>
                <input type="date" name="fechaBusqueda" class="form-control" value="<?php echo $this->input->post("fechaBusqueda") ? $this->input->post("fechaBusqueda") : ""; ?>" requerid>
              </div>
              <div class="col-3 form-group">
                <label for="fecha">&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Consultar</button>
                <input type="hidden" name="usuarioBusqueda" id="usuarioBusqueda" value="<?php echo isset($paciente["idUsuario"]) ? $paciente["idUsuario"] : ""; ?>">
              </div>
            </div>
          </form>

          <div class="row">
            <div class="col mt-2">
              <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">PROCEDIMIENTOS</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">EXÁMENES DE LABORATORIO <span class="badge badge-pill badge-success">( <?php echo $registrosLab->num_rows();?> )</span></a>
                </li>
              </ul>

              <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                  <div class="col-sm">
                    <table id="citas" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>CodigoInterno</th>
                          <th>FechaSolicitud</th>
                          <th>Servicio</th>
                          <th style="width: 250px;">Procedimiento</th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th>Pago</th>
                          <th></th>
                          <th>N°Orden</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        foreach ($registros as $clave => $registro) {
                        ?>
                          <tr style="background-color: <?php echo ($registro->norden) ? "#8BC34A" : ""; ?>;">
                            <td><?php echo $registro->codeAsignacion; ?></td>
                            <td align="center"><?php echo date("d/m/Y H:i", strtotime($registro->fechaCreacion)); ?></td>
                            <td><?php echo $registro->especialidad; ?></td>
                            <td><?php echo $registro->descripcion; ?></td>
                            <td>
                              <?php if ($registro->marca_cita) { ?>
                                <div class="custom-control custom-checkbox">
                                  <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="marca<?php echo $clave;?>" onclick="marcar_cita('<?php echo $registro->id; ?>', this.checked)" <?php echo $registro->marca_cita == 1 ? "checked" : ""; ?>>
                                    <label for="marca<?php echo $clave;?>">
                                    </label>
                                  </div>
                                </div>
                              <?php } ?>
                            </td>
                            <td><?php echo $registro->cantidad; ?></td>
                            <td>
                              <a href="#" class="btn btn-warning" onclick="detalle_citas_asignadas('<?php echo $registro->codigo_asignacion ? $registro->codigo_asignacion : 0; ?>')" title="Ver historial"><i class="far fa-clone"></i></a>
                            </td>
                            <td>
                              <?php if( $registro->pago == "SI"){ ?>
                                <span class="badge badge-danger"><?php echo $registro->pago; ?></span>
                              <?php } else { ?>
                                <?php echo $registro->pago; ?>
                              <?php } ?>
                            </td>
                            <!-- <td><button type="button" disabled class="btn btn-danger" onclick="cancelarCita('<?php echo $registro->id; ?>', '<?php echo $registro->idUsuario; ?>')"><i class="fas fa-trash-alt"></i></button></td> -->
                            <td>
                              <a href="<?php echo base_url('cita/search/CP?codeOne=') . $registro->codigo_asignacion . "&user=" . $registro->idUsuario . "&user=" . $registro->idUsuario . "&service=" . $registro->idEspecialidad; ?>" class="btn btn-outline-primary" target="_blank" title="Asignar Horario" title="Eliminar Registro"><i class="fa fa-hourglass-half" aria-hidden="true"></i></a>
                            </td>
                            <td><?php echo $registro->norden; ?></td>
                          </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>

                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                  <div class="col-sm">
                    <table id="citas" class="table table-bordered table-hover">
                      <thead>
                        <th><a href="<?php echo base_url('informe/index'); ?>" class="btn btn-secondary active" role="button" aria-pressed="true" target="_blank"><i class="fas fa-plus-circle"></i> Otros</a></th>
                        <th></th>
                        <th width="20%" colspan="4" style="text-align: right;"><button type="button" class="btn btn-success btn-lg active" data-toggle="modal" data-target="#newExamen">Realizar Examen</button></th>
                        </tr>
                        <tr>
                          <th>FechaSolicitud</th>
                          <th>Examen</th>
                          <th>CodLab</th>
                          <th>Registrado</th>
                          <th>Pagado</th>
                          <th>N°Orden</th>
						   <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        foreach ($registrosLab->result() as $row) {
                        ?>
                          <tr style="background-color: <?php echo ($row->norden) ? "#8BC34A" : ""; ?>;">
                            <td align="center"><?php echo date("d/m/Y H:i", strtotime($row->fechaCreacion)); ?></td>
                            <td><?php echo $row->text; ?></td>
                            <td><?php echo $row->codigo_lab; ?></td>
                            <td><?php echo $row->realizado; ?></td>
                            <td>
                              <?php if( $row->pago == "SI"){ ?>
                                <span class="badge badge-danger"><?php echo $row->pago; ?></span>
                              <?php } else { ?>
                                <?php echo $row->pago; ?>
                              <?php } ?>
                            </td>
                            <td><?php echo $row->norden; ?></td>
							                            <td>
                              <?php if(!empty($row->codigo_lab)) { ?>
                                <a href=" #" id="imprime" target="_blank" onclick="print_link('<?php echo $row->codigo_lab; ?>', '<?php echo $row->idUsuario; ?>')" title="Imprimir Examen"><i class="fas fa-print"></i> Imprimir</a>
                              <?php } ?>
                            </td>
                          </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div><!-- /.container-fluid -->

        <!-- Modal -->
        <div class="modal fade" id="newExamen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <form name="frmExamen" id="frmExamen">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header d-block">
                  <h4 class="modal-title text-center" id="newExamenLabel">REALIZAR EXAMEN SOLICITADO<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Fecha del Examen</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" value="<?php echo date("Y-m-d"); ?>" requerid>
                  </div>
                  <div class="form-group">
                    <label for="cmbExamenes">Examen => &nbsp;&nbsp;S./ <span style="font-size: 18px;" class="badge badge-warning" id="calculoCosto">0</span></label>
                    <select id="cmbExamenes" name="cmbExamenes[]" class="form-control searchExamen" style="width: 100%;" multiple required>
                      <?php
                      foreach ($laboratorios as $row) {
                      ?>
                        <option value="<?php echo $row->id; ?>"><?php echo $row->text; ?></option>
                      <?php } ?>
                    </select>
                  </div>
 
                  <div class="form-group">
                    <label for="descuento">Descuento S/.</label>
                    <input type="number" name="descuento" class="form-control" value="0" min="0">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" id="btnRegistrar" class="btn btn-success btn-block ml-1">REGISTRAR EXAMEN</button>
                  <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo isset($paciente["idUsuario"]) ? $paciente["idUsuario"] : ""; ?>">
                  <input type="hidden" name="codeExamenSolicitud" value="1">
				  <input type="hidden" name="precioTotal" id="precioTotal">
                </div>
              </div>
            </div>
          </form>
        </div>
        <!-- /.Modal -->
      </section>
      <!-- /.content -->
    </div>

    <footer class="main-footer bg_transparent">
      <div class="float-right d-none d-sm-block">
        <b>Versión</b> <?php echo $version["version"]; ?>
      </div>
      <strong>Copyright &copy; 2020 <a href="javascript:void(0)">SBCMedic</a>.</strong> Derechos Reservados.
    </footer>
  </div>
  <!-- ./wrapper -->
  <?php $this->load->view('scripts'); ?>
  <!-- Select2 -->

  <script src="<?php echo base_url('plugins/select2/js/select2.full.min.js'); ?>"></script>
  <script src="<?php echo base_url('plugins/inputmask/min/jquery.inputmask.bundle.min.js'); ?>"></script>
  <!-- DataTables -->
  <script src="<?php echo base_url('plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
  <script src="<?php echo base_url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>
  <script src="<?php echo base_url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>
  <script src="<?php echo base_url('plugins/datatables-responsive/js/dataTables.responsive.min.js'); ?>"></script>
  <script src="<?php echo base_url('plugins/datatables-responsive/js/responsive.bootstrap4.min.js'); ?>"></script>
  <!-- Bootstrap Switch -->
  <script src="<?php echo base_url('plugins/bootstrap-switch/js/bootstrap-switch.min.js'); ?>"></script>
  <!-- Select2 -->
  <script>
  
    function print_link(codigo, user) {
      var mywindow = window.open('<?php echo base_url('informe/imprimirExamen/') ?>' + codigo+'?user=' + user, 'Imprimir', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=1100,height=580,left = 390,top = 50');

    }
	
	$("#btnGuardar").click(function(evt){
      evt.preventDefault();
      var fechaNacimiento = $("#fechaNacimiento").val();
      var email = $("#email").val();
      var telefono = $("#telefono").val();
      var userPaciente = $("#usuarioBusqueda").val();
      var nombres = $("#nombres").val();
      var apellidos = $("#apellidos").val();
	  var nroDocumento = $("#nroDocumento").val();
      var nDocumentoOld = $("#nDocumentoOld").val();
     
      $.ajax({
          url: "<?php echo base_url('save-patient-information'); ?>",
          method: "POST",
			data: { fechaNacimiento: fechaNacimiento, email: email, telefono: telefono , userPaciente: userPaciente, nombres: nombres, apellidos: apellidos, nroDocumento: nroDocumento , nDocumentoOld: nDocumentoOld },
          success: function(dataresponse, statustext, response){
 
            alert(dataresponse.message);

            infoPaciente(userPaciente);
          },
          error: function(request, errorcode, errortext){
              $("#respuesta").html("<p>Ocurrió el siguiente error: <strong>" + errortext + "</strong></p>");
          }
      });
    });
	
    $("input[data-bootstrap-switch]").each(function() {
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

    let montoCosto = 0;

    $('#cmbExamenes').on('select2:select', function(e) {
      var data = e.params.data.text.split('=');

      montoCosto = montoCosto + data[1] * 1;
      $('#calculoCosto').text(montoCosto);
	  $('#precioTotal').val(montoCosto);

    });

    $('#cmbExamenes').on('select2:unselect', function(e) {
      var data = e.params.data.text.split('=');
      montoCosto = montoCosto - data[1] * 1;
      $('#calculoCosto').text(montoCosto);
	  $('#precioTotal').val(montoCosto);
    });

    function marcar_cita(id, value) {
      var marca = 0;
      if (value) marca = 1;
      else marca = 2;

      $.ajax({
        type: "POST",
        url: "<?php echo base_url('update-option'); ?>",
        data: {
          id: id,
          marca: marca
        },
        success: function(data) {
          //alert('Actualizado');
        }
      });
    }

    $('select').select2();
    var frm = $('#frmSolicitudes');
    $.validator.setDefaults({
      submitHandler: function(form) {

        $("#btnGuardar").attr('disabled', true);
        Swal.fire({
          title: '¿Esta seguro de registralo?',
          text: "No podrás revertir esto!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, de acuerdo!',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.value) {
            form.submit();
          }
        })

        $("#btnGuardar").attr('disabled', false);
      }
    });

    $('#frmSolicitudes').validate({
      paciente: {
        required: true
      },
      rules: {
        'procedimiento[]': {
          required: true
        }
      },
      messages: {
        paciente: {
          required: 'Seleccione un paciente'
        },
        'procedimiento[]': {
          required: 'Procedimiento es obligatorio'
        }
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });

    $('#citas').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "order": [1, 'desc'],
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "language": {
        "url": "<?php echo base_url("plugins/datatables-bs4/js/Spanish.json"); ?>"
      }
    });

    function detalle_gestion_detalle(codigo, tipocita, doctor, fecha) {
      var mywindow = window.open('<?php echo base_url('admin/view-appointment-management-detail/')  ?>' + codigo + '/' + tipocita + '/' + doctor + '/' + fecha, 'Imprimir', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=850,height=550,left = 390,top = 50');
    }

    $('.searchClient').select2({
      language: "es",
      placeholder: 'Seleccionar al paciente',
      minimumInputLength: 3,
      maximumSelectionLength: 10,
      ajax: {
        url: '<?php echo base_url("searchClient"); ?>',
        type: 'POST',
        dataType: 'json',
        delay: 250,
        processResults: function(data) {
          return {
            results: data
          };
        },
        cache: true
      },
      "language": {
        "noResults": function() {
          return "No se han encontrado resultados";
        },
        inputTooShort: function() {
          return 'Ingrese 3 o más caracteres.';
        },
        searching: function() {
          return "Buscando...";
        }
      }
    });

     
    $('#paciente').on('select2:select', function(e) {

      infoPaciente($("#paciente").val());
    });

    function infoPaciente(usuario) {
      
      $.ajax({
        type: 'POST',
        url: '<?php echo base_url('consult-patient'); ?>',
        data: {
          cliente: usuario
        },
        success: function(data) {
          if (data.status) {
            $('#nombres').val(data.paciente.firstname);
            $('#apellidos').val(data.paciente.lastname);
            $('#nroDocumento').val(data.paciente.document);
            $('#fechaNacimiento').val(data.paciente.birthdate);
            $('#edad').val(data.paciente.edad);
            $('#direccion').val(data.paciente.address + ' / ' + data.paciente.distrito);
            $('#email').val(data.paciente.email);
            $('#telefono').val(data.paciente.phone);
            $('#idUsuario').val(data.paciente.idUsuario);
            $('#usuarioBusqueda').val(data.paciente.idUsuario);
			$('#nDocumentoOld').val(data.paciente.document);
 
			$("#btnGuardar").prop('disabled', false);

          } else {
            Swal.fire({
              icon: 'error',
              timer: 5000,
              title: 'Error de validación',
              text: data.message
            })
          }
        },
        error: function(data) {
          Swal.fire({
            icon: 'error',
            timer: 5000,
            title: 'Error interno',
            text: 'Ha ocurrido un error interno!'
          })
        }
      });
    }
	
    $('.procedimientos').select2({
      width: '100%',
      language: "es",
      placeholder: 'Procedimientos',
      minimumInputLength: 3,
      maximumSelectionLength: 20,
      ajax: {
        url: '<?php echo base_url("searchProcedimientos"); ?>',
        type: 'POST',
        dataType: 'json',
        delay: 250,
        processResults: function(data) {
          return {
            results: data
          };
        },
        cache: true
      },
      "language": {
        "noResults": function() {
          return "No se han encontrado resultados";
        },
        inputTooShort: function() {
          return 'Ingrese 3 o más caracteres.';
        }
      }
    });

    function detalle_citas_asignadas(code) {
      var mywindow = window.open('<?php echo base_url('detail-appointments-assigned/') ?>' + code, 'HistorialAsignadas', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=1100,height=450,left = 390,top = 50');
    }

    function cancelarCita(codigo, usuario) {
      Swal.fire({
        title: '¿Estás seguro de Eliminar la solicitud?',
        text: 'Una vez confirmado, no se podrá revertir.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Seguro!',
        cancelButtonText: 'Cancelar',
      }).then((result) => {
        if (result.value) {

          $.ajax({
            type: 'post',
            url: "<?php echo base_url("cancel-request"); ?>",
            data: {
              codigo: codigo,
              usuario: usuario
            },
            success: function(data) {
              if (data.status) {

                Swal.fire({
                  icon: 'success',
                  timer: 7000,
                  title: 'Respuesta exitosa',
                  text: data.message,
                  onClose: () => {
                    location.reload();
                  }
                })
              } else {
                Swal.fire({
                  icon: 'error',
                  timer: 7000,
                  title: 'Error de validación',
                  text: data.message
                })
              }
            },
            error: function(data) {
              Swal.fire({
                icon: 'error',
                timer: 7000,
                title: 'Error interno',
                text: 'Ha ocurrido un error interno!'
              })
            },
          });
        }
      });
    }

    var frm = $('#frmExamen');
    $.validator.setDefaults({
      submitHandler: function() {

        Swal.fire({
          title: '¿ESTÁS SEGURO DE REGISTRAR EL EXAMEN?',
          text: 'Una vez confirmado, no se podrá revertir.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Seguro!',
          cancelButtonText: 'Cancelar',
        }).then((result) => {

          if (result.value) {


            $.ajax({
              type: 'POST',
              url: "<?php echo base_url("gNuevoExamen"); ?>",
              data: $('#frmExamen').serialize(),
              beforeSend: function() {
                $("#btnRegistrar").html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
                $("#btnRegistrar").removeClass("btn btn-primary btn-block ml-1");
                $("#btnRegistrar").addClass("btn btn-primary btn-block ml-1");
                $("#btnRegistrar").prop('disabled', true);
              },
              success: function(data) {
                if (data.status) {

                  Swal.fire({
                    icon: 'success',
                    timer: 5000,
                    title: 'Respuesta exitosa',
                    text: data.message,
                    onClose: () => {
                      window.location.reload();
                    }
                  })
                } else {
                  Swal.fire({
                    icon: 'error',
                    timer: 5000,
                    title: 'Error de validación',
                    text: data.message
                  })
                }
              },
              error: function(data) {
                Swal.fire({
                  icon: 'error',
                  timer: 5000,
                  title: 'Error interno',
                  text: 'Ha ocurrido un error interno. NO SE PUEDE GUARDAR!',
                  onClose: () => {
                    window.location.replace("<?php echo base_url('informe/index?cmbUsuario='); ?>" + $('#idUsuario').val());
                  }
                })
              },
            });

          }
        });

      }
    });

    $('#frmExamen').validate({
      rules: {
        "cmbExamenes[]": {
          required: true
        },
        cmbEspecialidad: {
          required: true
        }
      },
      messages: {
        "cmbExamenes[]": {
          required: "Examen(s) es requerido."
        },
        cmbEspecialidad: {
          required: "Seleccione una especialidad"
        }
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });

    $('#newExamen').on('hide.bs.modal', function(event) {

      $("#cmbExamenes").val('').trigger('change');

      $('#calculoCosto').text('0');
		$('#precioTotal').val('0');
      $(this).find('form')[0].reset();
      montoCosto = 0;

    });

    function detalle_fechas_disponibles(doctor) {
      var mywindow = window.open('<?php echo base_url('available-dates/') ?>' + doctor, 'Imprimir', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=770,height=600,left = 390,top = 50');
    }
  </script>
</body>
</html>