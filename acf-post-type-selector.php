<?php
/*
Plugin Name: Advanced Custom Fields: Post Type Selector
Plugin URI: https://github.com/TimPerry/acf-post-type-selector
Description: Provides the option to select a single or multiple post types
Version: 1.0.1
Author: Tim Perry
Author URI: http://www.forepoint.co.uk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


class acf_field_post_type_selector_plugin
{
	/*
	*  Construct
	*
	*  @description: 
	*  @since: 3.6
	*  @created: 1/04/13
	*/
	
	function __construct()
	{
		// set text domain
		/*
		$domain = 'acf-post_type_selector';
		$mofile = trailingslashit(dirname(__File__)) . 'lang/' . $domain . '-' . get_locale() . '.mo';
		load_textdomain( $domain, $mofile );
		*/
		
		
		// version 5+
		add_action('acf/include_field_types', array($this, 'include_field_types') );

		// version 4+
		add_action('acf/register_fields', array($this, 'register_fields'));	
	}
	
	/*
	*  register_fields
	*
	*  @description: 
	*  @since: 3.6
	*  @created: 1/04/13
	*/
	
	function include_field_types()
	{
		include_once('post-type-selector-v5.php');
	}
	
	/*
	*  register_fields
	*
	*  @description: 
	*  @since: 3.6
	*  @created: 1/04/13
	*/
	
	function register_fields()
	{
		include_once('post-type-selector-v4.php');
		
	}
	
}

new acf_field_post_type_selector_plugin();
		
?>
