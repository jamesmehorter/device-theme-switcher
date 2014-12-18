<?php
    // Bail if this file is being accessed directly
    defined( 'ABSPATH' ) OR exit;

    /**
     * UPDATE VERSION 1.x to 2.0
     *
     * We need to update the dts theme values stored in the database
     * previously just the slug was kept, now we're storing a url encoded string of 3 values
     */

    //Add new plugin options
    add_option( 'dts_session_lifetime', 300 );

    //The following options are no longer being used in 2.0, let's remove them
    delete_option( 'dts_device' );
    delete_option( 'dts_current_theme' );
    delete_option( 'dts_device_themes' );

    /**
     * The update below converts the old dts theme options in the options database table
     *
     * OLD FORMAT: default
     * NEW FORMAT: name=WordPress+Default&template=default&stylesheet=defaut
     * The old format stored just the theme template file name
     * The new format stores a url encoded string of 3 values (name, template, and stylesheet)
     */

    //Select each old dts option (single theme name in a string)
    $theme_setting['dts_handheld_theme']    = get_option( 'dts_handheld_theme' );
    $theme_setting['dts_tablet_theme']      = get_option( 'dts_tablet_theme' );
    $theme_setting['dts_low_support_theme'] = get_option( 'dts_low_support_theme' );

    //loop through each and gather complete theme info like what's being stored in version 2.0+
    foreach ( $theme_setting as $dts_option_name => $theme_string_name ) {

        // Gather all of the currently installed theme names
        // wp_get_themes was introduced in WP 3.4.0
        if ( function_exists( 'wp_get_themes' ) ) {
            $installed_themes = wp_get_themes();
        } else {
            $installed_themes = get_themes();
        }

        //Loop through each of the installed themes
        foreach ( $installed_themes as $theme ) {

            //Gather each theme's theme data
            //wp_get_theme was introduced in WordPress v3.4 - this check ensures we're backwards compatible
            if ( function_exists( 'wp_get_theme ') ) {
                $theme_data = wp_get_theme( $theme['Stylesheet'] );
            } else {
                $theme_data = get_theme_data( get_theme_root() . '/' . $theme['Stylesheet'] . '/style.css' );
            }

            //We'll only display a theme if it is an actual / functioning theme with theme data
            if ( isset( $theme_data ) ) {

                //Check if the theme is a child theme
                //In this instance the 'Template' variable will be empty and we're suppose to submit the stylesheet instead
                if ( ! empty( $theme_data['Template'] ) ) {
                    $template = $theme_data['Template'];
                    $theme_name = $theme_data['Name'];
                } else {
                    $template = $theme['Stylesheet'];
                }

                //Ok we found the theme the user had installed
                if ( $theme_string_name == $theme_data['Name'] ) {

                    $theme_options = build_query(
                        array(
                            'name'       => $theme->Name,
                            'template'   => $template,
                            'stylesheet' => $theme['Stylesheet']
                        )
                    );
                    update_option( $dts_option_name, $theme_options );

                } // end if
            } // end if
        } // end foreach
    } // end foreach

    // Create an admin notice and store it in a transient to display on page
    // **After the form is submit a new page is loaded by wp, hence the need
    // for the transient
    ob_start(); ?>

    <div class="dts updated">
        <p>
            <strong><?php _e( 'Thanks for upgrading! We\'ve compeltely rewritten Device Theme Switcher and added lots of new goodies! Read about the changes <a href="http://wordpress.org/plugins/device-theme-switcher/faq/" target="_blank">here</a>.', 'device-theme-switcher' ) ?></strong>
        </p>
    </div><?php

    $admin_notice = ob_get_clean();

    set_transient( 'dts_updated_notice', $admin_notice, 60*60*12 );


    // EOF/ EOF