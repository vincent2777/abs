<?php 
include 'includes/db_connect.php'; 
include "includes/check_role_header.php"; 
?>


<div class="row">
	<div class="col-md-12">

		<ol class="breadcrumb">
			<li><a href="../dashboard.php">Home</a></li>
			<li class="active">Dispensed Orders</li>
		</ol>

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="page-heading"> <i class="fas fa-edit"></i>Dispensed Orders</div>
			</div> <!-- /panel-heading -->
			<div class="panel-body">

				<div class="remove-messages"></div>

				<script type="text/javascript">
					$(document).ready(function() {
						$('#dispensed_order').DataTable({
							dom: 'lBfrtip',
							"lengthMenu": [
								[10, 25, 50, -1],
								[10, 25, 50, "All"]
							]
						});

					});
				</script>
				<table class="display" id="dispensed_order">
					<thead>
						<tr>
							<th>Product Sold By <br>(Cashier ID)</th>
							<th>Invoice Number</th>
							<th>Product Name</th>
							<th>Quantity Purchased</th>
							<th>Total Amount (NGN)</th>
							<th>Customer Name</th>
							<th>Customer Phone</th>
							<th>Payment Type</th>
							<th>Payment Method</th>
							<th>Date</th>

						</tr>
					</thead>
					<tbody>
						<?php


						$sql = mysqli_query($con, "SELECT * FROM sold_products") or die(mysqli_error($con));

						while ($fetch = mysqli_fetch_array($sql)) {

							echo "<tr>";
							echo "<td>" . $fetch["cashier"] . "</td>";
							echo "<td>" . $fetch["invoice_number"] . "</td>";
							echo "<td>" . $fetch["product_name"] . "</td>";
							echo "<td>" . $fetch["quantity"] . "</td>";
							echo "<td>" . $fetch["total_amount"] . "</td>";
							echo "<td>" . $fetch["customer_name"] . "</td>";
							echo "<td>" . $fetch["customer_phone"] . "</td>";
							echo "<td>" . $fetch["payment_type"] . "</td>";
							echo "<td>" . $fetch["payment_method"] . "</td>";
							echo "<td>" . $fetch["order_date"] . "</td>";

						?>
						<?php

							echo "</tr>";
						}

						?>

					</tbody>
				</table>
				<!-- /table -->

			</div> <!-- /panel-body -->
		</div> <!-- /panel -->
	</div> <!-- /col-md-12 -->
</div> <!-- /row -->


<script src="custom/js/list_place_order.js"></script>

<?php include "../partials/footer.php"; ?>