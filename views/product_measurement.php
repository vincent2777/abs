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

                <button class='btn btn-dark mb-5' data-toggle='modal' data-target='#addMeasurementModal'>Add New Measurement Unit</button>

                <div class="panel panel-default">

                    <div class="panel-body">

                        <div class="remove-messages"></div>

                        <?php

                        if (isset($_POST["submitUnitForm"])) {

                            $store_id = $_SESSION["store_id"];

                            for ($i = 0; $i < sizeof($_POST['product_id']); $i++) {

                                $product_id = $_POST['product_id'][$i];
                                $quantity = $_POST['quantity'][$i];
                                $price = $_POST['price'][$i];
                                $measurement = ucwords($_POST['measurement'][$i]);

                                $sql6 = "SELECT * FROM product WHERE product_name='$product_id'";
                                $query6 = mysqli_query($con, $sql6) or die(mysqli_error($con));
                                $fetch6 = mysqli_fetch_array($query6) or die(mysqli_error($con));

                                $product_id = $fetch6["product_id"];

                                //generate measurementID
                                $measurementID = strtoupper($product_id) . mt_rand(10, 99) . $measurement;

                                $sql = "INSERT INTO product_measurement (product_id,measurement_qty,measurement_price, measurement_unit, measurement_id) 
						        VALUES('$product_id','$quantity','$price','$measurement','$measurementID')";

                                $query = mysqli_query($con, $sql) or die(mysqli_error($con));
                            }


                            if ($query) {

                                echo '<div class="alert alert-success" style="border-left: 5px solid green;">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<strong>
								<i class="fas fa-ok-sign"></i> Data Added Successfully.</strong>
							</div>';
                            } else {
                                echo "";
                            }
                        } elseif (isset($_POST["addUnitBtn"])) {

                            $unit_name = $_POST["unit_name"];
                            $unit_id = mt_rand(100, 999) . date('ms');

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


                        $do_action = $_GET["do"];
                        $mid = $_GET["mid"];

                        if ($do_action == "delete" && $mid != "") {
                            $deleteSQL = mysqli_query($con, "DELETE FROM measurement_units WHERE unit_id='$mid'") or die(mysqli_error($con));

                            if ($deleteSQL) {
                                echo '<div class="alert alert-success" style="border-left: 5px solid green;">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong><i class="fas fa-ok-sign"></i> Measurement has been deleted Successfully</strong>
                                    </div>';
                            }
                        }else if($do_action == "pmeasure_delete" && $mid != ""){

                            $deleteSQL = mysqli_query($con, "DELETE FROM product_measurement WHERE measurement_id='$mid'") or die(mysqli_error($con));

                            if ($deleteSQL) {
                                echo '<div class="alert alert-success" style="border-left: 5px solid green;">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong><i class="fas fa-ok-sign"></i> Product Measurement has been deleted Successfully</strong>
                                    </div>';
                            }

                        }



                        ?>


                        <form class="form-horizontal" method="POST" id="formID">

                            <table id="emptbl">
                                <tr>
                                    <th>Product Name</th>
                                    <th>Measurement Unit</th>
                                    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Quantity</th>
                                    <th>Price</th>
                                    <th></th>

                                </tr>
                                <tr>
                                    <td id="col0">
                                    <input list="product_id" name="product_id[]" class="form-control" required>

                                    <datalist id="product_id">
                                <?php
                                    $sql = mysqli_query($con, "SELECT * FROM product ORDER BY product_name ASC");

								 while ($fetchData = mysqli_fetch_array($sql)) {
									?>
                                    <option value="<?php echo ucwords($fetchData["product_name"]); ?>" id="<?php echo ucwords($fetchData["product_id"]); ?>">

                                    <?php
                                }
                                    ?>
                            </datalist>

                                    

                                    </td>

                                    <td id="col1">

                                        <select id="measurement" class="form-control" name="measurement[]">
                                            <?php
                                            $sql2 = mysqli_query($con, "SELECT * FROM measurement_units");

                                            while ($fetchData2 = mysqli_fetch_array($sql2)) {
                                            ?>
                                                <option value="<?php echo $fetchData2["unit_name"]; ?>">
                                                    <?php echo ucwords($fetchData2["unit_name"]); ?>
                                                </option>

                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>

                                    <td id="col2">
                                        <input type="text" class="form-control ml-2 mr-5" id="quantity" placeholder="Quantity" name="quantity[]">

                                    </td>

                                    <td id="col3">
                                        <input type="text" class="form-control mr-5" id="Price" name="price[]" placeholder="Price" />
                                    </td>

                                    <td id="col4">
                                        <button type="button" class="btn btn-danger" name="deletebtn[]" onclick="deleteRows()"> <i class="fas fa-trash"></i> </button>
                                    </td>

                                </tr>
                            </table>

                            <div id="add-product-messages"></div>

                            <button type="button" class="btn btn-info mt-5" onclick="addRows()"> <i class="fas fa-plus"></i> Add New Row</button>

                            <button type="submit" class="btn btn-success mt-5" name="submitUnitForm" id="submitUnitForm"> <i class="fas fa-check"></i> Submit</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>


        <script type="text/javascript">
            $(document).ready(function() {
                $('#pmeasurements').DataTable({
                    dom: 'lBfrtip',
                    "lengthMenu": [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ]
                });
            });
        </script>

        <div class="row bg-white shadow-sm card p-3 rounded-0 mt-5">
            <div class="col-lg-12 mt-5">
                <h3 class="mb-5">Product Measurements/Variations</h3>

                <div class="panel panel-default">

                    <div class="panel-body">
                        <table class="table table-striped  mt-3" id="pmeasurements">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Measurement</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = mysqli_query($con, "SELECT * FROM product_measurement ORDER BY id DESC") or die(mysqli_error($con));

                                $count = 1;
                                while ($fetch = mysqli_fetch_array($sql)) {

                                    $measurement_qty = $fetch["measurement_qty"];
                                    $measurement_price = $fetch["measurement_price"];
                                    $measurement_unit = $fetch["measurement_unit"];
                                    $product_id = $fetch["product_id"];
                                    $measurementID = $fetch["measurement_id"];


                                    //get product name using its ID
                                    $sql2 = mysqli_query($con, "SELECT * FROM product WHERE product_id='$product_id'") or die(mysqli_error($con));
                                    $fetch2 = mysqli_fetch_array($sql2);
                                    $pname = $fetch2["product_name"];

                                    echo "<tr>";
                                    echo "<td>" . $count++ . "</td>";
                                    echo "<td>" . ucwords($pname) . "</td>";
                                    echo "<td>" . $measurement_qty . "</td>";
                                    echo "<td>" . number_format($measurement_price) . "</td>";
                                    echo "<td>" . ucwords($measurement_unit) . "</td>";

                                ?>
                                    <td>
                                        <a class="btn customize-abs-btn p-2" href="edit_pmeasurement?do=edit&mid=<?php echo $measurementID; ?>">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a class="btn btn-danger p-2" id="product_measurement?do=pmeasure_delete&mid=<?php echo $measurementID; ?>" onclick="confirmProductMeasurementDel(this.id)">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                    </tr>

                                <?php
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



        <div class="row bg-white shadow-sm card p-3 rounded-0 mt-5">
            <div class="col-lg-12 mt-5">
                <h3 class="mb-5">Measurements</h3>

                <div class="panel panel-default">

                    <div class="panel-body">
                        <table class="table table-striped  mt-3" id="pmeasurements">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = mysqli_query($con, "SELECT * FROM measurement_units ORDER BY id DESC") or die(mysqli_error($con));

                                $count = 1;
                                while ($fetch = mysqli_fetch_array($sql)) {

                                    $unit_name = $fetch["unit_name"];
                                    $unit_id = $fetch["unit_id"];

                                    echo "<tr>";
                                    echo "<td>" . $count++ . "</td>";
                                    echo "<td>" . ucwords($unit_name) . "</td>";
                                ?>
                                    <td>
                                        <a class="btn btn-danger p-2" id="product_measurement?do=delete&mid=<?php echo $unit_id; ?>" onclick="confirmMeasurementDel(this.id)">
                                            <i class="fas fa-trash"></i>
                                        </a>

                                    </td>
                                    </tr>

                                <?php
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
</div>
</div>
<script type="text/javascript">
    function confirmMeasurementDel(loc) {
        var x = window.confirm("You are about Deleting a Record. Do you wish to proceed?");
        if (x == true) {
            window.open(loc, '_self');
        }
    }

    function confirmProductMeasurementDel(loc) {
        var x = window.confirm("You are about Deleting a Record. Do you wish to proceed?");
        if (x == true) {
            window.open(loc, '_self');
        }
    }

    function addRows() {
        var table = document.getElementById('emptbl');
        var rowCount = table.rows.length;
        var cellCount = table.rows[0].cells.length;
        var row = table.insertRow(rowCount);
        for (var i = 0; i <= cellCount; i++) {
            var cell = 'cell' + i;
            cell = row.insertCell(i);
            var copycel = document.getElementById('col' + i).innerHTML;
            cell.innerHTML = copycel;
        }

    }


    function deleteRows() {
        var table = document.getElementById('emptbl');
        var rowCount = table.rows.length;
        if (rowCount > '2') {
            var row = table.deleteRow(rowCount - 1);
            rowCount--;
        } else {
            alert('There should be atleast one row');
        }
    }
</script>


<div class="modal fade" id="addMeasurementModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="post">
                <h4 class="modal-title p-3"><i class="fa fa-plus"></i> Add New</h4>

                <div class="modal-body" style="max-height:450px; overflow:auto;">

                    <div class="form-group">
                        <label for="quantity" class="col-sm-12 control-label">Measurement Name: </label>

                        <div class="col-sm-12">
                            <input type="text" required="" autocomplete="off" class="form-control" id="unit_name" placeholder="Unit Name" name="unit_name">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fas fa-remove-sign"></i> Close</button>

                    <button type="submit" class="btn customize-abs-btn" name="addUnitBtn" id="addUnitBtn" data-loading-text="Loading..."> <i class="fas fa-ok-sign"></i> Submit</button>
                </div> <!-- /modal-footer -->
            </form> <!-- /.form -->
        </div> <!-- /modal-content -->
    </div> <!-- /modal-dailog -->
</div>


<?php include "../partials/footer.php"; ?>