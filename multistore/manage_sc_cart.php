<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'] . "/abs/includes/";
$path1 = $path . "db_connect.php";
$path2 = $path . "functions.php";

include($path1);
include($path2);
setlocale(LC_MONETARY, "en_US");
error_reporting(0);

//get current user
$current_user = $_SESSION["role"];
$error = "";
# add products in cart 
if (isset($_POST["product_id"])) {
	foreach ($_POST as $key => $value) {
		$product[$key] = filter_var($value, FILTER_SANITIZE_STRING);
	}

	if (isset($_SESSION["store_transfer_from"])) {

		$store = $_SESSION["store_transfer_from"];
		$statement = $con->prepare("SELECT product_name,quantity,quantity_rem,store_id FROM stock_transfer WHERE product_id=? AND store_id=? ORDER BY id DESC LIMIT 1");
		$statement->bind_param('ss', $product['product_id'],$store);
		$statement->execute();
		$num_of_rows = $statement->num_rows;
		$statement->bind_result($product_name, $quantity, $quantity_rem,$store_id);

	} else {

		$statement = $con->prepare("SELECT product_name,pvld_restrict_sales,quantity,quantity_rem FROM product WHERE product_id=? LIMIT 1");
		$statement->bind_param('s', $product['product_id']);
		$statement->execute();
		$num_of_rows = $statement->num_rows;
		$statement->bind_result($product_name, $product_status, $quantity, $quantity_rem);
	}


	while ($statement->fetch()) {
		
		$product["product_name"] = $product_name;
		$product["quantity"] = $quantity;
		$product["quantity_rem"] = $quantity_rem;

		//check if available for sale

		//check if qty is low
		if ($quantity == 0) {
			$error = "lowqty";
			die(json_encode(array('products' => $error)));
		} elseif ($_POST["product_qty"] > $quantity_rem) { //if qty to sell is > qty rem
			$error = "highqty";
			die(json_encode(array('products' => $error)));
		} else {
			if (isset($_SESSION["ttb_products"])) {
				if (isset($_SESSION["ttb_products"][$product['product_id']])) {
					$_SESSION["ttb_products"][$product['product_id']]["product_qty"] =  $_POST["product_qty"];
				} else {
					$_SESSION["ttb_products"][$product['product_id']] = $product;
				}
			} else {
				$_SESSION["ttb_products"][$product['product_id']] = $product;
			}

			$total_product = count($_SESSION["ttb_products"]);
			die(json_encode(array('products' => $total_product)));
		}
	}
}


# Remove products from cart
if (isset($_GET["remove_code"]) && isset($_SESSION["ttb_products"])) {
	$product_id  = filter_var($_GET["remove_code"], FILTER_SANITIZE_STRING);
	if (isset($_SESSION["ttb_products"][$product_id])) {
		unset($_SESSION["ttb_products"][$product_id]);
	}
	$total_product = count($_SESSION["ttb_products"]);
	die(json_encode(array('ttb_products' => $total_product)));
}

# Update cart product quantity
if (isset($_GET["update_quantity"]) && isset($_SESSION["ttb_products"])) {
	if (isset($_GET["quantity"]) && $_GET["quantity"] > 0) {
		$_SESSION["ttb_products"][$_GET["update_quantity"]]["product_qty"] = $_GET["quantity"];
	}
	$total_product = count($_SESSION["ttb_products"]);
	die(json_encode(array('products' => $total_product)));
}
