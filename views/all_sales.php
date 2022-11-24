<?php
include "../partials/header.php";
include "../partials/sidebar.php"; ;

?>
<div class="main-panel">
	<div class="content-wrapper">
<div class="row card rounded-0 p-3 shadow-sm">
	<div class="col-md-12">

		<ol class="breadcrumb">
			<li><a href="dashboard">Home</a></li>
			<li class="active">All Sales</li>
		</ol>

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="page-heading"> <i class="fas fa-th"></i> All Sales</div>
			</div> <!-- /panel-heading -->

			<div class="panel-body">

				<div class="alert alert-info" style="border-left: 5px solid blue;">
					<p><b>NB: - </b> 1. Items that have the same invoice number and Part payment status are designed in green colors
						<br>
						2. To edit Sales that have the same Invoice Number, Click on ONLY ONE of such item.
					</p>
				</div>
				<?php


				if (isset($_POST["completeSaleBtn"])) {


					$invoice_id = $_POST["modal_invoice_id"];
					$total = $_POST["modal_total"];
					$apaid = intval($_POST["modal_apaid"]);
					$balance = intval($_POST["modal_balance"]);
					$new_paymethod = $_POST["new_paymethod"];
					$final_paid_amount = $apaid + $balance;
					$payment_method = "";
					$payMethod = "";
					$pay_amt = 0;


					$getPayMethod = mysqli_query($con, "SELECT payment_method FROM sold_products WHERE invoice_number='$invoice_id'");
					while ($data = mysqli_fetch_array($getPayMethod)) {

						$payment_method = $data["payment_method"];
					}

					if ($new_paymethod == "Cash" && $payment_method == "Cash") {
						$payMethod = $payment_method;
						$pay_amt = $balance;

						$sql1 = "UPDATE sold_products SET 
						balance_amount='0', payment_method='$payMethod', 
						cashpayment_amt= cashpayment_amt + $pay_amt, 
						payment_type='Full Payment',
						paid_amount = '$final_paid_amount'
						WHERE invoice_number='$invoice_id'";
						$query1 = mysqli_query($con, $sql1) or die(mysqli_error($con));

						if ($query1) {
							echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Payment status for Sales invoice #$invoice_id has been updated.</div>";
						} else {
							echo "<div class='alert alert-danger'><i class='fas fa-info'></i>Oops! An error occured.. Please try again later.</div>";
						}
					} else if ($new_paymethod == "Bank/Internet Transfer" && $payment_method == "Cash") {
						$payMethod = $payment_method . ", Bank/Internet Transfer";
						$pay_amt = $balance;

						$sql1 = "UPDATE sold_products SET 
						balance_amount='0', payment_method='$payMethod', 
						bankpayment_amt = bankpayment_amt + $pay_amt,
						payment_type='Full Payment',
						paid_amount = '$final_paid_amount'
						WHERE invoice_number='$invoice_id'";
						$query1 = mysqli_query($con, $sql1) or die(mysqli_error($con));


						if ($query1) {
							echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Payment status for Sales invoice #$invoice_id has been updated.</div>";
						} else {
							echo "<div class='alert alert-danger'><i class='fas fa-info'></i>Oops! An error occured.. Please try again later.</div>";
						}
						} else if ($new_paymethod == "Bank/Internet Transfer" && $payment_method == "Bank/Internet Transfer") {
						$payMethod = $payment_method;
						$pay_amt = $balance;

						$sql1 = "UPDATE sold_products SET 
						balance_amount='0', payment_method='$payMethod', 
						bankpayment_amt = bankpayment_amt + $pay_amt,
						payment_type='Full Payment',
						paid_amount = '$final_paid_amount'
						WHERE invoice_number='$invoice_id'";
						$query1 = mysqli_query($con, $sql1) or die(mysqli_error($con));

						if ($query1) {
							echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Payment status for Sales invoice #$invoice_id has been updated.</div>";
						} else {
							echo "<div class='alert alert-danger'><i class='fas fa-info'></i>Oops! An error occured.. Please try again later.</div>";
						}
					} else if ($new_paymethod == "Cash" && $payment_method == "Bank/Internet Transfer") {
						$payMethod = $payment_method . ", Cash";
						$pay_amt = $balance;

						$sql1 = "UPDATE sold_products SET 
						balance_amount='0', payment_method='$payMethod', 
						cashpayment_amt= cashpayment_amt + $pay_amt, 
						payment_type='Full Payment',
						paid_amount = '$final_paid_amount'
						WHERE invoice_number='$invoice_id'";
						$query1 = mysqli_query($con, $sql1) or die(mysqli_error($con));


						if ($query1) {
							echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Payment status for Sales invoice #$invoice_id has been updated.</div>";
						} else {
							echo "<div class='alert alert-danger'><i class='fas fa-info'></i>Oops! An error occured.. Please try again later.</div>";
						}
					} else {
						$pay_method = $new_paymethod;
						$pay_amt = 0;
					}

				?>
					<script>window.open('today_sales','_self');</script>
				<?php
				}
				?>

				<script type="text/javascript">
					$(document).ready(function() {
						$('#allsales_table').DataTable({
							dom: 'lBfrtip',
							"lengthMenu": [
								[10, 25, 50, -1],
								[10, 25, 50, "All"]
							]
						});
					});
				</script>
				<table class="table table-striped" id="allsales_table">
					<thead>
						<tr>
							<th>Date</th>
							<th>Invoice Number</th>
							<th>Pay Type</th>
							<th>Customer Data</th>
							<th>Cash</th>
							<th>Bank</th>
							<th>Total Amount</th>
							<th>Discount</th>
							<th>Quantity</th>
							<th>Unit</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$count_array = array();

						$sql = mysqli_query($con, "SELECT order_date,invoice_number,payment_type,cashpayment_amt,bankpayment_amt,
						customer_name,customer_phone, total_amount, product_discount,SUM(quantity) as total_qty, SUM(product_discount) as total_discount FROM sold_products GROUP BY invoice_number ORDER BY order_id DESC") or die(mysqli_error($con));

						while ($fetch = mysqli_fetch_array($sql)) {

							array_push($count_array, $fetch["invoice_number"]);

							//get number of occurence
							$tmp = array_count_values($count_array);
							$cnt = $tmp[$fetch["invoice_number"]];
							if ($cnt > 0 && $fetch["payment_type"] == "Part Payment") {
								echo "<tr style='color: rgba(255,0,0,0.7)'>";
							} else {
								echo "<tr>";
							}

							echo "<td>" . $fetch["order_date"] . "</td>";
							echo "<td>" . $fetch["invoice_number"] . "</td>";
							echo "<td>" . $fetch["payment_type"] . "</td>";
							echo "<td>" . $fetch["customer_name"] . " <br> " . $fetch["customer_phone"] . "</td>";
							echo "<td>" . number_format($fetch["cashpayment_amt"],2) . "</td>";
							echo "<td>" . number_format($fetch["bankpayment_amt"],2) . "</td>";
							echo "<td>" . number_format($fetch["total_amount"],2) . "</td>";
							echo "<td>" . number_format($fetch["total_discount"],2) . "</td>";
							echo "<td>" . $fetch["total_qty"] . "</td>";
							echo "<td>" . ucwords($fetch["measurement_unit"]) . "</td>";

							if ($fetch["payment_type"] == "Part Payment") {


						?>
								<td>
									<!-- <button class="btn customize-abs-btn editSalesBtn" data-toggle="modal" data-target="#editSalesModal" onclick="editSales(this.id)" id="<?php echo $fetch["invoice_number"]; ?>">
										<i class="fas fa-edit"></i>
									</button> -->
									<?php
									if ($_SESSION["role"] == "owner" || $_SESSION["role"] == "manager") {
									?>
									<a class="btn customize-abs-btn" id="reprint_invoice?slipno=<?php echo $fetch["invoice_number"]; ?>" onclick="reprintInvoice(this.id);">
										<i class="fas fa-print"></i>
									</a>

									<?php } ?>

								</td>
								</tr>


							<?php } else { ?>
								<td>
									<a class="btn customize-abs-btn" id="reprint_invoice?slipno=<?php echo $fetch["invoice_number"]; ?>" onclick="reprintInvoice(this.id);">
										<i class="fas fa-print"></i>
									</a>

								</td>
								</tr>
						<?php
							}
						} ?>

					</tbody>

				</table>
				<!-- /table -->
			</div> <!-- /panel-body -->
		</div> <!-- /panel -->
	</div> <!-- /col-md-12 -->
</div> <!-- /row -->

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
</div>
</div>
</div>
</div>

<?php include "../partials/footer.php"; ?>