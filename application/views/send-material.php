<?php getHead(); ?>
<!-- datetimepicker plugin -->
<link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.css">


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
<?php
$errors = $this->session->flashdata('errors');
$success = $this->session->flashdata('success');
?>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Send Materials</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url() ?>dashboard"><i class="fa fa-dashboard"></i> Home</a>
			<li><a href="<?php echo base_url() ?>users"><i class="fa fa-user"></i> Users</a>
			<li>Send Materials</a>
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
				    <?php require_once APPPATH.'views/includes/errors/errors.php' ?>
				  </div>
				  <!-- form start -->
				  <form role="form" action="<?php echo base_url('users/send/materials/'.$user->id) ?>" method="post" enctype="multipart/form-data">
				    <div class="box-body">
				      	<div class="col-md-6">
					        <div class="form-group">
					          <label>Select files</label>
					          <input type="file" class="form-control" name="documents[]" tabindex="1" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.docx,.xls,.xlsx">
					        </div>
				    	</div>
				    </div>
				    <!-- /.box-body -->

				    <div class="box-footer">
				      <div class="col-md-6">
				        <a class="btn btn-default" href="<?php echo base_url('users') ?>">Back To Listing</a>
				        <button type="submit" class="btn btn-info pull-right">Send</button>
				        <input type="hidden" name="id" value="<?php echo (isset($user) && isset($user->id)) ? $user->id : '' ?>">
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
<script type="text/javascript">
	$(document).ready(function(){
		$('.datepicker').datepicker({
			format: 'yyyy-mm-dd',
			clearBtn: true
		});
	});
</script>