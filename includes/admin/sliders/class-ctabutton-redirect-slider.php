<?php
/**
 * Redirect settings
 */

class CTAButton_Redirect_Slider implements CTAButton_Slider_Settings_Interface {

	private $plugin_name;

    private $slider_id = 'redirect';

    public function __construct($plugin_name) {
		$this->plugin_name = $plugin_name;

		$this->register_settings();
	}

    public function register_settings() {

		$option_name = $this->plugin_name.'-settings-' . $this->slider_id;
		$section	 = $this->plugin_name . '-settings-redirect-section';

		// Here we are going to add a section for our setting.
		add_settings_section(
			$section,
			'',
			array( $this, 'sandbox_add_settings_section' ),
			$this->plugin_name . '-settings-redirect'
		);

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
				'uid' => 'external',
				'label' => __( 'Redirect to external page', 'cta-button' ),
				'section' => $section,
				'type' => 'checkbox',
				'options' => array(
					1 => ''
				)
			),
			array(
				'uid' => 'title',
				'label' => __( 'Title', 'cta-button' ),
				'section' => $section,
				'type' => 'text',
				'options' => false,
				'default' => 'Nice Title'
			),

			array(
				'uid' => 'desc',
				'label' => __( 'Description', 'cta-button' ),
				'section' => $section,
				'type' => 'textarea',
				'options' => false,
			),

			array(
				'uid' => 'pages',
				'label' => __( 'Select Pages to show slider', 'cta-button' ),
				'section' => $section,
				'type' => 'multiselect',
				'options' => CTAButton_Utility::get_select_options( $pages, 'ID', 'post_title'),
			),

			array(
				'uid' => 'page',
				'label' => __( 'Redirect to page', 'cta-button' ),
				'section' => $section,
				'type' => 'select',
				'options' => CTAButton_Utility::get_select_options($pages, 'ID', 'post_title'),
			),

			array(
				'uid' => 'link',
				'label' => __( 'External link', 'cta-button' ),
				'section' => $section,
				'type' => 'text',
				'options' => false,
			),
			array(
				'uid' => 'redirect-to-tab',
				'label' => __( 'Open in new tab?', 'cta-button' ),
				'section' => $section,
				'type' => 'checkbox',
				'options' => array(
					1 => ''
				)
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

	/**
	 * Sandbox our section for the settings.
	 *
	 * @since    1.0.0
	 */
	public function sandbox_add_settings_section($arg) {

		return;
	}
}