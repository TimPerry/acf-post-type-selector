<?php

/*
Plugin Name: Advanced Custom Fields: Post Type Selector
Plugin URI: PLUGIN_URL
Description: ACF Fieldtype | Select from post types
Version: 1.0.0
Author: Rafe
Author URI: http://rafegoldberg.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/




// 1. set text domain
// Reference: https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
load_plugin_textdomain( 'acf-post_type_selector', false, dirname( plugin_basename(__FILE__) ) . '/lang/' ); 




// 2. Include field type for ACF5
// $version = 5 and can be ignored until ACF6 exists
function include_field_types_post_type_selector( $version ) {
	
	include_once('acf-post_type_selector-v5.php');
	
}

add_action('acf/include_field_types', 'include_field_types_post_type_selector');	




// 3. Include field type for ACF4
function register_fields_post_type_selector() {
	
	include_once('acf-post_type_selector-v4.php');
	
}

add_action('acf/register_fields', 'register_fields_post_type_selector');	



	
?>