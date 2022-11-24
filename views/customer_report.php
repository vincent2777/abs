<?php
include "../partials/header.php";
include "../partials/sidebar.php";;
?>

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
		<div class="row shadow-sm card p-3 rounded-0">
			<div class="col-md-12">

				<div class="panel panel-default" style="border:0px">
					<div class="panel-heading">
						<i class="fas fa-chart-area"></i> Customer Report Analysis
					</div>
					<!-- /panel-heading -->
					<div class="panel-body">
						<script type="text/javascript">
							$(document).ready(function() {
								$('#credit_log').DataTable({
									dom: 'lBfrtip',
									buttons: [
										'csv', 'excel'
									],
									"lengthMenu": [
										[10, 25, 50, -1],
										[10, 25, 50, "All"]
									]
								});

								$('#payment_log').DataTable({
									dom: 'lBfrtip',
									buttons: [
										'csv', 'excel'
									],
									"lengthMenu": [
										[10, 25, 50, -1],
										[10, 25, 50, "All"]
									]
								});

								$('#customer_summary').DataTable({
									dom: 'lBfrtip',
									buttons: [
										'csv', 'excel'
									],
									"lengthMenu": [
										[10, 25, 50, -1],
										[10, 25, 50, "All"]
									]
								});

								$('#purchase_report').DataTable({
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


							<div class="row" style="font-family: 'Times New Roman';">
								<div class="col-md-12 mx-auto">
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

										<h2 style="text-decoration: underline;"><b>Customer Report</b></h2>

										<br>
									</center>
								</div>
							</div>

							<div class="row">

							<div class="col-md-6">
								<?php

								if (isset($_POST["reportBtn"])) {

									$customerid = $_POST["customerid"];
									$today = date("Y-m-d");

									$sqlData = mysqli_query($con, "SELECT * FROM customers 
									WHERE cust_id='$customerid'") or die(mysqli_error($con));
									$queryData = mysqli_fetch_array($sqlData);

								?>
									<p>
									<h4><b>Customer Data</b></h4>
									</p>

									<table class="table taber-borderless">
										<tr>
											<td>Name</td>
											<td> - </td>
											<td><?php echo ucwords($queryData["cust_name"]);  ?></td>
										</tr>

										<tr>
											<td>Phone Number</td>
											<td> - </td>
											<td><?php echo $queryData["cust_phone"]; ?></td>
										</tr>

										<tr>
											<td>Address</td>
											<td> - </td>
											<td><?php echo ucwords($queryData["cust_address"]);  ?></td>
										</tr>

										<tr>
											<td>Birthday</td>
											<td> - </td>
											<td><?php $dob = date("d M, Y", strtotime($queryData["cust_dob"]));
												echo $dob;  ?></td>
										</tr>

										<tr>
											<td>Credit Limit</td>
											<td> - </td>
											<td><?php echo number_format($queryData["cust_credit_limit"], 2);  ?></td>
										</tr>

										<tr>
											<td>Current Debt </td>
											<td> - </td>
											<td><?php echo "$currency" . number_format($queryData["cust_owing"], 2);  ?></td>
										</tr>

										<tr>
											<td>Customer Since </td>
											<td> - </td>
											<td><?php $d = date("d M, Y", strtotime($queryData["reg_date"]));
												echo $d; ?></td>
										</tr>

									</table>

							</div>

							<div class="col-md-6">

								<p>
								<h4><b>Credit Limit Changes</b></h4>
								<table class="table table-borderless" id="credit_log">
									<thead>
										<tr>
											<th>Issued By</th>
											<th>Previous Amt.</th>
											<th>New Amt.</th>
											<th>Trnx. Date</th>
										</tr>
									<tbody>
										<?php

										$cLogData = mysqli_query($con, "SELECT * FROM customer_credit_log 
										WHERE customer_id='$customerid'") or die(mysqli_error($con));
										while ($querycLog = mysqli_fetch_array($cLogData)) {

										?>
											<tr>
												<td><?php echo $querycLog["cashier_id"]; ?></td>
												<td><?php echo $querycLog["prev_amount"]; ?></td>
												<td><?php echo $querycLog["new_amount"]; ?></td>
												<td><?php echo $querycLog["change_date"]; ?></td>
											</tr>

										<?php }   ?>
									</tbody>
									</thead>

								</table>
								</p>
							</div>
							
							</div>
							
							<div class="row">
								<div class="col-md-12">

									<p>
									<h4><b>Payment Summary</b></h4>
									</p>

									<table class="table table-borderless" id="payment_log">
										<thead>
											<tr>
												<th>Issued By</th>
												<th>Ref. No.</th>
												<th>Amt. Paid</th>
												<th>Pay Method</th>
												<th>Payment Date</th>
											</tr>
										<tbody>
											<?php

											$pLogData = mysqli_query($con, "SELECT * FROM balance_sheet 
											WHERE customer_id='$customerid'") or die(mysqli_error($con));
											while ($querypLog = mysqli_fetch_array($pLogData)) {

											?>
												<tr>
													<td><?php echo $querypLog["cashier_id"]; ?></td>
													<td><?php echo $querypLog["payment_ref"]; ?></td>
													<td><?php echo $currency . number_format($querypLog["amount_paid"], 2); ?></td>
													<td><?php echo $querypLog["pay_type"]; ?></td>
													<td><?php echo $querypLog["payment_date"]; ?></td>
												</tr>

											<?php }   ?>
										</tbody>
										</thead>

									</table>

								</div>
							</div>

							<div class="row">

								<div class="col-md-12">
									<p>
									<h4><b>Full Summary of Purchases</b></h4>
									</p>

									<table class="table" id="purchase_report">
										<thead>
											<tr>
												<th>Product Name</th>
												<th>Qty. Sold</th>
												<th>Selling Price</th>
												<th>Cost Price</th>
												<th>Profit</th>
											</tr>
										</thead>
										<tbody>
											<?php

											$today = date("Y-m-d");
											$sql = mysqli_query($con, "SELECT COUNT(product_id),product_id,order_date,product_name,cashier,quantity,sold_at_price
								,SUM(quantity) AS total_qty,SUM(sold_at_price) AS total_price FROM sold_products WHERE customer_id='$customerid'
								GROUP BY product_name ") or die(mysqli_error($con));

											while ($fetch = mysqli_fetch_array($sql)) {

												//get cost price
												$pid = $fetch["product_id"];

												$sql_cost =	 mysqli_query($con, "SELECT * FROM product WHERE product_id='$pid'") or die(mysqli_error($con));
												$data = mysqli_fetch_array($sql_cost);
												$costPrice =  round(intval($data["cost_price"] * $fetch["total_qty"]));
												echo "<tr>";
												echo "<td>" . $fetch["product_name"] . "</td>";
												echo "<td>" . $fetch["total_qty"] . "</td>";
												echo "<td>$currency" . number_format($fetch["total_price"], 2) . "</td>";
												echo "<td>$currency" . number_format($costPrice, 2) . "</td>";
												echo "<td>" . number_format($fetch["total_price"] - $costPrice, 2)  . "</td>";

											?>
											<?php

												echo "</tr>";
											}

											?>

										</tbody>
									</table>


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


						<?php } ?>
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
										</div>


<script src="../custom/js/report.js"></script>

<?php include "../partials/footer.php"; ?>