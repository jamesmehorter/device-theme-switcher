<?php
    /**
     * Load the plugin shortcodes
     *
     * The shortcodes allow capable users to place 'View Full Website' and
     * 'Return to Mobile Website' links in their posts / pages.
     */
    class DTS_Shortcode {
        
        /**
         * Display a link to 'View Full Website'
         *
         * Ex: [link_to_full_website link_text="View Full Website" css_classes="blue-text, alignleft"]
         * 
         * @param $atts array containing 2 indicies 'link_text' and 'css_classes'. 
         * These determine the output <a> text and the css classes applied to the <a>
         * @return the generated <a> tag which, when clicked, takes the user to the full theme
         */
        public function link_to_full_website_shortcode( $atts ) {
            extract( shortcode_atts( array(
                 'link_text' => __("View Full Website"),
                 'css_classes' => array()
            ), $atts ) );
            //Globals the $dts variable created on load
            //This variable is created in /dts-controller.php around line 70
            global $dts;
            return $dts->build_html_link('active', $link_text, explode(',', str_replace(' ', '', $css_classes)), false);
        }//link_to_full_website_shortcode

        /**
         * Display a link to 'View Full Website'
         *
         * Ex: [link_back_to_device link_text="Return to Mobile Website" css_classes="blue-text, alignleft"]
         * 
         * @param $atts array containing 2 indicies 'link_text' and 'css_classes'. 
         * These determine the output <a> text and the css classes applied to the <a>
         * @return the generated <a> tag which, when clicked, takes the user back to their respective device
         */
        public function link_back_to_device_shortcode( $atts ) {
            extract( shortcode_atts( array(
                 'link_text' => __("Return to Mobile Website"),
                 'css_classes' => array()
            ), $atts ) );
            //Globals the $dts variable created on load
            //This variable is created in /dts-controller.php around line 70
            global $dts;
            return $dts->build_html_link('device', $link_text, explode(',', str_replace(' ', '', $css_classes)), false);
        }//link_back_to_device_shortcode
    }//DTS_Shortcode