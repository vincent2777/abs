<?php

date_default_timezone_set("Africa/Lagos");

//randomize BTN Color in Customization
function randomizeBtnColor($btn_color1, $btn_color2)
{

    $btnColors = array($btn_color1, $btn_color2);
    $position = shuffle($btnColors);
    return $btnColors[$position];
}


function setPriviledge($con, $username, $priviledge)
{
    $priviledge_status = [];
    $newarr = [];

    foreach ($priviledge as $granted) {
        array_push($newarr, $granted);
    }

    foreach ($username as $user) {
        $user_selected = $user;

        if (in_array("cansell", $newarr)) {
            array_push($priviledge_status, 1);
        } else {
            array_push($priviledge_status, 0);
        }

        if (in_array("editprice", $newarr)) {
            array_push($priviledge_status, 1);
        } else {
            array_push($priviledge_status, 0);
        }

        if (in_array("inventory", $newarr)) {
            array_push($priviledge_status, 1);
        } else {
            array_push($priviledge_status, 0);
        }

        if (in_array("printreport", $newarr)) {
            array_push($priviledge_status, 1);
        } else {
            array_push($priviledge_status, 0);
        }

        if (in_array("initiate_returns", $newarr)) {
            array_push($priviledge_status, 1);
        } else {
            array_push($priviledge_status, 0);
        }

        if (in_array("qty_log", $newarr)) {
            array_push($priviledge_status, 1);
        } else {
            array_push($priviledge_status, 0);
        }

        if (in_array("view_products", $newarr)) {
            array_push($priviledge_status, 1);
        } else {
            array_push($priviledge_status, 0);
        }

        if (in_array("create_users", $newarr)) {
            array_push($priviledge_status, 1);
        } else {
            array_push($priviledge_status, 0);
        }

        if (in_array("canview_daily_sales", $newarr)) {
            array_push($priviledge_status, 1);
        } else {
            array_push($priviledge_status, 0);
        }

        if (in_array("canview_total_sales", $newarr)) {
            array_push($priviledge_status, 1);
        } else {
            array_push($priviledge_status, 0);
        }

        if (in_array("candoexpenses", $newarr)) {
            array_push($priviledge_status, 1);
        } else {
            array_push($priviledge_status, 0);
        }

        if (in_array("canaccess_heldreceipts", $newarr)) {
            array_push($priviledge_status, 1);
        } else {
            array_push($priviledge_status, 0);
        }

        if (in_array("cangive_discount", $newarr)) {
            array_push($priviledge_status, 1);
        } else {
            array_push($priviledge_status, 0);
        }

        if (in_array("canedit_qty", $newarr)) {
            array_push($priviledge_status, 1);
        } else {
            array_push($priviledge_status, 0);
        }

        if (in_array("change_app_settings", $newarr)) {
            array_push($priviledge_status, 1);
        } else {
            array_push($priviledge_status, 0);
        }

        if (in_array("canreceive_direct_orders", $newarr)) {
            array_push($priviledge_status, 1);
        } else {
            array_push($priviledge_status, 0);
        }

        if (in_array("view_customers", $newarr)) {
            array_push($priviledge_status, 1);
        } else {
            array_push($priviledge_status, 0);
        }

        if (in_array("import_inventory", $newarr)) {
            array_push($priviledge_status, 1);
        } else {
            array_push($priviledge_status, 0);
        }

        $sql = "UPDATE users SET 
pvld_cansell='$priviledge_status[0]',
pvld_editprice='$priviledge_status[1]',
pvld_inventory='$priviledge_status[2]',
pvld_printreport='$priviledge_status[3]',
pvld_initiate_returns='$priviledge_status[4]',
pvld_view_qtylog='$priviledge_status[5]',
pvld_view_product='$priviledge_status[6]',
pvld_create_users='$priviledge_status[7]',
pvld_view_dailysales='$priviledge_status[8]',
pvld_view_allsales='$priviledge_status[9]',
pvld_candoexpenses='$priviledge_status[10]',
pvld_canaccess_heldreceipts = '$priviledge_status[11]',
pvld_cangive_discount = '$priviledge_status[12]',
pvld_canedit_qty = '$priviledge_status[13]',
pvld_editapp_settings='$priviledge_status[14]',
pvld_canreceive_direct_orders='$priviledge_status[15]',
pvld_view_customers='$priviledge_status[16]',
pvld_import_inventory='$priviledge_status[17]'
WHERE id = '$user_selected'";
        $result = $con->query($sql);
    }

    if ($result) {
        echo "<div class='alert alert-success'>Priviledges Updated</div>";
    } else {
        echo "<div class='alert alert-success'>Sorry...An error occured. Please try again</div>";
    }
}

function checkPriviledge($con, $user)
{

    //check all priviledge
    $sql = "SELECT * FROM users WHERE username='$user'";
    $result = $con->query($sql);

    while ($fetch = mysqli_fetch_array($result)) {

        $pvld_cansell = $fetch["pvld_cansell"];
        $pvld_editprice = $fetch["pvld_editprice"];
        $pvld_inventory = $fetch["pvld_inventory"];
        $pvld_printreport = $fetch["pvld_printreport"];
        $pvld_initiate_returns = $fetch["pvld_initiate_returns"];
        $pvld_view_qtylog = $fetch["pvld_view_qtylog"];
        $pvld_view_product = $fetch["pvld_view_product"];
        $pvld_create_users = $fetch["pvld_create_users"];
        $pvld_view_dailysales = $fetch["pvld_view_dailysales"];
        $pvld_view_allsales = $fetch["pvld_view_allsales"];
        $pvld_candoexpenses = $fetch["pvld_candoexpenses"];
        $pvld_cangive_discount = $fetch["pvld_cangive_discount"];
        $pvld_canaccess_heldreceipts = $fetch["pvld_canaccess_heldreceipts"];
        $pvld_canedit_qty = $fetch["pvld_canedit_qty"];
        $pvld_canreceive_direct_order = $fetch["pvld_canreceive_direct_orders"];
        $pvld_editapp_settings = $fetch["pvld_editapp_settings"];
        $pvld_view_customers = $fetch["pvld_view_customers"];
        $pvld_import_inventory = $fetch["pvld_import_inventory"];
        $pvld_printreport = $fetch["pvld_printreport"];

        $priviledge = array(
            "cansell" => $pvld_cansell,
            "editprice" => $pvld_editprice,
            "inventory" => $pvld_inventory,
            "printreport" => $pvld_printreport,
            "initiate_returns" => $pvld_initiate_returns,
            "qty_log" => $pvld_view_qtylog,
            "view_product" => $pvld_view_product,
            "create_users" => $pvld_create_users,
            "canview_daily_sales" => $pvld_view_dailysales,
            "canview_total_sales" => $pvld_view_allsales,
            "candoexpenses" => $pvld_candoexpenses,
            "canaccess_heldreceipts" => $pvld_canaccess_heldreceipts,
            "cangive_discount" => $pvld_cangive_discount,
            "canedit_qty" => $pvld_canedit_qty,
            "canreceive_direct_orders" => $pvld_canreceive_direct_order,
            "change_app_settings" => $pvld_editapp_settings,
            "view_customers" => $pvld_view_customers,
            "import_inventory" => $pvld_import_inventory
        );
    }


    if ($result) {
        return $priviledge;
    } else {
        return "Could not extract Priviledges";
    }
}

function saveProductInfo($con, $store_id)
{
    $d_sales = $_POST["disable_sales"];
    $expiration_alert = $_POST["expiration_alert"];
    $bday_message = $_POST["bday_message"];
    $sender = $_POST["bday_message_sender"];
    $bday_alert = $_POST["bday_alert"];

    $new_query2 = $con->query(
        "INSERT INTO settings (expiration_alert,bday_message,bday_message_sender,bday_alert) 
        VALUES('$expiration_alert','$bday_message','$sender','$bday_alert')"
    );

    $new_query = $con->query(
        "INSERT INTO 
        product_info_settings (store_id,disable_sales) 
        VALUES('$store_id','$d_sales')"
    );

    if ($new_query && $new_query2) {
        echo "<div class='alert alert-success'><i class='fas fa-ok-sign'></i> Information Saved!</div>";
    } else {
        echo "<div class='alert alert-danger'><i class='fas fa-remove-sign'></i> Oops!! An error occured. Please try again</div>";
    }
}

function updateProductInfo($con)
{

    $d_sales = $_POST["disable_sales"];
    $pinfo_id = $_POST["pinfo_id"];
    $expiration_alert = $_POST["expiration_alert"];
    $bday_message = $_POST["bday_message"];
    $sender = $_POST["bday_message_sender"];
    $bday_alert = $_POST["bday_alert"];

    $new_query = $con->query(
        "UPDATE
        product_info_settings SET 
        disable_sales='$d_sales'"
    );

    $new_query2 = $con->query(
        "UPDATE settings SET 
        bday_alert='$bday_alert',
        bday_message='$bday_message',
        bday_message_sender='$sender',
        expiration_alert='$expiration_alert'"
    );

    if ($new_query && $new_query2) {
        echo "<div class='alert alert-success'><i class='fas fa-ok-sign'></i> Information Updated!</div>";
    } else {
        echo "<div class='alert alert-danger'><i class='fas fa-remove-sign'></i> Oops!! An error occured. Please try again</div>";
    }
}

function savePriceLevel($con, $store_id)
{
    $level = ["Regular", "Sales", "Wholesale", "Employee", "Sub Distributor"];

    //get price level as array
    for ($x = 0; $x <= 4; ++$x) {
        $pl_category = $level[$x];
        $pl_quantity_above = $_POST["price_level_qty_above"][$x];
        $pl_quantity_below = $_POST["price_level_qty_below"][$x];
        $pl_amount = $_POST["price_level_amount"][$x];

        // construct the query and execute it
        $new_query = $con->query(
            "INSERT INTO 
                        price_level_settings (store_id,pricelevel_category,price_level_qty_above,price_level_qty_below,price_level_amount) 
                        VALUES('$store_id','$pl_category','$pl_quantity_above','$pl_quantity_below','$pl_amount')"
        ) or die(mysqli_error($con));
    }

    if ($new_query) {
        echo "<div class='alert alert-success'><i class='fas fa-ok-sign'></i> New Price Level Saved</div>";
    } else {
        echo "<div class='alert alert-danger'><i class='fas fa-remove-sign'></i> Oops!! An error occured. Please try again</div>";
    }
}

function updatePriceLevel($con)
{

    $level = ["Regular", "Sales", "Wholesale", "Employee", "Sub Distributor"];

    //get price level as array
    for ($x = 0; $x <= 4; ++$x) {
        $pl_category = $level[$x];
        $pl_quantity_above = $_POST["price_level_qty_above"][$x];
        $pl_quantity_below = $_POST["price_level_qty_below"][$x];
        $pl_amount = $_POST["price_level_amount"][$x];
        $pl_id = $_POST["id"][$x];

        // construct the query and execute it
        $new_query = $con->query(
            "UPDATE price_level_settings SET 
        pricelevel_category='$pl_category',
        price_level_qty_above='$pl_quantity_above',
        price_level_qty_below='$pl_quantity_below',
        price_level_amount='$pl_amount'
        WHERE id='$pl_id'"
        ) or die(mysqli_error($con));
    }

    if ($new_query) {
        echo "<div class='alert alert-success'><i class='fas fa-ok-sign'></i> Price Level Updated</div>";
    } else {
        echo "<div class='alert alert-danger'> <i class='fas fa-remove-sign'></i> Oops!! An error occured. Please try again</div>";
    }
}

function saveCompanyInfo($con, $store_id)
{
    $company_name = $_POST["company_name"];
    $company_address = $_POST["company_address"];
    $company_phone = $_POST["company_phone"];
    $company_email = $_POST["company_email"];
    $company_website = $_POST["company_website"];
    $company_vat = $_POST["company_vat"];
    $company_currency = htmlspecialchars($_POST["company_currency"]);

    $new_query = $con->query(
        "INSERT INTO 
        settings (store_id,company_name,company_address,company_phone,company_email,company_website,currency,company_vat) 
        VALUES('$store_id','$company_name','$company_address','$company_phone','$company_email','$company_website','$company_currency','$company_vat')"
    );

    if ($new_query) {
        echo "<div class='alert alert-success'><i class='fas fa-ok-sign'></i> Company Profile Saved!</div>";
    } else {
        echo "<div class='alert alert-danger'><i class='fas fa-remove-sign'></i> Oops!! An error occured. Please try again</div>";
    }
}

function updateCompanyInfo($con)
{
    $company_name = $_POST["company_name"];
    $company_address = $_POST["company_address"];
    $company_phone = $_POST["company_phone"];
    $company_email = $_POST["company_email"];
    $company_website = $_POST["company_website"];
    $company_id = $_POST["company_id"];
    $company_vat = $_POST["company_vat"];
    $company_currency = htmlspecialchars($_POST["company_currency"]);

    $new_query = $con->query(
        "UPDATE 
        settings SET 
        company_name = '$company_name',
        company_address = '$company_address',
        company_phone = '$company_phone',
        company_email = '$company_email',
        company_website  = '$company_website',
        currency  = '$company_currency',
        company_vat  = '$company_vat'
        WHERE id='$company_id'"
    );

    if ($new_query) {
        echo "<div class='alert alert-success'><i class='fas fa-ok-sign'></i> Company Profile Updated!</div>";
    } else {
        echo "<div class='alert alert-danger'><i class='fas fa-remove-sign'></i> Oops!! An error occured. Please try again</div>";
    }
}


function checkoutDiscountController($prod_name, $prod_id, $prod_discount)
{
    //store discount and product number in array
    $itemArray = array($prod_id => array('p_name' => $prod_name, 'prod_id' => $prod_id, 'discount' => $prod_discount));

    if (!empty($_SESSION["cart_discounts"])) {

        if (in_array($prod_id, array_keys($_SESSION["cart_discounts"]))) {

            foreach ($_SESSION["cart_discounts"] as $k => $v) {

                if ($prod_id == $k) {

                    if (empty($_SESSION["cart_discounts"][$k]["discount"])) {
                        $_SESSION["cart_discounts"][$k]["discount"] = 0;
                    }

                    $_SESSION["cart_discounts"][$k]["discount"] = $prod_discount;
                }
            }
        } else {
            $_SESSION["cart_discounts"] = array_merge($_SESSION["cart_discounts"], $itemArray);
        }
    } else {
        $_SESSION["cart_discounts"] = $itemArray;
    }
}

function yearlyExpensesChart($con, $year)
{
    //get product purchase price using the order number
    $sql = mysqli_query($con, "SELECT year($year) as year, month(exp_date) as month, sum(exp_amount) as total_amount
    FROM expenditures
    GROUP BY year($year), month(exp_date)") or die(mysqli_error($con));

    $expensesData = array();

    while ($row = mysqli_fetch_array($sql)) {

        $temp = array();

        $my_month = $row["month"];
        $total_amount = $row["total_amount"];
        $my_year = $row["year"];
        $dateObj   = DateTime::createFromFormat('!m', $my_month);
        $monthName = $dateObj->format('F');

        $temp['y'] = $total_amount;
        $temp['label'] = $monthName;
        array_push($expensesData, $temp);
    }

    return $expensesData;
}


function yearlySalesChart($con, $year)
{
    //get product purchase price using the order number
    $sql = mysqli_query($con, "SELECT year($year) as year, month(order_date) as month, sum(sold_at_price) as total_amount
    FROM sold_products
    GROUP BY year($year), month(order_date)") or die(mysqli_error($con));

    $salesData = array();

    while ($row = mysqli_fetch_array($sql)) {

        $temp = array();

        $my_month = $row["month"];
        $total_amount = $row["total_amount"];
        $my_year = $row["year"];
        $dateObj   = DateTime::createFromFormat('!m', $my_month);
        $monthName = $dateObj->format('F');

        $temp['y'] = $total_amount;
        $temp['label'] = $monthName;
        array_push($salesData, $temp);
    }

    return $salesData;
}

function generateExpensesReport($con, $from, $to, $currency, $filterUser)
{

    $sql = mysqli_query($con, "SELECT * FROM expenditures WHERE exp_date BETWEEN '$from' AND '$to' ORDER BY exp_date ASC") or die(mysqli_error($con));

    if (!empty($filterUser)) {
        $sql = mysqli_query($con, "SELECT * FROM expenditures WHERE exp_cashierid='$filterUser' AND exp_date BETWEEN '$from' AND '$to' ORDER BY exp_date ASC") or die(mysqli_error($con));
    }
    if (mysqli_num_rows($sql) > 0) {

        while ($data = mysqli_fetch_array($sql)) {

            $getDate = $data["exp_date"];
            $getDate = date("d M, Y", strtotime($getDate));
            echo "<tr>
                 <td>" . $data["exp_refno"] . "</td>
                 <td>" . $data["exp_cashierid"] . "</td>
                 <td>" . $data["exp_description"] . "</td>
                 <td>" . $data["exp_category"] . "</td>
                 <td>$currency" . number_format($data["exp_amount"], 2) . "</td>
                 <td>" . $getDate . "</td>
                 
            </tr>";
        }
    } else {
        echo "<tr><h3>No Data Available..Please refine your search.</h3>";
    }
}

function generateDebtorsReport($con, $from, $to)
{
    $total_credit = 0;
    $credit_qty  = 0;
    $paid_amount = 0;

    $sql = mysqli_query($con, "SELECT customer_id,customer_name,
    customer_phone,customer_address,paid_amount,quantity,AVG(paid_amount) 
    as p_amt,SUM(quantity) as credit_qty,total_amount,AVG(total_amount) 
    AS total_credit 
    FROM sold_products WHERE paid_amount >= 0 
	AND order_date BETWEEN '$from' AND '$to' AND payment_type='Part Payment' GROUP BY customer_id") or die(mysqli_error($con));

    if (mysqli_num_rows($sql) > 0) {

        while ($data = mysqli_fetch_array($sql)) {

            $total_credit += $data["total_credit"];
            $credit_qty += $data["credit_qty"];
            $paid_amount += $data["p_amt"];
            $cust_id = $data["customer_id"];

            $total_credit =  $data["total_credit"];

            $old_credit_sql = mysqli_query($con, "SELECT amount_paid, SUM(amount_paid) AS total FROM balance_sheet 
            WHERE payment_date BETWEEN '$from' AND '$to' AND customer_id='$cust_id'") or die(mysqli_error($con));
            $old_creditData = mysqli_fetch_array($old_credit_sql);


            $getOrderDate = $data["order_date"];
            $getOrderDate = date("d M, Y", strtotime($getOrderDate));
            echo "<tr>
                 <td>" . ucwords($data["customer_name"]) . "</td>
                 <td>" . $data["customer_phone"] . "</td>
                 <td>" . ucwords($data["customer_address"]) . "</td>
                 <td>" .  number_format($data["credit_qty"], 2) . "</td>";
            if (!empty($cust_id)) {
                echo "
                 <td>
                 <form action=\"customer_report\" target='_blank' method=\"post\" style=\"display: inline;\">
                 <input type=\"hidden\" name=\"customerid\" value=\"" . $data["customer_id"] . "\">
                 <button class=\"btn customize-abs-btn\" name=\"reportBtn\">
                 <i class=\"fas fa-chart-area\"></i></button>
                 </form>
                </td>";
            }
            echo "</tr>";
        }
    } else {
        echo "<tr><h3>No Data Available..Please refine your search.</h3>";
    }
}

function generateMSItemsReport($con, $from, $to, $currency)
{
    $sql = mysqli_query($con, "SELECT product_name,product_id, COUNT(product_id) AS total_product , SUM(sold_at_price) AS total_amt FROM sold_products WHERE payment_type='Full Payment' AND order_date BETWEEN '$from' AND '$to' GROUP BY product_id ORDER BY order_date ASC") or die(mysqli_error($con));
    if (mysqli_num_rows($sql) > 0) {

        while ($data = mysqli_fetch_array($sql)) {

            echo "<tr>
                 <td>" . $data["product_id"] . "</td>
                 <td>" . $data["product_name"] . "</td>
                 <td>" . $data["total_product"] . "</td>
                 <td>$currency" . number_format($data["total_amt"], 2) . "</td>
            </tr>";
        }
    } else {
        echo "<tr><h3>No Data Available..Please refine your search.</h3>";
    }
}


function generateReversalsReport($con, $from, $to, $filterUser)
{

    $sql = mysqli_query($con, "SELECT * FROM reverted_receipts WHERE reversal_date BETWEEN '$from' AND '$to' ORDER BY reversal_date ASC") or die(mysqli_error($con));

    if (!empty($filterUser)) {
        $sql = mysqli_query($con, "SELECT * FROM reverted_receipts WHERE cashier='$filterUser' AND reversal_date BETWEEN '$from' AND '$to' ORDER BY reversal_date ASC") or die(mysqli_error($con));
    }
    if (mysqli_num_rows($sql) > 0) {

        while ($data = mysqli_fetch_array($sql)) {

            echo "<tr>
                 <td>" . $data["invoice_number"] . "</td>
                 <td>" . $data["product_name"] . "</td>
                 <td>" . $data["quantity"] . "</td>
                 <td>" . number_format($data["sold_at_price"], 2) . "</td>
                 <td>" . number_format($data["total_amount"], 2) . "</td>
                 <td>" . $data["payment_type"] . "</td>
                 <td>" . $data["payment_method"] . "</td>
                 <td>" . $data["customer_name"] . " <br> " . $data["customer_phone"] . " <br> " . $data["customer_address"] . "</td>
                 <td>" . $data["order_date"] . "</td>
                 <td>" . $data["reversal_date"] . "</td>
            </tr>";
        }
    } else {
        echo "<tr><h3>No Data Available..Please refine your search.</h3>";
    }
}

function generateReturnsReport($con, $from, $to)
{

    $sql = mysqli_query($con, "SELECT * FROM returned_receipts WHERE return_date >= '$from' AND return_date <= '$to' ORDER BY return_date ASC") or die(mysqli_error($con));
    if (mysqli_num_rows($sql) > 0) {

        while ($data = mysqli_fetch_array($sql)) {

            echo "<tr>
                 <td>" . $data["invoice_number"] . "</td>
                 <td>" . $data["product_name"] . "</td>
                 <td>" . $data["quantity"] . "</td>
                 <td>" . number_format($data["sold_at_price"], 2) . "</td>
                 <td>" . number_format($data["total_amount"], 2) . "</td>
                 <td>" . $data["payment_type"] . "</td>
                 <td>" . $data["payment_method"] . "</td>
                 <td>" . $data["customer_name"] . " <br> " . $data["customer_phone"] . " <br> " . $data["customer_address"] . "</td>
                 <td>" . $data["order_date"] . "</td>
                 <td>" . $data["return_date"] . "</td>
            </tr>";
        }
    } else {
        echo "<tr><h3>No Data Available..Please refine your search.</h3>";
    }
}

function generatePQCLogReport($con, $from, $to)
{
    $sql = mysqli_query($con, "SELECT * FROM product_qty_change WHERE change_date BETWEEN '$from' AND '$to' ORDER BY change_date ASC") or die(mysqli_error($con));
    if (mysqli_num_rows($sql) > 0) {

        while ($data = mysqli_fetch_array($sql)) {
            $sql2 = mysqli_query($con, "SELECT * FROM product WHERE product_id='" . $data["product_number"] . "'") or die(mysqli_error($con));
            $fetch2 = mysqli_fetch_array($sql2);
            $getname = $fetch2["product_name"];

            echo "<tr>
                 <td>" . $data["product_number"] . "</td>
                 <td>" . $getname . "</td>
                 <td>" . $data["former_qty"] . "</td>
                 <td>" . $data["new_qty"] . "</td>
                 <td>" . $data["total_qty"] . "</td>
                 <td>" . $data["change_date"] . "</td>
            </tr>";
        }
    } else {
        echo "<tr><h3>No Data Available..Please refine your search.</h3>";
    }
}

function generateSalesReport($con, $from, $to, $filterUser)
{
    $sql = mysqli_query($con, "SELECT * FROM sold_products WHERE order_date BETWEEN '$from' AND '$to' ") or die(mysqli_error($con));

    if ($filterUser != "all") {
        $sql = mysqli_query($con, "SELECT * FROM sold_products WHERE cashier='$filterUser' AND order_date BETWEEN '$from' AND '$to' ") or die(mysqli_error($con));
    }else{
        $sql = mysqli_query($con, "SELECT * FROM sold_products WHERE order_date BETWEEN '$from' AND '$to' ") or die(mysqli_error($con));

    }


    if (mysqli_num_rows($sql) > 0) {

        while ($data = mysqli_fetch_array($sql)) {

            echo "<tr>
            <td>" . $data["invoice_number"] . "</td>
            <td>" . $data["cashier"] . "</td>
            <td>" . $data["product_name"] . "</td>
            <td>" . $data["quantity"] . "</td>
            <td>" . $data["product_discount"] . "</td>
            <td>" . $data["sold_at_price"] . "</td>
            <td>" . $data["expected_sale_price"] . "</td>
            <td>" . $data["paid_amount"] . "</td>
            <td>" . $data["payment_type"] . "</td>
            <td>" . $data["cashpayment_amt"] . "</td>
            <td>" . $data["bankpayment_amt"] . "</td>
            <td>" . $data["payment_method"] . "</td>
            <td>" . $data["customer_name"] . " <br>" . $data["customer_phone"] . " <br> " . $data["customer_address"] . "</td>
       </tr>";
        }
    } else {
        echo "<tr><h3>No Data Available..Please refine your search.</h3>";
    }
}

function generateStockLevelReport($con,$from, $to)
{

    //get order date using order_number
    $sql2 =  mysqli_query($con, "SELECT order_number, COUNT(product_name) AS total_order FROM placed_orders WHERE order_date BETWEEN '$from' AND '$to' GROUP BY product_name");
    if (mysqli_num_rows($sql2) > 0) {

        while ($fetch2 = mysqli_fetch_array($sql2)) {
            $onumber = $fetch2["order_number"];

            $sql = mysqli_query($con, "SELECT * FROM product WHERE order_number='$onumber'") or die(mysqli_error($con));
            $fetch = mysqli_fetch_array($sql);

            $prod_id = $fetch["product_id"];

            $calQuantitySold = $fetch["quantity"] - $fetch["quantity_rem"];
            $calCostAmountExpected = $fetch["cost_price"] * $fetch["quantity_rem"];
            $calSalesAmountExpected = $fetch["product_price"] *  $fetch["quantity_rem"];

            $sql6 = mysqli_query($con, "SELECT product_id,SUM(sold_at_price) as total_price FROM sold_products WHERE product_id='$prod_id' AND  order_date GROUP BY product_id") or die(mysqli_error($con));
            $fetch6 = mysqli_fetch_array($sql6);

            //total sale price
            $totalPrice = $fetch6["total_price"];

            $profitMarginExpected =  $calSalesAmountExpected - $calCostAmountExpected;
            $profitMarginReceived = $totalPrice - $calCostAmountExpected;

            // <td>" . number_format($totalPrice) . "</td>
            // <td>" . number_format($profitMarginExpected) . "</td>
            // <td>" . number_format($profitMarginReceived) . "</td>
            // <td>" . number_format($profitMarginExpected - $profitMarginReceived) . "</td>
            //  <td>" . $calQuantitySold . "</td>
            //  <td>" . $fetch["quantity_rem"] . "</td>

            echo "<tr>
                 <td>" . $fetch["product_id"] . "</td>
                 <td>" . $fetch["product_name"] . "</td>
                 <td>" . $fetch["quantity"] . "</td>
                 <td>" . $fetch["cost_price"] . "</td>
                 <td>" . $fetch["product_price"] . "</td>
                 <td>" . number_format($calCostAmountExpected, 2) . "</td>
                 <td>" . number_format($calSalesAmountExpected, 2) . "</td>
                 <td>" . number_format($calSalesAmountExpected - $calCostAmountExpected, 2) . "</td>
               
            </tr>";
        }
    } else {
        echo "<tr><h3>No Data Available..Please refine your search.</h3>";
    }
}

function generateProfitMarginReport($con, $from, $to, $currency)
{
    $today = date("Y-m-d");
    $sql = mysqli_query($con, "SELECT COUNT(product_id),product_id,order_date,product_name,cashier,quantity,sold_at_price
    ,SUM(quantity) AS total_qty,SUM(sold_at_price) AS total_price FROM sold_products WHERE order_date  BETWEEN '$from' AND '$to'
    GROUP BY product_name ") or die(mysqli_error($con));
    if (mysqli_num_rows($sql) > 0) {

        while ($fetch = mysqli_fetch_array($sql)) {
            //get cost price
            $pid = $fetch["product_id"];
            $sql_cost =     mysqli_query($con, "SELECT * FROM product WHERE product_id='$pid'") or die(mysqli_error($con));
            $data = mysqli_fetch_array($sql_cost);
            $costPrice =  round(intval($data["cost_price"] * $fetch["total_qty"]));
            $sellingPrice =  round(intval($fetch["sold_at_price"] * $fetch["total_qty"]));

            echo "<tr>";
            echo "<td>" . $fetch["product_name"] . "</td>";
            echo "<td>" . $fetch["total_qty"] . "</td>";
            echo "<td>$currency" . number_format($sellingPrice, 2) . "</td>";
            echo "<td>$currency" . number_format($costPrice, 2) . "</td>";
            echo "<td>" . number_format($costPrice-$sellingPrice, 2)  . "</td></tr>";
        }
    } else {
        echo "<tr><h3>No Data Available..Please refine your search.</h3>";
    }
}


function backupDB()
{


    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "absaccounting";
    // Get connection object and set the charset
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    $conn->set_charset("utf8");

    // Get All Table Names From the Database
    $tables = array();
    $sql = "SHOW TABLES";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_row($result)) {
        $tables[] = $row[0];
    }

    $sqlScript = "";
    foreach ($tables as $table) {

        // Prepare SQLscript for creating table structure
        $query = "SHOW CREATE TABLE $table";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_row($result);

        $sqlScript .= "\n\n" . $row[1] . ";\n\n";

        $query = "SELECT * FROM $table";
        $result = mysqli_query($conn, $query);

        $columnCount = mysqli_num_fields($result);

        // Prepare SQLscript for dumping data for each table
        for ($i = 0; $i < $columnCount; $i++) {
            while ($row = mysqli_fetch_row($result)) {
                $sqlScript .= "INSERT INTO $table VALUES(";
                for ($j = 0; $j < $columnCount; $j++) {
                    $row[$j] = $row[$j];

                    if (isset($row[$j])) {
                        $sqlScript .= '"' . $row[$j] . '"';
                    } else {
                        $sqlScript .= '""';
                    }
                    if ($j < ($columnCount - 1)) {
                        $sqlScript .= ',';
                    }
                }
                $sqlScript .= ");\n";
            }
        }

        $sqlScript .= "\n";
    }

    if (!empty($sqlScript)) {
        // Save the SQL script to a backup file
        $dir = "/Applications/XAMPP/xamppfiles/htdocs/abs/backup";
        
        if (!file_exists($dir) && !is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $backup_file_name = $dbname . '_backup_' . date("Y-m-d") . "_" . time() . '.sql';
        $fileHandler = fopen($backup_file_name, 'w+');
        $number_of_lines = fwrite($fileHandler, $sqlScript);
        fclose($fileHandler);

        //write to file
        file_put_contents($dir . '/' . $backup_file_name, $sqlScript);

        // Download the SQL backup file to the browser
        exec('rm ' . $backup_file_name);
        if ($number_of_lines) {
            echo "<div class='alert alert-success'>
            <i class='fas fa-check-circle'></i> Database backup was successfull.</div>";
        }
    }
}



function restoreDatabaseTables($con, $filePath)
{

    // Temporary variable, used to store current query
    $templine = '';

    // Read in entire file
    $lines = file($filePath);

    $error = '';

    // Loop through each line
    foreach ($lines as $line) {
        // Skip it if it's a comment
        if (substr($line, 0, 2) == '--' || $line == '') {
            continue;
        }

        // Add this line to the current segment
        $templine .= $line;

        // If it has a semicolon at the end, it's the end of the query
        if (substr(trim($line), -1, 1) == ';') {
            // Perform the query
            if (!$con->query($templine)) {
                $error .= 'Error performing query "<b>' . $templine . '</b>": ' . $con->error . '<br /><br />';
            }

            // Reset temp variable to empty
            $templine = '';
        }
    }
    return !empty($error) ? $error : true;
}


function holdSalesReceipt($con, $store_id)
{
    //save invoice in held receipts
    $paytype = $_POST["paytype"];
    $paymethod = $_POST["paymethod"];
    $user_id = $_SESSION['user'];
    $customer_phone = $_POST["customer_phone"];
    $customer_name = $_POST["customer_name"];
    $today = date('Y-m-d h:i:s');
    $order_number = date('dy') . mt_rand(2500, 9500);

    foreach ($_SESSION["products"] as $product) {
        $product_name = $product["product_name"];
        $product_price = $product["product_price"];
        $product_number = $product["product_id"];
        $product_qty = $product["product_qty"];

        $sql_hold = mysqli_query($con, "INSERT INTO held_receipts 
        (store_id,cashier,customer_name,customer_phone,order_date,invoice_number,product_name,product_id,quantity,product_price,payment_type,payment_method) 
        VALUES('$store_id','$user_id','$customer_name','$customer_phone','$today','$order_number','$product_name','$product_number','$product_qty','$product_price','$paytype','$paymethod')") or die(mysqli_error($con));
    }

    if ($sql_hold) {
        echo "<div class='alert alert-success'> <b>RECEIPT NO: $order_number has been placed on hold. <b/> </div><br>";
        unset($_SESSION['products']);
        unset($_SESSION['cart_discounts']);
        unset($_SESSION["returns"]);
    }
}

function birthDayReminder($con)
{

    //get settings to retrieve when birthday alerts should start showing
    $setSql = "SELECT * FROM settings";
    $setQuery = mysqli_query($con, $setSql);
    $setRow = mysqli_fetch_array($setQuery);
    $bdayAlertDate = $setRow["bday_alert"];
    $alertDate = date("Y-m-d", strtotime("+" . $bdayAlertDate));

    //to begin showing alert, check if difference between now and 
    //birthday is = alert settings date
    $now = date("Y-m-d");
    $dateNow = new DateTime($now);
    $bdayAlertDate = new DateTime($alertDate);
    $interval = $dateNow->diff($bdayAlertDate);
    $countFrom = $interval->days;
    $countFrom = date("Y-m-d", strtotime("+" . $countFrom . "days"));

    $bdaySql = "SELECT * FROM customers WHERE cust_dob != '' AND cust_dob <= '$countFrom' LIMIT 3";
    $bdayQuery = mysqli_query($con, $bdaySql) or die(mysqli_error($con));

    if ($bdayQuery->num_rows) {

        while ($bdayRow = mysqli_fetch_array($bdayQuery)) {

            $cust_bdate = $bdayRow["cust_dob"];
            $cust_name = $bdayRow["cust_name"];

            //find difference between now and customer birth date
            $dateNow = new DateTime($now);
            $cust_bdate = new DateTime($cust_bdate);
            $bday_interval = $dateNow->diff($cust_bdate);
            $bday_interval = $bday_interval->format('%R%a');

            //get countdown in days
            $countdownTo = $bday_interval;

            //start showing alerts if difference between today and birthday == specified alert date
            $msg = "<div class='alert alert-success'>
						<i class='fas fa-birthday-cake'></i> Today is " . ucwords($cust_name) . " Birthday";

            if ($countdownTo == 0) {

                //show send SMS button if birthday remains a day or on the exact date
                if ($countdownTo <= 1) {
                    echo $msg . "<a href='#sendBdayMsgModal' data-toggle='modal'> 
                    <span class='badge' style='background-color: #4CAF50'>Send SMS</span></a>
                    </div>";
                } else {
                    echo $msg . "</div>";
                }
            } elseif (strpos($countdownTo, '-') !== false) {
                //date back dated
            } else {
                echo "<div class='alert alert-warning'>
							<i class='fas fa-thumbs-up'></i> " . ucwords($cust_name) . " is celebrating in  " . $countdownTo . "day(s)</div>";
            }
        }
    } else {
        echo "No Upcoming Birthdays";
    }
}

function productExpiryReminder($con)
{
    //get settings to retrieve when birthday alerts should start showing
    $getSql = "SELECT * FROM settings";
    $getQuery = mysqli_query($con, $getSql) or die(mysqli_error($con));
    $getRow = mysqli_fetch_array($getQuery);
    $pExpiryAlertDate = $getRow["expiration_alert"];
    $alertDate = date("Y-m-d", strtotime("+" . $pExpiryAlertDate));

    //to begin showing alert, check if difference between now and 
    //expiry is = alert settings date
    $now = date("Y-m-d");
    $dateNow = new DateTime($now);
    $pExpiryAlertDate = new DateTime($alertDate);
    $interval = $dateNow->diff($pExpiryAlertDate);
    $countFrom = $interval->days;
    $countFrom = date("Y-m-d", strtotime("+" . $countFrom . "days"));

    $expirySql = "SELECT * FROM product WHERE pexpiry_date != '' AND pexpiry_date <= '$countFrom' LIMIT 3";
    $expiryQuery = mysqli_query($con, $expirySql) or die(mysqli_error($con));

    if ($expiryQuery->num_rows) {

        while ($expiryRow = mysqli_fetch_array($expiryQuery)) {

            $pexipry_date = $expiryRow["pexpiry_date"];
            $pname = $expiryRow["product_name"];

            //find difference between now and customer birth date
            $dateNow = new DateTime($now);
            $pexipry_date = new DateTime($pexipry_date);
            $expiry_interval = $dateNow->diff($pexipry_date);
            $expiry_interval = $expiry_interval->format('%R%a');

            //get countdown in days
            $countdownTo = $expiry_interval;

            //start showing alerts if difference between today and birthday == specified alert date
            $msg = "<div class='alert alert-danger'>
						<i class='fas fa-ban'></i> The Product with " . ucwords($pname) . " has expired. Kindly discard, as this might pose a treat to human life or the environment";

            if ($countdownTo == 0) {

                echo $msg . "</div>";
            } elseif (strpos($countdownTo, '-') !== false) {
                //date back dated
                echo $msg . "</div>";
            } else {
                echo "<div class='alert alert-warning'>
							<i class='fas fa-thumbs-up'></i> " . ucwords($pname) . " will be expiring in  " . $countdownTo . " day(s)</div>";
            }
        }
    } else {
        echo "No Upcoming Product Expiration.";
    }
}

function customerLoyaltyConverter($days, $type)
{

    $convert = $days; // days you want to convert

    $years = $convert * 0.002738; // days / 365 days
    $floor_years = floor($years); // Remove all decimals

    $month = $convert * 0.032855; // I choose 30.5 for Month (30,31) ;)
    $month = floor($month); // Remove all decimals

    if ($type == "days") {
        return $convert . "day(s)";
    } elseif ($type == "months") {
        return $month . "month(s)";
    } elseif ($type == "years") {

        if ($years > 1) {
            return $floor_years . "yr+";
        } else {
            return $floor_years . "yr";
        }
    }
}


function updateEMSInfo($con)
{

    $stakehold = $_POST["stakehold"];
    $salary_pay_date = $_POST["salary_pay_date"];
    $lateness_fee = $_POST["lateness_fee"];
    $work_resumes = $_POST["work_resumes"];
    $work_closes = $_POST["work_closes"];

    $new_query = $con->query(
        "UPDATE 
        settings SET 
        stakehold = '$stakehold',
        salary_pay_date = '$salary_pay_date',
        lateness_fee='$lateness_fee',
        work_resumes_time='$work_resumes',
        work_closes_time='$work_closes'"
    );

    if ($new_query) {
        echo "<div class='alert alert-success'><i class='fas fa-ok-sign'></i> EMS Settings Updated!</div>";
    } else {
        echo "<div class='alert alert-danger'><i class='fas fa-remove-sign'></i> Oops!! An error occured. Please try again</div>";
    }
}


function generateStakeholdReport($con, $from, $to, $currency)
{

    //total salary reeceived
    $sql_ts = mysqli_query($con, "SELECT SUM(amount) as total FROM salary_stakeholds WHERE payment_date BETWEEN '$from' AND '$to'") or die(mysqli_error($con));
    $fetch_ts = mysqli_fetch_array($sql_ts);
    $total_stakehold = $fetch_ts["total"];

    echo "Total Stakehold Paid Out - " . $currency . $total_stakehold;
}

function generateEmployeeReport($con, $from, $to, $currency)
{

    $sql = mysqli_query($con, "SELECT * FROM users") or die(mysqli_error($con));

    if (mysqli_num_rows($sql) > 0) {

        while ($data = mysqli_fetch_array($sql)) {
            $username = $data["username"];

            //check total products sold and amount
            $sql2 = "SELECT quantity, paid_amount, SUM(paid_amount) as total_amount, SUM(quantity) as total_qty FROM sold_products WHERE cashier='$username' AND order_date BETWEEN '$from' AND '$to'";
            $result2 = $con->query($sql2);
            $row2 = $result2->fetch_array();
            $qty_sold = $row2["total_qty"];
            $total_amount = $row2["total_amount"];

            //total salary received
            $sql_lp = mysqli_query($con, "SELECT SUM(amount_paid) as total FROM salary_payments WHERE username='$username' AND payment_date BETWEEN '$from' AND '$to' GROUP BY username") or die(mysqli_error($con));
            $fetch_lp = mysqli_fetch_array($sql_lp);
            $total_salary = $fetch_lp["total"];

            //total salary reeceived
            $sql_ts = mysqli_query($con, "SELECT SUM(amount) as total FROM salary_stakeholds WHERE username='$username' AND payment_date BETWEEN '$from' AND '$to' GROUP BY username ") or die(mysqli_error($con));
            $fetch_ts = mysqli_fetch_array($sql_ts);
            $total_stakehold = $fetch_ts["total"];

            echo "<tr>";
            echo "<td>";
            if (empty($data["photo"])) {
                echo "<i class='fas fa-image'></i>";
            } else {
                echo  "<img class='img-thumbnail' src='" . $data["photo"] . "' width='70' style='height:70px'/>";
            }
            echo "</td>";
            echo "
                 <td>" . ucwords($username) . "</td>
                 <td>" . $data["full_name"] . "</td>
                 <td>" . $data["phone_number"] . "</td>
                 <td>" .  $currency . number_format($total_salary) . "</td>
                 <td>" .  $currency . number_format($total_stakehold) . "</td>";
            echo  "<td>";
            if (empty($qty_sold)) {
                echo 0;
            } else {
                echo $qty_sold;
            }
            echo "</td>";
            echo "<td>" .  $currency . number_format($total_amount) . "</td>
                 <td>" .   $data["employment_date"] . "</td>
                 
                 ";

            echo "</tr>";
        }
    } else {
        echo "<tr><h3>No Data Available..Please refine your search.</h3>";
    }
}

function generatePlacedOrdersReport($con, $from, $to, $category)
{

    $sql2 = mysqli_query($con, "SELECT * FROM placed_orders WHERE order_date BETWEEN '$from' AND '$to' ORDER BY id DESC") or die(mysqli_error($con));

    if (!empty($category) && $category == "received") {
        $sql2 = mysqli_query($con, "SELECT * FROM placed_orders WHERE order_date BETWEEN '$from' AND '$to' AND order_status='Received' ORDER BY id DESC") or die(mysqli_error($con));
    } elseif (!empty($category) && $category == "pending") {
        $sql2 = mysqli_query($con, "SELECT * FROM placed_orders WHERE order_date BETWEEN '$from' AND '$to' AND order_status!='Received' ORDER BY id DESC") or die(mysqli_error($con));
    }
    if (mysqli_num_rows($sql2) > 0) {

        while ($fetch2 = mysqli_fetch_array($sql2)) {

            echo "<tr>";
            echo "<td>" . $fetch2["order_number"] . "</td>";
            echo "<td>" . $fetch2["product_name"] . "</td>";
            echo "<td>" . $fetch2["quantity"] . "</td>";
            echo "<td>" . $fetch2["supplier_name"] . " <br> " . $fetch2["supplier_phone"] . "</td>";
            echo "<td>" . number_format($fetch2["totalAmount"], 2) . "</td>";
            echo "<td>" . $fetch2["order_date"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><h3>No Data Available..Please refine your search.</h3>";
    }
}



function generateSalaryReport($con, $from, $to, $currency)
{

    $sql = mysqli_query($con, "SELECT * FROM users") or die(mysqli_error($con));

    if (mysqli_num_rows($sql) > 0) {

        while ($data = mysqli_fetch_array($sql)) {

            $username = $data["username"];

            //total salary received
            $sql_lp = mysqli_query($con, "SELECT SUM(amount_paid) as total FROM salary_payments WHERE username='$username' AND payment_date BETWEEN '$from' AND '$to' GROUP BY username") or die(mysqli_error($con));
            $fetch_lp = mysqli_fetch_array($sql_lp);
            $total_salary = $fetch_lp["total"];

            //total salary reeceived
            $sql_ts = mysqli_query($con, "SELECT SUM(amount) as total FROM salary_stakeholds WHERE username='$username' AND payment_date BETWEEN '$from' AND '$to' GROUP BY username ") or die(mysqli_error($con));
            $fetch_ts = mysqli_fetch_array($sql_ts);
            $total_stakehold = $fetch_ts["total"];

            echo "<tr>";
            echo "<td>";
            if (empty($data["photo"])) {
                echo "<i class='fas fa-image'></i><br>";
            } else {
                echo  "<img class='img-thumbnail' src='" . $data["photo"] . "' width='150' style='height:150px'/><br>";
            }
            echo "</td>";

            echo "<td>";
            echo "<b> Account Name: </b>" . ucwords($data["bank_acc_name"]) . "<br>
            <b> Account Number: </b>" . $data["bank_acc_no"] . "<br>
            <b> Bank Name: </b>" . ucwords($data["bank_name"]) . "<br>
           <b> Monthly Salary: </b>" . $currency . number_format($data["salary"]) . "<br>
           <b> Total Salary Received: </b>" . $currency . number_format($total_salary) . "<br>
           <b> Total Stakehold Accumulated: </b>" . $currency . number_format($data["accumulated_stakehold"]) . "<br>
            <b> Total Stakehold Received: </b>" . $currency . number_format($total_stakehold) . "";
            echo "</td>
            </tr>";
        }
    } else {
        echo "<tr><h3>No Data Available..Please refine your search.</h3>";
    }
}

function generateAttendanceReport($con, $from, $to, $currency, $work_resumes, $work_closes, $lateness_fee)
{

    $sql = mysqli_query($con, "SELECT * FROM attendance WHERE clock_in_date BETWEEN '$from' AND '$to' GROUP BY username") or die(mysqli_error($con));

    if (mysqli_num_rows($sql) > 0) {

        $noOfEarlyClockOut = 0;
        $noOfLateClockOut = 0;

        while ($data = mysqli_fetch_array($sql)) {

            $username = $data["username"];


            $lateInQuery = mysqli_query($con, "SELECT * FROM attendance WHERE clock_in_time > '$work_resumes' AND username='$username'") or die(mysqli_error($con));
            $noOfLateClockIn = mysqli_num_rows($lateInQuery);

            $earlyInQuery = mysqli_query($con, "SELECT * FROM attendance WHERE clock_in_time <= '$work_resumes' AND username='$username'") or die(mysqli_error($con));
            $noOfEarlyClockIn = mysqli_num_rows($earlyInQuery);


            $earlyOutQuery = mysqli_query($con, "SELECT * FROM attendance WHERE clock_out_time <= '$work_closes' AND username='$username'") or die(mysqli_error($con));
            $earlyOutData = mysqli_fetch_array($earlyOutQuery);

            if (!empty($earlyOutData["clock_out_time"])) {
                $noOfEarlyClockOut = mysqli_num_rows($earlyOutQuery);

                $lateOutQuery = mysqli_query($con, "SELECT * FROM attendance WHERE clock_out_time > '$work_closes' AND username='$username'") or die(mysqli_error($con));
                $noOfLateClockOut = mysqli_num_rows($lateOutQuery);
            }



            //get user details
            $sqlData = mysqli_query($con, "SELECT * FROM users WHERE username='$username'") or die(mysqli_error($con));
            $userData = mysqli_fetch_array($sqlData);
            $staffSalary = $userData["salary"];



            $amountToBeDeducted = 0;
            $amountToReceive = $staffSalary;


            //if user came late more than thrice, deduct his/her salary for each time 
            //if greater than 3, get lateness fee and multiply by no of days - 3

            //calculate amt to be received
            if ($noOfLateClockIn > 3) {

                //calculate amt to be deducted
                $amountToBeDeducted = $data["current_lateness_fee"] * ($noOfLateClockIn - 3);
                $amountToReceive = $staffSalary - $amountToBeDeducted;
            }


            echo "<tr>";
            echo "<td>";
            if (empty($data["photo"])) {
                echo "<i class='fas fa-image'></i><br>";
            } else {
                echo  "<img class='img-thumbnail' src='" . $data["photo"] . "' width='150' style='height:150px'/><br>";
            }
            echo "</td>";

            echo "<td>";
            echo "<b> Full Name: </b>" . $userData["full_name"] . "<br>
            <b> Number of Early Clock In: </b>" . $noOfEarlyClockIn . "<br>
            <b> Number of Late Clock In: </b>" . $noOfLateClockIn . "<br>
            <b> Number of Early Clock Out: </b>" . $noOfEarlyClockOut . "<br>
            <b> Number of Late Clock Out: </b>" . $noOfLateClockOut . "<br>
            <b> Account Name: </b>" . ucwords($userData["bank_acc_name"]) . "<br>
            <b> Account Number: </b>" . $userData["bank_acc_no"] . "<br>
            <b> Bank Name: </b>" . ucwords($userData["bank_name"]) . "<br>
             <b> Monthly Salary: </b>" . $currency . number_format($staffSalary) . "<br>
             <b> Amount To be Deducted For Time Management: </b>" . $currency . number_format($amountToBeDeducted) . "<br>
            <b> Payment To Receive: </b>" . $currency . number_format($amountToReceive) . "<br>
           ";

            echo "</td>
            </tr>";
        }
    } else {
        echo "<tr><h3>No Data Available..Please refine your search.</h3>";
    }
}
