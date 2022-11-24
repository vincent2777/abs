<!-- content-wrapper ends -->
<!-- partial:partials/_footer.html -->
<footer class="footer hidden-print">
  <div class="d-sm-flex justify-content-center justify-content-sm-between">
    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">(c) 2019 - 2021. All Rights Reserved. Business Solution Developed by <a target="_blank" href="https://aitechnologiesng.com">Artificial Intelligence Technologies</a>, Abuja.</span>
  </div>

</footer>
</div>
</div>
</div>


<script src="<?php echo $pageUrl; ?>vendors/chart.js/Chart.min.js"></script>

<script type="text/javascript" src="<?php echo $pageUrl; ?>DataTables/datatables.min.js"></script>
<script src="<?php echo $pageUrl; ?>assests/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo $pageUrl; ?>DataTables/Buttons-1.6.2/js/buttons.bootstrap4.min.js"></script>

<script type="text/javascript" src="<?php echo $pageUrl; ?>Datatables/Buttons-1.6.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="<?php echo $pageUrl; ?>Datatables/Buttons-1.6.2/js/dataTables.buttons.min.js"></script>

<script src="<?php echo $pageUrl; ?>js/settings.js"></script>
<script src="<?php echo $pageUrl; ?>js/dashboard.js"></script>
<script src="<?php echo $pageUrl; ?>js/Chart.roundedBarCharts.js"></script>
<script src="<?php echo $pageUrl; ?>script/make_sales.js"></script>
<script src="<?php echo $pageUrl; ?>script/customer.js"></script>

<script type="text/javascript" src="<?php echo $pageUrl; ?>script/functions.js"></script>
<script>
  function reprintPlacedOrder(loc) {
    window.open(loc, 'targetWindow',
      `toolbar=no,
      location=no,
      status=no,
      menubar=no,
      scrollbars=yes,
      resizable=yes,
      width=500,
      height=500`);
    return false;
  }
</script>

<script>
  $(document).ready(function() {

    $(".editCustomerBtn").click(function() {

      var user_id = $(this).data('id');

      $.ajax({
        url: '../includes/fetchCustomers.php',
        type: 'post',
        data: {
          custID: user_id
        },
        dataType: 'json',
        success: function(response) {
          $("#modal_custname").val(response.cust_name);
          $("#modal_address").val(response.cust_address);
          $("#modal_phone1").val(response.cust_phone);
          $("#modal_phone2").val(response.cust_phone2);
          $("#modal_dob").val(response.cust_dob);
          $("#modal_customer_id").val(response.id);
        }

      });

    });

    $('#list_all_products').DataTable();


  });
</script>
<script src="<?php echo $pageUrl; ?>script/birthday_msg.js"></script>

<script src="<?php echo $pageUrl; ?>script/checkout.js"></script>
<!-- End custom js for this page-->

<?php include "../includes/all_modals.php"; ?>

</body>

</html>