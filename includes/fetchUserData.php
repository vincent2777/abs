<?php 	

require_once 'db_connect.php';

if(isset($_POST["userID"])){
    $user = $_POST["userID"];
    $sql = "SELECT * FROM users WHERE id='$user'";
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

