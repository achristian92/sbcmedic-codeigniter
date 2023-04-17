<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4" style=" background-image: repeating-linear-gradient(to bottom, #22b473, #5897bc);">
        <!-- Brand Logo -->
        <a href="<?php echo base_url('inicio');?>" class="brand-link active" >
      <img src="<?php echo base_url('img/logo_aside.png');?>"
           alt="Logo"
           class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-normal" style="color:#fff"><strong>SBCMedic</strong></span>
    </a>
        <!-- Brand Logo -->
        <a href="<?php echo base_url('miperfil');?>" class="brand-link"  title="Mi Perfil">
        <img src="<?php echo base_url('img/miPerfil.png');?>"
           alt="Logo"
           class="brand-image img-circle elevation-3"
           style="opacity: .8">
        <span class="brand-text font-weight-light"><?php echo explode(" ", $firstname)[0]. " - ".$document;?></span>
    </a>
    <!-- Brand Logo -->

    <?php $this->load->view('sidebar'); ?>
  </aside>

 