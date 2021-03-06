<?php
 include 'tab-menu.php';

  $transaction_id = $_GET['id'];

  global $wpdb;

  if ($_POST['edit']) {
    $data = array(
      'amount_send' => $_POST['amount_send'],
      'amount_recieve' => $_POST['amount_recieve'],
      'currency_id' => $_POST['currency'],
      'status' => $_POST['status'],
      'comments' => $_POST['comments'],
      'total_amount_paid' => $_POST['total_amount_paid'],
      'updated_date' => date('Y-m-d H:i:s'),
    );
    $where = array(
      'id' => $transaction_id,
    );

    $updated = $wpdb->update( $wpdb->prefix.'mt_transaction', $data, $where );

    if ( false === $updated ) {
        echo "<div class='error'>Cannot Updated Error has been occured</div>";
    } else {
        echo "<div class='updated'>The record has been updated Successfully!</div>";
    }
  } elseif ($_POST['email']) {
    global $wpdb;
    $display_name = $_POST['display_name'];
    $email = $wpdb->get_results("SELECT user_email FROM wp_users where display_name = '".$display_name."'");

    $to = $email[0]->user_email;
    $subject = 'The subject';
    $body = 'Your Transation has completely submitted Successfully';
    $headers = array('Content-Type: text/html; charset=UTF-8');

    if(wp_mail( $to, $subject, $body, $headers )){
      echo "<div class='updated'>Email Sent Successfully</div>";
    }else{
      echo "<div class='error'>Email Not sent check configurations</div>";
    }
  } elseif ($_POST['cancel']) {
    echo "Cancel";
  }

  $transactions = $wpdb->get_results("SELECT * FROM wp_mt_transaction t INNER JOIN wp_users u ON u.id = t.user_id INNER JOIN wp_mt_benificiary b ON b.id = t.beneficiary_id
    INNER JOIN wp_mt_currency c ON c.id = t.currency_id where t.id =".$transaction_id);
?>
<style media="screen">
  .button-email{
    background: #179340 !important;
    color: #fff !important;
    border-color: #179340 !important;
  }
</style>
<div class="wrap">
  <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
  <form method="post" action="#">
    <table class="form-table">
      <tbody>
      <?php foreach($transactions as $transaction) : ?>
        <tr>
          <th scope="row"><label for="transaction_id">Transaction Reference</label></th>
          <td><input name="transaction_id" id="transaction_id" value="<?php echo $transaction->ref; ?>" class="regular-text" type="text" readonly></td>
        </tr>
        <tr>
          <th scope="row"><label for="display_name">User Name</label></th>
          <td><input name="display_name" id="display_name" value="<?php echo $transaction->display_name; ?>" class="regular-text" type="text" readonly></td>
        </tr>
        <tr>
          <th scope="row"><label for="beneficiary_name">Beneficiary Name</label></th>
          <td>
            <select name="beneficiary_name" id="beneficiary_name">
            <?php
              $beneficiaries = $wpdb->get_results("SELECT id, firstname, lastname FROM ".$wpdb->prefix."mt_benificiary");
              foreach ($beneficiaries as $key => $value) {
                $sel = '';
                if($value->id == $transaction->beneficiary_id){
                  $sel = 'selected="selected"';
                }
                echo '<option '.$sel.' value="'.$value->id.'">'.$value->firstname.' '.$value->lastname.'</option>';
              }
            ?>
            </select>
          </td>
        </tr>
        <tr>
          <th scope="row"><label for="amount">Amount Send</label></th>
          <td>
            <input name="amount_send" id="amount" value="<?php echo $transaction->amount_send; ?>" class="regular-text" type="text">
            <select name="paid_currency" id="currency">
            <?php
              $currencies = $wpdb->get_results("SELECT id, name, code FROM ".$wpdb->prefix."mt_currency");
              foreach ($currencies as $key => $value) {
                $sel = '';
                if($value->code == 'AUD'){
                  $sel = 'selected="selected"';
                }
                echo '<option '.$sel.' value="'.$value->id.'">'.$value->name.'</option>';
              }
            ?>
            </select>
          </td>
        </tr>
        <tr>
          <th scope="row"><label for="amount">Amount Recieve</label></th>
          <td>
            <input name="amount_recieve" id="amount" value="<?php echo $transaction->amount_recieve; ?>" class="regular-text" type="text">
            <select name="currency" id="currency">
            <?php
              $currencies = $wpdb->get_results("SELECT id, name, code FROM ".$wpdb->prefix."mt_currency");
              foreach ($currencies as $key => $value) {
                $sel = '';
                if($value->code == $transaction->code){
                  $sel = 'selected="selected"';
                }
                echo '<option '.$sel.' value="'.$value->id.'">'.$value->name.'</option>';
              }
            ?>
            </select>
          </td>
        </tr>
        <tr>
          <th scope="row"><label for="amount">Total Amount Paid</label></th>
          <td>
            <input name="total_amount_paid" id="amount" value="<?php echo $transaction->total_amount_paid; ?>" class="regular-text" type="text">
            <select name="paid_currency" id="currency">
            <?php
              $currencies = $wpdb->get_results("SELECT id, name, code FROM ".$wpdb->prefix."mt_currency");
              foreach ($currencies as $key => $value) {
                $sel = '';
                if($value->code == 'AUD'){
                  $sel = 'selected="selected"';
                }
                echo '<option '.$sel.' value="'.$value->id.'">'.$value->name.'</option>';
              }
            ?>
            </select>
          </td>
        </tr>
        <tr>
          <th scope="row"><label for="amount">Comments</label></th>
          <td>
            <textarea name="comments" class="regular-text"><?php echo $transaction->comments; ?></textarea>
          </td>
        </tr>
        <tr>
          <th scope="row">Status</th>
          <td>
            <fieldset>
              <legend class="screen-reader-text"><span>Membership</span></legend>
              <?php
                $paid = '';
                $unpaid = '';
                $hold = '';
                $cancelled = '';
                $processed = '';

                if ($transaction->status == 'pr'){
                  $paid = 'checked="checked"';
                } else {
                  $unpaid = 'checked="checked"';
                }
              ?>
              <label for="users_can_register">
                <input name="status" id="status" value="pr" <?php echo $paid; ?> type="radio">Processed
              </label>
              <label for="users_can_register">
                <input name="status" id="status" value="pen" <?php echo $unpaid; ?> type="radio">Pending
              </label>
          </fieldset></td>
        </tr>
        <tr>
          <td></td>
          <td>
            <input type="submit" name="edit" value="Update" class="button button-primary">
            <input type="submit" name="email" value="Send Email" class="button button-email">
            <a class="button button-default" href='<?php echo admin_url('?page=mtransfer'); ?>'>Close</a>
          </td>
        </tr>
          <?php endforeach; ?>
      </tbody>
    </table>
  </form>
</div>
<?php

?>
