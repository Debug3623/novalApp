<?php getHead(); ?>
<!-- datetimepicker plugin -->
<link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.css">


<style>
	.box-primary {
		margin: 5px 2px;
	}

	.box-primary img {
		min-width: 200px;
		min-height: 200px;

	}

	div.center {
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

	.hidden {
		display: none;
	}
</style>
<?php
$errors = $this->session->flashdata('errors');
$success = $this->session->flashdata('success');
if (!isset($user)) {
	$user = json_decode(json_encode($this->session->flashdata('data')), FALSE);
}
?>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark"><?php echo (isset($user) && $user->id) ? 'Edit' : 'Add' ?> User</h1>
					</div><!-- /.col -->
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="dashboard">Home</a></li>
							<li class="breadcrumb-item"><a href="users">Users</a></li>
							<li class="breadcrumb-item active"><?php echo (isset($user) && $user->id) ? 'Edit' : 'Add' ?> User</li>
						</ol>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</div>
		<!-- /.content-header -->

		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
				<!-- Main content -->
				<section class="content">
					<div class="row">
						<!-- left column -->
						<div class="col-12">
							<div class="card">
								<!-- general form elements -->
								<div class="box box-primary">
									<div class="box-header"></div>
									<!-- /.box-header -->
									<div class="box-body">
										<?php require_once APPPATH . 'views/includes/errors/errors.php' ?>
									</div>
									<!-- form start -->
									<form role="form" action="<?php echo base_url('users/save') ?>" method="post" enctype="multipart/form-data">
										<div class="box-body">
											<div class="col-md-6">
												<div class="form-group">
													<label>First Name</label>
													<input type="text" class="form-control" placeholder="Enter First name" name="fname" tabindex="1" value="<?php echo (isset($user) && isset($user->fname)) ? $user->fname : '' ?>">
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label>Last Name</label>
													<input type="text" class="form-control" placeholder="Enter Last Name" name="lname" tabindex="2" value="<?php echo (isset($user) && isset($user->lname)) ? $user->lname : '' ?>">
												</div>
											</div>

												<div class="col-md-6">
												<div class="form-group">
													<label>Email</label>
													<input type="text" class="form-control" placeholder="Enter Email" name="email" tabindex="30" value="<?php echo (isset($user) && isset($user->email)) ? $user->email : '' ?>">
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
													<label>User Type</label>
													<select class="form-control" name="user_type" tabindex="6">
														<?php
														$userTypes = getUserTypes();
														if (count($userTypes) > 0) {
															foreach ($userTypes as $types) {
																// list jobs for job_id, you can using $job_id as array-key
																if ($this->session->userdata('user')->user_type == 2) {
																	if ($types['id'] == 1 || $types['id'] == 2) {
																		continue;
																	}
																}
																?>
																<option value="<?php echo $types['id']; ?>" <?php echo (isset($user) && isset($user->user_type) && $user->user_type == $types['id']) ? 'selected' : '' ?>><?php echo $types['title']; ?></option>
														<?php
															}
														}
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
						<!-- /.card -->
					</div>
				</section>
				<!-- /.content -->

			</div>
			<!--/. container-fluid -->
		</section>

		<!-- /.content -->
</div>
<?php getFooter(); ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('.datepicker').datepicker({
			format: 'yyyy-mm-dd',
			clearBtn: true
		});
	});
</script>