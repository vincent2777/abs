function reprintCreditBal(loc) {
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

  function confirmDelete(expense_id) {

    var x = window.confirm("You are about Deleting a Record. Do you wish to proceed?");

    if (x == true) {
      window.open("expenditure?action=delete&expense_id=" + expense_id, '_self');
    }
  }