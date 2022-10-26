<?php getHead();
$controller = $this->router->class; ?>
<div class="content-wrapper">

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark"><?= ucfirst($this->router->class); ?></h1>
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
                  <a href="<?php echo base_url('category/add') ?>" class="btn btn-primary pull-right">Add Category</a>
                  </a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="table" class="table table-striped table-bordered responsive">
                  <thead>
                    <tr>
                      <th width="5%">Sr#</th>
                      <th width="10%">Title</th>
                      <th width="10%">Arbic</th>
                      <th width="10%">Image</th>
                      <th width="10%">Action</th>
                    </tr>
                  </thead>


                  <tbody>
                    <?php
                    if ($data->num_rows() > 0) {
                      $key = 0;
                      foreach ($data->result() as $row) {
                        ?>
                        <tr id="row_<?php echo $row->id; ?>">
                          <td><?php echo $key + 1 ?></td>
                          <td><?php echo $row->title; ?></td>
                          <td><?php echo $row->titleAr; ?></td>

                        <!--   <td><?php $src = $row->image;
                                  $src = base_url() . 'uploads/' . $src; ?>
                            <a class="fancybox" href="<?= $src ?>"><img src="<?= $src ?>" width="60"></a>
                          </td> -->


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
                            <a data-toggle="tooltip" title=" <?php echo ucwords(this_lang('Edit')); ?>" class="btn  btn-info" href="category/edit/<?php echo $row->id; ?>">
                              <i class="fa fa-edit"></i>

                            </a>
                            <?php
                            /* 
                            '<a data-toggle="tooltip" title=" <?php echo ucwords(this_lang('Delete')); ?>" class="btn  btn-danger" href="javascript:void(0)" onClick="deleteItem('<?= $row->id; ?>');">
                              <i class="fa fa-trash"></i>'
                              */
                                ?>
                            </a>
                          </td>
                        </tr>
                    <?php }
                    } ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <!-- /.box -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
      </section>
      <!-- /.content -->
    </div>
    <!--/. container-fluid -->
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>


<?php getFooter(); ?>
<script>
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
  /*********************/
  function deleteItem(id) {
    var x;
     if (confirm("Are you sure to delete?") == true) {
         x = "Are you sure to delete?";
         $.ajax({
              url: "<?php echo base_url() . $controller . '/delete'; ?>",
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
  $(document).ready(function() {
    $('#table').DataTable({
      "paging": true,
      "lengthChange": true,
      "lengthMenu": [10, 25, 50, "All"],
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
    });
  });
</script>