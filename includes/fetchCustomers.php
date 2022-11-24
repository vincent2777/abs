<?php

require_once 'db_connect.php';

if(!empty($_POST['query'])){
    $response = [];
    $keyword = $_POST['query'];

    $sql = "SELECT * FROM customers WHERE cust_name LIKE '%$keyword%' OR cust_id LIKE '%$keyword%' LIMIT 10";
    $result = $connect->query($sql);

    if ($result->num_rows > 0) {
       while($row = $result->fetch_array()){

        array_push($response,$row);
       }
    } // if num_rows
    else {
        $response = "No Customer Found."; 
    }
echo json_encode($response);
}

if(!empty($_POST["custID"])){
    $customer_id = $_POST['custID'];

    $sql = "SELECT * FROM customers WHERE cust_id='$customer_id'";
    $result = $connect->query($sql);

    if ($result->num_rows > 0) {
       while( $row = $result->fetch_array()){
            $response = $row;
       }
    } // if num_rows
    else {
        $response = "Data could not be retrieved"; 
    }

    echo json_encode($response);
}



