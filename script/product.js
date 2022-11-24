$(document).ready(function(){
    $("#pexpiry_date").datepicker({
        dateFormat: 'yy-mm-dd'
    });
});

function getIDForEdit(value_id) {
    console.log(value_id)
    $.ajax({
        url: '../includes/fetchProductData.php',
        type: 'post',
        data: {
            prodID: value_id
        },
        dataType: 'json',
        success: function (response) {
            $("#modal_pname").val(response.product_name);
            $("#barcode").val(response.product_id),
            $("#modal_discount").val(response.product_discount);
            $("#modal_pnumber").val(response.product_id);
            $("#modal_sprice").val(response.product_price);
            $("#modal_cprice").val(response.cost_price);
            $("#old_qty").val(response.quantity);
            $("#modal_qty").val(response.quantity_rem);
            $("#modal_rlevel").val(response.reorder_level);
            $("#disable_sale").val(response.pvld_restrict_sales);
            $("#old_qty").val(response.quantity);
            $("#price_level_qty_above").val(response.price_level_qty_above);
            $("#price_level_qty_below").val(response.price_level_qty_below);
            $("#price_level_amount").val(response.price_level_amount);
            $("#pexpiry_date").val(response.pexpiry_date);

            if (response.max_to_sell != 0) {
                $("#modal_qtyto_sell").val(response.max_to_sell);
            } else {
                $("#modal_qtyto_sell").val(response.quantity_rem);
            }
        },
        error: function (err) {
            console.log(err);
        }


    });
}



function checkDiscount(value) {

    if (value > 100) {

        if (alert("Discount cannot be greater than 100%. Do this at your own detriment.")) {
            document.getElementById("update").style.display = 'none';

            alert("done");
        } else {
            document.getElementById("update").style.display = 'inline-block';
        }


    }
}

function printBarcode(barcode_id) {
    window.open("barcodes?code=" + barcode_id, "_blank");
}

function refreshAfterEdit() {
    $("#all-products").load(document.URL + " #all-products>*");
    $("#editProductModal").load(document.URL + " #editProductModal>*");
    $("#pexpiry_date").datepicker({
        dateFormat: 'yy-mm-dd'
    });
}

function submitEditProduct(event) {
    $("#waitmsg").html('<img src="../images/loading.gif" style="width: 40%"/>');

    setTimeout(function () {
        var formData = {
            modal_pnumber: $("#modal_pnumber").val(),
            modal_barcode: $("#barcode").val(),
            modal_sprice: $("#modal_sprice").val(),
            modal_cprice: $("#modal_cprice").val(),
            modal_discount: $("#modal_discount").val(),
            disable_sale: $("#disable_sale").val(),
            modal_pname: $("#modal_pname").val(),
            old_qty: $("#old_qty").val(),
            modal_qty: $("#modal_qty").val(),
            modal_rlevel: $("#modal_rlevel").val(),
            modal_qtyto_sell: $("#modal_qtyto_sell").val(),
            pexpiry_date: $("#pexpiry_date").val(),
            punit_qty: $("#punit_qty").val(),
            punit_price: $("#punit_price").val(),
            punit_id: $("#punit_id").val(),
            price_level_qty_above: $("#price_level_qty_above").val(),
            price_level_qty_below: $("#price_level_qty_below").val(),
            price_level_amount: $("#price_level_amount").val()

        };

        console.log(formData)

        $.ajax({
            type: "POST",
            url: "../includes/updateProductData.php",
            data: formData,
            dataType: "json",
            encode: true,
        }).done(function (data) {

            $("#product-holder").load(location.href + " #product-holder>*");
            $(".productEditDone").html(data)

            console.log(data)

        });

    }, 1000);

    setTimeout(function () {
        loadDataTable();
    }, 1500);

    event.preventDefault();
}


function deleteProduct(getId) {

    var x = window.confirm("You are about Deleting a Product from the Inventory. Do you wish to proceed?");
    var formData = {
        do_action: "delete",
        pid: getId,
    };
    if (x == true) {

        $.ajax({
            type: "POST",
            url: "../includes/deleteProduct.php",
            data: formData,
            dataType: "json",
            encode: true,
        }).done(function (data) {

            $("#product-holder").load(document.URL + " #product-holder>*");
            $(".product-delete-alert").html(data);
            $(".product-delete-alert").css("display", "block");

            setTimeout(function () {
                $(".product-delete-alert").css("display", "none");
                window.location.reload();
                loadDataTable();
            }, 1500);

        });

    } else {
        return false;
    }

}

function loadDataTable() {
    $('#product_table').DataTable({
        dom: 'lBfrtip',
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        stateSave: true

    });

    $("#pexpiry_date").datepicker({
        dateFormat: 'yy-mm-dd'
    });
}

window.addEventListener("load", function () {
    loadDataTable();
})


function addProductunit() {
    var unit_name = $("#unit_name").val();
    var unit_price = $("#unit_price").val();
    var unit_qty = $("#unit_qty").val();

    $("#addUnitBtn").text("Please wait..")

    if (unit_name == "" || unit_price == "" || unit_qty == "") {
        alert("Enter a Measurement Unit")
    } else {

        $.ajax({
            url: '../includes/addProductUnit.php',
            type: 'post',
            data: {
                unit_name: unit_name,
                unit_price: unit_price,
                unit_qty: unit_qty
            },
            success: function(response) {

                $("#addUnitMsg").html(response);
                $("#addUnitBtn").text("Submit")
                $("#addUnitBtn").css("display", 'none');

                // $('#closeUnitModal').click();

            },
            error: function(err) {
                console.log(err);
            }


        });
    }


}

function getProductUnitData(unit_id){

    //make ajax request and get the previous values
    $(".toggleUnitDataInput").css('display','inline-block');
    $("#punit_id").val(unit_id);

    $.ajax({
            url: '../includes/getProductUnitData.php',
            type: 'post',
            data: {
                unit_id: unit_id,
            },
            dataType: "json",
            encode: true,
            success: function(response) {

                console.log(response)

                $("#punit_price").val(response.measurement_price == "" || response.measurement_price == null ? '0' : response.measurement_price);
                $("#punit_qty").val(response.measurement_qty == "" || response.measurement_qty == null ? '1' : response.measurement_qty)
            },
            error: function(err) {
                console.log(err);
            }


        });
}