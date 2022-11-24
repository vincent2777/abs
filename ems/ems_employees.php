<?php
include "../partials/header.php";
include "../partials/sidebar.php";

?>

<style>
	label {
		font-weight: normal;
		font-family: 'Times New Roman', Times, serif;
	}
</style>

<div class="main-panel">
	<div class="content-wrapper">
		<ol class="breadcrumb">
			<li><a href="../dashboard">Home/ </a></li>
			<li class="active">All Employees</li>
		</ol>

		<div class="row bg-white shadow-sm card p-3 rounded-0">
			<div class="col-md-12">

				<?php

				if ($_GET["do"] == "delete" && $_GET["type"] == "user") {
					$user = $_GET["param"];
					$sqlu = mysqli_query($con, "DELETE FROM users WHERE id = '$user'") or die(mysqli_error($con));

					if ($sqlu) {
						echo "<div class='alert alert-success'>User has been deleted successfully</div>";
					} else {
						echo "<div class='alert alert-danger'>An error occured...please try again later.</div>";
					}
				}

				if ($_GET["do"] == "delete" && $_GET["type"] == "role") {
					$user = $_GET["param"];

					$sqlr = mysqli_query($con, "UPDATE users SET user_role = 'associate' WHERE id = '$user'") or die(mysqli_error($con));

					if ($sqlr) {
						echo "<div class='alert alert-success'>Role has been deleted successfully</div>";
					} else {
						echo "<div class='alert alert-danger'>An error occured...please try again later.</div>";
					}
				}

				if (isset($_POST["createRole"])) {

					$rolename = $_POST["rolename"];
					$assign_to = $_POST["assign_to"];

					$sqlcr = mysqli_query($con, "UPDATE users SET user_role = '$rolename' WHERE username = '$assign_to'") or die(mysqli_error($con));
					if ($sqlcr) {
						echo "<div class='alert alert-success'>New Role has been created</div>";
					} else {
						echo "<div class='alert alert-danger'>An error occured...please try again later.</div>";
					}
				}


				if (isset($_POST["createUserBtn"])) {

					$username = $_POST["username"];
					$fname = $_POST["fname"];
					$email = $_POST["email"];
					$phone = $_POST["phone"];
					$address = $_POST["address"];
					$rolename = $_POST["role"];
					$epassword = $_POST["epassword"];
					$salary = $_POST["salary"];
					$edob = $_POST["edob"];
					$store_id = $_POST["store"];
					$edate = $_POST["employment_date"];
					$sdate = $_POST["sack_date"];
					$accname = $_POST["accname"];
					$accno = $_POST["accno"];
					$accbname = $_POST["accbname"];

					if ($_FILES["photo"]["name"]) {

						$target_dir =  "employee_photos/";
						$target_file = $target_dir . basename($_FILES["photo"]["name"]);

						$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
						$fileTypes = array("png", "jpg", "jpeg");

						if (!in_array($imageFileType, $fileTypes)) {
							echo "Only png, jpg and jpeg photos are allowed";
						} else {
							move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
						}
					} else {
						$target_dir = "";
					}


					$sqlcu = mysqli_query($con, "INSERT INTO users (bank_acc_name,bank_acc_no,bank_name,employment_date,sack_date,photo,dob,salary,store_id,full_name,user_password,email,phone_number,address,user_role,username,login_status) 
    VALUES('$accname','$accno','$accbname','$edate','$sdate','$target_file','$edob','$salary','$store_id','$fname','$epassword','$email','$phone','$address','$rolename','$username',1)") or die(mysqli_error($con));
					if ($sqlcu) {
						echo "<div class='alert alert-success'>New User has been created</div>";
					} else {
						echo "<div class='alert alert-danger'>An error occured...please try again later.</div>";
					}
				}

				if (isset($_POST["updateUserBtn"])) {
					$username = $_POST["eusername"];
					$fname = $_POST["efname"];
					$email = $_POST["eemail"];
					$role = $_POST["erole"];
					$phone = $_POST["ephone"];
					$address = $_POST["eaddress"];
					$password = $_POST["epassword"];
					$userdb_id = $_POST["userdb_id"];
					$store_id = $_POST["store"];
					$salary = $_POST["salary"];
					$edob = $_POST["edob"];
					$edate = $_POST["employment_date"];
					$sdate = $_POST["sack_date"];
					$eaccname = $_POST["eaccname"];
					$eaccno = $_POST["eaccno"];
					$eaccbname = $_POST["eaccbname"];

					if ($_FILES["photo"]["name"]) {

						$target_dir =  "employee_photos/";
						$target_file = $target_dir . basename($_FILES["photo"]["name"]);

						$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
						$fileTypes = array("png", "jpg", "jpeg");

						if (!in_array($imageFileType, $fileTypes)) {
							echo "Only png, jpg and jpeg photos are allowed";
						} else {
							move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
						}
					} else {
						$target_dir = "";
					}

					$sql = mysqli_query($con, "UPDATE users SET 
			user_role = '$role',
			user_password = '$password',
			username = '$username',
			full_name = '$fname',
			phone_number = '$phone',
			address = '$address',
			email = '$email',
			store_id='$store_id',
			employment_date='$edate',
			sack_date='$sdate',
			salary='$salary',
			dob='$edob',
			photo = '$target_file',
			bank_acc_name = '$eaccname',
			bank_acc_no = '$eaccno',
			bank_name = '$eaccbname'
			WHERE id = '$userdb_id'") or die(mysqli_error($con));

					if ($sql) {
						echo "<div class='alert alert-success'>User Data has been Updated.</div>";
					} else {
						echo "<div class='alert alert-danger'>An error occured...please try again later.</div>";
					}
				}


				?>
				<div class="alert alert-info" style="border-left: 5px solid blue;" style="width: 50%;">
					<strong>NB: Please make sure you create a user before creating a new role</strong>
				</div>

				<div style="float:right;margin-right:85px">
					<button class="btn customize-abs-btn" data-toggle="modal" data-target="#createRoleModal">Create Roles</button>
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="page-heading">
							<i class="fas fa-edit"></i> System Roles
						</div>
					</div> <!-- /panel-heading -->
					<div class="panel-body">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Roles</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php

								$sql = mysqli_query($con, "SELECT id,user_role, COUNT(*) FROM users GROUP BY user_role") or die(mysqli_error($con));
								while ($fetch = mysqli_fetch_array($sql)) {

									echo "<tr>";
									echo "<td>" . ucwords($fetch["user_role"]) . "</td>";

								?>

									<td style="float:right;margin-right:80px">

										<button class='btn customize-abs-btn' type='button' onclick='confirmDeleteRole("<?php echo $fetch["id"]; ?>")'>
											<i class='fas fa-trash'></i></button>
									</td>

								<?php
								}
								?>
							</tbody>
						</table>
					</div>
				</div>


				<p>
					<?php
					$user_arr = [];
					$priviledge_arr = [];


					if (isset($_POST["updatePriviledge"])) {

						foreach ($_POST["selectedUsers"] as $user) {
							array_push($user_arr, $user);
						}

						foreach ($_POST["selectedPriviledges"]  as $selected) {
							array_push($priviledge_arr, $selected);
						}
						setPriviledge($connect, $user_arr, $priviledge_arr);
					}

					?>
				</p>

				<div style="float:right;margin-right:85px">
					<button class="btn btn-info mb-5 mt-5" data-toggle="modal" data-target="#createUserModal">Add Employee</button>
				</div>
				<div class="panel panel-default mt-5">
					<div class="panel-heading">
						<div class="page-heading mb-5"> <i class="fas fa-edit"></i> Employees/Users</div>
					</div> <!-- /panel-heading -->

					<div class="panel-body">

						<script type="text/javascript">
							$(document).ready(function() {
								$('#user_table').DataTable({
									dom: 'lBfrtip',
									"lengthMenu": [
										[10, 25, 50, -1],
										[10, 25, 50, "All"]
									]
								});

							});
						</script>

						<form method="post" action="">
							<div style="overflow-x: auto;">
								<table class="table table-striped" id="user_table">
									<thead>
										<tr>
											<th></th>
											<th>Username</th>
											<th>Full name</th>
											<th>Email</th>
											<th>Phone Number</th>
											<th>Address</th>
											<th>Store/Branch</th>
											<th>Role</th>
											<th>Salary</th>
											<th>Account Details</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$sql = mysqli_query($con, "SELECT * FROM users") or die(mysqli_error($con));
										while ($fetch = mysqli_fetch_array($sql)) {

											echo "<tr>";
											echo "<td><input type='checkbox' value='" . $fetch["id"] . "' name='selectedUsers[]' /></td>";
											echo "<td>" . $fetch["username"] . "</td>";
											echo "<td>" . $fetch["full_name"] . "</td>";
											echo "<td>" . $fetch["email"] . "</td>";
											echo "<td>" . $fetch["phone_number"] . "</td>";
											echo "<td>" . $fetch["address"] . "</td>";
											$store_id = $fetch['store_id'];

											$sql_get = $con->query("SELECT * FROM stores WHERE id='$store_id'");
											$get_row = mysqli_fetch_array($sql_get);
											$getStoreName = $get_row["store_name"];

											echo "<td>" . ucwords($getStoreName) . "</td>";
											echo "<td>" . $fetch["user_role"] . "</td>";
											echo "<td>" . $currency . number_format($fetch["salary"]) . "</td>";
											echo "<td>";
											if ($fetch["bank_acc_no"] != 0) {
												echo $fetch["bank_name"] . "<br>";
												echo $fetch["bank_acc_name"] . "<br>";
												echo $fetch["bank_acc_no"] . "<br>";
											} else {
												echo "No Data";
											}
											echo "</td>";
											echo "<td>
									<button class=\"btn btn-success\" type='button' onclick='editUser(this.id)' data-toggle=\"modal\" data-target=\"#editUserModal\" id=\"" . $fetch["id"] . "\"> 
									<i class='fas fa-edit'></i>
									</button>
								<button class='btn btn-danger' onclick='confirmDelete(\"" . $fetch["id"] . "\")'> <i class='fas fa-trash'></i></button>
								
								</td>";
											echo "</tr>";
										}
										?>

									</tbody>
								</table>
							</div>


							<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

							<!-- /table -->
							<div class="container-fluid">
								<div class="row" style="font-size: 18px;">
									<p style="font-size: 17px;"><i class="fas fa-check"></i> Select priviledges you wish to grant to an employee
										<br>
										<b class="text-danger" style="font-size: 17px;">NB: When a priviledge is unchecked, such priviledge will be disabled for that employee.</b>
									</p>
									<br>
									<div class="col-md-12">
										<div class="row ">
											<div class="col-md-5">
												<label class="form-check-label">
													<input type="checkbox" name="selectedPriviledges[]" value="cansell"> Can Sell
												</label>
												<br>
												<label class="form-check-label">
													<input type="checkbox" name="selectedPriviledges[]" value="editprice"> Can Edit Price
												</label>
												<br>
												<label class="form-check-label">
													<input type="checkbox" name="selectedPriviledges[]" value="inventory"> Can Record/View/Edit Inventory
												</label><br>
												<label class="form-check-label">
													<input type="checkbox" name="selectedPriviledges[]" value="printreport"> Can Print Report
												</label>
												<br>
												<label class="form-check-label">
													<input type="checkbox" name="selectedPriviledges[]" value="initiate_returns"> Can Initiate Returns/Reversal of receipts
												</label>
												<br>
												<label class="form-check-label">
													<input type="checkbox" name="selectedPriviledges[]" value="cangive_discount"> Can Give Discount
												</label>
												<br>
												<label class="form-check-label">
													<input type="checkbox" name="selectedPriviledges[]" value="canedit_qty"> Can Edit Quantity
												</label>
												<br>
												<label class="form-check-label">
													<input type="checkbox" name="selectedPriviledges[]" value="canreceive_direct_orders"> Can Receive Orders Directly
												</label>

											</div>

											<div class="col-md-3">

												<label class="form-check-label">
													<input type="checkbox" name="selectedPriviledges[]" value="create_users"> Can Access EM Portal
												</label>
												<br>
												<label class="form-check-label">
													<input type="checkbox" name="selectedPriviledges[]" value="canview_daily_sales"> Can View Daily Sales
												</label>
												<br>
												<label class="form-check-label">
													<input type="checkbox" name="selectedPriviledges[]" value="candoexpenses"> Can Perform/Record Expenses
												</label><br>

												<label class="form-check-label">
													<input type="checkbox" name="selectedPriviledges[]" value="canaccess_heldreceipts"> Can Access Held Receipts
												</label>
												<br>
												<label class="form-check-label">
													<input type="checkbox" name="selectedPriviledges[]" value="canview_total_sales"> Can View All Sales
												</label>
												<br>
												<label class="form-check-label">
													<input type="checkbox" name="selectedPriviledges[]" value="qty_log"> Can View Quantity Change Log
												</label>
												<br>
												<label class="form-check-label">
													<input type="checkbox" name="selectedPriviledges[]" value="view_products"> Can View Products
												</label><br>
												<label class="form-check-label">
													<input type="checkbox" name="selectedPriviledges[]" value="change_app_settings"> Can View/Edit App Settings
												</label>
												<br>
											</div>

											<div class="col-md-4">
												<label class="form-check-label">
													<input type="checkbox" name="selectedPriviledges[]" value="view_customers"> Can View Customers
												</label>
												<br>
												<label class="form-check-label">
													<input type="checkbox" name="selectedPriviledges[]" value="import_inventory"> Can Import Inventory
												</label>

											</div>
										</div>
									</div>

								</div>
								<center>
									<button type="button" class="btn btn-info btn-lg pb-4" style="margin-top: 5%;margin-bottom:2%" id="markall">Mark All Priviledges</button>
									<button type="submit" class="btn btn-success btn-lg pb-4" style="margin-top: 5%;margin-bottom:2%" name="updatePriviledge">Update</button>
								</center>
							</div>

						</form>

					</div>
				</div> <!-- /panel-body -->

			</div> <!-- /panel -->

		</div> <!-- /col-md-12 -->

	</div> <!-- /panel -->

</div> <!-- /col-md-12 -->
</div> <!-- /panel -->

</div> <!-- /col-md-12 -->

<div class="modal fade" id="createUserModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="ems_employees" method="post" enctype="multipart/form-data">
				<h4 class="modal-title p-3"><i class="fa fa-plus"></i> Create Employee</h4>


				<div class="modal-body" style="max-height:450px; overflow:auto;">

					<h3>Please fill out the Information below</h3>

					<div class="form-group">
						<label for="username" class="col-sm-12 control-label">Photocard: </label>
						<div class="col-sm-12">
							<input type="file" class="form-control" id="photo" name="photo">
						</div>
					</div> <!-- /form-group-->

					<div class="form-group">
						<label for="username" class="col-sm-12 control-label">Username: </label>
						<div class="col-sm-12">
							<input type="text" required="" autocomplete="off" class="form-control" id="username" placeholder="Username" name="username">
						</div>
					</div> <!-- /form-group-->

					<div class="form-group">
						<label for="password" class="col-sm-12 control-label">Password: </label>
						<div class="col-sm-12">
							<div class="row">
								<div class="col-md-9">
									<input type="password" required="" autocomplete="off" class="form-control" id="password" placeholder="Password" name="epassword">

								</div>
								<div class="col-md-3" style="left:0;margin:0">
									<button class="btn" type="button" id="togglePassword"><i class="fas fa-eye" id="pass-icon"></i></button>
								</div>
							</div>
						</div>
					</div> <!-- /form-group-->
					<div class="form-group">
						<label for="role" class="col-sm-12 control-label">Role: </label>
						<div class="col-sm-12">
							<select class="form-control" required="" name="role" id="role">
								<option value="">-Select Role-</option>
								<?php

								$sql = mysqli_query($con, "SELECT * FROM users GROUP BY user_role") or die(mysqli_error($con));
								while ($fetch = mysqli_fetch_array($sql)) {
									echo "<option value='" . $fetch["user_role"] . "'>" . ucwords($fetch["user_role"]) . "</option>";
								}
								?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="role" class="col-sm-12 control-label">Store/Branch </label>
						<div class="col-sm-12">
							<select class="form-control" required="" name="store" id="store">
								<option selected value="">-Select Store-</option>
								<?php

								$sql = mysqli_query($con, "SELECT * FROM stores") or die(mysqli_error($con));
								while ($fetch = mysqli_fetch_array($sql)) {
									echo "<option value='" . $fetch["id"] . "'>" . ucwords($fetch["store_name"]) . "</option>";
								}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="fname" class="col-sm-12 control-label">Fullname: </label>

						<div class="col-sm-12">
							<input type="text" required="" class="form-control" id="fname" placeholder="Fullname" name="fname">
						</div>
					</div> <!-- /form-group-->
					<div class="form-group">
						<label for="email" class="col-sm-12 control-label">Email: </label>

						<div class="col-sm-12">
							<input type="text" class="form-control" id="email" placeholder="Email" name="email">
						</div>
					</div> <!-- /form-group-->
					<div class="form-group">
						<label for="phone" class="col-sm-12 control-label">Phone Number: </label>

						<div class="col-sm-12">
							<input type="text" class="form-control" id="phone" placeholder="Phone Number" name="phone">
						</div>
					</div> <!-- /form-group-->
					<div class="form-group">
						<label for="address" class="col-sm-12 control-label">Address </label>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="address" placeholder="Address" name="address">
						</div>
					</div> <!-- /form-group-->

					<div class="form-group">
						<label for="salary" class="col-sm-12 control-label">Salary </label>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="salary" placeholder="Salary" name="salary">
						</div>
					</div>
					<div class="form-group">
						<label for="address" class="col-sm-12 control-label">Bank Name </label>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="accbname" placeholder="Bank" name="accbname">
						</div>
					</div> <!-- /form-group-->

					<div class="form-group">
						<label for="address" class="col-sm-12 control-label">Account Name </label>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="accname" placeholder="Name" name="accname">
						</div>
					</div> <!-- /form-group-->

					<div class="form-group">
						<label for="address" class="col-sm-12 control-label">Account Number </label>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="accno" placeholder="0055221.." name="accno">
						</div>
					</div> <!-- /form-group-->

					<div class="form-group">
						<label for="address" class="col-sm-12 control-label">Date of Birth </label>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="dob" placeholder="DOB" name="edob">
						</div>
					</div>

					<div class="form-group">
						<label for="address" class="col-sm-12 control-label">Employment Date </label>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="employment_date" placeholder="00/00/00" name="employment_date">
						</div>
					</div>

					<div class="form-group" style="margin-bottom: 40px">
						<label for="address" class="col-sm-12 control-label">Sack Date</label>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="sack_date" placeholder="00/00/00" name="sack_date">
						</div>
					</div>


				</div> <!-- /modal-body -->

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fas fa-remove-sign"></i> Close</button>

					<button type="submit" class="btn customize-abs-btn" name="createUserBtn" id="createUserBtn" data-loading-text="Loading...">
						<i class="fas fa-ok-sign"></i> Submit</button>
				</div> <!-- /modal-footer -->
			</form> <!-- /.form -->
		</div> <!-- /modal-content -->
	</div> <!-- /modal-dailog -->
</div>

<div class="modal fade" id="editUserModal">
	<div class="modal-dialog modal-dialog-lg">
		<div class="modal-content">
			<form class="form-horizontal" action="ems_employees" method="POST" enctype="multipart/form-data">

				<h4 class="modal-title p-3"><i class="fa fa-edit"></i> Edit Employee Information</h4>


				<div class="modal-body" style="max-height:450px; overflow:auto;">

					<h3>Please fill out the Information below</h3>

					<div class="form-group">

						<label for="username" class="col-sm-12 control-label">Photo </label>
						<div class="col-sm-12">
							<input type="file" class="form-control" id="photo" name="photo">
							<br>
							<img src="" id="employee_photo" alt="" width="180" height="200" style="display: none;">
						</div>
					</div> <!-- /form-group-->

					<div class="form-group">
						<label for="username" class="col-sm-12 control-label">Username: </label>
						<div class="col-sm-12">
							<input type="text" required="" class="form-control" id="eusername" placeholder="Username" name="eusername">
						</div>
					</div> <!-- /form-group-->

					<div class="form-group">
						<label for="epassword" class="col-sm-12 control-label">Password: </label>
						<div class="col-sm-12">
							<div class="row">
								<div class="col-md-9">
									<input type="password" required="" class="form-control" id="epassword" placeholder="Password" name="epassword">

								</div>
								<div class="col-md-3" style="left:0;margin:0">
									<button class="btn" type="button" id="etogglePassword"><i class="fas fa-eye" id="epass-icon"></i></button>
								</div>
							</div>
						</div>
					</div> <!-- /form-group-->

					<div class="form-group">
						<label for="role" class="col-sm-12 control-label">Role: </label>
						<div class="col-sm-12">
							<select class="form-control" name="erole" id="erole">
								<option value="">-Select Role-</option>
								<?php

								$sql = mysqli_query($con, "SELECT * FROM users GROUP by user_role") or die(mysqli_error($con));
								while ($fetch = mysqli_fetch_array($sql)) {
									echo "<option value='" . $fetch["user_role"] . "'>" . ucwords($fetch["user_role"]) . "</option>";
								}
								?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="role" class="col-sm-12 control-label">Store/Branch </label>
						<div class="col-sm-12">
							<select class="form-control" required="" name="store" id="estore">
								<option selected value="">-Select Store-</option>
								<?php

								$sql = mysqli_query($con, "SELECT * FROM stores") or die(mysqli_error($con));
								while ($fetch = mysqli_fetch_array($sql)) {
									echo "<option value='" . $fetch["id"] . "'>" . ucwords($fetch["store_name"]) . "</option>";
								}
								?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="fname" class="col-sm-12 control-label">Fullname: </label>

						<div class="col-sm-12">
							<input type="text" class="form-control" id="efname" placeholder="Fullname" name="efname">
						</div>
					</div> <!-- /form-group-->

					<div class="form-group">
						<label for="email" class="col-sm-12 control-label">Email: </label>

						<div class="col-sm-12">
							<input type="text" class="form-control" id="eemail" placeholder="Email" name="eemail">
						</div>
					</div> <!-- /form-group-->

					<input type="hidden" id="userdb_id" name="userdb_id">

					<div class="form-group">
						<label for="phone" class="col-sm-12 control-label">Phone Number: </label>

						<div class="col-sm-12">
							<input type="text" class="form-control" id="ephone" placeholder="Phone Number" name="ephone">
						</div>
					</div> <!-- /form-group-->

					<div class="form-group">
						<label for="address" class="col-sm-12 control-label">Address </label>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="eaddress" placeholder="Address" name="eaddress">
						</div>
					</div> <!-- /form-group-->

					<div class="form-group">
						<label for="address" class="col-sm-12 control-label">Salary </label>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="esalary" placeholder="Salary" name="salary">
						</div>
					</div> <!-- /form-group-->

					<div class="form-group">
						<label for="address" class="col-sm-12 control-label">Bank Name </label>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="eaccbname" placeholder="Bank" name="eaccbname">
						</div>
					</div> <!-- /form-group-->


					<div class="form-group">
						<label for="address" class="col-sm-12 control-label">Account Name </label>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="eaccname" placeholder="Name" name="eaccname">
						</div>
					</div> <!-- /form-group-->


					<div class="form-group">
						<label for="address" class="col-sm-12 control-label">Account Number </label>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="eaccno" placeholder="0055221.." name="eaccno">
						</div>
					</div> <!-- /form-group-->

					<div class="form-group">
						<label for="address" class="col-sm-12 control-label">Employment Date </label>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="eemployment_date" placeholder="00/00/00" name="employment_date">
						</div>
					</div>

					<div class="form-group">
						<label for="address" class="col-sm-12 control-label">Sack Date</label>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="esack_date" placeholder="00/00/00" name="sack_date">
						</div>
					</div>

					<div class="form-group">
						<label for="address" class="col-sm-12 control-label">Date of Birth </label>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="edob" placeholder="00/00/00" name="edob">
						</div>
					</div> <!-- /form-group-->

				</div> <!-- /modal-body -->

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fas fa-remove-sign"></i> Close</button>

					<button type="submit" class="btn customize-abs-btn" name="updateUserBtn" id="updateUserBtn" data-loading-text="Loading...">
						<i class="fas fa-ok-sign"></i> Update</button>
				</div> <!-- /modal-footer -->
			</form> <!-- /.form -->
		</div> <!-- /modal-content -->
	</div> <!-- /modal-dailog -->
</div>


<script>
	$(document).ready(function() {
		// order date picker
		$("#edob").datepicker({
			dateFormat: 'yy-mm-dd'
		});

		$("#dob").datepicker({
			dateFormat: 'yy-mm-dd'
		});

		$("#employment_date").datepicker({
			dateFormat: 'yy-mm-dd'
		});

		$("#sack_date").datepicker({
			dateFormat: 'yy-mm-dd'
		});

		$("#eemployment_date").datepicker({
			dateFormat: 'yy-mm-dd'
		});

		$("#esack_date").datepicker({
			dateFormat: 'yy-mm-dd'
		});

	});
</script>


<script src="<?php echo $pageUrl; ?>script/users.js"></script>

<?php require_once '../includes/footer.php'; ?>