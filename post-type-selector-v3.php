<?php

class acf_field_post_type_selector extends acf_Field
{
	
	const SELECTOR_TYPE_SELECT = 0;
	const SELECTOR_TYPE_RADIO = 1;
	const SELECTOR_TYPE_CHECKBOXES = 2;
	
	/*--------------------------------------------------------------------------------------
	*
	*	Constructor
	*	- This function is called when the field class is initalized on each page.
	*	- Here you can add filters / actions and setup any other functionality for your field
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function __construct($parent)
	{
		// do not delete!
    	parent::__construct($parent);
    	
    	// set name / title
    	$this->name = 'post_type_selector';
		$this->label = __('Post Type Selector');
		$this->defaults = array(
			'select_type' => 'Checkboxes',
		);
		
   	}

	
	/*--------------------------------------------------------------------------------------
	*
	*	create_options
	*	- this function is called from core/field_meta_box.php to create extra options
	*	for your field
	*
	*	@params
	*	- $key (int) - the $_POST obejct key required to save the options to the field
	*	- $field (array) - the field object
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function create_options($key, $field)
	{
		
		// defaults?
		$field = array_merge($this->defaults, $field);
		
		// key is needed in the field names to correctly save the data
		$key = $field['name'];
		
		?>
		
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Selector Type",'acf'); ?></label>
				<p>How would you like to select the post type?</p>
			</td>
			<td>
				
				<?php
		
				do_action('acf/create_field', array(
					'type' => 'select',
					'name' => 'fields['.$key.'][select_type]',
					'value' => $field['select_type'],
					'layout' => 'horizontal',
					'choices' => array( 
						acf_field_post_type_selector::SELECTOR_TYPE_SELECT => __( 'Select' ), 
						acf_field_post_type_selector::SELECTOR_TYPE_RADIO => __( 'Radio' ),
						acf_field_post_type_selector::SELECTOR_TYPE_CHECKBOXES => __( 'Checkboxes' ),
					)
				));
				
				?>
	
			</td>
		</tr>
		
		<?php
		
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	pre_save_field
	*	- this function is called when saving your acf object. Here you can manipulate the
	*	field object and it's options before it gets saved to the database.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function pre_save_field($field)
	{
		// Note: This function can be removed if not used
		
		// do stuff with field (mostly format options data)
		
		return parent::pre_save_field($field);
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	create_field
	*	- this function is called on edit screens to produce the html for this field
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function create_field($field)
	{
		
		// defaults?
		$field = array_merge( $this->defaults, $field );
				
		$post_types = get_post_types( array(
			'public' => true,
		), 'objects' );

		// create Field HTML
		$checked = array( );

		switch ( $field[ 'select_type' ] ) {
		
			case acf_field_post_type_selector::SELECTOR_TYPE_SELECT:
					
				echo '<select id="' . $field[ 'name' ] . '" class="' . $field[ 'class' ] . '" name="' . $field[ 'name' ] . '">';
				
				$checked[ $field[ 'value' ] ] = 'selected="selected"';
				
				foreach( $post_types as $post_type ) {
				
					echo '<option ' . $checked[ $post_type->name ] . ' value="' . $post_type->name . '">' . $post_type->labels->name . '</option>';
				
				}
				
				echo '</select>';
				
			break;
			
			case acf_field_post_type_selector::SELECTOR_TYPE_RADIO:
				
				echo '<ul class="radio_list radio horizontal">';
				
				$checked[ $field[ 'value' ] ] = 'checked="checked"';
				
				foreach( $post_types as $post_type ) {
				
				?>
				
					<li><input type="radio" <?php echo ( isset( $checked[ $post_type->name ] ) ) ? $checked[ $post_type->name] : null; ?> class="<?php echo $field[ 'class' ]; ?>" name="<?php echo $field[ 'name' ]; ?>" value="<?php echo $post_type->name; ?>"><label><?php echo $post_type->labels->name; ?></label></li>
					
				<?php
				
				}
				
				echo '</ul>';
				
			
			break;
			
			case acf_field_post_type_selector::SELECTOR_TYPE_CHECKBOXES:
			
				echo '<ul class="checkbox_list checkbox">';
				
 				if ( ! empty( $field[ 'value'] ) ) {

					foreach(  $field[ 'value' ] as $val ) {
					
						$checked[ $val ] = 'checked="checked"';
					
					}
				
				}
				
				foreach( $post_types as $post_type ) {
				
				?>
								
					<li><input type="checkbox" <?php echo ( isset( $checked[ $post_type->name ] ) ) ? $checked[ $post_type->name] : null; ?> class="<?php echo $field[ 'class' ]; ?>" name="<?php echo $field[ 'name' ]; ?>[]" value="<?php echo $post_type->name; ?>"><label><?php echo $post_type->labels->name; ?></label></li>
				<?php
				
				}
				
				echo '</ul>';
			
			break;
			
		}
		
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	admin_head
	*	- this function is called in the admin_head of the edit screen where your field
	*	is created. Use this function to create css and javascript to assist your 
	*	create_field() function.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function admin_head()
	{
		// Note: This function can be removed if not used
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	admin_print_scripts / admin_print_styles
	*	- this function is called in the admin_print_scripts / admin_print_styles where 
	*	your field is created. Use this function to register css and javascript to assist 
	*	your create_field() function.
	*
	*	@author Elliot Condon
	*	@since 3.0.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function admin_print_scripts()
	{
		// Note: This function can be removed if not used
		
		
		// register acf scripts
		wp_register_script( 'acf-input-post_type_selector', $this->settings['dir'] . 'js/input.js', array('acf-input'), $this->settings['version'] );
		
		// scripts
		wp_enqueue_script(array(
			'acf-input-post_type_selector',	
		));

		
	}
	
	function admin_print_styles()
	{
		// Note: This function can be removed if not used
		
		
		wp_register_style( 'acf-input-post_type_selector', $this->settings['dir'] . 'css/input.css', array('acf-input'), $this->settings['version'] ); 
		
		// styles
		wp_enqueue_style(array(
			'acf-input-post_type_selector',	
		));
	}

	
	/*--------------------------------------------------------------------------------------
	*
	*	update_value
	*	- this function is called when saving a post object that your field is assigned to.
	*	the function will pass through the 3 parameters for you to use.
	*
	*	@params
	*	- $post_id (int) - usefull if you need to save extra data or manipulate the current
	*	post object
	*	- $field (array) - usefull if you need to manipulate the $value based on a field option
	*	- $value (mixed) - the new value of your field.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function update_value($post_id, $field, $value)
	{
		// Note: This function can be removed if not used
		
		// do stuff with value
		
		// save value
		parent::update_value($post_id, $field, $value);
	}
	
	
	
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	get_value
	*	- called from the edit page to get the value of your field. This function is useful
	*	if your field needs to collect extra data for your create_field() function.
	*
	*	@params
	*	- $post_id (int) - the post ID which your value is attached to
	*	- $field (array) - the field object.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function get_value($post_id, $field)
	{
		// Note: This function can be removed if not used
		
		// get value
		$value = parent::get_value($post_id, $field);
		
		// format value
		
		// return value
		return $value;		
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	get_value_for_api
	*	- called from your template file when using the API functions (get_field, etc). 
	*	This function is useful if your field needs to format the returned value
	*
	*	@params
	*	- $post_id (int) - the post ID which your value is attached to
	*	- $field (array) - the field object.
	*
	*	@author Elliot Condon
	*	@since 3.0.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function get_value_for_api($post_id, $field)
	{
		// Note: This function can be removed if not used
		
		// get value
		$value = $this->get_value($post_id, $field);
		
		// format value
		
		// return value
		return $value;

	}
	
}

?>