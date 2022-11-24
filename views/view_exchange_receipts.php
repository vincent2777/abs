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
							<h3 class="p-2">All Exchange Receipts</h3>
						</div>
					</div> <!-- /panel-heading -->

					<div class="panel-body">

						<script type="text/javascript">
							$(document).ready(function() {
								$('#exchanged_sales').DataTable({
									dom: 'lBfrtip',
									"lengthMenu": [
										[10, 25, 50, -1],
										[10, 25, 50, "All"]
									]
								});
							});
						</script>
						<table class="table table-striped" id="exchanged_sales">
							<thead>
								<tr>
									<th>Order Date</th>
									<th>Cashier</th>
									<th>Invoice Number</th>
									<th>Product Name</th>
									<th>Quantity</th>
									<th>Pay Type</th>
									<th>Customer Data</th>
									<th>Exchanged On</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$sql = mysqli_query($con, "SELECT customer_name,product_name,order_date,cashier,invoice_number,
								quantity,payment_type,customer_phone,exchange_date,exchange_time
								FROM exchanged_receipts 
								ORDER BY invoice_number DESC") or die(mysqli_error($con));

								while ($fetch = mysqli_fetch_array($sql)) {

									echo "<tr>";

									echo "<td>" . $fetch["order_date"] . "</td>";
									echo "<td>" . $fetch["cashier"] . "</td>";
									echo "<td>" . $fetch["invoice_number"] . "</td>";
									echo "<td>" . $fetch["product_name"] . "</td>";
									echo "<td>" . $fetch["quantity"] . "</td>";
									echo "<td>" . $fetch["payment_type"] . "</td>";
									echo "<td>" . $fetch["customer_name"] . " <br> " . $fetch["customer_phone"] . "</td>";
									echo "<td>" . $fetch["exchange_date"] ." ". $fetch["exchange_time"]. "</td>";

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

	<?php include "../partials/footer.php"; ?>