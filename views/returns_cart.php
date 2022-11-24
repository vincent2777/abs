<?php
include "../partials/header.php";
include "../partials/sidebar.php"; ;
?>

<style type="text/css">
	#cart-info:hover {
		text-decoration: none;
	}

	.cart-alert-update {
		position: absolute;
		top: 40;
		width: 30%;
		max-width: 30%;
		z-index: 2;
		left: 35%;
		display: none;
	}
</style>
<div class="container" id="view_cart">
	<div class="alert alert-success cart-alert-update animate__animated animate__bounceIn">
		<p><i class="fas fa-check-circle"></i> Product Quantity Updated..Please wait</p>
	</div>
	<div class="text-left">
		<div class="col-md-12">
			<?php
			if (isset($_SESSION["products"]) && count($_SESSION["products"]) > 0) {
			?>
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading"> <i class="fas fa-calendar"></i> View Cart</div>
						<div class="panel-body">

							<div class="alert alert-info" style="border-left: 5px solid blue;">
								<h2>To change quantity, </h2>
								<p>1. Use the up and down arrow to change the quantity of any item OR
									<br>
									2. Type the new quantity into the box and then click outside
									<br>
									3. Once there is a page refresh, you can continue with sales.
								</p>
							</div>

							<table class="table table-responsive table-striped" id="shopping-cart-results" style="width: 100%;margin-top:-5%">
								<thead>
									<tr>
										<th>Product</th>
										<th>Unit Price</th>
										<th>Quantity</th>
										<th>Subtotal</th>
										<th>&nbsp;</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$cart_box = '<ul class="cart-products-loaded">';
									$total = 0;
									foreach ($_SESSION["products"] as $product) {
										$product_name = $product["product_name"];
										$product_price = $product["product_price"];
										$product_number = $product["product_id"];
										$product_qty = $product["product_qty"];
										$subtotal = ($product_price * $product_qty);
										$total = ($total + $subtotal);
									?>
										<tr>
											<td><?php echo $product_name; ?></td>
											<td><?php echo number_format($product_price,2); ?></td>
											<td><input type="number" onkeypress="return isNumber(event)" data-code="<?php echo $product_number; ?>" class="form-control text-center quantity" value="<?php echo $product_qty; ?>"></td>
											<td><?php echo $currency;
												echo number_format(sprintf("%01.2f", ($subtotal))); ?></td>
											<td>
												<a href="#" class="btn customize-abs-btn remove-item" data-code="<?php echo $product_number; ?>"><i class="fas fa-trash"></i></a>
											</td>
										</tr>
										<script>
											function isNumber(evt) {
												var charCode = (evt.which) ? evt.which : event.keyCode;
												if ((charCode < 48 || charCode > 57)) {
													return false;
												} else {
													return true;
												}
											}
										</script>
									<?php } ?>
								<tfoot>
									<br>
									<br>
									<tr>
										<td><a href="make_sales" class="btn btn-warning"><i class="fas fa-menu-left"></i> Add More Products</a></td>
										<td colspan="2"></td>
										<?php
										if (isset($total)) {
										?>
											<td class="text-center cart-products-total"><strong>Total: <?php echo $currency . number_format(sprintf("%01.2f", $total)); ?></strong></td>
											<td><a href="process_returns" class="btn customize-abs-btn btn-block">Checkout <i class="fas fa-menu-right"></i></a></td>
										<?php } ?>
									</tr>
								</tfoot>
							<?php
						} else {
							echo "Your Cart is empty";
							?>
								<tfoot>
									<br>
									<br>
									<tr>
										<td><a href="make_sales" class="btn customize-abs-btn"><i class="fas fa-menu-left"></i> Add Products</a></td>
										<td colspan="2"></td>
									</tr>
								</tfoot>
							<?php } ?>
							</tbody>
							</table>
						</div>
					</div>
				</div>
		</div>
	</div>
</div>
<?php include('includes/footer.php'); ?>