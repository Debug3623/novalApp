<?php getHead();
$controller = $this->router->class; ?>
<!-- Content Header (Page header) -->
<div class="content-wrapper">

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
												<label for="exampleInputEmail1"> Title </label>
												<input type="text" class="form-control" id="title" placeholder=" title" name="title" value="<?php if (isset($row)) {
																																				echo $row->title;
																																			} ?>">
											</div>
													<div class="col-xs-12 col-md-6">
												<label for="exampleInputEmail1"> Arabic </label>
												<input type="text" class="form-control" id="titleAr" placeholder=" titleAr" name="titleAr" value="<?php if (isset($row)) {
																	echo $row->titleAr;
																								} ?>">
											</div>
											<div class="clearfix">&nbsp;

											</div>
										<!-- 	<div class="col-xs-12 col-md-6">
												<label> Image</label>
												<input type="file" name="image" id="image" />
												<div class="clearfix">&nbsp;</div>
												<div class="clearfix">&nbsp;</div>
												<?php if (isset($row)) {
													echo '<img src="' . base_url() . 'uploads/' . $row->image . '" width="50">';
												} ?>
											</div> -->
											<div class="clearfix">&nbsp;</div>

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
		// if ($('#image').val() != '') {
		// 	formData.append("image", document.getElementById('image').files[0]);
		// }
		$.ajax({
			type: "POST",
			url: "<?php echo base_url() . $controller . '/save'; ?>",
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
						window.location = '<?= $controller ?>';
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
						window.location = '<?= $controller ?>';
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