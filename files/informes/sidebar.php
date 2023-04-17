<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
?>
    
    <!-- Sidebar -->
    <div class="sidebar">

    <!-- Sidebar user panel (optional) -->
    <div class="row">
     
      <div class=col-6" >       
         
      </div>
     </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="<?php echo base_url('inicio');?>" class="nav-link <?php echo setActive("inicio");?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Inicio
              </p>
            </a>
            </li>
 
          <?php if($rol !=2) { ?>
          <li class="nav-item">
            <a href="<?php echo base_url('cita');?>" class="btn btn-primary w-100 mt-2 text-left <?php echo setActive("cita");?>">
            <img src="<?php echo base_url('img/logo_nueva_cita.png');?>" />
              <span class="ml-2">Solicitar Cita</span>
            </a>
          </li>
		      <?php } ?>
          
          <li class="nav-item">
          <a href="<?php echo base_url('miscitas');?>" class="btn btn-primary w-100 mt-2 text-left <?php echo setActive("misCitas");?>">
          <img src="<?php echo base_url('img/logo_mis_citas.png');?>" />
          <span class="ml-2">Mis Citas</span>                              
            </a>
          </li>
                 
          <li class="nav-item">
          <a href="<?php echo base_url('mihistorial');?>" class="btn btn-primary w-100 mt-2 text-left <?php echo setActive("miHistorial");?>">
          <img src="<?php echo base_url('img/logo_historial.png');?>" />             
          <span class="ml-2">Historial</span>                              
            </a>
          </li>
        
          <?php //if($rol != 4) { ?>
          <li class="nav-item">
          <a href="<?php echo base_url('examenesMedicos');?>" class="btn btn-primary w-100 mt-2 text-left <?php echo setActive("examenesMedicos");?>">
          <img src="<?php echo base_url('img/logo_solicitar_examenes.png');?>" />               
          <span class="ml-2">Solicitar Exámenes</span>       
            </a>
          </li>
          <li class="nav-item">
          <a href="<?php echo base_url('resultados');?>" class="btn btn-primary w-100 mt-2 text-left <?php echo setActive("resultados");?>">
          <img src="<?php echo base_url('img/logo_resultados.png');?>" />               
          <span class="ml-2">Resultados</span>
            </a>
          </li>
          <li class="nav-item">
          <a href="<?php echo base_url('farmacia');?>" class="btn btn-primary w-100 mt-2 text-left <?php echo setActive("farmacia");?>"">
          <img src="<?php echo base_url('img/logo_farmacia.png');?>" />               
          <span class="ml-2">Farmacia</span>  <br><span class="badge bg-warning text-dark">Próximamente</span>
            </a>
          </li>
          
           <?php 
			     // }
            
            if($rol == 1 || $rol == 4) {
          ?>
          <li class="nav-item mt-2">
            <a href="<?php echo base_url('gestionAntigeno');?>" class="nav-link <?php echo setActive("gestionAntigeno");?>">
              <i class="fas fa-tasks"></i>
              <p>
                Prueba Antígeno
              </p>
            </a>
          </li>


          <?php 
            }

           if(in_array('configuración_general', $permisosTotal) ==1) { 
          ?>
          <li class="nav-item has-treeview menu-open mt-2">
            <a href="#" class="nav-link">
              <i class="nav-icon fab fa-windows"></i>
              <p>
              Configuración
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php
                if(in_array('aperturar_horarios', $permisosTotal) ==1) {
              ?>
              <li class="nav-item mt-2">
                <a href="<?php echo base_url('admin/schedule');?>" class="nav-link <?php echo setActive("schedule");?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Aperturar Horarios</p>
                </a>
              </li>
              <?php
                }

                if(in_array('permiso_usuario', $permisosTotal) ==1) {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('admin/permisos');?>" class="nav-link <?php echo setActive("permisos");?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Permisos</p>
                </a>
              </li>
              <?php
                }

                if(in_array('exportar_excel', $permisosTotal) ==1) {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url('admin/reporteExcel');?>" class="nav-link <?php echo setActive("reporteExcel");?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Reportes</p>
                </a>
              </li>
            </ul>
          </li>
          <?php } } ?>
        </ul>
        


      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  