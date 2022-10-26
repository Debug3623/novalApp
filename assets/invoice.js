function createInvoice(order_id){
  var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
var d = new Date();
var day = days[d.getDay()];
var hr = d.getHours();
var min = d.getMinutes();
if (min < 10) {
    min = "0" + min;
}
var ampm = "am";
if( hr > 12 ) {
    hr -= 12;
    ampm = "pm";
}
var date = d.getDate();
var month = months[d.getMonth()];
var year = d.getFullYear();
var currDateTime = date + " " + month + ", " + year + " " + hr + ":" + min + ampm;

console.log("order id"+order_id);
    $.ajax({
        url: "admin/orderProfile",
        type: 'POST',
        data: {
            order_id: order_id
        },
        dataType: "json",
        success: function(response) {
             
             var data = response.data;
             var table = data.table;
             var order = data.order;
             var waiter = data.waiter;
             var html = "<div style='width:260px;margin-left: 23px;'>";
             var settings = data.settings;
             html +="<table style='width:100%'>";
             html +="<tr style='text-align: center;'><td colspan='2'><span style='font-weight:bold;'>"+settings.restaurant_name+"</span></td><tr>";
             html +="<tr style='text-align: center;'><td colspan='2'><span>"+settings.restaurant_address+"</span></td><tr>";
             html +="<tr style='text-align: center;'><td colspan='2'><span>Tel: "+settings.restaurant_phone+"</span></td><tr>";
             if(settings.restaurant_ntn.length > 0){
              html +="<tr style='text-align: center;'><td colspan='2'><span>NTN# "+settings.restaurant_ntn+"</span></td><tr>";
             }
            

             html +="<tr style='text-align: center;'><td colspan='2'>-----------<b>INVOICE</b>------------</td><tr>";
      
             html +="<tr style='text-align: left;'>";
             html +="<td>Invoice# "+order.id+"</td>";
             html +="<td>T# "+table.order_table+"</td>";
             html +="</tr>";

             html +="<tr style='text-align: left;'>";
             html +="<td>"+waiter.name+"</td>";
             html +="<td>"+currDateTime+"</td>";
             html +="</tr>";

             html +="</table></br>";
             
             var id = 0;
             var order_status = 0;
             
               // DISHES
               var dishes = data.dishes;
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
                 dishHtml += "<tr>";
                 dishHtml += "<td>" + dish.dish_name + "</td>";
                 dishHtml += "<td style='text-align:left'>" + dish.quantity + "</td>";
                 dishHtml += "<td style='text-align:center'>"+dish.item_price + "</td>";
                 dishHtml += "<td style='text-align:center'>" + Number(dish.item_price) * Number(dish.quantity) + "</td>";
                 dishHtml += "</tr>";
                 totalOrderCost += Number(dish.item_price) * Number(dish.quantity);
                 Q += Number(dish.quantity);
               });
                dishHtml += "<tr>";
                dishHtml += "<td colspan='4' style='text-align:right'>------------------------------------------</td>";
                dishHtml += "</tr>";
        
                dishHtml += "<tr>";
                dishHtml += "<td style='text-align:left;'>Sub Total:</td>";
                dishHtml += "<td style='text-align:left'>"+Q+"</td>";
                dishHtml += "<td style='text-align:center'></td>";
                dishHtml += "<td style='text-align:right'>"+totalOrderCost+"</td>";
                dishHtml += "</tr>";   

              
                dishHtml += "<tr>";
                dishHtml += "<td style='text-align:left;'>Tax:</td>";
                dishHtml += "<td>" + order.tax_percentage + "%</td>";
                dishHtml += "<td></td>";
                dishHtml += "<td style='text-align:right'>" + order.total_tax + "</td>";
                dishHtml += "</tr>";

               if(order.total_service_charges>0){
               dishHtml += "<tr>";
               dishHtml += "<td style='text-align:left'>Service Charges:</td>";
               dishHtml += "<td>" + order.total_persons + "P</td>";
               dishHtml += "<td style='text-align:center'>" + order.service_charges + "</td>";
               dishHtml += "<td style='text-align:right'>" + order.total_service_charges + "</td>";
               dishHtml += "</tr>";
              }

               dishHtml += "<tr>";
               dishHtml += "<td colspan='4' style='text-align:right'>------------------------------------------</td>";
               dishHtml += "</tr>";

               dishHtml += "<tr>";
               dishHtml += "<td style='text-align:left'><b>Total:</b></td>";
               dishHtml += "<td> </td>";
               dishHtml += "<td> </td>";
               dishHtml += "<td style='text-align:right'><b>" + order.total_cost + "</b></td>";
               dishHtml += "</tr>";

               dishHtml += "<tr>";
               dishHtml += "<td colspan='4' style='text-align:right'>------------------------------------------</td>";
               dishHtml += "</tr>";

               dishHtml += "<tr>";
               dishHtml += "<td> </td>";
               dishHtml += "<td> </td>";
               dishHtml += "<td style='text-align:left'>Cash:</td>";
               dishHtml += "<td style='text-align:right'><b>" + order.cash_received + "</b></td>";
               dishHtml += "</tr>";

               dishHtml += "<tr>";
               dishHtml += "<td> </td>";
               dishHtml += "<td> </td>";
               dishHtml += "<td style='text-align:left'>Balance:</td>";
               dishHtml += "<td style='text-align:right'><b>" + order.cash_change + "</b></td>";
               dishHtml += "</tr>";

               dishHtml += "</table>";
               html +=dishHtml;
               html +="<p>PLEASURE SERVING YOU !!!</p>";
               html +="<span>Software By: "+settings.software_house_website+" "+settings.software_house_phone+"</span>";

             html += "</div>";

             setTimeout(function() {
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
    });


    // var html ='<div id="printableArea"><h1>Print me</h1></div>';
    // var printContents = html;
    // var originalContents = document.body.innerHTML;

    // console.log("order id"+order_id);

    // document.body.innerHTML = printContents;
    //  window.print();
    //  document.body.innerHTML = originalContents;
    //  window.location.reload(true);
}