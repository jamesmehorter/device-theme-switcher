<?php
    // Bail if this file is being accessed directly
    defined( 'ABSPATH' ) OR exit;
    
    /**
     * We renamed the primary class from Device_Theme_Switcher to DTS in version 1.9
     *
     * It was used in documentation for users inserting the html links 
     * wrapped with if(class_exists(Device_Theme_Switcher)). The following
     * simply extends the new class with the old name so that it still functions
     * and that the previous methods still exist and also function as expected 
     * using the newer build method
     */
    class Device_Theme_Switcher Extends DTS_Switcher {

        /**
         * Generate a link to "View Full Website"
         * 
         * @param  string $link_text The text displayed within the anchor
         * @return echo Display the <a> tag
         */
        static function generate_link_to_full_website ( $link_text = "View Full Website" ) {
            
            //Globals the $dts variable created on load
            //This variable is created in /dts-controller.php around line 70
            global $dts;
            return $dts->build_html_link('active', $link_text, array(), $echo = true );

        }// generate_link_to_full_website
        
        /**
         * Generate a link to "Return to Mobile Website"
         * 
         * @param  string $link_text The text displayed within the anchor
         * @return echo Display the <a> tag
         */
        static function generate_link_back_to_mobile ( $link_text = "Return to Mobile Website" ) {

            //Globals the $dts variable created on load
            //This variable is created in /dts-controller.php around line 70
            global $dts;
            return $dts->build_html_link( 'device', $link_text, array(), $echo = true );

        }// generate_link_back_to_mobile

    }// Device_Theme_Switcher