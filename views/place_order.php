<?php
include "../partials/header.php";
include "../partials/sidebar.php";;
?>

<style>
	table,
	tr,
	td {
		padding-right: 20px;
		padding-bottom: 20px;
	}
</style>

<div class="main-panel">
	<div class="content-wrapper">

<ol class="breadcrumb">
				<li><a href="../dashboard.php">Home/</a></li>
				<li class="active">Place Order</li>
			</ol>
	<div class="row bg-white shadow-sm card p-3 rounded-0">
		<div class="col-lg-12 mt-5">

			<div class="panel panel-default">

				<div class="panel-body">

					<div class="remove-messages"></div>
					<center>
						<div class="div-action" style="padding-bottom:40px;font-family: cambria">
							<div class="alert alert-info" style="border-left: 5px solid blue;">
								Click on the button below to trigger the Inventory Wizard<br> <b style="color: red">Make sure you fill all necessary fields</b>
							</div>

						</div> <!-- /div-action -->
					</center>

					<?php

					if (isset($_POST["submitForm"])) {

						$store_id = $_SESSION["store_id"];
						$order_number = strtoupper($shorten_product_name) . rand(100, 999) . date('ys');

						//generate order number
						$sql2 = mysqli_query($connect, "SELECT * FROM placed_orders ORDER BY id DESC LIMIT 1") or die(mysqli_error($connect));
						$data = mysqli_fetch_array($sql2);
						$getLastOrderNo = $data["order_number"];
						$order_number = $getLastOrderNo + 1;

						for ($i = 0; $i < sizeof($_POST['productName']); $i++) {

							$product_name = $_POST['productName'][$i];
							$quantity = $_POST['quantity'][$i];
							$supplier = $_POST['supplier_name'][$i];
							$totalAmount = $_POST['totalAmount'][$i];
							$unit = $_POST['unit'][$i];
							$order_date = date('Y-m-d', strtotime($_POST['orderDate'][$i]));

							//generate product number
							$shorten_product_name = substr($product_name, 0, 2);
							$po_number = strtoupper($shorten_product_name) . date('d') .mt_rand(100, 999). mt_rand(1, 9);

							$sql = "INSERT INTO placed_orders (measurement_unit,po_number,store_id,order_number, product_name, quantity, supplier_name, supplier_com_name, supplier_phone, totalAmount,currentAmountPaid,paymentType,paymentStatus,order_status,order_date) 
									VALUES('$unit','$po_number','$store_id','$order_number','$product_name', '$quantity', '$supplier', '', '', '$totalAmount','$totalAmount', '', '','Not Received','$order_date')";
							$query = mysqli_query($con, $sql) or die(mysqli_error($con));
						}


						if ($query) {
							echo '<div class="alert alert-success" style="border-left: 5px solid green;">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<strong>
								<i class="fas fa-ok-sign"></i> Order saved succesfully. Please make sure you Visit the Received order portal to queue in the product when they arrive.</strong>
								<a id="print_placed_order?orderno=' . $order_number . '" onclick="reprintPlacedOrder(this.id);" class="btn btn-success">Print</a>
							</div>';
						} else {
							echo "";
						}

						
					}

					?>


					<form class="form-horizontal" method="POST" id="formID">
						<table id="emptbl">
							<tr>
								<th>Date</th>
								<th>Item Name</th>
								<th>Quantity</th>
								<th>Unit</th>
								<th>Amount</th>
								<th>Supplier</th>
								<th></th>

							</tr>
							<tr>
								<td id="col0">
									<input type="text" autocomplete="off" class="form-control mr-5 " id="order_date" placeholder="Select Date" name="orderDate[]">
								</td>
								<td id="col1">
									<input type="text" class="form-control mr-5" id="productName" placeholder="Product Name" name="productName[]">
								</td>

								<td id="col2">
									<input type="text" class="form-control mr-2" id="quantity" placeholder="Quantity" name="quantity[]">
								</td>

								<td id="col3">

								
								<select id="unit" class="form-control mr-5" name="unit[]">
									<option value=""></option>
                                            <?php
                                            $sql2 = mysqli_query($con, "SELECT * FROM measurement_units");

                                            while ($fetchData2 = mysqli_fetch_array($sql2)) {
                                            ?>
                                                <option value="<?php echo $fetchData2["unit_name"]; ?>">
                                                    <?php echo ucwords($fetchData2["unit_name"]); ?>
                                                </option>

                                            <?php
                                            }
                                            ?>
                                    </select>
								</td>

								<td id="col4">
									<input type="text" class="form-control mr-2" id="totalAmount" name="totalAmount[]" placeholder="Amount" />
								</td>

								<td id="col5">
									<input type="text" class="form-control mr-5" id="supplier_name" placeholder="Supplier" name="supplier_name[]">

								</td>

								<td id="col6">
									<button type="button" class="btn btn-danger" name="deletebtn[]" onclick="deleteRows()"> <i class="fas fa-trash"></i> </button>
								</td>

							</tr>
						</table>

						<div id="add-product-messages"></div>

						<button type="button" class="btn btn-info mt-5" onclick="addRows()"> <i class="fas fa-plus"></i> Add New Row</button>

						<button type="submit" class="btn btn-success mt-5" name="submitForm" id="submitForm"> <i class="fas fa-check"></i> Submit</button>
					</form> <!-- /.form -->

				</div> <!-- /panel-body -->
			</div> <!-- /panel -->
		</div> <!-- /col-md-12 -->
	</div> <!-- /row -->

</div>


</div>
</div>

<script type="text/javascript">
	function loadDatePicker() {
		$(document).ready(function() {
			$("#order_date").datepicker({
				dateFormat: 'dd-mm-yy'
			});
		});
	}

	loadDatePicker()

	function addRows() {
		var table = document.getElementById('emptbl');
		var rowCount = table.rows.length;
		var cellCount = table.rows[0].cells.length;
		var row = table.insertRow(rowCount);
		for (var i = 0; i <= cellCount; i++) {
			var cell = 'cell' + i;
			cell = row.insertCell(i);
			var copycel = document.getElementById('col' + i).innerHTML;
			cell.innerHTML = copycel;
		}
		
	}


	function deleteRows() {
		var table = document.getElementById('emptbl');
		var rowCount = table.rows.length;
		if (rowCount > '2') {
			var row = table.deleteRow(rowCount - 1);
			rowCount--;
		} else {
			alert('There should be atleast one row');
		}
	}
</script>



<?php include "../partials/footer.php"; ?>