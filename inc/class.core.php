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
            //Run the Core initialization
            DTS_Core::init();
            
            //Set an option to store the plugin cookie name
            update_option('dts_cookie_name', DTS_Core::build_cookie_name());
            
            //add the version to the database
            update_option('dts_version', DTS_VERSION);
            
            //Add new plugin options - but don't overwrite an old value 
            if (!get_option('dts_cookie_lifespan')) add_option('dts_cookie_lifespan', 0);
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
            delete_option('dts_cookie_name');
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

        /**
         * Build the name of the cookie DTS will create
         *
         * When a user clicks to 'View Full Website' we set a cookie so they can browse
         * the website and retain the full website theme. The following builds the name of the
         * cookie so that "My Magical Website" becomes 'my-magical-website-alternate-theme'
         * 
         * @return string the name of the cookie being used
         */
        static public function build_cookie_name () {
            //we'll use this for the cookie name so that it refernces the website not dts
            //Determine the 'slug' of the website name
            $cookie_name = get_bloginfo('sitename');
            //remove special characters
            $cookie_name = preg_replace('/[^a-zA-Z0-9_%\[().\]\\/-]/s', '', $cookie_name); 
            //change spaces to hyphens
            $cookie_name = str_replace(' ', '-', $cookie_name);
            //lowercase everything
            $cookie_name = strtolower($cookie_name);
            //append some identifying text
            $cookie_name = $cookie_name . '-alternate-theme';
            
            //Return the assembled cookie name
            return $cookie_name;
        }//build_cookie_name
    }