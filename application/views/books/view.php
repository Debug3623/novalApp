<?php getHead();
$controller = $this->router->class; ?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->


  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">All Books</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="dashboard">Admin</a></li>
            <li class="breadcrumb-item active"><?= ucfirst($this->router->class); ?></li>
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
          <div class="col-12">
            <div class="card">
            <div class="card-header">
                <div class="card-tools">
                  <a href="<?php echo base_url('adminBooks/add') ?>" class="btn btn-primary pull-right">Add Book</a>
              </div>
            </div>
             
            <div class="card-body">                
                    <div class="box-body">
                      <?php
                      $errors = $this->session->flashdata('errors');
                      $success = $this->session->flashdata('success');
                      require_once APPPATH . 'views/includes/errors/errors.php'
                      ?>
                    </div>
                    <table id="users_table" class="table table-striped table-bordered responsive">
                      <thead>
                        <tr>
                          <th width="5%">Sr#</th>
                          <th width="20%">Title</th>
                          <th>Image</th>
                          <th width="20%">Description</th>
<!--                           <th width="5%">Price</th>
 -->                          <th width="5%">Category</th>
                          <th width="5%">Status</th>
                          <th width="20%">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        if (count($books) > 0) {
                          foreach ($books as $key => $row) {
                            $key = $key + 1;
                            ?>
                            <tr id="row_<?php echo $row->id; ?>">
                              <td><?php echo $key ?></td>
                              <td><?php echo $row->title; ?></td>
                              <td class="text-center"><?php $src = $row->image;
                                  $src = base_url() . 'uploads/' . $src; ?>
                            <a class="fancybox" href="<?= $src ?>"><img src="<?= $src ?>" width="60"></a>
                          </td>

                              <td><?php echo $row->description ?></td>
                         <!--      <td><?php echo $row->price ?></td> -->

                              <?php $sub = getCategoryTitle($row->category_id); ?>

                              <td class="center"><?php echo $sub; ?></td>
                              <td class="center">
                                <?PHP if ($row->is_active == 0) {
                                      $class = "btn-danger";
                                      $text = 'Blocked';
                                    } else {
                                      $class = "btn-success";
                                      $text = 'Active';
                                    }
                                    ?>
                                <span id="div_status_<?PHP echo $row->id; ?>">
                                  <a id="anchor_<?PHP echo $row->id; ?>" href="javascript:void(0);" onclick="changeStatus('<?PHP echo $row->id; ?>','<?PHP echo $row->is_active; ?>');">
                                    <span class="btn btn-sm <?PHP echo $class; ?>"><?PHP echo $text; ?></span>
                                  </a>
                                </span>
                              </td>
                              <td class="center">
                                <a class="btn btn-" href="<?php echo base_url('adminBooks/files/' . $row->id) ?>" title="View"><span class="fas fa-eye"></span></a>

                                <a class="btn btn-warning" href="<?php echo base_url('adminBooks/edit/' . $row->id) ?>" title="Edit"><span class="fas fa-edit"></span></a>
                              </td>
                            </tr>
                        <?php }
                        } ?>
                      </tbody>
                    </table>
              
              </div>
            </div>
            <!-- /.card -->
            <!-- /.box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </section>
      <!-- /.content -->
    </div>
    <!--/. container-fluid -->
  </section>
  <!-- /.content -->
</div>


<?php getFooter(); ?>
<script>
  $('#users_table').dataTable({
    "ordering": true
  });

  function changeStatus(id, status) {
    var id;
    var rowid = "div_status_" + id;
    var status;
    var changed;
    if (status == 1) {
      changed = 0;
    } else {
      changed = 1;
    }
    $.ajax({
      url: "<?= $controller . '/changeStatus'; ?>",
      type: 'POST',
      data: {
        id: id,
        status: status
      },
      dataType: "json",
      success: function(response) {
        if (response.chaged == true) {
          var q = "'";
          if (response.status == "Inactive") {
            mehtml = '<a href="javascript:void(0);" onclick="changeStatus(\'' + id + '\',\'' + changed + '\')"><span class="btn btn-sm btn-danger">Inactive</span></a>';
          } else {
            mehtml = '<a href="javascript:void(0);" onclick="changeStatus(\'' + id + '\',\'' + changed + '\')"><span class="btn btn-sm btn-success">Active</span></a>';
          }

          $("#" + rowid).html(mehtml);

        }

      }
    });
  }
</script>