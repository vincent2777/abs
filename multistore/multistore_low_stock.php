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
									<th>Product ID</th>
									<th>Product Name</th>
											<?php
									$sql2 = "SELECT * FROM stores WHERE store_type !='branch'";
									$result2 = mysqli_query($con, $sql2) or die(mysqli_error($con));
									$numberOfStores = mysqli_num_rows($result2);
									while ($row2 = mysqli_fetch_array($result2)) {
									?>
										<th><?php echo ucwords($row2["store_name"]); ?></th>

									<?php } ?>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php


								$lowStockSql = "SELECT * FROM product";
								$lowStockQuery = $connect->query($lowStockSql);
								$countLowStock = $lowStockQuery->num_rows;

								while ($fetch = mysqli_fetch_array($lowStockQuery)) {

									$id = $fetch["product_id"];
									$low_alert = 10;

									$query_ttbl = "SELECT SUM(quantity_rem) as total_qty FROM stock_transfer WHERE product_id='$id' GROUP BY store_name ";
									$result_ttbl = mysqli_query($con, $query_ttbl) or die("database error:" . mysqli_error($con));

									echo "<td>" . $fetch["product_id"] . "</td>";
									echo "<td>" . $fetch["product_name"] . "</td>";
									$count = 0;

									while ($row2 = mysqli_fetch_assoc($result_ttbl)) {

										echo "<td>" . $row2["total_qty"] . "</td>";


								?>
										<?php
										if (!empty($row2["total_qty"]) && $row2["total_qty"] <= $low_alert) {

											while ($count <= $numberOfStores) {
												echo "<td></td>";
												$count++;
											}
										?>


											<td><i class="badge start" style="background-color: indigo">Running Out of Stock</i></td>
									<?php }
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