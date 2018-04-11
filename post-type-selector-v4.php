<?php

class acf_field_post_type_selector extends acf_field
{
	
	const SELECTOR_TYPE_SELECT = 0;
	const SELECTOR_TYPE_RADIO = 1;
	const SELECTOR_TYPE_CHECKBOXES = 2;
	
	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options
		
		
	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function __construct()
	{
		// vars
		$this->name = 'post_type_selector';
		$this->label = __('Post Type Selector');
		$this->category = __("Relational",'acf'); // Basic, Content, Choice, etc
		$this->defaults = array(
			'select_type' => 'Checkboxes',
		);
		
		
		// do not delete!
    	parent::__construct();
    	
    	
    	// settings
		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => '1.0.0'
		);

	}
	
	
	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/
	
	function create_options( $field )
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
	
	
	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function create_field( $field )
	{
		// defaults?
		$field = array_merge( $this->defaults, $field );
				
		$post_types = get_post_types( array(
			'public' => true,
		), 'objects' );

		/**
		 * Filters the array of post types.
		 *
		 * @since 1.0.1
		 *
		 * @param array $post_types List of post types.
		 * @param array $field      The field being rendered.
		 */
		$post_types = apply_filters( 'post_type_selector_post_types', $post_types, $field );

		// not required: add emmpty/none value
		if (!$field['required']) {
			$obj = new stdClass();
			$obj->name = "";
			$obj->labels = new stdClass();
			$obj->labels->name = "None";
			array_unshift ( $post_types, $obj);
			
		}

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
	
	
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add css + javascript to assist your create_field() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_enqueue_scripts()
	{
		// Note: This function can be removed if not used
		
		
		// register acf scripts
		wp_register_script( 'acf-input-post_type_selector', $this->settings['dir'] . 'js/input.js', array('acf-input'), $this->settings['version'] );
		wp_register_style( 'acf-input-post_type_selector', $this->settings['dir'] . 'css/input.css', array('acf-input'), $this->settings['version'] ); 
		
		
		// scripts
		wp_enqueue_script(array(
			'acf-input-post_type_selector',	
		));

		// styles
		wp_enqueue_style(array(
			'acf-input-post_type_selector',	
		));
		
		
	}
	
	
	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add css and javascript to assist your create_field() action.
	*
	*  @info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_head()
	{
		// Note: This function can be removed if not used
	}
	
	
	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add css + javascript to assist your create_field_options() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function field_group_admin_enqueue_scripts()
	{
		// Note: This function can be removed if not used
	}

	
	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add css and javascript to assist your create_field_options() action.
	*
	*  @info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function field_group_admin_head()
	{
		// Note: This function can be removed if not used
	}


	/*
	*  load_value()
	*
	*  This filter is appied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value found in the database
	*  @param	$post_id - the $post_id from which the value was loaded from
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the value to be saved in te database
	*/
	
	function load_value( $value, $post_id, $field )
	{
		// Note: This function can be removed if not used
		return $value;
	}
	
	
	/*
	*  update_value()
	*
	*  This filter is appied to the $value before it is updated in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value which will be saved in the database
	*  @param	$post_id - the $post_id of which the value will be saved
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the modified value
	*/
	
	function update_value( $value, $post_id, $field )
	{
		// Note: This function can be removed if not used
		return $value;
	}
	
	
	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is passed to the create_field action
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/
	
	function format_value( $value, $post_id, $field )
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/
		
		// perhaps use $field['preview_size'] to alter the $value?
		
		
		// Note: This function can be removed if not used
		return $value;
	}
	
	
	/*
	*  format_value_for_api()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is passed back to the api functions such as the_field
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/
	
	function format_value_for_api( $value, $post_id, $field )
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/
		
		// perhaps use $field['preview_size'] to alter the $value?
		
		
		// Note: This function can be removed if not used
		return $value;
	}
	
	
	/*
	*  load_field()
	*
	*  This filter is appied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$field - the field array holding all the field options
	*/
	
	function load_field( $field )
	{
		// Note: This function can be removed if not used
		return $field;
	}
	
	
	/*
	*  update_field()
	*
	*  This filter is appied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*  @param	$post_id - the field group ID (post_type = acf)
	*
	*  @return	$field - the modified field
	*/

	function update_field( $field, $post_id )
	{
		// Note: This function can be removed if not used
		return $field;
	}

	
}


// create field
new acf_field_post_type_selector();

?>
