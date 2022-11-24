<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'] . "/abs/includes/";
$path1 = $path . "db_connect.php";

include($path1);

// # Remove products from cart
if (isset($_GET["remove_code"]) && isset($_SESSION["rproducts"])) {

	$product_id  = filter_var($_GET["remove_code"], FILTER_SANITIZE_STRING);

	unset($_SESSION["rproducts"][$product_id]);
	

	$total_product = count($_SESSION["rproducts"]);
	$cartData = cartData($connect);
	$productMeasurement = cartDataMeasurement($connect);

	die(json_encode(array('products' => $total_product, 'data' => $cartData, 'measurements' => $productMeasurement)));
}



# Update cart product quantity
if (isset($_GET["update_product_id"]) && isset($_SESSION["rproducts"])) {

	//check if quantity is an increment
	//if true disallow
	//returns can only be for decrements
	$current_qty = $_SESSION["rproducts"][$_GET["update_product_id"]]["product_qty"];

	if ($_GET["quantity"] > $current_qty) {

		//error
		//cant increment
		$error = "increment";
		$total_product = count($_SESSION["rproducts"]);
		$cartData = cartData($connect);
		$productMeasurement = cartDataMeasurement($connect);
		die(json_encode(array('msg' => $error,'old_qty'=>$current_qty,'products' => $total_product, 'data' => $cartData, 'measurements' => $productMeasurement)));

	} else {

		if (isset($_GET["quantity"]) && $_GET["quantity"] > 0) {
			$_SESSION["rproducts"][$_GET["update_product_id"]]["product_qty"] = $_GET["quantity"];
		}

		$total_product = count($_SESSION["rproducts"]);
		$cartData = cartData($connect);
		$productMeasurement = cartDataMeasurement($connect);
		$success = "done";
		die(json_encode(array('msg' => $success,'products' => $total_product, 'data' => $cartData, 'measurements' => $productMeasurement)));
	
	}
}


function cartData($connect)
{

	//send response in JSOn with conteents of the cart

	$cartData = [];

	foreach ($_SESSION["rproducts"] as $rproduct) {

		$pname = $rproduct["product_name"];
		$pprice = $rproduct["product_price"];
		$pnumber = $rproduct["product_id"];
		$pqty = $rproduct["product_qty"];
		$product_discount = $rproduct["product_discount"];
		$hasVariations = 0;
		$inv_no = $rproduct["invoice_number"];

		//ge toriginal price the items where bought for
		$sql2 = mysqli_query($connect, "SELECT * FROM sold_products WHERE invoice_number='$inv_no' GROUP BY invoice_number");
		$data2 = mysqli_fetch_array($sql2);
		$amount_paid = $data2["total_amount"];

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
			'invno' => $inv_no,
			'pdiscount' => $product_discount,
			'hasVariations' => $hasVariations,
			'amountPaid'=>$amount_paid
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


//post cart session data back when page is reloaded
if (isset($_GET["page_ready"])) {

	$cartData = cartData($connect);
	$productMeasurement = cartDataMeasurement($connect);
	$total_product = count($_SESSION["rproducts"]);

	die(json_encode(array('products' => $total_product,'data' => $cartData, 'measurements' => $productMeasurement)));
}
