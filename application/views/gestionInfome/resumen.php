<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>

<head>
  <base href="consulta">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo  base_url('img/favicon.ico'); ?>" />
  <title>SBCMedic | RESUMEN EXÁMENES</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php $this->load->view("styles"); ?>
  <!-- waitme-->
  <link rel="stylesheet" href="<?php echo base_url('plugins/waitme/waitMe.min.css'); ?>">
</head>

<body style="background: #a8ff78;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #78ffd6, #a8ff78);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #78ffd6, #a8ff78); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

">
  <div class="container">
    <div class="row bg-black">
      <div class="col-sm text-center" style=" justify-content: center;align-items: center;">
        <h2>RESUMEN DE EXÁMENES<p><a href="javascript:void(0)" id="imprime"><i class="fas fa-print"></i> Imprimir</a></p>
        </h2>
      </div>
    </div>


    <div class="row mt-2">
      <div class="col-sm">
        <form action="<?php base_url('informe/index'); ?>" method="POST">
          <table id="gestion" class="table table-striped table-hover table-bordered" style="width:100%;background-color:#B2EBF2;">
            <thead>
              <tr>
                <td style="width: 13%;"><strong>Status</strong></td>
                <td style="width: 15%;"><select name="status" class="form-control" style="width: 100%;">
                    <option value="">Todos</option>
                    <option value="0">En Proceso</option>
                    <option value="1">Procesado</option>
                    <option value="2">Válidado</option>
                  </select></td>
                <td style="width: 15%;" align="right"><strong>Paciente</strong></td>
                <td colspan="6"><select id="cmbUsuario" name="cmbUsuario" class="searchClient form-control select2" style="width: 100%;">

                  </select></td>
              </tr>
              <tr>
                <td><strong>Fecha Inicio</strong></td>
                <td>
                  <input type="date" name="fechaIni" class="form-control" value="<?php echo ($this->input->post("fechaIni")) ? $this->input->post("fechaIni") :""; ?>" requerid>
                </td>
                <td style="width: 15%;" align="right"><strong>Fecha Fin</strong></td>
                <td colspan="5">
                  <input type="date" name="fechaFin" class="form-control" value="<?php echo ($this->input->post("fechaFin")) ? $this->input->post("fechaFin") : ""; ?>" requerid>
                </td>
                <td style="width: 15%;"><button type="submit" class="btn btn-primary btn-block">Consultar</button></td>
              </tr>
          </table>
        </form>
        <div id="myPrintArea">
          <table id="gestion" class="table table-striped table-hover table-bordered" style="width:100%;background-color:#B2EBF2;">
            <tr style="background-color: black; color: blanchedalmond;">
              <th></th>
              <th>PACIENTE</th>
              <th>CÓDIGO INTERNO</th>
              <th>FECHA EXAMEN</th>
              <th>STATUS</th>
              <th>TIPO</th>
              <th>EXAMEN</th>
              <th style="text-align: center;">TOTAL(S./)</th>
            </tr>
            </thead>
            <tbody>
              <?php 
              $codigoI = null;
              $filas = null;
              foreach ($resultados as $clave => $resultado) {
                $clave++;
              ?>
                <tr>
                  <td><?php echo $clave; ?></td>
                  <td><?php echo $resultado->usuario; ?></td>
                  <td><?php echo $resultado->codigo_interno; ?></td>
                  <td><?php echo date("d/m/Y", strtotime($resultado->fechaExamen)); ?></td>
                  <td><span class="badge badge-<?php if ($resultado->estado == 0) echo "warning";
                                                else if ($resultado->estado == 1) echo "success";
                                                else echo "primary"; ?>">
                      <?php if ($resultado->estado == 0) echo "En Proceso"; else if ($resultado->estado == 1) echo "Procesado"; else echo "Envíado"; ?></span>
                  </td>
                  <td style="color:blue"><strong><?php echo $resultado->tipo; ?></strong></td>
                  <td style="color:blue"><strong><?php echo $resultado->examen . "(S./ " . $resultado->precio . ")"; ?></strong></td>
                  <?php
                  $filas++;
                  if ($filas == 1) {
                  ?>
                    <td align="center" rowspan="<?php echo explode("-", $resultado->cantidadFilas)[0] * 1; ?>" style="background-color: #1E6091; color: #D9ED92; vertical-align:middle;">S./ <strong><?php echo explode("-", $resultado->cantidadFilas)[1]  + $resultado->costo_transporte - explode("-", $resultado->cantidadFilas)[2] ; ?></strong><br>Pagado <input type="checkbox" <?php echo ($resultado->status_pago == 1) ? "disabled" : ""; ?> <?php if($idUsuario == 8687 || $idUsuario == 6436 || $idUsuario == 3914 || $idUsuario == 48) echo ""; else echo "disabled"; ?>  class="switcher" name="ckbPago" data-bootstrap-switch data-off-color="danger" data-on-color="success" data-on-text="SI&nbsp;&nbsp;&nbsp;" data-off-text="NO" data="<?php echo $resultado->codigo_interno; ?>" <?php echo ($resultado->status_pago > 0) ? "checked='checked'" : ""; ?> "/>                                                <br>                        <a href=" #" id="imprime" target="_blank" onclick="print_link('<?php echo $resultado->codigo_interno; ?>', '<?php echo $resultado->idUsuario; ?>')" title="Imprimir Examen"><i class="fas fa-print"></i> Imprimir</a>                    </td>
                  <?php
                  }

                  if ($resultado->cantidadFilas == $filas)  $filas = 0;
                  ?>
                </tr>
              <?php
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
  <!-- Modal -->
  <div class="modal fade" id="newExamen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form name="frmExamen" id="frmExamen">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header d-block">
            <h4 class="modal-title text-center" id="newExamenLabel">SOLICITAR NUEVO EXAMÉN<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <?php if ($rol == 1 || $rol == 4 || $rol == 5) { ?>
              <div class="form-group">
                <label for="exampleInputEmail1">Código Interno</label>
                <input type="text" name="codigoInterno" class="form-control">
              </div>
            <?php } ?>
            <div class="form-group">
              <label for="exampleInputEmail1">Fecha del Examen</label>
              <input type="date" name="fecha" class="form-control" value="<?php echo date("Y-m-d"); ?>" requerid>
            </div>

            <div class="form-group">
              <label for="cmbExamenes">Examen</label>
              <select id="cmbExamenes" name="cmbExamenes[]" class="form-control searchExamen" style="width: 100%;" multiple>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" id="btnRegistrar" class="btn btn-primary btn-block ml-1">REGISTRAR EXAMEN</button>
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- /.Modal -->
  <?php $this->load->view("scripts"); ?>
  <!-- Select2 -->
  <script src="<?php echo base_url('plugins/select2/js/select2.full.min.js'); ?>"></script>
  <!-- pace-progress -->
  <script src="<?php echo base_url('js/jquery.PrintArea.js'); ?>"></script>

  <!-- Bootstrap Switch -->
  <script src="<?php echo base_url('plugins/bootstrap-switch/js/bootstrap-switch.min.js'); ?>"></script>
  <!-- Select2 -->
  <script src="<?php echo base_url('plugins/select2/js/select2.full.min.js'); ?>"></script>
  <script src="<?php echo base_url('plugins/inputmask/min/jquery.inputmask.bundle.min.js'); ?>"></script>

  <script>
    $("input[data-bootstrap-switch]").each(function() {
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

    $('.switcher').on('switchChange.bootstrapSwitch', function(e, data) {
      $.ajax({
        type: 'post',
        url: "<?php echo base_url("informe/statusPago_examen"); ?>",
        data: {
          codigoInterno: $(this).attr('data'),
          status: data
        },
        success: function(respuesta) {
          if (!respuesta) alert('Error! No se guardo.');
        },
        error: function(respuesta) {
          if (respuesta) alert('Error! No se guardo.' + respuesta);
        }
      });
    });

    $("#imprime").click(function() {
      $("div#myPrintArea").printArea();
    })

    $('.select2').select2();


    $('.searchClient').select2({
      language: "es",
      placeholder: 'Todos',
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
        }
      }
    });

    function print_link(codigo, user) {
      var mywindow = window.open('<?php echo base_url('informe/imprimirExamen/') ?>' + codigo+'?user=' + user, 'Imprimir', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=1100,height=580,left = 390,top = 50');

    }
  </script>
</body>

</html>