function JScreateKitchenInvoice(response) {


  var data = response.data;
  var cart_order = data.cart_order;
  //var order = data.order;
  // var waiter = data.waiter;
  var html = "<div style='width:260px;margin-left: 23px;'>";
  var settings = data.settings;
  html += "<table style='width:100%'>";
  html += "<tr style='text-align: center;'><td colspan='2'><span style='font-weight:bold;'>" + settings.restaurant_name + "</span></td><tr>";


  html += "<tr style='text-align: center;'><td colspan='2'>-----------<b>kITCHEN PRINT</b>------------</td><tr>";

  html += "<tr style='text-align: left;'>";
  html += "<td>" + data.waiter.name + "</td>";
  html += "<td>T# " + data.table.order_table + "</td>";
  html += "</tr>";


  html += "</table></br>";

  var id = 0;
  var order_status = 0;

  // DISHES
  var dishes = data.cart_order;
  var dishHtml = "<table style='width:100%'>";
  dishHtml += "<tr style='text-align:left'>";
  dishHtml += "<th>Item</th>";
  dishHtml += "<th>Q</th>";
  dishHtml += "<th>Price</th>";
  dishHtml += "<th>Total</th>";
  dishHtml += "</tr>";
  var totalOrderCost = 0;
  var Q = 0;
  dishes.forEach((dish) => {
    console.log("dish: " + dish.title);
    dishHtml += "<tr>";
    dishHtml += "<td>" + dish.title + "</td>";
    dishHtml += "<td style='text-align:left'>" + dish.qty + "</td>";
    dishHtml += "<td style='text-align:center'>" + dish.item_price + "</td>";
    dishHtml += "<td style='text-align:center'>" + Number(dish.item_price) * Number(dish.qty) + "</td>";
    dishHtml += "</tr>";
    totalOrderCost += Number(dish.item_price) * Number(dish.qty);
    Q += Number(dish.qty);
  });
  dishHtml += "<tr>";
  dishHtml += "<td colspan='4' style='text-align:right'>------------------------------------------</td>";
  dishHtml += "</tr>";

  dishHtml += "<tr>";
  dishHtml += "<td style='text-align:left;'>Sub Total:</td>";
  dishHtml += "<td style='text-align:left'>" + Q + "</td>";
  dishHtml += "<td style='text-align:center'></td>";
  dishHtml += "<td style='text-align:right'>" + totalOrderCost + "</td>";
  dishHtml += "</tr>";





  html += dishHtml;


  html += "</div>";

  setTimeout(function () {
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = html;
      setTimeout(function() {
        window.resizeTo(240, 1000);

        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload(true);
    }, 10);
  }, 15);



}

