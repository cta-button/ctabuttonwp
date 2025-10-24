<?php

class CTAButton_Call_Slider implements CTAButton_Slider_Settings_Interface {

	private $plugin_name;

    private $slider_id = 'call';

    public function __construct($plugin_name) {
		$this->plugin_name = $plugin_name;

		$this->register_settings();
    }

    public function old_register_settings() {

		$option_name = $this->plugin_name.'-settings-' . $this->slider_id;

		// Here we are going to add a section for our setting.
        $section = $this->plugin_name . '-settings-' . $this->slider_id . '-section';

		add_settings_section(
			$section,
			'', 
			array( $this, 'sandbox_add_settings_section' ),
			$this->plugin_name . '-settings-' . $this->slider_id
		);

		$blog_categories = get_categories();

		$query = array(
			'post_type' => 'page',
    		'post_status' => 'publish'
		);
		$pages = get_pages( $query );
		
		$fields = array(
			array(
				'uid' => 'enabled',
				'label' => __( 'Enable/Disable', 'cta-button' ),
				'section' => $section,
				'type' => 'checkbox',
				'options' => array(
					1 => ''
				),
			),			

			array(
				'uid' =>'phone',
				'label' => __( 'Phone', 'cta-button' ),
				'section' => $section,
				'type' => 'text',
				'options' => false,
				'default' => '',
				'description'	=> 'Select country and add your local number.',
				'attributes'    => array(
					'autocomplete'  => 'on',
					'class'			=> 'intl-tel-input'
				)
			),

			array(
				'uid' => 'pages',
				'label' => __( 'Select Pages to show slider', 'cta-button' ),
				'section' => $section,
				'type' => 'multiselect',
				'options' => CTAButton_Utility::get_select_options( $pages, 'ID', 'post_title'),
			),

			array(
				'uid' => 'categories',
				'label' => __( 'Select Categories to show slider', 'cta-button' ),
				'section' => $section,
				'type' => 'multiselect',
				'options' => CTAButton_Utility::get_select_options( $blog_categories, 'cat_ID', 'name'),
			),

			array(
				'uid'           =>'btn_text',
				'label'         => __( 'Button Text', 'cta-button' ),
				'section'       => $section,
				'type'          => 'text',
				'options'       => false,
				'default'       => '',
			),
	
			array(
				'uid' 		 => 'btn_color',
				'label' 	 => __( 'Button Background Color', 'cta-button' ),
				'section'	 => $section,
				'type' 		 => 'text',
				'options' 	 => null,
				'attributes' => array( 'class' => 'color-picker' )
			),
	
			array(
				'uid'       => 'btn_type',
				'label'     => __( 'Button Type', 'cta-button' ),
				'section'   => $section,
				'type'      => 'select',
				'options'   => array(
					'square'  => 'Square',
					'rounded' => 'Slightly Rounded',
					'capsule' => 'Capsule'
				),
				'attributes' => array( 'class' => 'select' )
			),
		);

		foreach ($fields as $field) {
			$field['option_name'] = $option_name;

			add_settings_field( 
				$option_name . '-' . $field['uid'], 
				$field['label'], 
				array( 'CTAButton_Utility', 'render_fields' ), 
				$this->plugin_name . '-settings-' . $this->slider_id, 
				$field['section'], 
				$field 
			);
		}

		register_setting(
			$this->plugin_name . '-settings-' . $this->slider_id,
			$option_name,
			array(
				'type' => 'string', 
				'sanitize_callback' => 'sanitize_text_field',
				'default' => NULL,
			)
		);
	}

	public function ctabutton_sanitize_settings($input) {
		// if (is_array($input)) {
		// 	return array_map('sanitize_text_field', $input);
		// }
		// return sanitize_text_field($input);
		return $input;
	}
	
	public function register_settings() {

		$group_name  = $this->plugin_name.'-settings-' . $this->slider_id;
		$option_name = $this->plugin_name.'-settings-' . $this->slider_id;		

		register_setting(
			$group_name,
			$option_name,
			array(
				'type' => 'array',
    			'sanitize_callback' => [$this, 'ctabutton_sanitize_settings'],
				'default' => NULL,
			)
		);
	}

	public function render() {
		$option_name = $this->plugin_name.'-settings-' . $this->slider_id;

		// Here we are going to add a section for our setting.
        $section = $this->plugin_name . '-settings-' . $this->slider_id . '-section';

		add_settings_section(
			$section,
			'', 
			array( $this, 'sandbox_add_settings_section' ),
			$this->plugin_name . '-settings-' . $this->slider_id
		);

		$blog_categories = get_categories();

		$query = array(
			'post_type' => 'page',
    		'post_status' => 'publish'
		);
		$pages = get_pages( $query );
		
		$types = array(
			'call' => array(
				array(
					'uid' => 'call-enabled',
					'label' => __( 'Enable/Disable', 'cta-button' ),
					'section' => $section,
					'type' => 'checkbox',
					'options' => array(
						1 => ''
					),
				),			

				array(
					'uid' =>'call-phone',
					'label' => __( 'Phone', 'cta-button' ),
					'section' => $section,
					'type' => 'text',
					'options' => false,
					'default' => '',
					'description'	=> 'Select country and add your local number.',
					'attributes'    => array(
						'autocomplete'  => 'on',
						'class'			=> 'intl-tel-input'
					)
				),

				array(
					'uid' => 'call-pages',
					'label' => __( 'Select Pages to show slider', 'cta-button' ),
					'section' => $section,
					'type' => 'multiselect',
					'options' => CTAButton_Utility::get_select_options( $pages, 'ID', 'post_title'),
				),

				array(
					'uid' => 'call-categories',
					'label' => __( 'Select Categories to show slider', 'cta-button' ),
					'section' => $section,
					'type' => 'multiselect',
					'options' => CTAButton_Utility::get_select_options( $blog_categories, 'cat_ID', 'name'),
				),

				array(
					'uid'           =>'call-btntext',
					'label'         => __( 'Button Text', 'cta-button' ),
					'section'       => $section,
					'type'          => 'text',
					'options'       => false,
					'default'       => '',
				),
		
				array(
					'uid' 		 => 'call-btncolor',
					'label' 	 => __( 'Button Background Color', 'cta-button' ),
					'section'	 => $section,
					'type' 		 => 'text',
					'options' 	 => null,
					'attributes' => array( 'class' => 'color-picker' )
				),
		
				array(
					'uid'       => 'call-btntype',
					'label'     => __( 'Button Type', 'cta-button' ),
					'section'   => $section,
					'type'      => 'select',
					'options'   => array(
						'square'  => 'Square',
						'rounded' => 'Slightly Rounded',
						'capsule' => 'Capsule'
					),
					'attributes' => array( 'class' => 'select' )
				),
			),
			'sms'  => array(
				array(
					'uid' => 'sms-enabled',
					'label' => __( 'Enable/Disable', 'cta-button' ),
					'section' => $section,
					'type' => 'checkbox',
					'options' => array(
						1 => ''
					),
				),			
				array(
					'uid' 			=>'sms-phone',
					'label' 		=> __( 'Phone', 'cta-button' ),
					'section' 		=> $section,
					'type' 			=> 'text',
					'options' 		=> false,
					'default' 		=> '',
					'description'	=> 'Select country and add your local number.',
					'attributes'  	=> array(
						'class'	=> 'intl-tel-input'
					) 
				),
	
				array(
					'uid' => 'sms-pages',
					'label' => __( 'Select Pages to show slider', 'cta-button' ),
					'section' => $section,
					'type' => 'multiselect',
					'options' => CTAButton_Utility::get_select_options( $pages, 'ID', 'post_title'),
				),
	
				array(
					'uid'           =>'sms-btntext',
					'label'         => __( 'Button Text', 'cta-button' ),
					'section'       => $section,
					'type'          => 'text',
					'options'       => false,
					'default'       => '',
				),
		
				array(
					'uid' 		 => 'sms-btncolor',
					'label' 	 => __( 'Button Background Color', 'cta-button' ),
					'section'	 => $section,
					'type' 		 => 'text',
					'options' 	 => null,
					'attributes' => array( 'class' => 'color-picker' )
				),

				array(
					'uid'       => 'sms-btntype',
					'label'     => __( 'Button Type', 'cta-button' ),
					'section'   => $section,
					'type'      => 'select',
					'options'   => array(
						'square'  => 'Square',
						'rounded' => 'Slightly Rounded',
						'capsule' => 'Capsule'
					),
					'attributes' => array( 'class' => 'select' )
				),
			)
		);

		$slider_data = CTAButton_Utility::get_slider_data( $this->slider_id );
		
		?>
		<div id="wrap">
			<h1><?php echo esc_html( $slider_data['title'] ); ?></h1>
			<a href="<?php echo esc_attr( menu_page_url( 'ctabutton', false ) ); ?>"><- Back to Dashboard</a>
			<form class="ctabutton-form" method="post" action="options.php">        
				<?php settings_fields( 'ctabutton-settings-' . $this->slider_id ); ?>
				<div class="tabs" style="margin-top: 0.5em">
					<?php foreach( $types as $type => $fields ) { ?>
					<div class="tab">
						<input type="radio" id="<?php echo esc_attr( $type ); ?>-radio" name="type">
						<label for="tab-1" class="tab-label"><?php echo esc_html( ucfirst( $type ) ); ?></label>
						
						<div class="tab-content" id="<?php echo esc_attr( $type ); ?>-tab">
							<table class="form-table" role="presentation"><tbody>
							<?php
							foreach ($fields as $field) {
								$field['option_name'] = $option_name;
								?>
								<tr>
									<th scope="row"><?php echo esc_html( $field['label'] ); ?></th>
									<td>
										<?php CTAButton_Utility::render_fields( $field ); ?>
									</td>
								</tr>
								<?php
							}
							?>
							</tbody></table>
							<p class="submit">
								<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
							</p> 
						</div>
					</div>
						
					<?php } ?>
				</div>
			</form>
		</div>
		<?php
	}

	/**
	 * Sandbox our section for the settings.
	 *
	 * @since    1.0.0
	 */
	public function sandbox_add_settings_section($arg) {

		return;
	}
}