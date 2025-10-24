<?php
/**
 * Message slider
 */
class CTAButton_Message_Slider implements CTAButton_Slider_Interface {

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
        ?>
        <div style="background: #fff">
            <div class="ctabutton message-slider">
                <div class="mb-15">
                    <?php
                    foreach( $settings as $type ) {
                        if($type['enabled'] && $type['showOnCurrentPage']) {
                        ?>                            
                            <a href="<?php echo esc_url( $type['url'] ); ?>" class="launch-btn" style="background-color: <?php echo esc_attr( $type['btnColor'] ); ?>" target="_blank"> 
                                <?php echo wp_kses_post( $type['icon'] ); ?>
                            </a>                                    
                        <?php
                        }            
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php   
    }

    /**
     * Format content
     */
    public function get_settings() {
        $slider_values  = get_option( $this->plugin_name.'-settings-message' );
        $settings       = array();

        $types = array();

        if( is_array( $slider_values )) {
            foreach( $slider_values as $key => $value ) {
                $keyItems = explode( '-', $key );
                $type     = $keyItems[0];
                $typeKey  = $keyItems[1];

                $types[$type][$typeKey] = $value;
            }
        }

        foreach( $types as $type => $values ) {
            $enabledVal = isset( $values['enabled'] ) ? $values['enabled'] : '';
            $enabled    = is_array( $enabledVal ) && in_array( 1, $enabledVal );
            $pages      = isset( $values['pages'] ) ? $values['pages'] : [];
            $phone      = isset( $values['phone'] ) ? $values['phone'] : '';
            $btnText    = isset( $values['btntext'] ) ? $values['btntext'] : '';
            $btnColor   = isset( $values['btncolor'] ) ? $values['btncolor'] : '';

            Global $wp_query;
            $currentPageID = is_front_page() ? get_option('page_on_front') : $wp_query->get_queried_object_id();

            $showOnCurrentPage = is_array($pages) && in_array($currentPageID, $pages);

            $settings[$type] = array(
                'enabled'           => $enabled,
                'pages'             => $pages,
                'phone'             => $phone,  
                'showOnCurrentPage' => $showOnCurrentPage,  
                'icon'              => '<span class="dashicons dashicons-' . ( $type === 'sms' ? 'format-chat' : $type ) . '"></span>',
                'url'               => $this->get_url( $type, $phone ),
                'btnText'           => $btnText,
                'btnColor'          => $btnColor
            );
        }
        
        return $settings;
    }

    private function get_url( $type, $phone ) {
        switch( $type ) {
            case 'whatsapp':
                return 'https://wa.me/' . $phone;
            case 'sms':
                return 'sms:' . $phone;
            default:
                // Nothing for now
        }
    }
} 