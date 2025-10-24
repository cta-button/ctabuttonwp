<?php
/**
 * 
 * Display slider Slider settings
 *
 * 
 * @param $slider_id string
 * 
 */

$url = menu_page_url( 'ctabutton', false );
?>

<div id="wrap">
	<h1><?php echo esc_html( $slider_data['title'] ); ?></h1>
	<a href="<?php echo esc_url( $url ); ?>"><- Back to Dashboard</a>
 	<form method="post" action="options.php" class="ctabutton-form">
	    <?php 
	        settings_fields( 'ctabutton-settings-' . $slider_id ); 
	        do_settings_sections( 'ctabutton-settings-' . $slider_id );
	        submit_button();
	    ?>
	</form>
</div>