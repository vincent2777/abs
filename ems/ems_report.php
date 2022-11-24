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
	<ol class="breadcrumb">
			<li><a href="../dashboard">Home/ </a></li>
			<li class="active">Generate Report</li>
		</ol>

<div class="row bg-white shadow-sm card p-3 rounded-0">
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
								<label for="report_type" class="col-sm-2 control-label">Type of Report</label>
								<div class="col-sm-10">
									<select name="report_type" id="report_type" required class="form-control" onchange="toggleFilterVisibility(this.value)">
										<option value="" selected>-Select-</option>
										<option value="stakehold">Stakehold</option>
										<option value="employee">Employee</option>
										<option value="salary">Salary Payment</option>
										<option value="attendance">Attendance</option>

									</select>
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" class="btn customize-abs-btn btn-block" name="generateReportBtn" id="generateReportBtn"> <i class="fas fa-ok-sign"></i> Generate Report</button>
								</div>
							</div>
						</div>
						<div class="col-md-2"></div>

					</div>
				</form>

				<script type="text/javascript">
					$(document).ready(function() {
						$('#emsreport').DataTable({
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

					//query the above information based on paytype
					if ($reportType == "stakehold") {

				?>
						<div class="row" style="font-family: 'Time New Roman';">
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

									<h2 style="text-decoration: underline;"><b>Stakehold Report</b></h2>

									<br>
								</center>
							</div>
						</div>

								<div>
									<h2>
									<?php generateStakeholdReport($con, $getStartDate, $getEndDate,$currency); ?>

					</h2>
								</div>						
						<br><br>
						<center>
							<button class="btn customize-abs-btn hidden-print" onclick="window.print()">
								<i class="fas fa-print fa-2x"></i> Print All</button>
						</center>

					<?php

					} elseif ($reportType == "employee") {

					?>

						<div class="row" style="font-family: 'Time New Roman';">
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

									<h2 style="text-decoration: underline;"><b>Employee Report <?php echo "From ".date("M. d",strtotime($getStartDate))." - ".date("M. d",strtotime($getEndDate)); ?></b></h2>

									<br>
								</center>
							</div>
						</div>

						<table class="table" id="emsreport">
							<thead>
								<tr>
									<th></th>
									<th>Employee ID</th>
									<th>Name</th>
									<th>Phone Number</th>
									<th>Total Salary Received</th>
									<th>Total Stakehold Received</th>
									<th>Total Products Sold</th>
									<th>Total Amounts Sold</th>
									<th>Employment Date</th>

								</tr>
							</thead>
							<tbody>
								<?php generateEmployeeReport($con, $getStartDate, $getEndDate, $currency); ?>
							</tbody>
						</table>

						<br><br>
						<center>
							<button class="btn customize-abs-btn hidden-print" onclick="window.print()">
								<i class="fas fa-print fa-2x"></i> Print All</button>
						</center>

					<?php
					} elseif ($reportType == "salary") {
					?>

						<div class="row" style="font-family: 'Time New Roman';">
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

									<h2 style="text-decoration: underline;"><b>Salary Payment Report <?php echo "From ".date("M. d",strtotime($getStartDate))." - ".date("M. d",strtotime($getEndDate)); ?></b></h2>

									<br>
								</center>
							</div>
						</div>

						<table class="table" id="emsreport">
							
							<tbody>
								<?php generateSalaryReport($con, $getStartDate, $getEndDate, $currency); ?>
							</tbody>
						</table>

						<br>
						<br>

						<div class="row">

							<div class="col-md-3">
								<?php

									$sql = mysqli_query($con, "SELECT * FROM users") or die(mysqli_error($con));
									$total_staff = mysqli_num_rows($sql);
								
								    //total salary received
									$sql_lp = mysqli_query($con, "SELECT SUM(amount_paid) as total FROM salary_payments WHERE payment_date BETWEEN '$getStartDate' AND '$getEndDate'") or die(mysqli_error($con));
									$fetch_lp = mysqli_fetch_array($sql_lp);
									$total_salary = $fetch_lp["total"];

								?>
								
							
								<p>
								<h4><b>Summary</b></h4>
								</p>
								<table class="table table-borderless pay-summary">
									<tr>
										<td>Total No. of Staff</td>
										<td> - </td>
										<td><?php echo $total_staff;  ?></td>
									</tr>

									<tr>
										<td>Total Salary Paid</td>
										<td> - </td>
										<td><?php echo "$currency" . number_format($total_salary, 2);  ?></td>
									</tr>

								</table>

							</div>

							<div class="col-md-4">
							</div>

							<div class="col-md-5">

								<?php

								$salaryData = array(
									array("label" => "Total Staff", "symbol" => "TS", "y" => $total_staff),
									array("label" => "Total Salary Paid", "symbol" => "TS", "y" => $total_salary)
								);

								?>

								<script>
									window.onload = function() {

										var salarychart = new CanvasJS.Chart("totalSalaryContainer", {
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
												dataPoints: <?php echo json_encode($salaryData, JSON_NUMERIC_CHECK); ?>
											}]
										});
										salarychart.render();
									}
								</script>

								<div class="row" style="margin-top: 30px;">
									<div class="col-md-8">
										<div id="totalSalaryContainer" style="height: 350px; width: 100%;"></div>
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
					} elseif ($reportType == "attendance") {
					?>

						<div class="row" style="font-family: 'Time New Roman';">
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

									<h2 style="text-decoration: underline;"><b>Staff Attendance Report <?php echo "From " . date("M. d", strtotime($getStartDate)) . " - " . date("M. d", strtotime($getEndDate)); ?></b></h2>

									<br>
								</center>
							</div>
						</div>

						<table class="table" id="emsreport">

							<tbody>
								<?php generateAttendanceReport($con, $getStartDate, $getEndDate, $currency, $work_resumes, $work_closes, $lateness_fee); ?>
							</tbody>
						</table>
						<br>
						<br>


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
					} else {
						echo "Please Select a Report Type.";
					}
				}

				?>
			</div>
		</div>
	</div>

</div>


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
<?php include '../includes/footer.php'; ?>