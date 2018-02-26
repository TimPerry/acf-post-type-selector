# Advanced Custom Fields: acf-post-type-selector Field

* **Contributors:** [@timperry](https://github.com/timperry), [@shaunbent](https://github.com/shaunbent), @emaildano, @rafegoldberg, @thomasdebruin, @gnowland 
* **Tags:** ACF, Post Type Selector
* **Requires at least:** 4.0
* **Tested up to:** 5.0.0
* **Stable tag:** trunk
* **License:** GPLv2 or later
* **License URI:** http://www.gnu.org/licenses/gpl-2.0.html

## Description

Provides the option to select a single or multiple post types

## Compatibility

This add-on will work with:

* version 5 and up
* version 4 and up

## Installation

This add-on can be treated as both a WP plugin and a theme include.

### Plugin
1. Copy the 'acf-post-type-selector' folder into your plugins folder
2. Activate the plugin via the Plugins admin page

### Include
1. Copy the 'acf-post-type-selector' folder into your theme folder (can use sub folders). You can place the folder anywhere inside the 'wp-content' directory
2. Edit your functions.php file and add the code below (Make sure the path is correct to include the acf-post-type-selector.php file)

##### ACF Version 4
        add_action( 'acf/register_fields', 'my_register_fields' );

        function my_register_fields() {
	
             include_once( 'acf-post-type-selector/acf-post-type-selector.php' );
    
        }
        
##### ACF Version 5
        add_action( 'acf/include_fields', 'my_register_fields' );

        function my_register_fields() {
	
             include_once( 'acf-post-type-selector/acf-post-type-selector.php' );
    
        }

## Usage

### Get Field Value
```
$post_type_var = 'post_type';
$post_type = get_sub_field( $post_type_var );
```

### Get Field Value and Display Label

```
<?php
// get post type fields
$post_type_var = 'post_type';
$post_type = get_sub_field( $post_type_var );

// get post type label
$post_type_object = get_post_type_object( $post_type ); ?>

<li><a href="<?php echo get_post_type_archive_link( $post_type ); ?>"><?php echo $post_type_object->label; ?></a></li>
```

## Changelog
### 1.0.0
* Support for v5

### 0.0.1
* Initial Release.
