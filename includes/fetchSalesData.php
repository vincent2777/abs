<?php 	

require_once 'db_connect.php';

if(isset($_POST["invoiceID"])){
    $inv_id = $_POST["invoiceID"];
    $sql = "SELECT * FROM sold_products WHERE invoice_number='$inv_id'";
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

    $connect->close();
    
}

