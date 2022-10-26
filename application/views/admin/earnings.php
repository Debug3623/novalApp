<?php getHead();
$controller = $this->router->class; ?>
<?php $title = "Sales"; ?>
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
      <div class="row">
        <div class="col-md-12">
          <div class="card">

            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-sm-3 col-6">
                  <div class="description-block border-right">
                    <span class="description-percentage text-success"><i class="fas fa-caret-up"></i></span>
                    <h5 class="description-header" id="totalRevenue">0</h5>
                    <span class="description-text">TOTAL REVENUE</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                  <div class="description-block border-right">
                    <span class="description-percentage text-warning"><i class="fas fa-caret-up"></i></span>
                    <h5 class="description-header" id="totalCost">0</h5>
                    <span class="description-text">TOTAL COST</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                  <div class="description-block border-right">
                    <span class="description-percentage text-success"><i class="fas fa-caret-up"></i></span>
                    <h5 class="description-header" id="totalProfit">0</h5>
                    <span class="description-text">TOTAL PROFIT</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                  <div class="description-block">
                    <span class="description-percentage text-danger"><i class="fas fa-caret-up"></i></span>
                    <h5 class="description-header" id="totalOrders">0</h5>
                    <span class="description-text">ORDERS COMPLETED</span>
                  </div>
                  <!-- /.description-block -->
                </div>
              </div>
              <!-- /.row -->
            </div>
            <!-- ./card-body -->

          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>

      <!-- Info boxes -->
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h5 class="card-title">Today Stats</h5>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                  <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-money-bill-alt"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Revenue</span>
                      <span class="info-box-number" id="todayRevenue">
                        0
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                  <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-wallet"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Expense</span>
                      <span class="info-box-number" id="todayExpense">0</span>
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
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-motorcycle"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Parcels</span>
                      <span class="info-box-number" id="todayTotalParcels">0</span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                  <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-square"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Completed Orders</span>
                      <span class="info-box-number" id="todayTotalOrders">0</span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

            </div>
            <!-- ./card-body -->

          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- Info Filters -->
      <div class="row">
        <div class="col-md-12">
          <div class="card card-info">
            <div class="card-header">
              <h5 class="card-title">Filters</h5>
              <div class="card-tools">

                <button type="button" class="btn btn-tool">
                  PDF
                </button>
                <button type="button" class="btn btn-tool">
                  CSV
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <!-- form -->

                  <form role="form" id="formFilter" style="display:none;">
                    <div class="row">
                      <div class="col-12 col-sm-6 col-md-3">
                        <div class="form-group">
                          <label for="from_date">From Date</label>
                          <input type="date" name="from_date" class="form-control" id="from_date" placeholder="Enter email">
                        </div>
                      </div>
                      <div class="col-12 col-sm-6 col-md-3">
                        <div class="form-group">
                          <label for="to_date">To Date</label>
                          <input type="date" name="to_date" class="form-control" id="to_date" placeholder="Password">
                        </div>
                      </div>
                      <div class="col-12 col-sm-6 col-md-3">
                        <div class="form-group">
                          <label for="noOfDays">No. of Days</label>
                          <input type="number" name="noOfDays" class="form-control" id="noOfDays" placeholder="10" value="">
                        </div>
                      </div>
                      <div class="col-12 col-sm-6 col-md-3">
                        <div class="form-group">
                          <label for="exampleInputPassword1">&nbsp;</label>
                          <button type="button" onclick="searchtableData()" class="btn btn-success form-control" id="searchBtn" placeholder="10"> <i class="fas fa-search"></i></button>
                        </div>
                      </div>
                    </div>

                </div>
                <!-- /.card-body -->


                </form>
              </div>

            </div>
            <!-- ./card-body -->
            <div class="card-footer">
              <table id="example" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Order#</th>
                    <th>Customer</th>
                    <th>Tax</th>
                    <th>Service Charges</th>
                    <th>Total</th>
                    <th>Created By</th>
                    <th>Total Items</th>
                    <th>Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>

            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>


      <!-- Monthly Graph -->
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <?php $years = customQuery("SELECT DISTINCT YEAR(updated_at) as year FROM orders ORDER BY created_at DESC"); ?>

              <h5 class="card-title" id="cardTitle">Monthly Report - <?php if (count($years) > 0) {
                                                                        echo $years[0]->year;
                                                                      } else {
                                                                        echo date("Y");
                                                                      } ?></h5>

              <div class="card-tools">
                <div class="btn-group">

                  <button type="button" id="recapYearTitle" value="<?php if (count($years) > 0) {
                                                                      echo $years[0]->year;
                                                                    } else {
                                                                      echo date("Y");
                                                                    } ?>" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                    <?php if (count($years) > 0) {
                      echo $years[0]->year;
                    } else {
                      echo date("Y");
                    } ?>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <?php for ($i = 0; $i < count($years); $i++) { ?>
                      <a href="javascript:void(0)" onclick="monthlyRecapChangeYear(<?php echo $years[$i]->year; ?>)" class="dropdown-item"><?php echo $years[$i]->year; ?></a>
                    <?php } ?>
                  </div>
                </div>

              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

              <!-- Monthly graph start -->
              <div class="chart">
                <canvas id="barChart" style="height:300px; min-height:300px"></canvas>
              </div>
              <!-- Monthly graph end -->
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-md-12">
          <div class="card card-info">
            <div class="card-header">
              <h5 class="card-title">Daily Sales Report</h5>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <p class="text-center">
                    <strong><div class="form"><input id="dailyFilter" value="<?php echo date("Y-m"); ?>" type="month" name="dailyDate" /></strong>
                  </p>
                  <p class="text-center">
                    <strong id="dailyTotalSale">Sales</strong>
                  </p>

                  <div class="chart">
                    <!-- Sales Chart Canvas -->
                    <canvas id="salesChartt" height="330" style="height: 330px;"></canvas>
                  </div>
                  <!-- /.chart-responsive -->
                  <p class="text-center">
                    <strong id="dailyTotalExpense">Expense</strong>
                  </p>

                  <div class="chart">
                    <!-- Sales Chart Canvas -->
                    <canvas id="expenseChart" height="330" style="height: 330px;"></canvas>
                  </div>
                  <!-- /.chart-responsive -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- ./card-body -->

          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </div>

</div>
<!--/. container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php getFooter(); ?>
<script>
  $(document).ready(function() {
    //console.log("document eady!");
    fetchSales();
    (function loop() {
      setTimeout(function() {
        fetchSales();
        loop()
      }, 3000);
    }());
  });

  function monthlyRecapChangeYear(year) {
    $('#cardTitle').html("Monthly Report - " + year);
    $('#recapYearTitle').html(year);
    $('#recapYearTitle').val(year);
  }

  function fetchSales() {
    //console.log('fetchOrders');
    var days = 1;
    var date = Date();

    $.ajax({
      url: "<?= $controller . '/sales'; ?>",
      type: 'POST',
      data: {
        days: days,
        date: date,
        monthlyRecapYr: $('#recapYearTitle').val(),
        dailyFilter: $('#dailyFilter').val()
      },
      dataType: "json",
      success: function(response) {
        var data = response.data;
        console.log("response " + data);
        var stats = data.stats;

        $('#totalRevenue').html(stats.totalRevenue);
        $('#totalCost').html(stats.totalCost);
        $('#totalProfit').html(stats.totalProfit);
        $('#totalOrders').html(stats.totalOrders);

        //today
        $('#todayRevenue').html(stats.todayRevenue);
        $('#todayExpense').html(stats.todayExpense);
        $('#todayTotalParcels').html(stats.todayTotalParcels);
        $('#todayTotalOrders').html(stats.todayTotalOrders);

        createMonthlyRecapChart(data);
        createDailyRecapChart(data);
      }
    });
  }

  var salesChart = null;
  var expenseChart = null;
  function createDailyRecapChart(data) {
    $('#dailyTotalSale').html("Sale: "+data.DailtyRecapByMonthYear.totalSale);
    $('#dailyTotalExpense').html("Expense: "+data.DailtyRecapByMonthYear.totalExpense);

    //-----------------------
    //- MONTHLY SALES CHART -
    //-----------------------
    if (salesChart != null) {
      salesChart.destroy();
    }
    
    // Get context with jQuery - using jQuery's .get() method.
    var salesChartCanvas = $('#salesChartt').get(0).getContext('2d')
    var saleDays = data.DailtyRecapByMonthYear.saleDays;
    var saleData = data.DailtyRecapByMonthYear.sales;

    var expensesData = data.DailtyRecapByMonthYear.expenses;


    var salesChartData = {
      labels: saleDays,
      datasets: [{
        label: 'Total Sale',
        backgroundColor: 'rgb(108,117,125,09)',
        borderColor: 'rgb(108,117,125,0.5)',
        pointRadius: true,
        pointColor: '#3b8bba',
        pointStrokeColor: 'rgba(60,141,188,1)',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data: saleData
      }]
    }

    var salesChartOptions = {
      animation: false,
      maintainAspectRatio: false,
      responsive: true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines: {
            display: false,
          }
        }],
        yAxes: [{
          gridLines: {
            display: false,
          }
        }]
      }
    }

    // This will get the first returned node in the jQuery collection.
     salesChart = new Chart(salesChartCanvas, {
      type: 'line',
      data: salesChartData,
      options: salesChartOptions
    })

    //---------------------------
    //- END MONTHLY SALES CHART -
    //---------------------------


    //-----------------------
    //- MONTHLY EXPENSE CHART -
    //-----------------------

    if (expenseChart != null) {
      expenseChart.destroy();
    }

    // Get context with jQuery - using jQuery's .get() method.
    var salesChartCanvas = $('#expenseChart').get(0).getContext('2d')


    var salesChartData = {
      labels: saleDays,
      datasets: [{
        label: 'Total Expense',
        backgroundColor: 'rgb(23,162,184,09)',
        borderColor: 'rgb(23,162,184,0.5)',
        pointRadius: true,
        pointColor: '#3b8bba',
        pointStrokeColor: 'rgba(60,141,188,1)',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data: expensesData
      }]
    }

    var salesChartOptions = {
      animation:false,
      maintainAspectRatio: false,
      responsive: true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines: {
            display: false,
          }
        }],
        yAxes: [{
          gridLines: {
            display: false,
          }
        }]
      }
    }

    // This will get the first returned node in the jQuery collection.
     expenseChart = new Chart(salesChartCanvas, {
      type: 'bar',
      data: salesChartData,
      options: salesChartOptions
    })

    //---------------------------
    //- END MONTHLY EXPENSE CHART -
    //---------------------------

  }
  var barChart = null;

  function createMonthlyRecapChart(data) {
    //-------------
    //- BAR CHART -
    //-------------

    if (barChart != null) {
      barChart.destroy();
    }
    var barChartCanvas = $('#barChart').get(0).getContext('2d');
    var areaChartData = {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', "August", "September", "October", "September", "December"],
      datasets: [{
          label: 'Expenses - ' + data.MonthlyRecapByYear.totalExpense,
          backgroundColor: 'rgba(210, 214, 222, 1)',
          borderColor: 'rgba(210, 214, 222, 1)',
          pointRadius: false,
          pointColor: 'rgba(210, 214, 222, 1)',
          pointStrokeColor: '#c1c7d1',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data: data.MonthlyRecapByYear.expenses
        },
        {
          label: 'Sales - ' + data.MonthlyRecapByYear.totalSale,
          backgroundColor: 'rgba(60,141,188,0.9)',
          borderColor: 'rgba(60,141,188,0.8)',
          pointRadius: false,
          pointColor: '#3b8bba',
          pointStrokeColor: 'rgba(60,141,188,1)',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data: data.MonthlyRecapByYear.sales
        },

      ]
    }

    var barChartData = jQuery.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    barChartData.datasets[0] = temp1
    barChartData.datasets[1] = temp0

    var barChartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      datasetFill: false,
      animation: false
    }

    barChart = new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    });
  }

  //load 
  $(document).ready(function() {
    tableData();
  });

  function searchtableData() {
    $('#example').DataTable().ajax.reload();
  }

  function tableData() {

    $('#example').DataTable({
      "paging": true,
      //   "serverSide": true,
      "ajax": {
        "url": "<?= $controller . '/salesTableData'; ?>",
        "type": "POST",
        "data": {
          "form": $("#formFilter").serializeArray(),
          "days": $("#noOfDays").val(),
          "from_date": document.getElementById("from_date").value,
          "to_date": document.getElementById("to_date").value
        }
      },
      "columns": [{
          "data": "id"
        },
        {
          "data": "customer_name"
        },
        {
          "data": "total_tax"
        },
        {
          "data": "total_service_charges"
        },
        {
          "data": "total_cost"
        },
        {
          "data": "created_by"
        },
        {
          "data": "total_items"
        },
        {
          "data": "updated_at"
        },
        {
          data: null,
          defaultContent: '<button onclick=invoice(this) class="print" style="border:1px solid #666;\n\
                                font-size:12px;\n\
                                font-weight:bold;\n\
                                cursor: pointer;\n\
                                  -webkit-border-radius: 2px;\n\
                           -moz-border-radius: 2px;\n\
                            -ms-border-radius: 2px;\n\
                             -o-border-radius: 2px;\n\
                                border-radius: 2px;\n\
                                   text-decoration: none !important;\n\
                                  -webkit-box-shadow: 1px 1px 3px #999;\n\
                           -moz-box-shadow: 1px 1px 3px #999;\n\
                            -ms-box-shadow: 1px 1px 3px #999;\n\
                             -o-box-shadow: 1px 1px 3px #999;\n\
                           box-shadow: 1px 1px 3px #999;\n\
                               background: orange;">PRINT</button>',
          orderable: false,
          searchable: false
        }
      ],
      rowId: 'id',

    });
  }

  function invoice(elm) {
    var order_id = $(elm).closest("tr").attr('id');
    createInvoice(order_id);
  }

  function changeOrderStatus(id, status) {
    var id;
    var rowid = "div_status_" + id;
    var status;
    $.ajax({
      url: "<?= $controller . '/changeOrderStatus'; ?>",
      type: 'POST',
      data: {
        id: id,
        status: status
      },
      dataType: "json",
      success: function(response) {
        if (response.chaged == true) {


        }

      }
    });
  }
</script>
<script src="assets/invoice.js"></script>