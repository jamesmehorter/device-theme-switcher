<?php
    //We renamed the primary class to DTS in version 1.9
    //This line can be deleted once everyone is on 1.9 :)
    //It was used in documentation for users inserting the html links wrapped with if(class_exists(Device_Theme_Switcher))
    class Device_Theme_Switcher Extends DTS_Switcher {
        // ------------------------------------------------------------------------
        // THEME DEVICE LINK SWITCH - For switching between mobile and screen themes
        //                            Within your theme you can call this method like so: 
        //                            device_theme_switcher::generate_link_to_full_website()
        //                            OR
        //                            device_theme_switcher::generate_link_back_to_mobile()
        // ------------------------------------------------------------------------
        static function generate_link_to_full_website ($link_text = "View Full Website") {
            link_to_full_website($link_text, array());
        }//generate_link_to_full_website
        
        //Generate a link back to the mobile website
        static function generate_link_back_to_mobile ($link_text = "Return to Mobile Website") {
            link_back_to_device($link_text, array());
        }//generate_link_back_to_mobile
    }