<?php
include "../partials/header.php";
include "../partials/sidebar.php";;
?>


<div class="main-panel">
	<div class="content-wrapper">
		<div class="row p-2 mt-1 g-white mb-5 justify-content-center" style="background-color: white !important;">
			<div class="row justify-content-center">
				<div class="col p-5">

					<?php

					if (isset($_POST["reverseReceiptBtn"])) {
						//extract product ID and qty sold from sold products table
						$getInvNumber = $_POST["getInvNo"];
						$sql1 = mysqli_query($con, "SELECT * FROM sold_products WHERE invoice_number = '$getInvNumber'");
						$reversal_date = date("Y-m-d h:i:s");

						$customerid = "";
						$price = 0;
						$paid = 0;

						while ($data = mysqli_fetch_array($sql1)) {

							$qty = $data["quantity"];
							$pid = $data["product_id"];
							$customerid = $data["customer_id"];
							$paid = $data["paid_amount"];
							$price = $data["total_amount"];
							$store_id = $_SESSION["store_id"];

							//use product id and qty to update products table
							$sql2 = mysqli_query($con, "UPDATE product SET quantity_rem=quantity_rem+$qty WHERE product_id='$pid'");

							//move invoice data from sold products to revert receipt table with data of reversal
							$sql3 = "INSERT INTO reverted_receipts (store_id,order_time,cashpayment_amt,bankpayment_amt,cashier,customer_name,customer_phone,customer_address,order_date,invoice_number,product_name,product_id,quantity,
									sold_at_price,expected_sale_price,total_amount,paid_amount,balance_amount,product_discount,payment_type,payment_method)
									SELECT store_id,order_time,cashpayment_amt,bankpayment_amt,cashier,customer_name,customer_phone,customer_address,order_date,invoice_number,product_name,product_id,quantity,sold_at_price,
									expected_sale_price,total_amount,paid_amount,balance_amount,product_discount,payment_type,payment_method
									FROM sold_products
									WHERE invoice_number = '$getInvNumber'";

							$query3 = mysqli_query($con, $sql3) or die(mysqli_error($con));

							//update and set reversal_date
							$sql_reverse = mysqli_query($con, "UPDATE reverted_receipts SET reversal_date='$reversal_date' WHERE invoice_number = '$getInvNumber'");

							//check if data has been copied before deleting
							if ($sql3) {
								$sql4 = "DELETE FROM sold_products
										WHERE invoice_number = '$getInvNumber'";
								$query4 = mysqli_query($con, $sql4);
							} else {
								echo "<div class='alert alert-danger'><i class='fas fa-check-circle'></i> An error occured. Try again later.</div>";
							}
						}

						$newBal = $price - $paid;

						if ($price > $paid) {
							//bought on credit
							$sql6 = mysqli_query($con, "UPDATE customers SET cust_owing=cust_owing-$newBal WHERE cust_id='$customerid'");
						}

						if ($query3 && $query4) {
							echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Invoice has been Removed from Sales History</div>";
						} else {
							echo "<div class='alert alert-danger'><i class='fas fa-info'></i> Oopsss! An error occured..Please try again later.</div>";
						}

					}

					if (isset($_POST['checkInvoiceBtn'])) {

					?>
						<script type="text/javascript">
							$(document).ready(function() {
								$('#receipt_reversal').DataTable({
									dom: 'lBfrtip',
									"lengthMenu": [
										[10, 25, 50, -1],
										[10, 25, 50, "All"]
									]
								});
							});
						</script>

						<form action="" method="post">

							<div class="overflow-x:auto">
								<table class="table table-striped ml-4" id="receipt_reversal">
									<thead>
										<tr>
											<th>Date</th>
											<th>Invoice Number</th>
											<th>Product Name</th>
											<th>Pay Type</th>
											<th>Customer Name</th>
											<th>Customer Phone</th>
											<th>Quantity</th>
										</tr>
									</thead>
									<tbody>
										<?php

										$inv_number = $_POST['inv_number'];
										$sql = "SELECT * FROM sold_products WHERE invoice_number = '$inv_number'";
										$result = $con->query($sql);

										while ($fetch = mysqli_fetch_array($result)) {

											echo "<tr>";
											echo "<td>" . $fetch["order_date"] . "</td>";
											echo "<td>" . $fetch["invoice_number"] . "</td>";
											echo "<td>" . $fetch["product_name"] . "</td>";
											echo "<td>" . $fetch["payment_type"] . "</td>";
											echo "<td>" . $fetch["customer_name"] . "</td>";
											echo "<td>" . $fetch["customer_phone"] . "</td>";
											echo "<td>" . $fetch["quantity"] . "</td>
												 <input type='hidden' value='" . $fetch["invoice_number"] . "' id='getInvNo' name='getInvNo'>";
											echo "</tr>";
										?>
										<?php } ?>

									</tbody>
								</table>
							</div>
							<center>
								<button class="customize-abs-btn p-2" name="reverseReceiptBtn" onclick="return confirmReversal(document.getElementById('getInvNo').value)"><i class="mdi mdi-trash-can"></i> <br>Reverse</button>

							</center>
						</form>

				</div>

			</div>
		</div>
	<?php
					} else {
	?>

		<div class="row justify-content-center mx-auto mt-1 g-white">

			<div class="col p-3 mb-5">
				<div class="card shadow">

					<div class="card tale-bg">
						<center>
							<h3 class="text-dark p-2"> Receipt Reversal</h3>
						</center>
					</div>
					<form class="p-5" method="post">

						<fieldset>
							<div class="form-group">
								<label for="username" class="control-label">Receipt/Invoice Number</label>
								<div class="col-sm-12">
									<input type="text" class="form-control" autocomplete="off" required id="inv_number" name="inv_number" placeholder="Invoice Number" />
								</div>
							</div>

							<br>
							<div class="form-group">
								<center>
									<button type="submit" name="checkInvoiceBtn" class="btn btn-warning p-2 w-100">
										<i class="mdi mdi-nfc-tap"></i> Retrieve </button>
								</center>
							</div>
						</fieldset>
					</form>
				</div>

			</div>
			<!-- /panel -->
		</div>

	<?php } ?>
	</div>
</div>
</div>


<script type="text/javascript">

	function confirmReversal(invoice) {

		var x = window.confirm("You are about Reversing the Sale with Invoice Number #" + invoice + ". Do you wish to proceed?");

		if (x == true) {
			window.open("receipt_reversal", '_self');
			return true;
		} else {
			event.preventDefault();
			return false;
		}
	}
</script>


<?php include "../partials/footer.php"; ?>