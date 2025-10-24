<?php
/**
 * Social slider
 */
class CTAButton_Message_Slider implements CTAButton_Slider_Settings_Interface {

	private $plugin_name;

    private $slider_id = 'message';

    public function __construct( $plugin_name ) {
		$this->plugin_name = $plugin_name;

		$this->register_settings();
    }

    public function register_settings() {

		$group_name  = $this->plugin_name.'-settings-' . $this->slider_id;
		$option_name = $this->plugin_name.'-settings-' . $this->slider_id;

		register_setting(
			$group_name,
			$option_name,
			array(
				'type' => 'string', 
				'sanitize_callback' => 'sanitize_text_field',
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

		$query = array(
			'post_type' => 'page',
    		'post_status' => 'publish'
		);

		$pages = get_pages( $query );

		$whatsapp_fields = array(
			array(
				'uid' => 'whatsapp-enabled',
				'label' => __( 'Enable/Disable', 'cta-button' ),
				'section' => $section,
				'type' => 'checkbox',
				'options' => array(
					1 => ''
				),
			),			
			array(
				'uid' 			=>'whatsapp-phone',
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
				'uid' => 'whatsapp-pages',
				'label' => __( 'Select Pages to show slider', 'cta-button' ),
				'section' => $section,
				'type' => 'multiselect',
				'options' => CTAButton_Utility::get_select_options( $pages, 'ID', 'post_title'),
			),

			array(
				'uid'           =>'whatsapp-btntext',
				'label'         => __( 'Button Text', 'cta-button' ),
				'section'       => $section,
				'type'          => 'text',
				'options'       => false,
				'default'       => '',
			),
	
			array(
				'uid' 		 => 'whatsapp-btncolor',
				'label' 	 => __( 'Button Background Color', 'cta-button' ),
				'section'	 => $section,
				'type' 		 => 'text',
				'options' 	 => null,
				'attributes' => array( 'class' => 'color-picker' )
			),
		);

		$slider_data = CTAButton_Utility::get_slider_data( $this->slider_id );
		
		?>
		<div id="wrap">
			<h1><?php echo esc_html( $slider_data['title'] ); ?></h1>
			<a href="<?php echo esc_attr( menu_page_url( 'ctabutton', false ) ); ?>"><- Back to Dashboard</a>
			<form class="ctabutton-form" method="post" action="options.php">        
				<?php settings_fields( 'ctabutton-settings-message' ); ?>
				<div class="tabs" style="margin-top: 0.5em">
					<div class="tab">
						<input type="radio" id="whatsapp-radio" name="type">
						<label for="tab-1" class="tab-label">Whatsapp</label>
						
						<div class="tab-content" id="whatsapp-tab">
							<table class="form-table" role="presentation"><tbody>
							<?php
							foreach ($whatsapp_fields as $field) {
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