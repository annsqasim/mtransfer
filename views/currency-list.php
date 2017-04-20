<?php
   include 'tab-menu.php';

  if($_POST['add_currency']){
    $name = $_POST['currency_name'];
    $code = $_POST['currency_code'];

    global $wpdb;
    $data = array(
      'name' => $name,
      'code' => $code,
      'is_active' => 1,
      'is_default' => 0,
    );
    if($wpdb->insert($wpdb->prefix.'mt_currency',$data)){
      echo "<div class='updated'>New Currency Added Successfully</div>";
    }
  }

  global $wpdb;

  $currencies = $wpdb->get_results("SELECT * FROM wp_mt_currency");
?>
<div class="wrap">
  <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
  <div class="wrap">

    <table class="wp-list-table widefat fixed striped pages">
    	<thead>
        <th>Currency Code</th>
        <th>Currency</th>
        <th>Status</th>
    	</thead>
    	<tbody>
        <?php
          if($currencies){
            foreach ($currencies as $key => $value) {
              $active = '';
              if($value->is_active){
                $active = 'checked="checked"';
              }
              echo "<tr>";
              echo "<td>".$value->code."</td>";
              echo "<td>".$value->name."</td>";
              echo "<td><input type='checkbox' name='is_active' value='".$value->id."' ".$active."/></td>";
              echo "</tr>";
            }
          }
        ?>
    	</tbody>
    	<tfoot>
    	</tfoot>
    </table>

    <h2>Add New Currency</h2>
    <form method="post" action="#">
      <table class="table">
        <tr>
          <td>
            <label>Currency Name<label>
            <input type="text" name="currency_name" placeholder="Enter Currency Name"/>
          </td>
          <td>
            <label>Currency Code<label>
            <input type="text" name="currency_code" placeholder="Enter Currency Code"/>
          </td>
          <td>
            <input type="submit" class="button button-primary" name="add_currency" value="Add Currency" />
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
