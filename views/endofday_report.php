<?php
include "../partials/header.php";
include "../partials/sidebar.php";;
?>
<script type="text/javascript">
	function filterSeller(selected) {
		if (selected == "all") {
			window.open("endofday_report", "_self");
		} else {
			window.open("endofday_report?filter_by=" + selected, "_self");
		}
	}

	$(document).ready(function() {
		$('#eodreport').DataTable({
			dom: 'lBfrtip',
			buttons: [
				'csv', 'excel'
			],
			"lengthMenu": [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			]
		});
		$('#transfers').DataTable({
			dom: 'lBfrtip',
			buttons: [
				'csv', 'excel'
			],
			"lengthMenu": [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			]
		});
	});
</script>
<style>
	.pay-summary tr td {
		border: none;
		border-collapse: collapse;
	}
</style>

<style type="text/css">
	@media print {

		.hidden-print,
		.hidden-print * {
			display: none !important;
		}
	}

	@media print {
		@page {
			margin: 40px 0px 55px 0px;
		}

	}

	@media print {

		.panel-heading,
		.panel-heading * {
			display: none !important;
		}

	}
</style>


<div class="main-panel">
	<div class="content-wrapper">
		<div class="row rounded-0 shadow-sm p-3 bg-white">
			<div class="col-md-12">

				<div class="panel panel-default" style="border:0px">
					<div class="panel-heading" style="padding: 15px">
						<i class="fas fa-check"></i> End of Day Report

						<span style="float: right;margin-right: 50px">
							<select name="" id="" class="form-control" onchange="filterSeller(this.value)">
								<option selected>-Filter by User</option>

								<?php
								$userssql = mysqli_query($con, "SELECT * FROM users");

								while ($fetchUsers = mysqli_fetch_array($userssql)) {
								?>
									<option value="<?php echo $fetchUsers["username"]; ?>"><?php echo ucwords($fetchUsers["username"]); ?></option>

								<?php
								}
								?>
								<option value="all">-Show All-</option>
							</select>
						</span>
					</div>

					<div class="panel-body">

						<?php

						$today = date("Y-m-d");

						//get returns data
						$check = mysqli_query($con, "SELECT * FROM sold_products WHERE order_date='$today'") or die(mysqli_error($con));


						?>

						<div class="row justify-content-center" style="font-family: 'Times New Roman';">
							<div class="col-md-12">
								<center>
									<?php
									$getData = mysqli_query($con, "SELECT * FROM settings") or die(mysqli_error($con));
									$fetchData = mysqli_fetch_array($getData);
									?>
									<h1><b><?php echo $fetchData["company_name"]; ?></b>
										<br>
										<span style="font-size: 20px;"><?php echo $fetchData["company_address"]; ?></span>
									</h1>
									<h4><?php echo $fetchData["company_phone"]; ?></h4>

									<h2 style="text-decoration: underline;"><b>End Of Day Report</b></h2>

									<br>
								</center>
							</div>
						</div>
						<h2><b>SALES</b></h2>
						<table class="table" id="eodreport">
							<thead>
								<tr>
									<th>Name</th>
									<th>Qty.</th>
									<th>SP</th>
									<th>CP</th>
									<th>Profit</th>
								</tr>
							</thead>
							<tbody>
								<?php

								$today = date("Y-m-d");
								$filter_by = $_GET["filter_by"];

								if (!empty($_GET["filter_by"])) {
									$sql = mysqli_query($con, "SELECT COUNT(product_id),product_id,order_date,product_name,cashier,quantity,sold_at_price
								,SUM(quantity) AS total_qty,SUM(sold_at_price) AS total_price FROM sold_products WHERE cashier='$filter_by' AND order_date='$today'
								GROUP BY product_name ") or die(mysqli_error($con));
								} else {
									$sql = mysqli_query($con, "SELECT COUNT(product_id),product_id,order_date,product_name,cashier,quantity,sold_at_price
									,SUM(quantity) AS total_qty,SUM(sold_at_price) AS total_price FROM sold_products WHERE order_date='$today'
									GROUP BY product_name ") or die(mysqli_error($con));
								}

								while ($fetch = mysqli_fetch_array($sql)) {

									//get cost price
									$pid = $fetch["product_id"];
									$customer_id = $fetch["customer_id"];


									$sql_cost =	 mysqli_query($con, "SELECT * FROM product WHERE product_id='$pid'") or die(mysqli_error($con));
									$data = mysqli_fetch_array($sql_cost);
									$costPrice =  round(intval($data["cost_price"] * $fetch["quantity"]));
									$sellingPrice = $data["product_price"] * $fetch["quantity"];

									echo "<tr>";
									echo "<td>" . $fetch["product_name"] . "</td>";
									echo "<td>" . $fetch["total_qty"] . "</td>";
									echo "<td>$currency" . number_format($sellingPrice, 2) . "</td>";
									echo "<td>$currency" . number_format($costPrice, 2) . "</td>";

									echo "<td>" . number_format($sellingPrice - $costPrice, 2)  . "</td>";

								?>
								<?php

									echo "</tr>";
								}


								?>

							</tbody>
						</table>

						<br>
						<h2><b>TRANSFERS</b></h2>
						<table class="table" id="transfers">
							<thead>
								<tr>
									<th>Issued By</th>
									<th>#</th>
									<th>Name</th>
									<th>ID</th>
									<th>Branch/Store</th>
									<th>Qty</th>
									<th>Date</th>
								</tr>
							</thead>
							<tbody>
								<?php

								$today = date("Y-m-d");
								$sql = mysqli_query($con, "SELECT * FROM stock_transfer WHERE transfer_date='$today'") or die(mysqli_error($con));

								while ($fetch = mysqli_fetch_array($sql)) {

									echo "<tr>";
									echo "<td>" . $fetch["cashier"] . "</td>";
									echo "<td>" . $fetch["invoice_number"] . "</td>";
									echo "<td>" . $fetch["product_name"] . "</td>";
									echo "<td>" . $fetch["product_id"] . "</td>";
									echo "<td>" . $fetch["branch_name"] . "</td>";
									echo "<td>" . $fetch["quantity"] . "</td>";
									echo "<td>" . $fetch["transfer_date"] . "</td>";

								?>
								<?php

									echo "</tr>";
								}

								?>

							</tbody>
						</table>
						<br>

						<div class="row">

							<div class="col-md-3">
								<?php
								$today = date("Y-m-d");
								//get sales data

								$total_sales = 0;
								if (!empty($_GET["filter_by"])) {
									$tsales = mysqli_query($con, "SELECT `paid_amount`,`cashier`,`order_date`,AVG(paid_amount) AS total FROM sold_products 
WHERE cashier='$filter_by' AND order_date='$today' AND paid_amount !=0 GROUP BY invoice_number
") or die(mysqli_error($con));
								} else {
									$tsales = mysqli_query($con, "SELECT `paid_amount`,AVG(paid_amount) AS total FROM sold_products 
WHERE order_date='$today' AND paid_amount !=0 GROUP BY invoice_number
") or die(mysqli_error($con));
								}

								while ($tsalesData = mysqli_fetch_array($tsales)) {
									$total_sales += $tsalesData["total"];
								}

								//calculate total quantity sold
								if (!empty($_GET["filter_by"])) {
									$qtySold = mysqli_query($con, "SELECT cashier,order_date,quantity, SUM(quantity) AS total FROM sold_products 
WHERE cashier='$filter_by' AND order_date='$today'") or die(mysqli_error($con));
									$qtySoldData = mysqli_fetch_array($qtySold);
								} else {
									$qtySold = mysqli_query($con, "SELECT quantity, SUM(quantity) AS total FROM sold_products 
WHERE order_date='$today'") or die(mysqli_error($con));
									$qtySoldData = mysqli_fetch_array($qtySold);
								}

								//calculate total vat sold
								$total_vat = 0;
								if (!empty($_GET["filter_by"])) {
									$vatQuery = mysqli_query($con, "SELECT order_date,cashier,invoice_number,vat_amount, avg(vat_amount) AS vat FROM sold_products 
WHERE cashier='$filter_by' AND order_date='$today' GROUP BY invoice_number") or die(mysqli_error($con));
								} else {
									$vatQuery = mysqli_query($con, "SELECT invoice_number,vat_amount, avg(vat_amount) AS vat FROM sold_products 
WHERE order_date='$today' GROUP BY invoice_number") or die(mysqli_error($con));
								}
								while ($vatData = mysqli_fetch_array($vatQuery)) {
									$total_vat += $vatData["vat"];
								}

								//calculate total discount given
								if (!empty($_GET["filter_by"])) {
									$discountGiven = mysqli_query($con, "SELECT cashier,product_discount, SUM(product_discount) AS total FROM sold_products 
WHERE cashier='$filter_by' AND order_date='$today'") or die(mysqli_error($con));
								} else {
									$discountGiven = mysqli_query($con, "SELECT product_discount, SUM(product_discount) AS total FROM sold_products 
WHERE order_date='$today'") or die(mysqli_error($con));
								}
								$discountGivenData = mysqli_fetch_array($discountGiven);

								//calculate expected payment,calculate cash or bank payment based on full payment
								$total_cash_amount = 0;
								if (!empty($_GET["filter_by"])) {
									$cashPay = mysqli_query($con, "SELECT avg(cashpayment_amt) as total_cash ,cashier, invoice_number 
FROM (SELECT COUNT(invoice_number) as total_count FROM sold_products WHERE order_date='$today' GROUP BY invoice_number) 
as a CROSS JOIN sold_products r WHERE cashier='$filter_by' AND order_date='$today' GROUP BY invoice_number") or die(mysqli_error($con));
								} else {
									$cashPay = mysqli_query($con, "SELECT avg(cashpayment_amt) as total_cash,cashier, invoice_number 
FROM (SELECT COUNT(invoice_number) as total_count FROM sold_products WHERE order_date='$today' GROUP BY invoice_number) 
as a CROSS JOIN sold_products r WHERE order_date='$today' GROUP BY invoice_number") or die(mysqli_error($con));
								}
								while ($cashPayData = mysqli_fetch_array($cashPay)) {
									$total_cash_amount += $cashPayData["total_cash"];
								}

								$total_bank_amount = 0;
								if (!empty($_GET["filter_by"])) {
									$bankPay = mysqli_query($con, "SELECT avg(bankpayment_amt) as total_bank, cashier,invoice_number FROM 
(SELECT COUNT(invoice_number) as total_count FROM sold_products WHERE order_date='$today' GROUP BY invoice_number) 
as a CROSS JOIN sold_products r WHERE cashier='$filter_by' AND order_date='$today' GROUP BY invoice_number") or die(mysqli_error($con));
								} else {
									$bankPay = mysqli_query($con, "SELECT avg(bankpayment_amt) as total_bank, invoice_number FROM 
(SELECT COUNT(invoice_number) as total_count FROM sold_products WHERE order_date='$today' GROUP BY invoice_number) 
as a CROSS JOIN sold_products r WHERE order_date='$today' GROUP BY invoice_number") or die(mysqli_error($con));
								}

								while ($bankPayData = mysqli_fetch_array($bankPay)) {
									$total_bank_amount += $bankPayData["total_bank"];
								}

								if (!empty($_GET["filter_by"])) {
									$expected_sales = mysqli_query($con, "SELECT cashier,expected_sale_price, SUM(expected_sale_price) AS total FROM sold_products 
WHERE cashier='$filter_by' AND order_date='$today'") or die(mysqli_error($con));
								} else {
									$expected_sales = mysqli_query($con, "SELECT expected_sale_price,quantity,SUM(quantity) AS totalQty, SUM(expected_sale_price) AS total FROM sold_products 
WHERE order_date='$today'") or die(mysqli_error($con));
								}
								$expected_salesData = mysqli_fetch_array($expected_sales);
								$expected_salesData = $expected_salesData["total"];

								//good sold on credit - having part payment
								$total_credit = 0;
								$credit_qty  = 0;
								$paid_amount = 0;
								if (!empty($_GET["filter_by"])) {
									$credit = mysqli_query($con, "SELECT cashier,paid_amount,quantity,AVG(paid_amount) as p_amt,SUM(quantity) as credit_qty,total_amount,AVG(total_amount) AS total_credit FROM sold_products WHERE paid_amount >= 0 
AND cashier='$filter_by' AND order_date='$today' AND payment_type='Part Payment' GROUP BY invoice_number") or die(mysqli_error($con));
								} else {
									$credit = mysqli_query($con, "SELECT paid_amount,quantity,AVG(paid_amount) as p_amt,SUM(quantity) as credit_qty,total_amount,AVG(total_amount) AS total_credit FROM sold_products WHERE paid_amount >= 0 
AND order_date='$today' AND payment_type='Part Payment' GROUP BY invoice_number") or die(mysqli_error($con));
								}
								while ($creditData = mysqli_fetch_array($credit)) {
									$total_credit += $creditData["total_credit"];
									$paid_amount += $creditData["p_amt"];
									$credit_qty += $creditData["credit_qty"];
								}

								$total_credit = $total_credit - $paid_amount;

								$earlierDate = date("Y/m/d 00:00:00");
								$nowDate = date("Y/m/d h:i:s");

								$sql_ret = mysqli_query($con, "SELECT return_date FROM returned_receipts WHERE return_date >= '$earlierDate' AND return_date <= '$nowDate'") or die(mysqli_error($con));
								$data_ret = mysqli_fetch_array($sql_ret);

								//get expenditure data
								if (!empty($_GET["filter_by"])) {

									$sql_exp = mysqli_query($con, "SELECT exp_cashierid,SUM(exp_amount) AS total FROM expenditures WHERE exp_cashierid='$filter_by' AND exp_date ='$today'") or die(mysqli_error($con));
								} else {
									$sql_exp = mysqli_query($con, "SELECT SUM(exp_amount) AS total FROM expenditures WHERE exp_date ='$today'") or die(mysqli_error($con));
								}
								$data_exp = mysqli_fetch_array($sql_exp);

								if (!empty($_GET["filter_by"])) {
									$old_credit_sql = mysqli_query($con, "SELECT cashier_id,amount_paid, SUM(amount_paid) AS total FROM balance_sheet 
		WHERE cashier_id='$filter_by' AND payment_date='$today'") or die(mysqli_error($con));
								} else {
									$old_credit_sql = mysqli_query($con, "SELECT amount_paid, SUM(amount_paid) AS total FROM balance_sheet 
		WHERE payment_date='$today'") or die(mysqli_error($con));
								}
								$old_creditData = mysqli_fetch_array($old_credit_sql);

								if (!empty($_GET["filter_by"])) {
									$creditCashPay = mysqli_query($con, "SELECT cashier_id,amount_paid, SUM(amount_paid) AS total FROM balance_sheet 
		WHERE cashier_id='$filter_by' AND payment_date='$today' AND pay_type='Cash'") or die(mysqli_error($con));
								} else {
									$creditCashPay = mysqli_query($con, "SELECT amount_paid, SUM(amount_paid) AS total FROM balance_sheet 
		WHERE payment_date='$today' AND pay_type='Cash'") or die(mysqli_error($con));
								}
								$creditCashRow = mysqli_fetch_array($creditCashPay);

								if (!empty($_GET["filter_by"])) {
									$creditbankPay = mysqli_query($con, "SELECT cashier_id,amount_paid, SUM(amount_paid) AS total FROM balance_sheet 
WHERE cashier_id='$filter_by' AND payment_date='$today' AND pay_type='Bank/Internet Transfer'") or die(mysqli_error($con));
								} else {
									$creditbankPay = mysqli_query($con, "SELECT amount_paid, SUM(amount_paid) AS total FROM balance_sheet 
WHERE payment_date='$today' AND pay_type='Bank/Internet Transfer'") or die(mysqli_error($con));
								}
								$creditBankRow = mysqli_fetch_array($creditbankPay);

								$totalAmtSales = $expected_salesData - $discountGivenData["total"] - $total_credit -  $data_exp["total"] + $total_vat;
								$overage = $total_sales + $total_credit - ($expected_salesData);
								$gsTotal = $expected_salesData + $overage;
								?>
								<p>
								<h4><b>Sales Summary</b></h4>
								</p>

								<table class="table taber-borderless">
									<tr>
										<td>Total Sales Amount</td>
										<td> - </td>
										<td><?php echo "$currency" . number_format($total_sales, 2);  ?></td>
									</tr>

									<tr>
										<td>Total Returns</td>
										<td> - </td>
										<td><?php echo mysqli_num_rows($sql_ret);  
											?></td>
									</tr>

									<tr>
										<td>Total Expenditure</td>
										<td></td>
										<td><?php echo "$currency" . number_format($data_exp["total"], 2);  ?></td>
									</tr>

									<tr>
										<td>Total Quantity Sold</td>
										<td> - </td>
										<td><?php echo number_format($qtySoldData["total"], 2);  ?></td>
									</tr>

									<tr>
										<td>Total Discount Given</td>
										<td> - </td>
										<td><?php echo "$currency" . number_format($discountGivenData["total"], 2);  ?></td>
									</tr>

									<tr>
										<td>Total VAT Given</td>
										<td> - </td>
										<td><?php echo "$currency" . number_format($total_vat, 2);  ?></td>
									</tr>

								</table>

								<p>
								<h4><b>Credit Summary</b></h4>
								<table class="table table-borderless">
									<tr>
										<td>Total Quantity Sold on Credit</td>
										<td> - </td>
										<td><?php echo $credit_qty;  ?></td>
									</tr>

									<tr>
										<td>Total Credit Amount</td>
										<td> - </td>
										<td><?php echo "$currency" . number_format($total_credit, 2);  ?></td>
									</tr>

									<tr>
										<td>Cash Received</td>
										<td> - </td>
										<td><?php echo "$currency" . number_format($creditCashRow["total"], 2);  ?></td>
									</tr>

									<tr>
										<td>Bank Payment Received</td>
										<td> - </td>
										<td><?php echo "$currency" . number_format($creditBankRow["total"], 2);  ?></td>
									</tr>

									<tr>
										<td>Total Credit Paid</td>
										<td> - </td>
										<td><?php echo "$currency" . number_format($old_creditData["total"], 2);  ?></td>
									</tr>


								</table>
								</p>
							</div>

							<div class="col-md-4">
								<p>
								<h4><b>Payments Summary</b></h4>
								</p>
								<table class="table table-borderless pay-summary">
									<tr>
										<td>Total Payment Expected From Sales</td>
										<td> - </td>
										<td><?php echo "$currency" . number_format($expected_salesData, 2);  ?></td>
									</tr>

									<tr>
										<td>Total Payment Received From Sales</td>
										<td> - </td>
										<td><?php echo "$currency" . number_format($total_sales, 2);  ?></td>
									</tr>

									<tr>
										<td>Overage From Sales</td>
										<td> - </td>
										<td><?php echo "$currency" . number_format($overage, 2);  ?></td>
									</tr>

									<tr>
										<td>Grand Sales Total</td>
										<td> - </td>
										<td><?php echo "$currency" . number_format($gsTotal, 2);  ?></td>
									</tr>


									<tr>
										<td>Credits</td>
										<td> - </td>
										<td><?php
											echo "$currency" . number_format($total_credit, 2);
											?></td>
									</tr>

									<tr>
										<td>Cash Payment</td>
										<td> - </td>
										<td><?php echo "$currency" . number_format($total_cash_amount, 2);  ?></td>
									</tr>

									<tr>
										<td>Bank Payment</td>
										<td> - </td>
										<td><?php echo "$currency" . number_format($total_bank_amount, 2);  ?></td>
									</tr>

								</table>

							</div>

							<div class="col-md-5">

								<?php

								$expensesData = array(
									array("label" => "Total Sales", "symbol" => "TS", "y" => $total_sales),
									array("label" => "Total Received", "symbol" => "TS", "y" => $total_sales),
									array("label" => "Bank Transfer Payment", "symbol" => "BTP", "y" => $total_bank_amount),
									array("label" => "Cash Payment", "symbol" => "CP", "y" => $total_cash_amount),
									array("label" => "Discount Given", "symbol" => "DG", "y" => $discountGivenData["total"]),
									array("label" => "Expenditure", "symbol" => "EXP", "y" => $data_exp["total"])
								);

								?>

								<script>
									window.onload = function() {

										var expenseschart = new CanvasJS.Chart("totalExpContainer", {
											theme: "light2",
											animationEnabled: true,
											title: {
												text: "Summary Chart"
											},
											data: [{
												type: "doughnut",
												indexLabel: "{symbol} - {y}",
												yValueFormatString: "\"₦\"#,##0.0",
												showInLegend: true,
												legendText: "{label} : {y}",
												dataPoints: <?php echo json_encode($expensesData, JSON_NUMERIC_CHECK); ?>
											}]
										});
										expenseschart.render();
									}
								</script>

								<div class="row" style="margin-top: 30px;">
									<div class="col-md-8">
										<div id="totalExpContainer" style="height: 350px; width: 100%;"></div>
									</div>
								</div>

							</div>



						</div>
						<br>
						<br>
						<div class="row">
							<div class="col-md-4">
								<h5><b>Total Available From Sales = <br><br></b> Grand Sales - Total Discount - Credits - Expenditures</h5>
								<h3> =
									<?php $total_available = round($gsTotal - $total_credit - $data_exp["total"]);
									echo "$currency" . number_format($total_available); ?></h3>
							</div>
							<div class="col-md-6">

								<h5><b>Total Available For Deposit = <br><br></b> Grand Sales Total - Total Credit Owed - Bank Payments + Total Credit Paid Today</h5>
								<h3> = <?php echo "$currency" . number_format($gsTotal - $total_credit - $total_bank_amount + $old_creditData["total"], 2); ?></h3>

							</div>
						</div>
						<br>
						<br>
						<p><b>NB:</b> This Report (is) and remains a property of the above named company
							and as such, <br> anyone found to have altered or falsified any part of this
							document will be subjected to <br> the Company Law(s) of the
							Federal Republic of Nigeria. </p>

						<br><br>
						<center>
							<button class="btn customize-abs-btn hidden-print" onclick="window.print()">
								<i class="fas fa-print fa-2x"></i> Print All</button>
						</center>

					</div>
				</div>

			</div>
		</div>

	</div>
	<!-- /panel-body -->
</div>
</div>
<!-- /col-dm-12 -->
</div>
<!-- /row -->
</div>

<?php include "../partials/footer.php"; ?>