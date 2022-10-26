<?php getHead();
$controller = $this->router->class; ?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark"><?= ucfirst($this->router->class); ?></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
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
              <div class="box">
                <div class="box-body">
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <div class="">
                    <div class="box-body">
                      <?php
                      $errors = $this->session->flashdata('errors');
                      $success = $this->session->flashdata('success');
                      require_once APPPATH . 'views/includes/errors/errors.php'
                      ?>
                    </div>
                    <div class="card-body">
                      <table id="latest_orders" class="table table-striped table-bordered responsive">
                        <thead>
                          <tr>
                            <th width="5%">Sr#</th>
                            <th width="10%">Table</th>
                            <th width="10%">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if ($data->num_rows() > 0) {
                            $key = 0;
                            foreach ($data->result() as $row) {
                              $key = $key + 1
                              ?>
                              <tr id="row_<?php echo $row->id; ?>">
                                <td><?php echo $key ?></td>
                                <td><?php echo $row->order_table; ?></td>

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
                              </tr>
                          <?php }
                          } ?>
                        </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>
                </div>

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
  $(document).ready(function() {
    $('#latest_orders').DataTable({
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