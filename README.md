# Advanced Custom Fields: acf-post-type-selector Field

* **Contributors:** [@iceicetimmy](https://github.com/iceicetimmy), [@shaunbent](https://github.com/shaunbent)
* **Tags:** ACF, Post Type Selector
* **Requires at least:** 3.4
* **Tested up to:** 4.0.1
* **Stable tag:** trunk
* **License:** GPLv2 or later
* **License URI:** http://www.gnu.org/licenses/gpl-2.0.html

## Description

Provides the option to select a single or multiple post types

## Compatibility

This add-on will work with:

* version 4 and up
* version 3 and bellow

## Installation

This add-on can be treated as both a WP plugin and a theme include.

### Plugin
1. Copy the 'acf-post-type-selector' folder into your plugins folder
2. Activate the plugin via the Plugins admin page

### Include
1. Copy the 'acf-post-type-selector' folder into your theme folder (can use sub folders). You can place the folder anywhere inside the 'wp-content' directory
2. Edit your functions.php file and add the code below (Make sure the path is correct to include the acf-post-type-selector.php file)

        add_action( 'acf/register_fields', 'my_register_fields' );

        function my_register_fields() {
	
             include_once( 'acf-post-type-selector}/acf-post-type-selector.php' );
    
        }

## Changelog

### 0.0.1
* Initial Release.
