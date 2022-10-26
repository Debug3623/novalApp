<?php getHead();
$controller = $this->router->class; ?>
<!-- Content Header (Page header) -->
<div class="content-wrapper">

	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Export Database</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="dashboard">Home</a></li>
						<li class="breadcrumb-item active">Export Database</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->
    <div class="row">
										<div class="col-xs-12 col-md-6">
											<button type="submit" class="btn btn-info"> Export Now </button>
										</div>
									</div>
<?php getFooter(); ?>
