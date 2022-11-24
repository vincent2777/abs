<?php
include_once("includes/db_connect.php");
include("includes/config.inc.php");
include "includes/check_role_header.php";
include 'includes/functions.php';
error_reporting(0);?>

<style type="text/css">
	@media print {

		.hidden-print,
		.hidden-print * {
			display: none !important;
		}
	}

	@media print {

		html,
		body {
			width: 100mm;
			height: 297mm;
		}
	}

	@media print {
		@page {
			size: auto;
			margin: 0mm 0mm 0mm 0mm;
		}
	}

	@media print {

		.panel-heading,
		.panel-heading * {
			display: none !important;
		}

		.footer,
		.footer * {
			display: none !important;
		}
	}

	@media print {

		.footer,
		.footer * {
			display: none !important;
		}
	}

	.cust_results {
		box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.1);
		width: inherit;
		background-color: white;
		height: auto;
		padding: 15px;
		color: #0a011a;
		display: none;
	}

	.cust_results a {
		font-size: 13px;
		text-decoration: none;
	}
</style>


<div class="container">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading"> <i class="fas fa-calendar"></i> Complete Return Process</div>

			<div class="panel-body">

				<?php
				if (isset($status)) {
					echo "<div class='alert alert-success'> Sales have been processed successfully </div><br>";
				}

				//create session array to store new discounts given at checkout
				if (isset($_POST["modal_product_number"])) {
					$prod_id = $_POST["modal_product_number"];
					$prod_name = $_POST["modal_pname"];
					$prod_discount = $_POST["modal_discount"];

					checkoutDiscountController($prod_name, $prod_id, $prod_discount);
					echo "<div class='alert alert-success'> $currency$prod_discount Discount has been given for <b>" . strtoupper($prod_name) . "</b> </div><br>";
				}

				if (!empty($_GET["action"]) && $_GET["action"] == "clear_cart") {
					unset($_SESSION["products"]);
					unset($_SESSION["cart_discounts"]);
					unset($_SESSION["products_r"]);
					echo "<div class='alert alert-success'> <b>All Products cleared..You can now make new sales<b/> </div><br>";
				}

				$paymethod = array();

				if (isset($_POST["submitNow"])) {

					if(intval($_POST["cash_payment"]) > 0){
						array_push($paymethod,"Cash");
					}
					
					if(intval($_POST["bank_payment"]) > 0){
						array_push($paymethod,"Bank/Internet Transfer");
					}
					
					$cash_payment_amt = $_POST["cash_payment"];
					$bank_payment_amt = $_POST["bank_payment"];
					$paytype = $_POST["paytype"];
					$user_id = $_SESSION['user'];
					$customer_phone = $_POST["customer_phone"];
					$customer_name = $_POST["customer_name"];
					$customer_address = $_POST["customer_address"];
					$today = date('y/m/d');
					$order_number = date('yd') . rand(2000, 10000);
					$part_payment = intval($_POST["part_payment_amount"]);
					$totalsale_discount = $_POST["totalsale_discount"];

					//get invoice design template
					$invoice_sql = mysqli_query($conn, "SELECT * FROM invoice_template") or die(mysqli_error($conn));
					$invoiceRow = mysqli_fetch_array($invoice_sql);

					$bname = $invoiceRow["business_name"];
					$bimage = $invoiceRow["business_logo_path"];
					$bslogan = $invoiceRow["business_slogan"];
					$binfo = $invoiceRow["additional_info"];
					$baddress = $invoiceRow["business_address"];
					$bwebsite = $invoiceRow["business_website"];
					$bphone = $invoiceRow["business_phone"];

				?>
					<script type="text/javascript">
						$(document).ready(function() {
							window.print();
						})
					
						$(document).ready(function() {
							document.getElementById("printBtn").addEventListener('click', function() {
								window.print();
							})
						})
					</script>

					<div style="width: 80mm;max-width: 80mm;">

						<center>
							<img src="<?php echo $bimage; ?>" style="width: 70px;height: 70px">
							<br>
							<p>
								<span style="font-size: 25px"><?php echo $bname; ?></span>
								<br>
								<span style="font-size: 15px"><?php echo $bslogan; ?></span>
							</p>

						</center>
						<p style="font-size: 15px">
							<b>Invoice No:</b> <?php echo $order_number; ?>
							<br>
							Date: <?php echo date('d-m-Y h:i:s'); ?>
							<?php

							if (!empty($customer_name) || !empty($customer_phone)) {
								echo "Customer: <span>$customer_name</span><br>";
								echo "Mobile: <span>$customer_phone</span><br>";
							}

							?>
						</p>
						<table class="table table-responsive" style="border:0px;font-size: 20px;width:120mm">
							<thead>
								<tr>
									<th>Desc.</th>
									<th>Qty.</th>
									<th>Price</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$index = 0;
								$total_to_pay = 0;
								$balance_amount = 0;

								foreach ($_POST["total_payable"] as $newprice) {
									$total_to_pay += $newprice;
								}

								//remove comma if only one payment type is selected
								if(sizeof($paymethod) > 1){
									$paymethod = implode(", ",$paymethod);
								}else{
									$paymethod = implode("",$paymethod);
								}
								

								foreach ($_SESSION["products"] as $product) {
									$product_name = $product["product_name"];
									$product_price = $product["product_price"];
									$product_number = $product["product_id"];
									$product_qty = $product["product_qty"];

									$expected_sale_price = sprintf("%01.2f", ($product_price * $product_qty));
									$amount_per_product = $_POST['total_payable'][$index];
									$discountPerItem = $expected_sale_price - $amount_per_product;

									//to get balance, substract total amount for sales($total_to_pay) from part payment
									if ($part_payment == 0) {
										$part_payment = $total_to_pay;
									} else {
										$balance_amount = $total_to_pay - $part_payment;
										$part_payment = $part_payment;
									}

									$sql = mysqli_query($conn, "INSERT INTO sold_products 
									(cashpayment_amt,bankpayment_amt,cashier,customer_name,customer_phone,customer_address,order_date,invoice_number,product_name,product_id,quantity,sold_at_price,expected_sale_price,total_amount,paid_amount,balance_amount,product_discount,payment_type,payment_method) 
									VALUES('$cash_payment_amt','$bank_payment_amt','$user_id','$customer_name','$customer_phone','$customer_address','$today','$order_number','$product_name','$product_number','$product_qty','$amount_per_product','$expected_sale_price','$total_to_pay','$part_payment','$balance_amount','$discountPerItem','$paytype','$paymethod')") or die(mysqli_error($conn));

									$getTotalPerItem = "";
									$amount_to_pay = 0;

									//add customers to DB
									$add = mysqli_query($conn, "INSERT INTO customers (cust_name,cust_phone,cust_address)
											VALUES('$customer_name','$customer_phone','$customer_address')");

									//check if dicount for product is in art_discount session
									//else retrieve discount from price level db where level category is regular
									$fetch_productSql = mysqli_query($conn, "SELECT quantity FROM product WHERE product_id='$product_number'") or die(mysqli_error($conn));

									while ($getQuantity = mysqli_fetch_array($fetch_productSql)) {
										$newQuantity = $getQuantity["quantity"];
										$newQuantity_rem = $newQuantity - $product_qty;
										$update_productSql = mysqli_query($conn, "UPDATE product SET quantity_rem='$newQuantity_rem' WHERE product_id='$product_number'") or die(mysqli_error($conn));
									}

								?>
									<tr>
										<td style="width: 90px;max-width: 90px"><?php echo $product_name; ?></td>
										<td style="width: 75px;word-break: break-all;"><?php echo $product_qty; ?></td>
										<td style="width: 75px;"><?php echo $_POST['total_payable'][$index]; ?></td>
										<td>&nbsp;</td>
									</tr>
								<?php
									unset($_SESSION["products"]);
									unset($_SESSION["cart_discounts"]);
									$index++;
								}
								$status = 1;
								?>
								<tr>
									<td></td>
									<td>Total: <br> Paid: <br> Bal.: <br> Discount:</td>
									<td>
										<?php echo $currency . number_format($total_to_pay,2);  ?>
										<br>
										<?php echo $currency . number_format($part_payment,2);  ?>
										<br>
										<?php echo $currency . number_format($balance_amount,2);  ?>
										<br>
										<?php echo $currency . number_format($totalsale_discount,2);  ?>
									</td>
								</tr>
							</tbody>

						</table>
						<hr style="margin-top:-20px">
						<p style="font-size: 11px;text-align: center;margin-top:-17px;margin-left:-40px">

							<?php echo $binfo; ?>
							<br>
							Customer Care: <?php echo $bphone; ?>
							<br>
							<?php echo $baddress; ?>
							<br>
							<?php echo $bwebsite; ?>

						</p>

						<?php
						if ($sql) {
							echo "<button class='btn btn-warning hidden-print' id='printBtn'>
							<i class='fas fa-print'></i> Print</button><br>";
						}
						?>
					</div>

				<?php

				} else {

				?>
					<?php
					if (isset($_SESSION["products_r"]) && count($_SESSION["products_r"]) > 0) {
						$total = 0;
						$list_tax = '';
						$total_discount = 0;
					?>
						<form method="post">
							<table class="table table-striped table-responsive" id="shopping-cart-results" style="font-family: 'Oxygen';font-size:14px">
								<thead>
									<tr>
										<th>Product</th>
										<th>Price</th>
										<th>Quantity</th>
										<th>Subtotal</th>
										<th>Discount</th>
										<th>Price Level</th>
										<th>Payable</th>
									</tr>
								</thead>
								<tbody>

									<?php

									$cart_box = '';

									print_r($_SESSION["products_r"]);
									foreach ($_SESSION["products_r"] as $rproduct) {

										$product_name = $rproduct["product_name"];
										$product_price = $rproduct["product_price"];
										$product_no = $rproduct["product_id"];
										$product_qty = $rproduct["product_qty"];
																				
										$sub_total = $product_price * $product_qty;
										$price_level = "";
										$price_level_discount = 0;

										echo $product_name . $product_no;
										
										$sql1 = "SELECT * FROM sold_products WHERE product_id='$product_no'";
										$result1 = mysqli_query($con, $sql1);
                                        
										while ($rowdata = mysqli_fetch_array($result1)) {

											$getDiscount = $rowdata["product_discount"];
											
											// if(!empty($getDiscount)){
											// 	$price_level_discount = $getDiscount;
											// }else{
											// 	$price_level_discount = 0;
											// }
											//calculate discount for each item
											$total_discount += $price_level_discount;

											//check if quantity is within regular price level
											$sql_regular = "SELECT * FROM price_level_settings WHERE pricelevel_category='Regular'";
											$result_regular  = mysqli_query($connect, $sql_regular);
											$rdata = mysqli_fetch_array($result_regular);
											$reg_qty_above = $rdata["price_level_qty_above"];
											$reg_qty_below = $rdata["price_level_qty_below"];
											$reg_price = $rdata["price_level_amount"];

											//query wholesale 
											$sql_ws = "SELECT * FROM price_level_settings WHERE pricelevel_category='Wholesale'";
											$result_ws  = mysqli_query($connect, $sql_ws);
											$wdata = mysqli_fetch_array($result_ws);
											$ws_qty_above = $wdata["price_level_qty_above"];
											$ws_qty_below = $wdata["price_level_qty_below"];
											$ws_price = $wdata["price_level_amount"];

											//query employee 
											$sql_em = "SELECT * FROM price_level_settings WHERE pricelevel_category='Employee'";
											$result_em  = mysqli_query($connect, $sql_em);
											$edata = mysqli_fetch_array($result_em);
											$em_qty_above = $edata["price_level_qty_above"];
											$em_qty_below = $edata["price_level_qty_below"];
											$em_price = $edata["price_level_amount"];

											//query sales 
											$sql_s = "SELECT * FROM price_level_settings WHERE pricelevel_category='Sales'";
											$result_s  = mysqli_query($connect, $sql_s);
											$sdata = mysqli_fetch_array($result_s);
											$s_qty_above = $sdata["price_level_qty_above"];
											$s_qty_below = $sdata["price_level_qty_below"];
											$s_price = $sdata["price_level_amount"];

											//query sales 
											$sql_subdist = "SELECT * FROM price_level_settings WHERE pricelevel_category='Sub Distributor'";
											$result_subdist  = mysqli_query($connect, $sql_subdist);
											$sudata = mysqli_fetch_array($result_subdist);
											$subdist_qty_above = $sudata["price_level_qty_above"];
											$subdist_qty_below = $sudata["price_level_qty_below"];
											$subdist_price = $sudata["price_level_amount"];


											//check if regular quantity is zero
											if ($reg_qty_above == 0 || $reg_qty_below == 0) {
												// use normal product price for sales
												$sub_total = $sub_total;
												$price_level = "Regular";

												//if cashier is giving discount at checkout, add the discount
												$price_level_discount = 0;
												if (isset($_SESSION["cart_discounts"])) {

													foreach ($_SESSION['cart_discounts'] as $item) {
														$new_prod_discount = $item['discount'];
														$price_level_discount = $new_prod_discount;
													}
												}
											} elseif ($product_qty >= $reg_qty_above  && $product_qty <= $reg_qty_below) {
												$sub_total = $sub_total;
												$price_level = "Regular";
												$price_level_discount = 0;

												//if cashier is giving discount at checkout, add the discount
												if (isset($_SESSION["cart_discounts"])) {

													foreach ($_SESSION['cart_discounts'] as $item) {
														$new_prod_discount = $item['discount'];
														$get_prod_number = $item["prod_id"];
														//give discount only for that particular product number
														if ($product_number == $get_prod_number) {
															$price_level_discount = $new_prod_discount;
															$sub_total -= $price_level_discount;
															$total_discount += $price_level_discount;
														}
													}
												}
												$total_discount += $reg_price;
											}

											//if quantity is above regular quantity, test for wholesale

											//check if wholesale quantity is zero
											if ($ws_qty_above == 0 || $ws_qty_below == 0) {
												// use normal product price for sales
												$sub_total = $sub_total;
												$price_level = "Wholesale";
												$price_level_discount = 0;
												$total_discount += 0;
											}

											if ($product_qty >= $ws_qty_above && $product_qty <= $ws_qty_below) {
												$sub_total -= $ws_price;
												$price_level = "Wholesale";
												$price_level_discount = $ws_price;
												$total_discount += $ws_price;
											}

											//if quantity is above wholesale quantity, test for employee

											//check if employee quantity is zero
											if ($em_qty_above == 0 || $em_qty_below == 0) {
												// use normal product price for sales
												$sub_total = $sub_total;
												$price_level = "Employee";
												$price_level_discount = 0;
												$total_discount += 0;
											}

											if ($product_qty >= $em_qty_above  && $product_qty <= $em_qty_below) {
												$sub_total -= $em_price;
												$price_level = "Employee";
												$price_level_discount = $em_price;
												$total_discount += $em_price;
											}

											//if quantity is above employee quantity, test for sales

											//check if employee quantity is zero
											if ($s_qty_above == 0 || $s_qty_below == 0) {
												// use normal product price for sales
												$sub_total = $sub_total;
												$price_level = "Sales";
												$price_level_discount = 0;
												$total_discount += 0;
											}

											if ($product_qty >= $s_qty_above  && $product_qty <= $s_qty_below) {
												$sub_total -= $s_price;
												$price_level = "Sales";
												$price_level_discount = $s_price;
												$total_discount += $s_price;
											}

											//if quantity is above employee quantity, test for sub distributor

											//check if employee quantity is zero
											if ($subdist_qty_above == 0 || $subdist_qty_below == 0) {
												// use normal product price for sales
												$sub_total = $sub_total;
												$price_level = "Sub-Distributor";
												$price_level_discount = 0;
												$total_discount += 0;
											}

											if ($product_qty >= $subdist_qty_above  && $product_qty <= $subdist_qty_below) {
												$sub_total -= $subdist_price;
												$price_level = "Sub-Distributor";
												$price_level_discount = $subdist_price;
												$total_discount += $subdist_price;
											}
										}

									?>
										<tr>
											<td><?php echo $product_name; ?></td>
											<td><?php echo number_format($product_price,2); ?></td>
											<td><?php echo $product_qty; ?></td>
											<td><?php echo $currency;
												echo number_format(sprintf("%01.2f", ($product_price * $product_qty))); ?></td>
											<td>
												<?php
												echo $currency;
												echo number_format($price_level_discount,2);

												if ($price_level == "Regular" && $_SESSION["cangive_discount"] == 1) {
													echo " <a href='#editDiscountModal' id='$price_level_discount' data-id='$product_number' class='giveDiscountBtn' data-toggle='modal'><span class='badge bg-warning'> Give Discount</span></a>";
												}

												?>
											</td>
											<td><?php echo $price_level; ?></td>
											<td><?php echo $currency;
												echo number_format(sprintf("%01.2f", ($sub_total))); ?>
												<input type="hidden" name="product_discount[]" value="<?php echo $price_level_discount; ?>">
												<input type="hidden" name="total_payable[]" value="<?php echo $sub_total; ?>">
											</td>
										</tr>


									<?php
										$subtotal = ($product_price * $product_qty);
										$total = ($total + $subtotal);
									}
									$grand_total = $total;
									foreach ($taxes as $key => $value) {
										$tax_amount = round($total * ($value / 100));
										$tax_item[$key] = $tax_amount;
										$grand_total = $grand_total + $tax_amount;
									}

									$sub_total_payable = sprintf("%01.2f", $grand_total);

									$cart_box .= "<span>$currency" . number_format(sprintf("%01.2f", $grand_total)) . "</span>";
									?>

									<tr>


										<td>
											<input type="hidden" id="paid_amount" name="paid_amount">
											<input type="hidden" id="balance_amount" name="balance_amount">
											<input type="hidden" id="totalsale_discount" name="totalsale_discount" value="<?php echo $total_discount; ?>">
										</td>
										<td></td>
										<td>
											<div id="customer_info" style="display: none;">
												<input type="text" id="customer_name" autocomplete="off" name="customer_name" class="form-control" placeholder="Customer Name">
												<div class="cust_results" id="custsearch_results"></div>
												<br>
												<input type="hidden" name="customer_id" id="customer_id">
												<br>
												<input type="text" id="customer_phone" name="customer_phone" class="form-control" placeholder="Phone Number" /> <br>
												<br>
												<input type="text" id="customer_address" name="customer_address" class="form-control" placeholder="Address">
												<br>
												<br>
												<input type="hidden" name="getTotal" id="getTotal">
											</div>

										</td>
										<td></td>
										<td>
											<br>
											<div class="form-check">
												<label for="" class="form-check-label" style="font-size: 14px;">
													<input type="checkbox" value="customer_info" name="" id="sell_to_cust"> Selling to a would-be Regular Customer?
												</label>
											</div>

											<br>
											<select class="form-control" name="paymethod" id="paymethod" required="" onchange="togglePayAmount()">
												<option value="" selected="">-Select Payment Method-</option>
												<option value="Cash">Cash</option>
												<option value="Bank/internet transfer">Bank/Internet Transfer</option>
											</select>

											<script>
												function togglePayAmount(){
													var selected = document.getElementById("paymethod").value;
													if(selected == "Cash"){
														document.getElementById("cash_payment_holder").style.display = "inline-block";
													}else if(selected == "Bank/internet transfer"){
														document.getElementById("bank_payment_holder").style.display = "inline-block";

													}
												}
											</script>

											<br>
											<div class="row">
												<div class="col-md-6" id="cash_payment_holder" style="display: none;">
													<label for="">Cash Amount</label>
												<input type="text" value="0" name="cash_payment" autocomplete="off" id="cash_payment" class="form-control" placeholder="Cash Amount">
												</div>

												<div class="col-md-6" id="bank_payment_holder" style="display: none;">
												<label for="">Bank Amount</label>
												<input type="text" value="0" name="bank_payment" autocomplete="off" id="bank_payment" class="form-control" placeholder="Bank Payment">
												</div>
											</div>
											
											<br>
											<select class="form-control" name="paytype" id="paytype" required="" onchange="toggleInputs(this.value)">
												<option value="" selected="">-Select Payment Type-</option>
												<option value="Part Payment">Part Payment</option>
												<option value="Full Payment">Full Payment</option>
											</select>
											<br>
											<input type="text" value="0" name="part_payment_amount" autocomplete="off" id="part_payment_amount" class="form-control" placeholder="Amount Received" style="display: none;">
											<br>

										</td>
										<td></td>
										<td></td>
									</tr>

								</tbody>
								<tfoot>
									<tr>
										<td><br><br><a href="make_sales" class="btn customize-abs-btn">
											<i class="fas fa-menu-left"></i> Add More Products</a></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td class="view-cart-total">
											<strong>Sub-Total: <span style="padding-left: 30px;float: right"><?php echo $cart_box . ".00"; ?></span></strong>
											<br>
											<strong>Total Discount: <span style="padding-left: 30px;float: right" id="discountTotal"><?php echo number_format($total_discount,2); ?></span></strong>
											<hr>
											<strong style="font-size: 18px">Total: <span style="padding-left: 30px;float: right" id="calcTotalPayable"></span></strong>
											<p id="error-msg" style="display: none;"></p>
											<br>
											<strong style="font-size: 15px">Paid: <span style="padding-left: 30px;float: right" id="calcTotalPaid">0.00</span></strong>
											<br>
											<strong style="font-size: 15px">Balance: <span style="padding-left: 30px;float: right" id="calcTotalBalance">0.00</span></strong>
										</td>
										<td>
											<br>
											<br>
											<br>
											<a href="process_returns?action=clear_cart" name="clearCart" id="clearCart" class="btn btn-warning btn-block">Clear All 
												<i class="fas fa-trash"></i>
											</a>
											
											<br>
											<button type="submit" name="submitNow" id="submitNow" class="btn customize-abs-btn btn-block">Submit 
												<i class="fas fa-check"></i>
											</button>
										</td>
									</tr>

								</tfoot>

							<?php
						} else {
							echo "Your Cart is empty";
						}
							?>

							</table>
						</form>

					<?php  } ?>
			</div>
		</div>
	</div>


	<div class="modal fade" id="editDiscountModal" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form class="form-horizontal" action="process_returns" method="POST">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><i class="fa fa-edit"></i> Edit Product Discount</h4>
					</div>

					<div class="modal-body" style="margin-left:30px;margin-right:30px ">

						<div class="form-group">
							<label for="">Product Name</label>
							<input type="text" class="form-control" required id="modal_pname" name="modal_pname">
						</div>

						<input type="hidden" name="modal_product_number" id="modal_product_number" />

						<div class="form-group">
							<label for="">Discount</label>
							<input type="text" class="form-control" id="modal_discount" autocomplete="off" name="modal_discount">
						</div>

					</div>


					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fas fa-remove-sign"></i> Close</button>

						<button type="submit" class="btn customize-abs-btn" name="updateDiscountBtn" id="updateDiscountBtn" data-loading-text="Loading..."> 
							<i class="fas fa-check-circle"></i> Give Discount</button>
					</div> <!-- /modal-footer -->
				</form> <!-- /.form -->
			</div> <!-- /modal-content -->
		</div> <!-- /modal-dailog -->
	</div>

</div>

<script type="text/javascript">
	

	$(document).ready(function() {
		let subTotal = <?php echo $sub_total_payable; ?>;
		let discountTotal = <?php echo $total_discount; ?>;
		let totalPayable = subTotal - discountTotal;

		$("#customer_name").keyup(function() {

			var query = $("#customer_name").val();
			document.getElementById('custsearch_results').style.display = 'block';

			$.ajax({
				url: 'includes/fetchCustomers.php',
				type: 'post',
				data: {
					query: query
				},
				dataType: 'json',
				success: function(response) {

					if (response.cust_name == undefined) {
						$("#custsearch_results").html("<small>No Customer Found</small> <span class='close-results' style='float:right'><button type='button' class='btn bg-danger text-white'>&times;</button></span>");
						$(".close-results").click(function() {
							document.getElementById('custsearch_results').style.display = 'none';
						})

					} else {
						var getCustID = response.id;
						$("#custsearch_results").html("<a id='cust_search_list' href='#'>" + response.cust_name + "</a> ");
						$("#cust_search_list").click(function() {

							$("#customer_name").val(this.innerHTML);
							document.getElementById('custsearch_results').style.display = 'none';

							//if customer is found and clicked, fetch other information
							$.ajax({
								url: 'includes/fetchCustomers.php',
								type: 'post',
								data: {
									custID: getCustID
								},
								dataType: 'json',
								success: function(res) {
									$("#customer_phone").val(res.cust_phone);
									$("#customer_address").val(res.cust_address);
									$("#customer_id").val(res.cust_id);
								}
							});

						});
					}
				}
			})

		});


		//calculate if total is to be paid in parts
		$("#part_payment_amount").on('keyup', function() {

			//check if input of part payment is > than total payable to avoid -ve values
			if ($("#part_payment_amount").val() > totalPayable) {

				$("#part_payment_amount").css("border", "2px solid red");
				$("#submitNow").css("display", "none");
				$("#error-msg").css("display", "inline-block");
				document.getElementById('error-msg').innerHTML = "<small>Error! The Amount Received <b>CANNOT</b> be greater than the Amount Payable</small>";
				document.getElementById('error-msg').style.color = 'red';

			} else {

				let calcPart = calcWithPartPayment(totalPayable);

				if ($("#part_payment_amount").val() != 0) {
					document.getElementById('calcTotalBalance').innerHTML = numberWithCommas(calcPart) + ".00";
				}

				//hide excess error
				$("#error-msg").css("display", "none");

				$("#submitNow").css("display", "block");
				$("#part_payment_amount").css("border", "none");
				document.getElementById('calcTotalPayable').style.color = 'black';
				document.getElementById('getTotal').value = calcPart;

				//get new paid amount if user is mking part payment
				var x = $("#calcTotalPaid").val();
				var y = $("#part_payment_amount").val();
				var z = $("#calcTotalBalance").val()
				var newPaidAmt = y - x;
				$("#calcTotalPaid").html(numberWithCommas(newPaidAmt) + ".00");

				if (y != "") {
					$("#paid_amount").val(newPaidAmt);
				} else {
					$("#paid_amount").val(x);
				}

				$("#balance_amount").val(z);
			}

		});

		document.getElementById('calcTotalPayable').innerHTML = "$currency" + numberWithCommas(totalPayable) + ".00";
		$("#calcTotalPaid").html(numberWithCommas(totalPayable) + ".00");

		document.getElementById('getTotal').value = totalPayable;

	});


</script>

<script src="script/checkout.js"></script>

</div> <!-- container -->

<script src="http://localhost/assests/plugins/fileinput/js/fileinput.min.js"></script>

<script type="text/javascript" src="http://localhost/DataTables/datatables.min.js"></script>
<script type="text/javascript" src="http://localhost/DataTables/Buttons-1.6.2/js/buttons.bootstrap4.min.js"></script>
<script type="text/javascript" src="http://localhost/DataTables/Buttons-1.6.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="http://localhost/DataTables/Buttons-1.6.2/js/dataTables.buttons.min.js"></script>

<script src="http://localhost/assests/jquery-ui/jquery-ui.min.js"></script>


</body>

</html>