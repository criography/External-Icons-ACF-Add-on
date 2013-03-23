<?php

  /*
	 * Advanced Custom Fields - External Icons Selector Field add-on
	 *
	 *
	 * Contributors: initial build by Marek Lenik @criography
	 *
	 * Tags: acf, acf add-on, icons, custom field, external images, wordpress
	 * Requires at least: 3.0
	 * Tested up to: 3.5.8.1
	 * Stable tag: 0.0
	 *
	 * More info at: https://github.com/criography/External-Icons-ACF-Add-on
	 *
	 */

if( class_exists('acf_Field') && !class_exists('Icons_field') ){
	class Icons_field extends acf_Field{

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
	      $this->name = 'icons'; // variable name (no spaces / special characters / etc)
			$this->title = __("Icons",'acf'); // field label (Displayed in edit screens)

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
		*	@author Marek Lenik
		*	@since 2.2.0
		*
		*-------------------------------------------------------------------------------------*/

		function create_options($key, $field){

			// vars
			$defaults = array(
				'iconspath'		=>	'/',
				'filetypes'		=>	false,
				'multichoice'	=>	0,
			);

			$field = array_merge($defaults, $field);

			// set default values
			$field['iconspath']   = !empty($field['iconspath']) ? $field['iconspath'] : $defaults['iconspath'];
			$field['multichoice'] = !empty($field['multichoice']) ? $field['multichoice'] : $defaults['multichoice'];




			// render field's options
			?>
			<tr class="field_option field_option_<?php echo $this->name; ?>">
				<td class="label">
					<label for=""><?php _e("Icons Path",'acf'); ?></label>
					<p class="description"><?php _e("Enter the path to your icons folder, relative to the site root, e.g.: '/_images/icons/'",'acf'); ?><br /></p>
				</td>
				<td>
					<?php

					do_action('acf/create_field', array(
						'type'	=>	'text',
						'name'	=>	'fields['.$key.'][iconspath]',
						'value'	=>	$field['iconspath']
					));

					?>
				</td>
			</tr>
			<tr class="field_option field_option_<?php echo $this->name; ?>">
				<td class="label">
					<label><?php _e("Filetypes",'acf'); ?></label>
					<p class="description"><?php _e("Select all file extensions to be accepted.<br/><br/><span style='color:#BC0B0B'>Please note:</span> all files should have lowercase extensions.",'acf'); ?></p>
				</td>
				<td>
					<?php

					do_action('acf/create_field', array(
						'type'    => 'checkbox',
						'name'    => 'fields[' . $key . '][filetypes]',
						'value'   => $field['filetypes'],
						'choices' => array(
							'jpg,jpeg'  => __("JPEGs (will recognise both .jpg and .jpeg)", 'acf'),
							'png'       => __("PNGs", 'acf'),
							'svg,svgz'       => __("SVGs (will recognise both .svg and .svgz)", 'acf'),
							'gif'       => __("GIFs", 'acf'),
							'bmp'       => __("BMPs", 'acf'),
							'ico'       => __("ICOs", 'acf')
						)
					));

					?>
				</td>
			</tr>

			<tr class="field_option field_option_<?php echo $this->name; ?>">
				<td class="label">
					<label><?php _e("Allow Multiple Choices?",'acf'); ?></label>
				</td>
				<td>
					<?php
					do_action('acf/create_field', array(
						'type'	=>	'radio',
						'name'	=>	'fields['.$key.'][multichoice]',
						'value'	=>	$field['multichoice'],
						'choices'	=>	array(
							1	=>	__("Yes",'acf'),
							0	=>	__("No",'acf'),
						),
						'layout'	=>	'horizontal',
					));
					?>
				</td>
			</tr>

			<tr class="field_option field_option_<?php echo $this->name; ?>">
				<td class="label">
					<label><?php _e("Output Value",'acf'); ?></label>
					<p class="description"><?php _e("<span style='color:#BC0B0B'>Please note:</span> if only one option is selected, the output will be served as a string; if more—as an array.",'acf'); ?></p>
				</td>
				<td>
					<?php
					do_action('acf/create_field', array(
						'type'     => 'checkbox',
						'name'     => 'fields[' . $key . '][output_value]',
						'value'    => $field['output_value'],
						'choices'  => array(
							'full_path'    => __("Full Path", 'acf'),
							'full_filename'=> __("Filename with extension", 'acf'),
							'css_class'    => __("CSS-safe string based on a filename without extension", 'acf'),
						),
						'layout'   => 'horizontal',
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


			// vars
			$hash = 'haxory-'.base64_encode(date('Y-m-d H:i:s'));
			$defaults = array(
				'iconspath'		=>	'/',
				'filetypes'		=>	false,
				'multichoice'	=>	0
			);


			// merge defaults with user defined settings
			$field          = array_merge($defaults, $field);
			if(!is_array($field['value'])){
				$field['value'] = array();
			}
			$field['value'] = array_merge(array($hash => 1), $field['value']);

			// composite, server absolute path to all the icons
			$iconURI  = str_replace('\\', '/', trim($field['iconspath']));
			$iconPath = realpath( get_theme_root() . DIRECTORY_SEPARATOR .
														get_template(). DIRECTORY_SEPARATOR .
														str_replace(  array('\\', '/'), DIRECTORY_SEPARATOR, trim($field['iconspath']) )
													).DIRECTORY_SEPARATOR;




			/* check if the given path exists
			* ---------------------------------------	*/
			if(!is_dir($iconPath)){
				echo  '<div class="form-invalid" style="border-radius: 3px; border:1px solid #cc0000"><p>'.
								'<strong>Something\'s going on with your ACF\'s '.$field['label'].' Field!:</strong><br/>'.
								'The <em>icons path</em> you specified in options is not pointing to any existing folder.'.
							'</p></div>';
			}



			/* check if any filetypes were selected
			* ---------------------------------------	*/
			elseif(empty($field['filetypes'])){
				echo  '<div class="form-invalid" style="border-radius: 3px; border:1px solid #cc0000"><p>'.
								'<strong>Something\'s going on with your ACF\'s '.$field['label'].' Field!:</strong><br/>'.
								'No <em>file types</em> were selected in your options.'.
							'</p></div>';
			}



			/* otherwise attempt directory scan
			* ---------------------------------------	*/
			else{

						$icons = glob( $iconPath. '*.{'. implode(',', $field['filetypes']) . '}', GLOB_BRACE	);




						/* if nothing was found
						* ---------------------------------------	*/
						if(count($icons) === 0){
							echo  '<div class="form-invalid" style="border-radius: 3px; border:1px solid #cc0000"><p style="margin: 1em;">'.
											'<strong>Oops! I couldn\'t find any images of given type in the path you specified in the options.</strong><br/'.
											'Make sure you selected right <em>File Types</em> and the files are actually in that location.'.
										'</p></div>';
						}




						/* if results were found,
						*  loop through them and generate HTML
						* ---------------------------------------	*/
						else{

							$i              = 0;
							$totalSelected  = 0;
							$iconHTML       = '<ul class="acf-icon-list" class="radio_list ' . $field['class'] . ' ' . $field['layout'] . '" data-acf-icons-multiple="'.$field['multichoice'].'">';

							foreach ($icons as $icon){

								$i++;
								$iconName  = basename($icon);                                                      /* icon filename */;
								$iconImage = '<img src="' . $iconURI . $iconName . '" alt="" class="acf-icon"/>'   /* icon container */;


								// if there is no value and this is the first of the choices and there is no "0" choice, select this on by default
								// the 0 choice would normally match a no value. This needs to remain possible for the create new field to work.
								if($i == 1){

									$iconHTML.= '<li class="acf-iconEntry acf-removed">'.
																	'<input id="'. $field['id'] .'-'.$hash.'" '.
																					'class="acf-placebo-haxor" '.
																					'type="checkbox" '.
																					'name="'. $field['name'] .'['.$hash.']" '.
																					'value="0" '.
																					'checked="checked" data-checked="checked" '.
																	'/>'.
															'</li>';
								}


								// set checked attribute for all existing choices
								$selected = '';

								if(in_array($iconName, array_keys($field['value'])) && ( ($field['multichoice']==0 && $totalSelected===0) || $field['multichoice']==1) ){
									$selected = 'checked="checked" data-checked="checked" ';
									$totalSelected++;
								}


								//generate HTML for each of the found icons
								$iconHTML.= '<li class="acf-iconEntry"><label class="acf-iconLabel">'.
																'<input class="acf-iconInput acf-removed" '.
																				'id="'. $field['id'] .'-'. $iconName . '" '.
																				'type="checkbox" '.
																				'name="'. $field['name'] .'['.$iconName.']" '.
																				'value="1" '.
																				$selected .
																'/>'.
																$iconImage.
														'</label></li>';
							}

							// output everything
							echo $iconHTML.'</ul>';

						}




			}

		}


		/*--------------------------------------------------------------------------------------
		*
		*	admin_head	*	- this function is called in the admin_head of the edit screen where your field
		*	is created. Use this function to create css and javascript to assist your
		*	create_field() function.
		*
		*	@author Elliot Condon
		*	@since 2.2.0
		*
		*-------------------------------------------------------------------------------------*/

		function admin_head(){

					?><style type="text/css">
					 .acf-icon-list{
						 width   : 100%;
						 margin  : 0 -.5em;
						 padding : 0;
						 display : block;
					 }

					  .acf-iconEntry{
						  display    : inline-block;
						  list-style : none;
						  margin     : .5em;
						  width      : 6em;
						  height     : 6em;
					  }

							.acf-iconLabel{
								display : block;
								width   : 100%;
								height  : 100%;
							}

						    .acf-removed{
							    position : absolute;
							    left     : -9999px;
						    }

							    .acf-icon{
								    position          : relative;
								    box-sizing        : border-box;
								    -moz-box-sizing   : border-box;
								    box-shadow        : inset 0 2px 2px rgba(0, 0, 0, .335);
								    width             : 100%;
								    height            : auto;
								    padding           : .65em;
								    border            : 1px dotted transparent;
								    border-radius     : 50%;
								    filter            : url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'saturate\' values=\'.075\'/></filter></svg>#grayscale");
								    -webkit-filter    : grayscale(75%);
								    -webkit-transform : scale(.9);
								    transform         : scale(.9);
							    }


								 .acf-iconInput:checked + .acf-icon{
									 padding           : .55em;
									 top               : -.275em;
									 box-shadow        : 0 2px 2px rgba(0, 0, 0, .335);
									 -webkit-transform : none;
									 transform         : none;
									 filter            : url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'saturate\' values=\'1\'/></filter></svg>#grayscale");
									 -webkit-filter    : grayscale(0%);
								 }

					</style>
					<script>
						(function(document, $){

							$(document).ready(function() {

								$('.acf-iconInput').on('change', function(){
									var parentList = $(this).parents('.acf-icon-list');

									if( parentList.data('acf-icons-multiple')==0 ){
										parentList.find('.acf-iconInput').not($(this)).prop('checked', false);
									}

								});

							});

						})(document, jQuery);



					</script>
					<?php


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

		function update_value($post_id, $field, $value){
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
			// get value
			$value = parent::get_value($post_id, $field);


			// return value
			return $value;
		}











		/*--------------------------------------------------------------------------------------
		*
		*	get_icon_output_values
		*	- called from get_value_for_api method in order to generate output for each icon
		*   based on 'output_value' choices.
		*
		*	@params
		*	- $entry (string) - current icon filename
		*	- $field (array) - complete field array
		*
		*	@author Marek Lenik
		*	@since 3.5.1
		*
		*-------------------------------------------------------------------------------------*/
		private function get_icon_output_values($entry, $field){
			if(!empty($entry) && !empty($field)){

				$output = array();

				if( in_array('full_path', $field['output_value']) ){
					$output['full_path'] = $field['iconspath'].$entry;
				}


				if( in_array('full_filename', $field['output_value']) ){
					$output['full_filename'] = $entry;
				}


				if( in_array('css_class', $field['output_value']) ){
					$fileExt = str_replace(',', '|', implode('|', $field['filetypes']));
					$output['css_class'] = sanitize_title( preg_replace ('/^(.+?)(\.('. $fileExt .'))$/i', '$1', $entry) );
				}

				//stringify if only 1 result;
				if( count($output) === 1 ){
					$output = reset($output);
				}

				return $output;

			}else{
				return false;
			}
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

		function get_value_for_api($post_id, $field){

			$output = '';

			// get value and ignore the 1st haxory field
			$entries = array_slice($this->get_value($post_id, $field), 1);

			//loop through all returned icons
			foreach (array_keys($entries) as $entry){
				$output[] = self::get_icon_output_values( $entry, $field );

			}


			//stringify if only 1 result;
			if( count($output) === 1 ){
				$output = reset($output);
			}

			return $output;

		}

	}
}
?>
