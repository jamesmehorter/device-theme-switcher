<?php
    // Bail if this file is being accessed directly
    defined( 'ABSPATH' ) OR exit;

    /**
     * UPDATE VERSION to 2.6
     *
     * In 2.6 we changed the previous usage of PHP $_SESSION's to browser cookies
     * for the use of recalling which theme ('View Full Website') the user has chosen
     *
     * On update, lets rename the previous dts_session_lifetime option (which also stored seconds)
     * to a new name which better represents the new convension; dts_cookie_lifespan
     */

    // Set an option to store the plugin cookie name
    // We'll reference this throughout the cookie setting/managing/removal process
    update_option( 'dts_cookie_name', DTS_Core::build_cookie_name( get_bloginfo( 'sitename' ) ) );

    // Add the new option using the new name and existing dts_session_lifetime value
    $dts_cookie_lifespan = intval( get_option( 'dts_session_lifetime' ) );

    // If the session is still 900 seconds / 15 minutes (the default set in version 2.0)
    // Change that to the new default (0 = until the user closes their browser)
    if ( $dts_cookie_lifespan == 900 ) {
        $dts_cookie_lifespan = 0 ;
    }

    // Save the new dts_cookie_lifespan option
    update_option( 'dts_cookie_lifespan', $dts_cookie_lifespan );

    // Remove the old option
    delete_option( 'dts_session_lifetime' );


    // EOF