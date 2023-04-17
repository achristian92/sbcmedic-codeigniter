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
  <title>SBCMedic | Ocupacional - Inicio</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php $this->load->view("styles"); ?>
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?php echo  base_url('plugins/icheck-bootstrap/icheck-bootstrap.min.css'); ?>">
  <style>
    table td, th {
        padding: 20px;
    }

    table {
        width: 100%;
        border-spacing: 20px;
         color: #27ED72;
    }
  </style>
</head>

<body style="background: #6dd5ed;">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-auto mt-5">
        <table style="width: 100%; background-image: url(<?php echo base_url('img/ocupacional/3.png'); ?>); background-repeat: no-repeat;" border="0">
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
 
     
          <tr>
            <td colspan="2" align="center"><h3><strong>MEDICINA OCUPACIONAL&nbsp;</strong></h3></td>
          </tr>
          <tr>
            <td><a href="<?php echo base_url('ocupacional/registroEmpresa'); ?>" target="_blank"><strong>Registrar Empresas</strong></a></td>
            <td><a href="<?php echo base_url('ocupacional/registroAfiliado'); ?>" target="_blank"><strong>Registrar Afiliado</strong></a></td>
          </tr>
          <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
          <tr>
            <td><a href="<?php echo base_url('ocupacional/registroDatos'); ?>" target="_blank"><strong>Registrar Datos Afiliado</strong></a></td>
            <td><a href="<?php echo base_url('ocupacional/buscar'); ?>" target="_blank"><strong>Buscar Afiliado</strong></a></td>
          </tr>
          <tr>
            <td colspan="2"><a href="<?php echo base_url('ocupacional/registroAxiliarLabo'); ?>" target="_blank"><strong>Registrar Exa. Auxiliar/ Laboratorio</strong></a></td>
            
          </tr>
          
        
     
        </table>
    </div>
 
  </div>
</div>
</body>

</html>