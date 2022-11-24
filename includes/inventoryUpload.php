<?php
session_start();
include 'db_connect.php';

if (isset($_FILES)) {
    $filename = $_FILES["selectedFile"]["tmp_name"];

    if ($_FILES["selectedFile"]["size"] > 0) {

        $file = fopen($filename, "r");
        $c = 0;
        while (($emapData = fgetcsv($file, 1000, ",")) !== FALSE) {

            $product_name = $emapData[1];
            $product_no = substr($emapData[0], 0, 2) . date('s') . mt_rand(100, 999);
            $product_no = str_replace(" ","",$product_no);
            $product_name = str_replace("'", "", $product_name);
            $quantity = intval($emapData[4]);
            $sell_price = intval($emapData[3]);
            $cost_price = intval($emapData[2]);
            $supplier_name = "";
            $supplier_com_name = "";
            $supplier_phone = "";
            $discount = 0;
            $totalAmount = 0;
            $currentAmountPaid = 0;
            $paymentType = "";
            $paymentStatus    = "";
            $order_date = date('Y-m-d');
            $store_id = $_SESSION["store_id"];
            //generate product number
            $shorten_product_name = substr($product_name, 0, 2);
            $po_number = strtoupper($shorten_product_name) . date('d') .mt_rand(100, 999). mt_rand(1, 9);

            //It wiil insert a row to our subject table from our csv file`
            $sql = "INSERT into placed_orders (po_number,store_id,order_number, cost_price, product_name,quantity, 
            supplier_name, supplier_com_name, supplier_phone, 
            discount, totalAmount,currentAmountPaid,paymentType,paymentStatus,
            order_status,order_date,order_receive_date,measurement_unit,product_desc)
            VALUES('$po_number','$store_id','$product_no','$cost_price','$product_name','$quantity','','','',0,0,0,'',
            '','Received','$order_date','$order_date','','')";
            //we are using mysql_query function. it returns a resource on true else False on error
            $result = mysqli_query($con, $sql) or die(mysqli_error($con));

            $insertProd = "INSERT INTO 
product (measurement_unit,received_status,po_number,store_id,max_to_sell,product_name,order_number,product_id,
quantity,product_price, cost_price, product_discount ,quantity_rem,pvld_restrict_sales) 
VALUES('',1,'$po_number','$store_id','$quantity','$product_name','$product_no','$product_no', '$quantity','$sell_price','$cost_price',0,'$quantity','1')";
            $query = mysqli_query($con, $insertProd) or die(mysqli_error($con));
            $c = $c + 1;
        }

        if (!$result) {
            echo "invalid";
        } else {
            //throws a message if data successfully imported to mysql database from excel file
            echo "done";
        }
        fclose($file);
    }
}
