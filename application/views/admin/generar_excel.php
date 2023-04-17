<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html> 
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SBCMedic | Reportes</title>
  <?php $this->load->view("styles"); ?>
  <style>
    fieldset.citas {
      background-color: rgba(111, 66, 193, 0.3);
      border-radius: 4px;
    }

    legend.citas {
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 4px;
      color: var(--purple);
      font-size: 17px;
      font-weight: bold;
      padding: 3px 5px 3px 7px;
      width: auto;
    }
	
	fieldset.lab {
      background-color: #3dd397;
      border-radius: 4px;
    }

    legend.lab {
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 4px;
      color: #28b17b;
      font-size: 17px;
      font-weight: bold;
      padding: 3px 5px 3px 7px;
      width: auto;
    }
	
	
	fieldset.proce {
      background-color: #99ffff;
      border-radius: 4px;
    }

    legend.proce {
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 4px;
      color: #17ffff;
      font-size: 17px;
      font-weight: bold;
      padding: 3px 5px 3px 7px;
      width: auto;
    }

	fieldset.soli {
      background-color: #2eb8e0;
      border-radius: 4px;
    }

    legend.soli {
      background-color: #fff;
      border: 1px solid #2eb8e0;
      border-radius: 4px;
      color: #1c97bb;
      font-size: 17px;
      font-weight: bold;
      padding: 3px 5px 3px 7px;
      width: auto;
    }	
	
		fieldset.covid {
      background-color: #CDDC39;
      border-radius: 4px;
    }

    legend.covid {
      background-color: #fff;
      border: 1px solid #AFB42B;
      border-radius: 4px;
      color: #AFB42B;
      font-size: 17px;
      font-weight: bold;
      padding: 3px 5px 3px 7px;
      width: auto;
    }	
	
  </style>
</head>
<body class="hold-transition sidebar-mini pace-primary" style="background-image: url(<?php echo base_url('img/fondo_body.png');?>); height: 100%;  background-position: right;  background-repeat: no-repeat;  ">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light bg_transparent" style="height: 100px;">
    <!-- Left navbar links -->
    <ul class="navbar-nav h-100 align-items-center">
      <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <span style="vertical-align:middle;  "><span style="margin-left: 10px;font-size: 2.4rem;font-weight:bold;color: #004663;vertical-align:middle;  "> Reportes<span></span>
      </li>
    
    </ul>
  </nav>
  <!-- /.navbar -->
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
      <form role="form" id="formAperturarHorario" method="post" action="<?php echo base_url('admin/reporteExcelCitas');?>">
      <fieldset class="col-12 citas">
        <legend class="citas">Reporte de citas</legend>
        <!-- form start -->
        
        <div class="row">
          <div class="col-xl-3 col-12 form-group">
            <label for="fecha">Profesional</label>
            <select id="medico" name="medico" class="form-control select2" style="width: 100%;">
              <option value="">Todos</option>                    
              <?php foreach ($medicos as $medico) { ?>
                  <option value="<?=$medico->idDoctor;?>"><?=$medico->nombreMedico;?></option>                    
                <?php } ?>
            </select>
          </div>
          <div class="col-xl-3 col-12 form-group">
            <label for="fecha">Fecha Cita Inicio</label>
            <input type="date" id="fechaInicio" name="fechaInicio" class="form-control" value="<?php echo date('Y-m-d');?>" required>
          </div>
          <div class="col-xl-3 col-12 form-group">
            <label for="fecha">Fecha Cita Fin</label>
            <input type="date" id="fechaFin" name="fechaFin" class="form-control" value="<?php echo date('Y-m-d');?>" required>
          </div>
          <div class="col-xl-3 col-12 form-group">
            <label for="fecha">&nbsp;</label>
            <button type="submit" id="btnGuardar" class="form-control btn btn-success" title="Exportar a excel"><i class="fas fa-file-excel"></i> Generar Excel</button>
          </div>
        </div>
        <!-- /.row -->
        </fieldset>
        </form>

      <form role="form" id="formAperturarHorario" method="post" action="<?php echo base_url('admin/reporteExcelPagos');?>">
      <fieldset class="col-12 citas">
        <legend class="citas">Reporte de Pagos Citas</legend>
        <!-- form start -->
        
        <div class="row">
 
          <div class="col-xl-3 col-12 form-group">
            <label for="fecha">Fecha Pago Inicio</label>
            <input type="date" id="fechaInicio" name="fechaInicio" class="form-control" value="<?php echo date('Y-m-d');?>" required>
          </div>
          <div class="col-xl-3 col-12 form-group">
            <label for="fecha">Fecha Pago Fin</label>
            <input type="date" id="fechaFin" name="fechaFin" class="form-control" value="<?php echo date('Y-m-d');?>" required>
          </div>
          <div class="col-xl-3 col-12 form-group">
            <label for="fecha">&nbsp;</label>
            <button type="submit" id="btnGuardar" class="form-control btn btn-success" title="Exportar a excel"  disabled><i class="fas fa-file-excel"></i> Generar Excel</button>
          </div>
        </div>
        <!-- /.row -->
        </fieldset>
        </form>
 
      <form role="form" id="formAperturarHorario" method="post" action="<?php echo base_url('admin/reporteExcelLab');?>">
      <fieldset class="col-12 lab">
        <legend class="lab">Reporte de Laboratorio</legend>
        
        <div class="row">
    
          <div class="col-xl-3 col-12 form-group">
            <label for="fecha">Fecha Examen Inicio</label>
            <input type="date" id="fechaInicio" name="fechaInicio" class="form-control" value="<?php echo date('Y-m-d');?>" required>
          </div>
          <div class="col-xl-3 col-12 form-group">
            <label for="fecha">Fecha Examen Final</label>
            <input type="date" id="fechaFin" name="fechaFin" class="form-control" value="<?php echo date('Y-m-d');?>" required>
          </div>
          <div class="col-xl-3 col-12 form-group">
            <label for="temp">&nbsp;</label>
			<input type="checkbox" class="form-check-input" id="consolidado" name="consolidado" value="1">
			<label class="form-check-label" for="consolidado">&nbsp;DETALLADO</label>
            <button type="submit" id="btnGuardar" class="form-control btn btn-success"   title="Exportar a excel"><i class="fas fa-file-excel"></i> Generar Excel</button>
          </div>
        
        </div>
        
        
        </fieldset>
        </form> 
		
 <!--
      <form role="form" id="formAperturarHorario" method="post" action="<?php echo base_url('admin/downloadProcedimientos');?>">
      <fieldset class="col-12 proce">
        <legend class="proce">Reporte de Procedimientos</legend>
        
        <div class="row">
    
          <div class="col-xl-3 col-12 form-group">
            <label for="fecha">Fecha Creación Inicio</label>
            <input type="date" id="fechaInicio" name="fechaInicio" class="form-control" value="<?php echo date('Y-m-d');?>" required>
          </div>
          <div class="col-xl-3 col-12 form-group">
            <label for="fecha">Fecha Creación Final</label>
            <input type="date" id="fechaFin" name="fechaFin" class="form-control" value="<?php echo date('Y-m-d');?>" required>
          </div>
          <div class="col-xl-3 col-12 form-group">
             <label for="temproc">&nbsp;</label>
			<input type="checkbox" class="form-check-input" id="consolidadoproc" name="consolidadoproc" value="1">
			<label class="form-check-label" for="consolidadoproc">&nbsp;DETALLADO</label>
            <button type="submit" id="btnGuardar" class="form-control btn btn-success" disabled  title="Exportar a excel"><i class="fas fa-file-excel"></i> Generar Excel</button>
          </div>
        </div>
        
        
        </fieldset>
        </form> 		-->
		
		
		
      <form role="form" id="formAperturarHorario" method="post" action="<?php echo base_url('admin/reporteExcelSolicita');?>">
      <fieldset class="col-12 soli">
        <legend class="soli">Exámenes solicitados por los Médicos</legend>
        
        <div class="row">
    
          <div class="col-xl-3 col-12 form-group">
            <label for="fecha">Fecha Creación Inicio</label>
            <input type="date" id="fechaInicio" name="fechaInicio" class="form-control" value="<?php echo date('Y-m-d');?>" required>
          </div>
          <div class="col-xl-3 col-12 form-group">
            <label for="fecha">Fecha Creación Final</label>
            <input type="date" id="fechaFin" name="fechaFin" class="form-control" value="<?php echo date('Y-m-d');?>" required>
          </div>
          <div class="col-xl-3 col-12 form-group">
             <label for="temproc">&nbsp;</label>
            <button type="submit" id="btnGuardar" class="form-control btn btn-success"  title="Exportar a excel"><i class="fas fa-file-excel"></i> Generar Excel</button>
          </div>
        </div>
        
        
        </fieldset>
        </form>
		
		 
		
      <form role="form" id="formAperturarHorario" method="post" action="<?php echo base_url('admin/reporteExcelOrden');?>">
      <fieldset class="col-12 soli">
        <legend class="soli">Reporte de ordenes LAB/PRO</legend>
        
        <div class="row">
    
          <div class="col-xl-3 col-12 form-group">
            <label for="fecha">Fecha Creación Inicio</label>
            <input type="date" id="fechaInicio" name="fechaInicio" class="form-control" value="<?php echo date('Y-m-d');?>" required>
          </div>
          <div class="col-xl-3 col-12 form-group">
            <label for="fecha">Fecha Creación Final</label>
            <input type="date" id="fechaFin" name="fechaFin" class="form-control" value="<?php echo date('Y-m-d');?>" required>
          </div>
          <div class="col-xl-3 col-12 form-group">
             <label for="temproc">&nbsp;</label>
            <button type="submit" id="btnGuardar" class="form-control btn btn-success"  title="Exportar a excel" disabled><i class="fas fa-file-excel"></i> Generar Excel</button>
          </div>
        </div>
        
        
        </fieldset>
        </form>  
		  <form role="form" id="formAperturarHorario" method="post" action="<?php echo base_url('admin/reporteExcelCovid');?>">
      <fieldset class="col-12 covid">
        <legend class="covid">REPORTE DE COVID</legend>
        
        <div class="row">
    
          <div class="col-xl-3 col-12 form-group">
            <label for="fecha">Fecha Toma Inicio</label>
            <input type="date" id="fechaInicio" name="fechaInicio" class="form-control" value="<?php echo date('Y-m-d');?>" required>
          </div>
          <div class="col-xl-3 col-12 form-group">
            <label for="fecha">Fecha Toma Final</label>
            <input type="date" id="fechaFin" name="fechaFin" class="form-control" value="<?php echo date('Y-m-d');?>" required>
          </div>
          <div class="col-xl-3 col-12 form-group">
             <label for="temproc">&nbsp;</label>
            <button type="submit" id="btnGuardar" class="form-control btn btn-success"  title="Exportar a excel"><i class="fas fa-file-excel"></i> Generar Excel</button>
          </div>
        </div>
        
        
        </fieldset>
        </form> 
		
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
   
  <footer class="main-footer bg_transparent">
    <div class="float-right d-none d-sm-block">
      <b>Versión</b> <?php echo $version["version"];?>
    </div>
    <strong>Copyright &copy; 2020 <a href="javascript:void(0)">SBCMedic</a>.</strong> Derechos Reservados.
  </footer>
</div>
<!-- ./wrapper -->
<?php $this->load->view('scripts'); ?>
<!-- Select2 -->
<script src="<?php echo base_url('plugins/select2/js/select2.full.min.js');?>"></script>

<script>
   $('.select2').select2();
</script>

</body>
</html>

