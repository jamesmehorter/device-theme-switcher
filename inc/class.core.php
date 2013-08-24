<?php
    class DTS_Core {
        // ------------------------------------------------------------------------------
        // INITILIZATION
        // ------------------------------------------------------------------------------
        static function init () { 
            //Define the DTS_VERSION constant
            define('DTS_VERSION', 2.0);
        }
        // ------------------------------------------------------------------------------
        // ACTIVATION
        // ------------------------------------------------------------------------------
        static function activate () {
            DTS_Core::init();
            //add the version to the database
            update_option('dts_version', DTS_VERSION);
            //Add new plugin options - but don't overwrite an old value 
            if (!get_option('dts_session_lifetime')) add_option('dts_session_lifetime', 900);

            //Display an admin notice letting the user know the activation was successfull
            //add_action('admin_notices', array('DTS_Admin', 'admin_activation_notice'));
        }
        // ------------------------------------------------------------------------------
        // DEACTIVATION
        // ------------------------------------------------------------------------------
        static function deactivate () {
            //Do nothing on deactivation
        }
        // ------------------------------------------------------------------------------
        // UNINSTALLATION
        // ------------------------------------------------------------------------------
        static function uninstall () {
            //Remove the plugin's settings
            delete_option('dts_version');
            delete_option('dts_handheld_theme');
            delete_option('dts_tablet_theme');
            delete_option('dts_low_support_theme');
            delete_option('dts_session_lifetime');
        }
        //Add a 'Settings' link to the plugin row in the wp-admin > Plugins page
        //The settings link just goes to the Appearance > Device Themes page
        static function device_theme_switcher_settings_link($links, $file) {
            if ($file == 'device-theme-switcher/dts_controller.php') :
                //Insert the link at the end
                unset($links['edit']);
                $links['settings'] = sprintf( '<a href="%s" class="edit"> %s </a>', admin_url( 'themes.php?page=device-themes' ), __( 'Settings', 'device_theme_switcher' ) );
            endif;
            return $links;
        }
    }