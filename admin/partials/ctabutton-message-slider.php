<?php
/**
 * 
 * Message slider Slider settings
 *
 * 
 */
    
$url = menu_page_url( 'ctabutton', false );
?>

<div id="wrap">
	<h1><?php echo esc_html( $slider_data['title'] ); ?></h1>
	<a href="<?php echo esc_url( $url ); ?>"><- Back to Dashboard</a>
    <form method="post" action="options.php"> 
        <?php // wp_nonce_field( 'ctabutton_options_page_save_action', 'ctabutton_options_page_nonce_field' ); ?>       
        <!-- <input type="hidden" name="option_page" value="ctabutton-settings-message">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="_wp_http_referer" value="/wp-admin/options-general.php?page=ctabutton&amp;slider=message"> -->
        <?php echo wp_kses( $html, CTAButton_Utility::get_allowed_tags() ); ?>	    
	</form>
</div>