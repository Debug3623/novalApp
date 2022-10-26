<?php getHead();
$controller = $this->router->class; ?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark"><?= ucfirst("Expense Management"); ?></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
            <li class="breadcrumb-item active">Expenses</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->


  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="card-tools">
              <a href="<?php echo base_url('expenses/add') ?>" class="btn btn-primary pull-right">Add Expense</a>
            </div>
          </div>

          <div class="card-body">
            <table id="users_table" class="table table-striped table-bordered responsive">
              <thead>
                <tr>
                  <th width="5%">Sr#</th>
                  <th width="10%">Category</th>
                  <th width="10%">Amount</th>
                  <th width="20%">Description</th>
                  <th width="20%">Date</th>
                  <th width="15%">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (count($data) > 0) {
                  $key = 0;
                  foreach ($data as $key => $row) {
                    ?>
                    <tr id="row_<?php echo $row->id; ?>">
                      <td><?php echo $key + 1 ?></td>
                      <td><?php echo $row->category; ?></td>
                      <td><?php echo $row->amount; ?></td>
                      <td><?php echo $row->description; ?></td>
                      <td><?php echo $row->created_at; ?></td>

                      <td class="center">
                        <a data-toggle="tooltip" title=" <?php echo ucwords(this_lang('Edit')); ?>" class="btn  btn-info" href="expenses/edit/<?php echo $row->id; ?>">
                          <i class="fa fa-edit"></i>
                        </a>

                        <a data-toggle="tooltip" title=" <?php echo ucwords(this_lang('Delete')); ?>" class="btn  btn-danger" href="javascript:void(0)" onClick="deleteItem('<?= $row->id; ?>');">
                          <i class="fa fa-trash"></i>
                        </a>
                      </td>
                    </tr>
                <?php }
                } ?>
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>


<?php getFooter(); ?>
<script>
  $('#users_table').dataTable({
    "ordering": false
  });

  function deleteItem(id) {
    var x;
     if (confirm("Are you sure to delete?") == true) {
         x = "Are you sure to delete?";
         $.ajax({
              url: "<?php echo base_url() . $controller . '/deleteExpense'; ?>",
              type: 'POST',
              data: {
                id: id
              },
              dataType: "json",
              success: function(response) {
                var row = "#row_" + id;
                if (response.status == 1) {
                  $(row).hide('slow');
                } else if (response.status == 0) {
                  $.alert('Error', ':You could not delete');
                } else {
                  $.alert('Error', response);
                }
              }
            });
     } else {
         x = "You pressed Cancel!";
     }

  }
</script>