<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       ctabutton
 * @since      1.0.0
 *
 * @package    CTAButton
 * @subpackage CTAButton/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    CTAButton
 * @subpackage CTAButton/public
 * @author     ctabutton <ctabutton>
 */
class CTAButton_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Load global files
		require_once CTABUTTON_PATH . '/includes/interfaces/class-ctabutton-slider-interface.php';
		require_once CTABUTTON_PATH . '/includes/utilities/class-ctabutton-utility.php';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ctabutton-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'dashicons' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ctabutton-public.js', array( 'jquery' ), $this->version, false );

	}

	public function register_sliders() {
		require_once CTABUTTON_PATH . '/includes/sliders/class-ctabutton-accommodation-slider.php';
		require_once CTABUTTON_PATH . '/includes/sliders/class-ctabutton-appointment-slider.php';
		require_once CTABUTTON_PATH . '/includes/sliders/class-ctabutton-call-slider.php';
		require_once CTABUTTON_PATH . '/includes/sliders/class-ctabutton-consultation-slider.php';
		require_once CTABUTTON_PATH . '/includes/sliders/class-ctabutton-message-slider.php';
		require_once CTABUTTON_PATH . '/includes/sliders/class-ctabutton-redirect-slider.php';

		if(CTAButton_Utility::isActive('accomondation')) {
			new CTAButton_Accomodation_Slider( $this->plugin_name );
		}

		if(CTAButton_Utility::isActive('appointment') ) {
			new CTAButton_Appointment_Slider( $this->plugin_name );
		}

		if(CTAButton_Utility::isActive('call') ) {
			new CTAButton_Call_Slider( $this->plugin_name );
		}

		if(CTAButton_Utility::isActive('consultation') ) {
			new CTAButton_Consultation_Slider( $this->plugin_name );
		}

		if(CTAButton_Utility::isActive('message') ) {
			new CTAButton_Message_Slider( $this->plugin_name );
		}

		if(CTAButton_Utility::isActive('redirect') ) {
			new CTAButton_Redirect_Slider( $this->plugin_name );
		}	
	}

	// Apply the coupon if the 'add_coupon' GET parameter is set
    public function add_coupon_by_link() {
        if (isset( $_GET['add_coupon'] ) && !empty( $_GET['add_coupon'] )) {
			// CRITICAL STEP: Nonce Verification
			$action_name = 'secure-coupon-add'; // MUST match the name used in wp_create_nonce()

			if ( 
				! isset( $_GET['_wpnonce'] ) || // Check if the nonce is even present
				! wp_verify_nonce( sanitize_key( $_GET['_wpnonce'] ), $action_name ) // Check if it's valid
			) {
				// If the nonce is missing or invalid, stop processing!
				return;
			}
			$coupon_code = sanitize_text_field( wp_unslash( $_GET['add_coupon'] ) );
            
            if (!WC()->cart->has_discount($coupon_code)) {
                WC()->cart->add_discount($coupon_code);
            }
        }        
    }
}
