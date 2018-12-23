<?php

class acf_field_post_type_selector extends acf_field {

	const SELECTOR_TYPE_SELECT = 0;
	const SELECTOR_TYPE_RADIO = 1;
	const SELECTOR_TYPE_CHECKBOXES = 2;

	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options

	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function __construct() {

		// vars
		$this->name = 'post_type_selector';
		$this->label = __('Post Type Selector');
		$this->category = __("Relational",'acf'); // Basic, Content, Choice, etc
		$this->defaults = array(
			'select_type' => self::SELECTOR_TYPE_CHECKBOXES,
		);


		// do not delete!
    	parent::__construct();


    	// settings
		$this->settings = array(
			'path' => plugin_dir_path( __FILE__),
			'dir' => plugin_dir_url( __FILE__ ),
			'version' => '1.0.0'
		);

	}


	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field_settings( $field ) {

		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/


		// defaults?
		$field = array_merge($this->defaults, $field);

		acf_render_field_setting( $field, array(
			'label'			=> __("Selector Type",'acf'),
			'instructions'	=> 'How would you like to select the post type?',
			'type'			=> 'select',
			'name'			=> 'select_type',
			'choices' => array(
				acf_field_post_type_selector::SELECTOR_TYPE_SELECT => __( 'Select' ),
				acf_field_post_type_selector::SELECTOR_TYPE_RADIO => __( 'Radio' ),
				acf_field_post_type_selector::SELECTOR_TYPE_CHECKBOXES => __( 'Checkboxes' ),
			)
		));
	}



	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field( $field ) {
		// defaults?
		$field = array_merge( $this->defaults, $field );

		$post_types = acf_get_pretty_post_types();

		/**
		 * Filters the array of post types.
		 *
		 * @since 1.0.1
		 *
		 * @param array $post_types List of post types.
		 * @param array $field      The field being rendered.
		 */
		$post_types = apply_filters( 'post_type_selector_post_types', $post_types, $field );

		// not required: add empty/none value
		if (!$field['required']) {
			$post_types = [ '' => __("None", 'acf') ] + $post_types;
		}

		// create Field HTML
		$checked = array( );

		switch ( $field[ 'select_type' ] ) {

			case acf_field_post_type_selector::SELECTOR_TYPE_SELECT:

				echo '<select id="' . $field[ 'name' ] . '" class="' . $field[ 'class' ] . '" name="' . $field[ 'name' ] . '">';

				$checked[ $field[ 'value' ] ] = 'selected="selected"';

				foreach( $post_types as $post_type => $post_type_label ) {
					echo '<option ' . (isset($checked[ $post_type ]) ? $checked [ $post_type ] : null) . ' value="' . $post_type . '">' . $post_type_label . '</option>';
				}

				echo '</select>';

			break;

			case acf_field_post_type_selector::SELECTOR_TYPE_RADIO:

				echo '<ul class="radio_list radio horizontal">';

				$checked[ $field[ 'value' ] ] = 'checked="checked"';

				foreach( $post_types as $post_type => $post_type_label ) {

				?>

					<li><label><input type="radio" <?php echo ( isset( $checked[ $post_type ] ) ) ? $checked[ $post_type] : null; ?> class="<?php echo $field[ 'class' ]; ?>" name="<?php echo $field[ 'name' ]; ?>" value="<?php echo $post_type; ?>"> <?php echo $post_type_label; ?></label></li>

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

				foreach( $post_types as $post_type => $post_type_label ) {

				?>

					<li><label><input type="checkbox" <?php echo ( isset( $checked[ $post_type ] ) ) ? $checked[ $post_type] : null; ?> class="<?php echo $field[ 'class' ]; ?>" name="<?php echo $field[ 'name' ]; ?>[]" value="<?php echo $post_type; ?>"><?php echo $post_type_label; ?></label></li>
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
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
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
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function input_admin_head() {
		// Note: This function can be removed if not used
	}


	/*
   	*  input_form_data()
   	*
   	*  This function is called once on the 'input' page between the head and footer
   	*  There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and
   	*  'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
   	*  seen on comments / user edit forms on the front end. This function will always be called, and includes
   	*  $args that related to the current screen such as $args['post_id']
   	*
   	*  @type	function
   	*  @date	6/03/2014
   	*  @since	5.0.0
   	*
   	*  @param	$args (array)
   	*  @return	n/a
   	*/

   	/*

   	function input_form_data( $args ) {



   	}

   	*/


	/*
	*  input_admin_footer()
	*
	*  This action is called in the admin_footer action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_footer)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function input_admin_footer() {



	}

	*/


	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function field_group_admin_enqueue_scripts() {

	}

	*/


	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function field_group_admin_head() {

	}

	*/


	/*
	*  load_value()
	*
	*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/


	function load_value( $value, $post_id, $field ) {

		return $value;

	}



	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is saved in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/

	function update_value( $value, $post_id, $field ) {

		return $value;

	}


	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
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
	*  validate_value()
	*
	*  This filter is used to perform validation on the value prior to saving.
	*  All values are validated regardless of the field's required setting. This allows you to validate and return
	*  messages to the user if the value is not correct
	*
	*  @type	filter
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$valid (boolean) validation status based on the value and the field's required setting
	*  @param	$value (mixed) the $_POST value
	*  @param	$field (array) the field array holding all the field options
	*  @param	$input (string) the corresponding input name for $_POST value
	*  @return	$valid
	*/

	/*

	function validate_value( $valid, $value, $field, $input ){

		// Basic usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = false;
		}


		// Advanced usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = __('The value is too little!','acf-cf7'),
		}


		// return
		return $valid;

	}

	*/


	/*
	*  delete_value()
	*
	*  This action is fired after a value has been deleted from the db.
	*  Please note that saving a blank value is treated as an update, not a delete
	*
	*  @type	action
	*  @date	6/03/2014
	*  @since	5.0.0
	*
	*  @param	$post_id (mixed) the $post_id from which the value was deleted
	*  @param	$key (string) the $meta_key which the value was deleted
	*  @return	n/a
	*/

	/*

	function delete_value( $post_id, $key ) {



	}

	*/


	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/

	function load_field( $field )
	{
		// Note: This function can be removed if not used
		return $field;
	}


	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/

	function update_field( $field )
	{
		// Note: This function can be removed if not used
		return $field;
	}


	/*
	*  delete_field()
	*
	*  This action is fired after a field is deleted from the database
	*
	*  @type	action
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	n/a
	*/

	/*

	function delete_field( $field ) {



	}

	*/


}


// create field
new acf_field_post_type_selector();

?>
