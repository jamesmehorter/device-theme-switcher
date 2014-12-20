<?php
    // Bail if this file is being accessed directly
    defined( 'ABSPATH' ) OR exit;

    /**
     * Backwards compatibility for Device Theme Switcher v1.0.0 URL $_GET Variables
     *
     * This code only assists users who have hardcoded ?dts_device= links into thier themes
     * Captures the following $_GET variables and ports them to the new code logic:
     *
     * ?dts_device=screen
     * Previously used allow the handheld/tablet user to view the full website
     *
     * ?dts_device=device
     * previously used to allow the handheld/tablet user return to their device's assigned theme
     */
    if ( isset( $_GET['dts_device'] ) ) {

        switch ( $_GET['dts_device'] ) {

            //'active' now represents the 'full website'
            //'active' theme as in the active theme set by the admin within wp-admin/themes.php
            case 'screen' :
                $_GET['theme'] = 'active';
            break;

            case 'device' :
                // Set the get 'theme' variable to match what our newer code is expecting
                $_GET['theme'] = DTS_Switcher::get_instance()->device;
            break;
        } // end switch

        // This really shouldn't be needed, but just to ensure it's been removed during runtime
        unset( $_GET['dts_device'] );

    } // end if
