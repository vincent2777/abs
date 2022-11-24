<?php
session_start();

require_once 'db_connect.php';

if (isset($_GET["customer_id"])) {

    $customer_id = $_GET["customer_id"];
    $paying = $_GET["paying"];
    $paymethod = $_GET["debt_paymethod"];
    $store_id = $_SESSION["store_id"];
    $current_user = $_SESSION["user"];
    $today = date("Y-m-d");
    $reference = mt_rand(100, 999) . date('s') . mt_rand(1, 9);
    $pay_time = date("h:i:s");

    //update customer owing with new amount
    $query1 = mysqli_query($con, "UPDATE customers SET cust_owing=cust_owing-$paying WHERE cust_id='$customer_id'") or die(mysqli_error($con));
    //insert to balance the payment the customer just made

    $sql2 = "INSERT INTO balance_sheet(pay_time,store_id,cashier_id,customer_id,pay_type,amount_paid,payment_date,payment_ref) 
VALUES('$pay_time','$store_id','$current_user','$customer_id','$paymethod', '$paying','$today','$reference')";
    $query2 = mysqli_query($con, $sql2) or die(mysqli_error($con));

    if ($query1 && $query2) {

        //send respomnse
        $sql = "SELECT * FROM customers WHERE cust_id='$customer_id' LIMIT 1";
        $result = $connect->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_array()) {
                $response = $row;
            }
        } // if num_rows
        else {
            $response = "An Error Occurred. Try again later";
        }
    }

    echo json_encode($response);
}
