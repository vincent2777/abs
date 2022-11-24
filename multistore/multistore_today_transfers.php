<?php
include "../partials/header.php";
include "../partials/sidebar.php";
?>

<div class="main-panel">
	<div class="content-wrapper">
		<div class="row rounded-0 shadow-sm p-3 bg-white">
			<div class="col-md-12">

				<ol class="breadcrumb">
					<li><a href="../../dashboard">Home/ </a></li>
					<li class="active">Stock Transfer</li>
				</ol>

				<div class="panel panel-default mt-5">
					<div class="panel-heading">
						<div class="page-heading"> <i class="fas fa-spinner"></i> Transfer History</div>
					</div> <!-- /panel-heading -->

					<div class="panel-body">

						<?php
						if (!empty($_GET["tid"])) {

							$transfer_id = $_GET["tid"];
							$product_id = $_GET["pid"];
							$product_qty = $_GET["qty"];
							$query = mysqli_query($con, "DELETE FROM stock_transfer WHERE invoice_number='$transfer_id'");
							$query2 = mysqli_query($con, "UPDATE product
					SET quantity_rem=quantity_rem+'$product_qty' 
					WHERE product_id='$product_id'");

							if ($query && $query2) {
								echo "<div class='alert alert-success'>Stock Transfer has been reverted successfully.</div>";
							} else {
								echo "<div class='alert alert-danger'>Oops! An error occured. Please try again.</div>";
							}
						}

						?>

						<script type="text/javascript">
							$(document).ready(function() {
								$('#today_transfers').DataTable({
									dom: 'lBfrtip',
									"lengthMenu": [
										[10, 25, 50, -1],
										[10, 25, 50, "All"]
									]
								});
							});
						</script>
						<table class="table" id="today_transfers">
							<thead>
								<tr>
									<th>Issued By</th>
									<th>Invoice Number</th>
									<th>Product Name</th>
									<th>Store Name</th>
									<th>Qty</th>
									<th>Qty Remaining</th>
									<th>Date</th>
									<th>Time</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php

								$today = date("Y-m-d");
								$sql = mysqli_query($con, "SELECT * FROM stock_transfer WHERE transfer_date='$today' GROUP BY invoice_number ORDER BY id DESC") or die(mysqli_error($con));

								while ($fetch = mysqli_fetch_array($sql)) {

									echo "<tr>";
									echo "<td>" . $fetch["cashier"] . "</td>";
									echo "<td>" . $fetch["invoice_number"] . "</td>";
									echo "<td>" . $fetch["product_name"] . "</td>";
									echo "<td>" . $fetch["store_name"] . "</td>";
									echo "<td>" . $fetch["quantity"] . "</td>";
									echo "<td>" . $fetch["quantity_rem"] . "</td>";
									echo "<td>" . $fetch["transfer_date"] . "</td>";
									echo "<td>" . $fetch["transfer_time"] . "</td>";

								?>
									<td>

										<a class="btn btn-success" id="reprint_multistore_transfers?ref=<?php echo $fetch["invoice_number"]; ?>" onclick="reprintInvoice(this.id);">
											<i class="fas fa-print"></i>
										</a>


									</td>
								<?php

									echo "</tr>";
								}

								?>

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


<?php include '../partials/footer.php'; ?>