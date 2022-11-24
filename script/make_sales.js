$(document).on("keypress", ".change_qty", function (e) {
	if (e.which == 13) {
		var qty = $(this).val();
		var code = $(this).attr("id");
		update_quantity(qty, code)
		return false;
	}
});

$(document).on("keypress", ".change_price", function (e) {
	if (e.which == 13) {
		var price = $(this).val();
		var code = $(this).attr("id");
		update_price(price, code)
		return false;
	}
});

$(document).on("keypress", ".change_price", function (e) {
	if (e.which == 13) {
		var price = $(this).val();
		var code = $(this).attr("id");
		update_price(price, code)
		return false;
	}
});

$(document).on("keypress", "#cash_payment", function (e) {
	if (e.which == 13) {
		$("#submitNow").click();
	}
});

$(document).on("keypress", "#bank_payment", function (e) {
	if (e.which == 13) {
		$("#submitNow").click();
	}
});


$(document).keypress(function (e) {
	var hasFocus = $('.change_qty').is(':focus');
	//listen for enter button
	if (e.which == 13) {
		if (!hasFocus) {
			$("#submitNow").click();
		}
	}

});
function isNumber(evt) {

	var charCode = (evt.which) ? evt.which : event.keyCode;
	if ((charCode < 48 || charCode > 57)) {
		return false;
	} else {
		return true;
	}

}

$(document).ready(function () {
	$("#barcode").on('input', function () {
		let qty = 1;
		addToCartFromBarcode(qty, this.value);
	})
})

//make an ajax request to get csutomer details
function retrieveCustomerInfo(name) {

	var search_results = document.getElementById("custsearch_results");
	search_results.style.display = 'block';
	search_results.innerHTML = "";

	$.ajax({
		url: '../includes/fetchCustomers.php',
		type: 'post',
		data: {
			query: name
		},
		dataType: 'json',
		success: function (response) {

			for (var i = 0; i < response.length; i++) {
				let data = response[i];

				if (!data.cust_name == "") {

					search_results.innerHTML += "<a id='" + data.cust_id + "' class='cust_search_list' href='#'>" + data.cust_name + "</a> <br>";

					$(".cust_search_list").click(function () {

						var getCustID = $(this).attr("id");
						$("#customer_name").val(this.innerHTML);
						search_results.style.display = 'none';

						//if customer is found and clicked, fetch other information
						$.ajax({
							url: '../includes/fetchCustomers.php',
							type: 'post',
							data: {
								custID: getCustID
							},
							dataType: 'json',
							success: function (res) {

								$("#customer_phone").val(res.cust_phone);
								$("#customer_address").val(res.cust_address);
								$("#customer_id").val(res.cust_id);
								$("#credit_limit").html("<h6><b>Credit Limit:</b> <?php echo $currency; ?>" + numberWithCommas(res.cust_credit_limit) + "</h6>");
								$("#customer_current_debt").val(res.cust_owing)

								//show clear debt button if customer owes
								var clearDebtModal = "";
								if (res.cust_owing > 0) {
									var clearDebtModal = "<button type='button' onclick='clearDebt(this.value,this.id)' value='" + res.cust_owing + "' id='" + res.cust_id + "' class='btn btn-sm btn-primary pl-2' data-target='#clearDebtModal' data-toggle='modal'>Clear Debt</button>" +
										"<input type='hidden' id='clearDebt_custname' value='" + res.cust_name + "' />";
								}

								$("#credit_owing").html("<h6><b>Current Debt:</b>  <?php echo $currency; ?>" + numberWithCommas(res.cust_owing) + "</h6>" + clearDebtModal);

								var climit = Number(res.cust_credit_limit);
								var debt = res.cust_owing; //string
								var current_debt = debt.replace("-", "");
								var bal = climit - Number(current_debt);
								$("#customer_balance").val(bal);
								$("#credit_balance").html("<h6><b>Balance:</b> <?php echo $currency; ?>" + numberWithCommas(bal) + "</h6>")
							}
						});

					});
				} else {
					search_results.innerHTML = "<small>No Customer Found</small> <span class='close-results' style='float:right'><button type='button' class='btn bg-danger text-white'>&times;</button></span>";

					$(".close-results").click(function () {
						search_results.style.display = 'none';
					});
				}
			}

		}
	})

}

function togglePayAmount() {
	selectPayMethod();
	var selected = document.getElementById("paymethod").value;
	if (selected == "Cash") {
		document.getElementById("cash_payment_holder").style.display = "inline-block";
	} else if (selected == "Bank/internet transfer") {
		document.getElementById("bank_payment_holder").style.display = "inline-block";
	}
}

function printAfterSales() {
	document.getElementById("printBtn").addEventListener('click', function () {
		if (window.print()) {
			window.open("checkout", "_self");
		}
	})
}





