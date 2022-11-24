<?php
include "../partials/header.php";
include "../partials/sidebar.php"; ;

?>

<div class="main-panel">
	<div class="content-wrapper">

	<ol class="breadcrumb">
			<li><a href="../dashboard">Home/ </a></li>
			<li class="active">Credit Limit Changes</li>
		</ol>

<div class="row rounded-0 shadow-sm bg-white p-3">
	
	<div class="col-md-12">

		
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="page-heading"> <i class="fas fa-spinner"></i> Credit Log</div>
			</div> <!-- /panel-heading -->

			<div class="panel-body">

				<script type="text/javascript">
					$(document).ready(function() {
						$('#climithistory').DataTable({
							dom: 'lBfrtip',
							"lengthMenu": [
								[10, 25, 50, -1],
								[10, 25, 50, "All"]
							]
						});
					});
				</script>
			<table class="table table-striped" id="climithistory">
					<thead>
						<tr>
                        <th>Issued By</th>
                        <th>Customer ID</th>
                        <th>Customer Data</th>
                        <th>Previous Amount</th>
                        <th>New Amount</th>
                        <th>Transaction Date</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$today = date("Y-m-d"); 
						$sql = mysqli_query($con, "SELECT * FROM customer_credit_log") or die(mysqli_error($con));

						while ($fetch = mysqli_fetch_array($sql)) {

							$cust_id = $fetch["customer_id"];
							$sql2 = mysqli_query($con, "SELECT * FROM customers WHERE cust_id='$cust_id'") or die(mysqli_error($con));
							$row = mysqli_fetch_array($sql2);

							$date = date("d F, Y",strtotime($fetch["change_date"]));
							echo "<tr>";
                            echo "<td>" .ucwords($fetch["cashier_id"]) . "</td>";
							echo "<td>" . $fetch["customer_id"] . "</td>";
							echo "<td>" . $row["cust_name"] . " <br> " . $row["cust_phone"] . "</td>";
							echo "<td>" .$currency. number_format($fetch["prev_amount"],2) . "</td>";
							echo "<td>" .$currency. number_format($fetch["new_amount"],2) . "</td>";
							echo "<td>" .  $date. "</td>";

                        ?>
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