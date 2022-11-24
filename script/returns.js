function loadReturnsAfterReload() {

    $.ajax({
        cache: false,
        url: "manage_returns_cart.php",
        type: "GET",
        dataType: "json",
        data: {
            "page_ready": "yes"
        }
    }).done(function(data) {

        var cartData = data.data
        var measurementsData = data.measurements
        updateReturnsTable(cartData, measurementsData)

    })
}


function removeReturnFromCart(id) {

    $.ajax({
        url: "manage_returns_cart.php",
        type: "GET",
        dataType: "json",
        data: { "remove_code": id }
    }).done(function (data) {

        var cartData = data.data
        var measurementsData = data.measurements
        updateReturnsTable(cartData, measurementsData);

    });

}

function updateReturnsTable(cartData, measurementsData) {

    var tr = "";
    var table = $("#return-cart-items");

    //empty the table
    $("#return-cart-items > tr").remove();

    //create rows for each item in the cart
    var subTotalAmount = 0;
    var totalAmount = 0;
    var amountPaid = 0;
    var discount = 0;
    var change = 0;

    for (let i = 0; i < cartData.length; i++) {

        const items = cartData[i];
        let pname = items.pname;
        let pprice = items.pprice;
        let pqty = items.pqty;
        let pnumber = items.pnumber;
        let pdiscount = items.pdiscount;
        let invoice_no = items.invno;
        amountPaid = parseInt(items.amountPaid);

        let productHasVariations = items.hasVariations;
        let subtotal = parseInt(pprice) * parseInt(pqty);
        let subtotalPayable = parseInt(pprice) * parseInt(pqty);

        if (pdiscount > 0) {
            subtotalPayable = parseInt(pprice) * parseInt(pqty);
        }

        discount += pdiscount
        subTotalAmount += subtotal;
        totalAmount = subTotalAmount
        change = amountPaid - totalAmount;

        tr = "<tr>"
            + "<input type='hidden' value='" + pprice.toFixed(2) + "' id='old_price" + pnumber + "'>"
            + "<input type='hidden' value='" + invoice_no + "' id='invoice_no' name='invoice_no'>"
            + "<td>" + pname + "</td>"
            + "<td>" + pprice + "</td>"
            + "<td>"
            + "<input type='number' onchange='updateReturnQuantity(this.value,this.id)' id='" + pnumber + "' class='form-control change_qty text-center change_qty"+ pnumber +"' style='width: 70px;' value='" + pqty + "'>"
            + "</td>"
            + "<td>" + subtotalPayable + "</td>";

        tr += "<td>"
            + "<button style='height: 25px;width: 30px;padding:2px' type='button' class='btn customize-abs-btn' onclick='removeReturnFromCart(this.value)' value='" + pnumber + "'>"
            + "<i class='mdi mdi-trash-can' style='font-size:12px'></i>"
            + "</button>"
            + "</td></tr>";

        table.append(tr);


    }


    //set total Amount
    $("#currency_holder_subtotal").text(numberWithCommas(amountPaid.toFixed(2)))
    $("#calcTotalPayable").text(numberWithCommas(totalAmount.toFixed(2)))
    $("#discountTotal").text(numberWithCommas(discount.toFixed(2)))
    $("#calcTotalChange").text(numberWithCommas(change.toFixed(2)))

    
    //set values of hidden input fields
    $("#price_paid").val(amountPaid)
    $("#new_price").val(totalAmount.toFixed(2));

}

function updateReturnQuantity(quantity, pcode) {

    if (quantity == 0 || quantity == "") {
        alert("Oops! You cannot sell less than Zero(0) of this product.");
        $(".change_qty"+pcode).val(1);
        quantity = 1;
    }

    $.ajax({
        url: "manage_returns_cart.php",
        type: "GET",
        dataType: "json",
        data: { "update_product_id": pcode, "quantity": quantity }

    }).done(function (data) {


        if (data.msg == "increment") {
            alert("Oops! You can only reduce the Quantity of a product in Sales Return. If you think this was an error, contact the System Admin.");
            $(".change_qty"+pcode).val(data.old_qty);

        } else {

            var cartData = data.data
            var measurementsData = data.measurements
            updateReturnsTable(cartData, measurementsData)

        }

    });



}

$(document).on("keypress", ".change_qty", function (e) {
	if (e.which == 13) {
		var qty = $(this).val();
		var code = $(this).attr("id");
		updateReturnQuantity(qty, code)
		return false;
	}
});

