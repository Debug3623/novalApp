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
<?php
$errors = $this->session->flashdata('errors');
$success = $this->session->flashdata('success');
if(!isset($user)) {
	$user = json_decode(json_encode($this->session->flashdata('data')), FALSE);
}
?>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Button Titles</h1>
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
				  <form role="form" action="<?php echo base_url('buttons/save') ?>" method="post" enctype="multipart/form-data">
				    <div class="box-body">
				        
				      	<div class="col-md-6">
					        <div class="form-group">
					          <label>Button One</label>
					          <input type="text" class="form-control" placeholder="Enter Button One Title" name="b_one" tabindex="1" value="<?php echo (isset($buttons) && isset($buttons[0]->title)) ? $buttons[0]->title : '' ?>">
					        </div>
				    	</div>

				    	<div class="col-md-6">
					        <div class="form-group">
					          <label>Button Two</label>
					          <input type="text" class="form-control" placeholder="Enter Button Two Title" name="b_two" tabindex="2" value="<?php echo (isset($buttons) && isset($buttons[1]->title)) ? $buttons[1]->title : '' ?>">
					        </div>
					    </div>

					    <div class="col-md-6">
					        <div class="form-group">
					          <label>Button Three</label>
					          <input type="text" class="form-control" placeholder="Enter Button Three Title" name="b_three" tabindex="3" value="<?php echo (isset($buttons) && isset($buttons[2]->title)) ? $buttons[2]->title : '' ?>">
					        </div>
				    	</div>

				    	<div class="col-md-6">
					        <div class="form-group">
					          <label>Button Four</label>
					          <input type="text" class="form-control" placeholder="Enter Button Four Title" name="b_four" tabindex="4" value="<?php echo (isset($buttons) && isset($buttons[3]->title)) ? $buttons[3]->title : '' ?>">
					        </div>
					    </div>

					    <div class="col-md-6">
					        <div class="form-group">
					          <label>Button Fiver</label>
					          <input type="text" class="form-control" placeholder="Enter Button Five Title" name="b_five" tabindex="5" value="<?php echo (isset($buttons) && isset($buttons[4]->title)) ? $buttons[4]->title : '' ?>">
					        </div>
					    </div>

					   

					 

				    </div>
				    <!-- /.box-body -->

				    <div class="box-footer">
				      <div class="col-md-12">
				        <button type="submit" class="btn btn-info pull-right">Save</button>
				        <input type="hidden" name="id" value="1">
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