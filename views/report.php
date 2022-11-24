<?php
include "../partials/header.php";
include "../partials/sidebar.php";;
?>

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

	.company-name {
		font-family: "Algerian";
		font-size: 35px;
	}

	.company-address {
		font-family: "Verdana";
		font-size: 18px;
	}
</style>


<script>
	$(document).ready(function() {


		$('#debtors_report').DataTable({
			dom: 'lBfrtip',
			buttons: [
				'csv', 'excel'
			],
			"lengthMenu": [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			]
		});


		$('#expenses_report').DataTable({
			dom: 'lBfrtip',
			buttons: [
				'csv', 'excel'
			],
			"lengthMenu": [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			]
		});


		$('#msitems_report').DataTable({
			dom: 'lBfrtip',
			buttons: [
				'csv', 'excel'
			],
			"lengthMenu": [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			]
		});


		$('#pqc_logreport').DataTable({
			dom: 'lBfrtip',
			buttons: [
				'csv', 'excel'
			],
			"lengthMenu": [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			]
		});


		$('#returns_report').DataTable({
			dom: 'lBfrtip',
			buttons: [
				'csv', 'excel'
			],
			"lengthMenu": [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			]
		});


		$('#reversals_report').DataTable({
			dom: 'lBfrtip',
			buttons: [
				'csv', 'excel'
			],
			"lengthMenu": [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			]
		});


		$('#sales_report').DataTable({
			dom: 'lBfrtip',
			buttons: [
				'csv', 'excel'
			],
			"lengthMenu": [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			]
		});


		$('#stock_level_report').DataTable({
			dom: 'lBfrtip',
			buttons: [
				'csv', 'excel'
			],
			"lengthMenu": [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			]
		});


		$('#placed_orders_report').DataTable({
			dom: 'lBfrtip',
			buttons: [
				'csv', 'excel'
			],
			"lengthMenu": [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			]
		});


		$('#profit_report').DataTable({
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

	function toggleFilterVisibility(selected) {

		if (selected == "sales" || selected == "reversals" || selected == "expenses") {
			document.querySelector("#user_filter").style.display = "block";
		} else {
			document.querySelector("#user_filter").style.display = "none";
		}

		if (selected == "placed_orders") {
			document.querySelector("#filter_placed_orders").style.display = "block";
		} else {
			document.querySelector("#filter_placed_orders").style.display = "none";
		}
	}
</script>


<div class="main-panel">
	<div class="content-wrapper">
		<div class="row bg-white rounded-0 shadow-sm p-3">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fas fa-check"></i> Generate Report
					</div>
					<!-- /panel-heading -->
					<div class="panel-body">

						<form action="" method="post" id="getOrderReportForm">
							<div class="row hidden-print">
								<div class="col-md-2"></div>
								<div class="col-md-8 ">
									<div class="form-group">
										<label for="startDate" class="col-sm-2 control-label">Start Date</label>
										<div class="col-sm-10">
											<input type="text" required class="form-control" id="startDate" autocomplete="off" name="startDate" placeholder="Start Date" />
										</div>
									</div>

									<div class="form-group">
										<label for="endDate" class="col-sm-2 control-label">End Date</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="endDate" name="endDate" autocomplete="off" placeholder="End Date" />
										</div>
									</div>

									<div class="form-group">
										<label for="report_type" class="col-sm-12 control-label">Type of Report</label>
										<div class="col-sm-10">
											<select onchange="toggleFilterVisibility(this.value)" name="report_type" id="report_type" required class="form-control">
												<option value="" selected>-Select-</option>
												<option value="debtors">Debtors</option>
												<option value="expenses">Expenses</option>
												<option value="ms_items">Most Sold Items</option>
												<option value="profit">Profit</option>
												<option value="placed_orders">Placed Orders</option>
												<!-- <option value="returns">Returns</option> -->
												<option value="reversals">Reversals</option>
												<option value="sales">Sales</option>
												<option value="stock_level">Total Stock Level (All Products)</option>
											</select>
										</div>
									</div>

									<div class="form-group" id="user_filter" style="display: none;">

										<label for="user_type" class="col-sm-12 control-label">Filter by User</label>
										<div class="col-sm-10">
											<select name="filter_by_user" id="filter_by_user" class="form-control">
												<option selected>-Select User</option>

												<?php
												$userssql = mysqli_query($con, "SELECT * FROM users");

												while ($fetchUsers = mysqli_fetch_array($userssql)) {
												?>
													<option value="<?php echo $fetchUsers["username"]; ?>"><?php echo ucwords($fetchUsers["username"]); ?></option>

												<?php
												}
												?>
												<option value="all">Show All</option>
											</select>
										</div>
									</div>

									<div class="form-group" id="filter_placed_orders" style="display: none;">

										<label for="user_type" class="col-sm-12 control-label">Filter by Category</label>
										<div class="col-sm-10">
											<select name="filter_by_po" id="filter_by_po" class="form-control">
												<option selected>-Select Category-</option>
												<option value="all">Show All</option>
												<option value="received">Received</option>
												<option value="pending">Pending</option>
											</select>
										</div>
									</div>
									<br>
									<br>
									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
											<button type="submit" class="btn btn-info btn-block" name="generateReportBtn" id="generateReportBtn"> <i class="fas fa-ok-sign"></i> Generate Report</button>
										</div>
									</div>
								</div>
								<div class="col-md-2"></div>

							</div>
						</form>

						<?php

						// $total_for_cashier = 0;
						// $tsales = mysqli_query($con, "SELECT `paid_amount`,AVG(paid_amount) AS total FROM sold_products 
						// WHERE cashier='priscilla' AND order_date ='2021-10-05' AND paid_amount !=0 GROUP BY invoice_number
						// ") or die(mysqli_error($con));
						// while ($tsalesData = mysqli_fetch_array($tsales)) {
						// 	$total_for_cashier += $tsalesData["total"];
						// }

						if (isset($_POST["generateReportBtn"])) {
							$getStartDate = $_POST["startDate"];
							$getEndDate = $_POST["endDate"];
							$reportType = $_POST["report_type"];
							$filter_by_po = $_POST["filter_by_po"];

							$filterUser = $_POST["filter_by_user"];

							//query the above information based on paytype
							if ($reportType == "debtors") {

						?>
								<div class="row" style="font-family: 'Verdana';">
									<div class="col-md-12 mx-auto">
										<center>
											<?php
											$getData = mysqli_query($con, "SELECT * FROM settings") or die(mysqli_error($con));
											$fetchData = mysqli_fetch_array($getData);
											?>

											<h1 class="company-name"><b><?php echo $fetchData["company_name"]; ?></b>
												<br>
												<span class="company-address"><?php echo $fetchData["company_address"]; ?></span>
											</h1>
											<h4><?php echo $fetchData["company_phone"]; ?></h4>

											<h2 style="text-decoration: underline;"><b>Debtors Report</b></h2>

											<br>
											<?php 										echo $getStartDate. " - ".$getEndDate;
?>
<br>
										</center>
									</div>
								</div>

								<table class="table" id="debtors_report">
									<thead>
										<tr>
											<th>Name</th>
											<th>Phone Number</th>
											<th>Address</th>
											<!-- <th>Current Debt</th> -->
											<th>Qty. Purchased</th>
											<th>View History</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php generateDebtorsReport($con, $getStartDate, $getEndDate); ?>
									</tbody>
								</table>

								<br><br>
								<center>
									<button class="btn customize-abs-btn hidden-print" onclick="window.print()">
										<i class="fas fa-print fa-2x"></i> Print All</button>
								</center>

							<?php

							} elseif ($reportType == "expenses") {

							?>
								<div class="row" style="font-family: 'Verdana';">
									<div class="col-md-12 mx-auto">
										<center>
											<?php
											$getData = mysqli_query($con, "SELECT * FROM settings") or die(mysqli_error($con));
											$fetchData = mysqli_fetch_array($getData);
											?>

											<h1 class="company-name"><b><?php echo $fetchData["company_name"]; ?></b>
												<br>
												<span class="company-address"><?php echo $fetchData["company_address"]; ?></span>
											</h1>

											<h4><?php echo $fetchData["company_phone"]; ?></h4>

											<h2 style="text-decoration: underline;"><b>Expenditure Report</b></h2>

											<br>
																						<?php 										echo $getStartDate. " - ".$getEndDate;
?>
										</center>
									</div>
								</div>

								<table class="table" id="expenses_report">
									<thead>
										<tr>

											<th>Reference</th>
											<th>User</th>
											<th>Desc.</th>
											<th>Category</th>
											<th>Amount</th>
											<th>Date</th>

										</tr>
									</thead>
									<tbody>
										<?php generateExpensesReport($con, $getStartDate, $getEndDate, $currency, $filterUser); ?>
									</tbody>
								</table>

								<br><br>
								<center>
									<button class="btn customize-abs-btn hidden-print" onclick="window.print()">
										<i class="fas fa-print fa-2x"></i> Print All</button>
								</center>

							<?php
							} elseif ($reportType == "ms_items") {
							?>

								<div class="row" style="font-family: 'Verdana';">
									<div class="col-md-12 mx-auto">
										<center>
											<?php
											$getData = mysqli_query($con, "SELECT * FROM settings") or die(mysqli_error($con));
											$fetchData = mysqli_fetch_array($getData);
											?>


											<h1 class="company-name"><b><?php echo $fetchData["company_name"]; ?></b>
												<br>
												<span class="company-address"><?php echo $fetchData["company_address"]; ?></span>
											</h1>

											<h4><?php echo $fetchData["company_phone"]; ?></h4>

											<h2 style="text-decoration: underline;"><b>Most Sold Product Report</b></h2>

											<br>
																						<?php 										echo $getStartDate. " - ".$getEndDate;
?>
										</center>
									</div>
								</div>

								<table class="table" id="msitems_report">
									<thead>
										<tr>

											<th>ID</th>
											<th>Name</th>
											<th>Total Sales</th>
											<th>Amount Generated</th>

										</tr>
									</thead>
									<tbody>
										<?php generateMSItemsReport($con, $getStartDate, $getEndDate, $currency); ?>
									</tbody>
								</table>

								<br><br>
								<center>
									<button class="btn customize-abs-btn hidden-print" onclick="window.print()">
										<i class="fas fa-print fa-2x"></i> Print All</button>
								</center>

							<?php
							} elseif ($reportType == "pqc_log") { ?>

								<div class="row" style="font-family: 'Verdana';">
									<div class="col-md-12 mx-auto">
										<center>
											<?php
											$getData = mysqli_query($con, "SELECT * FROM settings") or die(mysqli_error($con));
											$fetchData = mysqli_fetch_array($getData);
											?>


											<h1 class="company-name"><b><?php echo $fetchData["company_name"]; ?></b>
												<br>
												<span class="company-address"><?php echo $fetchData["company_address"]; ?></span>
											</h1>

											<h4><?php echo $fetchData["company_phone"]; ?></h4>

											<h2 style="text-decoration: underline;"><b>Product Quantity Change Report</b></h2>

											<br>
																						<?php 										echo $getStartDate. " - ".$getEndDate;
?>
										</center>
									</div>
								</div>

								<table class="table" id="pqc_logreport">
									<thead>
										<tr>

											<th>ID</th>
											<th>Name</th>
											<th>Changed from (Qty.)</th>
											<th>Change to (Qty.)</th>
											<th>Final Qty. After Change</th>
											<th>Date</th>

										</tr>
									</thead>
									<tbody>
										<?php generatePQCLogReport($con, $getStartDate, $getEndDate); ?>
									</tbody>
								</table>

								<br><br>
								<center>
									<button class="btn customize-abs-btn hidden-print" onclick="window.print()">
										<i class="fas fa-print fa-2x"></i> Print All</button>
								</center>

							<?php
							} elseif ($reportType == "returns") { ?>

								<div class="row" style="font-family: 'Verdana';">
									<div class="col-md-12 mx-auto">
										<center>
											<?php
											$getData = mysqli_query($con, "SELECT * FROM settings") or die(mysqli_error($con));
											$fetchData = mysqli_fetch_array($getData);
											?>


											<h1 class="company-name"><b><?php echo $fetchData["company_name"]; ?></b>
												<br>
												<span class="company-address"><?php echo $fetchData["company_address"]; ?></span>
											</h1>

											<h4><?php echo $fetchData["company_phone"]; ?></h4>

											<h2 style="text-decoration: underline;"><b>Returned Items Report</b></h2>

											<br>
										</center>
									</div>
								</div>

								<table class="table" id="returns_report">
									<thead>
										<tr>

											<th>#</th>
											<th>Name</th>
											<th>Qty</th>
											<th>Sold At(Price)</th>
											<th>Amount</th>
											<th>Pay Type</th>
											<th>Pay Method</th>
											<th>Customer Data</th>
											<th>Order Date</th>
											<th>Return Date</th>

										</tr>
									</thead>
									<tbody>
										<?php
										$earlierDate = date("Y/m/d h:i:s", strtotime($getStartDate));
										$nowDate = date("Y/m/d h:i:s", strtotime($getEndDate));

										generateReturnsReport($con, $earlierDate, $nowDate);

										?>
									</tbody>
								</table>

								<br><br>
								<center>
									<button class="btn customize-abs-btn hidden-print" onclick="window.print()">
										<i class="fas fa-print fa-2x"></i> Print All</button>
								</center>

							<?php

							} elseif ($reportType == "reversals") { ?>
								<div class="row" style="font-family: 'Verdana';">
									<div class="col-md-12 mx-auto">
										<center>
											<?php
											$getData = mysqli_query($con, "SELECT * FROM settings") or die(mysqli_error($con));
											$fetchData = mysqli_fetch_array($getData);
											?>


											<h1 class="company-name"><b><?php echo $fetchData["company_name"]; ?></b>
												<br>
												<span class="company-address"><?php echo $fetchData["company_address"]; ?></span>
											</h1>

											<h4><?php echo $fetchData["company_phone"]; ?></h4>

											<h2 style="text-decoration: underline;"><b>Reversed Sales Report</b></h2>

											<br>
																						<?php 										echo $getStartDate. " - ".$getEndDate;
?>
										</center>
									</div>
								</div>

								<table class="table" id="reversals_report">
									<thead>
										<tr>

											<th>#</th>
											<th>Name</th>
											<th>Qty</th>
											<th>Sold At(Price)</th>
											<th>Amount Paid</th>
											<th>Pay Type</th>
											<th>Pay Method</th>
											<th>Customer Data</th>
											<th>Order Date</th>
											<th>Reverse Date</th>

										</tr>
									</thead>
									<tbody>
										<?php generateReversalsReport($con, $getStartDate, $getEndDate, $filterUser); ?>
									</tbody>
								</table>

								<br><br>
								<center>
									<button class="btn customize-abs-btn hidden-print" onclick="window.print()">
										<i class="fas fa-print fa-2x"></i> Print All</button>
								</center>
							<?php
							} elseif ($reportType == "sales") {
							?>
								<div class="row" style="font-family: 'Verdana';">
									<div class="col-md-12 mx-auto">
										<center>
											<?php
											$getData = mysqli_query($con, "SELECT * FROM settings") or die(mysqli_error($con));
											$fetchData = mysqli_fetch_array($getData);
											?>


											<h1 class="company-name"><b><?php echo $fetchData["company_name"]; ?></b>
												<br>
												<span class="company-address"><?php echo $fetchData["company_address"]; ?></span>
											</h1>

											<h4><?php echo $fetchData["company_phone"]; ?></h4>

											<h2 style="text-decoration: underline;"><b>Sales Report</b></h2>

											<br>
																						<?php 										echo $getStartDate. " - ".$getEndDate;
?>
										</center>
									</div>
								</div>

								<table class="table" id="sales_report">
									<thead>
										<tr>
											<th>#</th>
											<th>Sold By</th>
											<th>Name</th>
											<th>Qty</th>
											<th>Discount</th>
											<th>Sold at (Price)</th>
											<th>Expected SP</th>
											<th>Received Amount</th>
											<th>Payment Type</th>
											<th>Cash</th>
											<th>Bank</th>
											<th>Pay Method</th>
											<th>Buyer</th>

										</tr>
									</thead>
									<tbody>
										<?php
										
										generateSalesReport($con, $getStartDate, $getEndDate, $filterUser); ?>
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
										if (isset($filterUser)) {
											$sql = mysqli_query($con, "SELECT * FROM stock_transfer WHERE cashier='$filterUser'") or die(mysqli_error($con));
										} else {
											$sql = mysqli_query($con, "SELECT * FROM stock_transfer") or die(mysqli_error($con));
										}

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

										if ($filterUser != "all") {

											$tsales = mysqli_query($con, "SELECT `paid_amount`,`cashier`,AVG(paid_amount) AS total FROM sold_products 
								WHERE cashier='$filterUser' AND order_date 
								BETWEEN '$getStartDate' AND '$getEndDate' AND paid_amount !=0 
								GROUP BY invoice_number
								") or die(mysqli_error($con));
											while ($tsalesData = mysqli_fetch_array($tsales)) {
												$total_sales += $tsalesData["total"];
											}

										} else {
											$tsales = mysqli_query($con, "SELECT `paid_amount`,AVG(paid_amount) AS total FROM sold_products 
								WHERE order_date 
								BETWEEN '$getStartDate' AND '$getEndDate' AND paid_amount !=0 
								GROUP BY invoice_number
								") or die(mysqli_error($con));
											while ($tsalesData = mysqli_fetch_array($tsales)) {
												$total_sales += $tsalesData["total"];
											}
										}

										//calculate total quantity sold
										if ($filterUser != "all") {
											$qtySold = mysqli_query($con, "SELECT quantity,cashier, SUM(quantity) AS total FROM sold_products 
	WHERE  cashier='$filterUser' AND order_date BETWEEN '$getStartDate' AND '$getEndDate'") or die(mysqli_error($con));
											$qtySoldData = mysqli_fetch_array($qtySold);
										} else {
											$qtySold = mysqli_query($con, "SELECT quantity, SUM(quantity) AS total FROM sold_products 
									WHERE order_date BETWEEN '$getStartDate' AND '$getEndDate'") or die(mysqli_error($con));
											$qtySoldData = mysqli_fetch_array($qtySold);
										}

										//calculate total discount given
										if ($filterUser != "all") {
											$discountGiven = mysqli_query($con, "SELECT product_discount,cashier, SUM(product_discount) AS total FROM sold_products 
	WHERE cashier='$filterUser' AND order_date BETWEEN '$getStartDate' AND '$getEndDate'") or die(mysqli_error($con));
											$discountGivenData = mysqli_fetch_array($discountGiven);
										} else {
											$discountGiven = mysqli_query($con, "SELECT product_discount, SUM(product_discount) AS total FROM sold_products 
									WHERE order_date BETWEEN '$getStartDate' AND '$getEndDate'") or die(mysqli_error($con));
											$discountGivenData = mysqli_fetch_array($discountGiven);
										}

										//calculate total vat sold
										$total_vat = 0;
										if ($filterUser != "all") {
											$vatQuery = mysqli_query($con, "SELECT invoice_number,cashier,vat_amount, avg(vat_amount) AS vat FROM sold_products 
	WHERE cashier='$filterUser' AND order_date BETWEEN '$getStartDate' AND '$getEndDate' GROUP BY invoice_number") or die(mysqli_error($con));
											while ($vatData = mysqli_fetch_array($vatQuery)) {
												$total_vat += $vatData["vat"];
											}
										} else {
											$vatQuery = mysqli_query($con, "SELECT invoice_number,vat_amount, avg(vat_amount) AS vat FROM sold_products 
								WHERE order_date BETWEEN '$getStartDate' AND '$getEndDate' GROUP BY invoice_number") or die(mysqli_error($con));
											while ($vatData = mysqli_fetch_array($vatQuery)) {
												$total_vat += $vatData["vat"];
											}
										}

										if ($filterUser != "all") {
											//calculate expected payment,calculate cash or bank payment based on full payment
											$total_cash_amount = 0;
											$cashPay = mysqli_query($con, "SELECT avg(cashpayment_amt) as total_cash ,cashier, invoice_number 
	FROM (SELECT COUNT(invoice_number) as total_count FROM sold_products WHERE cashier='$filterUser' AND order_date BETWEEN '$getStartDate' AND '$getEndDate' GROUP BY invoice_number) 
	as a CROSS JOIN sold_products r WHERE cashier='$filterUser' AND order_date BETWEEN '$getStartDate' AND '$getEndDate' GROUP BY invoice_number") or die(mysqli_error($con));
											while ($cashPayData = mysqli_fetch_array($cashPay)) {
												$total_cash_amount += $cashPayData["total_cash"];
											}
										} else {
											$cashPay = mysqli_query($con, "SELECT avg(cashpayment_amt) as total_cash , invoice_number 
	FROM (SELECT COUNT(invoice_number) as total_count FROM sold_products WHERE order_date BETWEEN '$getStartDate' AND '$getEndDate' GROUP BY invoice_number) 
	as a CROSS JOIN sold_products r WHERE order_date BETWEEN '$getStartDate' AND '$getEndDate' GROUP BY invoice_number") or die(mysqli_error($con));
											while ($cashPayData = mysqli_fetch_array($cashPay)) {
												$total_cash_amount += $cashPayData["total_cash"];
											}
										}

										$total_bank_amount = 0;
										if ($filterUser != "all") {
											$bankPay = mysqli_query($con, "SELECT avg(bankpayment_amt) as total_bank,cashier invoice_number FROM 
	(SELECT COUNT(invoice_number) as total_count FROM sold_products WHERE cashier='$filterUser' AND order_date BETWEEN '$getStartDate' AND '$getEndDate' GROUP BY invoice_number) 
	as a CROSS JOIN sold_products r WHERE  cashier='$filterUser' AND order_date BETWEEN '$getStartDate' AND '$getEndDate' GROUP BY invoice_number") or die(mysqli_error($con));
											while ($bankPayData = mysqli_fetch_array($bankPay)) {
												$total_bank_amount += $bankPayData["total_bank"];
											}
										} else {
											$bankPay = mysqli_query($con, "SELECT avg(bankpayment_amt) as total_bank, invoice_number FROM 
								(SELECT COUNT(invoice_number) as total_count FROM sold_products WHERE order_date BETWEEN '$getStartDate' AND '$getEndDate' GROUP BY invoice_number) 
								as a CROSS JOIN sold_products r WHERE order_date BETWEEN '$getStartDate' AND '$getEndDate' GROUP BY invoice_number") or die(mysqli_error($con));
											while ($bankPayData = mysqli_fetch_array($bankPay)) {
												$total_bank_amount += $bankPayData["total_bank"];
											}
										}

										if ($filterUser != "all") {
											$expected_sales = mysqli_query($con, "SELECT expected_sale_price,cashier, SUM(expected_sale_price) AS total FROM sold_products 
	WHERE cashier='$filterUser' AND order_date BETWEEN '$getStartDate' AND '$getEndDate'") or die(mysqli_error($con));
										} else {
											$expected_sales = mysqli_query($con, "SELECT expected_sale_price, SUM(expected_sale_price) AS total FROM sold_products 
								WHERE order_date BETWEEN '$getStartDate' AND '$getEndDate'") or die(mysqli_error($con));
										}
										$expected_salesData = mysqli_fetch_array($expected_sales);
										$expected_salesData = $expected_salesData["total"];
										//good sold on credit - having part payment
										$total_credit = 0;
										$credit_qty  = 0;
										$paid_amount = 0;

										if ($filterUser != "all") {
											$credit = mysqli_query($con, "SELECT paid_amount,cashier,quantity,AVG(paid_amount) as p_amt,SUM(quantity) as credit_qty,total_amount,AVG(total_amount) AS total_credit FROM sold_products WHERE cashier='$filterUser' AND paid_amount >= 0 
	AND order_date BETWEEN '$getStartDate' AND '$getEndDate' AND payment_type='Part Payment' GROUP BY invoice_number") or die(mysqli_error($con));
											while ($creditData = mysqli_fetch_array($credit)) {
												$total_credit += $creditData["total_credit"];
												$paid_amount += $creditData["p_amt"];
												$credit_qty += $creditData["credit_qty"];
											}
										} else {
											$credit = mysqli_query($con, "SELECT paid_amount,quantity,AVG(paid_amount) as p_amt,SUM(quantity) as credit_qty,total_amount,AVG(total_amount) AS total_credit FROM sold_products WHERE paid_amount >= 0 
								AND order_date BETWEEN '$getStartDate' AND '$getEndDate' AND payment_type='Part Payment' GROUP BY invoice_number") or die(mysqli_error($con));
											while ($creditData = mysqli_fetch_array($credit)) {
												$total_credit += $creditData["total_credit"];
												$paid_amount += $creditData["p_amt"];
												$credit_qty += $creditData["credit_qty"];
											}
										}

										$total_credit = $total_credit - $paid_amount;

										$earlierDate = date("Y/m/d 00:00:00");
										$nowDate = date("Y/m/d h:i:s");

										if ($filterUser != "all") {
											$sql_ret = mysqli_query($con, "SELECT return_date,cashier FROM returned_receipts WHERE cashier='$filterUser' AND return_date >= '$earlierDate' AND return_date <= '$nowDate'") or die(mysqli_error($con));
											$data_ret = mysqli_fetch_array($sql_ret);
										} else {
											$sql_ret = mysqli_query($con, "SELECT return_date FROM returned_receipts WHERE return_date >= '$earlierDate' AND return_date <= '$nowDate'") or die(mysqli_error($con));
											$data_ret = mysqli_fetch_array($sql_ret);
										}
										//get expenditure data
										if ($filterUser != "all") {
											$sql_exp = mysqli_query($con, "SELECT exp_cashierid,SUM(exp_amount) AS total FROM expenditures WHERE exp_cashierid='$filterUser' AND exp_date BETWEEN '$getStartDate' AND '$getEndDate'") or die(mysqli_error($con));
											$data_exp = mysqli_fetch_array($sql_exp);
										} else {
											$sql_exp = mysqli_query($con, "SELECT SUM(exp_amount) AS total FROM expenditures WHERE exp_date BETWEEN '$getStartDate' AND '$getEndDate'") or die(mysqli_error($con));
											$data_exp = mysqli_fetch_array($sql_exp);
										}


										if ($filterUser != "all") {
											$old_credit_sql = mysqli_query($con, "SELECT cashier_id,amount_paid, SUM(amount_paid) AS total FROM balance_sheet 
	WHERE cashier_id='$filterUser' AND payment_date BETWEEN '$getStartDate' AND '$getEndDate'") or die(mysqli_error($con));
											$old_creditData = mysqli_fetch_array($old_credit_sql);
										} else {
											$old_credit_sql = mysqli_query($con, "SELECT amount_paid, SUM(amount_paid) AS total FROM balance_sheet 
									WHERE payment_date BETWEEN '$getStartDate' AND '$getEndDate'") or die(mysqli_error($con));
											$old_creditData = mysqli_fetch_array($old_credit_sql);
										}

										if ($filterUser != "all") {
											$creditCashPay = mysqli_query($con, "SELECT amount_paid,cashier_id, SUM(amount_paid) AS total FROM balance_sheet 
	WHERE cashier_id='$filterUser' AND payment_date BETWEEN '$getStartDate' AND '$getEndDate' AND pay_type='Cash'") or die(mysqli_error($con));
											$creditCashRow = mysqli_fetch_array($creditCashPay);
										} else {
											$creditCashPay = mysqli_query($con, "SELECT amount_paid, SUM(amount_paid) AS total FROM balance_sheet 
									WHERE payment_date BETWEEN '$getStartDate' AND '$getEndDate' AND pay_type='Cash'") or die(mysqli_error($con));
											$creditCashRow = mysqli_fetch_array($creditCashPay);
										}

										if ($filterUser != "all") {

											$creditbankPay = mysqli_query($con, "SELECT amount_paid,cashier_id, SUM(amount_paid) AS total FROM balance_sheet 
	WHERE cashier_id='$filterUser' AND payment_date BETWEEN '$getStartDate' AND '$getEndDate' AND pay_type='Bank/Internet Transfer'") or die(mysqli_error($con));
											$creditBankRow = mysqli_fetch_array($creditbankPay);
										} else {
											$creditbankPay = mysqli_query($con, "SELECT amount_paid, SUM(amount_paid) AS total FROM balance_sheet 
									WHERE payment_date BETWEEN '$getStartDate' AND '$getEndDate' AND pay_type='Bank/Internet Transfer'") or die(mysqli_error($con));
											$creditBankRow = mysqli_fetch_array($creditbankPay);
										}

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
												<td><?php echo mysqli_num_rows($sql_ret);  ?></td>
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

										<h5><b>Total Available From Sales = <br><br></b> Grand Sales - Total Discount - Credits - Expenditures</h5>
										<h3> =
									<?php $total_available = round($gsTotal - $total_credit - $data_exp["total"]);
									echo "$currency" . number_format($total_available); ?></h3>

										<br>
										<br>

										<h5><b>Total Available For Deposit = <br><br></b> Grand Sales Total - Total Credit Owed - Bank Payments + Total Credit Paid</h5>
										<h3> = <?php echo "$currency" . number_format($gsTotal - $total_credit - $total_bank_amount + $old_creditData["total"], 2); ?></h3>

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
								<p><b>NB:</b> This Report (is) and remains a property of the above named company
									and as such, <br> anyone found to have altered or falsified any part of this
									document will be subjected to <br> the Company Law(s) of the
									Federal Republic of Nigeria. </p>

								<br><br>
								<center>
									<button class="btn customize-abs-btn hidden-print" onclick="window.print()">
										<i class="fas fa-print fa-2x"></i> Print All</button>
								</center>

							<?php
							} elseif ($reportType == "stock_level") {
							?>
								<div class="row" style="font-family: 'Verdana';">
									<div class="col-md-12 mx-auto">
										<center>
											<?php
											$getData = mysqli_query($con, "SELECT * FROM settings") or die(mysqli_error($con));
											$fetchData = mysqli_fetch_array($getData);
											?>


											<h1 class="company-name"><b><?php echo $fetchData["company_name"]; ?></b>
												<br>
												<span class="company-address"><?php echo $fetchData["company_address"]; ?></span>
											</h1>

											<h4><?php echo $fetchData["company_phone"]; ?></h4>

											<h2 style="text-decoration: underline;"><b>Stock Level Report</b></h2>

											<br>
																						<?php 										echo $getStartDate. " - ".$getEndDate;
?>
										</center>
									</div>
								</div>

								<table class="table" id="stock_level_report">
									<thead>
										<tr>
											<th>ID</th>
											<th>Product Name</th>
											<th>Qty. Remaining</th>
											<th>Cost Price</th>
											<th>Selling Price</th>
											<th>Expected Cost Amt. <br>(CP x Sold)</th>
											<th>Expected Sales Amt. <br> (SP x Sold)</th>
											<th>Expected Profit Margin</th>
											<!-- <th>Received Amt. <br> (Sales)</th>
									<th>Profit (From Expected Sales)</th>
									<th>Profit (From Received Sales)</th>
									<th>Total Discount Given</th> -->
											<!-- <th>Qty. Purchased</th>
									<th>Qty. Sold</th> -->
										</tr>
									</thead>
									<tbody>
										<?php generateStockLevelReport($con,$getStartDate, $getEndDate); ?>
									</tbody>
								</table>

								<br><br>
								<center>
									<button class="btn customize-abs-btn hidden-print" onclick="window.print()">
										<i class="fas fa-print fa-2x"></i> Print All</button>
								</center>

							<?php
							} elseif ($reportType == "placed_orders") {
							?>
								<div class="row" style="font-family: 'Verdana';">
									<div class="col-md-12 mx-auto">
										<center>
											<?php
											$getData = mysqli_query($con, "SELECT * FROM settings") or die(mysqli_error($con));
											$fetchData = mysqli_fetch_array($getData);
											?>
											<h1 class="company-name"><b><?php echo $fetchData["company_name"]; ?></b>
												<br>
												<span class="company-address"><?php echo $fetchData["company_address"]; ?></span>
											</h1>

											<h4><?php echo $fetchData["company_phone"]; ?></h4>

											<h2 style="text-decoration: underline;"><b>Placed Orders Report</b></h2>

											<br>
																						<?php 										echo $getStartDate. " - ".$getEndDate;
?>
										</center>
									</div>
								</div>

								<table class="table" id="placed_orders_report">
									<thead>
										<tr>
											<th>Order Number</th>
											<th>Product Name</th>
											<th>Quantity Ordered</th>
											<th>Supplier Data</th>
											<th>Total Amount</th>
											<th>Date</th>

										</tr>
									</thead>
									<tbody>
										<?php generatePlacedOrdersReport($con, $getStartDate, $getEndDate, $filter_by_po); ?>
									</tbody>
								</table>

								<br><br>
								<center>
									<button class="btn customize-abs-btn hidden-print" onclick="window.print()">
										<i class="fas fa-print fa-2x"></i> Print All</button>
								</center>

							<?php
							} else if ($reportType == "profit") {
							?>

								<div class="row" style="font-family: 'Verdana';">
									<div class="col-md-12 mx-auto">
										<center>
											<?php
											$getData = mysqli_query($con, "SELECT * FROM settings") or die(mysqli_error($con));
											$fetchData = mysqli_fetch_array($getData);
											?>

											<h1 class="company-name"><b><?php echo $fetchData["company_name"]; ?></b>
												<br>
												<span class="company-address"><?php echo $fetchData["company_address"]; ?></span>
											</h1>

											<h4><?php echo $fetchData["company_phone"]; ?></h4>

											<h2 style="text-decoration: underline;"><b>Profit Margin Report</b></h2>

											<br>
																						<?php 										echo $getStartDate. " - ".$getEndDate;
?>
										</center>
									</div>
								</div>

								<table class="table" id="profit_report">
									<thead>
										<tr>
											<th>Product Name</th>
											<th>Qty. Sold</th>
											<th>Selling Price</th>
											<th>Cost Price</th>
											<th>Profit Margin</th>
										</tr>
									</thead>
									<tbody>
										<?php generateProfitMarginReport($con, $getStartDate, $getEndDate, $currency); ?>
									</tbody>
								</table>

								<br><br>
								<center>
									<button class="btn customize-abs-btn hidden-print" onclick="window.print()">
										<i class="fas fa-print fa-2x"></i> Print All</button>
								</center>

						<?php
							} else {
								echo "Please Select a Report Type.";
							}
						}

						?>

					</div>
					<!-- /panel-body -->
				</div>
			</div>
			<!-- /col-dm-12 -->
		</div>
		<!-- /row -->
	</div>
</div>
</div>
</div>

<!-- <script src="custom/js/report.js"></script> -->

<script>
	$(document).ready(function() {
		// order date picker
		$("#startDate").datepicker({
			dateFormat: 'yy-mm-dd'
		});
		// order date picker
		$("#endDate").datepicker({
			dateFormat: 'yy-mm-dd'
		});
	});
</script>


<?php include '../partials/footer.php'; ?>