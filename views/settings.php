<?php
include "../partials/header.php";
include "../partials/sidebar.php";;
?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row bg-white">
            <div class="col-md-12">
                <?php

                if (isset($_POST["disable_login"])) {
                    $new_query = $con->query(
                        "UPDATE 
        users SET 
        login_status = 0 WHERE user_role != 'owner'"
                    );

                    if ($new_query) {
                        echo "<div class='alert alert-success w-50'>Login Disabled for all users except Owner</div>";
                    } else {
                        echo "<div class='alert alert-danger w-50'>Oops!! An error occured. Please try again</div>";
                    }
                }

                if (isset($_POST["enable_login"])) {
                    $new_query = $con->query(
                        "UPDATE 
        users SET 
        login_status = 1"
                    );

                    if ($new_query) {
                        echo "<div class='alert alert-success w-50'>Login Enabled for all users.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Oops!! An error occured. Please try again</div>";
                    }
                }
                ?>

                <form action="" method="post" class="p-4">
                    <button class="btn customize-abs-btn" style="float: right;margin-right:30px" type="submit" name="disable_login">Disable Logins</button>
                    <button class="btn customize-abs-btn" style="float: right;margin-right:20px" type="submit" name="enable_login">Enable Logins</button>

                </form>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="page-heading">
                            <h3 class="pl-0"> App Settings</h3>
                        </div>
                    </div>

                    <div class="panel-body">

                        <?php
                        $store_id = $_SESSION["store_id"];
                        if (isset($_POST["saveCompanyInfoBtn"])) {

                            saveCompanyInfo($con, $store_id);
                        }

                        if (isset($_POST["updateCompanyInfoBtn"])) {

                            updateCompanyInfo($con);
                        }

                        if (isset($_POST["saveProductInfoBtn"])) {

                            saveProductInfo($con, $store_id);
                        }

                        if (isset($_POST["updateProductInfoBtn"])) {

                            updateProductInfo($con);
                        }

                        if (isset($_POST["savePriceLevelBtn"])) {
                            savePriceLevel($con, $store_id);
                        }

                        if (isset($_POST["updatePriceLevelBtn"])) {
                            updatePriceLevel($con);
                        }

                        ?>

                        <h3>Settings</h3>

                        <div class="row mb-4">

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" style="margin-left: 20px">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#appinfo">App Information</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#pinfo">Other Information/Alerts</a>
                                </li>
                                <!--<li class="nav-item">-->
                                <!--    <a class="nav-link" data-toggle="tab" href="#pricelevel">Price Level</a>-->
                                <!--</li>-->
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane container active" id="appinfo">
                                    <div class="col-md-12">
                                        <?php
                                        //check if data is already available
                                        $sql = "SELECT * FROM settings";
                                        $query = $con->query($sql);
                                        $results = $query->num_rows;

                                        //check if data is in db
                                        if ($results > 0) {

                                            $row = $query->fetch_array();
                                            $getCName = $row["company_name"];
                                            $getComAddress = $row["company_address"];
                                            $getComWebsite = $row["company_website"];
                                            $getComPhone = $row["company_phone"];
                                            $getComEmail = $row["company_email"];
                                            $getComID = $row["id"];
                                            $getComCurrency = $row["currency"];
                                            $getComVat = $row["company_vat"];

                                        ?>
                                            <form action="settings" method="post" style="margin-top: 20px">
                                                <div class="form-group">
                                                    <label for="">Company Name</label>
                                                    <input type="text" class="form-control" value="<?php echo $getCName; ?>" id="company_name" name="company_name">
                                                </div>

                                                <input type="hidden" name="company_id" value="<?php echo $getComID; ?>" />
                                                <div class="form-group">
                                                    <label for="">Company Address</label>
                                                    <input type="text" class="form-control" value="<?php echo $getComAddress; ?>" id="company_address" name="company_address">
                                                </div>

                                                <div class="form-group">
                                                    <label for="">Company Phone Number</label>
                                                    <input type="text" class="form-control" value="<?php echo $getComPhone; ?>" id="company_phone" name="company_phone">
                                                </div>

                                                <div class="form-group">
                                                    <label for="">Company Email</label>
                                                    <input type="email" class="form-control" value="<?php echo $getComEmail; ?>" id="company_email" name="company_email">
                                                </div>

                                                <div class="form-group">
                                                    <label for="">Company Website</label>
                                                    <input type="text" class="form-control" value="<?php echo $getComWebsite; ?>" id="company_website" name="company_website">
                                                </div>

                                                <div class="form-group">
                                                    <label for="">Currency</label>
                                                    <select class="form-control" id="company_currency" name="company_currency">
                                                        <option value="<?php echo htmlspecialchars_decode($getComCurrency); ?>" selected="selected" label="<?php echo htmlspecialchars_decode($getComCurrency); ?>"><?php echo htmlspecialchars_decode($getComCurrency); ?></option>
                                                        <option value="&#8358;">Nigerian Naira &#8358;)</option>
                                                        <option value="&#8373;">Ghana Cedis (&#8373;)</option>
                                                        <option value="&#36;">US dollar (&#36;)</option>
                                                        <option value="&#128;">Euro (&#128;)</option>
                                                        <option value="&#165;">Japanese Yen (&#165;)</option>
                                                        <option value="&#163;">Pound sterling (&#163;)</option>
                                                        <option value="&#8355;">French Franc(&#8355;)</option>
                                                        <option value="&#8356;">Turkish Lira (&#8356;)</option>
                                                        <option value="&#8359;">Spanish Peseta (&#8359;)</option>
                                                        <option value="&#x20B9;">Indian Rupee(&#x20B9;)</option>
                                                        <option value="&#8361;"> Korean Won(&#8361;)</option>
                                                        <option value="&#8372;">Ukrainian Hryvnia (&#8372;)</option>
                                                        <option value="&#8367;">Greek Drachma (&#8367;)</option>
                                                        <option value="&#8366;">Mongolian tögrög (&#8366;)</option>
                                                        <option value="&#8368;">German Penny (&#8368;)</option>
                                                        <option value="&#8370;">Paraguayan Guarani (&#8370;)</option>
                                                        <option value="&#8369;">Philippine Peso (&#8369;)</option>
                                                        <option value="&#8371;">Argentine Austral (&#8371;)</option>
                                                        <option value="&#8365;">Laos Kip (&#8365;)</option>
                                                        <option value="&#8362;">Israeli Sheqel (&#8362;)</option>
                                                        <option value="&#8363;">Vietnamese Dong (&#8363;)</option>
                                                    </select>

                                                </div>

                                                <div class="form-group">
                                                    <label for="">VAT (In Percentage - %)</label>
                                                    <input type="text" class="form-control" value="<?php echo $getComVat; ?>" id="company_vat" name="company_vat">
                                                </div>

                                                <center>
                                                    <button class="btn customize-abs-btn p-3" name="updateCompanyInfoBtn">Save Company Information</button>
                                                </center>
                                            </form>

                                        <?php } else { ?>
                                            <form action="settings" method="post" style="margin-top: 20px">
                                                <div class="form-group">
                                                    <label for="">Company Name</label>
                                                    <input type="text" class="form-control" id="company_name" name="company_name">
                                                </div>

                                                <input type="hidden" name="customer_id" />
                                                <div class="form-group">
                                                    <label for="">Company Address</label>
                                                    <input type="text" class="form-control" id="company_address" name="company_address">
                                                </div>

                                                <div class="form-group">
                                                    <label for="">Company Phone Number</label>
                                                    <input type="text" class="form-control" id="company_phone" name="company_phone">
                                                </div>

                                                <div class="form-group">
                                                    <label for="">Company Email</label>
                                                    <input type="email" class="form-control" id="company_email" name="company_email">
                                                </div>

                                                <div class="form-group">
                                                    <label for="">Company Website</label>
                                                    <input type="text" class="form-control" id="company_website" name="company_website">
                                                </div>

                                                <div class="form-group">
                                                    <label for="">Currency</label>

                                                    <select class="form-control" id="company_currency" name="company_currency">
                                                        <option value="&#8358;" selected>Nigerian Naira (&#8358;)</option>
                                                        <option value="&#8373;">Ghana Cedis (&#8373;)</option>
                                                        <option value="&#36;">US dollar (&#36;)</option>
                                                        <option value="&#128;">Euro (&#128;)</option>
                                                        <option value="&#165;">Japanese Yen (&#165;)</option>
                                                        <option value="&#163;">Pound sterling (&#163;)</option>
                                                        <option value="&#8355;">French Franc(&#8355;)</option>
                                                        <option value="&#8356;">Turkish Lira (&#8356;)</option>
                                                        <option value="&#8359;">Spanish Peseta (&#8359;)</option>
                                                        <option value="&#x20B9;">Indian Rupee(&#x20B9;)</option>
                                                        <option value="&#8361;"> Korean Won(&#8361;)</option>
                                                        <option value="&#8372;">Ukrainian Hryvnia (&#8372;)</option>
                                                        <option value="&#8367;">Greek Drachma (&#8367;)</option>
                                                        <option value="&#8366;">Mongolian tögrög (&#8366;)</option>
                                                        <option value="&#8368;">German Penny (&#8368;)</option>
                                                        <option value="&#8370;">Paraguayan Guarani (&#8370;)</option>
                                                        <option value="&#8369;">Philippine Peso (&#8369;)</option>
                                                        <option value="&#8371;">Argentine Austral (&#8371;)</option>
                                                        <option value="&#8365;">Laos Kip (&#8365;)</option>
                                                        <option value="&#8362;">Israeli Sheqel (&#8362;)</option>
                                                        <option value="&#8363;">Vietnamese Dong (&#8363;)</option>



                                                    </select>

                                                </div>

                                                <div class="form-group">
                                                    <label for="">VAT (In Percentage - %)</label>
                                                    <input type="text" class="form-control" id="company_vat" name="company_vat">
                                                </div>

                                                <center>
                                                    <button class="btn customize-abs-btn p-3" name="saveCompanyInfoBtn">Save Company Information</button>
                                                </center>
                                            </form>

                                        <?php } ?>

                                    </div>

                                </div>
                                <div class="tab-pane container fade" id="pinfo">

                                    <?php
                                    //check if data is already available
                                    $sql = "SELECT * FROM product_info_settings";
                                    $query = $con->query($sql);
                                    $results = $query->num_rows;

                                    //check if data is in db
                                    if ($results > 0) {

                                        $row = $query->fetch_array();
                                        $getQtyAlert = $row["low_product_alertqty"];
                                        $getGenDiscount = $row["general_product_discount"];
                                        $getSalesStatus = $row["disable_sales"];
                                        $getPInfoID = $row["id"];

                                        $sql2 = "SELECT * FROM settings";
                                        $query2 = $con->query($sql2);
                                        $results2 = $query2->num_rows;
                                        $row2 = $query2->fetch_array();

                                        $getBdayAlert = $row2["bday_alert"];
                                        $getExpirationAlert = $row2["expiration_alert"];
                                        $getBdayMsg = $row2["bday_message"];
                                        $getBdaySender = $row2["bday_message_sender"];

                                    ?>
                                        <form action="settings" method="post" style="margin-top: 20px;">

                                            <input type="hidden" name="pinfo_id" value="<?php echo $getPInfoID; ?>" id="">

                                            <div class="form-group">
                                                <label for="">Disable Product Sales</label>
                                                <select name="disable_sales" id="" class="form-control">
                                                    <option selected value="<?php echo $getSalesStatus; ?>"><?php if ($getSalesStatus == 1) {
                                                                                                                echo "Yes";
                                                                                                            } else {
                                                                                                                echo "No";
                                                                                                            } ?></option>
                                                    <option value="0">Yes</option>
                                                    <option value="1">No</option>
                                                </select>
                                            </div>


                                            <div class="form-group">
                                                <label for="">Receive Product Expiration Alerts From</label>
                                                <select name="expiration_alert" id="" class="form-control">
                                                    <option selected value="<?php echo $getExpirationAlert; ?>"><?php echo $getExpirationAlert; ?></option>
                                                    <option value="4weeks">One Month Before Date</option>
                                                    <option value="8weeks">Two Months Before Date</option>
                                                    <option value="12weeks">Three Months Before Date</option>
                                                    <option value="16weeks">Four Months Before Expiration</option>

                                                </select>
                                            </div>


                                            <div class="form-group">
                                                <label for="">Receive Birthday Alerts From</label>
                                                <select name="bday_alert" id="" class="form-control">
                                                    <option selected value="<?php echo $getBdayAlert; ?>"><?php echo $getBdayAlert; ?></option>
                                                    <option value="1week">One Week Before Date</option>
                                                    <option value="2weeks">Two Weeks Before Date</option>
                                                    <option value="2weeks">Three Weeks Before Date</option>
                                                    <option value="4weeks">One Month Before Date</option>
                                                </select>
                                            </div>

                                            <div class="form-group">

                                                <label for="">Birthday Message</label>

                                                <textarea class="form-control" name="bday_message"><?php echo ucwords($getBdayMsg); ?></textarea>


                                                <p style="color: red;">
                                                    <i>An SMS page allows for up to 120 Characters</i>
                                                </p>

                                            </div>

                                            <div class="form-group">
                                                <label for="">Birthday Message Sender Name</label>
                                                <input type="text" value="<?php echo ucwords($getBdaySender); ?>" class="form-control" maxlength="11" name="bday_message_sender">
                                                <p style="color: red;"><i>Only 11 Characters are allowed</i></p>
                                            </div>

                                            <center>
                                                <button class="btn customize-abs-btn p-3" name="updateProductInfoBtn">Set Product Information</button>
                                            </center>
                                        </form>

                                    <?php  } else { ?>

                                        <form action="settings" method="post" style="margin-top: 20px;">

                                            <div class="form-group">
                                                <label for="">Disable Product Sales</label>
                                                <select name="disable_sales" id="" class="form-control">
                                                    <option selected>--Select Option--</option>
                                                    <option value="yes">Yes</option>
                                                    <option value="no">No</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="">Receive Product Expiration Alerts From</label>
                                                <select name="expiration_alert" id="" class="form-control">
                                                    <option selected>--Select Option--</option>
                                                    <option value="4weeks">One Month Before Date</option>
                                                    <option value="8weeks">Two Months Before Date</option>
                                                    <option value="12weeks">Three Months Before Date</option>
                                                    <option value="16weeks">Four Months Before Expiration</option>

                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="">Receive Birthday Alerts From</label>
                                                <select name="bday_alert" id="" class="form-control">
                                                    <option selected>--Select Option--</option>
                                                    <option value="1week">One Week Before Date</option>
                                                    <option value="2weeks">Two Weeks Before Date</option>
                                                    <option value="2weeks">Three Weeks Before Date</option>
                                                    <option value="4weeks">One Month Before Date</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="">Birthday Message</label>
                                                <textarea class="form-control" name="bday_message"></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="">Birthday Message Sender Name</label>
                                                <input type="text" class="form-control" name="bday_message_sender">
                                            </div>

                                            <center>
                                                <button class="btn customize-abs-btn p-3" name="saveProductInfoBtn">Update Product Information</button>
                                            </center>
                                        </form>

                                    <?php } ?>

                                </div>

                                <div class="tab-pane container fade" id="pricelevel">
                                    <form action="settings" method="post" style="margin-top: 20px;">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <table class="table table-striped">
                                                    <tr>
                                                        <th>Level</th>
                                                        <th>Quantity (Range above )</th>
                                                        <th>Quantity (Range below )</th>
                                                        <th>Amount to Subtract</th>
                                                    </tr>
                                                    <?php

                                                    $sql = "SELECT * FROM price_level_settings";
                                                    $query = $con->query($sql);
                                                    $results = $query->num_rows;

                                                    //check if data is in db
                                                    if ($results > 0) {

                                                        for ($sloop = 0; $sloop < $results; $sloop++) {
                                                            $row = $query->fetch_array();
                                                            $getCat = $row["pricelevel_category"];
                                                            $getQtyAbove = $row["price_level_qty_above"];
                                                            $getQtyBelow = $row["price_level_qty_below"];
                                                            $getAmt = $row["price_level_amount"];
                                                            $getID = $row["id"];

                                                    ?>
                                                            <?php

                                                            $level = ["Regular", "Sales", "Wholesale", "Employee", "Sub Distributor"];
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <label for=""><?php echo $level[$sloop]; ?></label>
                                                                        <input type="hidden" value="Regular" name="price_level_category[<?php echo $level[$sloop]; ?>]">
                                                                    </div>
                                                                </td>

                                                                <input type="hidden" value="<?php echo $getID; ?>" name="id[<?php echo $sloop; ?>]">

                                                                <td>
                                                                    <div class="form-group">
                                                                        <input type="number" class="form-control" value="<?php echo $getQtyAbove; ?>" name="price_level_qty_above[<?php echo $sloop; ?>]">
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <div class="form-group">
                                                                        <input type="number" class="form-control" value="<?php echo $getQtyBelow; ?>" name="price_level_qty_below[<?php echo $sloop; ?>]">
                                                                    </div>
                                                                </td>

                                                                <td>

                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" value="<?php echo $getAmt; ?>" name="price_level_amount[<?php echo $sloop; ?>]">
                                                                    </div>
                                                                </td>

                                                            </tr>

                                                        <?php }

                                                        ?>

                                                </table>

                                                <center>
                                                    <button class="btn customize-abs-btn" name="updatePriceLevelBtn" style="width: 50%;max-width:100%">Update Price Level</button>
                                                </center>

                                                <?php

                                                    } else {

                                                        $level = ["Regular", "Sales", "Wholesale", "Employee", "Sub Distributor"];
                                                        for ($x = 0; $x <= 4; $x++) {
                                                ?>


                                                    <tr>
                                                        <td>
                                                            <div class="form-group">
                                                                <label for=""><?php echo $level[$x]; ?></label>
                                                                <input type="hidden" value="Regular" name="price_level_category[<?php echo $level[$x]; ?>]">
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="form-group">
                                                                <input type="number" class="form-control" value="0" name="price_level_qty_above[<?php echo $x; ?>]">
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="form-group">
                                                                <input type="number" class="form-control" value="0" name="price_level_qty_below[<?php echo $x; ?>]">
                                                            </div>
                                                        </td>

                                                        <td>

                                                            <div class="form-group">
                                                                <input type="text" class="form-control" value="0.00" name="price_level_amount[<?php echo $x; ?>]">
                                                            </div>
                                                        </td>

                                                    </tr>
                                                <?php }

                                                ?>
                                                </table>


                                                <center>
                                                    <button class="btn customize-abs-btn" name="savePriceLevelBtn" style="width: 50%;max-width:100%">Set Price Level</button>
                                                </center>
                                            <?php } ?>
                                            </div>

                                        </div>
                                    </form>

                                </div>
                            </div>

                        </div>

                    </div> <!-- /panel-body -->
                </div> <!-- /panel -->
            </div> <!-- /col-md-12 -->
        </div> <!-- /row -->
    </div>
    <?php include "../partials/footer.php"; ?>