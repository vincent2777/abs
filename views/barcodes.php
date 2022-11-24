<?php
include "../partials/header.php";
include "../partials/sidebar.php";
require '../vendor/autoload.php';

?>


<style type="text/css">
    @media print {

        .panel-heading,
        .panel-heading * {
            display: none !important;
        }
    }

    @media print {

        .hidden-print,
        .hidden-print * {
            display: none !important;
        }
    }

    @media print {
        @page {
            padding: 0px 0px 0px 0px;
            size: auto;
            /* auto is the initial value */
            margin: 0;
            /* this affects the margin in the printer settings */
        }

        html {
            background-color: #FFFFFF;
            margin: 0px;
            /* this affects the margin on the html before sending to printer */
        }

        body {
            border: solid 1px blue;
            margin: 0mm 5mm 0mm 5mm;
            /* margin you want for the content */
        }
    }
</style>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row rounded-0 shadow-sm bg-white p-3">
            <div class="col-md-12">

                <ol class="breadcrumb hidden-print">
                    <li><a href="../dashboard.php">Home/ </a></li>
                    <li class="active"> Barcodes</li>
                </ol>

                <div class="panel panel-default">
                    <div class="panel-heading hidden-print">
                        <div class="page-heading mt-5 mb-4"> <i class="fas fa-edit"></i> Product Barcode Generator</div>
                    </div> <!-- /panel-heading -->
                    <div class="panel-body">
                        <?php

                        function generateCodes($count, $value,$barcodeId,$generator)
                        {
                            $width = 2;
                            $height = 100;

                            while ($count < $value) {

                               echo  "<div class=\"col-md-2\">
                                   <img src='data:image/png;base64,". base64_encode($generator->getBarcode($barcodeId, $generator::TYPE_CODE_128, $width, $height))."'
                                    <br>
                                    <br>
                                    <center>$barcodeId</center>

                                    <br>
                                    <br>

                                </div>";

                                $count++;
                            }
                        }

                        if (isset($_POST["generateCodeForSelected"])) {

                            $productIDs = [];
                            $productQtys = [];

                            foreach ($_POST["selectedForCode"] as $value) {
                                array_push($productIDs, $value);
                            }

                            foreach ($_POST["selectedForCodeQty"] as $value) {
                                if (!empty($value)) {
                                    array_push($productQtys, $value);
                                }
                            }

                            ?>

                            <div class="row" style="margin-top: 10px;">

                            <?php

                            $count = 0;
                            $generator = new Picqer\Barcode\BarcodeGeneratorPNG();

                                for ($i=0; $i < sizeof($productQtys); $i++) { 
                                
                                    $qtyToPrint = intval($productQtys[$i]);
                                    $pId = $productIDs[$i];
                                    generateCodes($count, $qtyToPrint,$pId,$generator);

                                }
                        ?>
                </div>
                            <button class="btn customize-abs-btn btn-lg hidden-print" onclick="window.print()">Print</button>

                        <?php

                        } elseif (!empty($_GET["code"])) {

                            $barcode = $_GET["code"];
                            $width = 2;
                            $height = 100;
                            $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                            $sql = mysqli_query($con, "SELECT * FROM product WHERE barcode_id='$barcode'") or die(mysqli_error($con));
                            $fetch = mysqli_fetch_array($sql);
                            $b_id = $fetch["barcode_id"];

                        ?>
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-2">
                                    <?php echo "<img src='data:image/png;base64," . base64_encode($generator->getBarcode($b_id, $generator::TYPE_CODE_128, $width, $height)) . "'"; ?>
                                    <br>
                                    <br>
                                    <?php echo $b_id; ?>

                                    <br>
                                    <br>

                                </div>
                            </div>

                            <button class="btn customize-abs-btn btn-lg hidden-print" onclick="window.print()">Print</button>

                            <?php

                        } elseif (isset($_POST["generate_code"])) {

                            $height = intval($_POST["barcode_type"]);
                            $btype = $_POST["barcode_height"];
                            $width = intval($_POST["barcode_width"]);
                            $show_name = $_POST["barcode_name"];
                            $show_number = $_POST["barcode_number"];

                            if (empty($width)) {
                                $width = 2;
                            }

                            if (empty($height)) {
                                $height = 100;
                            }

                            ?>

<div class="row" style="margin-top: <?php echo $mg; ?>px;">


<?php
                            $generator = new Picqer\Barcode\BarcodeGeneratorPNG();

                            $sql = mysqli_query($con, "SELECT * FROM product ORDER BY product_name ASC") or die(mysqli_error($con));
                            $mg = 10;
                            while ($fetch = mysqli_fetch_array($sql)) {

                                $p_id = $fetch["product_id"];
                                $product_name = $fetch["product_name"];

                                $product_id = $fetch["product_id"];
                                $product_id = str_replace(" ", "", $product_id);
                                $barcode_id = $product_id;

                                $sql2 = $con->query("UPDATE product SET barcode_id='$p_id' WHERE product_id='$p_id'");

                                //automate barcode margins
                                if ($mg == 25) {
                                    $mg = 18;
                                }

                                if ($mg == 28) {
                                    $mg = 18;
                                }
                                $mg += 5;

                            ?>
                                    <div class="col-md-2">
                                        <?php echo "<img src='data:image/png;base64," . base64_encode($generator->getBarcode($product_id, $generator::TYPE_CODE_128, $width, $height)) . "'"; ?>
                                        <br>
                                        <br>
                                        <?php if ($show_number == "Yes") {
                                            echo $product_id . "<br>";
                                        } ?>
                                        <?php if ($show_name == "Yes") {
                                            echo ucwords($product_name);
                                        } ?>
                                        <br>
                                        <br>

                                    </div>
                              

                            <?php } ?>

                            </div>
                            <br>
                            <br>

                            <button class="btn customize-abs-btn btn-lg hidden-print" onclick="window.print()">Print</button>

                        <?php  } else { ?>
                            <div class="row" style="margin-left: 20px;">
                                <div class="col-md-4">
                                    <h2>Print for all Products</h2>
                                    <form action="" method="post">
                                        <div class="alert alert-info" style="border-left: 5px solid blue;"> <i class="fas fa-info"></i> Customize the Barcode Design to Get Started</div>

                                        <div class="form-group">
                                            <label for="">Type Of Barcode</label>
                                            <select name="barcode_type" id="" class="form-control" required>
                                                <option value="TYPE_CODE_128">-Select Type-</option>
                                                <option selected value="TYPE_CODE_128">CODE 128 - Preferred</option>
                                                <option value="TYPE_CODE_128">CODE39</option>
                                                <option value="TYPE_CODE_128">QR CODE</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="">Show Product Name?</label>
                                            <select name="barcode_name" id="" class="form-control">
                                                <option>-Select Option-</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="">Show Product Barcode Number?</label>
                                            <select name="barcode_number" id="" class="form-control">
                                                <option>-Select Option-</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>

                                    

                                        <div class="form-group">
                                            <label for="">Height (default - 100)</label>
                                            <input type="text" name="barcode_height" class="form-control" value="100">
                                        </div>

                                        <div class="form-group">
                                            <label for="">Width (default - 1.5)</label>
                                            <input type="text" name="barcode_width" class="form-control" value="1.5">
                                        </div>

                                        <button class="btn btn-info btn-lg" name="generate_code">Generate Barcodes</button>
                                    </form>
                                </div>

                                <div class="col-md-8">

                                    <h3>Select a List of Products and Specify the Number of Codes you wish to print for each of them.</h3>

                                    <script>
                                        $(document).ready(function() {
                                            $('#barcode_table').DataTable({
                                                "lengthMenu": [
                                                    [10, 25, 50, -1],
                                                    [10, 25, 50, "All"]
                                                ]
                                            });
                                        })
                                    </script>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="page-heading"> <i class="fas fa-edit"></i> Select products</div>
                                        </div> <!-- /panel-heading -->
                                        <div class="panel-body" id="product-holder">
                                            <div style="overflow-x:auto">
                                                <form action="" method="post">
                                                    <table class="display table table-flush" id="barcode_table">

                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th style="width: 20%;"></th>
                                                                <th>Product Code</th>
                                                                <th>Name</th>
                                                                <th>Qty Rem.</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php

                                                            $sql = mysqli_query($con, "SELECT * FROM product ORDER BY product_name ASC") or die(mysqli_error($con));

                                                            while ($fetch = mysqli_fetch_array($sql)) {

                                                                echo "<tr>";
                                                                echo '<td>
                                                                <input type="checkbox" name="selectedForCode[]" onclick=\'toggleCheck(this.value)\' value="' . $fetch["product_id"] . '"></td>';
                                                                echo '<td>
                                                                <input type="number" style="display:none" name="selectedForCodeQty[]" class="form-control" id="' . $fetch["product_id"] . 'field">
                                                                </td>';

                                                                echo "<td>" . $fetch["product_id"] . "</td>";
                                                                echo "<td>" . $fetch["product_name"] . "</td>";
                                                                echo "<td>" . $fetch["quantity_rem"] . "</td>";

                                                            ?>

                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                    <br>

                                                    <br>
                                                    <button type="submit" class="btn btn-success btn-lg" name="generateCodeForSelected">Generate Barcode</button>
                                                </form>
                                                <!-- /table -->
                                            </div>

                                        </div> <!-- /panel-body -->
                                    </div> <!-- /panel -->
                                </div>

                            <?php } ?>

                            </div>
                    </div> <!-- /panel-body -->
                </div> <!-- /panel -->
            </div> <!-- /col-md-12 -->
        </div> <!-- /row -->

    </div>
</div>
</div>
</div>
<script>
    function toggleCheck(input) {

        input = input + 'field';

        if (document.getElementById(input).style.display === "none") {
            document.getElementById(input).style.display = "block";
        } else {
            document.getElementById(input).style.display = "none";
        }

    }
</script>
<?php include "../partials/footer.php"; ?>