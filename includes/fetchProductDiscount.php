<?php

require_once 'db_connect.php';

if(!empty($_POST["prodID"])){
    $product_id = $_POST['prodID'];

    $sql = "SELECT * FROM product WHERE product_id='$product_id'";
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



