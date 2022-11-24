<?php include "../partials/header.php";
include "../partials/sidebar.php"; ?>

<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">

        <div class="row bg-white p-4">
            <div class="col-md-8">
                <h3 class="ml-2">Create Store</h3>
            </div>


        </div>
        <hr>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#stores').DataTable({
                    dom: 'lBfrtip',
                    "aaSorting": [],
                    "lengthMenu": [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ]
                });
            });
        </script>
        <div class="row p-5 card bg-white rounded-0 shadow-sm">
            <div class="col-lg-12">
                <div class="panel panel-default">

                    <?php

                    $do_action = $_GET["action"];
                    $customer_id = $_GET["cust_id"];
                    $store_id = $_GET["store_id"];


                    //delete store
                    if ($do_action == "delete" && $store_id != "") {

                        $deleteSQL = mysqli_query($con, "DELETE FROM stores WHERE store_id='$store_id' ") or die(mysqli_error($con));

                        if ($deleteSQL) {
                            echo '<div class="alert alert-success" style="border-left: 5px solid green;">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <strong><i class="fas fa-ok-sign"></i> Store have been deleted Successfully</strong>
                                    </div>';
                        }
                    }

                    ?>



                    <?php
                    if ($_SESSION["role"] == "owner" || $_SESSION["role"] == "manager") {
                    ?>
                        <hr>
                        <div class="panel-heading">
                            <div class="page-heading"> <i class="fas fa-edit"></i> Stores</div>
                        </div>

                        <div class="panel-body">

                            <?php
                            if (isset($_POST["addStoreBtn"])) {

                                $store_name = ucwords($_POST["store_name"]);
                                $store_id = strtolower(trim($_POST["store_id"]));
                                $store_id  = preg_replace('/\s+/', '', $store_id);
                                $scategory = strtolower(trim($_POST["scategory"]));

                                $today = date("Y-m-d");

                                $new_query = $con->query(
                                    "INSERT INTO 
                                    stores (store_name,store_id,store_type) 
                                    VALUES('$store_name','$store_id','$scategory')"
                                );

                                if ($new_query) {
                                    echo "<div class='alert alert-success'>New Store Added</div>";
                                } else {
                                    echo "<div class='alert alert-danger'>Oops!! An error occured. Please try again</div>";
                                }
                            }

                            ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <br>
                                    <form action="" id="new_store" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="">Store Name</label>
                                            <input type="text" class="form-control" required id="store_name" name="store_name">
                                        </div>

                                        <div class="form-group">
                                            <label for="">Store ID</label>
                                          <span class="text-danger">
                                          (MUST be in small letters. DO NOT ADD Spaces)
                                          </span>
                                            <input type="text" class="form-control" required id="store_id" name="store_id">
                                        </div>

                                        <div class="form-group">
                                            <label for="">Category</label>
                                            <select name="scategory" id="" class="form-control">
                                                <option value="">-Select Category</option>
                                                <option value="store">Store</option>
                                            </select>
                                        </div>

                                        <center>

                                            <button class="customize-abs-btn" name="addStoreBtn" style="width: 100%;max-width:100%">Save</button>

                                        </center>
                                    </form>
                                </div>
                                <div class="col-md-7">

                                <style>
                                    #stores tr td{
                                        padding: 20px !important;
                                        margin: 20px !important;
                                    }
                                </style>
                                    <table class="table table-striped" id="stores">

                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            $sql = "SELECT * FROM stores";
                                            $result = $con->query($sql);
                                            while ($value = $result->fetch_assoc()) {
                                                $store_id = $value["store_id"];
                                                $store_name = $value["store_name"];
                                            ?>
                                                <tr>
                                                    <td><?php echo $store_id; ?></td>
                                                    <td><?php echo $store_name; ?></td>
                                                    <td>

                                                        <a class="customize-abs-btn" onclick="confirmStoreDelete('<?php echo $store_id; ?>')" href="#"><i class="mdi mdi-trash-can"></i></a>

                                                    </td>

                                                </tr>

                                            <?php  }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

        </div>
    </div>

    <?php include "../partials/footer.php"; ?>