<?php

class CTAButton_Slider_Factory 
{
    public static function get_slider( $slider, $plugin_name ): CTAButton_Slider_Settings_Interface {
        switch( $slider ) {
            case 'message':
                return new CTAButton_Message_Slider( $plugin_name );
            
            case 'coupon':
                return new CTAButton_Coupon_Slider( $plugin_name );

            case 'call':
                return new CTAButton_Call_Slider( $plugin_name );

            default:
                return null;
        }
    }
}