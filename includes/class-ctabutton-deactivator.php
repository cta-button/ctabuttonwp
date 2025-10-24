<?php

/**
 * Fired during plugin deactivation
 *
 * @link       ctabutton
 * @since      1.0.0
 *
 * @package    CTAButton
 * @subpackage CTAButton/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    CTAButton
 * @subpackage CTAButton/includes
 * @author     ctabutton <ctabutton>
 */
class CTAButton_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        // Remove all matching options from the database
        foreach (wp_load_alloptions() as $option => $value) {
            
            if (strpos($option, 'ctabutton-settings-') !== false) {
                
                delete_option($option);
                
            }
            
        }
	}

}
