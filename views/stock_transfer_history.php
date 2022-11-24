<?php
include "../partials/header.php";
include "../partials/sidebar.php"; ;

?>

<div class="row">
	<div class="col-md-12">

		<ol class="breadcrumb">
			<li><a href="dashboard">Home</a></li>
			<li class="active">Stock Transfer</li>
		</ol>

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="page-heading"> <i class="fas fa-spinner"></i> Transfer History</div>
			</div> <!-- /panel-heading -->

			<div class="panel-body">

				<script type="text/javascript">
					$(document).ready(function() {
						$('#transfers').DataTable({
							dom: 'lBfrtip',
							"lengthMenu": [
								[10, 25, 50, -1],
								[10, 25, 50, "All"]
							]
						});
					});
				</script>
			<table class="table" id="transfers">
							<thead>
								<tr>
								<th>Issued By</th>
								<th>Invoice Number</th>
									<th>Product Name</th>
									<th>Product ID</th>
								<th>Branch/Store Name</th>
								<th>Branch/Store ID</th>
									<th>Quantity</th>
									<th>Date</th>
								</tr>
							</thead>
							<tbody>
								<?php

								$today = date("Y-m-d");
								$sql = mysqli_query($con, "SELECT * FROM stock_transfer") or die(mysqli_error($con));

								while ($fetch = mysqli_fetch_array($sql)) {

									echo "<tr>";
									echo "<td>" . $fetch["cashier"] . "</td>";
										echo "<td>" . $fetch["invoice_number"] . "</td>";
											echo "<td>" . $fetch["product_name"] . "</td>";
											echo "<td>" . $fetch["product_id"] . "</td>";
									echo "<td>" . $fetch["branch_name"] . "</td>";
									echo "<td>" . $fetch["branch_id"] . "</td>";
									
									echo "<td>" . $fetch["quantity"] . "</td>";
									echo "<td>" . $fetch["transfer_date"] . "</td>";

								?>
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



<?php include "../partials/footer.php"; ?>