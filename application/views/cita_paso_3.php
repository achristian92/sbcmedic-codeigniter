<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html> 
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SBCMedic | Aperturar Horarios</title>
  <?php $this->load->view("styles"); ?>

    <!-- fullCalendar -->
    <link rel="stylesheet" href="plugins/fullcalendar/main.min.css">
  <link rel="stylesheet" href="plugins/fullcalendar-daygrid/main.min.css">
  <link rel="stylesheet" href="plugins/fullcalendar-timegrid/main.min.css">
  <link rel="stylesheet" href="plugins/fullcalendar-bootstrap/main.min.css">

  <style>
  .myCalendar {
    cursor: pointer;
}
.custom_pointer {cursor:pointer;}
.fc-event{
    cursor: pointer;
}

.fc-content {
    cursor: pointer;
}
  .main-header {
    border-bottom: 1px solid #dee2e6;
    z-index: 0;
}

.main-sidebar {
 
    overflow-y: visible;
 
}

.sidebar {
 
 overflow-y: visible;

}
  .align-items-center {
    -ms-flex-align: center!important;
    align-items: center!important;
}
.align-text-bottom {
    vertical-align: text-bottom!important;
}

/*This is modifying the btn-primary colors but you could create your own .btn-something class as well*/
.btn-primary {
    color: #fff;
    background-color: #5996be;
    border-color: #357ebd; /*set the color you want here*/
}
.btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary {
    color: #fff;
    background-color: #004862;
    border-color: #285e8e; /*set the color you want here*/
}

.btn-secondary {
    color: #fff;
    background-color: #22b573;
    border-color: #357ebd; /*set the color you want here*/
}
.btn-secondary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary {
    color: #fff;
    background-color: #004862;
    border-color: #285e8e; /*set the color you want here*/
}

.btn-info {
    color: #fff;
    background-color: #90b9d4;
    border-color: #004862; /*set the color you want here*/
}
.btn-info:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary {
    color: #fff;
    background-color: #004862;
    border-color: #285e8e; /*set the color you want here*/
}

.btn-appointment {
    color: #004761;
    background-color: #fff;
    border-color: #357ebd; /*set the color you want here*/
}
.btn-appointment:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary {
    color: #fff;
    background-color: #004862;
    border-color: #285e8e; /*set the color you want here*/
}

.btn-medicine {
    color: #fff;
    background-color: #004761;
    border-color: #357ebd; /*set the color you want here*/
}
.btn-medicine:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary {
    color: #fff;
    background-color: #5996be;
    border-color: #285e8e; /*set the color you want here*/
}

[class*=sidebar-dark-] .sidebar a {
    color: #fff;
}

[class*=sidebar-dark-] .sidebar a:hover {
    color: #c2c7d0; 
}

.br-32 {
  border-radius: 32px;
	-webkit-box-shadow: 0 8px 6px -6px black;
	   -moz-box-shadow: 0 8px 6px -6px black;
	        box-shadow: 0 8px 6px -6px black;
}

.br-32 {
  border-radius: 32px;
	-webkit-box-shadow: 2px 12px 10px -6px black;
	   -moz-box-shadow: 2px 12px 10px -6px black;
	        box-shadow: 2px 12px 10px -6px black;
}

.one-edge-shadow {
  border-radius: 145px;
	-webkit-box-shadow: 0 8px 6px -6px black;
	   -moz-box-shadow: 0 8px 6px -6px black;
	        box-shadow: 0 8px 6px -6px black;
}


.card-shadow {
  border-radius: 16px;
	-webkit-box-shadow: 0 8px 6px -6px black;
	   -moz-box-shadow: 0 8px 6px -6px black;
	        box-shadow: 0 8px 6px -6px black;
}

h1, h2, h3, h4, h5, h6 {
    
    
}

.card {

  border: 1px solid rgba(52,172,113,.9);
}

.font-green{
  color: #34ac71;
}

.font-blue {
  color: #004862;
}

.step-active {
  background-color: #22b573;
}

.bg_transparent {
  background-color: transparent;
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
        <a href="<?php echo base_url("admin/schedule");?>" class="nav-link <?php echo setActive("schedule");?>">Aperturar Horario</a>
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
      
  
      <div class="card card-shadow">
      <form role="form" id="quickForm" method="get" action="<?=base_url('buscarEventos');?>">  
        <div class="card-body" >
        
          <div class="row">
              
                <div class="col-md-6">
                   

                  <div id="calendar"></div>
                   
                </div>

                <div class="col-md-6">
                   
                <div id="listView"></div>
                
  
 
                    
                 </div>
          </div>
          </div>

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

<!-- fullCalendar 2.2.5 -->
<script src='plugins/fullcalendar/locales/es.js'></script>
<script src="plugins/fullcalendar/main.min.js"></script>
<script src="plugins/fullcalendar-daygrid/main.min.js"></script>
<script src="plugins/fullcalendar-timegrid/main.min.js"></script>
<script src="plugins/fullcalendar-interaction/main.min.js"></script>
<script src="plugins/fullcalendar-bootstrap/main.min.js"></script>

<!-- Select2 -->
<script src="<?php echo base_url('plugins/select2/js/select2.full.min.js');?>"></script>
 
<script>
   
  

 


var calendarEl = document.getElementById('calendar');

var calendar = new FullCalendar.Calendar(calendarEl, {
  plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
  selectable: true,
  themeSystem: 'bootstrap',
  locale: 'es',
  events:  '<?php echo base_url("mitestDataHorario");?>',
  validRange: {
          start: "<?php echo date('Y-m-d');?>"
        },

  dateClick: function(info) {
    alert('clicked ' + info.dateStr);




    $.ajax({
        type: 'GET',
        url:  '<?php echo base_url("buscarEventos2/8/2021-02-01");?>',
        data: {
          user:2,
          cmbUsuario: 2,
          cbSpe: 1,
          cbDoc: 8,
          fecha: '01/02/2021',
          tipoCita: 'CV'


        },
        success: function (data) {
          //console.log(data);
          $('#listView').html(data);

      
        
        },
        error: function (data) {
            alert(data);
        },
    }); 


  } 
});

calendar.render();
</script>
</body>
</html>

