
function loadExchangeAfterReload() {

    $.ajax({
        cache: false,
        url: "manage_exchange_cart.php",
        type: "GET",
        dataType: "json",
        data: {
            "page_ready": "yes"
        }
    }).done(function(data) {

        var cartData = data.data
        var measurementsData = data.measurements
        updateExchangeTable(cartData, measurementsData)

    })
}


function removeExchangeFromCart(id) {

    $.ajax({
        url: "manage_exchange_cart.php",
        type: "GET",
        dataType: "json",
        data: { "remove_code": id }
    }).done(function (data) {

        var cartData = data.data
        var measurementsData = data.measurements
        updateExchangeTable(cartData, measurementsData);

    });

}

function updateExchangeTable(cartData, measurementsData) {

    var tr = "";
    var table = $("#exchange-cart-items");

    //empty the table
    $("#exchange-cart-items > tr").remove();

    //create rows for each item in the cart
    var totalAmount = 0;
    var amountPaid = 0;
    var discount = 0;
    var change = 0;
    let cust_name = "";
    let cust_phone = "";
    let cust_address= "";

    for (let i = 0; i < cartData.length; i++) {

        const items = cartData[i];
        let pname = items.pname;
        let pprice = items.pprice;
        let pqty = items.pqty;
        let pnumber = items.pnumber;
        let pdiscount = items.pdiscount;
        let invoice_no = items.invno;
        let productHasVariations = items.hasVariations;

        cust_name = items.cust_name;
        cust_phone = items.cust_phone;
        cust_address = items.cust_address;

        amountPaid = parseInt(items.amountPaid);

        if (pdiscount > 0) {
            pprice = pprice + pdiscount;
        }

        let subtotalPayable = parseInt(pprice) * parseInt(pqty);

        if (pdiscount > 0) {
            subtotalPayable = parseInt(pprice) * parseInt(pqty);
        }

        discount += pdiscount
        totalAmount += subtotalPayable

        tr = "<tr>"
            + "<input type='hidden' value='" + pprice.toFixed(2) + "' id='old_price" + pnumber + "'>"
            + "<input type='hidden' value='" + invoice_no + "' id='invoice_no' name='invoice_no'>"
            + "<td>" + pname + "</td>"
            + "<td>" + pprice + "</td>"
            + "<td>"
            + "<input type='number' onchange='updateExchangeQuantity(this.value,this.id)' id='" + pnumber + "' class='form-control change_qty text-center change_qty"+ pnumber +"' style='width: 70px;' value='" + pqty + "'>"
            + "</td>"
            + "<td>" + subtotalPayable + "</td>";
            if (productHasVariations == 0) {

                tr += "<td></td>";
    
            } else {
    
                tr += "<td><select name='item_unit' id='" + pnumber + "' onchange='getProductMeasurementData(this.id,this.value)' class='form-control form-control-sm m-1'>"
                    + "<option selected></option>";
                for (let j = 0; j < measurementsData.length; j++) {
                    const item = measurementsData[j];
                    if (pnumber == item.product_id) {
                        tr += "<option value='" + item.measurement_id + "'>" + item.measurement_unit + "</option>";
                    }
                }
                tr += "</select></td>";
    
            }
    
        tr += "<td>"
            + "<button style='height: 25px;width: 30px;padding:2px' type='button' class='btn customize-abs-btn' onclick='removeExchangeFromCart(this.value)' value='" + pnumber + "'>"
            + "<i class='mdi mdi-trash-can' style='font-size:12px'></i>"
            + "</button>"
            + "</td></tr>";

        table.append(tr);


    }

    totalAmount = totalAmount - discount;
    change = amountPaid - totalAmount;

    //set total Amount
    $("#currency_holder_subtotal").text(numberWithCommas(amountPaid.toFixed(2)))
    $("#calcTotalPayable").text(numberWithCommas(totalAmount.toFixed(2)))
    $("#discountTotal").text(numberWithCommas(discount.toFixed(2)))

    var change_or_balance = change.toString().replace('-','');
    change_or_balance = parseInt(change_or_balance);
    $("#calcTotalChange").text(numberWithCommas(change_or_balance.toFixed(2)))

    
    //set values of hidden input fields
    $("#price_paid").val(amountPaid)
    $("#new_price").val(totalAmount.toFixed(2));

    $("#customer_name").val(cust_name);
    $("#customer_phone").val(cust_phone);
    $("#customer_address").val(cust_address);

    if (amountPaid < totalAmount) {
        document.getElementById("change_or_balance").innerText = "Balance";

    } else {
        //system owing
        document.getElementById("change_or_balance").innerText = "Change";
    }


}

function updateExchangeQuantity(quantity, pcode) {

    if (quantity == 0 || quantity == "") {
        alert("Oops! You cannot sell less than Zero(0) of this product.");
        $(".change_qty"+pcode).val(1);
        quantity = 1;
    }

    $.ajax({
        url: "manage_exchange_cart.php",
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
            updateExchangeTable(cartData, measurementsData)

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

function addExchangeItemsToCart(product_qty, product_id) {

    $("#submitNow").prop('disabled', true);

    $.ajax({
        cache: false,
        url: "manage_exchange_cart.php",
        type: "GET",
        dataType: "json",
        data: { "product_qty": product_qty, "product_id": product_id }
    }).done(function (data) {

        //set focus for barcode input
        $("#barcode").val("");
        $("#barcode").focus();
        $("#submitNow").prop('disabled', false);


        if (data.products == "low_store_stock") {
            alert("Oops! The product you're trying to sell is finished. Please restock.");
        } else if (data.products == "low_store_qty") {
            alert("Oops! The Quantity you are trying to sell is higher than the Quantity remaining in your Store. Please Restock");
        } else if (data.products == "onhold") {
            $("#submitNow").prop('disabled', false);

            alert("Sorry, this Product is ONHOLD and cannot be sold at this time. Contact the System Administrator for more details.");
        } else if (data.products == "lowqty") {
            $("#submitNow").prop('disabled', false);

            alert("Oops! Quantity is low and as such, this product cannot be sold. Contact the System Administrator for more details.");
        } else if (data.products == "reachedmax") {
            $("#submitNow").prop('disabled', false);

            alert("Oops! You cannot sell More than " + data.message + " of this product. If you think this was an error, Contact the System Administrator for more details.");
        } else if (data.products == "highqty") {
            $("#submitNow").prop('disabled', false);

            alert("CAUTION! The Quantity you are trying to sell is higher than the Quantity remaining. If you think this was an error, Contact the System Administrator for more details.");
        } else {

            var cartData = data.data
            var measurementsData = data.measurements
            updateExchangeTable(cartData, measurementsData)

        }


    })
}


function getProductMeasurementData(productID, measurementID) {

    $.ajax({
        cache: false,
        url: "manage_exchange_cart.php",
        type: "GET",
        dataType: "json",
        data: { "measurementID": measurementID, "productID": productID }
    }).done(function (data) {

        var cartData = data.data
        var measurementsData = data.measurements
        updateExchangeTable(cartData, measurementsData)

    })
}
