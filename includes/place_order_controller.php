<?php

require_once 'admin_core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {

	$productName 		= $_POST['productName'];
	$quantity 			= $_POST['quantity'];
	$supplier_name 					= $_POST['supplierName'];
	$supplier_com_name 			= $_POST['supplierComName'];
	$supplier_phone 	= $_POST['supplierPhone'];
	$discount 	= $_POST['discount'];
	$totalAmount 			= $_POST['totalAmount'];
	$currentAmountPaid 	= $_POST['currentAmountPaid'];
	$paymentType 	= $_POST['paymentType'];
	$paymentStatus 	= $_POST['paymentStatus'];
	$order_date = date('Y-m-d', strtotime($_POST['orderDate']));


	$order_number = rand(2000, 10000);

	$sql = "INSERT INTO placed_orders (store_id,order_number, product_name, quantity, supplier_name, supplier_name, supplier_com_name, supplier_phone, discount, totalAmount,currentAmountPaid,paymentType,paymentStatus,order_date) 
VALUES('$store_id','$order_number','$product_name', '$quantity', '$supplier_name', '$supplier_name', '$supplier_com_name', '$supplier_phone', '$discount', '$totalAmount','$currentAmountPaid', '$paymentType', '$paymentStatus','$order_date')";

	if ($connect->query($sql) === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "Successfully Added";
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while adding the members";
	}

	$connect->close();

	echo json_encode($valid);
} // /if $_POST