<?php
/**
 * Call slider
 */
class CTAButton_Call_Slider implements CTAButton_Slider_Interface {

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
        <div style="background: transparent">
            <div class="ctabutton">
                <div class="mb-15">
                <?php
                foreach( $settings as $type ) {
                    if($type['enabled'] && ($type['showOnCurrentPage'] || $type['showOnCategory'])) {
                    ?>                            
                        <a href="<?php echo esc_url( $type['url'] ); ?>" class="launch-btn" style="border-radius: <?php echo esc_attr( $type['btnRadius'] ); ?>; background-color: <?php echo esc_attr( $type['btnColor'] ); ?>" target="_blank"> 
                            <?php $btnTxt = $type['btnText']; $typePhone = $type['phone']; echo wp_kses( $type['icon'], CTAButton_Utility::get_allowed_tags() ) . esc_html( $btnTxt !== '' ? $btnTxt : $typePhone ); ?>
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
        $slider_values = get_option( $this->plugin_name.'-settings-call' );

        $slider_types  = CTAButton_Utility::group_slider_values_by_type( $slider_values );
        $settings      = array();

        foreach( $slider_types as $type => $values) {
            $enabledVal = isset($values['enabled']) ? $values['enabled'] : '';
            $enabled    = is_array($enabledVal) && in_array(1, $enabledVal);
            $pages      = isset($values['pages']) ? $values['pages'] : [];
            $phone      = isset($values['phone']) ? $values['phone'] : '';
            $categories = isset($values['categories']) ? $values['categories'] : [];
            $btnText    = isset( $values['btntext'] ) ? $values['btntext'] : '';
            $btnColor   = isset( $values['btncolor'] ) ? $values['btncolor'] : '';
            $btnType    = isset( $values['btntype'] ) ? $values['btntype'] : '';

            $btnRadius;

            switch( $btnType ) {
                case 'rounded':
                    $btnRadius = '4px';
                    break;
                case 'capsule':
                    $btnRadius = '45px';
                    break;
                default:
                    $btnRadius = '0';
            }

            Global $wp_query;
            $currentPageID = is_front_page() ? get_option('page_on_front') : $wp_query->get_queried_object_id();

            // Check if can show on blog 
            $currentBlogCategories = get_the_category( $currentPageID );

            $showOnCategory = false;
            
            foreach($currentBlogCategories as $category) {
                if(is_array($categories) && in_array($category->cat_ID, $categories) && is_single()) {
                    $showOnCategory = true;
                }
            }

            // Check if can show on page
            $showOnCurrentPage = is_array($pages) && in_array($currentPageID, $pages);
            
            $settings[$type] = array(
                'enabled'           => $enabled,
                'pages'             => $pages,
                'phone'             => $phone,  
                'showOnCurrentPage' => $showOnCurrentPage,  
                'showOnCategory'    => $showOnCategory,
                'btnText'           => $btnText,
                'btnColor'          => $btnColor,
                'btnRadius'         => $btnRadius,
                'icon'              => '<span class="dashicons dashicons-' . ( $type === 'sms' ? 'format-chat' : 'phone' ) . '"></span>',
                'url'               => $type === 'call' ? 'tel:' . $phone : 'sms:' . $phone,
            );
        }

        return $settings;
    }
} 