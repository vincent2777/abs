<?php 
include 'includes/db_connect.php'; 
include "includes/check_role_header.php"; 

?>

<div class="row">
	<div class="col-md-12">

		<ol class="breadcrumb">
			<li><a href="dashboard">Home</a></li>
			<li class="active">View Returns</li>
		</ol>

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="page-heading"> <i class="fas fa-th"></i> All Returned Products</div>
			</div> <!-- /panel-heading -->
			
			<div class="panel-body">

				<script type="text/javascript">
					$(document).ready(function() {
						$('#sales').DataTable({
							dom: 'lBfrtip',
								"lengthMenu": [
									[10, 25, 50, -1],
									[10, 25, 50, "All"]
								]
						});
					});
				</script>
				<table class="table table-striped" id="sales">
					<thead>
						<tr>
							<th>Order Date</th>
							<th>Cashier</th>
							<th>Invoice Number</th>
							<th>Product Name</th>
							<th>Quantity</th>
							<th>Pay Type</th>
							<th>Customer Data</th>
							<th>Returned On</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$sql = mysqli_query($con, "SELECT * FROM returned_receipts ORDER BY order_id DESC") or die(mysqli_error($con));

						while ($fetch = mysqli_fetch_array($sql)) {
						
								echo "<tr>";
								
							echo "<td>" . $fetch["order_date"] . "</td>";
							echo "<td>" . $fetch["cashier"] . "</td>";
							echo "<td>" . $fetch["invoice_number"] . "</td>";
							echo "<td>" . $fetch["product_name"] . "</td>";
							echo "<td>" . $fetch["quantity"] . "</td>";
							echo "<td>" . $fetch["payment_type"] . "</td>";
							echo "<td>" . $fetch["customer_name"] . " <br> " . $fetch["customer_phone"] . "</td>";
							echo "<td>" . $fetch["return_date"] . "</td>";
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


<?php include "../partials/footer.php"; ?>