<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'] . "/abs/includes/";
$path1 = $path . "db_connect.php";

include($path1);

//get current user
$current_user = $_SESSION["role"];
$store_id = $_SESSION["store_id"];

$error = "";
# add products in cart 
if (isset($_GET["product_id"])) {
    foreach ($_GET as $key => $value) {
        $exproduct[$key] = filter_var($value, FILTER_SANITIZE_STRING);
    }

    $pid = $_GET["product_id"];
    $invno = $_SESSION["exchangeInvoice"];

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
    $statement->bind_param('s', $exproduct['product_id']);
    $statement->execute();
    $num_of_rows = $statement->num_rows;
    $statement->bind_result($product_discount, $product_name, $product_price, $product_status, $quantity_rem, $max_to_sell);

    while ($statement->fetch()) {
        $exproduct["product_name"] = $product_name;
        $exproduct["product_price"] = $product_price;
        $exproduct["pvld_restrict_sales"] = $product_status;
        $exproduct["quantity_rem"] = $quantity_rem;
        $exproduct["max_to_sell"] = $max_to_sell;
        $exproduct["product_discount"] = $product_discount;
        $exproduct["invoice_number"] = $invno;

        
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

                    if (isset($_SESSION["exproducts"])) {
                        if (isset($_SESSION["exproducts"][$exproduct['product_id']])) {
                            $_SESSION["exproducts"][$exproduct['product_id']]["product_qty"] =  $_SESSION["exproducts"][$exproduct['product_id']]["product_qty"];
                        } else {
                            $_SESSION["exproducts"][$exproduct['product_id']] = $exproduct;
                        }
                    } else {
                        $_SESSION["exproducts"][$exproduct['product_id']] = $exproduct;
                    }
                }
            } else { //not an associate

                //add to cart
                if (isset($_SESSION["exproducts"])) {
                    if (isset($_SESSION["exproducts"][$exproduct['product_id']])) {
                        $_SESSION["exproducts"][$exproduct['product_id']]["product_qty"] =  $_SESSION["exproducts"][$exproduct['product_id']]["product_qty"];
                    } else {
                        $_SESSION["exproducts"][$exproduct['product_id']] = $exproduct;
                    }
                } else {
                    $_SESSION["products"][$exproduct['product_id']] = $exproduct;
                }
            }
        }

        //prepare product for the cart
        $total_product = count($_SESSION["exproducts"]);

        $cartData = cartData($connect);
        $productMeasurement = cartDataMeasurement($connect);

        die(json_encode(array('products' => $total_product, 'data' => $cartData, 'measurements' => $productMeasurement)));
    }
}

// # Remove products from cart
if (isset($_GET["remove_code"]) && isset($_SESSION["exproducts"])) {

    $product_id  = filter_var($_GET["remove_code"], FILTER_SANITIZE_STRING);

    unset($_SESSION["exproducts"][$product_id]);


    $total_product = count($_SESSION["exproducts"]);
    $cartData = cartData($connect);
    $productMeasurement = cartDataMeasurement($connect);

    die(json_encode(array('products' => $total_product, 'data' => $cartData, 'measurements' => $productMeasurement)));
}



# Update cart product quantity
if (isset($_GET["update_product_id"]) && isset($_SESSION["exproducts"])) {

    if (isset($_GET["quantity"]) && $_GET["quantity"] > 0) {
        $_SESSION["exproducts"][$_GET["update_product_id"]]["product_qty"] = $_GET["quantity"];
    }

    $total_product = count($_SESSION["exproducts"]);
    $cartData = cartData($connect);
    $productMeasurement = cartDataMeasurement($connect);
    $success = "done";
    die(json_encode(array('msg' => $success, 'products' => $total_product, 'data' => $cartData, 'measurements' => $productMeasurement)));
}


function cartData($connect)
{

    //send response in JSOn with conteents of the cart

    $cartData = [];

    foreach ($_SESSION["exproducts"] as $exproduct) {

        $pname = $exproduct["product_name"];
        $pprice = $exproduct["product_price"];
        $pnumber = $exproduct["product_id"];
        $pqty = $exproduct["product_qty"];
        $product_discount = $exproduct["product_discount"];
        $hasVariations = 0;
        $inv_no = $exproduct["invoice_number"];

        //ge toriginal price the items where bought for
        $sql2 = mysqli_query($connect, "SELECT * FROM sold_products WHERE invoice_number='$inv_no' GROUP BY invoice_number");
        $data2 = mysqli_fetch_array($sql2);
        $amount_paid = $data2["total_amount"];
        $customer_name = $data2["customer_name"];
        $customer_phone = $data2["customer_phone"];
        $customer_address = $data2["customer_address"];

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
            'amountPaid' => $amount_paid,
            'cust_name'=>$customer_name,
            'cust_phone'=>$customer_phone,
            'cust_address'=>$customer_address

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
    $total_product = count($_SESSION["exproducts"]);

    die(json_encode(array('products' => $total_product, 'data' => $cartData, 'measurements' => $productMeasurement)));
}


if (isset($_GET["measurementID"]) && isset($_GET["productID"])) {

    $measurementID = $_GET["measurementID"];
    $productID = $_GET["productID"];

    //get the product variation information
    $sql1 = mysqli_query($connect, "SELECT measurement_id,product_id,measurement_qty,measurement_price FROM product_measurement WHERE measurement_id='$measurementID' AND product_id='$productID'");

    //if it exist,
    //extract the information and store it in the session
    $data = mysqli_fetch_array($sql1);

    $measurement_qty = $data["measurement_qty"];
    $measurement_price = $data["measurement_price"];

    // //update the cart with the new qty and price

    if (isset($_SESSION["products"])) {
        if (isset($_SESSION["products"][$_GET["productID"]])) {

            $_SESSION["products"][$_GET["productID"]]["product_price"] = intval($measurement_price);
            $_SESSION["products"][$_GET["productID"]]["product_qty"] = intval($measurement_qty);

            $cartData = cartData($connect);
            $productMeasurement = cartDataMeasurement($connect);

            die(json_encode(array('data' => $cartData, 'measurements' => $productMeasurement)));
        }
    }
}
