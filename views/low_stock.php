<?php
include "../partials/header.php";
include "../partials/sidebar.php";;

?>
<div class="main-panel">
	<div class="content-wrapper">
		<div class="row bg-white shadow-sm rounded-0 p-3">
			<div class="col-md-12">

				<ol class="breadcrumb">
					<li><a href="../dashboard">Home/ </a></li>
					<li class="active">Low Stock</li>
				</ol>

				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="page-heading m-3"> <i class="fas fa-th"></i> Low Stock</div>
					</div> <!-- /panel-heading -->

					<div class="panel-body">

						<div class="alert alert-info" style="border-left: 5px solid blue;">
							<p><b>NB: - </b> Kindly, alert the Store Keeper or Manager to Re-order Items.

							</p>
						</div>


						<script type="text/javascript">
							$(document).ready(function() {
								$('#low_stock').DataTable({
									dom: 'lBfrtip',
									"lengthMenu": [
										[10, 25, 50, -1],
										[10, 25, 50, "All"]
									]
								});
							});
						</script>
						<table class="table table-striped" id="low_stock">
							<thead>
								<tr>
									<th></th>
									<th>Product ID</th>
									<th>Product Name</th>
									<th>Quantity Purchased</th>
									<th>Quantity Remaining</th>

								</tr>
							</thead>
							<tbody>
								<?php


								$lowStockSql = "SELECT * FROM product";
								$lowStockQuery = $connect->query($lowStockSql);
								$countLowStock = $lowStockQuery->num_rows;
								$count = 1;
								while ($fetch = mysqli_fetch_array($lowStockQuery)) {
									$id = $fetch["product_id"];

									$low_sql = "SELECT reorder_level FROM product WHERE product_id='$id'";
									$sql_result = mysqli_query($con, $low_sql) or die(mysqli_error($con));
									$data = mysqli_fetch_array($sql_result);
									$low_alert = $data["reorder_level"];

									if ($fetch["quantity_rem"] < $low_alert) {
										echo "<td>" . $count++ . "</td>";
										echo "<td>" . $fetch["product_id"] . "</td>";
										echo "<td>" . $fetch["product_name"] . "</td>";
										echo "<td>" . $fetch["quantity"] . "</td>";
										echo "<td>" . $fetch["quantity_rem"] . "</td>";
									}
								?>

									</tr>

								<?php }  ?>


							</tbody>
						</table>
						<!-- /table -->

					</div> <!-- /panel-body -->
				</div> <!-- /panel -->
			</div> <!-- /col-md-12 -->
		</div> <!-- /row -->

	</div>
</div>
</div>
</div>
<?php include "../partials/footer.php"; ?>