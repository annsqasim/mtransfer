jQuery( function() {

  jQuery( "#datepicker" ).datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd'
  });

  jQuery('#remit-table').DataTable();
  jQuery('#beneficiary-table').DataTable();

// when typing the amount
  jQuery('#sending_currency').on('change',function(){
    var currency = jQuery(this).val();

    var data = {
      'action':'getCode',
      'currency' : currency,
    };

    jQuery.post(ajaxURL, data, function(resp) {
      jQuery('#f_currency').html(resp);
    });


	});

  jQuery('#amount').on('change',function(e){
    e.preventDefault();
    var amount = jQuery('#amount').val().match(/^\d+$/) ? jQuery('#amount').val() : false;
    var additionalCharges = 0.00;
    var data = {
      'action':'convertRates',
      'amount' : jQuery('#amount').val(),
      'currency' : jQuery('#sending_currency').val(),
    };
    jQuery.post(ajaxURL, data, function(resp) {
      if (amount <= 300) {
        amount = parseInt(amount) + parseInt(10);
        additionalCharges = 10.00;
      }
      var res = JSON.parse(resp);
      console.log(res);
      jQuery('#additionalCharges').val(additionalCharges);
      jQuery('#total_amount').val(amount);
      jQuery('#rec_amount').val(res.total);
      jQuery('#xrate').val(res.rate);
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

  jQuery('#bank_country').on('change',function(){
    var country = jQuery(this).val();
    if(country == "Pakistan"){
      jQuery('input.bank_input').fadeOut();
      jQuery('select.bank_input').fadeIn(100);
    } else {
      jQuery('select.bank_input').fadeOut();
      jQuery('input.bank_input').fadeIn(100);
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
      jQuery('#transaction_details').html('<div class="row-fluid"><div class="span12"><strong>Purpose</strong> '+res.purpose+'</div><div class="span6"><strong>Transaction Ref: </strong>  '+res.ref+'</div><div class="span6"><strong>User: </strong> '+res.display_name+'</div><div class="span12"><strong>Amount Recieved: </strong> '+res.amount_recieve+' '+res.code+'</div></div><div class="row"><div class="span4"><strong>Beneficiary Name</strong> '+res.firstname+' '+res.lastname+'</div><div class="span4"><strong>Relation</strong> '+res.relationship+'</div></div><div class="row"><div class="span4"><strong>Transaction Date: </strong> '+res.updated_date+'</div><div class="span4"><strong>Your Payment: </strong> '+res.total_amount_paid+' AUD</div><div class="span4"><strong>Status: '+status+'</strong> </div></div>');
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
