<?php
    // Bail if this file is being accessed directly
    defined( 'ABSPATH' ) OR exit;
    
    /**
     *  
     * Load the plugin core routines
     *
     * This is where Device Theme Switcher hooks into the WordPress
     * activation, deactivation, unintiall, init, and plugin_action_links
     */
    class DTS_Core extends DTS_Singleton {

        /**
         * Instance Construction
         *
         * Load plugin includes and hook into WordPress
         *
         * @access self::factory // plugins_loaded action
         * @return null
         */
        public function __construct () {

            // Load our plugins includes 
            $this->load_includes();

            // Hook into WordPress
            $this->hook_into_wordpress();

        } // function __construct


        /**
         * Load all the files the plugin will need 
         *
         * @see    self::__construct
         * @return null
         */
        public function load_includes () {

            /**
             * Load the plugin update routines
             *
             * The update class checks the currently installed version and runs
             * any necessary update routines. This only occurs once per version,
             * and ONLY in the admin.
             */
            // include the updating class
            include_once( 'class-update.php' );

            /**
             * Load the plugin admin features
             *
             * The admin features include the display of the status output in the Dashboard 
             * 'Right Now' widget. They also create an admin page at Appearance > Device Themes
             * for the website admin to save the plugin settings 
             */
            // include the wp-admin class
            include_once( 'class-wp-admin.php' );

             /**
             * Load the plugin theme switching functionality
             *
             * The theme switching utilizes the MobileESP library to detect
             * the browser User Agent and determine if it's a 'handheld' or 'tablet'.
             * This plugin then taps into the WordPress template and stylesheet hooks 
             * to deliver the alternately set themes in Appearance > Device Themes
             */
            // We only want to tap into the theme filters if a frontend page or an ajax request is being requested
            
            // Include our external device theme switcher class library
            include_once( 'class-switcher.php' );
            
            // Load support for legacy GET variables used in version 1.0.0
            include_once( 'legacy/legacy-get-support.php' );

            // Include the template tags developers can access in their themes
            include_once( 'template-tags.php' );
            
            // Load support for legacy classes, methods, functions, and variables
            include_once( 'legacy/legacy-structural-support.php' );

            /**
             * Load in the plugin widgets
             *
             * The widgets create an option for capable users to place 'View Full Website'
             * and 'Return to Mobile Website' links in their theme sidebars.
             */
            // Load the widget class 
            include_once( 'class-widgets.php' );

            /**
             * Load the plugin shortcodes
             *
             * The shortcodes allow capable users to place 'View Full Website' and
             * 'Return to Mobile Website' links in their posts / pages. Register the 
             * [device-theme-switcher] shortcode and Include our external shortcodes class library
             */
            // load the shortodes class
            include_once( 'class-shortcodes.php' );

        } // function load_includes


        /**
         * Hook into WordPress
         * 
         * @return null
         */
        public function hook_into_wordpress () {
            
            // Only run the following in the admin, the REAL admin--not admin ajax
            if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

                // Grab the single instance of the admin class
                $dts_admin = DTS_Admin::factory();
                
                // Create our plugin admin page under the 'Appearance' menu
                add_action( 'admin_menu', array( $dts_admin, 'admin_menu' ), 10, 0 );
                
                // Check if we need to save any form data that was submitted
                add_action( 'load-appearance_page_device-themes', array( $dts_admin, 'load' ), 10, 0 );

                // Display a 'Settings' link with the plugin in the plugins list
                add_filter( 'plugin_action_links', array( $this, 'device_theme_switcher_settings_link' ), 10, 2 );
         
            } else { // is_admin()

                // Grab the single instance of the switcher class
                // And make it available globally for use in themes/other plugins
                global $dts;
                $dts = DTS_Switcher::factory();
                
                // Hook into the template output function with a filter and change the template delivered if need be
                add_filter( 'template', array( $dts, 'deliver_template' ), 10, 0 );
                
                // Hook into the stylesheet output function with a filter and change the stylesheet delivered if need be
                add_filter( 'stylesheet', array( $dts, 'deliver_stylesheet' ), 10, 0 );

            } // if is_admin()

            // Load the plugin widgets
            add_action( 'widgets_init', array( $this, 'register_widgets' ), 10, 0 );

            // Add our shortcodes
            DTS_Shortcode::factory()->add_shortcodes();

        } // function hook_into_wordpress

        /**
         * Plugin Activation
         * 
         * This method is run statically in dts_controller.php 
         * on the register_activation_hook() function. It calls the Core init (above), 
         * makes sure the version is stored in an option, and that the default 
         * 15 minute cookie lifespan is stored in an option as well
         *
         * @see    class-core.php DTS_Core->build_cookie_name
         * @uses   update_option(), get_option(), add_option()
         * @return void
         */
        static function activate () {

            //Set an option to store the plugin cookie name
            update_option( 'dts_cookie_name', DTS_Core::build_cookie_name() );
            
            //add the version to the database
            update_option( 'dts_version', DTS_VERSION );
            
            //Add new plugin options - but don't overwrite an old value 
            if ( ! get_option( 'dts_cookie_lifespan' ) ) {
                add_option( 'dts_cookie_lifespan', 0 );
            }
        } // function activate
        

        /**
         * Plugin Deactivation
         * 
         * This method is run statically in dts_controller.php 
         * on the register_deactivation_hook() function
         *
         * @return void
         */
        static function deactivate () {
            
            //Do nothing on deactivation
            
        } // function deactivate


        /**
         * Remove all plugin options stored in the database
         * 
         * This method is run statically in dts_controller.php 
         * on the register_uninstall_hook() function
         *
         * @uses   delete_option()
         * @return void
         */
        static function uninstall () {
            
            //Remove the plugin's settings
            delete_option( 'dts_version' );
            delete_option( 'dts_handheld_theme' );
            delete_option( 'dts_tablet_theme' );
            delete_option( 'dts_low_support_theme' );
            delete_option( 'dts_cookie_name' );
            delete_option( 'dts_cookie_lifespan' );

        } // function uninstall


        /**
         * Run Update Routines
         *
         * @see    includes/class-update.php Class DTS_Update 
         * @return null
         */
        public function update () {
            
            // Trigger the update class
            DTS_Update::factory()->do_update();

        } // function update

        /**
         * Register our widgets 
         * 
         * For displaying 'View Full Website' and 'Return to mobile website' links
         *
         * @access  widgets_init action hook
         * @uses    register_widget()
         * @param   null
         * @return  null
         */
        public function register_widgets () {

            // Register the 'View Full Website' widget
            register_widget( 'DTS_View_Full_Website' );
            
            // Register the 'Return to Mobile Website' widget
            register_widget( 'DTS_Return_To_Mobile_Website' );

        } // function dts_register_widgets


        /**
         * Add a 'Settings' link to the plugin row in the WP-Admin > Plugins page
         *
         * This method is run statically in dts_controller.php 
         * on the 'plugin_action_links' hook
         *
         * @uses  admin_url()
         * @param $links Contains an array of the current plugin links
         * @param $file Contains a string of the main plugin path/filename.php
         * @return $links After adding in our own
         */    
        static function device_theme_switcher_settings_link( $links, $file ) {
            if ( $file == 'device-theme-switcher/dts_controller.php' ) {

                // Insert a new 'Settings' link which points to the 
                // Appearance > Device Themes page
                $links['settings'] = sprintf( 
                    '<a href="%s" class="edit"> %s </a>', 
                    admin_url( 'themes.php?page=device-themes' ), 
                    __( 'Settings', 'device_theme_switcher' ) 
                );

            } // end if

            // Return the links with our new 'Settings' link appended
            return $links;

        } // function device_theme_switcher_settings_link


        /**
         * Build the name of the cookie DTS will create
         *
         * When a user clicks to 'View Full Website' we set a cookie so they can browse
         * the website and retain the full website theme. The following builds the name of the
         * cookie so that "My Magical Website" becomes 'my-magical-website-alternate-theme'
         *
         * @uses   bloginfo()
         * @param  null
         * @return string the name of the cookie being used
         */
        static public function build_cookie_name () {
            // Start with the site name for the cookie name
            $cookie_name = get_bloginfo( 'sitename' );
            
            // Remove special characters
            $cookie_name = preg_replace( '/[^a-zA-Z0-9_%\[().\]\\/-]/s', '', $cookie_name ); 
            
            // Change spaces to hyphens
            $cookie_name = str_replace( ' ', '-', $cookie_name );
            
            // Lowercase everything
            $cookie_name = strtolower( $cookie_name );
            
            // Append some identifying text
            $cookie_name = $cookie_name . '-alternate-theme';
            
            // Return the assembled cookie name
            return $cookie_name;

        } // function build_cookie_name


        /**
         * Determine the current plugin version
         *
         * This function grabs the version stored in a WordPress option, however,
         * pre version 2.0 we never stored the plugin version in an option, so if 
         * there is no stored version we specify it below as '1'
         *
         * @uses   get_option
         * @return decimal plugin version ex. 2.4
         */
        public function get_current_version () {

            // check for the dts_version option (New in Version 2.0)
            $current_version = get_option('dts_version');
            
            // If there is no current version we'll just reference this as 'version 1'
            if ( empty( $current_version ) ) {
                $current_version = '1.0.0';
            }

            $current_version = '1.0.0';

            // return the currently installed plugin version
            return $current_version ;

        } // function get_current_version

    } // Class DTS_Core


    // EOF