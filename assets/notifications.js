console.log("notifications.js");
$(document).ready(function () {
  loadNotifications();
    (function loop() {
      setTimeout(function () {
        loadNotifications();
        loop()
      }, 5000);
    }());
});
function playAudioNoti() {
  var sound = document.getElementById("audionoti");
  audionoti.volume = 0.1;
  sound.play();
}
function seenNotifications(){
  console.log('seenNotifications');
  $.ajax({
    url: "api/seenNotifications",
    type: 'POST',
    data: {
      user_id: 1
    },
    dataType: "json",
    success: function (response) {
     
      
    }
  });
}
function loadNotifications() {
  $.ajax({
    url: "api/notifications",
    type: 'POST',
    data: {
      user_id: 1
    },
    dataType: "json",
    success: function (response) {
      var complete_orders_req = response.data.complete_orders_req;
      //console.log("complete_orders_req"+complete_orders_req);
      var notifications = response.data.notifications;
      var status_count = response.data.status_count;
      var currentStatusCount=$('#status_count').val();
      if(status_count==0 || status_count == currentStatusCount){
        $('#status_count').val(status_count);
        return;
      }

      // Complete Order Request Buttons
      var reqestsHTML = '';
      complete_orders_req.forEach((req) => {
        // console.log(notification.message);
         var id = req.id;
         reqestsHTML +='<a href="order?id='+req.notification_id+'">';
         reqestsHTML += '<button class="btn btn-sm bg-black" style="margin: 2px;">'+req.order_table+'</button>';
         reqestsHTML +='</a>';

      });   

      $('#complete_orders_req').html(reqestsHTML);
      console.log("complete_orders_req.length = "+complete_orders_req.length);
      if(complete_orders_req.length===0){
        $('#complete_orders_req').empty();
      }
      
      $('#status_count').val(status_count)
      var notiData='<a class="nav-link" data-toggle="dropdown" href="#" onclick=seenNotifications()>';
      notiData +='<i class="far fa-bell"></i>';
      var totalUnseen = response.data.unseen;
      if(totalUnseen>0){
        notiData +='<span class="badge badge-danger navbar-badge">'+response.data.unseen+'</span>';
      }else{
        notiData +='<span class="badge badge-success navbar-badge">'+response.data.unseen+'</span>';
      }

      notiData +='</a>';
      console.log(notiData);

      notiData +='<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">';
      var Q = 0;
      notifications.forEach((notification) => {
       // console.log(notification.message);
        var id = notification.id;
        notiData +='<a href="order?id='+id+'" class="dropdown-item">';
        notiData +='<div class="media">';
        notiData +='<img src="uploads/bell.png" alt="User Avatar" class="img-size-50 mr-3">';
        notiData +='<div class="media-body">';
        notiData +='<h3 class="dropdown-item-title">';
        notiData +="";
        var state = "text-danger";
        if(notification.is_seen!=0){
          state="text-muted";
        }
        notiData +='<span class="float-right text-sm '+state+'"><i class="fas fa-star"></i></span>';
        notiData +='</h3>';
        notiData +='<p class="text-sm">'+notification.message+'</p>';
        notiData +='<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i>Today</p>';
        notiData +='</div>';
        notiData +='</div>';
        notiData +='</a>';
        notiData +='<div class="dropdown-divider"></div>';
      }); 

      notiData +='</div>';
      $("#notification").html(notiData);
    }
  });
}
/*
  




*/
