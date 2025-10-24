<?php
/**
 * Add Coupon to Cart" enables you to provide visitors with a discounted coupon link that automatically applies the discount to the cart and convert them into a customer by making more sales conveniently. Share this link in your content, sidebars, email newsletters, or social media.
 */
class CTAButton_Coupon_Slider implements CTAButton_Slider_Settings_Interface {

    private $plugin_name;

    private $slider_id = 'coupon';

    public function __construct( $plugin_name ) {
		$this->plugin_name = $plugin_name;

		$this->register_settings();

        // enqueue scripts        
        // wp_enqueue_script('clipboard', 'https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js');
        wp_add_inline_script('clipboard', 'new ClipboardJS(".copy-btn");');
    }

    public function register_settings() {}

    public function render() {
        $back_button = '<a href="' . menu_page_url( 'ctabutton', false ) . '"><- Back to Dashboard</a>';

        if( ! CTAButton_Utility::is_woo_active() ) {
            echo '<div id="wrap">';
            echo esc_html( $back_button );
            echo '<p style="text-align: center;">WooCommerce is not active, this <strong>Share Coupon Code</strong> option is not available.</p>';
            echo '</div>';

            return;
        }

        $args = array(
            'posts_per_page' => -1,
            'post_type'      => 'shop_coupon',
            'post_status'    => 'publish',
        );

        $coupons = get_posts($args);

        echo '<div id="wrap">';
        echo '<h1>Add Coupon by Link Settings</h1>';
        echo esc_html( $back_button );
        echo '<div><br/><a class="button button-secondary" style="margin-bottom:10px" href="edit.php?post_type=shop_coupon">Go to coupons</a></div>';
        echo '<div class="table-responsive">';
        echo '<table class="wp-list-table widefat fixed striped table">';
        echo '<thead><tr><th>Coupon Code</th><th>Description</th><th>Action</th><th class="coupon-link-col">Coupon Link</th><th>Edit Coupon</th></tr></thead>';
        echo '<tbody id="the-list">';
        
        foreach ($coupons as $coupon) {
            $coupon_id = $coupon->ID;
            $coupon_code = $coupon->post_title;

            $action_name = 'secure-coupon-add';
            $coupon_link = add_query_arg(
                [
                    'add_coupon' => $coupon_code,
                    '_wpnonce'   => wp_create_nonce( $action_name )
                ],
                site_url('/cart/')
            );
            $edit_link = get_edit_post_link($coupon_id);
            $coupon_description = $coupon->post_excerpt;
        
            echo '<tr><td>' . esc_html( $coupon_code ) . '</td>';
            echo '<td>' . esc_html( $coupon_description ) . '</td>';
            echo '<td><button class="copy-btn button button-primary" data-clipboard-text="' . esc_attr( $coupon_link ) . '">Copy Link</button></td>';
            echo '<td><input type="text" class="coupon-link" value="' . esc_attr( $coupon_link ) . '" readonly></td>';
            echo '<td><a href="' . esc_attr( $edit_link ) . '">Edit Coupon</a></td>';
            echo '</tr>';            
        }
        
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '</div>';
    }
}
