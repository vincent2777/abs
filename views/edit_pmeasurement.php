<?php
include "../partials/header.php";
include "../partials/sidebar.php";;
?>

<style>
    table,
    tr,
    td {
        padding-right: 20px;
        padding-bottom: 20px;
    }
</style>

<div class="main-panel">
    <div class="content-wrapper">

        <ol class="breadcrumb">
            <li><a href="../dashboard.php">Home/ </a></li>
            <li class="active">Product Units</li>
        </ol>
        <div class="row bg-white shadow-sm card p-3 rounded-0">
            <div class="col-lg-12 mt-5">

                <h4>Edit Product Measurement</h4>

                <div class="panel panel-default">

                    <div class="panel-body">

                        <div class="remove-messages"></div>

                        <?php

                        if (isset($_POST["updateBtn"])) {

                            $store_id = $_SESSION["store_id"];
                            $product_id = $_POST['product_id'];
                            $quantity = $_POST['quantity'];
                            $price = $_POST['price'];
                            $measurement = ucwords($_POST['measurement']);
                            $measurementID =  $_POST['pmeasurement_id'];

                            $sql = "UPDATE product_measurement SET 
                            product_id='$product_id',
                            measurement_qty='$quantity',
                            measurement_price='$price', 
                            measurement_unit='$measurement' WHERE measurement_id='$measurementID'";
                            $query = mysqli_query($con, $sql) or die(mysqli_error($con));

                            if ($query) {

                                echo '<div class="alert alert-success" style="border-left: 5px solid green;">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<strong>
								<i class="fas fa-ok-sign"></i> Data Updated Successfully.</strong>
							</div>';
                            } else {
                                echo "";
                            }
                        }


                        if ($_GET["do"] == "edit" && $_GET["mid"] != "") {

                            $measurement_id = $_GET["mid"];

                            $sql2 = mysqli_query($con, "SELECT * FROM product_measurement WHERE measurement_id='$measurement_id'") or die(mysqli_error($con));
                            $fetch2 = mysqli_fetch_array($sql2);
                            $getProductId = $fetch2["product_id"];

                            $sql3 = mysqli_query($con, "SELECT * FROM product WHERE product_id='$getProductId'") or die(mysqli_error($con));
                            $fetch3 = mysqli_fetch_array($sql3);
                            $pname = $fetch3["product_name"];


                        ?>


                            <form class="form-horizontal" method="POST" id="formID">

                                <input type="hidden" value="<?php echo $_GET["mid"]; ?>" name="pmeasurement_id">

                                <table id="emptbl">
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Measurement Unit</th>
                                        <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Quantity</th>
                                        <th>Price</th>
                                    </tr>
                                    <tr>
                                        <td id="col0">

                                            <select id="product_id" name="product_id" class="form-control">

                                                <option value="<?php echo $getProductId; ?>">
                                                    <?php echo ucwords($pname); ?>
                                                </option>

                                                <?php
                                                $sql = mysqli_query($con, "SELECT * FROM product ORDER BY product_name ASC");
                                                while ($fetchData = mysqli_fetch_array($sql)) {
                                                ?>
                                                    <option value="<?php echo $fetchData["product_id"]; ?>">
                                                        <?php echo ucwords($fetchData["product_name"]); ?>
                                                    </option>

                                                <?php
                                                }
                                                ?>
                                            </select>

                                        </td>
                                        <td id="col1">

                                            <select id="measurement" class="form-control" name="measurement">

                                                <option value="<?php echo ucwords($fetch2["measurement_unit"]); ?>">
                                                    <?php echo ucwords($fetch2["measurement_unit"]); ?>
                                                </option>

                                                <?php
                                                $sql2 = mysqli_query($con, "SELECT * FROM product_measurement WHERE measurement_unit != '" . $fetch2["measurement_unit"] . "'");

                                                while ($fetchData2 = mysqli_fetch_array($sql2)) {
                                                ?>
                                                    <option value="<?php echo $fetchData2["measurement_unit"]; ?>">
                                                        <?php echo ucwords($fetchData2["measurement_unit"]); ?>
                                                    </option>

                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </td>

                                        <td id="col2">
                                            <input type="text" value="<?php echo $fetch2["measurement_qty"]; ?>" class="form-control ml-2 mr-5" id="quantity" placeholder="Quantity" name="quantity">

                                        </td>

                                        <td id="col3">
                                            <input type="text" class="form-control mr-5" value="<?php echo $fetch2["measurement_price"]; ?>" id="Price" name="price" placeholder="Price" />
                                        </td>

                                    </tr>
                                </table>

                                <button type="submit" class="btn btn-success mt-5" name="updateBtn" id="updateBtn"> <i class="fas fa-check"></i> Update</button>
                            </form>

                        <?php } else {
                            echo "Record Not Found";
                        }

                        ?>

                    </div>
                </div>
            </div>
        </div>

    </div>


</div>
</div>
</div>



<?php include "../partials/footer.php"; ?>