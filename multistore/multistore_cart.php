<?php
include "../partials/header.php";
include "../partials/sidebar.php";
?>
<script type="text/javascript" src="<?php echo $pageUrl; ?>script/ttb_cart.js"></script>

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
<div class="main-panel">
	<div class="content-wrapper">
		<div class="container" id="view_cart">
			<div class="alert alert-success cart-alert-update animate__animated animate__bounceIn">
				<p><i class="fas fa-check-circle"></i> Product Quantity Updated..Please wait</p>
			</div>
			<div class="row rounded-0 shadow-sm p-3 bg-white">
				<div class="col-md-12">
					<?php
					if (isset($_SESSION["ttb_products"]) && count($_SESSION["ttb_products"]) > 0) {
					?>
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading"> <i class="fas fa-calendar"></i> View Cart</div>
								<div class="panel-body">

									<div class="alert alert-info mt-3">
										<h4>To change quantity, </h4>
										<p>1. Use the up and down arrow to change the quantity of any item OR
											<br>
											2. Type the new quantity into the box and then click outside
											<br>
											3. Once there is a page refresh, you can continue with sales.
										</p>
									</div>

									<table class="table table-responsive table-striped" id="sc-cart-results">
										<thead>
											<tr>
												<th>Product</th>
												<th>Quantity</th>
												<th>&nbsp;</th>
											</tr>
										</thead>
										<tbody>
											<?php
											error_reporting(0);
											$cart_box = '<ul class="cart-products-loaded">';
											$total = 0;
											foreach ($_SESSION["ttb_products"] as $product) {
												$product_name = $product["product_name"];
												$product_price = $product["product_price"];
												$product_number = $product["product_id"];
												$product_qty = $product["product_qty"];
											?>
												<tr>
													<td><?php echo $product_name; ?></td>
													<td><input type="number" onkeypress="return isNumber(event)" data-code="<?php echo $product_number; ?>" class="form-control text-center sc-quantity" value="<?php echo $product_qty; ?>"></td>
													<td>
														<a href="#" class="btn btn-danger remove-item" data-code="<?php echo $product_number; ?>"><i class="fas fa-trash"></i></a>
													</td>
												</tr>

											<?php } ?>
										<tfoot>
											<br>
											<br>
											<tr>
												<td><a href="multistore" class="btn btn-warning"><i class="fas fa-menu-left"></i> Add More Products</a></td>
												<td colspan="1"></td>

												<td><a href="multistore_checkout" class="btn btn-danger btn-block">Checkout <i class="fas fa-menu-right"></i></a></td>

											</tr>
										</tfoot>
									<?php
								} else {
									echo "Your Transfer List is empty";
									?>
										<tfoot>
											<br>
											<br>
											<tr>
												<td><a href="multistore" class="btn btn-danger"><i class="fas fa-menu-left"></i> Add Products</a></td>
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
		</div>

	</div>
</div>
</div>
</div>
<?php include '../partials/footer.php'; ?>