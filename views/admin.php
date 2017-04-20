<?php include 'tab-menu.php'; ?>

<style>
.paid{
  color: green !important;
  font-weight: bold;
}
.pending{
  color: #e0c924 !important;
  font-weight: bold;
}
.cancelled{
  color: #d21616 !important;
  font-weight: bold;
}
.processed{
  color: #269db1 !important;
  font-weight: bold;
}
</style>
<?php
//for post-check

global $wpdb;

if($_POST['export_to_csv']){
  generate_csv();
} elseif ($_POST['filter_by_date']) {
  //echo $date = $_POST['datetofilter'];
  $transactions = $wpdb->get_results("SELECT t.id,t.amount,t.created_date,t.updated_date,t.status,t.user_id,t.converted_amount,t.applied_rates,u.display_name,b.firstname,b.lastname,c.code
    FROM wp_mt_transaction t INNER JOIN wp_users u ON u.id = t.user_id INNER JOIN wp_mt_benificiary b ON b.id = t.beneficiary_id
    INNER JOIN wp_mt_currency c ON c.id = t.currency_id WHERE t.updated_date <= '".$date."'");
} else {
  $transactions = $wpdb->get_results("SELECT t.id,t.amount,t.created_date,t.updated_date,t.status,t.user_id,u.display_name,b.firstname,b.lastname,c.code
    FROM wp_mt_transaction t INNER JOIN wp_users u ON u.id = t.user_id INNER JOIN wp_mt_benificiary b ON b.id = t.beneficiary_id
    INNER JOIN wp_mt_currency c ON c.id = t.currency_id");
}

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.5.0/datepicker.css">
<div class="wrap">
  <form method="post" action="#">
  <h2><?php echo esc_html(get_admin_page_title()); ?>
    <select name="bank_report">
      <option value="hbl">HBL</option>
      <option value="scb">Standard Chartered</option>
      <option value="meezan">Meezan</option>
      <option value="ahb">Al Habib</option>
    </select>
    <input type="submit" id="exportCsv"name="export_to_csv" class="page-title-action button button-primary" value="EXPORT"></h2>
    <div class="wrap">
      <div class="tablenav top">
        <!-- <div class="alignleft actions bulkactions">
          <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
          <select name="action" id="bulk-action-selector-top">
            <option value="-1">Bulk Actions</option>
            <option value="edit" class="hide-if-no-js">Edit</option>
            <option value="trash">Move to Trash</option>
          </select>
          <input id="doaction" class="button action" value="Apply" type="submit">
        </div> -->
        <div class="alignleft actions">
          <label for="filter-by-date" class="screen-reader-text">Filter by date</label>
          <input type="text" name="fromdatetofilter" id="mydatepicker1" value="" placeholder="From" />
          <input type="text" name="todatetofilter" id="mydatepicker2" value="" placeholder="To" />
          <input name="filter_by_date" id="post-query-submit" class="button" value="Filter" type="submit">
        </div>
      </div>
  </form>
    <table class="wp-list-table widefat fixed striped pages">
    	<thead>
        <th>User</th>
        <th>Amount Sent</th>
        <th>Converted Amount</th>
        <th>Applied Rates</th>
        <th>Benificiary</th>
        <th>Transaction Date</th>
        <th>Updated Date</th>
        <th width="10%">Status</th>
        <th width="10%">Details</th>
    	</thead>
    	<tbody>
        <?php

          if($transactions){
            foreach ($transactions as $key => $value) {
              $detailsUrl = admin_url('/admin.php?page=transaction-detail&id='.$value->id);
              echo "<tr>";
              echo "<td>".$value->display_name."</td>";
              echo "<td>AUD ".number_format_i18n($value->amount,2)."</td>";
              echo "<td>".$value->code." ".$value->converted_amount."</td>";
              echo "<td>".$value->applied_rates."</td>";
              echo "<td>".$value->firstname." ".$value->lastname."</td>";
              echo "<td>".$value->created_date."</td>";
              echo "<td>".$value->updated_date."</td>";
              if ($value->status == 'pr') {
								echo "<td class='processed'>Processed</td>";
							} else {
								echo "<td class='pending'>Pending</td>";
							}
              echo '<td><a class="button" href="'.$detailsUrl.'">Details</a></td>';
              echo "</tr>";
            }
          } else {
            echo "<tr>";
            echo "<td colspan='5'><div class='alert alert-info' role='alert'><strong>No Result Found!</strong>
            No transaction has been made.</div>";
            echo "</tr>";
          }
        ?>
    	</tbody>
    	<tfoot>
    	</tfoot>
    </table>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.5.0/datepicker.js"></script>
<script type="text/javascript">
jQuery( "#mydatepicker1" ).datepicker({
  format: 'yyyy-mm-dd',
});
jQuery( "#mydatepicker2" ).datepicker({
  format: 'yyyy-mm-dd',
});

</script>
