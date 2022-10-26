<?php getHead();
$controller = $this->router->class; ?>
<!-- Content Header (Page header) -->
<div class="content-wrapper">

	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark"><?php echo (isset($row)) ? 'Edit' : 'Add' ?> Expense</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="dashboard">Home</a></li>
						<li class="breadcrumb-item"><a href="expenses">Expenses</a></li>
						<li class="breadcrumb-item active"><?php echo (isset($row)) ? 'Edit' : 'Add' ?> Expense</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	</section>
	<section class="content">
		<div class="container-fluid">
			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="box box-primary">

								<div class="box-body">
									<form id="form_add_update" enctype="multipart/form-data" name="form_add_update" role="form">
										<div class="col-xs-12">
											<div class="alert hidden"></div>
										</div>
										<div class="form-group wrap_form">

											<div class="col-xs-12 col-md-6">
												<label for="category"> Category </label>
												<select class="form-control" name="category_id" tabindex="3">
													<?php
													$userTypes = getAll("expense_categories");

													if (count($userTypes) > 0) {
														foreach ($userTypes as $types) {
															// list jobs for job_id, you can using $job_id as array-key
															?>
															<option value="<?php echo $types['id']; ?>" <?php echo (isset($row) && isset($row->category_id) && $row->category_id == $types['id']) ? 'selected' : '' ?>><?php echo $types['title']; ?></option>
													<?php
														}
													}
													?>

												</select>

												<div class="clearfix">&nbsp;</div>
											</div>
											<div class="col-xs-12 col-md-6">
												<label for="exampleInputEmail1"> Amount </label>
												<input type="number" class="form-control" id="amount" placeholder="100" name="amount" value="<?php if (isset($row)) {
																																				echo $row->amount;
																																			} ?>">
											</div>
											<div class="clearfix">&nbsp;</div>
											<div class="col-xs-12 col-md-6">
												<label for="exampleInputEmail1"> Expense </label>
												<input type="text" class="form-control" id="description" placeholder="Expense Details" name="description" value="<?php if (isset($row)) {
																																								echo $row->description;
																																							} ?>">
											</div>

										</div>

										<div class="col-xs-12 col-md-6">
											<input type="hidden" id="user_id" name="user_id" value="<?php echo $this->session->userdata('user')->id; ?>">
											<button type="submit" class="btn btn-info"><?php if (isset($row)) {
																							echo 'Update Expense';
																						} else {
																							echo 'Add Expense';
																						} ?> </button>
											<input type="hidden" id="id" name="id" value="<?php if (isset($row)) {
																								echo $row->id;
																							} ?>">
										</div>
										<div class="clearfix">&nbsp;

										</div>

								</div>
								</form>
							</div>
						</div>
					</div>
				</div>
		</div>
	</section>
	<!-- /.content -->
</div>
<!--/. container-fluid -->
</section>
</div>
<?php getFooter(); ?>


<script>
	/**********************************save************************************/
	$('#form_add_update').on("submit", function(e) {
		e.preventDefault();
		var formData = new FormData();
		var other_data = $('#form_add_update').serializeArray();
		$.each(other_data, function(key, input) {
			formData.append(input.name, input.value);
		});

		$.ajax({
			type: "POST",
			url: "<?php echo base_url() . $controller . '/updateExpense'; ?>",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			dataType: 'JSON',
			beforeSend: function() {
				$('#loader').removeClass('hidden');
				//	$('#form_add_update .btn_au').addClass('hidden');
			},
			success: function(data) {
				$('#loader').addClass('hidden');
				if (data.status == 1) {
					$(".alert").addClass('alert-success');
					$(".alert").removeClass('alert-danger');
					$(".alert").html(data.message);
					$(".alert").removeClass('hidden');
					setTimeout(function() {
						$(".alert").addClass('hidden');
						$('#form_add_update')[0].reset();
						window.location = 'expenses/add';
					}, 2000);
				} else if (data.status == 0) {
					$(".alert").addClass('alert-danger');
					$(".alert").removeClass('alert-success');
					$(".alert").html(data.message);
					$(".alert").removeClass('hidden');
					setTimeout(function() {
						$(".alert").addClass('hidden');
					}, 3000);
				} else if (data.status == 2) {

					$(".alert").addClass('alert-success');
					$(".alert").removeClass('alert-danger');
					$(".alert").html(data.message);
					$(".alert").removeClass('hidden');
					setTimeout(function() {
						window.location = 'expenses';
					}, 1000);
				} else if (data.status == "validation_error") { //alert(data.status);
					$(".alert").addClass('alert-warning');
					$(".alert").html(data.message);
					$(".alert").removeClass('hidden');

				}

			}
		});

		//ajax end    
	});
</script>