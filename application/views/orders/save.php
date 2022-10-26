<?php getHead();
$controller = $this->router->class; ?>
<style>
	.table td,
	.table th {
		padding: 0.25rem;
	}

	* {
		box-sizing: border-box
	}

	body {
		font-family: "Lato", sans-serif;
	}

	/* Style the tab */
	.tab {
		float: left;
		background-color: #FFFFFF;
		width: 30%;
		overflow-y: auto;
		height: 400px;
	}

	/* Style the buttons inside the tab */
	.tab button {
		display: block;
		background-color: inherit;
		color: black;
		padding: 12px 10px;
		width: 100%;
		border: none;
		outline: none;
		text-align: left;
		cursor: pointer;
		transition: 0.3s;
		font-size: 17px;
	}

	/* Change background color of buttons on hover */
	.tab button:hover {
		background-color: #ddd;
	}

	/* Create an active/current "tab button" class */
	.tab button.active {
		background-color: #26a745;
		color: white;
	}

	/* Style the tab content */
	.tabcontent {
		float: left;
		padding: 0px 12px;
		border: 1px solid #ccc;
		width: 70%;
		border-left: none;
		background-color: #26a745;
		overflow-y: auto;
		height: 400px;
		padding: 5px;
	}

	.box {
		padding: 5px;
		background: white;
		margin: 5px;
	}

	.box p {
		padding-left: 5px;
		font-size: 15px;
		color: red;
		font-weight: bold;
	}

	.box #price {
		padding-left: 5px;
		font-size: 14px;
		font-weight: bold;
	}
</style>
<!-- Content Header (Page header) -->
<div class="content-wrapper">
	<div class="card card-danger">
		<div class="card-body" style="padding: 0.25rem !important;">
			<div class="row" style="margin:5px" id="complete_orders_req">
				
			</div>
			<div class="row">

				<div class="col-2">
					<select class="form-control" name="waiter_id" onchange="changeWaiter()" id="waiter_id" tabindex="2">
						<?php
						$userTypes = getMultipleRecordWhere("users", array("user_type !=" => CUSTOMER_USER_TYPE));

						if (count($userTypes) > 0) {
							foreach ($userTypes as $types) {
								// list jobs for job_id, you can using $job_id as array-key
								$selected = "";
								if (isset($notification)) {
									$user_id =  $notification->user_id;
									if ($user_id == $types["id"]) {
										$selected = 'selected="selected"';
									} else {
										$selected = "";
									}
								}
						?>

								<option value="<?php echo $types['id']; ?>" <?php echo $selected; ?>><?php echo $types['name']; ?></option>
						<?php
							}
						}
						?>
					</select>
				</div>
				<div class="col-2">
					<select class="form-control" name="customer_id" id="customer_id" tabindex="2">
						<?php
						$userTypes = getMultipleRecordWhere("users", array("user_type" => CUSTOMER_USER_TYPE));

						if (count($userTypes) > 0) {
							foreach ($userTypes as $types) {
								// list jobs for job_id, you can using $job_id as array-key

						?>
								<option value="<?php echo $types['id']; ?>"><?php echo $types['name']; ?></option>
						<?php
							}
						}
						?>
					</select>
				</div>


				<div class="col-2">
					<select class="form-control" name="table_id" id="table_id" tabindex="3" onchange="tableChange(this)">
						<?php
						$userTypes = getMultipleRecordWhere("t_tables", array("is_active" => 1));

						if (count($userTypes) > 0) {
							foreach ($userTypes as $types) {
								// list jobs for job_id, you can using $job_id as array-key
								$selected = "";
								if (isset($notification)) {
									$user_id =  $notification->table_id;
									if ($user_id == $types["id"]) {
										$selected = 'selected="selected"';
									} else {
										$selected = "";
									}
								}
						?>
								<option value="<?php echo $types['id']; ?>" <?php echo $selected; ?>><?php echo $types['order_table']; ?></option>
						<?php
							}
						}
						?>
					</select>
				</div>
				<div class="col-2">
					<button class="form-control btn btn-danger btn-sm" onclick="clearOrder(this)">Clear Order</button>
				</div>
				<div class="col-2" style="display:none;">
					<button class="form-control btn btn-info btn-sm" onclick="createInvoice(56)">Create Invoice</button>
				</div>
				<div class="col-2" style="">
					<button class="form-control btn btn-info btn-sm" onclick="createKitchenInvoice(56)">Kitchen Print</button>
				</div>
			</div>
		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->


	<section class="content">
		<div class="container-fluid">
			<!-- Main content -->
			<section class="content" id="print_sec">
				<div class="row">

					<div class="col-md-4">
						<!--/.tabs start -->
						<div class="category_tabs" id="category_tab">


						</div>
						<!--/.tabs end -->
					</div>

					<div class="col-md-5">
						<table id="latest_orders" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th class="text-center">Del</th>
									<th class="text-center">Items</th>
									<th class="text-center">Price</th>
									<th class="text-center">Qty</th>
									<th class="text-center">Total</th>
								</tr>
							</thead>
							<tbody id="cart">

							</tbody>
						</table>


					</div>
					<!-- Menu Section -->

					<div class="col-md-3">
						<div id="invoice">

						</div>
						<!-- this row will not appear when printing -->
						<div class="row no-print" style="margin-bottom:100px">
							<div id="complete_order_section" class="col-12">

							</div>
						</div>
					</div>
					<!-- /.Invoice Section -->


				</div>
			</section>
			<!-- /.content -->
		</div>
		<!--/. container-fluid -->
		<audio id="audio" src="<?php echo base_url('uploads/beep.wav'); ?>" autostart="0"></audio>

	</section>
</div>
<?php getFooter(); ?>


<script>
	$(document).ready(function() {
		$('.main-footer').hide();
		makeMenu();
		console.log("document eady!");
		fetchOrders();
		(function loop() {
			setTimeout(function() {
				//fetchOrders();
				loop()
			}, 3000);
		}());
	});

	function changeWaiter() {
		fetchOrders();
	}

	function tableChange(elm) {
		var table_id = elm.value;
		console.log("table_id " + table_id);
		fetchOrders();
	}

	function clearOrder() {
		console.log("clear Order");
		var table_id = document.getElementById("table_id").value;

		var user_id = document.getElementById("waiter_id").value;
		$.ajax({
			url: "<?= $controller . '/deleteDishCartItem'; ?>",
			type: 'POST',
			data: {
				user_id: user_id,
				table_id: table_id,
				empty_cart: 1,
			},
			dataType: "json",
			success: function(response) {
				// console.log("response " + response);
				if (response.status == true) {
					var data = response.data;
					console.log("response data" + data);
					proceedCartItems(document.getElementById("cart"), data);
					location.reload();
				}

			}
		});
	}

	function makeMenu() {
		var now = new Date();
		months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
		var formattedDate = now.getDate() + "-" + months[now.getMonth()] + "-" + now.getFullYear()

		var dateTime = formattedDate;
		var headHTML = "";
		headHTML = '<li class="nav-item">';
		headHTML += '<a href="dashboard" class="nav-link">' + dateTime + '</a>';
		headHTML += '</li>';
		$(".right-nav").append(headHTML);
	}

	function fetchOrders() {
		//console.log('fetchOrders');
		var table_id = document.getElementById("table_id").value;
		var user_id = document.getElementById("waiter_id").value;
		$.ajax({
			url: "<?= $controller . '/orderstats'; ?>",
			type: 'POST',
			data: {
				user_id: user_id,
				table_id: table_id,
			},
			dataType: "json",
			success: function(response) {
				// console.log("response " + response);
				if (response.status == true) {
					var data = response.data;
					console.log("response data" + data);
					proceedTab(document.getElementById("category_tab"), data);
					proceedCartItems(document.getElementById("cart"), data);
					updateTotalAmount();
				}

			}
		});
	}

	function proceedTab(elmt, data) {
		var categories = data.categories;
		var dishes = data.dishes;
		//console.log("dishes " + dishes);
		var finalHTML = "<div class='tab'>";
		var count = 0;
		categories.forEach((category) => {

			if (count == 0) {
				finalHTML += "<button class='tablinks' onclick='openCity(event," + category.id + ")' id='defaultOpen'>" + category.title + "</button>";
			} else {
				finalHTML += "<button class='tablinks' onclick='openCity(event," + category.id + ")'>" + category.title + "</button>";
			}
			count = count + 1;
		});

		finalHTML += "</div>";

		//add dishes content 
		var dishIndex = 0;
		dishes.forEach((dish) => {
			var categoryObj = categories[dishIndex];
			var currency = "<?php echo CURRENCY; ?>";
			finalHTML += "<div id='" + categoryObj.id + "' class='tabcontent'>";
			dish.forEach((eachDish) => {
				finalHTML += "<div class='row box' id='box_" + eachDish.id + "' onClick='addDish(this,1)'>";
				finalHTML += "<img class='text-center' src= " + eachDish.image + " height='70px' width='70px' />";
				finalHTML += "<div><p>" + eachDish.title + "</p>";
				finalHTML += "<h6 id='price'>" + currency + " " + eachDish.price + "</h6></div>";
				finalHTML += "</div>";
			});
			if (dish.length == 0) {
				finalHTML += "<p class='box'>No Dishes Available</p>";
			}

			finalHTML += "</div>";
			dishIndex = dishIndex + 1;

		});

		elmt.innerHTML = finalHTML;
		// Get the element with id="defaultOpen" and click on it
		document.getElementById("defaultOpen").click();
	}



	/**********************************save************************************/
	$('#form_add_update').on("submit", function(e) {
		e.preventDefault();
		var formData = new FormData();
		var other_data = $('#form_add_update').serializeArray();
		$.each(other_data, function(key, input) {
			formData.append(input.name, input.value);
		});

		$.ajax({
			type: "POST",
			url: "<?php echo base_url() . $controller . '/updateSettings'; ?>",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			dataType: 'JSON',
			beforeSend: function() {
				$('#loader').removeClass('hidden');
				//	$('#form_add_update .btn_au').addClass('hidden');
			},
			success: function(data) {
				$('#loader').addClass('hidden');
				if (data.status == 1) {
					$(".alert").addClass('alert-success');
					$(".alert").removeClass('alert-danger');
					$(".alert").html(data.message);
					$(".alert").removeClass('hidden');
					setTimeout(function() {
						$(".alert").addClass('hidden');
						$('#form_add_update')[0].reset();
						window.location = '<?= $controller . "/settings" ?>';
					}, 2000);
				} else if (data.status == 0) {
					$(".alert").addClass('alert-danger');
					$(".alert").removeClass('alert-success');
					$(".alert").html(data.message);
					$(".alert").removeClass('hidden');
					setTimeout(function() {
						$(".alert").addClass('hidden');
					}, 3000);
				} else if (data.status == 2) {

					$(".alert").addClass('alert-success');
					$(".alert").removeClass('alert-danger');
					$(".alert").html(data.message);
					$(".alert").removeClass('hidden');
					setTimeout(function() {
						window.location = '<?= $controller . "/settings" ?>';
					}, 1000);
				} else if (data.status == "validation_error") { //alert(data.status);
					$(".alert").addClass('alert-warning');
					$(".alert").html(data.message);
					$(".alert").removeClass('hidden');

				}

			}
		});

		//ajax end    
	});

	function openCity(evt, cityName) {
		var i, tabcontent, tablinks;
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
		}
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" active", "");
		}
		document.getElementById(cityName).style.display = "block";
		evt.currentTarget.className += " active";
	}

	function playAudio() {
		var sound = document.getElementById("audio");
		audio.volume = 0.1;
		sound.play();
	}


	function addDish(elm, is_increment) {
		var qty = 1;
		var item_price = 0;
		if (is_increment == 4) {
			//update price_item in cart table
			item_price = elm.value;
			console.log("item_price = " + item_price);
		}
		if (is_increment == 2) {
			console.log(elm.value);
			if (elm.value >= 5000) {
				alert("Maximum quantity could be 5000");
				elm.value = 5000;
			}
			if (elm.value < 1) {
				alert("Minimum quantity should be 1");
				elm.value = 1;
			}
			qty = elm.value;
		}
		playAudio();
		console.log("addDish " + elm.id);
		var table_id = document.getElementById("table_id").value;
		var product_id = elm.id.split('_')[1];
		// echo "product_id";
		// exit();
		var user_id = document.getElementById("waiter_id").value;
		$.ajax({
			url: "<?= $controller . '/addDishCart'; ?>",
			type: 'POST',
			data: {
				user_id: user_id,
				table_id: table_id,
				product_id: product_id,
				item_price: item_price,
				qty: qty,
				is_increment: is_increment,
			},
			dataType: "json",
			success: function(response) {
				// console.log("response " + response);
				if (response.status == true) {
					var data = response.data;
					console.log("response data" + data);
					proceedCartItems(document.getElementById("cart"), data);
					updateTotalAmount();
				}

			}
		});

	}

	function setFocus(id) {
		document.getElementById(id).focus();
	}

	function proceedCartItems(elm, data) {
		var cartItems = data.cart_order;
		var settings = data.settings;
		var finalHTML = "";
		var invoice = "";
		var subTotal = 0;

		cartItems.forEach((cartItem) => {
			console.log(cartItem);
			finalHTML += "<tr id='cartItem_" + cartItem.id + "'>";
			finalHTML += "<td class='text-center'><div class='btn btn-danger btn-xs' id='delete_" + cartItem.id + "' onclick='deleteCartItem(this)'>&nbsp;x&nbsp;</div></td>";
			finalHTML += "<td class='text-center'>" + cartItem.title + "</td>";
			finalHTML += "<td class='text-center'><input type='number' value='" + cartItem.item_price + "' style='width: 5em' onblur='addDish(this,4)' name='cartItemPrice' class='cartItemPrice form-control' id='itemprice_" + cartItem.product_id + "'/></td>";
			finalHTML += "<td class='text-center'><div class='btn btn-info btn-xs' id='min_" + cartItem.product_id + "' onclick='addDish(this,0)'>&nbsp;-&nbsp;</div>&nbsp; <input type='number' style='width: 4em' name='qty' onblur='addDish(this,2)' id='actual_" + cartItem.product_id + "' value='" + cartItem.qty + "'/>&nbsp;<div class='btn btn-success btn-xs' id='plus_" + cartItem.product_id + "' onclick='addDish(this,1)'>&nbsp;<span>+</span>&nbsp;</div></td>";
			finalHTML += "<td class='text-center'>" + Number(cartItem.item_price * cartItem.qty) + "</td>";
			finalHTML += "<tr>";
			subTotal = subTotal + Number(cartItem.item_price * cartItem.qty);
		});
		elm.innerHTML = finalHTML;

		var tax = 0;
		if (settings.tax_percentage > 0) {
			tax = Number((subTotal * settings.tax_percentage) / 100);
		}



		//invoice += '<p class="lead">Invoice</p>';
		invoice += '<div class="table-responsive">';
		invoice += '<table class="table">';
		invoice += '<tr>';
		invoice += '<th style="width:50%">Subtotal:</th>';
		invoice += '<td id="subTotal">' + subTotal + '</td>';
		invoice += '</tr>';
		invoice += '<tr>';
		invoice += '<th>Tax (' + settings.tax_percentage + '%)</th>';
		invoice += '<td id="tax">' + Math.round(tax) + '</td>';
		invoice += '</tr>';

		var service_charges = 0;
		var check_is_perperson_service = "no";
		var checkHTML = "";
		if (settings.service_charges > 0) {
			service_charges = Number(settings.service_charges);
			if (settings.is_perperson_service > 0) {
				invoice += '<tr>';
				invoice += '<th>Total Persons:</th>';
				invoice += '<td><div class=""><input type="hidden" id="service_charges_per_person" value="' + settings.service_charges + '"><input type="number" value="' + settings.is_perperson_service + '" class="form-control" name="total_persons" id="total_persons" placeholder="0"></div></td>';
				invoice += '</tr>';
				check_is_perperson_service = "yes";
				checkHTML = '(' + settings.service_charges + ')';
			}
		}

		invoice += '<tr>';
		invoice += '<th>Service Charges' + checkHTML + ':<input type="hidden" id="check_is_perperson_service" value="' + check_is_perperson_service + '"></th>';
		invoice += '<td id="service_charges">' + settings.service_charges + '</td>';
		invoice += '</tr>';
		invoice += '<tr class="table-grey">';
		invoice += '<th>Total:</th>';
		var finalTotal = Number(subTotal + (Math.round(tax)) + service_charges);
		finalTotal = Math.round(finalTotal);
		invoice += '<th id="finalTotal" style="color:#dc3545" value="' + finalTotal + '">' + finalTotal + '</th>';
		invoice += '</tr>';
		invoice += '<tr>';
		invoice += '<th>Cash Received:</th>';
		invoice += '<td><div class=""><input type="number" class="form-control" name="paid_amount" id="paid_amount" placeholder="0"></div></td>';
		invoice += '</tr>';
		invoice += '<tr>';
		invoice += '<th>Change:</th>';
		invoice += '<td id="change_return">0</td>';
		invoice += '</tr>';
		invoice += '</table>';
		invoice += '</div>';

		document.getElementById("invoice").innerHTML = invoice;
		setFocus("paid_amount");

		$('#total_persons').on('input', function() {
			var total_persons = Number(document.getElementById('total_persons').value);
			var service_charges_per_person = Number(document.getElementById('service_charges_per_person').value);
			console.log("service_charges_per_person: " + service_charges_per_person);
			var newServiceCharges = Number(total_persons * service_charges_per_person);
			document.getElementById('service_charges').innerHTML = newServiceCharges;
			updateTotalAmount();
		});


		$('#paid_amount').on('input', function() {
			// do something
			updateTotalAmount();
		});

		// $('.cartItemPrice').on('input', function(event) {
		// 	// do something
		// 	console.log(event.target.id);
		// 	 addDish(document.getElementById(event.target.id),4);
		// });

	}

	function updateTotalAmount() {
		setTimeout(function() {

			// Something you want delayed.
			var paidAmount = Number(document.getElementById('paid_amount').value);
			console.log("paidAmount: " + paidAmount);
			var finalTotal = Number(document.getElementById('subTotal').innerHTML) + Number(document.getElementById('service_charges').innerHTML) + Number(document.getElementById('tax').innerHTML);
			document.getElementById('finalTotal').innerHTML = finalTotal;
			console.log("finalTotal: " + finalTotal);
			if (Number(document.getElementById('subTotal').innerHTML) == 0) {
				document.getElementById('paid_amount').value = "";

			}
			change = Math.round(Number(paidAmount - finalTotal) * 100) / 100;
			console.log("change: " + change);

			var cmpltOrderSec = document.getElementById("complete_order_section");

			var completeOrderHTML = "";


			if (change >= 0) {
				$('#change_return').html(change);
				document.getElementById('paid_amount').classList.add("is-valid");
				document.getElementById('paid_amount').classList.remove("is-invalid");
				completeOrderHTML += '<button type ="button" onclick="completeCartOrder(this)" id="complete_order_btn" class = "btn btn-sm btn-success">';
				completeOrderHTML += '<i class = "far fa-credit-card" ></i> Complete Order</button>';
				cmpltOrderSec.innerHTML = completeOrderHTML;
				setFocus("complete_order_btn");
				playAudio();
			} else {
				$('#change_return').html('0');
				document.getElementById('paid_amount').classList.remove("is-valid");
				document.getElementById('paid_amount').classList.add("is-invalid");
				//completeOrderHTML += '<button type = "button" class ="btn btn-sm disabled">';
				completeOrderHTML += '<i></i>';
				// completeOrderHTML+= '</button>';
				cmpltOrderSec.innerHTML = completeOrderHTML;
				setFocus("paid_amount");
			}


		}, 1);
	}


	function handleValueChange() {

	}

	function deleteCartItem(elm) {
		console.log(elm.id);
		var table_id = document.getElementById("table_id").value;
		var id = elm.id.split('_')[1];
		var hideRow = "#cartItem_" + id;
		$(hideRow).hide('slow');
		var user_id = document.getElementById("waiter_id").value;
		$.ajax({
			url: "<?= $controller . '/deleteDishCartItem'; ?>",
			type: 'POST',
			data: {
				id: id,
				user_id: user_id,
				table_id: table_id,
				empty_cart: 0,
			},
			dataType: "json",
			success: function(response) {
				// console.log("response " + response);
				if (response.status == true) {
					var data = response.data;
					console.log("response data" + data);
					proceedCartItems(document.getElementById("cart"), data);
				}

			}
		});
	}

	function completeCartOrder() {
		//console.log("completeCartOrder");
		var check_is_perperson_service = document.getElementById("check_is_perperson_service").value;
		var total_persons = 1;
		if (check_is_perperson_service === "yes") {
			total_persons = document.getElementById("total_persons").value;
		}
		var table_id = document.getElementById("table_id").value;

		var customer_id = document.getElementById("customer_id").value;
		var user_id = document.getElementById("waiter_id").value;
		var paidAmount = Number(document.getElementById('paid_amount').value);
		if (paidAmount == 0 || paidAmount < 0) {
			alert('Please Add Cash Received');
			setFocus("paid_amount");
			return;
		}
		var change_return = Number(document.getElementById('change_return').innerHTML);

		$.ajax({
			url: "<?= $controller . '/completeCartOrder'; ?>",
			type: 'POST',
			data: {
				user_id: user_id,
				table_id: table_id,
				is_cart: true,
				customer_id: customer_id,
				total_persons: total_persons,
				cash_received: paidAmount,
				cash_change: change_return,
			},
			dataType: "json",
			success: function(response) {
				console.log("data.status " + response.status);
				if (response.status == true) {
					var data = response.data;
					if (response.status === true) {
						console.log("response data" + data);
						proceedCartItems(document.getElementById("cart"), data);
						setTimeout(function() {
							createInvoice(response.order_id);
						}, 10);

					} else {
						console.log("else");
						alert(response.message);
					}
				}

			}
		});
	}

	function createKitchenInvoice() {


		var table_id = document.getElementById("table_id").value;
		// var customer_id = document.getElementById("customer_id").value;
		var user_id = document.getElementById("waiter_id").value;
		// var product_id = 1;

		//  alert("table: "+table_id);
		//  exit();
		$.ajax({
			url: "<?= $controller . '/orderstats'; ?>",
			type: 'POST',
			data: {
				user_id: user_id,
				table_id: table_id,
				createKitchenInvoice: 1
			},
			dataType: "json",
			success: function(response) {
				console.log("data.status " + response.status);
				setTimeout(function() {
					JScreateKitchenInvoice(response);
				}, 10);

			}

		});

	}
</script>
<script src="assets/invoice.js"></script>
<script src="assets/invoice_kitchen.js"></script>