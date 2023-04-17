<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/fontawesome-free/css/all.min.css');?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- pace-progress -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/pace-progress/themes/black/pace-theme-flat-top.css');?>">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css');?>">
  
  <!-- daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/bootstrap-datepicker/css/bootstrap-datepicker.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('plugins/select2/css/select2.min.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css');?>">

  <!-- adminlte-->
  <link rel="stylesheet" href="<?php echo base_url('css/adminlte.css');?>">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <link rel="stylesheet" href="<?php echo base_url('css/icomoon.css');?>">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
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
  </style>
