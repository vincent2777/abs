<?php 	
session_start();
require_once 'db_connect.php';

if(isset($_POST["prodID"])){
    
    $pid = $_POST["prodID"];
    $sql = "SELECT * FROM product WHERE product_id='$pid'";
    $result = $connect->query($sql);

    $_SESSION["prodUnitId"] = $pid;
    
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
    
}else{
    
$sql = "SELECT product_id, product_name FROM product";
$result = $connect->query($sql);
$data = $result->fetch_all();

$connect->close();
echo json_encode($data);
}

