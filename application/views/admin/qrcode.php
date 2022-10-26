<?php getHead();
$controller = $this->router->class; ?>
<?php $title = "QR Code"; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark"><?php echo $title; ?></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="dashboard">Admin</a></li>
            <li class="breadcrumb-item active"><?php echo $title; ?></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">

            <!-- /.card-header -->
            <div class="card-body">
               <div class="row">
               <div class="col-md-12">
               <?php echo $image; ?>
               </div>
               </div>
              <!-- /.row -->
            </div>
            <!-- ./card-body -->

          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
    </div>

</div>
<!--/. container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php getFooter(); ?>
