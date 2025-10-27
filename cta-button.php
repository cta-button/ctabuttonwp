<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              ctabutton
 * @since             1.0.0
 * @package           CTAButton
 *
 * @wordpress-plugin
 * Plugin Name:       CTA Button
 * Plugin URI:        https://www.ctabutton.com
 * Description:       Add links for text and call on your website.
 * Version:           1.1.4
 * Author:            CTA Button
 * Author URI:        https://www.ctabutton.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cta-button
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'CTABUTTON_VERSION', '1.1.4' );

/**
 * Set plugin basename
 */
define('CTABUTTON_BASENAME', plugin_basename( __FILE__));

/**
 * Set plugin path
 */
define('CTABUTTON_PATH', plugin_dir_path( __FILE__ ));

/**
 * Set plugin url
 */
define('CTABUTTON_URL', plugin_dir_url( __FILE__ ));

/**
 * Set plugin domain
 */
define('CTABUTTON_DOMAIN', 'ctabutton');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ctabutton-activator.php
 */
function activate_ctabutton() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ctabutton-activator.php';
	CTAButton_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ctabutton-deactivator.php
 */
function deactivate_ctabutton() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ctabutton-deactivator.php';
	CTAButton_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ctabutton' );
register_deactivation_hook( __FILE__, 'deactivate_ctabutton' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ctabutton.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ctabutton() {

	$plugin = new CTAButton();
	$plugin->run();

}

run_ctabutton();

