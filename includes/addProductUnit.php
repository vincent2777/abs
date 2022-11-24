<?php
session_start();
include "db_connect.php";

if (isset($_POST["unit_name"])) {

    $unit_name = $_POST["unit_name"];
    $unit_price = $_POST["unit_price"];
    $unit_qty = $_POST["unit_qty"];
    $unit_id = mt_rand(100, 999) . date('ms');

    //generate measurementID
    $measurementID = strtoupper($product_id) . mt_rand(10, 99) . ucwords($_POST["unit_name"]);
    $pid = $_SESSION["prodUnitId"];
    //check if exist
    $sql_check = "SELECT * FROM measurement_units WHERE unit_name='$unit_name'";
    $query_check = mysqli_query($con, $sql_check) or die(mysqli_error($con));

    if (mysqli_num_rows($query_check) > 0) {

        //measurement exist
        echo '<div class="alert alert-danger" style="border-left: 5px solid maroon;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>
        <i class="fas fa-ok-sign"></i> Measurement Unit Already exist.</strong>
        </div>';

    } else {

        $sql = "INSERT INTO measurement_units (unit_name,unit_id) 
        VALUES('$unit_name','$unit_id')";

        $query = mysqli_query($con, $sql) or die(mysqli_error($con));

        $sql2 = "INSERT INTO product_measurement (product_id,measurement_qty,measurement_price, measurement_unit, measurement_id) 
        VALUES('$pid','$unit_qty','$unit_price','$unit_name','$measurementID')";
        $query2 = mysqli_query($con, $sql2) or die(mysqli_error($con));

        if ($query) {

            echo '<div class="alert alert-success" style="border-left: 5px solid green;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>
            <i class="fas fa-ok-sign"></i> Measurement Unit Added Successfully.</strong>
        </div>';
        } else {
            echo "";
        }
    }
}