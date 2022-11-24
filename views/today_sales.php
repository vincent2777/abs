<?php
include "../partials/header.php";
include "../partials/sidebar.php";

?>
<div class="main-panel">
	<div class="content-wrapper">
		<div class="row p-2" style="background-color: white !important;">
			<div class="col-md-12">

				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="page-heading">
							<h3 class="p-2">Today Sales</h3>
						</div>
					</div> <!-- /panel-heading -->

					<div class="panel-body">

						<div class="alert alert-info w-75" style="border-left: 5px solid blue;">
							<p><b>NB: - </b> 1. Items that have the same invoice number and Part payment status are designed in green colors
								<br>
							</p>
						</div>

						<?php

						if (isset($_POST["completeSaleBtn"])) {

							$invoice_id = $_POST["modal_invoice_id"];
							$total = $_POST["modal_total"];
							$apaid = intval($_POST["modal_apaid"]);
							$balance = intval($_POST["modal_balance"]);
							$new_paymethod = $_POST["new_paymethod"];
							$final_paid_amount = $apaid + $balance;
							$payment_method = "";
							$payMethod = "";
							$pay_amt = 0;


							$getPayMethod = mysqli_query($con, "SELECT payment_method FROM sold_products WHERE invoice_number='$invoice_id'");
							while ($data = mysqli_fetch_array($getPayMethod)) {

								$payment_method = $data["payment_method"];
							}

							if ($new_paymethod == "Cash" && $payment_method == "Cash") {
								$payMethod = $payment_method;
								$pay_amt = $balance;

								$sql1 = "UPDATE sold_products SET 
								balance_amount='0', payment_method='$payMethod', 
								cashpayment_amt= cashpayment_amt + $pay_amt, 
								payment_type='Full Payment',
								paid_amount = '$final_paid_amount'
								WHERE invoice_number='$invoice_id'";
								$query1 = mysqli_query($con, $sql1) or die(mysqli_error($con));

								if ($query1) {
									echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Payment status for Sales invoice #$invoice_id has been updated.</div>";
								} else {
									echo "<div class='alert alert-danger'><i class='fas fa-info'></i>Oops! An error occured.. Please try again later.</div>";
								}
							} else if ($new_paymethod == "Bank/Internet Transfer" && $payment_method == "Cash") {
								$payMethod = $payment_method . ", Bank/Internet Transfer";
								$pay_amt = $balance;

								$sql1 = "UPDATE sold_products SET 
								balance_amount='0', payment_method='$payMethod', 
								bankpayment_amt = bankpayment_amt + $pay_amt,
								payment_type='Full Payment',
								paid_amount = '$final_paid_amount'
								WHERE invoice_number='$invoice_id'";
								$query1 = mysqli_query($con, $sql1) or die(mysqli_error($con));


								if ($query1) {
									echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Payment status for Sales invoice #$invoice_id has been updated.</div>";
								} else {
									echo "<div class='alert alert-danger'><i class='fas fa-info'></i>Oops! An error occured.. Please try again later.</div>";
								}
							} else if ($new_paymethod == "Bank/Internet Transfer" && $payment_method == "Bank/Internet Transfer") {
								$payMethod = $payment_method;
								$pay_amt = $balance;

								$sql1 = "UPDATE sold_products SET 
								balance_amount='0', payment_method='$payMethod', 
								bankpayment_amt = bankpayment_amt + $pay_amt,
								payment_type='Full Payment',
								paid_amount = '$final_paid_amount'
								WHERE invoice_number='$invoice_id'";
								$query1 = mysqli_query($con, $sql1) or die(mysqli_error($con));

								if ($query1) {
									echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Payment status for Sales invoice #$invoice_id has been updated.</div>";
								} else {
									echo "<div class='alert alert-danger'><i class='fas fa-info'></i>Oops! An error occured.. Please try again later.</div>";
								}
							} else if ($new_paymethod == "Cash" && $payment_method == "Bank/Internet Transfer") {
								$payMethod = $payment_method . ", Cash";
								$pay_amt = $balance;

								$sql1 = "UPDATE sold_products SET 
								balance_amount='0', payment_method='$payMethod', 
								cashpayment_amt= cashpayment_amt + $pay_amt, 
								payment_type='Full Payment',
								paid_amount = '$final_paid_amount'
								WHERE invoice_number='$invoice_id'";
								$query1 = mysqli_query($con, $sql1) or die(mysqli_error($con));


								if ($query1) {
									echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Payment status for Sales invoice #$invoice_id has been updated.</div>";
								} else {
									echo "<div class='alert alert-danger'><i class='fas fa-info'></i>Oops! An error occured.. Please try again later.</div>";
								}
							} else {
								$pay_method = $new_paymethod;
								$pay_amt = 0;
							}

						?>
							<script>
								window.open('today_sales', '_self');
							</script>
						<?php
						}
						?>

						<script type="text/javascript">
							$(document).ready(function() {
								$('#sales_table').DataTable({
									dom: 'lBfrtip',
									"aaSorting": [],
									"lengthMenu": [
										[10, 25, 50, -1],
										[10, 25, 50, "All"]
									]
								});
							});
						</script>
						
						<div class="overflow-x:auto">
							<table class="display table table-striped" id="sales_table">
								<thead>
									<tr>
										<th>Date</th>
										<th>Invoice</th>
										<th>Type</th>
										<th>Customer Data</th>
										<th>Cash</th>
										<th>Bank</th>
										<th>Total Amount</th>
										<th>Discount</th>
										<th>Quantity</th>
										<th>Unit</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$count_array = array();
									$today = date("Y-m-d");
									$current_user = $_SESSION["user"];

									if ($_SESSION["role"] == "admin" || $_SESSION["role"] == "manager" || $_SESSION["role"] == "accountant") {
										$sql = mysqli_query($con, "SELECT order_date,invoice_number,payment_type,
						customer_name,customer_phone, total_amount,cashpayment_amt,bankpayment_amt, product_discount,SUM(quantity) as total_qty, SUM(product_discount) as total_discount FROM sold_products WHERE order_date='$today' GROUP BY invoice_number ORDER BY order_id  DESC") or die(mysqli_error($con));
									} else {
										$sql = mysqli_query($con, "SELECT order_date,invoice_number,payment_type,cashier,
						customer_name,customer_phone, total_amount,cashpayment_amt,bankpayment_amt, product_discount,SUM(quantity) as total_qty, SUM(product_discount) as total_discount FROM sold_products WHERE cashier='$current_user' AND order_date='$today' GROUP BY invoice_number ORDER BY order_id  DESC") or die(mysqli_error($con));
									}

									while ($fetch = mysqli_fetch_array($sql)) {

										array_push($count_array, $fetch["invoice_number"]);

										//get number of occurence
										$tmp = array_count_values($count_array);
										$cnt = $tmp[$fetch["invoice_number"]];
										if ($cnt > 0 && $fetch["payment_type"] == "Part Payment") {
											echo "<tr style='color: rgba(255,0,0,0.7)'>";
										} else {
											echo "<tr>";
										}

										echo "<td>" . $fetch["order_date"] . "</td>";
										echo "<td>" . $fetch["invoice_number"] . "</td>";
										echo "<td>" . $fetch["payment_type"] . "</td>";
										echo "<td>" . $fetch["customer_name"] . " <br> " . $fetch["customer_phone"] . "</td>";
										echo "<td>" . number_format($fetch["cashpayment_amt"], 2) . "</td>";
										echo "<td>" . number_format($fetch["bankpayment_amt"], 2) . "</td>";
										echo "<td>" . number_format($fetch["total_amount"], 2) . "</td>";
										echo "<td>" . number_format($fetch["total_discount"], 2) . "</td>";
										echo "<td>" . $fetch["total_qty"] . "</td>";
										echo "<td>" . ucwords($fetch["measurement_unit"]) . "</td>";

										if ($fetch["payment_type"] == "Part Payment") {
									?>
											<td>

												<?php
												if ($_SESSION["role"] == "owner" || $_SESSION["role"] == "manager") {
												?>
													<a class="btn btn-success h-75" id="reprint_invoice?slipno=<?php echo $fetch["invoice_number"]; ?>" onclick="reprintInvoice(this.id);">
														<i class="mdi mdi-printer"></i>
													</a>
												<?php } ?>

											</td>
											</tr>


										<?php } else { ?>
											<td>
												<a class="btn customize-abs-btn p-2" id="reprint_invoice?slipno=<?php echo $fetch["invoice_number"]; ?>" onclick="reprintInvoice(this.id);">
													<i class="mdi mdi-printer"></i>
												</a>

											</td>
											</tr>
									<?php
										}
									} ?>

								</tbody>

							</table>
						</div>
					</div> <!-- /panel-body -->
				</div> <!-- /panel -->
			</div> <!-- /col-md-12 -->
		</div> <!-- /row -->
	</div>

	<div class="modal fade" id="editSalesModal" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form class="form-horizontal" action="today_sales" method="POST">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><i class="fa fa-edit"></i> Complete Sales Information</h4>
					</div>

					<div class="modal-body" style="margin-left:30px;margin-right:30px ">

						<div class="form-group">
							<label for="">Product Name</label>
							<input type="text" class="form-control" readonly id="modal_pname" name="modal_pname">
						</div>

						<div class="form-group">
							<label for="">Quantity</label>
							<input type="text" class="form-control" readonly id="modal_qty" name="modal_qty">
						</div>

						<input type="hidden" name="modal_invoice_id" id="modal_invoice_id" />

						<div class="row">
							<div class="col-md-5">
								<div class="form-group">
									<label for="">Total Amount</label>
									<input type="text" class="form-control" readonly id="modal_total" autocomplete="off" name="modal_total">
								</div>
							</div>
							<div class="col-md-1">
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<label for="">Amount Paid</label>
									<input type="text" class="form-control" readonly id="modal_apaid" autocomplete="off" name="modal_apaid">
								</div>
							</div>
						</div>


						<div class="row">
							<div class="col-md-5">
								<div class="form-group">
									<label for="">Amount to Balance</label>
									<input type="text" class="form-control" readonly id="modal_balance" autocomplete="off" name="modal_balance">
								</div>
							</div>
							<div class="col-md-1">
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<label for="">Previous Payment Method</label>
									<input type="text" class="form-control" readonly id="modal_paymethod" autocomplete="off" name="modal_paymethod">
								</div>
							</div>
						</div>

						<div class="form-group">
							<label for="">New Payment Method</label>
							<select class="form-control" name="new_paymethod" id="new_paymethod" required="">
								<option value="" selected="">-Select Payment Method-</option>
								<option value="Cash">Cash</option>
								<option value="Bank/Internet Transfer">Bank/Internet Transfer</option>
							</select>
						</div>
						<div class="alert alert-info" style="border-left: 5px solid blue;">
							<b>Make Sure you have collected the complete balance before clicking on the Complete Payment Button</b>
						</div>

					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">
							&times; Close</button>

						<button type="submit" class="btn customize-abs-btn" name="completeSaleBtn" id="completeSaleBtn" data-loading-text="Loading..."> <i class="fas fa-check-circle"></i> Complete Payment</button>
					</div> <!-- /modal-footer -->
				</form> <!-- /.form -->
			</div> <!-- /modal-content -->
		</div> <!-- /modal-dailog -->
	</div>

	<script>
		function reprintInvoice(loc) {
			window.open(loc, 'targetWindow',
				`toolbar=no,
                                    location=no,
                                    status=no,
                                    menubar=no,
                                    scrollbars=yes,
                                    resizable=yes,
                                    width=500,
                                    height=500`);
			return false;
		}
	</script>

	<script src="../script/edit_sales.js"></script>

	<?php include "../partials/footer.php"; ?>