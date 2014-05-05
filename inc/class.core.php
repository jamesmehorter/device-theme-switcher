<?php
    /**
     *  
     * Load the plugin core routines
     *
     * This is where Device Theme Switcher hooks into the WordPress
     * activation, deactivation, unintiall, init, and plugin_action_links
     */
    class DTS_Core {
        /**
         * Plugin Initialization
         * 
         * This method is run statically in dts_controller.php 
         * on the WordPress init hook. It creates a 
         * DTS_VERSION constant for user anywhere in WordPress 
         */
        static function init () { 
            //Define the DTS_VERSION constant
            define('DTS_VERSION', 2.4);
        }
        
        /**
         * Plugin Activation
         * 
         * This method is run statically in dts_controller.php 
         * on the register_activation_hook() function. It calls the Core init (above), 
         * makes sure the version is stored in an option, and that the default 
         * 15 minute cookie lifespan is stored in an option as well
         */
        static function activate () {
            DTS_Core::init();
            //add the version to the database
            update_option('dts_version', DTS_VERSION);
            //Add new plugin options - but don't overwrite an old value 
            if (!get_option('dts_cookie_lifespan')) add_option('dts_cookie_lifespan', 900);
        }
        
        /**
         * Plugin Deactivation
         * 
         * This method is run statically in dts_controller.php 
         * on the register_deactivation_hook() function
         */
        static function deactivate () {
            //Do nothing on deactivation
        }

        /**
         * Remove all plugin options stored in the database
         * 
         * This method is run statically in dts_controller.php 
         * on the register_uninstall_hook() function        
         */
        static function uninstall () {
            //Remove the plugin's settings
            delete_option('dts_version');
            delete_option('dts_handheld_theme');
            delete_option('dts_tablet_theme');
            delete_option('dts_low_support_theme');
            delete_option('dts_cookie_lifespan');
        }

        /**
         * Add a 'Settings' link to the plugin row in the WP-Admin > Plugins page
         *
         * This method is run statically in dts_controller.php 
         * on the 'plugin_action_links' hook
         *
         * @param $links Contains an array of the current plugin links
         * @param $file Contains a string of the main plugin path/filename.php
         * @return $links After adding in our own
         */        
        static function device_theme_switcher_settings_link($links, $file) {
            if ($file == 'device-theme-switcher/dts_controller.php') :
                //Insert the link at the end
                unset($links['edit']);
                $links['settings'] = sprintf( '<a href="%s" class="edit"> %s </a>', admin_url( 'themes.php?page=device-themes' ), __( 'Settings', 'device_theme_switcher' ) );
            endif;
            return $links;
        }
    }