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
							<h3 class="p-2">Withheld Receipts</h3>
						</div>
					</div> <!-- /panel-heading -->
					<div class="panel-body">

						<div class="alert alert-info" style="border-left: 5px solid blue;">
							<h4 style="color: #0a011a;"><strong>Important</strong></h4>
							<p>1. Use the Invoice Number, Date or Time to Identify the Receipt you wish to unhold
								<br>
								2. Not all items that were held in a particular receipt will be visible.. Kindly identify the
								receipt and click to unhold button to proceed.
							</p>
						</div>

						<?php

						if (!empty($_GET["delete_invoice"])) {
							$inv_no = $_GET["delete_invoice"];
							$sql = mysqli_query($connect, "DELETE FROM held_receipts WHERE invoice_number='$inv_no'") or die(mysqli_error($connect));
							if ($sql) {
								echo "<div class='alert alert-success' style='border-left: 5px solid green;'><i class='fas fa-check-circle'></i> Product has been removed from Held Receipts.</div>";
							} else {
								echo "<div class='alert alert-danger' style='border-left: 5px solid red;'><i class='fas fa-minus'></i> Oops!!..An error occured. Try again later.</div>";
							}
						}

						?>
						<script type="text/javascript">
							$(document).ready(function() {
								$('#receipt').DataTable({
									dom: 'lBfrtip',
									"lengthMenu": [
										[10, 25, 50, -1],
										[10, 25, 50, "All"]
									]
								});
							});
						</script>
						<div class="overflow-x:auto">
							<table class="table table-striped" id="receipt">
								<thead>
									<tr>

										<th>Invoice No.</th>
										<th>Seller</th>
										<th>Product ID</th>
										<th>Product Name</th>
										<th>Quantity</th>
										<th>Customer Data</th>
										<th>Date</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php

									$current_user = $_SESSION["user"];

									$sql = mysqli_query($con, "SELECT DISTINCT customer_address,order_date,product_name,SUM(product_price) as total_price,SUM(quantity) as total_qty, customer_name,customer_phone,product_id,cashier,invoice_number FROM held_receipts WHERE cashier='$current_user' GROUP BY invoice_number ORDER BY order_date ASC") or die(mysqli_error($con));

									while ($fetch = mysqli_fetch_array($sql)) {

										echo "<tr>";
										echo "<td>" . $fetch["invoice_number"] . "</td>";
										echo "<td>" . $fetch["cashier"] . "</td>";
										echo "<td>" . $fetch["product_id"] . "</td>";
										echo "<td>" . $fetch["product_name"] . "</td>";
										echo "<td>" . $fetch["total_qty"] . "</td>";
										echo "<td>" . $fetch["customer_name"] . " 
							<br> " . $fetch["customer_phone"] . " 
							<br>
							" . $fetch["customer_address"] . " </td>";
										echo "<td>" . $fetch["order_date"] . "</td>";
										echo "<td>
							<a class='btn customize-abs-btn p-2' href='unhold_receipt?invoice=" . $fetch["invoice_number"] . "'>Unhold</a>
							<a class='btn customize-abs-btn p-2' href='held_receipts?delete_invoice=" . $fetch["invoice_number"] . "'><i class='mdi mdi-trash-can'></i></a>
							</td>";
									}
									?>

								</tbody>
							</table>
						</div>

					</div> <!-- /panel-body -->
				</div> <!-- /panel -->
			</div> <!-- /col-md-12 -->
		</div> <!-- /row -->
	</div>
	<!-- /categories brand -->

	<?php include "../partials/footer.php"; ?>