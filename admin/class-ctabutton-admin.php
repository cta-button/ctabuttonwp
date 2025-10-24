<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       ctabutton
 * @since      1.0.0
 *
 * @package    CTAButton
 * @subpackage CTAButton/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CTAButton
 * @subpackage CTAButton/admin
 * @author     ctabutton <ctabutton>
 */
class CTAButton_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function admin_init() {
		
		require_once( CTABUTTON_PATH . 'includes/interfaces/class-ctabutton-slider-settings-interface.php' );
		require_once( CTABUTTON_PATH . 'includes/admin/class-ctabutton-slider-factory.php' );
		require_once( CTABUTTON_PATH . 'includes/utilities/class-ctabutton-utility.php' );

		require_once( CTABUTTON_PATH . 'includes/admin/sliders/class-ctabutton-accommodation-slider.php' );
		require_once( CTABUTTON_PATH . 'includes/admin/sliders/class-ctabutton-appointment-slider.php' );
		require_once( CTABUTTON_PATH . 'includes/admin/sliders/class-ctabutton-call-slider.php' );
		require_once( CTABUTTON_PATH . 'includes/admin/sliders/class-ctabutton-consultation-slider.php' );
		require_once( CTABUTTON_PATH . 'includes/admin/sliders/class-ctabutton-coupon-slider.php' );
		require_once( CTABUTTON_PATH . 'includes/admin/sliders/class-ctabutton-message-slider.php' );
		require_once( CTABUTTON_PATH . 'includes/admin/sliders/class-ctabutton-redirect-slider.php' );
		
		new CTAButton_Accomodation_Slider( $this->plugin_name );
		new CTAButton_Appointment_Slider( $this->plugin_name );
		new CTAButton_Call_Slider( $this->plugin_name );			
		new CTAButton_Consultation_Slider( $this->plugin_name );
		new CTAButton_Message_Slider( $this->plugin_name );
		new CTAButton_Redirect_Slider( $this->plugin_name );			
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in CTAButton_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The CTAButton_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ( 'settings_page_ctabutton' === get_current_screen()->id ) {
			wp_enqueue_style( $this->plugin_name . '-select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '-intlTelInput', plugin_dir_url( __FILE__ ) . 'css/intlTelInput.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_style( $this->plugin_name.'-tabs', CTABUTTON_URL . 'admin/css/ctabutton-tabs.css', array(), '1.0', 'all' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ctabutton-admin.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in CTAButton_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The CTAButton_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ( 'settings_page_ctabutton' === get_current_screen()->id ) {
			wp_enqueue_script( $this->plugin_name.'-select2', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-intlTelInput', plugin_dir_url( __FILE__ ) . 'js/intlTelInput-jquery.min.js', array( 'jquery' ), $this->version, false );
					
			wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false, 1 );
		}
		
	}

	public function add_action_links ( $actions ) {
	   $action_links = array(
	   		'settings' => '<a href="' . admin_url( 'options-general.php?page='.$this->plugin_name ) . '">Settings</a>'
	   	);

	   return array_merge($action_links, $actions);
	}

	public function register_settings_page() {
		add_options_page( 
			'CTA Button', 
			'CTA Button', 
			'manage_options', 
			'ctabutton', 
			array( $this, 'display_settings_page' ) 
		);
	}

	public function display_settings_page() {
		// 1. **VERIFY USER CAPABILITY**
		if ( ! current_user_can( 'manage_options' ) ) {
			// Stop execution if the user doesn't have the necessary capability
			wp_die( 'You do not have sufficient permissions to access this page.' );
		}

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ctabutton-admin.js', array( 'jquery' ), $this->version, false );

		$sliders 	= CTAButton_Utility::$sliders;

		// Check the nonce field name (ctabutton_options_page_nonce_field) against the action name (ctabutton_options_page_save_action)
		if ( 
			isset( $_POST['ctabutton_options_page_nonce_field'] ) && 
			! wp_verify_nonce( sanitize_key( $_POST['ctabutton_options_page_nonce_field'] ), 'ctabutton_options_page_save_action' ) 
		) {
			// Stop execution if the nonce is invalid
			//wp_die( 'Security check failed. Please try again.' );
		}

		if( 			
			isset( $_GET['slider'] ) && 
			in_array( $_GET['slider'], array_column( $sliders, 'id' ) ) !== false 
		) {
			$slider_id = sanitize_text_field( wp_unslash( $_GET['slider'] ) );

			// Add slider js variables			
			wp_localize_script( $this->plugin_name, 'CTAButtonObj', array(
				'slider_id'	=> $slider_id,
				'settings_url' => admin_url( 'options.php' ),
			));
			
			$slider_key  = array_search( $_GET['slider'], array_column( $sliders, 'id' ) );
			$slider_data = $sliders[$slider_key];
			
			// Show the slider settings
			switch( $slider_id ) {
				case 'sell':
					// Notify user if woo is inactive
					if( ! CTAButton_Utility::is_woo_active() ) {
						$this->woocommerce_inactive_message();
					}
					break;
				
				// Render logic is defined in the slider
				case 'message':
				case 'coupon':
				case 'call':
					$slider_instance = CTAButton_Slider_Factory::get_slider( $slider_id, $this->plugin_name );

					$slider_instance->render();

					break;
				default:
					require_once plugin_dir_path( __FILE__ ) . 'partials/ctabutton-view.php';
			}
		} else {			
			require_once plugin_dir_path( __FILE__ ) . 'partials/ctabutton-admin-display.php';
		}
	}

	public function woocommerce_inactive_message() {
		?>
		<div class="notice notice-warning is-dismissible">
			<p><?php esc_attr_e( 'This page requires woocommerce!', 'cta-button' ); ?></p>
		</div>
		<?php
	}
}
