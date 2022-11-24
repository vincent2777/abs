<?php
include "../partials/header.php";
include "../partials/sidebar.php";
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
</style>

<div class="main-panel">
	<div class="content-wrapper">
		<div class="row rounded-0 shadow-sm p-3 bg-white">
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
								<div class="col-md-8">
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

									<br>
									<div class="form-group">
										<button type="submit" class="btn btn-success btn-block ml-3" name="generateReportBtn" id="generateReportBtn"> <i class="fas fa-ok-sign"></i> Generate Report</button>
									</div>

								</div>


							</div>


						</form>
					</div>
				</div>

			</div>

		</div>

		<script type="text/javascript">
			$(document).ready(function() {
				$('#multistore_report').DataTable({
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

		<?php

		if (isset($_POST["generateReportBtn"])) {
			$getStartDate = $_POST["startDate"];
			$getEndDate = $_POST["endDate"];
			$reportType = $_POST["report_type"];

		?>

			<div class="row mt-3 p-3 rounded-0 shadow-sm bg-white" style="font-family: 'Time New Roman';">
				<div class="col-md-12 mx-auto">
					<center>
						<?php
						$getData = mysqli_query($con, "SELECT * FROM settings") or die(mysqli_error($con));
						$fetchData = mysqli_fetch_array($getData);
						?>
						<h1><b><?php echo $fetchData["company_name"]; ?></b>
							<br>
							<span style="font-size: 20px;"><?php echo $fetchData["company_address"]; ?></span>
							<br>
							<span style="font-size: 20px;">
							<?php echo $fetchData["company_phone"]; ?>
							</span>
						</h1>


						<h2 style="text-decoration: underline;"><b>Multistore Stock Report</b></h2>

						<br>
					</center>
				</div>
			</div>

			<div class="row bg-white p-3">
				<div class="col-md-12">

			<h2><b>TRANSFERS</b></h2>
			<table class="table" id="multistore_report">
				<thead>
					<tr>
						<th>Issued By</th>
						<th>Invoice Number</th>
						<th>Product Name</th>
						<th>Product ID</th>
						<th>Store Name</th>
						<th>Quantity</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
					<?php

					$today = date("Y-m-d");
					$sql = mysqli_query($con, "SELECT * FROM stock_transfer WHERE transfer_date BETWEEN '$getStartDate' AND '$getEndDate'") or die(mysqli_error($con));

					while ($fetch = mysqli_fetch_array($sql)) {

						echo "<tr>";
						echo "<td>" . $fetch["cashier"] . "</td>";
						echo "<td>" . $fetch["invoice_number"] . "</td>";
						echo "<td>" . $fetch["product_name"] . "</td>";
						echo "<td>" . $fetch["product_id"] . "</td>";
						echo "<td>" . $fetch["store_name"] . "</td>";
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

			<br>
			<p><b>NB:</b> This Report (is) and remains a property of the above named company
				and as such, <br> anyone found to have altered or falsified any part of this
				document will be subjected to <br> the Company Law(s) of the
				Federal Republic of Nigeria. </p>

			<br><br>
			<center>
				<button class="btn btn-success hidden-print" onclick="window.print()">
					<i class="fas fa-print fa-2x"></i> Print All</button>
			</center>
				</div>
			</div>

		<?php } ?>

	</div>
	<!-- /col-dm-12 -->
</div>
<!-- /row -->

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
</div>
</div>
</div>
</div>
</div>
</div>


<?php include '../partials/footer.php'; ?>