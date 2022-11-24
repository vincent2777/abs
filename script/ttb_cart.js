$(document).ready(function () {

	// update product quantity in cart
	$(".quantity").change(function () {
		var element = this;

		if (this.value < 1) {
			alert("You cant sell a product at Zero quantity!!");
			this.value = 1;
		} else {

			update_quantity.call(element);
		}
	});
	
	function update_quantity() {
		var pcode = $(this).attr("data-code");
		var quantity = $(this).val();
		$.getJSON("manage_sc_cart.php", { "update_quantity": pcode, "quantity": quantity }, function (data) {
			$(".cart-alert-update").css("display", "block");
			setTimeout(function () {
				$(".cart-alert-update").css("display", "none");
				window.location.reload();
			}, 2000)

		});
	}

	// add item to stock control cart
$(".stock-transfer-form").submit(function (e) {
	var form_data = $(this).serialize();
	var button_content = $(this).find('button[type=submit]');
	button_content.html('Adding...');

	$.ajax({
		url: "manage_sc_cart.php",
		type: "POST",
		dataType: "json",
		data: form_data
	}).done(function (data) {


		if (data.products == "onhold") {
			alert("Sorry, this Product is ONHOLD and cannot be sold at this time. Contact the System Administrator for more details.");
		} else if (data.products == "lowqty") {
			alert("Oops! Quantity is low and as such, this product cannot be transferred. Contact the System Administrator for more details.");
		} else if (data.products == "highqty") {
			alert("CAUTION! The Quantity you are trying to transfer is higher than the Quantity remaining. If you think this was an error, Contact the System Administrator for more details.");

		} else {

			$("#cart-container").html(data.products);
			$(".cart-alert-add").css("display", "block");

			setTimeout(function () {
				$(".cart-alert-add").css("display", "none");
			}, 2000);
		}


	})
	e.preventDefault();
});


	//Remove items from cart
	$("#shopping-cart-results").on('click', 'a.remove-item', function (e) {
		e.preventDefault();
		var pcode = $(this).attr("data-code");
		$(this).parent().parent().fadeOut();
		$.getJSON("manage_sc_cart.php", { "remove_code": pcode }, function (data) {
			$("#cart-container").html(data.products);
			window.location.reload();
		});
	});
});