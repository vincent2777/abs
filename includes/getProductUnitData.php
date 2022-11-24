<?php

require_once 'db_connect.php';

if(!empty($_POST["unit_id"])){

    $unit_id = $_POST['unit_id'];

    $sql = "SELECT * FROM product_measurement WHERE measurement_id='$unit_id'";
    $result = $connect->query($sql);

    if ($result->num_rows > 0) {
       while( $row = $result->fetch_array()){
            $response = $row;
       }

       echo json_encode($response);

    } // if num_rows

}

?>