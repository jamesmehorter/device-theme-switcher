<?php
    // Bail if this file is being accessed directly
    defined( 'ABSPATH' ) OR exit;

    /**
     * Load the plugin shortcodes
     *
     * The shortcodes allow capable users to place 'View Full Website' and
     * 'Return to Mobile Website' links in their posts / pages.
     */
    class DTS_Shortcode {

        /**
         * Internally stored reference to the single instance of this class
         * @var object
         */
        private static $_instance;

        /**
         * Return the single instance of this class
         *
         * @return object Instance of this class
         */
        static function get_instance () {

            if ( ! isset( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;

        } // function get_instance

        /**
         * Add Shortcodes to WordPress
         */
        public function add_shortcodes () {

            // Ex: [link_to_full_website link_text="View Full Website" css_classes="blue-text, alignleft"]
            // This shortcode outputs an HTML <a> link for the user to 'View Full Website' or to 'Return to Mobile Website'
            add_shortcode( 'link_to_full_website', array( $this, 'link_to_full_website_shortcode' ) );

            // Ex: [link_back_to_device link_text="Return to Mobile Website" css_classes="blue-text, alignleft"]
            add_shortcode( 'link_back_to_device', array( $this, 'link_back_to_device_shortcode' ) );

        } // function add_shortcodes

        /**
         * Display a link to 'View Full Website'
         *
         * Ex: [link_to_full_website link_text="View Full Website" css_classes="blue-text, alignleft"]
         *
         * @param $atts array containing 2 indicies 'link_text' and 'css_classes'.
         * These determine the output <a> text and the css classes applied to the <a>
         * @return the generated <a> tag which, when clicked, takes the user to the full theme
         */
        static function link_to_full_website_shortcode ( $atts, $content, $name ) {
            extract( shortcode_atts( array(
                 'link_text' => __( 'View Full Website', 'device-theme-switcher' ),
                 'css_classes' => array()
            ), $atts ) );

            // Generate the html anchor link
            $html_output = DTS_Switcher::get_instance()->build_html_link(
                'active',   // anchor link destination theme
                $link_text, // anchor link inner text
                explode( ',', str_replace( ' ', '', $css_classes ) ), // anchor link css classes
                false       // return the html anchor, don't echo it
            );

            return $html_output ;

        } // function link_to_full_website_shortcode

        /**
         * Display a link to 'View Full Website'
         *
         * Ex: [link_back_to_device link_text="Return to Mobile Website" css_classes="blue-text, alignleft"]
         *
         * @param $atts array containing 2 indicies 'link_text' and 'css_classes'.
         * These determine the output <a> text and the css classes applied to the <a>
         * @return the generated <a> tag which, when clicked, takes the user back to their respective device
         */
        static function link_back_to_device_shortcode ( $atts, $content, $name ) {
            extract( shortcode_atts( array(
                 'link_text' => __( 'Return to Mobile Website', 'device-theme-switcher' ),
                 'css_classes' => array()
            ), $atts ) );

            // Generate the html anchor link
            $html_output = DTS_Switcher::get_instance()->build_html_link(
                'device',   // anchor link destination theme
                $link_text, // anchor link inner text
                explode( ',', str_replace( ' ', '', $css_classes ) ), // anchor link css classes
                false       // return the html anchor, don't echo it
            );

            return $html_output ;

        } // function link_back_to_device_shortcode

    } // class DTS_Shortcode


    // EOF