<?php getHead();
$controller = $this->router->class; ?>

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
<!-- Content Header (Page header) -->
<div class="content-wrapper">
	<section class="content-header">

		<h1><?php echo (isset($row)) ? 'Edit' : 'Add' ?>&nbsp;<?php echo ucfirst($controller); ?> </h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url() ?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?php echo $controller; ?>">View <?php echo ucfirst($controller); ?> </a></li>
		</ol>

	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header"></div>
					<div class="box-body">
						<form id="form_add_update" enctype="multipart/form-data" name="form_add_update" role="form">
							<div class="col-xs-12">
								<div class="alert hidden"></div>
							</div>
							<div class="form-group wrap_form">
								<div class="col-xs-12 col-md-4">
									<label for="exampleInputEmail1"> Title </label>
									<input type="text" class="form-control" id="title" placeholder=" title" name="title" value="<?php if (isset($row)) {
																																	echo $row->title;
																																} ?>">
								</div>
								<div class="col-xs-12 col-md-4">
									<label for="price"> Price </label>
									<input type="number" class="form-control" id="price" placeholder="500" name="price" value="<?php if (isset($row)) {
																																	echo $row->price;
																																} ?>">
								</div>
								<div class="col-xs-12 col-md-4">
									<label for="category"> Category </label>
									<select class="form-control" name="category_id" tabindex="3">
										<?php
										$userTypes = getAll("categories");
										
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
								</div>
								<div class="clearfix">&nbsp;</div>
								<div class="clearfix">&nbsp;</div>
								<div class="col-xs-12 col-md-8">
									<label for="description"> Description </label>
									<input type="text" class="form-control" id="title" placeholder="Description" name="description" value="<?php if (isset($row)) {
																																				echo $row->description;
																																			} ?>">
								</div>
								<div class="clearfix">&nbsp;</div>
								<div class="clearfix">&nbsp;</div>

								<div class="clearfix">&nbsp;
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