<?php
session_start();
include 'db_connect.php';

//get current user
$currentRole = $_SESSION["role"]; //"owner"; 
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

	$statement = $con->prepare("SELECT product_discount,product_name,product_price,pvld_restrict_sales,quantity_rem,max_to_sell FROM product WHERE product_id=? LIMIT 1");
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
		$product["measurement_unit"] = $measurement_unit;
		$product["has_variations"] = 0;
		$product["unit_changed_from_default"] = 0;
		$product["default_qty"] = 1;
		$product["qty_change"] = 1;

		//check if qty is greater than allowed to sell quantity

		if ($storeQtyRemain == 0) {
			$error = "low_store_stock";
			die(json_encode(array('products' => $error)));
		} elseif ($_GET["product_qty"] > $storeQtyRemain) {
			$error = "low_store_qty";
			die(json_encode(array('products' => $error)));
		} else {

			//BEGIN ASSOCIATE RESTRICTIONS
			if ($currentRole == "associate") {

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
							$_SESSION["products"][$product['product_id']]["product_qty"] =  $_GET["product_qty"];
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
						$_SESSION["products"][$product['product_id']]["product_qty"] =  $_GET["product_qty"]; //$_SESSION["products"][$product['product_id']]["product_qty"];
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


# Remove products from cart
if (isset($_GET["remove_code"]) && isset($_SESSION["products"])) {

	$product_id  = filter_var($_GET["remove_code"], FILTER_SANITIZE_STRING);


	if (isset($_SESSION["products"][$product_id])) {
		unset($_SESSION["products"][$product_id]);
	}
	$total_product = count($_SESSION["products"]);

	$cartData = cartData($connect);
	$productMeasurement = cartDataMeasurement($connect);
	die(json_encode(array('products' => $total_product, 'data' => $cartData, 'measurements' => $productMeasurement)));
}


# Update cart product quantity
if (isset($_GET["update_quantity"]) && isset($_SESSION["products"])) {

	if (isset($_GET["quantity"]) && $_GET["quantity"] > 0) {

		$product_id = $_GET["update_quantity"];

		//check if store can sell the required quantity
		$store_sql = mysqli_query($con, "SELECT product_id,store_id,quantity_rem FROM stock_transfer WHERE product_id='$product_id' AND store_id='$store_id' ORDER BY id DESC LIMIT 1");

		if (mysqli_num_rows($store_sql) > 0) {
			$data = mysqli_fetch_array($store_sql);
			$storeQtyRemain = $data["quantity_rem"];
		} else {

			$p_sql = mysqli_query($con, "SELECT quantity_rem FROM product WHERE product_id='$product_id'");
			$data2 = mysqli_fetch_array($p_sql);
			$storeQtyRemain = $data2["quantity_rem"];
		}

		$statement = mysqli_query($con, "SELECT product_name,product_discount, product_price,pvld_restrict_sales,quantity_rem,max_to_sell 
		FROM product WHERE product_id='$product_id' OR barcode_id='$product_id' LIMIT 1");

		while ($product = mysqli_fetch_array($statement)) {

			$product_name = $product["product_name"];
			$product_price = $product["product_price"];
			$product_status = $product["pvld_restrict_sales"];
			$quantity_rem = $product["quantity_rem"];
			$max_to_sell = $product["max_to_sell"];
			$product_discount = $product["product_discount"];

			if ($storeQtyRemain == 0) {
				$error = "low_store_stock";
				die(json_encode(array('products' => $error)));
			} elseif ($_POST["product_qty"] > $storeQtyRemain) {
				$error = "low_store_qty";
				die(json_encode(array('products' => $error)));
			} else {

				//check if measurement unit has been changed from original unit has been 

				$getMUnitFromSesssion = $_SESSION["products"][$_GET["update_quantity"]]["measurement_unit"];

				$sql10 = mysqli_query($connect, "SELECT * FROM product_measurement WHERE product_id='$pnumber'");
				$row10 = mysqli_fetch_array($sql10);

				//BEGIN ASSOCIATE RESTRICTIONS
				if ($currentRole == "associate") {

					if ($_GET["quantity"] > $max_to_sell) { //cannot sell more than max
						$error = "reachedmax";
						die(json_encode(array('products' => $error, 'message' => $max_to_sell)));
					} elseif ($_GET["quantity"] > $quantity_rem) { //if qty to sell is > qty rem
						$error = "highqty";
						die(json_encode(array('products' => $error)));
					} else {

						if (!empty($getMUnitFromSesssion)) {
							$_SESSION["products"][$_GET["update_quantity"]]["product_qty"] = $_SESSION["products"][$_GET["update_quantity"]]["default_qty"] * $_GET["quantity"];
						} else {
							$_SESSION["products"][$_GET["update_quantity"]]["product_qty"] = $_GET["quantity"];
						}

						//if quantity has been changed, update the qty that shows in the qty change box of the 
						//make sales environment

						$_SESSION["products"][$_GET["update_quantity"]]["qty_change"] = $_GET["quantity"];

						$total_product = count($_SESSION["products"]);

						$cartData = cartData($connect);
						$productMeasurement = cartDataMeasurement($connect);

						die(json_encode(array('products' => $total_product, 'data' => $cartData, 'measurements' => $productMeasurement)));
					}
				} else {

					if (!empty($getMUnitFromSesssion)) {
						$_SESSION["products"][$_GET["update_quantity"]]["product_qty"] = $_SESSION["products"][$_GET["update_quantity"]]["default_qty"] * $_GET["quantity"];
					} else {
						$_SESSION["products"][$_GET["update_quantity"]]["product_qty"] = $_GET["quantity"];
					}

					//if quantity has been changed, update the qty that shows in the qty change box of the 
					//make sales environment

					$_SESSION["products"][$_GET["update_quantity"]]["qty_change"] = $_GET["quantity"];
					$total_product = count($_SESSION["products"]);

					$cartData = cartData($connect);
					$productMeasurement = cartDataMeasurement($connect);
					die(json_encode(array('products' => $quantity_rem, 'data' => $cartData, 'measurements' => $productMeasurement)));
				}
			}
		}
	}
}


# Update cart product price
if (isset($_GET["update_price"]) && isset($_SESSION["products"])) {
	if (isset($_GET["price"]) && $_GET["price"] > 0) {

		$product_id = $_GET["update_price"];

		$statement = mysqli_query($con, "SELECT product_name, product_price,pvld_restrict_sales,quantity_rem,max_to_sell 
		FROM product WHERE product_id='$product_id' LIMIT 1");

		while ($product = mysqli_fetch_array($statement)) {
			$product_name = $product["product_name"];
			$product_price = $product["product_price"];

			$_SESSION["products"][$_GET["update_price"]]["product_price"] = $_GET["price"];
			$total_product = count($_SESSION["products"]);
			$msg = "done";
			die(json_encode(array('message' => $msg)));
		}
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
		$hasVariations = $product["has_variations"];
		$mUnitChanged = $_SESSION["products"][$pnumber]["unit_changed_from_default"];
		$quantity_change = $_SESSION["products"][$pnumber]["qty_change"];
        $plevelDiscount = 0;

		// check if product has variations
		// query the DB to get the measurement for each product
		$sql1 = mysqli_query($connect, "SELECT * FROM product_measurement WHERE product_id='$pnumber'");

		if (mysqli_num_rows($sql1)) {
			$hasVariations = 1;
		} else {
			$hasVariations = $product["has_variations"];
		}

        // //give discounts based on quantity
		// //retrieve current price levels with their amount for each product
		$stmt = mysqli_query($connect, "SELECT * FROM product WHERE product_id='$pnumber' OR barcode_id='$pnumber' LIMIT 1");
		$stmtRow = mysqli_fetch_array($stmt);
		$price_level_qty_above = $stmtRow["price_level_qty_above"];
		$price_level_amount = $stmtRow["price_level_amount"];

		if($pqty >= $price_level_qty_above){
			//add discount to price session variable
			$plevelDiscount = $price_level_amount;
			//update the discount super global in SESSION
			
		}else{
			$plevelDiscount = 0;
		}
		
		$_SESSION["products"][$pnumber]["product_discount"] = $plevelDiscount;

		// $price_level_qty_below = $stmtRow["price_level_qty_below"];

		
		$data = [
			'pname' => $pname,
			'pprice' => $pprice,
			'pnumber' => $pnumber,
			'pqty' => $pqty,
			'pdiscount' => $product_discount,
            'hasVariations' => $hasVariations,
			'mUnitChanged'=>$mUnitChanged,
			'qtyChanged'=>$quantity_change,
            'plevelDiscount'=>$plevelDiscount
		];

		array_push($cartData, $data);
	}

	

	return $cartData;
}

function cartDataMeasurement($connect)
{

	$productMeasurement = [];

	// query the DB to get the measurement for each product
	$sql1 = mysqli_query($connect, "SELECT * FROM product_measurement") or die(mysqli_error($connect));

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

//post cart session data back when page is reloaded
if (isset($_GET["page_ready"])) {

	$cartData = cartData($connect);
	$productMeasurement = cartDataMeasurement($connect);

	die(json_encode(array('data' => $cartData, 'measurements' => $productMeasurement)));
}


if (isset($_GET["measurementID"]) && isset($_GET["productID"])) {

	$measurementID = $_GET["measurementID"];
	$productID = $_GET["productID"];
	$measurementUnit = $_GET["measurementUnit"];

	//get the product variation information
	$sql1 = mysqli_query($connect, "SELECT measurement_id,product_id,measurement_unit,measurement_qty,measurement_price 
	FROM product_measurement WHERE measurement_id='$measurementID' AND product_id='$productID'");

	//if it exist,
	//extract the information and store it in the session
	$data = mysqli_fetch_array($sql1);
	$measurement_qty = $data["measurement_qty"];
	$measurement_price = $data["measurement_price"];
	$measurement_unit = $data["measurement_unit"];

	$sql2 = mysqli_query($connect, "SELECT * FROM product WHERE product_id='$productID' AND measurement_unit='$measurement_unit'");
	$mUnitChanged = 0;

	//monitor change in measurement_unit
	if (mysqli_num_rows($sql2) > 0) {
		$mUnitChanged= 0;
	} else {
		$mUnitChanged = 1;
	}


	//update the cart with the new qty and price

	if (isset($_SESSION["products"])) {
		if (isset($_SESSION["products"][$_GET["productID"]])) {

			$_SESSION["products"][$_GET["productID"]]["product_price"] = intval($measurement_price);
			$_SESSION["products"][$_GET["productID"]]["product_qty"] = intval($measurement_qty);
			$_SESSION["products"][$_GET["productID"]]["measurement_unit"] = $measurement_unit;
			$hasVariations = $_SESSION["products"][$_GET["productID"]]["has_variations"];
			$_SESSION["products"][$productID]["unit_changed_from_default"] = $mUnitChanged;
			$_SESSION["products"][$_GET["productID"]]["default_qty"] = intval($measurement_qty);


			$cartData = cartData($connect);
			$productMeasurement = cartDataMeasurement($connect);

			die(json_encode(array('data' => $cartData, 'measurements' => $productMeasurement)));
		}
	}
}

?>