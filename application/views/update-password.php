<?php getHead(); ?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1 class="text-center">Update Password</h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12 col-md-4 col-md-offset-4">
        <div class="box">
          <div class="box-header">

          </div>
          <!-- /.box-header -->
          <div class="box-body" style="padding: 15px;">
            <?php
            $errors = $this->session->flashdata('errors');
            $success = $this->session->flashdata('success');
            require_once APPPATH.'views/includes/errors/errors.php'
            ?>
            <form method="post" action="<?php echo base_url('save/password') ?>" >
              <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="Password">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
              </div>
              <div class="form-group has-feedback">
                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
              </div>
              <div class="row">
                <!-- /.col -->
                <div class="col-xs-4">
                  <button type="submit" class="btn btn-primary btn-block btn-flat">Update</button>
                </div>
                <!-- /.col -->
              </div>
            </form>
        </div>  
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->
</div>


<?php  getFooter(); ?>
<script>
  $('.main-sidebar').remove();
  $('.content-wrapper').css('margin', '0');
</script>



