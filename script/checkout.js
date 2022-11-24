$(document).ready(function () {
    $('input[type="checkbox"]').click(function () {
        var inputValue = $(this).attr("value");
        $("#" + inputValue).toggle();
    });
});

function calcWithPartPayment(tp) {

    if (tp != "") {
        let partPaymentAmt = $("#part_payment_amount").val();
        let newPayable = tp - partPaymentAmt;
        return newPayable;
    }
}

function giveDiscountBtn(product_discount,product_id){

    
    if (product_discount == "" || Number(product_discount) == 0) {

        $.ajax({
            url: 'includes/fetchProductDiscount.php',
            type: 'post',
            data: {
                prodID: product_id
            },
            dataType: 'json',
            success: function (response) {

                $("#modal_pname").val(response.product_name);
                $("#modal_discount").val(response.product_discount);
                $("#modal_pnumber").val(response.product_id);

            }

        });

    } else {
        $.ajax({
            url: 'includes/fetchProductDiscount.php',
            type: 'post',
            data: {
                prodID: product_id
            },
            dataType: 'json',
            success: function (response) {
                $("#modal_pname").val(response.product_name);
                $("#modal_discount").val(product_discount);
                $("#modal_pnumber").val(response.product_id);

            }
        });
    }
}


function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function toggleInputs(value) {
    if (value == 'Part Payment') {
        document.getElementById('customer_info').style.display = 'inline-block';
        document.getElementById('part_payment_amount').style.display = 'inline-block';

    }
}


