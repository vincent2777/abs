<?php
include "../partials/header.php";
include "../partials/sidebar.php";;
?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row bg-white">
            <div class="col-md-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="page-heading">
                            <h3 class="p-2">Invoice Design</h3>
                        </div>
                    </div>

                    <div class="panel-body">

                        <?php
                        if (isset($_POST["saveBtn"])) {

                            $valid_extensions = array('jpeg', 'jpg', 'png');
                            $path = 'assests/images/invoice_uploads/';

                            $img = $_FILES['business_logo']['name'];
                            $tmp = $_FILES['business_logo']['tmp_name'];

                            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

                            $final_image = str_replace(' ', '', rand(1000, 1000000) . $img);

                            if (in_array($ext, $valid_extensions)) {

                                $path = $path . strtolower($final_image);

                                if (move_uploaded_file($tmp, $path)) {

                                    $bname = $_POST["business_name"];
                                    $add_msg = $_POST["additional_msg"];
                                    $bslogan = $_POST["business_slogan"];
                                    $baddress = $_POST["business_address"];
                                    $bwebsite = $_POST["business_website"];
                                    $bphone = $_POST["business_phone"];
                                    $store_id = $_SESSION["store_id"];

                                    $new_query = $con->query(
                                        "INSERT INTO 
                                invoice_template (store_id,business_logo_path,business_name,business_slogan,additional_info,business_address,business_website,business_phone) 
                                VALUES('$store_id','$path','$bname','$bslogan','$add_msg','$baddress','$bwebsite','$bphone')"
                                    );

                                    if ($new_query) {
                                        echo "<div class='alert alert-success'>Changes Saved</div>";
                                    } else {
                                        echo "<div class='alert alert-danger'>Oops!! An error occured. Please try again</div>";
                                    }
                                } else {
                                    echo "<div class='alert alert-danger'>Oops!! An error occured. Could not upload Logo</div>";
                                }
                            } else {
                                echo "<div class='alert alert-danger'>Oops!! An error occured. File type not accepted. Upload only jpg, png or jpeg</div>";
                            }
                        }

                        if (isset($_POST["updateBtn"])) {

                            $valid_extensions = array('jpeg', 'jpg', 'png');
                            $path = '../assests/images/invoice_uploads/';

                            $img = $_FILES['business_logo']['name'];
                            $tmp = $_FILES['business_logo']['tmp_name'];

                            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

                            $final_image = str_replace(' ', '', rand(1000, 1000000) . $img);

                            if (in_array($ext, $valid_extensions)) {

                                $path = $path . strtolower($final_image);

                                if (move_uploaded_file($tmp, $path)) {

                                    $bname = $_POST["business_name"];
                                    $add_msg = $_POST["additional_msg"];
                                    $bslogan = $_POST["business_slogan"];
                                    $baddress = $_POST["business_address"];
                                    $bwebsite = $_POST["business_website"];
                                    $bphone = $_POST["business_phone"];
                                    $invoice_id = $_POST["invoice_id"];

                                    $new_query = $con->query(
                                        "UPDATE 
                                invoice_template SET business_logo_path='$path',
                                business_name='$bname',
                                business_slogan='$bslogan',
                                additional_info='$add_msg',
                                business_address='$baddress',
                                business_website='$bwebsite',
                                business_phone='$bphone'
                                WHERE id='$invoice_id'"
                                    ) or die(mysqli_error($con));

                                    if ($new_query) {
                                        echo "<div class='alert alert-success'>Changes Saved</div>";
                                    } else {
                                        echo "<div class='alert alert-danger'>Oops!! An error occured. Please try again</div>";
                                    }
                                } else {
                                    echo "<div class='alert alert-danger'>Oops!! An error occured. Could not upload Logo</div>";
                                }
                            } else {
                                echo "<div class='alert alert-danger'>Oops!! An error occured. File type not accepted. Upload only jpg, png or jpeg</div>";
                            }
                        }
                        ?>

                        <?php

                        $sql = "SELECT * FROM invoice_template";
                        $result = $con->query($sql);


                        $value = $result->fetch_assoc();
                        $id = $value["id"];
                        $name = $value["business_name"];
                        $logo = $value["business_logo_path"];
                        $slogan = $value["business_slogan"];
                        $info = $value["additional_info"];
                        $address = $value["business_address"];
                        $website = $value["business_website"];
                        $phone = $value["business_phone"];


                        ?>

                        <form action="" method="post" enctype="multipart/form-data">

                            <div class="row ml-5">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="">Business Logo</label>
                                        <input type="file" class="form-control" required id="business_logo" name="business_logo">
                                    </div>

                                    <input type="hidden" name="invoice_id" value="<?php echo $id; ?>">

                                    <div class="form-group">
                                        <label for="">Business Name</label>
                                        <input type="text" class="form-control" required value="<?php echo $name; ?>" id="business_name" name="business_name">
                                    </div>

                                    <div class="form-group">
                                        <label for="">Business Slogan</label>
                                        <input type="text" class="form-control" required value="<?php echo $slogan; ?>" id="business_slogan" name="business_slogan">
                                    </div>

                                    <section class="inv_footer">

                                        <div class="form-group">
                                            <label for="">Additional Message</label>
                                            <input type="text" class="form-control" id="additional_msg" required value="<?php echo $info; ?>" name="additional_msg">
                                        </div>

                                        <div class="form-group">
                                            <label for="">Business Address</label>
                                            <input type="text" class="form-control" id="business_address" required value="<?php echo $address; ?>" name="business_address">
                                        </div>

                                        <div class="form-group">
                                            <label for="">Business Website</label>
                                            <input type="text" class="form-control" id="business_website" required value="<?php echo $website; ?>" name="business_website">
                                        </div>

                                        <div class="form-group">
                                            <label for="">Business Phone Number</label>
                                            <input type="text" class="form-control" id="business_phone" required value="<?php echo $phone; ?>" name="business_phone">
                                        </div>
                                    </section>
                                </div>

                                <div class="col-md-6" style="margin-left: 10px;">

                                    <div class="invoice" style="height: 500px;border:1px solid black;border-radius:4px">
                                        <div id="invoice_header">
                                            <center>
                                                <img src="<?php if (!empty($logo)) {
                                                                echo $logo;
                                                            } else {
                                                                echo "Business Logo";
                                                            } ?>" style="width: 50px;height:30px;margin-top:30px" id="inv_logo" alt="">
                                                <h3 id="inv_bname" style="margin-top: -0.15px;">
                                                    <?php if (!empty($name)) {
                                                        echo $name;
                                                    } else {
                                                        echo "Business Name";
                                                    } ?></h3>
                                                <h5 id="inv_slogan">
                                                    <?php if (!empty($slogan)) {
                                                        echo $slogan;
                                                    } else {
                                                        echo "Business Slogan";
                                                    } ?></h5>
                                            </center>
                                        </div>
                                        <div id="invoice_body" style="margin-left: 10px;">
                                            <p>Invoice No.: <span id="inv_number">#00000</span></p>
                                            <p>Date/Time:
                                                <span id="inv_date_time">22-03-2021 09:12:04</span>
                                                <span style="float: right;right:0;margin-right:40px">USER: <small id="inv_cashier">Cahier1</small> </span>
                                            </p>
                                        </div>

                                        <div style="border: 1px dotted black;width:100%"> </div>

                                        <div id="inv_body" style="margin-top: 30px;margin-bottom:30px">
                                            <center>
                                                <h3>RESERVED AREA</h3>
                                            </center>
                                        </div>

                                        <div style="border: 1px dotted black;width:100%"> </div>

                                        <div id="inv_footer" style="margin-top: 30px;margin-bottom:50px">
                                            <center>
                                                <p id="inv_additional_msg">
                                                    <?php if (!empty($info)) {
                                                        echo $info;
                                                    } else {
                                                        echo "There is no refund of money after payment. Please confirm items before leaving";
                                                    } ?>
                                                </p>

                                                <hr>

                                                <h5 id="inv_address">
                                                    <?php if (!empty($address)) {
                                                        echo $address;
                                                    } else {
                                                        echo "Business Address";
                                                    } ?>
                                                </h5>
                                                <h5 id="inv_website">
                                                    <?php if (!empty($website)) {
                                                        echo $website;
                                                    } else {
                                                        echo "Business Website";
                                                    } ?> </h5>
                                                <h5 id="inv_phone">
                                                    <?php if (!empty($phone)) {
                                                        echo $phone;
                                                    } else {
                                                        echo "Business Phone Number";
                                                    } ?> </h5>
                                            </center>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <center>
                                <?php
                                if (empty($name)) {

                                ?>
                                    <button class="btn btn-success" name="saveBtn" style="width: 20%;max-width:100%">Save</button>
                                <?php
                                } else {

                                ?>
                                    <button class="btn btn-success mb-3 p-3" name="updateBtn" style="width: 20%;max-width:100%">Update</button>

                                <?php } ?>

                            </center>
                        </form>

                    </div> <!-- /panel-body -->
                </div> <!-- /panel -->
            </div> <!-- /col-md-12 -->
        </div> <!-- /row -->
    </div>
    <script src="<?php echo $pageUrl; ?>custom/js/invoice_design.js"></script>


    <?php include "../partials/footer.php"; ?>