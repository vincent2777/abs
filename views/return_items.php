<?php
include "../partials/header.php";
include "../partials/sidebar.php";

?>


<div class="main-panel">
	<div class="content-wrapper">
		<ol class="breadcrumb">
			<li><a href="../dashboard">Home/ </a></li>
			<li class="active">Return Items</li>
		</ol>
		<div class="row justify-content-center mt-1 bg-white p-3 shadow-sm">
			<div class="col-md-12 mx-auto mb-5">

				<div class="panel panel-default">

					<div class="panel-body">

						<center>
							<?php


							if (!empty($_GET["action"]) && $_GET["action"] == "clear_rcart") {
								unset($_SESSION["rproducts"]);
								unset($_SESSION["returns"]);
								echo "<div class='alert alert-success'> <b>All Products cleared..You can now make new returns<b/> </div><br>";
								echo "<script>window.open('return_items','_self'); </script>";
							}

							if (isset($_POST["returnItemsBtn"])) {

								unset($_SESSION["rproducts"]);
								unset($_SESSION["returns"]);
								$inv_no = $_POST["inv_number"];

								//check eligiblility of return
								$sql4 = mysqli_query($connect, "SELECT * FROM returned_receipts WHERE invoice_number='$inv_no'");
								if (mysqli_num_rows($sql4) > 0) {
									//returns is prohibited on this invoice

									echo "<div class='alert alert-danger'>
									<i class='fas fa-info'></i> Oopsss! Returns has been Disabled on this Invoice.</div>";
								} else {

									# add products in cart 
									if (isset($_POST["inv_number"])) {

										foreach ($_POST as $key => $value) {
											$rproduct[$key] = filter_var($value, FILTER_SANITIZE_STRING);
										}

										$statement = $con->prepare("SELECT product_id,product_name,sold_at_price,quantity,product_discount FROM sold_products WHERE invoice_number=?");
										$statement->bind_param('s', $_POST["inv_number"]);
										$statement->execute();
										$statement->store_result();
										$statement->bind_result($product_id, $product_name, $product_price, $product_qty, $product_discount);
										$num_of_rows = $statement->num_rows;

										if ($num_of_rows > 0) {

											while ($statement->fetch()) {

												$rproduct["product_name"] = $product_name;
												$rproduct["product_qty"] = $product_qty;
												$rproduct['product_id'] = $product_id;
												$rproduct['product_discount'] = $product_discount;
												$rproduct["product_price"]  = $product_price;
												$rproduct["invoice_number"] = $inv_no;

												if (isset($_SESSION["rproducts"])) {

													if (isset($_SESSION["rproducts"][$rproduct['product_id']])) {
														$_SESSION["rproducts"][$rproduct['product_id']]["product_qty"] = $product_qty;
													} else {
														$_SESSION["rproducts"][$rproduct['product_id']] = $rproduct;
													}
												} else {
													$_SESSION["rproducts"][$rproduct['product_id']] = $rproduct;
													$_SESSION["returns"] = 1;
												}
											}


											echo "<div class='alert alert-success' style='font-size:17px;'>
										<i class='fas fa-check-circle'> </i> <b>Invoice Found</b> <a class='btn customize-abs-btn' href='#return-items-holder'>Click here </a> to Continue the Return process </div>";
										} else {
											echo "<div class='btn btn-danger'>Invoice Number not found</div>";
										}
									}
								}
							}

							?>
						</center>

						<div class="row justify-content-center">



							<div class="col-md-5">

								<div class="alert alert-success">
									<p>
										<b>Kindly note that Returns can only be Initiated once for a Particular Invoice.</b>
									</p>
								</div>
								<div class="card shadow p-3 rounded-0">



									<form style="padding: 30px;" method="post">

										<fieldset>
											<div class="form-group">
												<label for="username" class="control-label">Receipt/Invoice Number</label>
												<div class="col-sm-12">
													<input type="text" autocomplete="off" class="form-control" required id="inv_number" name="inv_number" placeholder="Invoice Number" />
												</div>
											</div>

											<br>

											<div class="form-group">


												<center>
													<button type="submit" style="margin-top: 10px;" name="returnItemsBtn" class="btn btn-success btn-lg btn-block">
														<i class="fas fa-log-in"></i> Retrieve </button>

												</center>
											</div>
										</fieldset>
									</form>

								</div>
							</div>
						</div>

					</div>
				</div>
			</div>

		</div>



		<?php

		if (isset($_POST["submitNow"])) {
			$new_price = $_POST["new_price"];
			$invoice_no = $_POST["invoice_no"];

			//COPY THE OLD DATA TO the returned receipt TBL
			$sql3 = "INSERT INTO returned_receipts (vat_amount,store_id,customer_type,cashpayment_amt,bankpayment_amt,
			cashier,customer_id,customer_name,customer_phone,customer_address,order_date,order_time,invoice_number,
			product_name,product_id,barcode_id,quantity,sold_at_price,expected_sale_price,total_amount,paid_amount,
			balance_amount,product_discount,payment_type,payment_method)
			SELECT vat_amount,store_id,customer_type,cashpayment_amt,bankpayment_amt,
					cashier,customer_id,customer_name,customer_phone,customer_address,order_date,order_time,invoice_number,
					product_name,product_id,barcode_id,quantity,sold_at_price,expected_sale_price,total_amount,paid_amount,
					balance_amount,product_discount,payment_type,payment_method 
			FROM sold_products
			WHERE invoice_number = '$invoice_no'";
			$query3 = mysqli_query($connect, $sql3);

			$sql5 = mysqli_query($connect, "DELETE FROM sold_products WHERE invoice_number='$invoice_no'");


			foreach ($_SESSION["rproducts"] as $rproduct) {

				$product_name = $rproduct["product_name"];
				$product_price = $rproduct["product_price"];
				$product_number = $rproduct["product_id"];
				$product_qty = $rproduct["product_qty"];
				$invoice_no = $rproduct["invoice_number"];
				$product_discount = $rproduct["product_discount"];

				//get expected price
				$ex_sql = mysqli_query($con, "SELECT * FROM product WHERE product_id='$product_number'") or die(mysqli_error($con));
				$exrow = mysqli_fetch_array($ex_sql);
				$expected_sale_price = $exrow["product_price"] * $product_qty;


				//fetch all sold products with the invoice number
				//grab the transaction date
				//delete the transaction from the sold products table
				//save a copy of the new transaction in the retuned receipts table

				// INSERT THE NEW PRODUCTS INTO TABLE
				$sql = mysqli_query($con, "INSERT INTO sold_products 
					(vat_amount,store_id,customer_type,cashpayment_amt,bankpayment_amt,cashier,customer_id,
					customer_name,customer_phone,customer_address,order_date,order_time,invoice_number,
					product_name,product_id,barcode_id,quantity,sold_at_price,expected_sale_price,total_amount,
					paid_amount,balance_amount,product_discount,payment_type,payment_method) 
				SELECT vat_amount,store_id,customer_type,'$new_price','0',cashier,customer_id,
					customer_name,customer_phone,customer_address,order_date,order_time,invoice_number,
					'$product_name','$product_number','$product_number','$product_qty',sold_at_price,
					'$expected_sale_price','$new_price','$new_price',0,product_discount,payment_type,
					payment_method FROM returned_receipts WHERE product_id='$product_number' AND invoice_number='$invoice_no'") or die(mysqli_error($con));

				//we need to update the qty remaining for each product that has been returned
				//compare quantity

				$sql7 = mysqli_query($con, "SELECT * FROM returned_receipts WHERE invoice_number='$invoice_no' AND product_id='$product_number'") or die(mysqli_error($con));
				$row7 = mysqli_fetch_array($sql7);
				$old_quantity = $row7["quantity"];

				//e.g if 1 < 2
				$newQtyRem = 0;

	
				if ($product_qty < $old_quantity) {

					$newQtyRem = $old_quantity - $product_qty;
					$sql6 = mysqli_query($connect, "UPDATE product SET quantity_rem=quantity_rem + $newQtyRem WHERE product_id='$product_number'");
				}
			}

			if ($sql) {

				unset($_SESSION["rproducts"]);
				unset($_SESSION["returns"]);

				echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Item Return has been completed and Sales History has been updated. </div>";
			} else {
				echo "<div class='alert alert-danger'><i class='fas fa-info'></i> Oopsss! An error occured..Please try again later.</div>";
			}
		}
		if (isset($_SESSION["rproducts"])) { ?>

			<div class="row justify-content-center mt-5 bg-white p-3 shadow-sm">
				<div class="col-md-12 mx-auto mb-5">

					<div class="panel panel-default">

						<div class="panel-body" id="return-items-holder">

							<form method="POST" id="makeSalesForm">

								<table class="table table-striped table-sm" id="returnscart-table" style="font-size:11px !important">
									<thead id="table-head">
										<tr>
											<th>Item</th>
											<th>Price</th>
											<th>Qty</th>
											<th>Subtotal</th>
											<th></th>
										</tr>
									</thead>
									<tbody id="return-cart-items">

									</tbody>
								</table>

								<input type="hidden" name="price_paid" id="price_paid">
								<input type="hidden" name="new_price" id="new_price">

								<div style="margin-top: 70px;margin-left: 15px" id="calculations-holder">

									<div class="row" style="text-align: left;font-size:14px">
										<div class="col-md-9">
											<strong class="mr-auto">Paid: </strong>
										</div>
										<div class="col">
											<span id="showSubTotal"><?php echo $currency; ?>
												<span id="currency_holder_subtotal">0.00</span>
											</span>
										</div>

									</div>

									<div class="row" style="text-align: left;font-size:14px">
										<div class="col-md-9">
											<strong>Discount: </strong>
										</div>
										<div class="col">
											<?php echo $currency; ?>
											<span id="discountTotal">0.00</span>
										</div>
									</div>


									<div class="row" style="text-align: left;font-size:14px">
										<div class="col-md-9">
											<strong>New Total: </strong>
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

											<strong id="change_or_balance">Change:</strong>
										</div>
										<div class="col">
											<?php echo $currency; ?>

											<strong><span id="calcTotalChange">0.00</span>
											</strong>
										</div>
									</div>


									<div class="row mt-5 ml-1">
										<div class="d-flex justify-content-end">

											<div class="form-group">
												<a href="return_items?action=clear_rcart" name="clearCart" id="clearCart" class="btn btn-warning mr-5 btn-block">
													<i class="fas fa-trash"></i> Clear
												</a>
											</div>

											<div class="form-group">
												<button type="submit" name="submitNow" id="submitNow" style="width: 200px;" class="btn btn-success">Submit
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
			</div>

			<script>
				$(document).ready(function() {
					loadReturnsAfterReload();
				})
			</script>

		<?php } ?>


	</div>
</div>
</div>
</div>
</div>



<script src="<?php echo $pageUrl; ?>script/returns.js"></script>

<?php include "../partials/footer.php"; ?>