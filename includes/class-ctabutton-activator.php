<?php

/**
 * Fired during plugin activation
 *
 * @link       ctabutton
 * @since      1.0.0
 *
 * @package    CTAButton
 * @subpackage CTAButton/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    CTAButton
 * @subpackage CTAButton/includes
 * @author     ctabutton <ctabutton>
 */
class CTAButton_Activator {

	/**
	 * Add defult setting options
     * 
	 * @since    1.0.0
	 */
	public static function activate() {
        // add_option('ctabutton-settings-enabled', [1]);
        // add_option('ctabutton-settings-show-title', [1]);
        // add_option('ctabutton-settings-title', 'Book Rooms');
	}

}
