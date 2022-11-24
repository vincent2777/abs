<?php
include "../partials/header.php";
include "../partials/sidebar.php";;
?>

<div class="main-panel">
	<div class="content-wrapper">
		<div class="row bg-white shadow-sm rounded-0 p-3">
			<div class="col-md-12">

				<ol class="breadcrumb">
					<li><a href="../dashboard.php">Home/ </a></li>
					<li class="active">All Orders</li>
				</ol>

				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="page-heading"> <i class="fas fa-edit"></i> List of all Placed Orders</div>
					</div> <!-- /panel-heading -->
					<div class="panel-body">


						<script type="text/javascript">
							$(document).ready(function() {
								$('#list_placed_order').DataTable({
									dom: 'lBfrtip',
									"aaSorting": [],
									"lengthMenu": [
										[10, 25, 50, -1],
										[10, 25, 50, "All"]
									]
								});

							});
						</script>

						<div class="remove-messages"></div>

						<?php

						if (isset($_POST["update"])) {
							//update data
							$id = $_POST["id"];
							$product_name = $_POST['productName'];
							$quantity	= $_POST['quantity'];
							$supplier_name			= $_POST['supplier_name'];
							$supplier_com_name	= $_POST['supplier_com_name'];
							$supplier_phone 	= $_POST['supplier_phone'];
							$discount 	= $_POST['discount'];
							$totalAmount	= $_POST['totalAmount'];
							$currentAmountPaid 	= $_POST['currentAmountPaid'];
							$paymentType 	= $_POST['paymentType'];
							$paymentStatus 	= $_POST['paymentStatus'];
							$order_date = date('Y-m-d');

							$updateSQL = mysqli_query($con, "UPDATE placed_orders SET 
					product_name='$product_name', 
					quantity='$quantity', 
					supplier_name='$supplier_name', 
					supplier_com_name='$supplier_com_name', 
					supplier_phone='$supplier_phone', 
					totalAmount='$totalAmount',
					currentAmountPaid='$totalAmount',
					paymentType='$paymentType',
					paymentStatus='$paymentStatus' WHERE id='$id' ") or die(mysqli_error($con));

							if ($updateSQL) {

								echo '<div class="alert alert-success" style="border-left: 5px solid green;">
		            <button type="button" class="close" data-dismiss="alert">&times;</button>
		            <strong><i class="fas fa-ok-sign"></i> Data has been updated Successfully!</strong>
		          </div>';
							}
						}


						$do_action = $_GET["do"];
						$id = $_GET["order"];

						if ($do_action == "delete" && $id != "") {
							$deleteSQL = mysqli_query($con, "DELETE FROM placed_orders WHERE id='$id' ") or die(mysqli_error($con));

							if ($deleteSQL) {
								echo '<div class="alert alert-success" style="border-left: 5px solid green;">
		            <button type="button" class="close" data-dismiss="alert">&times;</button>
		            <strong><i class="fas fa-ok-sign"></i> Data have been deleted Successfully</strong>
		          </div>';
							}
						}


						?>


						<table class="display" id="list_placed_order">
							<thead>
								<tr>
									<th>Order Number</th>
									<th>Product Name</th>
									<th>Quantity Ordered</th>
									<th>Supplier</th>
									<th>Total Amount</th>
									<th>Date</th>
									<th style="width: 12%">Options</th>
								</tr>
							</thead>
							<tbody>
								<?php

								$sql = mysqli_query($connect, "SELECT * FROM placed_orders WHERE order_status='Not Received' ORDER BY id DESC") or die(mysqli_error($connect));

								while ($fetch = mysqli_fetch_array($sql)) {

									echo "<tr>";
									echo "<td>" . $fetch["order_number"] . "</td>";
									echo "<td>" . $fetch["product_name"] . "</td>";
									echo "<td>" . $fetch["quantity"] . "</td>";
									echo "<td>" . $fetch["supplier_name"] . "</td>";
									echo "<td>" . number_format($fetch["totalAmount"], 2) . "</td>";
									echo "<td>" . $fetch["order_date"] . "</td>";

									if ($fetch["order_status"] != "Received") {

										echo "<td><a href='list_placed_order?do=edit&order=" . $fetch["id"] . "'>
								<button class='btn customize-abs-btn p-2'> <i class='fas fa-edit'></i></button></a>
							
								<button class='btn customize-abs-btn p-2' onclick='confirmDeleteOrder(\"" . $fetch["id"] . "\")'> 
								<i class='fas fa-trash'></i></button>
								<a class=\"btn customize-abs-btn p-2\" id=\"print_placed_order?orderno=" . $fetch["order_number"] . "\" onclick=\"reprintPlacedOrder(this.id);\">
								<i class=\"mdi mdi-printer\"></i>
							</a>
							</td>
								";
									} else {

										echo "<td>
									<button class='btn customize-abs-btn p-2' onclick='confirmDeleteOrder(\"" . $fetch["id"] . "\")'> 
									<i class='fas fa-trash'></i></button>
									<a class=\"btn customize-abs-btn p-2\" id=\"print_placed_order?orderno=" . $fetch["order_number"] . "\" onclick=\"reprintPlacedOrder(this.id);\">
									<i class=\"mdi mdi-printer\"></i>
								</a>
									</td>";
									}
									echo "</tr>";

									$do = $_GET["do"];
									$order = $_GET["order"];

									if ($do == "edit" && $order == $fetch["id"]) {
										echo "<tr class='alert' style='background-color: PeachPuff'>";
								?>

										<script type="text/javascript">
											$(document).ready(function() {

												$("#order_date").datepicker();
											});
										</script>
										<form class="form-horizontal" action="list_placed_order" method="POST">

											<td> <input style="display: none;" type="text" value="<?php echo "" . $fetch["order_date"] . "" ?>" disabled class="form-control" id="order_date" placeholder="Select Date" name="orderDate">

												<input type="hidden" value="<?php echo "" . $fetch["id"] . "" ?>" name="id">

											</td>

											<td> <input type="text" value="<?php echo "" . $fetch["product_name"] . "" ?>" class="form-control" id="productName" placeholder="Product Name" name="productName"></td>

											<td><input type="text" value="<?php echo "" . $fetch["quantity"] . "" ?>" class="form-control" id="quantity" placeholder="Quantity" name="quantity"></td>

											<Td> <input type="text" value="<?php echo "" . $fetch["supplier_name"] . "" ?>" class="form-control" id="supplier_name" placeholder="Supplier Name" name="supplier_name"></Td>

											<td> <input type="text" value="<?php echo "" . $fetch["supplier_com_name"] . "" ?>" class="form-control" id="supplier_com_name" placeholder="Supplier Company Name" name="supplier_com_name"></td>

											<td> <input type="text" value="<?php echo "" . $fetch["supplier_phone"] . "" ?>" class="form-control" id="supplier_phone" placeholder="Supplier Phone" name="supplier_phone"></td>

											<td> <input type="text" value="<?php echo "" . $fetch["totalAmount"] . "" ?>" class="form-control" id="totalAmount" name="totalAmount" placeholder="Amout to be Paid" /></td>


											<td> <select class="form-control" name="paymentType" id="paymentType">
													<option selected="" value="<?php echo "" . $fetch["paymentType"] . "" ?>"><?php if (empty($fetch["paymentType"])) {
																																	echo "-Pay Method-";
																																} else {
																																	echo "" . $fetch["paymentType"] . "";
																																} ?></option>
													<option value="Cash">Cash</option>
													<option value="Bank/internet transfer">Bank/Internet Transfer</option>
												</select>
											</td>

											<td>
												<select class="form-control" name="paymentStatus" id="paymentStatus">
													<option selected="" value="<?php echo "" . $fetch["paymentStatus"] . "" ?>"><?php if (empty($fetch["paymentStatus"])) {
																																	echo "-Pay Status-";
																																} else {
																																	echo "" . $fetch["paymentStatus"] . "";
																																} ?> </option>
													<option value="Full Payment">Full Payment</option>
													<option value="Advance Payment">Advance Payment</option>
													<option value="No Payment">No Payment</option>
												</select>
											</td>

									<?php

										echo "<td><button class='btn customize-abs-btn' name='update' type='submit'> <i class='fas fa-check'></i> Update</button></td>";
										echo "</form></tr>";
									} else {
									}
								}


									?>

							</tbody>
						</table>
						<!-- /table -->

					</div> <!-- /panel-body -->
				</div> <!-- /panel -->
			</div> <!-- /col-md-12 -->
		</div> <!-- /row -->

	</div>
</div>
</div>
</div>

<script type="text/javascript">
	function confirmDeleteOrder(val) {
		var x = window.confirm("You are about Deleting a Record. Do you wish to proceed?");
		if (x == true) {
			window.open("list_placed_order.php?do=delete&order=" + val, '_self');
		}else{
			return false;
		}
	}
</script>

<?php include "../partials/footer.php"; ?>