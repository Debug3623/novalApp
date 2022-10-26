<?php getHead();
$controller = $this->router->class; ?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1><?= ucfirst($this->router->class); ?></h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url() ?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        
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
              <table id="users_table" class="table table-striped table-bordered responsive">
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
          </div>

        </div>

        <!-- /.box -->
      </div>
      <!-- /.col -->
    </div>
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
 
</script>