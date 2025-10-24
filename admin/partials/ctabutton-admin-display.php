<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       ctabutton
 * @since      1.0.0
 *
 * @package    CTAButton
 * @subpackage CTAButton/admin/partials
 */

function os_navigate_to_page( $slider_name ) {
    // Safely read existing query parameters (ignore nonce warning; only reading harmless GET data)
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    $query = array_map( 'sanitize_text_field', wp_unslash( $_GET ) );

    // Add or replace the "slider" parameter
    $query['slider'] = sanitize_text_field( $slider_name );

    // Build a safe URL for the same admin page (no $_SERVER access)
    $url = add_query_arg( $query, admin_url( 'options-general.php' ) );

    // Return the escaped URL for safe output
    return esc_url( $url );
}
?>

<div id="wrap">

    <h1>CTA Button Dashboard</h1>
    <p>What do you want the site visitor to do ?</p>
    <div class="ctabutton-slider-items">
        <?php foreach( $sliders as $slider ) {            
            $sliderOptions = get_option('ctabutton-settings-' . $slider['id']);
            $sliderEnabled = isset($sliderOptions['enabled']) && is_array($sliderOptions['enabled']);  
                   
        ?>
        <a class="ctabutton-slider-item" style="<?php echo $sliderEnabled ? 'border: 2px solid limegreen;' : ''; ?>" href="<?php echo esc_attr( os_navigate_to_page( $slider['id'] ) ); ?>">
            <span class="dashicons <?php echo esc_attr( $slider['icon'] ); ?>"></span>
            <div class="ctabutton-content">
                <div class="ctabutton-title"><?php echo esc_html( $slider['title'] ); ?></div>
                <div class="ctabutton-description"><?php echo esc_html( $slider['description'] ); ?></div>
            </div>
        </a>
        <?php } ?>
    </div>

</div>