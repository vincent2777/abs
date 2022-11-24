<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'] . "/abs/includes/";
$path1 = $path . "db_connect.php";
$path2 = $path . "functions.php";
setlocale(LC_MONETARY, "en_US");


include($path1);
include($path2);

//get current user
$current_user = $_SESSION["role"];
$store_id = $_SESSION["store_id"];

$error = "";
# add products in cart 
if (isset($_GET["product_id"])) {
	foreach ($_GET as $key => $value) {
		$product[$key] = filter_var($value, FILTER_SANITIZE_STRING);
	}

	$pid = $_GET["product_id"];

	//check if store can sell the required quantity
	$store_sql = mysqli_query($con, "SELECT product_id,store_id,quantity_rem FROM stock_transfer WHERE product_id='$pid' AND store_id='$store_id' ORDER BY id DESC LIMIT 1");

	if (mysqli_num_rows($store_sql) > 0) {
		$data = mysqli_fetch_array($store_sql);
		$storeQtyRemain = $data["quantity_rem"];
	} else {

		$p_sql = mysqli_query($con, "SELECT quantity_rem FROM product WHERE product_id='$pid'");
		$data2 = mysqli_fetch_array($p_sql);
		$storeQtyRemain = $data2["quantity_rem"];
	}

	$statement = $con->prepare("SELECT product_discount,product_name, product_price,pvld_restrict_sales,quantity_rem,max_to_sell FROM product WHERE product_id=? LIMIT 1");
	$statement->bind_param('s', $product['product_id']);
	$statement->execute();
	$num_of_rows = $statement->num_rows;
	$statement->bind_result($product_discount, $product_name, $product_price, $product_status, $quantity_rem, $max_to_sell);

	while ($statement->fetch()) {
		$product["product_name"] = $product_name;
		$product["product_price"] = $product_price;
		$product["pvld_restrict_sales"] = $product_status;
		$product["quantity_rem"] = $quantity_rem;
		$product["max_to_sell"] = $max_to_sell;
		$product["product_discount"] = $product_discount;

		//check if qty is greater than allowed to sell quantity

		if ($storeQtyRemain == 0) {
			$error = "low_store_stock";
			die(json_encode(array('products' => $error)));
		} elseif ($_POST["product_qty"] > $storeQtyRemain) {
			$error = "low_store_qty";
			die(json_encode(array('products' => $error)));
		} else {

			//BEGIN ASSOCIATE RESTRICTIONS
			if ($current_user == "associate") {

				if ($quantity_rem <= 0) {
					$error = "lowqty";
					die(json_encode(array('products' => $error)));
				} elseif ($_GET["product_qty"] > $max_to_sell) { //cannot sell more than max
					$error = "reachedmax";
					die(json_encode(array('products' => $error, 'message' => $max_to_sell)));
				} elseif ($product_status != 1) { //cannot sell when a product is on hold
					$error = "onhold";
					die(json_encode(array('products' => $error)));
				} elseif ($_GET["product_qty"] > $quantity_rem) { //if qty to sell is > qty rem
					$error = "highqty";
					die(json_encode(array('products' => $error)));
				} else {

					if (isset($_SESSION["products"])) {
						if (isset($_SESSION["products"][$product['product_id']])) {
							$_SESSION["products"][$product['product_id']]["product_qty"] =  $_SESSION["products"][$product['product_id']]["product_qty"];
						} else {
							$_SESSION["products"][$product['product_id']] = $product;
						}
					} else {
						$_SESSION["products"][$product['product_id']] = $product;
					}
				}
			} else { //not an associate

				//add to cart
				if (isset($_SESSION["products"])) {
					if (isset($_SESSION["products"][$product['product_id']])) {
						$_SESSION["products"][$product['product_id']]["product_qty"] =   $_GET["product_qty"] + $_SESSION["products"][$product['product_id']]["product_qty"];
					} else {
						$_SESSION["products"][$product['product_id']] = $product;
					}
				} else {
					$_SESSION["products"][$product['product_id']] = $product;
				}
			}
		}

		//prepare product for the cart
		$total_product = count($_SESSION["products"]);

		$cartData = cartData($connect);
		$productMeasurement = cartDataMeasurement($connect);

		die(json_encode(array('products' => $total_product, 'data' => $cartData, 'measurements' => $productMeasurement)));
	}
}


function cartData($connect)
{

	//send response in JSOn with conteents of the cart

	$cartData = [];

	foreach ($_SESSION["products"] as $product) {

		$pname = $product["product_name"];
		$pprice = $product["product_price"];
		$pnumber = $product["product_id"];
		$pqty = $product["product_qty"];
		$product_discount = $product["product_discount"];
		$hasVariations = 0;

		//check if product has variations
		// query the DB to get the measurement for each product
		$sql1 = mysqli_query($connect, "SELECT * FROM product_measurement WHERE product_id='$pnumber'");

		if (mysqli_num_rows($sql1)) {
			$hasVariations = 1;
		}

		$data = [
			'pname' => $pname,
			'pprice' => $pprice,
			'pnumber' => $pnumber,
			'pqty' => $pqty,
			'pdiscount' => $product_discount,
			'hasVariations' => $hasVariations
		];

		array_push($cartData, $data);
	}

	return $cartData;
}

function cartDataMeasurement($connect)
{

	$productMeasurement = [];

	// query the DB to get the measurement for each product
	$sql1 = mysqli_query($connect, "SELECT * FROM product_measurement");

	if (mysqli_num_rows($sql1)) {

		while ($measurement = mysqli_fetch_array($sql1)) {

			$measurementData = [
				"measurement_id" => $measurement["measurement_id"],
				"measurement_unit" => $measurement["measurement_unit"],
				"product_id" => $measurement["product_id"],
				"measurement_qty" => $measurement["measurement_qty"],
				"measurement_price" => $measurement["measurement_price"]
			];

			array_push($productMeasurement, $measurementData);
		}
	}

	return $productMeasurement;
}



