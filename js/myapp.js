jQuery( function() {

  jQuery( "#datepicker" ).datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd'
  });

  jQuery('#remit-table').DataTable();
  jQuery('#beneficiary-table').DataTable();

// when typing the amount
  // jQuery('#base_currency').on('change',function(){
  //   var amount = jQuery('#amount').val();
  //   var base_currency =  jQuery('#sending_currency').val();
  //
  //   jQuery('#total_amount').val(amount);
  //
  //   var send_data = {
  //     'action': 'convertrates',
  //     'amount': amount,
  //     'base_currency': base_currency,
  //   };
  //   jQuery.post(ajaxURL, send_data, function(result) {
  //     var res = JSON.parse(result);
  //     jQuery('#converted_amount').val(res.amount);
  //   });
	// });

  jQuery('#check_amount').on('click',function(e){
    e.preventDefault();
    var amount = jQuery('#amount').val().match(/^\d+$/) ? jQuery('#amount').val() : false;
    var data = {
      'action':'convertRates',
      'amount' : jQuery('#amount').val(),
      'currency' : jQuery('#sending_currency').val(),
    };
    jQuery.post(ajaxURL, data, function(resp) {
      console.log(resp);
      jQuery('#total_amount').val(amount);
      jQuery('#rec_amount').val(resp);
    });
  });

  jQuery('#confirm_password').on('keyup',function(){
    var password = jQuery('#password').val();
    if(jQuery('#confirm_password').val() == password){
      jQuery('#error').html('');
      jQuery('#error').html('<span style="color:green">Password Matched</span>');
    } else {
      jQuery('#error').html('');
      jQuery('#error').html('<span style="color:red">Password Doesnt Match</span>');
    }

  });

  jQuery('a[id^="modal-"').on('click',function(){
    var transaction_id = jQuery(this).data('tid');
    console.log(transaction_id);
    var send_data = {
      'action': 'getdata',
      'tid': transaction_id,
    };
    jQuery.post(ajaxURL, send_data, function(result) {
      var res = JSON.parse(result);
      var status = '';
      console.log(res[0]);
      if(res.status=='pr'){
        status = 'Processed';
      } else {
        status = 'Pending';
      }
      jQuery('#transaction_details').html('<div class="row-fluid"><div class="span12"><strong>Purpose</strong> '+res.purpose+'</div><div class="span6"><strong>Transaction Ref: </strong>  '+res.ref+'</div><div class="span6"><strong>User: </strong> '+res.display_name+'</div><div class="span12"><strong>Amount: </strong> '+res.amount+'</div></div><div class="row"><div class="span4"><strong>Beneficiary Name</strong> '+res.firstname+' '+res.lastname+'</div><div class="span4"><strong>Relation</strong> '+res.relationship+'</div></div><div class="row"><div class="span4"><strong>Transaction Date: </strong> '+res.updated_date+'</div><div class="span4"><strong>Your Payment: </strong> '+res.converted_amount+' '+res.code+'</div><div class="span4"><strong>Status: '+status+'</strong> </div></div>');
    });
    // alert(transaction_id);
	});

  jQuery('.radio-button').on('change',function(){
    var radioBtnValue = jQuery(this).val();
    if(radioBtnValue == 'yes'){
      jQuery('.bank-detail-section').fadeIn(300);
    } else {
      jQuery('.bank-detail-section').fadeOut(300);
    }
  });

});
