<?php
/**
 * Consultation slider
 */
class CTAButton_Consultation_Slider implements CTAButton_Slider_Interface {

    private $plugin_name;

    public function __construct( $plugin_name ) {
        $this->plugin_name = $plugin_name;

        $this->render();
    }

    /**
     * Render slider
     */
    public function render() {
        
        $settings = $this->get_settings();

		if($settings['enabled'] && $settings['showOnCurrentPage']) {

            require_once CTABUTTON_PATH . 'public/partials/ctabutton-public-header.php';
            ?>
            <div>
                <div>
                    <h4>
                        <?php echo esc_html( isset($settings['title']) ? $settings['title'] : '' ); ?>
                    </h4>
                    <p><?php echo esc_html( $settings['description'] ); ?></p>
                </div>
    
                <div>
                    <?php if( $settings['redirectToExternal'] ) { ?>
                        <a href="<?php echo esc_url( $settings['externalLink'] ); ?>" class="sheet-button" <?php echo $settings['openOnNewTab'] ? 'target="_blank"' : ''; ?>><?php echo esc_html__( 'Go Here', 'cta-button' ); ?></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url( $settings['internalLink'] ); ?>" class="sheet-button"> <?php echo esc_html__( 'Go Here', 'cta-button' ); ?></a>
                    <?php } ?>
                </div>
            </div>
            <?php
            require_once CTABUTTON_PATH . 'public/partials/ctabutton-public-footer.php';
		}
    }

    /**
     * Format content
     */
    public function get_settings() {
        $values             = get_option( $this->plugin_name.'-settings-consultation' );
		$enabledVal         = isset($values['enabled']) ? $values['enabled'] : '';
        $enabled            = is_array($enabledVal) && in_array(1, $enabledVal);
		$pages              = isset($values['pages']) ? $values['pages'] : [];
		$expiry             = isset($values['expiry']) ? $values['expiry'] : '';
		$title              = isset($values['title']) ? $values['title'] : '';
		$description        = isset($values['desc']) ? $values['desc'] : '';
        $externalLink       = isset($values['link']) ? $values['link'] : '';
		$internalPageId     = isset($values['page']) ? $values['page'] : '';
		$redirectToExternal = isset($values['external']) ? $values['external'] : '';
		$redirectToExternal = is_array($redirectToExternal) && count($redirectToExternal) > 0 ? 1 : 0;
		$openOnNewTab       = isset($values['redirect-to-tab']) ? $values['redirect-to-tab'] : '';
        $openOnNewTab       = is_array($openOnNewTab) && count($openOnNewTab) > 0 ? 1 : 0;

		Global $wp_query;
		$currentPageID = is_front_page() ? get_option('page_on_front') : $wp_query->get_queried_object_id();

		$showOnCurrentPage = is_array($pages) && in_array($currentPageID, $pages);

        return array(
            'enabled'               => $enabled,
            'showTitle'             => true,
            'title'                 => $title,
            'description'           => $description,
            'externalLink'          => $externalLink,
            'internalLink'          => get_page_link( $internalPageId ),
            'openOnNewTab'          => $openOnNewTab,
            'showOnCurrentPage'     => $showOnCurrentPage,
            'redirectToExternal'    => $redirectToExternal,
        );
    }
} 