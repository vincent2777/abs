<?php
include "../partials/header.php";
include "../partials/sidebar.php";;
?>



<div class="main-panel">
	<div class="content-wrapper">

		<ol class="breadcrumb">
			<li><a href="../dashboard.php">Home/</a></li>
			<li class="active"> Receive Arrived Orders</li>
		</ol>

		<div class="row  bg-white shadow-sm card p-3 rounded-0">
			<div class="col-md-12">


				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="page-heading"> <i class="fas fa-edit"></i> List of Orders Queued to be Confirmed as Received</div>
					</div> <!-- /panel-heading -->
					<div class="panel-body mt-3">

						<div class="remove-messages"></div>

						<div class="alert alert-info" style="border-left: 5px solid blue;">
							<strong>IMPORTANT: <br>1. If you wish to edit any Information contained in the table below please
								<a href="list_placed_order">Click Here</a>
								<br>2. If the Quantity received is different from the ordered Quantity, <a href="list_placed_order">Click Here</a> to return and change the ordered quantity in order to Reconcile them.

								<br>
								4. Click on the Confirm button to confirm reception of orders. <i style="color: red">NB: You can't change the order status after confirming it</i>
								<br>
								4. Set the Price at which the Product will be sold at.
							</strong>
						</div>

						<?php

						if (isset($_GET["order_number"])) {
							//update data
							$order_number = $_GET["order_number"];
							$order_receive_date = date('Y-m-d');
							$store_id = $_SESSION["store_id"];

							$sql4 = "SELECT * FROM placed_orders WHERE order_number='$order_number'";
							$query4 = mysqli_query($con, $sql4) or die(mysqli_error($con));

							//move purchase data from placed orders to products table
							while ($data4 = mysqli_fetch_array($query4)) {
								
								$id = $data4["id"];
								$pname = $data4["product_name"];
								$po_number = $data4["po_number"];
								$pqty = $data4["quantity"];
								$pqtyRem = $data4["quantity"];
								$paidAmount = $data4["totalAmount"];
								$currentQty = $data4["quantity"];
								$measurement_unit = $data4["measurement_unit"];

								$cost_price = $paidAmount / $currentQty;

								//generate product number
								$shorten_product_name = substr($pname, 0, 2);
								$product_id = strtoupper($shorten_product_name) . date('mds') . mt_rand(10, 50);

								$insertProd = "INSERT INTO 
									product (barcode_id,pexpiry_date,max_to_sell,product_price,measurement_unit,po_number,store_id,cost_price,product_name,order_number,product_id,quantity,quantity_rem,pvld_restrict_sales) 
									VALUES('$product_id','0000-00-00','$pqty','0','$measurement_unit','$po_number','$store_id','$cost_price','$pname','$order_number','$product_id', '$pqty','$pqtyRem',0)";
								$query = mysqli_query($con, $insertProd) or die(mysqli_error($con));


								//update placed orders DB
								$updateSQL = mysqli_query($con, "UPDATE placed_orders 
								SET cost_price='$cost_price', order_receive_date='$order_receive_date', order_status='Received' 
								WHERE id='$id' ") or die(mysqli_error($con));
							}

							if ($query) {

								echo '<div class="alert alert-success" style="border-left: 5px solid green;">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong><i class="fas fa-ok-sign"></i> Congratulations! Order have been received and Product Number has been Generated.<br><br></div>';
							}
						}


						?>

						<script type="text/javascript">
							$(document).ready(function() {
								$('#receive_arrivals').DataTable({
									dom: 'lBfrtip',
									"lengthMenu": [
										[10, 25, 50, -1],
										[10, 25, 50, "All"]
									]
								});

							});
						</script>
						<form method="post" action="receive_arrivals">

							<table class="table table-responsive table-striped" id="receive_arrivals">
								<thead>
									<tr>
										<th>Order Number</th>
										<th>Product Name</th>
										<th>Quantity Received</th>
										<th>Supplied By</th>
										<th>Total Amount</th>
										<th>Order Status</th>
										<th>Options</th>
									</tr>
								</thead>
								<tbody>
									<?php

									$sql = mysqli_query($con, "SELECT * FROM placed_orders WHERE order_status='Not Received' GROUP BY order_number") or die(mysqli_error($con));
									while ($fetch = mysqli_fetch_array($sql)) {
										echo "<tr>
								<td>" . $fetch["order_number"] . "</td>
								<td><input type='hidden' value='" . $fetch["product_name"] . "' class='form-control' name='product_name'>" . $fetch["product_name"] . "</td>
								<td><input type='hidden' value='" . $fetch["quantity"] . "' class='form-control' name='quantity'>" . $fetch["quantity"] . "
								</td>
								<td>" . $fetch["supplier_name"] . "
								<td>" . number_format($fetch["totalAmount"], 2) . "</td>
								<td>" . $fetch["order_status"] . "</td>";
									?>
										<td>
											<button class='btn customize-abs-btn p-2' name='changeOrderBtn' type="button" onclick="confirmOrderStatusChange(<?php echo $fetch['order_number']; ?>)">
												<i class='fas fa-ok-sign'></i> Confirm</button>
										</td>
										</tr>

									<?php
									}

									?>

								</tbody>
							</table>
						</form>

					</div> <!-- /panel-body -->
				</div> <!-- /panel -->
			</div> <!-- /col-md-12 -->
		</div> <!-- /row -->


	</div>
</div>
</div>



<script type="text/javascript">
	function confirmOrderStatusChange(order) {

		var x = window.confirm("Are you sure you want to confirm receipt of this Order?");
		if (x == true) {
			window.open("receive_arrivals?order_number="+order, "_self");
			return true;
		} else {
			return false;
		}

	}
</script>


<?php include "../partials/footer.php"; ?>