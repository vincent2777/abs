<?php
include "../partials/header.php";
include "../partials/sidebar.php";;
?>

<style>
	.product-delete-alert {
		position: absolute;
		top: 40;
		width: 30%;
		max-width: 30%;
		z-index: 2;
		left: 35%;
		display: none;
	}
</style>

<div class="main-panel">
	<div class="content-wrapper">
		<ol class="breadcrumb">
			<li><a href="../dashboard.php">Home/ </a></li>
			<li class="active">All Products</li>
		</ol>



		<div class="row bg-white shadow-sm card p-3 rounded-0">

			<?php

			if (isset($_POST["addNewBtn"])) {

				$store_id = $_SESSION["store_id"];

				//generate order number
				$sql2 = mysqli_query($connect, "SELECT * FROM placed_orders ORDER BY id DESC LIMIT 1") or die(mysqli_error($connect));
				$data = mysqli_fetch_array($sql2);
				$getLastOrderNo = $data["order_number"];
				$order_number = $getLastOrderNo + 1;

				$pname = $_POST['modal_pname'];
				$pdesc = $_POST['modal_pdesc'];
				$pvendor = $_POST['modal_vendor'];
				$cost_price = $_POST['modal_cprice'];
				$selling_price = $_POST['modal_sprice'];
				$quantity = $_POST["modal_qty"];

				$order_date = date('Y-m-d');

				//generate product number
				$shorten_product_name = substr(str_replace(" ", "", $pname), 0, 2);
				$po_number = strtoupper($shorten_product_name) . date('d') . mt_rand(100, 999) . mt_rand(1, 9);
				$product_id = strtoupper($shorten_product_name) . date('mds') . mt_rand(10, 50);

				$sql = "INSERT INTO placed_orders (cost_price,selling_price,measurement_unit,product_desc,po_number,store_id,order_number, product_name, 
			quantity, supplier_name, supplier_com_name, supplier_phone, 
			totalAmount,currentAmountPaid,paymentType,paymentStatus,order_status,order_date) 
					VALUES('$cost_price','$selling_price','','$pdesc', '$po_number','$store_id','$order_number','$pname', '$quantity', '$pvendor', '', '', '','', '', '','Not Received','$order_date')";
				$query = mysqli_query($con, $sql) or die(mysqli_error($con));


				$insertProd = "INSERT INTO 
				product (po_number,store_id,cost_price,product_price,product_name,order_number,product_id,quantity,quantity_rem,
				pvld_restrict_sales,received_status) 
				VALUES('$po_number','$store_id','$cost_price','$selling_price','$pname','$order_number','$product_id', 
				'$quantity','$quantity',0,0)";
				$query = mysqli_query($con, $insertProd) or die(mysqli_error($con));


				if ($query) {
					echo '<div class="alert alert-success" style="border-left: 5px solid green;">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>
					<i class="fas fa-ok-sign"></i> Order saved succesfully.</strong>
				</div>';
				} else {
					echo '<div class="alert alert-danger" style="border-left: 5px solid green;">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Oops! Sorry, an error occured</strong>
				</div>';
				}
			}

			?>

			<!-- <div class="d-flex justify-content-end">
				<button class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">Add Product</button>
			</div> -->

			<div class="col-md-12">

				<div class="product-delete-alert animate__animated animate__bounceIn" style="position: fixed;display:none">
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="page-heading"> <i class="fas fa-edit"></i> All products</div>
					</div> <!-- /panel-heading -->
					<div class="panel-body mt-5" id="product-holder">
						<div style="overflow-x:auto">
							<table class="display table table-flush" id="product_table">

								<thead>
									<tr>
										<th></th>
										<th>Code</th>
										<th>Name</th>
										<th>Unit</th>
										<th>SP</th>
										<th>CP</th>
										<th>Total Cost</th>
										<th>Expected Amt.</th>
										<th>Margin</th>
										<th>Discount</th>
										<th>Qty Ordered</th>
										<th>Qty Sold</th>
										<th>Qty Rem.</th>
										<th>Total Sold Amt.</th>
										<th>Status</th>
										<th>Desc.</th>
										<th style="width: 12%">Options</th>
									</tr>
								</thead>
								<tbody>
									<?php

									$sql = mysqli_query($con, "SELECT * FROM product ORDER BY product_name ASC") or die(mysqli_error($con));
									$count = 1;
									while ($fetch = mysqli_fetch_array($sql)) {

										$calQuantitySold = $fetch["quantity"] - $fetch["quantity_rem"];
										$calQuantityRem = $fetch["quantity"] - $calQuantitySold;
										$calAmountSold = $fetch["product_price"] * $calQuantitySold;
										$calAmountExpected = $fetch["product_price"] * $fetch["quantity"];
										$orderNumber = $fetch["order_number"];
										$pstatus = $fetch["pvld_restrict_sales"];

										//get product purchase price using the order number
										$sql_order = mysqli_query($con, "SELECT quantity,totalAmount,product_desc FROM placed_orders WHERE order_number='$orderNumber'") or die(mysqli_error($con));
										$row = mysqli_fetch_array($sql_order);
										$purchase_price = $fetch["cost_price"] * $fetch["quantity"];
										$profitMargin = $calAmountExpected - $fetch["cost_price"] * $fetch["quantity"];

										echo "<tr>";
										echo "<td>" . $count++ . "</td>";
										echo "<td>" . $fetch["product_id"] . "</td>";
										echo "<td>" . ucwords($fetch["product_name"]) . "</td>";
										echo "<td>" . ucwords($fetch["measurement_unit"]) . "</td>";
										echo "<td>" . $fetch["product_price"] . "</td>";
										echo "<td>" . $fetch["cost_price"] . "</td>";
										echo "<td>" . number_format($purchase_price, 2) . "</td>";
										echo "<td>" . number_format($calAmountExpected, 2) . "</td>";
										echo "<td>" . number_format($profitMargin, 2) . "</td>";
										echo "<td>" . $fetch["product_discount"] . "</td>";
										echo "<td>" . $fetch["quantity"] . "</td>";
										echo "<td>" . $calQuantitySold . "</td>";
										echo "<td>" . $fetch["quantity_rem"] . "</td>";
										echo "<td>" . number_format($calAmountSold, 2) . "</td>";

										if ($pstatus == 1) {
											echo "<td>In Sales</td>";
										} else {
											echo "<td>On Hold</td>";
										}
										echo "<td>" . ucwords($row["product_desc"]) . "</td>";

									?>
										<td>
											<button class="btn btn-success editProductBtn p-2" onclick="getIDForEdit(this.id)" data-toggle="modal" data-target="#editProductModal" id="<?php echo $fetch["product_id"]; ?>">
												<i class="fas fa-edit"></i></button>

											<button class="btn btn-danger p-2" onclick="deleteProduct('<?php echo $fetch['product_id']; ?>')">
												<i class="fas fa-trash"></i>
											</button>

											<button class="btn btn-info p-2" onclick="printBarcode('<?php echo $fetch['barcode_id']; ?>')">
												<i class="fas fa-barcode"></i>
											</button>
										</td>
										</tr>
									<?php
									}
									?>
								</tbody>
							</table>
							<!-- /table -->
						</div>

					</div> <!-- /panel-body -->
				</div> <!-- /panel -->
			</div> <!-- /col-md-12 -->
		</div>

	</div>
</div>

</div>
</div>

<div class="all-products">
	<div class="modal fade" id="editProductModal">
		<div class="modal-dialog">
			<div class="modal-content productEditDone">
				<form class="form-horizontal" id="updateProductDataForm" onsubmit="return submitEditProduct();" action="products" method="POST">
					<h4 class="modal-title p-3"><i class="fa fa-edit"></i> Edit Product Information</h4>



					<div class="modal-body" style="margin-left:30px;margin-right:30px ">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group" style="margin-right: 3px;">
									<label for="">Product Name</label>
									<input type="text" class="form-control" id="modal_pname" name="modal_pname">
								</div>


								<div class="form-group" style="margin-right: 3px;">
									<label for="">Barcode ID</label>
									<input type="text" class="form-control" id="barcode" name="barcode">
								</div>

								<?php

								if (!$_SESSION["editprice"] == 1) {

								?>
									<div class="form-group" style="margin-right: 3px;">
										<label for="">Selling Price (<span>Sorry..You dont have the priviledge to edit this</span>)</label>
										<input type="text" class="form-control" disabled id="modal_sprice" name="modal_sprice">
									</div>

								<?php } else { ?>

									<div class="form-group" style="margin-right: 3px;">
										<label for="">Selling Price</label>
										<input type="text" class="form-control" id="modal_sprice" name="modal_sprice">
									</div>

									<div class="form-group" style="margin-right: 3px;">
										<label for="">Cost Price</label>
										<input type="text" class="form-control" id="modal_cprice" name="modal_cprice">
									</div>

								<?php } ?>

							</div>

							<div class="col-md-6">
								<?php
								if (!$_SESSION["canedit_qty"] == 1) {

								?>
									<div class="form-group">
										<label for="">Quantity Rem (<span>Sorry..You dont have the priviledge to edit this</span>)</label>
										<input type="text" class="form-control" id="modal_qty" disabled name="modal_qty">
									</div>
								<?php } else { ?>


									<div class="form-group" style="margin-right: 3px;">
										<label for="">Disable Sale/Hold Product</label>
										<select name="disable_sale" id="disable_sale" class="form-control">
											<option value="0" selected>-Select Option</option>
											<option value="0">Yes</option>
											<option value="1">No</option>
										</select>
									</div>

									<div class="form-group">
										<label for="">Quantity Rem</label>
										<input type="text" class="form-control" id="modal_qty" name="modal_qty">
									</div>

									<div class="form-group">
										<label for="">Maximum Quantity to Sell</label>
										<input type="text" class="form-control" id="modal_qtyto_sell" name="modal_qtyto_sell">
									</div>
								<?php } ?>

								<?php
								if (!$_SESSION["cangive_discount"] == 1) {

								?>
									<div class="form-group">
										<label for="">Discount (Amount)</label>
										<input type="text" class="form-control" id="modal_discount" disabled autocomplete="off" name="modal_discount">
										<br>
										(<span>Sorry..You dont have the priviledge to edit this</span>)
									</div>

								<?php } else { ?>
									<div class="form-group">
										<label for="">Discount (Amount)</label>
										<input type="text" class="form-control" id="modal_discount" autocomplete="off" name="modal_discount">
									</div>
								<?php } ?>

							</div>
						</div>


						<input type="hidden" name="old_qty" id="old_qty">
						<input type="hidden" name="modal_pnumber" id="modal_pnumber" />


						<div class="row">
							<div class="col-md-6">
								<div class="form-group" style="margin-right: 3px;">
									<label for="">Re-order Level</label>
									<input type="text" class="form-control" id="modal_rlevel" name="modal_rlevel">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Expiry Date (Format: yyyy-mm-dd)</label>
									<input type="text" class="form-control" id="pexpiry_date" name="pexpiry_date">
								</div>
							</div>
						</div>

						<!-- <div class="form-group">
							<div class="form-group">
								<label for=""><b>Measurement Unit</b></label>
								<input type="hidden" value="Wholesale" name="price_level_category">
							</div>

							<div class="row">

								<div class="col-md-7">
									<div class="form-group">
										<select id="selectedUnit" class="form-control mr-5" onchange="getProductUnitData(this.value)" name="selectedUnit">
											<option>-Select a Unit -</option>
											<?php

											$sql2 = mysqli_query($con, "SELECT * FROM measurement_units");

											while ($fetchData2 = mysqli_fetch_array($sql2)) {
											?>
												<option selected value="<?php echo $fetchData2["unit_id"]; ?>">
													<?php echo ucwords($fetchData2["unit_name"]); ?>
												</option>

											<?php
											}
											?>
										</select>
									</div>

									<input type="hidden" id="punit_id" name="punit_id">
								</div>


								<div class="col-md-5">
									<div class="form-group">
										<div class="d-flex justify-content-end">
											<button type="button" class="btn btn-info" data-toggle='modal' data-target='#addProductUnitModal'>Add New</button>
										</div>
									</div>
								</div>
							</div>

							<div class="row toggleUnitDataInput" style="display: none;">
								<div class="col">
									<div class="form-group">
										<label for="">Price</label>
										<input type="text" required="" autocomplete="off" value="0" class="form-control" id="punit_price" placeholder="Unit Price" name="punit_price">
									</div>
								</div>

								<div class="col">
									<div class="form-group">
										<label for="">Quantity</label>
											<input type="text" required="" value="1" autocomplete="off" class="form-control" id="punit_qty" placeholder="Unit Qty" name="punit_qty">
									</div>
								</div>
							</div>

						</div> -->

						<div class="form-group">
							<div class="form-group">
								<label for="">Wholesale Price Level</label>
								<input type="hidden" value="Wholesale" name="price_level_category">
							</div>

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="">Qty. Above</label>
										<input type="number" class="form-control" style="width: 90%;" id="price_level_qty_above" name="price_level_qty_above">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="">Qty. Below</label>
										<input type="number" class="form-control" style="width: 90%;" id="price_level_qty_below" name="price_level_qty_below">
									</div>
								</div>

								<div class="col-md-4">

									<div class="form-group">
										<label for="">Amount to Subtract</label>
										<input type="text" class="form-control" style="width: 90%;" id="price_level_amount" name="price_level_amount">
									</div>
								</div>
							</div>

						</div>

					</div>

					<div class="modal-footer">
						<span id="waitmsg"></span>
						<button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fas fa-remove-sign"></i> Close</button>
						<button type="submit" onclick="submitEditProduct(event)" class="btn btn-success"> <i class="fas fa-ok-sign"></i> Update</button>
					</div> <!-- /modal-footer -->
				</form> <!-- /.form -->
			</div> <!-- /modal-content -->
		</div> <!-- /modal-dailog -->
	</div>

	<script src="<?php echo $pageUrl; ?>script/product.js"></script>
</div>

<div class="modal fade" id="addProductModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal" method="post" action="products?action=addnew">
				<div class="modal-header">
					<h4 class="modal-title"><i class="fa fa-plus"></i> Purchase Order</h4>
				</div>

				<div class="modal-body" style="margin-left:30px;margin-right:30px ">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group" style="margin-right: 3px;">
								<label for="">Product Name</label>
								<input type="text" class="form-control" id="modal_pname" name="modal_pname">
							</div>

							<div class="form-group" style="margin-right: 3px;">
								<label for="">Selling Price</label>
								<input type="text" class="form-control" value="0.00" id="modal_sprice" name="modal_sprice">
							</div>

							<div class="form-group" style="margin-right: 3px;">
								<label for="">Cost Price</label>
								<input type="text" class="form-control" value="0.00" id="modal_cprice" name="modal_cprice">
							</div>

						</div>

						<div class="col-md-6">


							<div class="form-group">
								<label for="">Quantity</label>
								<input type="text" class="form-control" value="0" id="modal_qty" name="modal_qty">
							</div>


							<div class="form-group" style="margin-right: 3px;">
								<label for="">Vendor</label>
								<input type="text" class="form-control" id="modal_vendor" name="modal_vendor">
							</div>

							<div class="form-group" style="margin-right: 3px;">
								<label for="">Description</label>
								<input type="text" class="form-control" id="modal_pdesc" name="modal_pdesc">
							</div>

						</div>

					</div>
				</div>


				<input type="hidden" name="old_qty" id="old_qty">
				<input type="hidden" name="modal_pnumber" id="modal_pnumber" />


				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fas fa-remove-sign"></i> Close</button>

					<button type="submit" class="btn customize-abs-btn" name="addNewBtn" id="addNewBtn"> <i class="fas fa-ok-sign"></i> Done</button>
				</div> <!-- /modal-footer -->
			</form> <!-- /.form -->
		</div> <!-- /modal-content -->
	</div> <!-- /modal-dailog -->
</div>




<div class="modal fade" id="addProductUnitModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<h4 class="modal-title p-3"><i class="fa fa-plus"></i> Add Measurement Unit</h4>
			<div class="modal-body" style="max-height:450px; overflow:auto;">

				<div class="form-group">
					<div class="col-sm-12">
						<input type="text" required="" autocomplete="off" class="form-control" id="unit_name" placeholder="Unit Name" name="unit_name">
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">
						<input type="text" required="" autocomplete="off" class="form-control" id="unit_qty" placeholder="Unit Qty" name="unit_qty">
					</div>
				</div>

				<div class="form-group">

					<div class="col-sm-12">
						<input type="text" required="" autocomplete="off" class="form-control" id="unit_price" placeholder="Unit Price" name="unit_price">
					</div>

					<input type="hidden" value='no' id="makeDefault">

					<br>
					<br>
					<button type="button" onclick="if(document.getElementById('makeDefault').value='no'){
							document.getElementById('setDefaultBtn').style.backgroundColor='green';
							document.getElementById('makeDefault').value='yes';
							}else{
							" class="btn btn-success" name="setDefaultBtn" id="setDefaultBtn">
						<i class="fas fa-ok-sign"></i> Set as Default</button>

					<br><br>

					<p id="addUnitMsg"></p>

				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" id="closeUnitModal" data-dismiss="modal"> <i class="fas fa-remove-sign"></i> Close</button>
				<button type="button" onclick="addProductunit()" class="btn customize-abs-btn" name="addUnitBtn" id="addUnitBtn"> <i class="fas fa-ok-sign"></i> Add</button>
			</div> <!-- /modal-footer -->
		</div> <!-- /modal-content -->
	</div> <!-- /modal-dailog -->
</div>

<?php include '../partials/footer.php'; ?>