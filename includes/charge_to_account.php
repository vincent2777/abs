<?php
session_start();
require_once 'db_connect.php';

if (isset($_GET["customer_id"])) {

    $customer = $_GET["customer_id"];
    $amount = $_GET["getTotalAmt"];
    $paymethod = "Cash";
    $store_id = $_SESSION["store_id"];
    $current_user = $_SESSION["user"];
    $today = date("Y-m-d");
    $reference = mt_rand(100, 999) . date('s') . mt_rand(1, 9);
    $pay_time = date("h:i:s");

    $sql = mysqli_query($con, "UPDATE customers SET cust_owing=cust_owing + '$amount' WHERE cust_id = '$customer'");

    if (strpos($amount, '-') !== false) {

        $amount = str_replace("-", "", $amount);

        $sql2 = "INSERT INTO balance_sheet(pay_time,store_id,cashier_id,customer_id,pay_type,amount_paid,payment_date,payment_ref) 
    VALUES('$pay_time','$store_id','$current_user','$customer','$paymethod', '$amount','$today','$reference')";
        $query2 = mysqli_query($con, $sql2) or die(mysqli_error($con));
    }

    $amount = str_replace("-", "", $amount);


    if ($sql) {
        $response = "Customer Account has been Charged with " . number_format(round($amount));
    } // if num_rows
    else {
        $response = "Data could not be retrieved";
    }
    echo json_encode($response);
}
