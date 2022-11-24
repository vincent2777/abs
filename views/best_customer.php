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
                            <h3 class="p-3">My Best Customers</h3>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="row justify-content-center">

                            <div class="col-md-12">
                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('#best_customers').DataTable({
                                            dom: 'lBfrtip',
                                            "aaSorting": [],
                                            "lengthMenu": [
                                                [10, 25, 50, -1],
                                                [10, 25, 50, "All"]
                                            ]
                                        });
                                    });
                                </script>
                                <table class="table table-striped" id="best_customers">

                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Loyalty Period</th>
                                            <th>Products Purchased</th>
                                            <th>Most Purchased Products</th>
                                            <th>Amount</th>
                                            <th>Debt</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        $sql = "SELECT * FROM customers  LIMIT 20";
                                        $result = $con->query($sql);
                                        while ($value = $result->fetch_assoc()) {
                                            $cust_name = $value["cust_name"];
                                            $cust_id = $value["cust_id"];
                                            $reg_date = $value["reg_date"];
                                            $current_debt = $value["cust_owing"];

                                            //check how long the person has been a customer
                                            $now = date("Y-m-d");
                                            $dateNow = new DateTime($now);
                                            $reg_date = new DateTime($reg_date);
                                            $interval = $dateNow->diff($reg_date);
                                            $loyalty = $interval->days;

                                            //check total products purchased
                                            $sql2 = "SELECT quantity, paid_amount, SUM(paid_amount) as total_amount, SUM(quantity) as total_qty FROM sold_products WHERE customer_id='$cust_id' ORDER BY total_amount DESC";
                                            $result2 = $con->query($sql2);
                                            $row2 = $result2->fetch_array();
                                            $qty_purchased = $row2["total_qty"];
                                            $total_amount = $row2["total_amount"];

                                            //check most purchased products
                                            $most_purchased = array();
                                            $sql3 = "SELECT product_name, COUNT(*) count FROM sold_products WHERE customer_id='$cust_id' GROUP BY product_name, customer_id HAVING COUNT(*) > 0 LIMIT 3";
                                            $result3 = $con->query($sql3);
                                            while ($row3 = $result3->fetch_array()) {
                                                array_push($most_purchased, $row3["product_name"]);
                                            }

                                        ?>
                                            <tr>
                                                <td><?php echo $cust_name; ?></td>
                                                <td>
                                                    <?php

                                                    if ($_GET["convert_to"] == "months") {
                                                        echo customerLoyaltyConverter($loyalty, "months");
                                                    } elseif ($_GET["convert_to"] == "years") {
                                                        echo customerLoyaltyConverter($loyalty, "years");
                                                    } else {
                                                        echo customerLoyaltyConverter($loyalty, "days");
                                                    }

                                                    ?>

                                                    <a href="best_customer?convert_to=months">
                                                        <span class="badge bg-info text-white">To months</span>
                                                    </a>

                                                    <a href="best_customer?convert_to=years">
                                                        <span class="badge bg-secondary text-white">To years</span>
                                                    </a>
                                                </td>
                                                <td><?php echo $qty_purchased; ?></td>
                                                <td><?php echo implode("<br>", $most_purchased); ?></td>
                                                <td><?php echo $currency . number_format($total_amount, 2); ?></td>
                                                <td><?php echo  $currency . number_format($current_debt, 2); ?></td>
                                                <td>
                                                    <form action="customer_report" method="post" style="display: inline;">
                                                        <input type="hidden" name="customerid" value="<?php echo $cust_id; ?>">
                                                        <button class="btn customize-abs-btn p-2" name="reportBtn">
                                                            <i class="mdi mdi-chart-bar"></i></button>
                                                    </form>

                                                </td>

                                            </tr>

                                        <?php  }
                                        ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>

                    </div>

                    <br>
                    <br>

                </div> <!-- /col-md-12 -->
            </div> <!-- /row -->
        </div>
    </div>
    <script src="script/customer.js"></script>

    <?php include "../partials/footer.php"; ?>