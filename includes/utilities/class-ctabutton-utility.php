<?php
/**
 * CTA button utility classes
 * 
 * @since 1.0.0
 * 
 * @package CTAButton
 */
class CTAButton_Utility {

	/**
	 * List sliders list here
	 */
	public static $sliders = array(
		array(
			'id'			=> 'call',
			'title' 		=> 'Call or Text My Business',
			'icon'			=> 'dashicons-phone',
			'description'	=> 'Add a Call now button on any page to convert website visitors into customers with a click.',
		),
		array(
			'id'			=> 'message',
			'title' 		=> 'Send Message',
			'icon'			=> 'dashicons-format-status',
			'description'	=> 'Drive visitors to book a consultations with you or your team',
		),
	);

	protected static $allowed_tags = null;

	public static function get_allowed_tags() {
		if (self::$allowed_tags === null) {
			self::$allowed_tags = array_merge(wp_kses_allowed_html('post'), [
				'form' => [
					'action' => true,
					'method' => true,
					'id' => true,
					'class' => true,
					'name' => true,
				],
				'input' => [
					'type' => true,
					'name' => true,
					'value' => true,
					'id' => true,
					'class' => true,
					'checked' => true,
					'disabled' => true,
					'readonly' => true,
					'placeholder' => true,
					'required' => true,
					'autocomplete' => true,
					'min' => true,
					'max' => true,
					'step' => true,
					'pattern' => true,
					'size' => true,
				],
				'label' => [
					'for' => true,
					'class' => true,
					'id' => true,
				],
				'select' => [
					'name' => true,
					'id' => true,
					'class' => true,
					'multiple' => true,
					'required' => true,
				],
				'option' => [
					'value' => true,
					'selected' => true,
					'disabled' => true,
				],
				'textarea' => [
					'name' => true,
					'id' => true,
					'class' => true,
					'rows' => true,
					'cols' => true,
					'placeholder' => true,
					'required' => true,
				],
				'button' => [
					'type' => true,
					'name' => true,
					'value' => true,
					'id' => true,
					'class' => true,
					'disabled' => true,
				],
				'fieldset' => [
					'id' => true,
					'class' => true,
					'disabled' => true,
					'form' => true,
					'name' => true,
				],
				'legend' => [
					'id' => true,
					'class' => true,
				],
				'br' => [],
			]);
		}
		return self::$allowed_tags;
	}

	/**
	 * Check if woocommerce is active
	 */
	public static function is_woo_active() {
		return class_exists( 'woocommerce' );
	}

    /**
	 * Format dropdown options
	 *
	 * @param $list List of items
	 * @param $value_key Key to use on value
	 * @param $label_key Key to use on label
	 * @return Array
	 */
	public static function get_select_options($list, $value_key, $label_key) {
		$options = array();

		foreach($list as $row) {
			$options[$row->$value_key] = $row->$label_key; 
		}

		return $options;
	}

    /**
	 *  Filters pages if already selected in any of the slider
	 * 
	 * @since 1.0.0
	 * @return array $selected_pages 
	 */
	public static function get_selected_pages( $plugin_name, $current_slider = null ) {
		$query = array(
			'post_type' => 'page',
    		'post_status' => 'publish'
		);
		$pages = get_pages( $query );

		$selected_pages = array();

		foreach( self::$sliders as $slider) {
			// Get the slider selected pages
			$option_name 	 = $plugin_name.'-settings-' . $slider['id'];
			$slider_settings = get_option( $option_name );

			if ( isset( $slider_settings['pages'] ) && $slider['id'] !== $current_slider ) {				
				$slider_pages	 = $slider_settings['pages'];

				if ( is_array( $slider_pages ) ) {
					$selected_pages = array_merge( $selected_pages, $slider_pages );
				}
			}
		}
		
		return $selected_pages;
	}

    /**
	 * Sandbox our dropdown.
	 *
	 * @since    1.0.0
	 */
	public static function render_fields( $params ) {
		
		$option_name	= $params['option_name'];
        $name    		= "{$option_name}[{$params['uid']}]";
		$values  		= get_option($params['option_name']); // Get the current value, if there is one
        $value   		= isset($values[$params['uid']]) ? $values[$params['uid']] : '';
		$id				= $option_name . '-' . $params['uid'];
		$slider_id 		= explode('-', $option_name)[2];
		
        if( $value === '' && isset($params['default'])) { // If no value exists
	        $value = $params['default']; // Set to our default
	    }

		// Populate attributes
		$attributes = '';

		if( isset($params['attributes']) ) {
			foreach($params['attributes'] as $key => $attribute) {
				$attributes .= ' ' . $key . '="' . $attribute . '" ';
			}
		}

		switch($params['type']) {
			case 'text':
				 printf( 
					'<input name="%1$s" id="%4$s" type="%2$s" value="%3$s" %5$s />', 
					esc_attr( $name ), 
					esc_attr( $params['type'] ), 
					esc_attr( $value ), 
					esc_attr( $id ), 
					wp_kses( $attributes, self::get_allowed_tags() ) 
				);
				break;
			case 'textarea':
				 printf( 
					'<textarea name="%1$s" id="%3$s" rows="5">%2$s</textarea>', 
					esc_attr( $name ), 
					esc_attr( $value ), 
					esc_attr( $id )
				);
				break;
			case 'select':
			case 'multiselect': // If it is a select dropdown
				$selected_pages = self::get_selected_pages( 'ctabutton', $slider_id );
				
		        if( ! empty ( $params['options'] ) && is_array( $params['options'] ) ) {
		            $options_markup = '';

					if ($params['type'] === 'multiselect') {
						foreach( $params['options'] as $key => $label ){
							$options_markup .= sprintf( 
								'<option value="%s" %s %s>%s</option>', 
								$key, 
								is_array($value) && in_array($key, $value) ? 'selected' : '', 
								is_array( $selected_pages ) && in_array( $key, $selected_pages ) ? 'disabled' : '', 
								$label 
							);
						}
					} else {
						foreach( $params['options'] as $key => $label ) {							
							$options_markup .= sprintf( 
								'<option value="%s" %s %s>%s</option>', 
								$key, 
								(string) $key === $value ? 'selected' : '', 
								is_array( $selected_pages ) && in_array( $key, $selected_pages ) ? 'disabled' : '', 
								$label 
							);
						}
					}

		            if( $params['type'] === 'multiselect' ) {
                        $attributes .= ' multiple="multiple" ';
                    }

                    if ( ! isset( $params['attributes']['class'] ) ) {
                    	$attributes .= ' class="select2" ';
                    }

                    if($params['type'] === 'select') {
		            	printf( 
							'<select name="%1$s" id="%4$s" %2$s>%3$s</select>', 
							esc_attr( $name ), 
							wp_kses( $attributes, self::get_allowed_tags() ), 
							wp_kses( $options_markup, self::get_allowed_tags() ), 
							esc_attr( $id )
						);
		            } else {
		            	printf( 
							'<select name="%1$s[]" id="%4$s" %2$s>%3$s</select>', 
							esc_attr( $name ), 
							wp_kses( $attributes, self::get_allowed_tags() ), 
							wp_kses( $options_markup, self::get_allowed_tags() ), 
							esc_attr( $id )
						);
		            }
		        }	
	        	break;
	        case 'radio':
            case 'checkbox':
                if( ! empty ( $params['options'] ) && is_array( $params['options'] ) ) {

                    $options_markup = '';
                    $iterator = 0;
                    foreach( $params['options'] as $key => $label ){
                        $iterator++;
                        if($params['type'] === 'radio') {
                        	$options_markup .= sprintf( '<label for="%1$s_%6$s"><input id="%7$s_%6$s" name="%1$s" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>', $name, $params['type'], $key, is_array($value) && in_array($key, $value) ? 'checked' : '', $label, $iterator, $id );
                        } else {
                        	$options_markup .= sprintf( '<label for="%1$s_%6$s"><input id="%7$s_%6$s" class="wppd-ui-toggle" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>', $name, $params['type'], $key, is_array($value) && in_array($key, $value) ? 'checked' : '', $label, $iterator, $id );
                        }
                    }

					printf( 
						'<fieldset class="%s">%s</fieldset>', 
						esc_attr ( $params['type'] ) . '-container', 
						wp_kses( $options_markup, self::get_allowed_tags() )
					);
                }
                break;
		}

		// Show helper teext if its set
		if ( isset( $params['description']  )) {
			printf( '<p class="ctabutton-description">%s</p>', esc_html( $params['description'] ) );
		}
	}

	/**
	 * Format product dropdown options
	 *
	 * @param $list List of products
	 * 
	 * @return Array
	 */
	public static function get_product_list_options($list) {
		$options = array();

		foreach($list as $row) {
			$options[$row->get_id()] = $row->get_title(); 
		}

		return $options;
	}

	/**
	 * Get the slider details given the slider id
	 */
	public static function get_slider_data( $slider_id ) {
		$sliders 	 = self::$sliders;
		$slider_key  = array_search( $slider_id, array_column( $sliders, 'id' ) );
		$slider_data = $sliders[$slider_key];

		return $slider_data;
	}

	/**
	 * Group slider fields by type
	 */
	public static function group_slider_values_by_type( $slider_values ): array {
		$types = array();

		if ( is_array( $slider_values) ) {
			foreach( $slider_values as $key => $value ) {
				$keyItems = explode( '-', $key );
				$type     = $keyItems[0];
				$typeKey  = $keyItems[1];

				$types[$type][$typeKey] = $value;
			}
		}

		return $types;
	}

	/**
	 * Check if the slider is active
	 */
	public static function isActive( $slider_id ) {
		$sliders 	 = self::$sliders;
		$slider_key  = array_search( $slider_id, array_column( $sliders, 'id' ) );
		
		return false === $slider_key ? false : true;
	}

	/**
	 * 
	 */
	public static function wp_kses_custom( $value ) {
		

		return wp_kses( $value, $allowed_tags );
	}
}