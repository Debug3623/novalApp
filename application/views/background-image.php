<?php getHead(); ?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Background Image</h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url() ?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            <form method="post" enctype="multipart/form-data" action="<?php echo base_url('background/save') ?>">
              <div class="col-md-6">
                <div class="form-group">
                  <input type="file" name="background" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <button class="btn btn-primary" type="submit" role="button">Save</button>
              </div>
            </form>
          </div>
        </div>
        <div class="box">
          <div class="box-body">
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="">
              <div class="box-body">
                <?php
                $errors = $this->session->flashdata('errors');
                $success = $this->session->flashdata('success');
                require_once APPPATH.'views/includes/errors/errors.php'
                ?>
              </div>
              <table class="table table-striped table-bordered responsive">
                <thead>
                  <tr>
                    <th width="40%">Image</th>
                    <th width="25%">Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php if(!empty($image) > 0){ ?>
                  <tr id="row_<?php echo $image->id; ?>">
                    <td class="center">
                      <a href="<?php echo base_url('uploads/'.$image->image); ?>" target="_blank"><img src="<?php echo base_url('uploads/'.$image->image); ?>" style="width:80px"></a>
                    </td>
                    <td class="center">
                      <a class="btn btn-danger" href="<?php echo base_url('background/delete/'.$image->id) ?>" title="Delete"> <span class="fa fa-trash"></span></a>
                    </td>
                  </tr>
                <?php } else { ?>
                  <tr>
                    <td class="text-center" colspan="2">No image added yet</td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
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
  $('#users_table').dataTable( {
    "ordering": false
  });
</script>