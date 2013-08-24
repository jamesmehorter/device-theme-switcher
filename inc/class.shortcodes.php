<?php
    class DTS_Shortcode {
        //Ex: [link_to_full_website link_text="View Full Website" css_classes="blue-text, alignleft"]
        public function link_to_full_website_shortcode( $atts ) {
            extract( shortcode_atts( array(
                 'link_text' => __("View Full Website"),
                 'css_classes' => array()
            ), $atts ) );
            //Use our own template tag to generate the shortcode output
            //This function is located in /inc/inc.template-tags.php
            return link_to_full_website($link_text, explode(',', str_replace(' ', '', $css_classes)), false);
        }//link_to_full_website_shortcode

        //Ex: [link_back_to_device link_text="Return to Mobile Website" css_classes="blue-text, alignleft"]
        public function link_back_to_device_shortcode( $atts ) {
            extract( shortcode_atts( array(
                 'link_text' => __("Return to Mobile Website"),
                 'css_classes' => array()
            ), $atts ) );
            //Use our own template tag to generate the shortcode output
            //This function is located in /inc/inc.template-tags.php
            return  link_back_to_device($link_text, explode(',', str_replace(' ', '', $css_classes)), false);
        }//link_back_to_device_shortcode
    }//DTS_Shortcode