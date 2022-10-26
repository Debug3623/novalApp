<?php getHead();
$controller = $this->router->class; ?>
<?php $title = "Users"; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark"><?php echo $title; ?></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="dashboard">Admin</a></li>
            <li class="breadcrumb-item active"><?php echo $title; ?></li>
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
                  <a href="<?php echo base_url('users/add') ?>"> <button class="btn btn-primary">Add User</button>
                </div></a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="latest_orders" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th width="5%">Sr#</th>
                      <th width="10%">First Name</th>
                      <th width="10%">Last Name</th>
                    
                      <th width="10%">Role</th>
                      <th width="10%">Status</th>
                      <th width="10%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if (count($users) > 0) {
                      foreach ($users as $key => $row) {
                        ?>
                        <tr id="row_<?php echo $row->id; ?>">
                          <td><?php echo $key + 1 ?></td>
                       
                          <td class="center"><?php echo  $row->fname ?></td>
                          <td class="center"><?php echo $row->lname ?></td>

                          <?php
                              $sub = $row->user_type;

                              ?>

                          <td class="center"><?php 

                             if ( $sub == 1) {
                                  $class = "btn-danger";
                                  $text = 'Admin';
                                } else {
                                  $class = "btn-info";
                                  $text = 'Author';
                                }
                              

                          ?>
                            
                                  <span class="btn btn-sm <?PHP echo $class; ?>"><?PHP echo $text; ?></span>


                          </td>
                          <td class="center" >

                            <?PHP if ($row->is_active == 0) {
                                  $class = "btn-danger";
                                  $text = 'Blocked';
                                } else {
                                  $class = "btn-success";
                                  $text = 'Active';
                                }
                                ?>
                            <?php if ($this->session->userdata('user')->user_type == 2 && $row->user_type != 1 && $row->user_type != 2) { ?>

                              <span id="div_status_<?PHP echo $row->id; ?>">
                                <a id="anchor_<?PHP echo $row->id; ?>" href="javascript:void(0);" onclick="changeStatus('<?PHP echo $row->id; ?>','<?PHP echo $row->is_active; ?>');">
                                  <span class="btn btn-sm <?PHP echo $class; ?>" ><?PHP echo $text; ?></span>
                                </a>
                              </span>
                            <?php } ?>

                            <?php if ($this->session->userdata('user')->user_type == 1 && $row->user_type != 1) { ?>
                              <span id="div_status_<?PHP echo $row->id; ?>">
                                <a id="anchor_<?PHP echo $row->id; ?>" href="javascript:void(0);" onclick="changeStatus('<?PHP echo $row->id; ?>','<?PHP echo $row->is_active; ?>');">
                                  <span class="btn btn-sm <?PHP echo $class; ?>"><?PHP echo $text; ?></span>
                                </a>
                              </span>
                            <?php } ?>

                          </td>

                          <td class="center">
                            <?php if ($this->session->userdata('user')->user_type == 1) { ?>
                              <a class="btn btn-warning" href="<?php echo base_url('users/edit/' . $row->id) ?>" title="Edit"><span class="fas fa-edit"></span></a>
                              <?php if ($this->session->userdata('user')->id != $row->id) { ?>
                                <a class="btn btn-danger" href="javascript:void(0)" onClick="deleteItem(<?= $row->id; ?>)" title="Delete"> <span class="fa fa-trash"></span></a>
                            <?php }
                                } ?>

                            <?php if ($this->session->userdata('user')->user_type == 2 && $row->id != 1 && $row->id != 2) { ?>
                              <?php if ($this->session->userdata('user')->user_type == 2 && $row->user_type != 2) { ?>
                                <a class="btn btn-danger" href="javascript:void(0)" onClick="deleteItem(<?= $row->id; ?>)" title="Delete"> <span class="fa fa-trash"></span></a>
                            <?php }
                                } ?>
                            <?php if ($this->session->userdata('user')->user_type == 2 && $this->session->userdata('user')->id == $row->id) { ?>
                              <a class="btn btn-warning" href="<?php echo base_url('users/edit/' . $row->id) ?>" title="Edit"><span class="fas fa-edit"></span></a>

                            <?php } ?>
                          </td>
                        </tr>
                    <?php }
                    } ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
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
<!-- /.content-wrapper -->
<?php getFooter(); ?>
<script>
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
  function deleteItem(id) {
    console.log(id);
    var x;
     if (confirm("Are you sure to delete?") == true) {
         x = "Are you sure to delete?";
         $.ajax({
              url: "<?php echo base_url().'users/delete/'; ?>"+id,
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