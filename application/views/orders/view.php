<?php getHead();
$controller = $this->router->class; ?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>App <?php echo $controller; ?></h1>
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
            <a href="<?php echo base_url('orders/add') ?>" class="btn btn-primary pull-right">Add Order</a>
          </div>
        </div>

        <!--/.tabs start -->



        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#home">Dining(<span id="#status_1">0</span>)</a></li>
          <li><a data-toggle="tab" href="#menu1" >Kitchen(<span id="#status_0">0</span>)</a></li>
          <li><a data-toggle="tab" href="#menu2" >Completed(<span id="#status_2">0</span>)</a></li>
          <li><a data-toggle="tab" href="#menu3" >Cancelled(<span id="#status_3">0</span>)</a></li>
        </ul>

        <div class="tab-content">
          <div id="home" class="tab-pane fade in active">
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
                  <table id="dining_table" class="table table-striped table-bordered responsive">
                    <thead>
                      <tr>
                        <th width="5%">Sr#</th>
                        <th width="5%">Table</th>
                        <th width="20%">Dishes</th>
                        <th width="5%">Total Cost</th>
                        <th width="5%">Time</th>
                        <th width="5%">Waiter</th>
                        <th width="10%">Action</th>
                      </tr>
                    </thead>
                    <tbody id="dining_orders">
                      
                    </tbody>
                  </table>
                </div>
              </div>

            </div>

          </div>
          <div id="menu1" class="tab-pane fade">
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
                  <table id="kitchen_table" class="table table-striped table-bordered responsive">
                    <thead>
                      <tr>
                        <th width="5%">Sr#</th>
                        <th width="5%">Table</th>
                        <th width="20%">Dishes</th>
                        <th width="5%">Total Cost</th>
                        <th width="5%">Time</th>
                        <th width="5%">Waiter</th>
                        <th width="10%">Action</th>
                      </tr>
                    </thead>
                    <tbody id="kitchen_orders">

                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>
          <div id="menu2" class="tab-pane fade">
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
                  <table id="completed_table" class="table table-striped table-bordered responsive">
                    <thead>
                      <tr>
                        <th width="5%">Sr#</th>
                        <th width="5%">Table</th>
                        <th width="20%">Dishes</th>
                        <th width="5%">Total Cost</th>
                        <th width="5%">Time</th>
                        <th width="5%">Waiter</th>
                        <th width="10%">Action</th>
                      </tr>
                    </thead>
                    <tbody id="completed_orders">
                      
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>
          <div id="menu3" class="tab-pane fade">
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
                  <table id="cancelled_table" class="table table-striped table-bordered responsive">
                    <thead>
                      <tr>
                        <th width="5%">Sr#</th>
                        <th width="5%">Table</th>
                        <th width="20%">Dishes</th>
                        <th width="5%">Total Cost</th>
                        <th width="5%">Time</th>
                        <th width="5%">Waiter</th>
                        <th width="10%">Action</th>
                      </tr>
                    </thead>
                    <tbody id="cancelled_orders">
                      
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!--/.tabs end -->



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
  $('#kitchen_table').dataTable({
    "ordering": false
  });
  $('#dining_table').dataTable({
    "ordering": false
  });
  $('#cancelled_table').dataTable({
    "ordering": false
  });
  $('#completed_table').dataTable({
    "ordering": false
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

  $(document).ready(function() {
    //console.log("document eady!");
    fetchOrders();
    (function loop() {
      setTimeout(function() {
        fetchOrders();
        loop()
      }, 3000);
    }());
  });

  function fetchOrders() {
    //console.log('fetchOrders');
    var days = 1;
    var date = Date();
    $.ajax({
      url: "<?= $controller . '/fetchOrders'; ?>",
      type: 'POST',
      data: {
        days: days,
        date: date
      },
      dataType: "json",
      success: function(response) {
        //  console.log("response " + response.status);
        if (response.status == true) {
          var orders = response.orders;

         
          

          var dining = orders.dining;
          proceedTab("dining_orders", dining);
          document.getElementById("#status_1").innerHTML=dining.length;
          // console.log("dining: " + dining.length);

          var kitchen = orders.kitchen;
          // console.log("kitchen: " + kitchen.length);
          proceedTab("kitchen_orders", kitchen);
          document.getElementById("#status_0").innerHTML=kitchen.length;

          var completed = orders.completed;
          // console.log("completed: " + completed.length);
          proceedTab("completed_orders", completed);
          document.getElementById("#status_2").innerHTML=completed.length;

          var cancelled = orders.cancelled;
          proceedTab("cancelled_orders", cancelled);
          document.getElementById("#status_3").innerHTML=cancelled.length;

          // console.log("cancelled: " + cancelled.length);
        }

      }
    });
  }

  function proceedTab(elmId, orders) {
    
   
    var html = "";
    var htmlHolder = document.getElementById(elmId);
    var c = 0;
    var order_status=0;
    orders.forEach((order) => {
      //console.log(order.order);
      c = c + 1;
      html = html + "<tr>";
      html += "<td>" + c + "</td>";

      //TABLE
      html += "<td>" + order.table.order_table + "</td>";

      // DISHES
      var dishes = order.dishes;
      var dishHtml = "<td><table class='table table-bordered responsive'>";
      var totalOrderCost = 0;
      dishes.forEach((dish) => {
        dishHtml += "<tr>";
        dishHtml += "<td>" + dish.dish_name + "</td>";
        dishHtml += "<td>" + dish.quantity + "</td>";
        dishHtml += "<td>" + dish.item_price + "</td>";
        dishHtml += "</tr>";
        totalOrderCost = Number(totalOrderCost + Number(dish.item_price));
      });
      dishHtml += "</table></td>";
      html = html + dishHtml;

      //COST
      html += "<td class='center'>" + totalOrderCost + "</td>";
      
      //TIME
      html += "<td>" + order.order.created_at + "</td>";

      //WAITER
      html += "<td>" + order.waiter.name + "</td>";

      //ACTION
      order_status = order.order.status;
      var actionHTML = "";
      if(order_status==1){
        actionHTML = "<button class='btn btn-success btn-xs'>Complete Order</button>";
      }else if(order_status==0){
        actionHTML = "<button class='btn btn-success btn-xs'>Dine Order</button>";
      }else if(order_status==2){
        actionHTML = "<button class='btn btn-danger btn-xs'>Delete Order</button>";
      }else{
        actionHTML = "<button class='btn btn-danger btn-xs'>Delete Order</button>";
      }
      html += "<td>"+actionHTML+"</td>";

      html = html + "<tr>";
    });
    htmlHolder.innerHTML = html;

  }
</script>