<div class="modal fade" id="editSalesModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal" action="all_sales" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><i class="fa fa-edit"></i> Edit Sales Information</h4>
				</div>

				<div class="modal-body" style="margin-left:30px;margin-right:30px ">

					<div class="form-group">
						<label for="">Product Name</label>
						<input type="text" class="form-control" readonly id="modal_pname" name="modal_pname">
					</div>

					<div class="form-group">
						<label for="">Quantity</label>
						<input type="text" class="form-control" readonly id="modal_qty" name="modal_qty">
					</div>

					<input type="hidden" name="modal_invoice_id" id="modal_invoice_id" />

					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
								<label for="">Total Amount</label>
								<input type="text" class="form-control" readonly id="modal_total" autocomplete="off" name="modal_total">
							</div>
						</div>
						<div class="col-md-1">
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<label for="">Amount Paid</label>
								<input type="text" class="form-control" readonly id="modal_apaid" autocomplete="off" name="modal_apaid">
							</div>
						</div>
					</div>


					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
								<label for="">Amount to Balance</label>
								<input type="text" class="form-control" readonly id="modal_balance" autocomplete="off" name="modal_balance">
							</div>
						</div>
						<div class="col-md-1">
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<label for="">Previous Payment Method</label>
								<input type="text" class="form-control" readonly id="modal_paymethod" autocomplete="off" name="modal_paymethod">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="">New Payment Method</label>
						<select class="form-control" name="new_paymethod" id="new_paymethod" required="">
							<option value="" selected="">-Select Payment Method-</option>
							<option value="Cash">Cash</option>
							<option value="Bank/internet transfer">Bank/Internet Transfer</option>
						</select>
					</div>
					<div class="alert alert-info" style="border-left: 5px solid blue;">
						<b>Make Sure you have collected the complete balance before clicking on the Complete Payment Button</b>
					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">
						&times; Close</button>

					<button type="submit" class="btn customize-abs-btn" name="completeSaleBtn" id="completeSaleBtn" data-loading-text="Loading..."> <i class="fas fa-check-circle"></i> Complete Payment</button>
				</div> <!-- /modal-footer -->
			</form> <!-- /.form -->
		</div> <!-- /modal-content -->
	</div> <!-- /modal-dailog -->
</div>




<div class="modal fade" id="createRoleModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="ems_employees" method="post">
				<div class="modal-header">
					<h4 class="modal-title p-2"><i class="fa fa-plus"></i> Create Role</h4>

				</div>

				<div class="modal-body" style="max-height:450px; overflow:auto;">

					<h3>Please fill out the Information below</h3>

					<div class="form-group">
						<label for="rolename" class="col-sm-12 control-label">Name of Role: </label>
						<div class="col-sm-12">
							<input type="text" required="" class="form-control" id="rolename" placeholder="Role name" name="rolename">
						</div>
					</div> <!-- /form-group-->

					<div class="form-group">
						<label for="users_role" class="col-sm-12 control-label">Select Users to assign this new Role to</label>
						<div class="col-sm-12">
							<select class="form-control" required="" name="assign_to" id="assign_to">
								<option value="">~~SELECT~~</option>
								<?php

								$sql = mysqli_query($con, "SELECT * FROM users") or die(mysqli_error($con));
								while ($fetch = mysqli_fetch_array($sql)) {
									echo "<option value=" . $fetch["username"] . ">" . ucwords($fetch["username"]) . "</option>";
								}
								?>
							</select>
						</div>
					</div>

				</div> <!-- /modal-body -->

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fas fa-remove-sign"></i> Close</button>

					<button type="submit" class="btn customize-abs-btn" name="createRole" id="createRole" data-loading-text="Loading...">
						<i class="fas fa-ok-sign"></i> Submit</button>
				</div> <!-- /modal-footer -->
			</form> <!-- /.form -->
		</div> <!-- /modal-content -->
	</div> <!-- /modal-dailog -->
</div>


<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal" action="customers?do=update" method="POST">
				<h4 class="modal-title p-3"><i class="fa fa-plus"></i> Edit Customer Data</h4>

				<div class="modal-body" style="margin-left:30px;margin-right:30px ">

					<h3>Please fill out the Information below</h3>

					<div class="form-group">
						<label for="">Name</label>
						<input type="text" class="form-control" required id="modal_custname" name="modal_custname">
					</div>

					<input type="hidden" name="modal_customer_id" id="modal_customer_id" />

					<div class="form-group">
						<label for="">Address</label>
						<input type="text" class="form-control" id="modal_address" name="modal_address">
					</div>

					<div class="form-group">
						<label for="">Phone Number 1:</label>
						<input type="text" class="form-control" id="modal_phone1" name="modal_phone1">
					</div>

					<div class="form-group">
						<label for="">Category</label>
						<select name="modal_category" id="modal_category" class="form-control">
							<option>-Select Category</option>
							<option value="regular">Regular</option>

						</select>
					</div>

					<div class="form-group">
						<label for="">Birth Date</label>
						<input type="text" class="form-control" id="modal_dob" name="modal_dob">
					</div>
					<?php
					if ($_SESSION["role"] == "owner" || $_SESSION["role"] == "manager") {
					?>
						<div class="form-group">
							<label for="">Credit Limit</label>
							<input type="text" class="form-control" id="modal_climit" name="modal_climit">
						</div>

					<?php } ?>

					<input type="hidden" name="modal_prevamount" id="modal_prevamount">

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fas fa-remove-sign"></i> Close</button>

					<button type="submit" class="btn customize-abs-btn" name="submitForm" id="submitForm" data-loading-text="Loading..."> <i class="fas fa-ok-sign"></i> Save Changes</button>
				</div> <!-- /modal-footer -->
			</form> <!-- /.form -->
		</div> <!-- /modal-content -->
	</div> <!-- /modal-dailog -->
</div>


<div class="modal fade" id="payCreditModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<form class="form-horizontal" action="customers" method="POST">
				<h4 class="modal-title p-3"><i class="fas fa-credit-card"></i> Customer Payment Wizard</h4>


				<div class="modal-body" style="margin-left:30px;margin-right:30px ">

					<div id="img-loader">
						<center>
							<img src="<?php echo $pageUrl; ?>images/loading.gif" width="200" height="200" />
						</center>
					</div>

					<div id="payNowBody" style="display:none">

						<div class="form-group">
							<label for="">Name</label>
							<input type="text" class="form-control" required id="credit_custname" name="credit_custname">
						</div>

						<input type="hidden" name="credit_customer_id" id="credit_customer_id" />


						<div class="form-group">
							<label for="">Credit Amount</label>
							<input type="text" class="form-control" disabled id="credit_amount" name="credit_amount">
						</div>

						<div class="form-group">
							<label for="">Amount to pay</label>
							<input type="text" class="form-control" required id="credit_topay" name="credit_topay">
						</div>

						<div class="form-group">
							<label for="">Payment Method</label>
							<select name="credit_paymethod" id="credit_paymethod" required class="form-control">
								<option value="">-Select Payment Method</option>
								<option value="Cash">Cash</option>
								<option value="Bank/Internet Transfer">Bank/Internet Transfer</option>
							</select>
						</div>
					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fas fa-remove-sign"></i> Close</button>

					<button type="submit" onmouseover="
						if(document.getElementById('credit_topay').value==0){
							alert('Amount is too Small. Kindly Increase it.')
							}else if(document.getElementById('credit_topay').value > document.getElementById('credit_amount').value){
								alert('Amount is larger than the amount the Customer owes. Kindly Increase it.')}" class="btn" style="background-color: #0a011a;color:white" name="payNowBtn" id="payNowBtn" data-loading-text="Loading..."> <i class="fas fa-ok-sign"></i> Pay Now</button>
				</div> <!-- /modal-footer -->
			</form> <!-- /.form -->
		</div> <!-- /modal-content -->
	</div> <!-- /modal-dailog -->
</div>



<div class="modal fade" id="editExpensesModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal" action="expenditure?do=update" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><i class="fa fa-plus"></i> Edit Expenditure</h4>
				</div>

				<div class="modal-body" style="margin-left:30px;margin-right:30px ">

					<h3>Please fill out the Information below</h3>

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
						<input type="text" class="form-control" id="expenditure_date" required name="expenditure_date">
					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fas fa-remove-sign"></i> Close</button>

					<button type="submit" class="btn customize-abs-btn" name="submitForm" id="submitForm" data-loading-text="Loading..."> <i class="fas fa-ok-sign"></i> Save Changes</button>
				</div> <!-- /modal-footer -->
			</form> <!-- /.form -->
		</div> <!-- /modal-content -->
	</div> <!-- /modal-dailog -->
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="removeProductModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fas fa-trash"></i> Remove Product</h4>
			</div>
			<div class="modal-body">

				<div class="removeProductMessages"></div>

				<p>Do you really want to remove ?</p>
			</div>
			<div class="modal-footer removeProductFooter">
				<button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fas fa-remove-sign"></i> Close</button>
				<button type="button" class="btn customize-abs-btn" id="removeProductBtn" data-loading-text="Loading..."> <i class="fas fa-ok-sign"></i> Save changes</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<div class="modal fade" id="receiveDirectModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal" action="list_received_orders.php?do=process" method="post">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title"><i class="fa fa-plus"></i> Receive Direct Order</h4>
				</div>

				<div class="modal-body" style="max-height:450px; overflow:auto;">

					<div class="form-group">
						<label for="order_date" class="col-sm-12 control-label">Date: </label>
						<div class="col-sm-12">
							<input type="text" required class="form-control" autocomplete="off" id="order_date" placeholder="Select Date" name="orderDate">
						</div>
					</div>

					<div class="form-group">
						<label for="quantity" class="col-sm-12 control-label">Quantity ordered: </label>

						<div class="col-sm-12">
							<input type="text" required="" class="form-control" id="quantity" placeholder="Quantity" name="quantity">
						</div>
					</div>

					<div class="form-group">
						<label for="totalAmount" class="col-sm-12 control-label">Total Amount to be paid for order</label>
						<div class="col-sm-12">
							<input type="text" required="" class="form-control" id="totalAmount" name="totalAmount" placeholder="Amout to be Paid" />

						</div>
					</div>

					<input type="hidden" id="po_number" name="po_number">


				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fas fa-remove-sign"></i> Close</button>

					<button type="submit" class="btn customize-abs-btn" name="submitForm" id="submitForm" data-loading-text="Loading..."> <i class="fas fa-ok-sign"></i> Save Changes</button>
				</div> <!-- /modal-footer -->
			</form> <!-- /.form -->
		</div> <!-- /modal-content -->
	</div> <!-- /modal-dailog -->
</div>



<div class="modal fade" id="newOrderModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal" action="list_received_orders?do=neworder" method="post">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title"><i class="fa fa-plus"></i> New Order</h4>
				</div>

				<div class="modal-body" style="max-height:450px; overflow:auto;">
					<div class="form-group">
						<label for="order_date" class="col-sm-12 control-label">Date: </label>
						<div class="col-sm-12">
							<input type="text" required class="form-control" autocomplete="off" id="order_date_new" placeholder="Select Date" name="order_date_new">
						</div>
					</div>

					<div class="form-group">
						<label for="quantity" class="col-sm-12 control-label">Quantity: </label>

						<div class="col-sm-12">
							<input type="text" required="" class="form-control" id="quantity" placeholder="Quantity" name="quantity">
						</div>
					</div>

					<div class="form-group">
						<label for="quantity" class="col-sm-12 control-label">Supplier Name: </label>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="sup_name" placeholder="Name" name="sup_name">
						</div>
					</div>

					<div class="form-group">
						<label for="quantity" class="col-sm-12 control-label">Supplier Phone Number: </label>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="sup_phone" placeholder="Phone" name="sup_phone">
						</div>
					</div>

					<div class="form-group">
						<label for="totalAmount" class="col-sm-12 control-label">Total Amount to be paid for order</label>
						<div class="col-sm-12">
							<input type="text" required="" class="form-control" id="totalAmount" name="totalAmount" placeholder="Amout to be Paid" />

						</div>
					</div>

					<input type="hidden" id="order_number_new" name="order_number_new">
					<input type="hidden" id="product_name_new" name="product_name_new">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fas fa-remove-sign"></i> Close</button>

					<button type="submit" class="btn customize-abs-btn" name="submitNewOrder" id="submitNewOrder" data-loading-text="Loading..."> <i class="fas fa-ok-sign"></i> Save Changes</button>
				</div> <!-- /modal-footer -->
			</form> <!-- /.form -->
		</div> <!-- /modal-content -->
	</div> <!-- /modal-dailog -->
</div>



<div class="modal fade" tabindex="-1" role="dialog" id="aboutModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"> About ABS</h4>
			</div>
			<div class="modal-body">
				<div class="mx-auto justify-content-center">
					<center>
						<img src="<?php echo $pageUrl; ?>images/ABS-logo/logo.png" alt="" width="50" height="50">
						<h2>Artificial Intelligence Business Solution</h2>
						<h4>Version - 1.2 Revision 9</h4>
					</center>

					<p>ABS is a robust online point of sales and financial accounting solution with numerous features.
						The software was developed to aid businesses improve their customer delivery service during sales and eliminate alterations to the system which lead to losses and poor turnover.
						<br>
						<br>
						MD5 encryption algorithm mechanism was used to ensure massive security of the system online for end-to-end data encryption. It is also protected from SQL injection, XSS, and DDOS attacks using a CORE PHP encryption procedure created by our Software Development Team.
						<br>
						<center>
							<h3>For Windows, Mac OS, Linux operating Systems
							</h3>

							<p style="font-family: cambria;">ABS is a Trademark Software of &copy; Artificial Intelligence Technologies, Abuja, Nigeria. All Rights Reserved.</p>

						</center>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"> <i class="fas fa-remove-sign"></i> Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>