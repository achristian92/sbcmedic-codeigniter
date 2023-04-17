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
  <title>SBCMedic | VER DETALLE CITAS ASIGNADAS</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php $this->load->view("styles"); ?>
 
</head>

<body style="background: #4AC29A;  /* fallback for old browsers */
background: -webkit-linear-gradient(to bottom, #BDFFF3, #4AC29A);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to bottom, #BDFFF3, #4AC29A); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
">
      <div class="container-fluid">
        <div class="row bg-black">
          <div class="col-sm text-center"  style=" justify-content: center;align-items: center;">
            <h2>DETALLE CITAS ASIGNADAS</h2>
          </div>
        </div>
        
        <div class="row mt-2">
          <div class="col-sm">
            <?php if($registros->num_rows() >0 ){ ?>
              <table id="misCitas" class="table table-bordered table-hover">
                  <thead class="thead-dark">
                  <tr>
                    <th style="text-align: center;">N°Cita</th>
                    <th style="text-align: center;">FechaCita</th>
                    <th style="text-align: center;">HoraCita</th>
                    <th style="text-align: center;">Estado</th>
					<th style="text-align: center;">Principal</th>
                    <th style="text-align: center;">Procedimiento</th>
                    <th style="text-align: center;">Especialidad</th>
                    <th style="text-align: center;">Profesional</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                    foreach ($registros->result() as $registro) {
                  ?>
                      <tr>
                        <td><?php echo str_pad($registro->idCita, 6, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo date("d/m/Y",strtotime($registro->fechaCita));?></td>
                        <td><?php echo $registro->horaCita;?></td>
                        <td><strong><?php echo $registro->estadoCita;?></strong></td>
						<td><?php echo $registro->proPrincipal;?></td>
                        <td><?php echo $registro->descripcion;?></td>
                        <td><?php echo $registro->especialidad;?></td>
                        <td><?php echo $registro->profesional;?></td>
                 
                      </tr>
                  <?php } ?>
                  </tbody>
              </table>
              <?php } ?>
          </div>
        </div>
      

      </div>

</body>

</html>