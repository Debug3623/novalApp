<?php getHead();
$controller = $this->router->class; ?>
<?php $title = "Dashboard"; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark"><?php echo $title; ?></h1>
          <!-- <?php $dateObj = date('y-m-d'); 
          echo $dateObj;

          ?> -->

          
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
      <!-- Info boxes -->
      <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Users</span>
              <span class="info-box-number" id="status_0">
                <?php echo $users; ?>
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-box"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Categories</span>
              <span class="info-box-number" id="status_1">
                      <?php echo $categories; ?>

              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-book"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Books</span>
              <span class="info-box-number" id="parcel_orders">  <?php echo $books; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-thumbs-up"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Likes</span>
              <span class="info-box-number" id="status_2"> <?php echo $favorite; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

<!-- Content Wrapper. Contains page content -->


  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="card-tools" style="float: left;    font-size: large;
">
                  Recently Books
                </div>
                </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="latest_orders" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th width="5%">Sr#</th>
                      <th width="10%">Book Title</th>
                      <th width="10%">Author</th>
                      <th width="10%">Image</th>
                      <th width="10%">Description</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if (count($allbooks) > 0) {
                      foreach ($allbooks as $key => $row) {
                        ?>
                        <tr id="row_<?php echo $row->id; ?>">
                          <td><?php echo $key + 1 ?></td>
                       
                          <td class="center"><?php echo  $row->book_title ?></td>
                          <td class="center"><?php echo $row->username ?></td>
                                <td class="text-center"><?php $src = $row->book_image;
                                  $src = base_url() . 'uploads/' . $src; ?>
                            <a class="fancybox" href="<?= $src ?>"><img src="<?= $src ?>" width="60"></a>
                          </td>
                       
                          <td class="center"><?php echo $row->description ?></td>

                       
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

<!-- /.content-wrapper -->
    </div>
    <!--/. container-fluid -->
  </section>
  <!-- /.content -->



</div>
<!-- /.content-wrapper -->
<?php getFooter(); ?>
<script>
  //load 
  $(document).ready(function() {
    tableData();
  });

  function searchtableData() {
    $('#example').DataTable().ajax.reload();
  }

  function loadModelData(elm) {
    console.log("loadModelData" + elm.id);
    var invID = "#invoice_" + elm.id;
    $('#exampleModalLabel').html("Order#" + elm.id + " - Invoice Details");
    $('#modal_body').html($(invID).html());
  }

  function tableData() {

    $('#example').DataTable({
      "paging": true,
      "order": [[ 0, "desc" ]],
      //   "serverSide": true,
      "ajax": {
        "url": "<?= $controller . '/dashboardTable'; ?>",
        "type": "POST",
        "data": {
          "test": 1
        }
      },
      "columns": [{
          "data": "order.id"
        },
        {
          "data": "table.order_table"
        },
        {
          "data": null,
          render: function(order, type, row) {
            // DISHES
            var dishes = order.dishes;
            var dishHtml = "";
            dishHtml = dishHtml + "<div class='card card card-danger collapsed-card' style='width: 200px;'>";
            dishHtml += "<div class='card-header'><h3 class='card-title'>Invoice</h3>";
            dishHtml += "<div class='card-tools'>";
            dishHtml += "<span class='badge badge-danger'>" + dishes.length + " Dishes</span>";
            dishHtml += "<button type='button' class='btn btn-tool' id='" + order.order.id + "' onclick='loadModelData(this)' data-toggle='modal' data-target='#exampleModal'><i class='fas fa-eye'></i></button>";
            dishHtml += "</div>";
            //card-tools div end
            dishHtml = dishHtml + "</div>";
            //card header div end

            dishHtml += "<div id='invoice_" + order.order.id + "' class='card-body p-0'>";
            dishHtml = dishHtml + "<table class='table table-bordered responsive'>";
            var totalOrderCost = 0;
            dishes.forEach((dish) => {
              dishHtml += "<tr>";
              dishHtml += "<td>" + dish.dish_name + "</td>";
              dishHtml += "<td>" + dish.quantity + " x " + dish.item_price + "</td>";
              dishHtml += "<td>" + Number(dish.item_price) * Number(dish.quantity) + "</td>";
              dishHtml += "</tr>";
            });

            //Extras  TAX+SERVICE CHARGES
            var extras = order.extras;
            console.log("response extras " + extras);
            var service_charges = extras.service_charges;
            var tax_percentage = extras.tax_percentage;
            var tax = extras.tax;
            totalOrderCost = extras.totalOrderCost;
            var persons = order.order.total_persons;
            dishHtml += "<tr>";
            dishHtml += "<td>Service Charges</td>";
            dishHtml += "<td>" + persons + "</td>";
            dishHtml += "<td>" + service_charges + "</td>";
            dishHtml += "</tr>";

            dishHtml += "<tr>";
            dishHtml += "<td>Tax</td>";
            dishHtml += "<td>" + tax_percentage + "%</td>";
            dishHtml += "<td>" + tax + "</td>";
            dishHtml += "</tr>";

            dishHtml += "<tr>";
            dishHtml += "<td>Total</td>";
            dishHtml += "<td></td>";
            dishHtml += "<td>" + totalOrderCost + "</td>";
            dishHtml += "</tr>";

            dishHtml += "</table>";

            dishHtml += "</div>";
            //card-body close
            dishHtml += "</div>";
            //card div close
            return dishHtml;

          },
          "targets": -1
        },
        {
          "data": "order.total_cost"
        },
        {
          "data": "order.updated_at"
        },
        {
          "data": null,
          render: function(order, type, row) {
            //STATUS
            var id = order.order.id;
            var order_status = order.order.status;
            var statusHTML = "";
            if (order_status == 1) {
              statusHTML = "<span class='badge badge-info'>Dining</span>";
            } else if (order_status == 0) {
              statusHTML = "<span class='badge badge-danger'>Kitchen</span>";
            } else if (order_status == 2) {
              statusHTML = "<span class='badge badge-success'>Completed</span>";
            }else if (order_status == 4) {
              statusHTML = "<span class='badge badge-danger'>Deleted</span>";
            } else {
              statusHTML = "<span class='badge badge-warning'>Cancelled</span>";
            }

            //STATUS
            return statusHTML;
          }
        },
        {
          "data": "waiter.name"
        },
        {
          "data": null,
          render: function(order, type, row) {
            //ACTION
            var id = order.order.id;
            var order_status = order.order.status;
            var actionHTML = "";
            var changed = 1;
            if (order_status == 1) {
              changed = 2;
              actionHTML = '<button class="btn btn-success btn-xs" onclick="changeOrderStatus(\'' + id + '\',\'' + changed + '\')">Complete Order</button>';
            } else if (order_status == 0) {
              changed = 1;
              actionHTML = '<button class="btn btn-success btn-xs" onclick="changeOrderStatus(\'' + id + '\',\'' + changed + '\')">Dine Order</button>';
            } else if (order_status == 2) {
              changed = 4;
              actionHTML = "<button onclick='createInvoice(" + id + ")' class='btn btn-info btn-xs'>Print Invoice</button></br>";
              actionHTML += '<button class="btn btn-danger btn-xs" data-toggle="modal" onclick="deleteRow(\'' + id + '\')" data-target="#deleteModal">Delete Order</button></br>';
            } else {
              changed = 4;
              //actionHTML = '<button class="btn btn-danger btn-xs" onclick="changeOrderStatus(\'' + id + '\',\'' + changed + '\')">Delete Order</button>';
            }
            return actionHTML;
          },
          "targets": -1
        }
      ],
      rowId: 'id',

    });
  }

function deleteRow(id){
  console.log("deleteRow: "+id);
  resetMessage("<span style='color: black;'>Are you sure to delete?</span>");
  $('#deleteModelFooter').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="button" onclick="changeOrderStatus(\'' + id + '\',4)" class="btn btn-danger">&nbsp;&nbsp;Yes&nbsp;&nbsp;</button>');
}



  $(document).ready(function() {
    //console.log("document eady!");
    fetchOrders();
    (function loop() {
      setTimeout(function() {
        fetchOrders();
        loop()
      }, 5000);
    }());
  });


  $(document).ready(function() {
    (function loop() {
      setTimeout(function() {
        searchtableData();
        loop()
      }, 10000);
    }());
  });


  function fetchOrders() {
    //console.log('fetchOrders');
    var days = 1;
    var date = Date();
    $.ajax({
      url: "<?= $controller . '/dashboard'; ?>",
      type: 'POST',
      data: {
        days: days,
        date: date
      },
      dataType: "json",
      success: function(response) {
        //console.log("response " + response);
        if (response.status == true) {

          var orders = response.data;
          var completed = orders.kitchen;
          var all = orders.all;
          var dining = orders.dining;
          var parcels = orders.parcels;

          document.getElementById("status_1").innerHTML = dining.length;
          // console.log("dining: " + dining.length);

          var kitchen = orders.kitchen;
          // console.log("kitchen: " + kitchen.length);

          document.getElementById("status_0").innerHTML = kitchen.length;

          var completed = orders.kitchen;
          // console.log("completed: " + completed.length);

          document.getElementById("status_2").innerHTML = completed.length;

          var cancelled = orders.cancelled;
          // proceedTab("cancelled_orders", cancelled);
          // document.getElementById("#status_3").innerHTML=cancelled.length;

          // console.log("cancelled: " + cancelled.length);

          document.getElementById("parcel_orders").innerHTML = parcels.length;
          var topHandies = response.data.topHandies;
          if (topHandies) {
            var total = 0;
            topHandies.forEach((handi) => {
              total = Number(total + Number(handi.quantity));
            });
            var htmlHandies = "";
            var colours = ['success', 'danger', 'purple', 'primary', 'info', 'green', 'grey', 'orange', 'warning', 'black', 'fuchsia', 'pink', 'lime', 'orange'];
            topHandies.forEach((handi) => {
              randomColour = colours[Math.floor(Math.random() * (colours.length - 1))];
              percent = Number((handi.quantity * 100) / total);
              htmlHandies += '<div class="progress-group">';
              htmlHandies += handi.title;
              htmlHandies += '<span class="float-right"><b>' + handi.quantity + '</b>/' + total + '</span>';
              htmlHandies += '<div class="progress progress-sm">';
              htmlHandies += '<div class="progress-bar bg-' + randomColour + ' progress-bar-striped active" style="width: ' + percent + '%"></div>';
              htmlHandies += '</div>';
              htmlHandies += '</div>';
            });
            $('#topHandies').html(htmlHandies);
            console.log(htmlHandies);

          }
          if (all.length == 0) {
            //proceedTab("orders_table", completed);
          } else {
            //proceedTab("orders_table", all);
          }
        }
      }
    });
  }

  function proceedTab(elmId, orders) {


    var html = "";
    html += '<table id="latest_orders" class="table table-bordered table-striped">';
    html += '<thead>';
    html += '<tr>';
    html += '<th width="5%">Order#</th>';
    html += '<th width="2%">Table</th>';
    html += '<th width="25%">Dishes</th>';
    html += '<th width="10%">Total</th>';
    html += '<th width="10%">Time</th>';
    html += '<th width="5%">Status</th>';
    html += '<th width="5%">Waiter</th>';
    html += '<th width="5%">Action</th>';
    html += '</tr>';
    html += '</thead>';
    html += '<tbody id = "all_orders">';

    var htmlHolder = document.getElementById(elmId);
    var id = 0;
    var order_status = 0;
    orders.forEach((order) => {
      console.log(order.order);
      id = order.order.id;
      html = html + "<tr>";
      html += "<td class=text-center'>" + id + "</td>";

      //TABLE
      html += "<td>" + order.table.order_table + "</td>";

      // DISHES
      var dishes = order.dishes;
      var dishHtml = "<td>";
      dishHtml = dishHtml + "<div class='card card card-danger collapsed-card'>";
      dishHtml += "<div class='card-header'><h3 class='card-title'>Order Invoice</h3>";
      dishHtml += "<div class='card-tools'>";
      dishHtml += "<span class='badge badge-danger'>" + dishes.length + " Dishes</span>";
      dishHtml += "<button type='button' class='btn btn-tool' data-card-widget='collapse'><i class='fas fa-minus'></i></button>";
      dishHtml += "</div>";
      //card-tools div end
      dishHtml = dishHtml + "</div>";
      //card header div end

      dishHtml += "<div class='card-body p-0'>";
      dishHtml = dishHtml + "<table class='table table-bordered responsive'>";
      var totalOrderCost = 0;
      dishes.forEach((dish) => {
        dishHtml += "<tr>";
        dishHtml += "<td>" + dish.dish_name + "</td>";
        dishHtml += "<td>" + dish.quantity + " x " + dish.item_price + "</td>";
        dishHtml += "<td>" + Number(dish.item_price) * Number(dish.quantity) + "</td>";
        dishHtml += "</tr>";
      });

      //Extras  TAX+SERVICE CHARGES
      var extras = order.extras;
      console.log("response extras " + extras);
      var service_charges = extras.service_charges;
      var tax_percentage = extras.tax_percentage;
      var tax = extras.tax;
      totalOrderCost = extras.totalOrderCost;
      var persons = order.order.total_persons;
      dishHtml += "<tr>";
      dishHtml += "<td>Service Charges</td>";
      dishHtml += "<td>" + persons + "</td>";
      dishHtml += "<td>" + service_charges + "</td>";
      dishHtml += "</tr>";

      dishHtml += "<tr>";
      dishHtml += "<td>Tax</td>";
      dishHtml += "<td>" + tax_percentage + "%</td>";
      dishHtml += "<td>" + tax + "</td>";
      dishHtml += "</tr>";

      dishHtml += "<tr>";
      dishHtml += "<td>Total</td>";
      dishHtml += "<td></td>";
      dishHtml += "<td>" + totalOrderCost + "</td>";
      dishHtml += "</tr>";

      dishHtml += "</table>";

      dishHtml += "</div>";
      //card-body close
      dishHtml += "</div>";
      //card div close

      dishHtml += "</td>";

      html = html + dishHtml;
      //COST
      html += "<td class='center'>" + totalOrderCost + "</td>";

      //TIME
      html += "<td>" + order.order.since_time + "</td>";

      //ACTION
      order_status = order.order.status;
      var statusHTML = "";
      var actionHTML = "";
      var changed = 1;
      if (order_status == 1) {
        changed = 2;
        actionHTML = '<button class="btn btn-success btn-xs" onclick="changeOrderStatus(\'' + id + '\',\'' + changed + '\')">Complete Order</button>';
        statusHTML = "<span class='badge badge-info'>Dining</span>";
      } else if (order_status == 0) {
        changed = 1;
        actionHTML = '<button class="btn btn-success btn-xs" onclick="changeOrderStatus(\'' + id + '\',\'' + changed + '\')">Dine Order</button>';
        statusHTML = "<span class='badge badge-danger'>Kitchen</span>";
      } else if (order_status == 2) {
        changed = 4;
        actionHTML = "<button onclick='createInvoice(" + id + ")' class='btn btn-info btn-xs'>Print Invoice</button>";
        actionHTML = '<button class="btn btn-danger btn-xs" onclick="changeOrderStatus(\'' + id + '\',\'' + changed + '\')">Delete Order</button>';
        statusHTML = "<span class='badge badge-success'>Completed</span>";
        actionHTML += "<button onclick='createInvoice(" + id + ")' class='btn btn-info btn-xs'>Print Invoice</button>";
      } else {
        changed = 4;
        actionHTML = '<button class="btn btn-danger btn-xs" onclick="changeOrderStatus(\'' + id + '\',\'' + changed + '\')">Delete Order</button>';
        statusHTML = "<span class='badge badge-warning'>Cancelled</span>";
      }

      //STATUS
      html += "<td>" + statusHTML + "</td>";

      //WAITER
      html += "<td class='center'>" + order.waiter.name + "</td>";

      html += "<td>" + actionHTML + "</td>";

      html = html + "<tr>";
    });


    //table close 
    html += "</tbody>";
    htmlHolder.innerHTML = html;

    setTimeout(function() {

      // Something you want delayed.
      // $('#latest_orders').DataTable({
      //   processing: true,
      //   serverSide: true,
      // });
    }, 1000);

  }

  function changeOrderStatus(id, status) {
    if (status == 4) {
      resetMessage("<img src='assets/loader.gif' height='50px'>");
        var id;
        var rowid = "div_status_" + id;
        var rowElmId = "#row_"+id;
        //var status;
        $.ajax({
          url: "<?= $controller . '/changeOrderStatus'; ?>",
          type: 'POST',
          data: {
            id: id,
            status: status
          },
          dataType: "json",
          success: function(response) {
            if(response.status==true){
              //alert('successfully deleted.');
              resetMessage("<span style='color: green;'>Successfully deleted.</span>");
            }else{
              resetMessage("<span style='color: red;'>Failed to deleted.</span>");
            }
            $('#deleteModelFooter').html('<button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>');
            searchtableData();
          }
        });
    } else {
      var id;
      var rowid = "div_status_" + id;
      //var status;
      $.ajax({
        url: "<?= $controller . '/changeOrderStatus'; ?>",
        type: 'POST',
        data: {
          id: id,
          status: status
        },
        dataType: "json",
        success: function(response) {
          searchtableData();
        }
      });
    }
  }
  function resetMessage($html){
    $('#delete_model_body').html($html);
  }
  //-------------
  //- DONUT CHART -
  //-------------
  // Get context with jQuery - using jQuery's .get() method.
  var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
  var donutData = {
    labels: [
      'Parcles',
      'Dine In'
    ],
    datasets: [{
      data: [700, 500],
      backgroundColor: ['#f56954', '#00a65a'],
    }]
  }
  var donutOptions = {
    maintainAspectRatio: false,
    responsive: true,
  }
  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  var donutChart = new Chart(donutChartCanvas, {
    type: 'doughnut',
    data: donutData,
    options: donutOptions
  });
</script>
<script src="assets/invoice.js"></script>