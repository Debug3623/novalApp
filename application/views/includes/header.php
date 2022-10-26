<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Novel App</title>
  <base href="<?php echo base_url(); ?>">

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>


<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed sidebar-collapse">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">

      <!-- Left navbar links -->
      <ul class="navbar-nav right-nav">
        <li class="nav-item">
          <a class="nav-link" id="pushMenu" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="dashboard" class="nav-link">Home</a>
        </li>
        <!-- <li class="nav-item d-none d-sm-inline-block">
          <a href="dashboard" class="nav-link">Hom2</a>
        </li> -->
      </ul>


      <audio id="audionoti" src="<?php echo base_url('uploads/beep.wav'); ?>" autostart="0"></audio>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
         <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown" id="notification">
          
      </li>
   
        <li class="nav-item">
          <a class="nav-link" href="logout"><i></i>Logout</a>
        </li>

      </ul>
    </nav>
    <!-- /.navbar -->
    <!-- Left side column. contains the logo and sidebar -->
    <?php include "aside.php"; ?>
