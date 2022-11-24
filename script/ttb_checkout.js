$(document).ready(function() {
    $('input[type="checkbox"]').click(function() {
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

$(document).ready(function() {

    $(".giveDiscountBtn").click(function() {

        var product_id = $(this).data('id');
        var elem_id = $(this).attr('id');

        if (elem_id == "" || Number(elem_id) == 0) {

            $.ajax({
                url: 'includes/fetchProductDiscount.php',
                type: 'post',
                data: {
                    prodID: product_id
                },
                dataType: 'json',
                success: function(response) {
                    $("#modal_pname").val(response.product_name);
                    $("#modal_discount").val(response.product_discount);
                    $("#modal_product_number").val(response.product_id);                    
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
                success: function(response) {
                    $("#modal_pname").val(response.product_name);
                    $("#modal_discount").val(elem_id);
                    $("#modal_product_number").val(response.product_id);
                }
            });
        }

    });

});

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function toggleInputs(value) {
    if (value == 'Part Payment') {
        document.getElementById('customer_info').style.display = 'inline-block';
        document.getElementById('part_payment_amount').style.display = 'inline-block';

    } else if (value == 'Full Payment') {

        document.getElementById('customer_info').style.display = 'none';
        document.getElementById('part_payment_amount').style.display = 'none';


    }
}