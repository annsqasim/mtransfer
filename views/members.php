<?php
   include 'tab-menu.php';
?>
<div class="wrap">
  <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
  <div class="wrap">
    <table class="wp-list-table widefat fixed striped pages">
    	<thead>
        <th>id</th>
    		<th>Name</th>
    		<th>Email</th>
        <th>Status</th>
    		<th>Registered At</th>
        <th>Role</th>
    		<th>Delete</th>
    	</thead>
    	<tbody>
        <?php

        $user_query = new WP_User_Query( array ( 'orderby' => 'registered', 'order' => 'ASC' ) );
        //echo "<pre>"; print_r($user_query->results); echo "</pre>";
        // User Loop
        if ( ! empty( $user_query->results ) ) {
        	foreach ( $user_query->results as $user ) {
            echo "<tr>";
            echo '<td>' . $user->ID . '</td>';
        		echo '<td>' . $user->display_name . '</td>';
            echo '<td>' . $user->user_email . '</td>';
            echo '<td>' . $user->user_status . '</td>';
            echo '<td>' . $user->user_registered . '</td>';
            echo '<td>' . $user->roles[0] . '</td>';
            echo "</tr>";
        	}
        } else {
        	echo 'No users found.';
        }
         ?>
    	</tbody>
    	<tfoot>
    	</tfoot>
    </table>

  </div>
</div>
