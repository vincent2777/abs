$(document).ready(function(e) {

    $("#form").on('submit', (function(e) {
        e.preventDefault();
        window.scrollTo(0, document.body.scrollHeight);
        $.ajax({
            url: "../includes/inventoryUpload.php",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $("#loader").css("display", "inline-block");
            },
            success: function(data) {

                $("#loader").css("display", "none");
                if (data == 'invalid') {
                    alert("Invalid File:Please Upload and Excel or CSV File.");
                    window.location = "import_inventory";
                } else {
                    // view uploaded file.
                    alert("Inventory has been successfully Imported.");
                    window.location = "products";
                }
            },
            error: function(e) {
                alert(e);
            }
        });
    }));
});