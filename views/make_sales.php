<?php

include "../partials/header.php";
include "../partials/sidebar.php";;
error_reporting(0);
unset($_SESSION["returns"])
?>
<script src="<?php echo $pageUrl; ?>script/cart.js"></script>

<div class="main-panel">
	<div class="content-wrapper ">
		<div class="row p-3" style="background-color: white !important;">

			<center>
				<span id="loader-holder" style="display: none;">
					<img src="<?php echo $pageUrl; ?>images/loading.gif" alt="" width="200" height="130" id="cart_loader">
				</span>
			</center>

			<!-- Start Left hand side where seller clicks on a product -->

			<div class="col-md-5 text-center hidden-print  ml-0 pl-0">
				<div class="panel panel-default">
					<div class="panel-heading text-left">
						<h3>Make Sales</h3>
					</div>
					<div class="panel-body" id="products-holder">

						<input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode" autofocus>
						<div class="text-left" style="margin-top: 5%">
							<?php
							$query = "SELECT * FROM product_info_settings WHERE disable_sales = 0";
							$result = mysqli_query($con, $query) or die("database error:" . mysqli_error($con));
							$mycount = mysqli_num_rows($result);

							if ($mycount > 0) {

								echo "<center>
						<img src='images/noaccess.png' />
						</center><div class='alert alert-danger'>Oops! Sales has been disabled on the System. If you think this was
						an error, Kindly Contact your System Administrator.</div>";
							} else {

							?>

								<div class="overflow-x:auto">
									<table class="table table-striped " id="list_all_products" style="font-size: 10px;">
										<thead>
											<tr>
												<th>Item</th>
												<th>Qty. Remaining</th>
												<th>Unit Price</th>

											</tr>
										</thead>
										<tbody>
											<?php
											$sql_query = "SELECT * FROM product WHERE received_status=1";
											$resultset = mysqli_query($connect, $sql_query) or die("database error:" . mysqli_error($conn));
											$count = 0;
											$rows_count = mysqli_num_rows($resultset);
											while ($row = mysqli_fetch_assoc($resultset)) {
												$count++;
											?>
												<tr onclick="addToCart(1,this.id)" class="add-to-sales" style="text-align: left;font-family: 'Verdana';font-size:12px;cursor:pointer" id="<?php echo $row["product_id"]; ?>">
													<td>
														<h6><?php echo $row["product_name"]; ?></h6>
													</td>

													<td>
														<h6><?php echo $row["quantity_rem"]; ?></h6>
													</td>

													<td><?php echo $currency;
														echo number_format($row["product_price"], 2); ?>
													</td>


												</tr>

											<?php } ?>
										</tbody>
									</table>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>

			<!-- End Left hand side where seller clicks on a product -->


			<style>
				#cart_table tr td {
					font-size: 18px;
					font-family: 'Verdana'"
				}
			</style>
			<div class="col-md-7">

				<?php
				$store_id = $_SESSION["store_id"];

				if (isset($status)) {
					echo "<div class='alert alert-success'> Sales have been processed successfully </div><br>";
				}

				if (!empty($_GET["action"]) && $_GET["action"] == "clear_cart") {
					unset($_SESSION["products"]);
					unset($_SESSION["cart_discounts"]);
					unset($_SESSION["returns"]);
					echo "<div class='alert alert-success'> <b>All Products cleared..You can now make new sales<b/> </div><br>";
					echo "<script>window.open('make_sales','_self'); </script>";
				}

				if (isset($_POST["holdReceiptBtn"])) {
					holdSalesReceipt($con, $store_id);
				}

				?>


				<form method="POST" id="makeSalesForm" novalidate>

					<table class="table table-striped table-sm" id="cart_table" style="font-size:11px !important">
						<thead id="table-head">
							<tr>
								<th>Item</th>
								<th>Price</th>
								<th></th>
								<th>Qty</th>
								<th>Subtotal</th>
								<th>Unit</th>
								<th></th>
							</tr>
						</thead>
						<tbody id="cart-items">

						</tbody>
					</table>


					<div style="margin-top: 70px;margin-left: 15px" id="calculations-holder">

						<input type="hidden" id="sub_total_payable" name="sub_total_payable">
						<input type="hidden" id="total_discount" name="total_discount">
						<input type="hidden" id="vat_amount" name="vat_amount">
						<input type="hidden" id="totalsale_discount" name="totalsale_discount">
						<input type="hidden" name="getTotal" id="getTotal">
						<input type="hidden" name="getVat" id="getVat" value="<?php echo $vat; ?>">

						<div class="row mt-2 pt-2">
							<div class="d-flex justify-content-between" style="text-align: left;">
								<div class="form-group mr-2">
									<label for="">Payment Method</label>
									<select class="form-control" name="paymethod" id="paymethod" required="" onchange="togglePayAmount()">
										<option value="">-Select Payment Method-</option>
										<option selected value="Cash">Cash</option>
										<option value="Bank/internet transfer">Bank/Internet Transfer</option>
									</select>
								</div>
								<div class="form-group mr-2" id="cash_payment_holder">
									<label for="">Cash</label>
									<input type="text" value="0" onkeyup="setCashAmount()" name="cash_payment" autocomplete="off" id="cash_payment" class="form-control" placeholder="Cash Amount">
									<span id="cash-error"></span>
								</div>

								<div class="form-group mr-2" id="bank_payment_holder" style="display: none;">
									<label for="">Bank/Transfer</label>
									<input type="text" value="0" name="bank_payment" onkeyup="setBankAmount()" autocomplete="off" id="bank_payment" class="form-control" placeholder="Bank Payment">
									<span id="bank-error"></span>
								</div>

								<input type="hidden" id="balance_amount" name="balance_amount" value="0">
								<br>

							</div>
						</div>

						<div id="customer_info" style="text-align: left;">
							<div class="row">
								<div class="d-flex justify-content-start">
									<div class="form-group mr-2">
										<input type="text" id="customer_name" onkeyup="retrieveCustomerInfo(this.value)" autocomplete="off" name="customer_name" class="form-control" placeholder="Customer Name">
										<div class="cust_results" id="custsearch_results"></div>
									</div>

									<input type="hidden" name="customer_id" id="customer_id">

									<div class="form-group mr-2">
										<input type="text" id="customer_phone" name="customer_phone" class="form-control" placeholder="Customer Phone" />
									</div>
									<div class="form-group mr-2">
										<input type="text" id="customer_address" name="customer_address" class="form-control" placeholder="Customer Address">

									</div>
								</div>
							</div>
							<input type="hidden" name="customer_current_debt" id="customer_current_debt">

							<input type="hidden" name="customer_balance" id="customer_balance">
							<span id="credit_limit"></span>
							<span id="credit_owing"></span>
							<span id="credit_balance"></span>
						</div>

						<div class="row" style="text-align: left;font-size:14px">
							<div class="col-md-9">
								<strong class="mr-auto">Sub-Total: </strong>
							</div>
							<div class="col">
								<span id="showSubTotal"><?php echo $currency; ?>
									<span id="currency_holder_subtotal">0.00</span>
								</span>
							</div>

						</div>

						<div class="row" style="text-align: left;font-size:14px">
							<div class="col-md-9">
								<strong>Total Discount: </strong>
							</div>
							<div class="col">
								<?php echo $currency; ?>
								<span id="discountTotal">0.00</span>
							</div>
						</div>

						<div class="row" style="text-align: left;font-size:14px">
							<div class="col-md-9">
								<strong>Total: </strong>
							</div>
							<div class="col">

								<span id="currency_holder_total"><?php echo $currency; ?></span>
								<span id="calcTotalPayable">
									0.00
								</span>

							</div>
						</div>

						<div class="row" style="text-align: left;font-size:14px">
							<div class="col-md-9">
								<strong>Paid:</strong>
							</div>
							<div class="col">
								<?php echo $currency; ?>

								<span id="calcTotalPaid">0.00</span>
							</div>
						</div>

						<div class="row" style="text-align: left;font-size:14px">
							<div class="col-md-9">

								<strong id="change_or_balance">Change:</strong>
							</div>
							<div class="col">
								<?php echo $currency; ?>

								<strong><span id="calcTotalBalance">0.00</span>
								</strong>
							</div>
						</div>

						<div class="row" style="text-align: left;font-size:14px">
							<div class="col-md-9">
								<strong>VAT:</strong>
							</div>
							<div class="col">
								<span id="calcVat"><?php echo $currency; ?></span>
							</div>
						</div>

						<div class="row mt-5 ml-1">
							<div class="d-flex justify-content-end">

								<div class="form-group">
									<a href="make_sales?action=clear_cart" name="clearCart" id="clearCart" class="btn btn-warning mr-2 ">
										<i class="fas fa-trash"></i> Clear
									</a>
								</div>
								<div class="form-group">
									<button type="submit" name="holdReceiptBtn" id="holdInvoiceBtn" class="btn btn-primary mr-3">
										<i class="fas fa-ban"></i> <span style="display: inline-block;">Hold</span>
									</button>
								</div>

								<div class="form-group">
									<button type="button" onclick="chargeToCustomerAccount()" id="chargeToAccount" class="btn btn-info mr-3">
										Charge To Account
									</button>
								</div>
								<div class="form-group">
									<button type="submit" name="submitNow" onclick="return submitOrder();" id="submitNow" style="width: 200px;" class="btn btn-success">Submit
										<i class="fas fa-check"></i>
									</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>

		</div>

	</div>



	<div class="modal fade" id="editDiscountModal" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><i class="fa fa-edit"></i> Edit Product Discount</h4>
				</div>
				<form action="" method="post">
					<div class="modal-body" style="margin-left:30px;margin-right:30px ">

						<div class="form-group">
							<label for="">Product Name</label>
							<input type="text" class="form-control" required id="modal_pname" name="modal_pname" />
						</div>

						<input type="hidden" name="modal_pnumber" id="modal_pnumber" />

						<div class="form-group">
							<label for="">Discount</label>
							<input type="text" class="form-control" id="modal_discount" autocomplete="off" name="modal_discount" />
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fas fa-remove-sign"></i> Close</button>

						<button type="submit" class="btn customize-abs-btn" name="updateDiscountBtn" id="updateDiscountBtn" data-loading-text="Loading...">
							<i class="fas fa-check-circle"></i> Give Discount</button>
					</div> <!-- /modal-footer -->
				</form>
			</div> <!-- /modal-content -->
		</div> <!-- /modal-dailog -->
	</div>

	<div class="modal fade" id="clearDebtModal" role="dialog">

		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<form class="form-horizontal" onsubmit="return clearCustomerDebt();" novalidate method="POST">
					<h4 class="modal-title p-3"><i class="fas fa-credit-card"></i> Customer Payments</h4>

					<div class="modal-body" style="margin-left:30px;margin-right:30px ">

						<div class="form-group">
							<label for="">Name</label>
							<input type="text" class="form-control" required id="custname" name="custname">
						</div>

						<input type="hidden" name="custid" id="custid" />

						<div class="form-group">
							<label for="">Credit Amount</label>
							<input type="text" class="form-control" disabled id="custamt" name="custamt">
						</div>

						<div class="form-group">
							<label for="">Amount to pay</label>
							<input type="text" class="form-control" required id="custtopay" name="custtopay">
						</div>

						<div class="form-group">
							<label for="">Payment Method</label>
							<select name="debt_paymethod" id="debt_paymethod" required class="form-control">
								<option selected value="Cash">Cash</option>
								<option value="Bank/Internet Transfer">Bank/Internet Transfer</option>
							</select>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-default close" data-dismiss="modal"> <i class="fas fa-remove-sign"></i> Cancel</button>

						<button type="submit" class="btn" style="background-color: #0a011a;color:white" name="payDebtBtn" id="payDebtBtn" data-loading-text="Loading..."> <i class="fas fa-ok-sign"></i> Pay Now</button>
					</div> <!-- /modal-footer -->
				</form> <!-- /.form -->
			</div> <!-- /modal-content -->
		</div> <!-- /modal-dailog -->
	</div>

	<script>
		$(document).ready(function() {
			loadCartAfterReload()

			window.addEventListener('afterprint', function() {
				window.open("make_sales", "_self");
			})
		});
	</script>


	<?php include "../partials/footer.php"; ?>