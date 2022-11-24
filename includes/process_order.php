<?php
session_start();
include 'db_connect.php';
$paymethod = array();

if (isset($_POST["getTotal"])) {

	if (intval($_POST["cash_payment"]) > 0) {
		array_push($paymethod, "Cash");
	}

	if (intval($_POST["bank_payment"]) > 0) {
		array_push($paymethod, "Bank/Internet Transfer");
	}


	//if  return, get return amount
	$return_amt_balance = $_POST["return_amount"];
	$cash_payment_amt = $_POST["cash_payment"];
	$bank_payment_amt = $_POST["bank_payment"];
	$paytype = $_POST["paymethod"];
	$user_id = $_SESSION['user'];
	$customer_phone = $_POST["customer_phone"];
	$customer_name = $_POST["customer_name"];
	$customer_address = $_POST["customer_address"];
	$today = date('y/m/d');
	$order_number = "";
	$order_time = date("h:i:s");
	$sold_at_vat = $_POST["vat_amount"];
	$totalsale_discount = $_POST["totalsale_discount"];
	$paid_amount = $_POST["paid_amount"];
	$sub_total_payable = $_POST["sub_total_payable"];
	$balance_amount = $_POST["balance_amount"];
	$getTotal = $_POST["getTotal"];
	$getCustID = $_POST["customer_id"];
	$transaction_type = "";


	//get last ID from Sold products and add 1 to it
	$getIdSql = mysqli_query($con, "SELECT * FROM sold_products ORDER BY invoice_number DESC LIMIT 1") or die(mysqli_error($con));
	$getIdData = mysqli_fetch_array($getIdSql);
	$getLastID = $getIdData["invoice_number"];


	if (empty($getLastID)) {
		$order_number = 1;
	} else {
		$order_number = $getLastID + 1; //e.g 412

		//if last ID exists, run a check through the reverted receipts TBL and check if the id exist there
		//if it exist there, add +2 to the invoice number instead of +1

		$checkIdQuery = mysqli_query($con, "SELECT * FROM reverted_receipts WHERE invoice_number='$order_number'") or die(mysqli_error($con));
		$checkIdExist = mysqli_num_rows($checkIdQuery);

		if ($checkIdExist > 0) {
			//OOps!! that invoice exist
			//get the last ID in the  reverted receipts table
			$retrieveID = mysqli_fetch_array($checkIdQuery);
			$getRevertedLastID = $retrieveID["invoice_number"];

			$order_number = $getRevertedLastID + 1;
		}
	}

	//remove comma if only one payment type is selected
	if (sizeof($paymethod) > 1) {
		$paymethod = implode(", ", $paymethod);
	} else {
		$paymethod = implode("", $paymethod);
	}

	if (!empty($customer_name) && empty($getCustID)) {
		// we have a new customer
		//check if customer is in db
		$newID = mt_rand(100, 999) . date('s');
		$getCustID = $newID;
		$today = date('Y-m-d');

		$add = mysqli_query($con, "INSERT INTO customers (reg_date,cust_id,cust_name,cust_phone,cust_address,customer_type)
					VALUES('$today','$newID','$customer_name','$customer_phone','$customer_address','regular')");

		$queryLog = $con->query(
			"INSERT INTO 
		customer_credit_log (prev_amount,new_amount,cashier_id,change_date,customer_id) 
		VALUES('0','$limit','$current_user','$today','$cust_id')"
		);
	}

	if (intval($cash_payment_amt) == 0 && intval($bank_payment_amt) == 0) {
		$paid_amount = 0;
		$cash_payment_amt = 0;
		$paymethod = "Cash";
	}



	if (!empty($balance_amount)) {

		//check if client was given a change or client owes us a balance
		//compute change
		$newPayableChange = $paid_amount - $balance_amount; //5000- 1940
		$newPayableBal = $paid_amount + $balance_amount; //1000 + 2000 = 3000

		if ($total_to_pay == $newPayableChange) {
			// a change transaction
			// do not update database
			$total_to_pay = $newPayableChange;
			$paid_amount = $total_to_pay;
			$cash_payment_amt = $cash_payment_amt - $balance_amount;
			if ($bank_payment_amt > 0) {
				$bank_payment_amt = $bank_payment_amt - $balance_amount;
			}
			$transaction_type = "debit";
		}

		if ($total_to_pay > $paid_amount) {
			//a credit transaction
			//customer aacount will be charged
			$transaction_type = "credit";
			$total_to_pay = $total_to_pay;
			//update customer data for credit
			$getCreditSql = mysqli_query($con, "SELECT * FROM customers WHERE cust_id = '$getCustID'");
			$getCreditData = mysqli_fetch_array($getCreditSql);
			$getCredit = $getCreditData["cust_owing"];

			//check if there is a minus sign and remove it
			if (strpos($getCredit, '-') !== false) {
				$getCredit = str_replace("-", "", $getCredit);
			}

			$newCredit = $getCredit + $balance_amount;
			$sql_credit = mysqli_query($con, "UPDATE customers SET cust_owing='$newCredit' WHERE cust_id = '$getCustID'");
		}
	}

	$index = 0;


	if (isset($_SESSION["invoice_from_hold"])) {

		//IF invoice originated from held receipts, delete it
		$getHeldInvoiceNo = $_SESSION["invoice_from_hold"];

		$check_held = mysqli_query($con, "SELECT * FROM held_receipts WHERE invoice_number='$getHeldInvoiceNo'") or die(mysqli_error($con));

		if (mysqli_num_rows($check_held) > 0) {
			mysqli_query($con, "DELETE FROM held_receipts WHERE invoice_number='$getHeldInvoiceNo'") or die(mysqli_error($con));
		}
	}


	foreach ($_SESSION["products"] as $product) {

		$product_name = $product["product_name"];
		$product_price = $product["product_price"];
		$product_number = $product["product_id"];
		$product_qty = $product["product_qty"];
		$invoice_no = $product["invoice_number"];
		$product_discount = $product["product_discount"];
		$measurement_unit = $product["measurement_unit"];
		$qty_change= $product["qty_change"];

		$expected_sale_price = 0;
		//get expected price
		$ex_sql = mysqli_query($con, "SELECT * FROM product WHERE product_id='$product_number'") or die(mysqli_error($con));
		$exrow = mysqli_fetch_array($ex_sql);

		//check if product was varied from original unit before sales
		$sql4 = mysqli_query($con, "SELECT * FROM product WHERE product_id='$product_number' AND measurement_unit='$measurement_unit'") or die(mysqli_error($con));
		if(mysqli_num_rows($sql4) > 0){
			$expected_sale_price = $exrow["product_price"] * $product_qty;
		}else{
			$expected_sale_price = $product_price * $qty_change;
		}

		$new_sale_price = sprintf("%01.2f", ($product_price * $product_qty));
		$sold_at_price = $product_price - $product_discount;
		$discountPerItem = $product_discount;

		$store_id = $_SESSION["store_id"];
		$completionStatus = 0;

		if (!isset($_SESSION["returns"])) {

			if ($balance_amount == 0) {
				$sql = mysqli_query($con, "INSERT INTO sold_products 
						(measurement_unit,vat_amount,store_id,customer_type,cashpayment_amt,bankpayment_amt,cashier,customer_id,customer_name,customer_phone,customer_address,order_date,order_time,invoice_number,product_name,product_id,barcode_id,quantity,sold_at_price,expected_sale_price,total_amount,paid_amount,balance_amount,product_discount,payment_type,payment_method) 
						VALUES('$measurement_unit','$sold_at_vat','$store_id','regular','$cash_payment_amt','$bank_payment_amt','$user_id','$getCustID','$customer_name','$customer_phone','$customer_address','$today','$order_time','$order_number','$product_name','$product_number','$product_number','$product_qty','$sold_at_price','$expected_sale_price','$getTotal','$paid_amount','$balance_amount','$discountPerItem','Full Payment','$paymethod')") or die(mysqli_error($con));
				if ($sql) {
					$completionStatus = 1;
				}
				
			} else {

				$sql = mysqli_query($con, "INSERT INTO sold_products 
							(measurement_unit,vat_amount,store_id,customer_type,cashpayment_amt,bankpayment_amt,cashier,customer_id,customer_name,customer_phone,customer_address,order_date,order_time,invoice_number,product_name,product_id,barcode_id,quantity,sold_at_price,expected_sale_price,total_amount,paid_amount,balance_amount,product_discount,payment_type,payment_method) 
							VALUES('$measurement_unit','$sold_at_vat','$store_id','regular','$cash_payment_amt','$bank_payment_amt','$user_id','$getCustID','$customer_name','$customer_phone','$customer_address','$today','$order_time','$order_number','$product_name','$product_number','$product_number','$product_qty','$sold_at_price','$expected_sale_price','$getTotal','$paid_amount',0,'$discountPerItem','Part Payment','$paymethod')") or die(mysqli_error($con));
				if ($sql) {
					$completionStatus = 1;
				}
			}
		}
		//do conversion
		$product_qty = intval($product_qty);

		$update_productSql = mysqli_query($con, 
		"UPDATE product 
		SET quantity_rem=quantity_rem - '$product_qty',
		max_to_sell=max_to_sell- '$product_qty'
		WHERE product_id='$product_number'") or die(mysqli_error($con));
	}

	if ($update_productSql && $completionStatus = 1) {

		unset($_SESSION["products"]);
		unset($_SESSION["cart_discounts"]);
		unset($_SESSION["returns"]);
		die(json_encode(array("invoice" => $order_number, "msg" => "done")));

	} else {
		die(json_encode(array("status" => $completionStatus, "msg" => "error")));
	}
}
