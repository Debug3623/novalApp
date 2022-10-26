<?php getHead();
$controller = $this->router->class; ?>

<!-- Content Header (Page header) -->
<div class="content-wrapper">

	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Settings</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="dashboard">Home</a></li>
						<li class="breadcrumb-item active">Settings</li>
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
							<div class="card-body">
								<form id="form_add_update" enctype="multipart/form-data" name="form_add_update" role="form">
									<div class="col-xs-12">
										<div class="alert hidden"></div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<!-- text input -->
											<div class="form-group">

												<label for="software_house_name	">Company Name</label>
												<input type="text" class="form-control" id="software_house_name" placeholder="" 
												name="software_house_name" value="<?php if (isset($row)) {
															echo $row->software_house_name;
														}
								                 ?>">

											</div>
										</div>
										<div class="col-sm-6">
											<!-- text input -->
											<div class="form-group">

												<label for="company_address">Company Address</label>
												<input type="text" class="form-control" id="company_address" placeholder="" name="company_address" value="<?php if (isset($row)) {
																	echo $row->company_address;
																											} ?>">

											</div>
										</div>
										
									</div>
									<div class="row">
										<div class="col-sm-6">
											<!-- textarea -->
									<div class="form-group">
											<label for="service_charges">Comapny Phone</label>
	                                        <input type="text" class="form-control" id="software_house_phone" placeholder=""
	                                         name="software_house_phone" value="<?php if (isset($row)) {
											 echo $row->software_house_phone;
			                                 } ?>">
											</div>
										</div>
											<div class="col-sm-6">
											<!-- textarea -->
									<div class="form-group">
											<label for="service_charges">Comapny Website</label>
	                                        <input type="text" class="form-control" id="software_house_website" placeholder=""
	                                         name="software_house_website" value="<?php if (isset($row)) {
											 echo $row->software_house_website;
			                                 } ?>">
											</div>
										</div>
								
										
									</div>
								
									
														
									<div class="row">
										<div class="col-xs-12 col-md-6">
											<button type="submit" class="btn btn-info"><?php if (isset($row)) {
																							echo 'Update';
																						} else {
																							echo 'Add';
																						} ?> </button>
											<input type="hidden" id="id" name="id" value="<?php if (isset($row)) {
																								echo $row->id;
																							} ?>">
										</div>
									</div>
								</form>
							</div>
							<!-- /.card-body -->
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
			url: "<?php echo base_url() . $controller . '/updateSettings'; ?>",
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
						window.location = '<?= $controller . "/settings" ?>';
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
						window.location = '<?= $controller . "/settings" ?>';
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