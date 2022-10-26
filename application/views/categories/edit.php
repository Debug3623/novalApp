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
if(!isset($user)) {
	$user = json_decode(json_encode($this->session->flashdata('data')), FALSE);
}
?>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->

	<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark"><?php echo (isset($row)) ? 'Edit' : 'Add' ?> Category</h1>
					</div><!-- /.col -->
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="dashboard">Home</a></li>
							<li class="breadcrumb-item"><a href="categories">Categories</a></li>
							<li class="breadcrumb-item active"><?php echo (isset($row)) ? 'Edit' : 'Add' ?> Category</li>
						</ol>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</div>
		<!-- /.content-header -->

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
				  <form id="form_add_update" enctype="multipart/form-data" name="form_add_update"  role="form">
				    <div class="box-body">
				      	<div class="col-md-6">
					        <div class="form-group">
					          <label>Name</label>
					          <input type="text" class="form-control" placeholder="Enter name" name="name" tabindex="1" value="<?php echo (isset($user) && isset($user->name)) ? $user->name : '' ?>">
					        </div>
				    	</div>

				    	<div class="col-md-6">
					        <div class="form-group">
					          <label>User Name</label>
					          <input type="text" class="form-control" placeholder="Enter User Name" name="user_name" tabindex="2" value="<?php echo (isset($user) && isset($user->user_name)) ? $user->user_name : '' ?>">
					        </div>
					    </div>

					    <div class="col-md-6">
					        <div class="form-group">
					          <label>Password</label>
					          <input type="password" class="form-control" placeholder="Enter password" name="password" tabindex="3" value="">
					        </div>
				    	</div>

				    	<div class="col-md-6">
					        <div class="form-group">
					          <label>Confirm Password</label>
					          <input type="password" class="form-control" placeholder="Confirm password " name="confirm_password" tabindex="4" value="">
					        </div>
					    </div>

					    <div class="col-md-6">
					        <div class="form-group">
					          <label>Mobile</label>
					          <input type="text" class="form-control" placeholder="Enter mobile" name="mobile" tabindex="5" value="<?php echo (isset($user) && isset($user->mobile)) ? $user->mobile : '' ?>">
					        </div>
					    </div>


					    <div class="col-md-6">
					        <div class="form-group">
					          <label>User Type</label>
					          <select class="form-control" name="user_type" tabindex="6">
								<?php
                    				$userTypes = getUserTypes();
									if(count($userTypes)>0){
										foreach ($userTypes as $types) {
										 // list jobs for job_id, you can using $job_id as array-key
										 ?>
										 	<option value="<?php echo $types['id']; ?>" <?php echo (isset($user) && isset($user->user_type) && $user->user_type == $types['id']) ? 'selected' : '' ?>><?php echo $types['title']; ?></option>
										 <?php
									  }}
                    				?>
								   
					          </select>
					        </div>
					    </div>

				    </div>
				    <!-- /.box-body -->

				    <div class="box-footer">
				      <div class="col-md-12">
				        <a class="btn btn-default" href="<?php echo base_url('users') ?>">Back To Listing</a>
				        <button type="submit" class="btn btn-info pull-right">Save</button>
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