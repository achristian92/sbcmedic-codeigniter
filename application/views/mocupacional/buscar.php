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
    <title>SBCMedic | Buscar Afiliado</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $this->load->view("styles"); ?>
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="<?php echo  base_url('plugins/icheck-bootstrap/icheck-bootstrap.min.css'); ?>">

</head>

<body style="background: #a8ff78;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #78ffd6, #a8ff78);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #78ffd6, #a8ff78); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
">
    <br>
    <div class="container">
        <form method="POST" name="frmEmpresa" id="frmEmpresa">
            <div class="row bg-black">
                <div class="col-sm text-center" style=" justify-content: center;align-items: center;">
                    <h2>BUSQUEDA DE AFILIADO</h2>
                </div>
            </div>


            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label for="cmbTipoE">NOMBRES</label>
                        <input type="text" class="form-control" name="nombres" value="<?php echo $this->input->post("nombres"); ?>">
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="cmbPuesto">APELLIDOS</label>
                        <input type="text" class="form-control" name="apellidos" value="<?php echo $this->input->post("apellidos"); ?>">
                    </div>
                </div>

                <div class="col-sm">
                    <div class="form-group">
                        <label for="cmbPuesto">DOCUMENTO</label>
                        <input type="text" class="form-control" name="documento" value="<?php echo $this->input->post("documento"); ?>">
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label for="cmbDepartamento">EMAIL</label>
                        <input type="text" class="form-control" name="email" value="<?php echo $this->input->post("email"); ?>">
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="cmbProvincia">TELÉFONO</label>
                        <input type="text" class="form-control" name="telefono" value="<?php echo $this->input->post("telefono"); ?>">
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="hora">EMPRESA</label>
                        <select class="form-control" name="cmbEmpresa" id="cmbEmpresa">
                            <option value="">SELECCIONAR</option>
                            <?php
                            foreach ($empresas as $empresa) {
                            ?>
                                <option value="<?php echo $empresa->id; ?>"><?php echo $empresa->razonSocial; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-12">
                    <button type="submit" name="btnRegistrarDatos" id="btnRegistrarDatos" class="btn btn-success  btn-lg active btn-block"><i class="fab fa-searchengin"></i> BUSCAR</button>
                </div>
            </div>

        </form>

        <div class="row mt-2">
            <div class="col">
                <table class="table table-hover">
                    <thead>
                        <tr class="table-active">
                            <th>#</th>
                            <th scope="col">Nro Historia</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Nombres</th>
                            <th scope="col">Documento</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Empresa</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($registroBusquedas as $key => $registroBusqueda) {
                            $key++;
                        ?>
                            <tr>
                                <td><?php echo $key; ?></td>
                                <td><?php echo str_pad($registroBusqueda->idHistorial, 6, '0', STR_PAD_LEFT);?></td>
                                <td><a href='<?php echo base_url("ocupacional/editarAfiliado/$registroBusqueda->id"); ?>' target="_blank"><?php echo $registroBusqueda->apellido; ?></a></td>
                                <td><?php echo $registroBusqueda->nombre; ?></td>
                                <td><?php echo $registroBusqueda->documento; ?></td>
                                <td><?php echo $registroBusqueda->telefono; ?></td>
                                <td><?php echo $registroBusqueda->razonSocial; ?></td>
                                <td>
                                    <a class="btn btn-primary" href="<?php echo base_url("ocupacional/anexo2/$registroBusqueda->idAfiliado/$registroBusqueda->idHistorial"); ?>" role="button" target="_blank" style="background-color: #03045e;"><i class="far fa-file-pdf"></i> Anexo2</a>
                                    <a class="btn btn-primary" href="<?php echo base_url("ocupacional/cAptitud/$registroBusqueda->idAfiliado/$registroBusqueda->idHistorial"); ?>" role="button" target="_blank" style="background-color: #0077b6;"><i class="far fa-file-pdf"></i> Aptitud</a>
                                    <a class="btn btn-primary" href="<?php echo base_url("ocupacional/informeMedico/$registroBusqueda->idAfiliado/$registroBusqueda->idHistorial"); ?>" role="button" target="_blank" style="background-color: #00b4d8;"><i class="far fa-file-pdf"></i> Informe</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
</body>

</html>