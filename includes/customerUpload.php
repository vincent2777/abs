<?php
session_start();
include 'db_connect.php';

if (isset($_FILES)) {
    $filename = $_FILES["selectedFile"]["tmp_name"];

    if ($_FILES["selectedFile"]["size"] > 0) {

        $file = fopen($filename, "r");
        $c = 0;
        while (($emapData = fgetcsv($file, 1000, ",")) !== FALSE) {

            $customer_id = substr($emapData[0], 0, 2) . date('s') . mt_rand(10, 99);
            $customer_id = str_replace(" ","",$customer_id);
            $customer_lname = $emapData[1];
            $customer_lname = str_replace("'", "", $customer_lname);
            $customer_fname = $emapData[2];
            $customer_fname = str_replace("'", "", $customer_fname);

            $customer_name = $customer_fname." ".$customer_lname;

            $phone = $emapData[3];
            $climit = intval($emapData[4]);
            $cdebt = $emapData[5];
            $reg_date = date("Y-m-d");
            
            $store_id = $_SESSION["store_id"];

            //It wiil insert a row to our subject table from our csv file`
            $sql = "INSERT into customers (store_id,cust_id, cust_name, cust_phone, cust_address, cust_dob, cust_credit_limit, cust_owing, customer_type,reg_date)
            VALUES('$store_id','$customer_id','$customer_name','$phone','','','$climit','$cdebt','Regular','$reg_date')";
            $result = mysqli_query($con, $sql) or die(mysqli_error($con));

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
