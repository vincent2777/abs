<?php
include "../partials/header.php";
include "../partials/sidebar.php";

unset($_SESSION["returns"]);
unset($_SESSION["products"]);
unset($_SESSION["store_transfer_from"]);

?>

<script type="text/javascript" src="<?php echo $pageUrl; ?>script/ttb_cart.js"></script>

<style type="text/css">
	#cart-info:hover {
		text-decoration: none;
	}

	.cart-alert-add {
		position: absolute;
		top: 40;
		width: 30%;
		max-width: 30%;
		z-index: 2;
		left: 35%;
		display: none;
	}
</style>


<div class="main-panel">
	<div class="content-wrapper">
		<div class="container" style="font-family: 'Oxygen';">

			<div class="alert btn-success cart-alert-add animate__animated animate__bounceIn" style="position: fixed;top: 12 !important;right: 30 !important;z-index: 4">
				<p><i class="fas fa-check-circle"></i> Product Added to Cart</p>
			</div>

			<div class="row rounded-0 shadow-sm p-3 bg-white">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading text-left"> <i class="fas fa-calendar"></i> Transfer to Store</div>
						<div class="panel-body">
							<div class="alert alert-info p-2 mt-3">
								<h5 class="text-left">Add Products to Transfer and Click Continue </h5>
								<a href="multistore_cart" class="cart-counter btn btn-info" id="cart-info" title="View Cart" style="float: right;width: auto;height: auto;">
									<span class="cart-item p-1 rounded text-dark bg-white" id="cart-container">
										<?php
										if (isset($_SESSION["ttb_products"])) {
											echo count($_SESSION["ttb_products"]);
										} else {
											echo 0;
										}
										?>

									</span>
									<span style="padding: 10px;color: white">Continue</span>
								</a>
							</div>

							<div class='row justify-content-start'>
								<div class='col-md-5'>

									<h4 style="text-align: left">Swap Stores</h4>
									<form method="post">
										<select class="form-control mb-3 mr-2" name="store" id="store" required="">
											<option value="">-Select Store-</option>
											<?php

											$sql1 = mysqli_query($con, "SELECT * FROM stores WHERE store_id != '$current_store'") or die(mysqli_error($con));
											while ($row = mysqli_fetch_array($sql1)) {
											?>
												<option value="<?php echo $row["store_id"]; ?>"><?php echo strtoupper($row["store_name"]); ?></option>
											<?php
											}
											?>
										</select>


										<Br>
										<span>
											<button type="submit" class="btn btn-success" name="store_swap_btn">Swap Now</button>
										</span>
									</form>
								</div>
							</div>
							<div class="text-center" style="margin-top: 5%">
								<script type="text/javascript">
									$(document).ready(function() {
										$('#list_stock').DataTable();
									});
								</script>
								<?php
								if (!isset($_POST["store_swap_btn"])) {

								?>

									<table class="table table-striped " id="list_stock">
										<thead>
											<tr>
												<th>Product Name</th>
												<th>Total Quantity</th>
												<th>Available For Transfer</th>
												<th>Qty. to Transfer</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$sql_query = "SELECT product_name,quantity_rem, product_id,quantity, product_price FROM product";
											$resultset = mysqli_query($connect, $sql_query) or die("database error:" . mysqli_error($connect));
											$count = 0;
											$rows_count = mysqli_num_rows($resultset);

											while ($row = mysqli_fetch_assoc($resultset)) {

												$qty_remaining = $row["quantity_rem"];
												$pid = $row["product_id"];

												$query_ttbl = "SELECT SUM(quantity_rem) AS total_rem FROM stock_transfer WHERE product_id='$pid' GROUP BY product_id";

												$result_ttbl = mysqli_query($connect, $query_ttbl) or die("database error:" . mysqli_error($conn));
												$row2 = mysqli_fetch_assoc($result_ttbl);
												$qtyRemToTransfer = intval($row["quantity_rem"]) - intval($row2["total_rem"]);
												$qtyTransferred = intval($row2["total_rem"]);

												$count++;
											?>
												<tr style="text-align: left;font-family: 'Oxygen';font-size:15px">

													<form class="stock-transfer-form">
														<td>
															<h5><?php echo $row["product_name"]; ?></h5>
														</td>

														<td>
															<?php echo $row["quantity_rem"]; ?>
														</td>

														<td><?php echo ($qtyRemToTransfer); ?></td>
														<td>
															<input type="number" onkeypress="return isNumber(event)" name="product_qty" class="form-control" value="1" data-code="<?php echo $row["product_id"]; ?>">

														</td>
														<input name="product_id" type="hidden" value="<?php echo $row["product_id"]; ?>">
														<td>

															<?php
															if ($qtyRemToTransfer != 0) {
															?>
																<button type="submit" class="btn btn-info">
																	<i class="fas fa-plus"></i></button>

															<?php
															}
															?>
														</td>

													</form>

													</td>
												</tr>

											<?php } ?>
										</tbody>

									</table>

								<?php } else {

									//show table when store id is changed

								?>

									<table class="table table-striped " id="list_all_products">
										<thead>
											<tr>
												<th>Product Name</th>
												<th>Total Qty</th>
												<th>Qty. Remaining in Store</th>
												<th>Qty. to Transfer</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php

											unset($_SESSION["store_id"]);


											$store = $_POST["store"];
											$_SESSION["store_id"] = $store;
											$_SESSION["store_transfer_from"] = $store;

											$sql1 = "SELECT product_id,store_id,product_name,SUM(quantity) as total_qty,SUM(quantity_rem) as total_rem FROM stock_transfer WHERE store_id='$store' GROUP BY product_id ORDER BY product_name ASC";

											$result1 = mysqli_query($connect, $sql1) or die("database error:" . mysqli_error($connect));
											$count = 0;

											while ($row = mysqli_fetch_assoc($result1)) {

												$pid = $row["product_id"];

												$count++;
											?>
												<tr style="text-align: left;font-family: 'Oxygen';font-size:15px">

													<form class="stock-transfer-form">
														<td>
															<h4><?php echo $row["product_name"]; ?></h4>
														</td>

														<td>
															<?php echo $row["total_qty"]; ?>
														</td>

														<td>
															<?php echo $row["total_rem"]; ?>
														</td>
														<td>
															<input type="number" onkeypress="return isNumber(event)" name="product_qty" class="form-control" value="1" data-code="<?php echo $row["product_id"]; ?>">

														</td>
														<input name="product_id" type="hidden" value="<?php echo $row["product_id"]; ?>">
														<td>

															<?php
															if ($row["total_rem"] != 0) {
															?>
																<button type="submit" class="btn" style="background-color: indigo;color:white">
																	<i class="fas fa-plus"></i></button>

															<?php
															}
															?>
														</td>

													</form>

													</td>
												</tr>

											<?php } ?>
										</tbody>
									</table>


								<?php
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<script>
				function isNumber(evt) {
					var charCode = (evt.which) ? evt.which : event.keyCode;
					if ((charCode < 48 || charCode > 57)) {
						return false;
					} else {
						return true;
					}
				}
			</script>

		</div>

	</div>
</div>

</div>
</div>

<?php include "../partials/footer.php"; ?>