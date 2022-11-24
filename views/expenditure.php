<?php include "../partials/header.php";
include "../partials/sidebar.php"; ?>

<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default rounded" style="background-color: white !important;">
                    <div class="panel-heading">
                        <div class="page-heading">
                            <h3 class="pl-4 pt-3 mt-0"> <i class="fas fa-edit"></i> Record Expenditure</h3>
                        </div>
                    </div>

                    <div class="panel-body p-4">


                        <?php
                        if (isset($_POST["saveBtn"])) {

                            $expenditure_desc = $_POST["expenditure_desc"];
                            $amount = $_POST["amount"];
                            $expenditure_date = $_POST["expenditure_date"];
                            $cashier = $_SESSION["user"];
                            $exp_refno = rand(10, 99) . rand(10, 99) . date('s');
                            $category = $_POST["exp_category"];
                            $store_id = $_SESSION["store_id"];

                            $new_query = $con->query(
                                "INSERT INTO 
                        expenditures (store_id,exp_category,exp_cashierid,exp_description,exp_amount,exp_date,exp_refno) 
                        VALUES('$store_id','$category','$cashier','$expenditure_desc','$amount','$expenditure_date','$exp_refno')"
                            );

                            if ($new_query) {
                                echo "<div class='alert alert-success'>Expenditure Added Successfully</div>";
                            } else {
                                echo "<div class='alert alert-danger'>Oops!! An error occured. Please try again</div>";
                            }
                        }

                        
            $do_action = $_GET["action"];
            $expense_id = $_GET["expense_id"];

            if ($do_action == "delete" && $expense_id != "") {

                $deleteSQL = mysqli_query($con, "DELETE FROM expenditures WHERE exp_refno='$expense_id' ") or die(mysqli_error($con));

                if ($deleteSQL) {
                    echo '<div class="alert alert-success" style="border-left: 5px solid green;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong><i class="fas fa-ok-sign"></i> Expenditure have been deleted Successfully</strong>
                </div>';
                }
            }

                        ?>

                        <div class="alert alert-info" style="border-left: 5px solid blue;">Use the form below to record all expenses made during the day.</div>
                        <div class="row">
                            <div class="col-md-4">

                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="">Category</label>
                                        <select name="exp_category" class="form-control" id="">
                                            <option selected>--Select Category--</option>
                                            <option value="Utilities">Utilities</option>
                                            <option value="Travel Costs">Travel Costs</option>
                                            <option value="Marketing">Marketing</option>
                                            <option value="Advertising">Advertising</option>
                                            <option value="Assets">Assets</option>
                                            <option value="Car and truck">Car and truck</option>
                                            <option value="Commissions and fees">Commissions and fees</option>
                                            <option value="Contract labor">Contract labor</option>
                                            <option value="Home office expenses">Home office expenses</option>
                                            <option value="Insurance">Insurance</option>
                                            <option value="Interest paid">Interest paid</option>
                                            <option value="Legal fees and professional services">Legal fees and professional services</option>
                                            <option value="Office expenses">Office expenses</option>
                                            <option value="Other business expenses">Other business expenses</option>
                                            <option value="Rent and lease">Rent and lease</option>
                                            <option value="Repairs and maintenance">Repairs and maintenance</option>
                                            <option value="Supplies">Supplies</option>
                                            <option value="Taxes and licenses">Taxes and licenses</option>
                                            <option value="Travel expenses">Travel expenses</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Expense Description</label>
                                        <textarea name="expenditure_desc" class="form-control" id="expenditure_desc" cols="30" rows="5"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Expense Amount</label>
                                        <input type="text" class="form-control" required id="amount" name="amount">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Date</label>
                                        <input type="text" autocomplete="off" class="form-control" id="expenditure_date" required name="expenditure_date">
                                    </div>

                                    <center>
                                        <button class=" customize-abs-btn" name="saveBtn" style="width: 75%;max-width:100%">Save</button>
                                    </center>

                                </form>
                            </div>

                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#expenses').DataTable({
                                        dom: 'lBfrtip',
                                        "lengthMenu": [
                                            [10, 25, 50, -1],
                                            [10, 25, 50, "All"]
                                        ]
                                    });

                                    $("#expenditure_date").datepicker({
                                        dateFormat: 'yy-mm-dd'
                                    });

                                });
                            </script>

                            <div class="col-md-8">
                                <div class="overflow-x:auto">
                                    <table class="table table-striped" id="expenses">

                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>User</th>
                                                <th>Description</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Options</th>
                                            </tr>
                                        </thead>


                                        <tbody>
                                            <?php

                                            $sql = "SELECT * FROM expenditures ORDER BY exp_date=CURDATE()";
                                            $result = $con->query($sql);
                                            while ($value = $result->fetch_assoc()) {
                                                $id = $value["id"];
                                                $exp_cashierid = $value["exp_cashierid"];
                                                $exp_description = $value["exp_description"];
                                                $exp_amount = $value["exp_amount"];
                                                $exp_date = $value["exp_date"];
                                                $exp_refno = $value["exp_refno"];
                                                $exp_category = $value["exp_category"];

                                            ?>
                                                <tr>
                                                    <td><?php echo strtoupper($exp_refno); ?></td>
                                                    <td><?php echo strtoupper($exp_cashierid); ?></td>
                                                    <td><?php echo ucwords($exp_description); ?></td>
                                                    <td><?php echo number_format($exp_amount, 2); ?></td>
                                                    <td><?php echo $exp_date; ?></td>
                                                    <td>
                                     
                                                        <a class="customize-abs-btn" onclick="confirmDelete('<?php echo $exp_refno; ?>')" href="#"><i class="mdi mdi-trash-can"></i></a>
                                                    </td>
                                                </tr>

                                            <?php  }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div> <!-- /panel-body -->
                </div> <!-- /panel -->
            </div> <!-- /col-md-12 -->
        </div>
    </div>



    <script type="text/javascript">
                function confirmDelete(expense_id) {

                    var x = window.confirm("You are about Deleting a Record. Do you wish to proceed?");

                    if (x == true) {
                        window.open("expenditure?action=delete&expense_id=" + expense_id, '_self');
                    }
                }
            </script>

    <?php include "../partials/footer.php"; ?>