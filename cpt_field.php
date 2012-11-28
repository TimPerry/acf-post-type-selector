<?php

/*
 *	Advanced Custom Fields - New field template
 *	
 *	Create your field's functionality below and use the function:
 *	register_field($class_name, $file_path) to include the field
 *	in the acf plugin.
 *
 *	Documentation: 
 *
 */
 
 
class PostTypeSelector extends acf_Field
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
	
	function __construct( $parent )
	{
		// do not delete!
    	parent::__construct( $parent );
    	
    	// set name / title
    	$this->name = 'post_type_selector'; // variable name (no spaces / special characters / etc)
		$this->title = __("Post Type Selector",'acf'); // field label (Displayed in edit screens)
		
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

		// defaults
		$field['select_type'] = isset($field['select_type']) ? $field['select_type'] : '';
		
		?>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Selector Type",'acf'); ?></label>
			</td>
			<td>
				<?php 
				$this->parent->create_field(array(
					'type'	=>	'select',
					'name'	=>	'fields['.$key.'][select_type]',
					'value'	=>	$field['select_type'],
					'choices'	=>	array( 'Select', 'Radio', 'Checkboxes' ),
				));
				?>
			</td>
		</tr>
		<?php

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

		$checked = array( );
		$post_types = get_post_types( '','objects' );

		switch ( $field[ 'select_type' ] ) {
		
			case PostTypeSelector::SELECTOR_TYPE_SELECT:
					
				echo '<select id="' . $field[ 'name' ] . '" class="' . $field[ 'class' ] . '" name="' . $field[ 'name' ] . '">';
				
				$checked[ $field[ 'value' ] ] = 'selected="selected"';
				
				foreach( $post_types as $post_type ) {
				
					echo '<option ' . $checked[ $post_type->name ] . ' value="' . $post_type->name . '">' . $post_type->labels->name . '</option>';
				
				}
				
				echo '</select>';
				
			break;
			
			case PostTypeSelector::SELECTOR_TYPE_RADIO:
				
				echo '<ul class="radio_list radio horizontal">';
				
				$checked[ $field[ 'value' ] ] = 'checked="checked"';
				
				foreach( $post_types as $post_type ) {
				
					echo '<li><input type="radio" ' . $checked[ $post_type->name ] . ' class="' . $field[ 'class' ] . '" name="' . $field[ 'name' ] . '" value="' . $post_type->name . '"><label>' . $post_type->labels->name . '</label></li>';
				
				}
				
				echo '</ul>';
				
			
			break;
			
			case PostTypeSelector::SELECTOR_TYPE_CHECKBOXES:
			
				echo '<ul class="checkbox_list checkbox">';
				
				foreach(  $field[ 'value' ] as $val ) {
				
					$checked[ $val ] = 'checked="checked"';
				
				}
				
				foreach( $post_types as $post_type ) {
				
					echo '<li><input type="checkbox" ' . $checked[ $post_type->name ] . ' class="' . $field[ 'class' ] . '" name="' . $field[ 'name' ] . '[]" value="' . $post_type->name . '"><label>' . $post_type->labels->name . '</label></li>';
				
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
		// get value
		$value = parent::get_value($post_id, $field);
		
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
		// get value
		$value = $this->get_value($post_id, $field);
		
		// return value
		return $value;

	}
	
}

?>