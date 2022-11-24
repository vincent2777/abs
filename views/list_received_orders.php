<?php
include "../partials/header.php";
include "../partials/sidebar.php"; ;
?>

<div class="main-panel">
  <div class="content-wrapper">
<div class="row bg-white shadow-sm rounded-0 p-3">
	<div class="col-md-12">

		<ol class="breadcrumb">
			<li><a href="../dashboard">Home/ </a></li>
			<li class="active">All Orders</li>
		</ol>

		<?php

		if (isset($_POST["submitForm"])) {

			$order_date = date('Y-m-d', strtotime($_POST['orderDate']));
			$quantity = $_POST["quantity"];
			$totalAmount = $_POST["totalAmount"];
			$po_number = $_POST["po_number"];
			$today = date("Y-m-d");
		
			//get order details from placed orders table using the invoice number
			$sql_extract = "SELECT product_name,supplier_name FROM placed_orders WHERE po_number='$po_number'";
			$query_extract = mysqli_query($con, $sql_extract);
			$row = mysqli_fetch_array($query_extract);
			$pname = $row["product_name"];
			$supplier = $row["supplier_name"];
			

			$oldAmt = 0;
			$getPOQuery = mysqli_query($con, "SELECT SUM(totalAmount) as old_amount FROM placed_orders WHERE po_number='$po_number' GROUP BY po_number") or die(mysqli_error($con));
			while ($fetchData = mysqli_fetch_array($getPOQuery)) {
				$oldAmt = $fetchData["old_amount"];
			}

			$getQuery = mysqli_query($con, "SELECT * FROM product WHERE po_number='$po_number'") or die(mysqli_error($con));
			$fetchSB = mysqli_fetch_array($getQuery);
			$oldQtyRem = $fetchSB["quantity_rem"];
			$oldCostPrice = $fetchSB["cost_price"];

			//price of rem qty
			//cost price * qty remaining
			$oldStockBalance = $oldCostPrice * $oldQtyRem;

			$newCostprice = intval($oldStockBalance + $totalAmount) / intval($oldQtyRem + $quantity);
			$newCostprice = round($newCostprice, 2);

			$cost_price = $totalAmount / $quantity;
			$store_id = $_SESSION["store_id"];

			//generate order number
			$sql2 = mysqli_query($connect, "SELECT * FROM placed_orders ORDER BY id DESC LIMIT 1") or die(mysqli_error($connect));
			$data = mysqli_fetch_array($sql2);
			$getLastOrderNo = $data["order_number"];
			$order_number = $getLastOrderNo + 1;

			//insert new data to placed orders table
			$sql_insert = mysqli_query($con, "INSERT INTO 
			placed_orders(store_id,cost_price,quantity,order_number,product_name,supplier_name,order_date,order_receive_date,order_status,totalAmount,po_number) 
			VALUES('$store_id','$cost_price','$quantity','$order_number','$pname','$supplier','$order_date','$today','Received','$totalAmount','$po_number')") or die(mysqli_error($con));

			//set new quantity

			//old qty + new
			$max_to_sell = $oldQtyRem + $quantity;
			$sql_products = mysqli_query($con, "UPDATE product SET cost_price='$newCostprice', max_to_sell='$max_to_sell', quantity=quantity+$quantity, quantity_rem=quantity_rem+$quantity WHERE po_number='$po_number'") or die(mysqli_error($con));

			if ($sql_products && $sql_insert) {
				echo "<div class=\"alert alert-success\">
		            <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
		            <strong><i class=\"fas fa-check-circle\"></i>
					 Congratulations! Order have been received and Product Quantity has been Updated. <br>
					 </strong></div>";
			} else {
				echo "<div class=\"alert alert-danger\">
		            <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
		            <strong><i class=\"fas fa-info\"></i> An error occured...Please try again later<br>
					</strong></div>";
			}
		}else if(isset($_POST["submitNewOrder"])){

			$order_date = date('Y-m-d', strtotime($_POST['order_date_new']));
			$quantity = $_POST["quantity"];
			$totalAmount = $_POST["totalAmount"];
			$po_number = $_POST["po_number_new"];
			$today = date("Y-m-d");
			$pname = $_POST["product_name_new"];
			$supplier_name = $_POST["sup_name"];
			$supplier_phone = $_POST["sup_phone"];
			$store_id = $_SESSION["store_id"];

			//generate order number
			$sql2 = mysqli_query($connect, "SELECT * FROM placed_orders ORDER BY id DESC LIMIT 1") or die(mysqli_error($connect));
			$data = mysqli_fetch_array($sql2);
			$getLastOrderNo = $data["order_number"];
			$order_number = $getLastOrderNo + 1;

			//insert new data to placed orders table
			$sql_insert2 = mysqli_query($con, "INSERT INTO 
			placed_orders(store_id,cost_price,quantity,order_number,product_name,supplier_name,supplier_phone,order_date,order_receive_date,order_status,totalAmount,po_number) 
			VALUES('$store_id','','$quantity','$order_number','$pname','$supplier_name','$supplier_phone','$order_date','','Not Received','$totalAmount','$po_number')") or die(mysqli_error($con));


				if ($sql_insert2 ) {
					echo "<div class=\"alert alert-success\">
						<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
						<strong><i class=\"fas fa-check-circle\"></i>
						Congratulations! Order have been placed. Once they arrive, Receive the Orders into the System. <br>
						</strong></div>";
				} else {
					echo "<div class=\"alert alert-danger\">
						<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
						<strong><i class=\"fas fa-info\"></i> An error occured...Please try again later<br>
						</strong></div>";
				}
		}
		?>

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="page-heading mb-5 mt-4"> <i class="fas fa-edit"></i> List of Received Orders</div>
			</div> <!-- /panel-heading -->
			<div class="panel-body">
				<div class="remove-messages"></div>

				<script type="text/javascript">
					$(document).ready(function() {
						$('#received_orders').DataTable({
							dom: 'lBfrtip',
							"aaSorting": [],
							"lengthMenu": [
								[10, 25, 50, -1],
								[10, 25, 50, "All"]
							]
						});

						// order date picker
						$("#order_date").datepicker();
						$("#order_date_new").datepicker();

					});

					function getId(id){
						$("#po_number").val(id);
					}

					function getNewId(id,pname){
						$("#po_number_new").val(id);
						$("#product_name_new").val(pname);
					}
				</script>
				<table class="display" id="received_orders">
					<thead>
						<tr>
							<th>Order Number</th>
							<th>Product Name</th>
							<th>Quantity Received</th>
							<th>Supplier Name</th>
							<th>Supplier Company</th>
							<th>Amount Paid</th>
							<th>Placed On</th>
							<th>Received On</th>
							<th></th>

						</tr>
					</thead>
					<tbody>
						<?php
						$sql = mysqli_query($con, "SELECT * FROM placed_orders WHERE order_status='Received' ORDER BY id DESC") or die(mysqli_error($con));

						while ($fetch = mysqli_fetch_array($sql)) {
							echo "<tr>";
							echo "<td>" . $fetch["po_number"] . "</td>";
							echo "<td>" . $fetch["product_name"] . "</td>";
							echo "<td>" . $fetch["quantity"] . "</td>";
							echo "<td>" . $fetch["supplier_name"] . "</td>";
							echo "<td>" . $fetch["supplier_com_name"] . "</td>";
							echo "<td>" . number_format($fetch["totalAmount"],2) . "</td>";
							echo "<td>" . $fetch["order_date"] . "</td>";
							echo "<td>" . $fetch["order_receive_date"] . "</td>";

							if ($_SESSION["canreceive_direct_orders"] == 1) {
						?>
								<td>

								<!-- <button class='btn customize-abs-btn' data-toggle='modal' id="<?php //echo $fetch['po_number']; ?>" value="<?php //echo $fetch['product_name']; ?>" onclick="getNewId(this.id,this.value)" data-target='#newOrderModal'>
									<i class='fas fa-plus'></i></button> -->

									<button class='btn customize-abs-btn' data-toggle='modal' id="<?php echo $fetch['po_number']; ?>" onclick="getId(this.id)" data-target='#receiveDirectModal'>
									<i class='fas fa-download'></i></button>
									
								</td>
								</tr>
						<?php
							} else {
								echo "<td></td></tr>";
							}
						}
						?>
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