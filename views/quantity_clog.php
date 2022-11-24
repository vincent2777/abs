<?php
include "../partials/header.php";
include "../partials/sidebar.php"; ;

?>

<div class="row">
    <div class="col-md-12">

        <ol class="breadcrumb">
            <li><a href="dashboard">Home</a></li>
            <li class="active">Quantity Change Log</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="page-heading"> <i class="fas fa-th"></i> Product Quantity Change Log</div>
            </div> <!-- /panel-heading -->

            <div class="panel-body">
                <?php
                if (isset($_GET["invno"])) {

                    $invoice_id = $_GET["invno"];

                    $sql = mysqli_query($con, "DELETE FROM product_qty_change WHERE product_number='$invoice_id'");

                    if ($sql) {
                        echo "<div class='alert alert-success'>
                        <i class='fas fa-check-circle'></i> Log deleted successfully</div>";
                    } else {
                        echo "<div class='alert alert-danger'>
                        <i class='fas fa-info'></i>Oops! An error occured.. Please try again later.</div>";
                    }
                }

                ?>

                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#pclog').DataTable({
                            dom: 'lBfrtip',
                            "lengthMenu": [
                                [10, 25, 50, -1],
                                [10, 25, 50, "All"]
                            ]
                        });
                    });
                </script>
                <table class="table table-striped" id="pclog">
                    <thead>
                        <tr>
                            <th># Product Number</th>
                            <th>Previous Qty</th>
                            <th>New Qty</th>
                            <th>Total Qty after Change</th>
                            <th>Date</th>
                            <th></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = mysqli_query($con, "SELECT * FROM product_qty_change ORDER BY change_date DESC") or die(mysqli_error($con));
                        if (mysqli_num_rows($sql) > 0) {

                            while ($data = mysqli_fetch_array($sql)) {

                                echo "<tr>
                                      <td>" . $data["product_number"] . "</td>
                                      <td>" . $data["former_qty"] . "</td>
                                      <td>" . $data["new_qty"] . "</td>
                                      <td>" . $data["total_qty"] . "</td>
                                      <td>" . $data["change_date"] . "</td>";

                        ?>
                                <td>

                                    <button onclick="confirmDelete('<?php echo $data['invoice_number']; ?>')" class="btn customize-abs-btn" type="button">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                                </tr>

                        <?php  }
                        } else {
                            echo "<tr><h3>No Data Available..Please refine your search.</h3>";
                        } ?>

                    </tbody>
                </table>
                <!-- /table -->

            </div> 
        </div> 
    </div>
</div> 

<script type="text/javascript">
                function confirmDelete(inv_id) {

                    var x = window.confirm("You are about Deleting a Record. Do you wish to proceed?");

                    if (x == true) {
                        window.open("quantity_change_log?invno=" + inv_id, '_self');
                    }
                }
            </script>

<?php include "../partials/footer.php"; ?>