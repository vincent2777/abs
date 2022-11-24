<?php
include "../partials/header.php";
include "../partials/sidebar.php";;

?>
<div class="main-panel">
	<div class="content-wrapper">

	<ol class="breadcrumb">
					<li><a href="../dashboard">Home/ </a></li>
					<li class="active">Credit History</li>
				</ol>
		<div class="row rounded-0 bg-white shadow-sm p-3">
			<div class="col-md-12">

				

				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="page-heading"> <i class="fas fa-chart-area"></i> All Credits</div>
					</div> <!-- /panel-heading -->

					<div class="panel-body">

						<script type="text/javascript">
							$(document).ready(function() {
								$('#chistory').DataTable({
									dom: 'lBfrtip',
									"lengthMenu": [
										[10, 25, 50, -1],
										[10, 25, 50, "All"]
									]
								});
							});
						</script>
						<table class="table table-striped" id="chistory">
							<thead>
								<tr>
									<th>Customer ID</th>
									<th>Customer Data</th>
									<th>Amount Paid</th>
									<th>Reference No.</th>
									<th>Payment Method</th>
									<th>Payment Date</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$today = date("Y-m-d");
								$sql = mysqli_query($con, "SELECT * FROM balance_sheet ORDER BY id DESC") or die(mysqli_error($con));

								while ($fetch = mysqli_fetch_array($sql)) {

									$cust_id = $fetch["customer_id"];
									$sql2 = mysqli_query($con, "SELECT * FROM customers WHERE cust_id='$cust_id'") or die(mysqli_error($con));
									$row = mysqli_fetch_array($sql2);

									$date = date("d F, Y", strtotime($fetch["payment_date"]));
									echo "<tr>";
									echo "<td>" . $fetch["customer_id"] . "</td>";
									echo "<td>" . ucwords($row["cust_name"]) . " <br> " . $row["cust_phone"] . "</td>";
									echo "<td>" . number_format($fetch["amount_paid"], 2) . "</td>";
									echo "<td>" . $fetch["payment_ref"] . "</td>";
									echo "<td>" . ucwords($fetch["pay_type"]) . "</td>";
									echo "<td>" .  $date . "</td>";

								?>

									<td>

										<a class="btn customize-abs-btn" id="reprint_payslip?ref=<?php echo $fetch["payment_ref"]; ?>" onclick="reprintInvoice(this.id);">
											<i class="fas fa-print"></i>
										</a>

									</td>
									</tr>
								<?php } ?>
							</tbody>

						</table>

					</div> <!-- /panel-body -->
				</div> <!-- /panel -->
			</div> <!-- /col-md-12 -->
		</div> <!-- /row -->

	</div>
</div>
</div>
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

<script src="script/edit_sales.js"></script>

<?php include "../partials/footer.php"; ?>