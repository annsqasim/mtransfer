<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/*
Plugin Name: Money Transfeer Custom Plugin
Plugin URI: http://www.annsqasim.com/riviera
Description: This is a social Plugin by Anns
Author: Anns Qasim
Version: 1.0
Author URI: http://www.annsqasim.com
*/

require_once ABSPATH.'wp-admin/includes/upgrade.php';

/**
 * Proper way to enqueue scripts and styles
 */
function my_scripts() {
  wp_enqueue_script('jquery-ui-datepicker');
  wp_enqueue_script('app-js',plugins_url('/mtransfer-custom/js/myapp.js') );
  wp_enqueue_script('datatable-js',plugins_url('/mtransfer-custom/js/datatable.js') );

  wp_enqueue_style( 'jquery-datatable', plugins_url('/mtransfer-custom/css/datatable.css') );
	wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
	wp_enqueue_style( 'custom-css', plugins_url('/mtransfer-custom/css/custom.css') );
}
add_action( 'wp_enqueue_scripts', 'my_scripts' );


add_action( 'admin_menu', 'my_admin_menu' );
register_nav_menu( 'sub-menu', __( 'Sub Menu', 'mtransfer' ) );

if(!is_admin()){
  add_filter('show_admin_bar', '__return_false');
}

function my_admin_menu() {
  add_menu_page('Money Transfer','Money Transfer','manage_options','mtransfer','admin_page','dashicons-money',6);
  add_submenu_page('mtransfer', 'Members', 'Members List', 'manage_options','mtransfer-members','members_page');
  add_submenu_page(null, 'Transaction Details', 'Transaction Details', 'manage_options','transaction-detail','transaction_detail_page');
  add_submenu_page('mtransfer', 'Rates', 'Rates', 'manage_options','mtransfer-rates','add_rates_page');
  add_submenu_page(null, 'Edit Rates', 'Edit Rates', 'manage_options','edit-rates','edit_rates_page');
  add_submenu_page('mtransfer', 'Currency', 'Currency', 'manage_options','mtransfer-currency','currency_page');
  //add_submenu_page( 'my-top-level-slug', 'My Custom Submenu Page', 'My Custom Submenu Page',
  //'manage_options', 'my-secondary-slug');
}

function get_path(){
	$path = dirname( __FILE__ );
	return $path;
}

function admin_tabs($tabs, $current=NULL){
	if(is_null($current)){
		if(isset($_GET['page'])){
			$current = $_GET['page'];
		}
	}
	$content = '';
	$content .= '<h2 class="nav-tab-wrapper">';
	foreach($tabs as $location => $tabname){
		if($current == $location){
			$class = ' nav-tab-active';
		} else{
			$class = '';
		}
		$content .= '<a class="nav-tab'.$class.'" href="?page='.$location.'">'.$tabname.'</a>';
	}
	$content .= '</h2>';
	return $content;
}

function admin_page(){
	// Create WP Admin Tabs on-the-fly.
	include( get_path() . '/views/admin.php' );
}

function members_page(){
  include( get_path() . '/views/members.php' );
}

function transaction_detail_page(){
  include( get_path() . '/views/transaction-detail.php' );
}

function add_rates_page(){
  include( get_path() . '/views/new-rates.php' );
}

function edit_rates_page(){
  include( get_path() . '/views/rates-edit.php' );
}

function currency_page(){
  include( get_path() . '/views/currency-list.php' );
}
