<?php
    // Bail if this file is being accessed directly
    defined( 'ABSPATH' ) OR exit;
    
    /**
     * Template Tag: Generate a link to the full website
     *
     * This is a wrapper function for DTS_Switcher::build_html_link()
     *
     * @param  string  $link_text The text within the generated link, i.e. <a>View Full Website</a>
     * @param  array   $css_classes And array of additional css classes to be applied to the generated link. 
     * Note: the class to-full-website is added by default
     * @param  boolean $echo 1 or 0 or true or false, by default the link is echoed to the screen. False will 
     * return it for use as a string.
     * @return string  HTML anchor link to the full website
     */
    function link_to_full_website( $link_text = "View Full Website", $css_classes = array(), $echo = true ) {

        DTS_Switcher::build_html_link( 'active', $link_text, $css_classes, $echo );

    } // link_to_full_website

    /**
     * Template Tag: Generate a link to return to the device theme
     *
     * This is a wrapper function for DTS_Switcher::build_html_link()
     *
     * @param  string  $link_text The text within the generated link, i.e. <a>Return to Mobile Website</a>
     * @param  array   $css_classes And array of additional css classes to be applied to the generated link. 
     * Note: the class back-to-mobile is added by default
     * @param  boolean $echo 1 or 0 or true or false, by default the link is echoed to the screen. False will 
     * return it for use as a string.
     * @return string  HTML anchor link to the device website
     */
    function link_back_to_device( $link_text = "Return to Mobile Website", $css_classes = array(), $echo = true ) {

        DTS_Switcher::build_html_link( 'device', $link_text, $css_classes, $echo );

    } // link_back_to_device


    // EOF