<?php getHead(); ?>
<style>
.box-primary {
	margin:5px 2px;	
}
.box-primary img{
	min-width:200px;
	min-height: 200px;
	
}
div.center{
	background-color: #fff;
	border-radius: 5px;
	box-shadow: -2px 2px 7px 1px;
	left: 0;
	margin-left: -100px;
	padding: 11px;
	position: absolute;
	top: 10%;
	width: 50%;
}
.hidden{
	display:none;
}
</style>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Admin Email</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url() ?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<!-- left column -->
			<div class="col-md-12">
				<!-- general form elements -->
				<div class="box box-primary">
				  <div class="box-header"></div>
				  <!-- /.box-header -->
				  <div class="box-body">
				    <?php
				    $errors = $this->session->flashdata('errors');
				    $success = $this->session->flashdata('success');
				    if(!isset($data)) {
				      $data = $this->session->flashdata('data');
				    }
				    require_once APPPATH.'views/includes/errors/errors.php'
				    ?>
				  </div>
				  <!-- form start -->
				  <form role="form" action="<?php echo base_url('settings/admin-email/save') ?>" method="post" enctype="multipart/form-data">
				    <div class="box-body">
				      	<div class="col-md-6">
					        <div class="form-group">
					          <label>Email</label>
					          <input type="text" class="form-control" placeholder="Enter email" name="admin_email" tabindex="1" value="<?php echo (isset($data) && isset($data->field) && $data->field == 'admin_email') ? $data->value : '' ?>">
					        </div>
				    	</div>
				    </div>
				    <!-- /.box-body -->

				    <div class="box-footer">
				      <div class="col-md-12">
				        <button type="submit" class="btn btn-info">Save</button>
				        <input type="hidden" name="id" value="<?php echo (isset($data) && isset($data->id)) ? $data->id : '' ?>">
				      </div>
				    </div>
				  </form>
				</div>
				<!-- /.box -->
			</div>
		</div>
	</section>

<!-- /.content -->
</div>
<?php  getFooter(); ?>