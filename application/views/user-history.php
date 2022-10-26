<?php getHead(); ?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>User History</h1>
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
                require_once APPPATH.'views/includes/errors/errors.php'
                ?>
              </div>
              <table id="users_table" class="table table-striped table-bordered responsive">
                <thead>
                  <tr>
                    <th width="5%">Sr#</th>
                    <th width="10%">User</th>
                    <th width="10%">Attachments</th>
                    <th width="10%">Date\Time</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                if(count($history) > 0){
                  foreach ($history as $key => $row){
                ?>
                  <tr id="row_<?php echo $row->id;?>">
                    <td><?php echo $key+1 ?></td>
                    <td><?php echo $row->name;?></td>
                    <td class="center">
                      <?php 
                      $attachments = $row->attachments;
                      if(!empty($attachments)) {
                        $attachments = explode(',', $attachments);
                        for ($i=0; $i < count($attachments); $i++) { ?>
                          <a href="<?php echo base_url('uploads/'.$attachments[$i]); ?>" target="_blank"><img src="<?php echo base_url('uploads/'.$attachments[$i]); ?>" style="height:50px;width:50px" /></a><?php echo count($attachments)-1 != $i ? ' <b>,</b> ' : '' ?>
                      <?php } } ?>
                    </td>
                    <td class="center"><?php echo (new DateTime($row->created_at))->format('F d, Y h:i:s A') ?></td>
                  </tr>
                <?php } 
                }else{ ?>
                  <td colspan="4" class="text-center">No histry found</td>
                <?php } ?>
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


<?php  getFooter(); ?>
<script>
  $('#users_table').dataTable( {
    "ordering": false
  });
</script>



