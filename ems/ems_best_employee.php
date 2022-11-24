<?php
include "../partials/header.php";
include "../partials/sidebar.php";

?>

<div class="main-panel">
    <div class="content-wrapper">
        <ol class="breadcrumb">
            <li><a href="../dashboard">Home/ </a></li>
            <li class="active">Best Employee(s)</li>
        </ol>
        <div class="row bg-white shadow-sm card p-3 rounded-0">
            <div class="col-md-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="page-heading"> <i class="fas fa-edit"></i> My Best Employees</div>
                    </div>

                    <div class="panel-body">

                        <div class="row justify-content-center">

                            <div class="col-md-12">

                                <table class="table table-striped" id="customers">

                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Loyalty Period</th>
                                            <th>Total Products Sold</th>
                                            <th>Three Most Sold Products</th>
                                            <th>Total Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        $sql = "SELECT * FROM users";
                                        $result = $con->query($sql);
                                        while ($value = $result->fetch_assoc()) {

                                            $username = $value["username"];
                                            $employment_date = $value["employment_date"];
                                            $full_name = $value["full_name"];

                                            //check how long the person has been a customer
                                            $now = date("Y-m-d");
                                            $dateNow = new DateTime($now);
                                            $employment_date = new DateTime($employment_date);
                                            $interval = $dateNow->diff($employment_date);
                                            $loyalty = $interval->days;

                                            //check total products purchased
                                            $sql2 = "SELECT quantity, paid_amount, SUM(paid_amount) as total_amount, SUM(quantity) as total_qty FROM sold_products WHERE cashier='$username'";
                                            $result2 = $con->query($sql2);
                                            $row2 = $result2->fetch_array();
                                            $qty_sold = $row2["total_qty"];
                                            $total_amount = $row2["total_amount"];

                                            //check most purchased products
                                            $most_sold = array();
                                            $sql3 = "SELECT product_name, COUNT(*) count FROM sold_products WHERE cashier='$username' GROUP BY product_name, cashier HAVING COUNT(*) > 0 LIMIT 3";
                                            $result3 = $con->query($sql3);
                                            while ($row3 = $result3->fetch_array()) {
                                                array_push($most_sold, $row3["product_name"]);
                                            }

                                        ?>
                                            <tr>
                                                <td><?php echo $full_name; ?></td>
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

                                                    <a href="ems_best_employee?convert_to=months">
                                                        <span class="badge text-white" style="background-color: teal;">To months</span>
                                                    </a>

                                                    <a href="ems_best_employee?convert_to=years">
                                                        <span class="badge text-white" style="background-color: black;">To years</span>
                                                    </a>
                                                </td>
                                                <td><?php if (empty($qty_sold)) {
                                                        echo 0;
                                                    } else {
                                                        echo $qty_sold;
                                                    } ?></td>
                                                <td><?php if (empty($most_sold)) {
                                                        echo "Nil";
                                                    } else {
                                                        echo implode("<br>", $most_sold);
                                                    } ?></td>
                                                <td><?php echo $currency . number_format($total_amount, 2); ?></td>
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
</div>
</div>
</div>
<?php require_once 'includes/footer.php'; ?>