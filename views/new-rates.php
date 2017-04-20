<?php
 include 'tab-menu.php';

  global $wpdb;
  if($_POST['add_new_rate']){
    $data = array(
      'currency_id' => $_POST['currency'],
      'rates' => $_POST['rates'],
      'date' => date('Y-m-d'),
    );

    if($wpdb->insert($wpdb->prefix.'mt_currency_rates',$data)){
      echo "<div class='updated'>New Currency Rates Added Successfully</div>";
    }
  }

  $currencies = $wpdb->get_results("SELECT * FROM wp_mt_currency c INNER JOIN wp_mt_currency_rates cr ON c.id = cr.currency_id
    where c.is_active = 1");
?>
<div class="wrap">
  <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
  <div class="wrap">

    <table class="wp-list-table widefat fixed striped pages">
    	<thead>
        <th>Currency Code</th>
        <th>Rates from Base Currency</th>
        <th>Updated Rates</th>
        <th>Edit</th>
    	</thead>
    	<tbody>
        <?php
          if($currencies){
            foreach ($currencies as $key => $value) {
              $detailsUrl = admin_url('/admin.php?page=edit-rates&id='.$value->id);
              echo "<tr>";
              echo "<td>".$value->code."</td>";
              echo "<td> 1 AUD = ".$value->rates." ".$value->name."</td>";
              echo "<td>".$value->date."</td>";
              echo "<td><a class='button' href='".$detailsUrl."'>Edit</a></td>";
              echo "</tr>";
            }
          }
        ?>
    	</tbody>
    	<tfoot>
    	</tfoot>
    </table>

    <h2>Add New Rates</h2>
    <table class="table">
      <form method="post" action="#">
        <tr>
          <td>
            <label>Select Currency<label>
              <?php

              $currencies = $wpdb->get_results("SELECT * from wp_mt_currency WHERE is_active=1");

              if($currencies){
              ?>
              <select name="currency" required="required">
                <option disabled="disabled">Select Currency</option>
                <?php foreach ($currencies as $key => $value) { ?>
                <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                <?php }
              } ?>
              </select>
          </td>
          <td>
            <label>From Base Currency<label>
            <input type="text" name="rates" placeholder="Rates"/>
          </td>
          <td>
            <input type="submit" class="button button-primary" name="add_new_rate" value="Add New Rates" />
          </td>
        </tr>
      </from>
    </table>
  </div>
</div>
