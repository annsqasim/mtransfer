<?php
 include 'tab-menu.php';

  $currency_id = $_GET['id'];
  global $wpdb;

  if($_POST['edit']){

    $data = array(
      'rates' => $_POST['new_rate'],
      'date' => date('Y-m-d'),
    );
    $where = array(
      'currency_id' => $_POST['currency_id'],
    );

    $updated = $wpdb->update( $wpdb->prefix.'mt_currency_rates', $data, $where );

    if ( false === $updated ) {
        echo "error";
    } else {
        echo "changed";
    }

  }

  $currencies = $wpdb->get_results("SELECT * FROM wp_mt_currency_rates cr INNER JOIN wp_mt_currency c on
    c.id = cr.currency_id where currency_id =".$currency_id);
?>
<div class="wrap">
  <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
  <form method="post" action="#">
    <table class="form-table">
      <tbody>
      <?php foreach($currencies as $currency) : ?>
        <tr>
          <th scope="row"><label for="transaction_id">Currency ID</label></th>
          <td><input name="currency_id" id="currency_id" value="<?php echo $currency->id; ?>" class="regular-text" type="text" readonly></td>
        </tr>
        <tr>
          <th scope="row"><label for="display_name">Currency Name</label></th>
          <td><input name="name" id="name" value="<?php echo $currency->name; ?>" class="regular-text" type="text" readonly></td>
        </tr>
        <tr>
          <th scope="row"><label for="display_name">Currency Rates (From Base)</label></th>
          <td><input name="fromrate" id="rate" value="<?php echo $currency->rates; ?>" class="regular-text" type="text"></td>
        </tr>
        <tr>
          <th scope="row"><label for="display_name">New Rates (To Base)</label></th>
          <td><input name="new_rate" id="rate" value="" class="regular-text" type="text"></td>
        </tr>
        <tr>
          <th scope="row"><label for="display_name">Currency Date</label></th>
          <td><input name="date" id="date" value="<?php echo $currency->date; ?>" class="regular-text" type="text" readonly></td>
        </tr>

        <tr>
          <td></td>
          <td>
            <input type="submit" name="edit" value="Update" class="button button-primary">
            <a class="button button-default" href='<?php echo admin_url('?page=mtransfer-rates'); ?>'>Cancel</a>
          </td>
        </tr>
          <?php endforeach; ?>
      </tbody>
    </table>
  </form>
</div>
