<?php
include "../partials/header.php";
include "../partials/sidebar.php";;
?>

<style>
    .checked {
  color: orange;
}
</style>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row bg-white">
            <div class="col-md-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="page-heading">
                            <h3 class="p-3">My Top Products</h3>
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
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Sold Out</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        $sql = "SELECT product_id,product_name,SUM(quantity) as total_qty,count(*) as total_count FROM sold_products GROUP BY product_id ORDER BY total_count DESC LIMIT 10";
                                        $result = $con->query($sql);
                                        while ($value = $result->fetch_assoc()) {
                                            $product_name = $value["product_name"];
                                            $product_id = $value["product_id"];
                                            $quantity = $value["total_qty"];
                                            $total_count = $value["total_count"]/2;
                                        ?>
                                            <tr>
                                                <td><?php echo $product_id; ?></td>
                                                <td><?php echo ucwords($product_name); ?></td>
                                                <td><?php echo $quantity; ?></td>
                                                <td>
                                                    <?php 
                                                        $star = 0;
                                                        while($star <= $total_count){

                                                    ?>
                                                    <span class="fa fa-star checked"></span>
                                                    <?php $star++; } ?>

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