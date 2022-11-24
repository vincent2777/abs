<?php
error_reporting(0);
include "../partials/header.php";
include "../partials/sidebar.php";
session_start();
?>
<div class="main-panel">
	<div class="content-wrapper">
		<?php
		$inv_no = $_GET["invoice"];
		$fetch_prod = "SELECT * FROM held_receipts WHERE invoice_number='$inv_no'";
		$result_prod = mysqli_query($connect, $fetch_prod);
		while ($row = mysqli_fetch_assoc($result_prod)) {

			$product_name = $row["product_name"];
			$total_amount = $row["product_price"];
			$product_id = $row["product_id"];

			# add products in cart 
			if (!empty($product_id)) {
				foreach ($row as $key => $value) {
					$product[$key] = filter_var($value, FILTER_SANITIZE_STRING);
				}

				$statement = $connect->prepare("SELECT product_name, product_price,quantity,product_discount FROM held_receipts WHERE product_id=?");
				$statement->bind_param('s', $product_id);
				$statement->execute();
				$statement->bind_result($product_name, $product_price, $product_qty,$product_discount);

				while ($statement->fetch()) {
					$product["product_name"] = $product_name;
					$product["product_price"] = $product_price;
					$product["product_qty"] = $product_qty;
					$product["product_discount"] = $product_discount;

					$_SESSION["invoice_from_hold"] = $inv_no;

					if (isset($_SESSION["products"])) {
						if (isset($_SESSION["products"][$product_id])) {
							$_SESSION["products"][$product_id]["product_qty"] = $_SESSION["products"][$product_id]["product_qty"];
						} else {
							$_SESSION["products"][$product_id] = $product;
						}
					} else {
						$_SESSION["products"][$product_id] = $product;
					}
				}
			}
		}

		echo "<center><div class='alert alert-info justify-content-center mx-auto' style='font-size:25px;width:50%;'>
	<i class='fas fa-ok'> </i> Unholding Receipt..Please Wait 
	<img src='" . $pageUrl . "images/loading.gif' />
	<meta http-equiv=\"refresh\" content=\"2; url=make_sales\"></div></center>";
		?>

		<?php include "../partials/footer.php"; ?>