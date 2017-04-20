jQuery( function() {

  jQuery( "#datepicker" ).datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd'
  });

  jQuery('#remit-table').DataTable();
  jQuery('#beneficiary-table').DataTable();

// when typing the amount
  jQuery('#base_currency').on('change',function(){
    var amount = jQuery('#amount').val();
    var base_currency =  jQuery('#sending_currency').val();

    jQuery('#total_amount').val(amount);

    var send_data = {
      'action': 'convertrates',
      'amount': amount,
      'base_currency': base_currency,
    };
    jQuery.post(ajaxURL, send_data, function(result) {
      var res = JSON.parse(result);
      jQuery('#converted_amount').val(res.amount);
    });
	});

  jQuery('#add_beneficiary').on('click',function(e){
    e.preventDefault();
    var data = {
      'action':'addbeneficiary',
      'user_id' : jQuery('#user_id').val(),
      'firstname' : jQuery('#firstname').val(),
      'lastname' : jQuery('#lastname').val(),
      'nationality' : jQuery('#nationality').val(),
      'dob' : jQuery('#datepicker').val(),
      'nic' : jQuery('#nic').val(),
      'relation' : jQuery('#relation').val(),
      'contact' : jQuery('#contact').val(),
      'email' : jQuery('#email').val(),
      'acc_title' : jQuery('#acc_title').val(),
      'acc_number' : jQuery('#acc_number').val(),
      'iban' : jQuery('#iban').val(),
      'bank' : jQuery('#bank').val(),
      'bank_address' : jQuery('#bank_address').val(),
      'branch_code' : jQuery('#branch_code').val(),
      'address' : jQuery('#address').val(),
      'city' : jQuery('#city').val(),
      'state' : jQuery('#state').val(),
      'postcode' : jQuery('#postcode').val(),
      'country' : jQuery('#country').val(),
    };
    jQuery.post(ajaxURL, data, function(resp) {
      console.log(resp);
      if(resp == 0){
        jQuery('#ben-success').fadeIn(300);
      }else{
        jQuery('#ben-error').fadeIn(300);
      }
    });
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
