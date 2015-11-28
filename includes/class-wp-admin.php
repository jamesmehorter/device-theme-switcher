<?php
    // Bail if this file is being accessed directly
    defined( 'ABSPATH' ) OR exit;

    /**
     * Load the plugin admin features
     *
     * The admin features include the display of the status output in the Dashboard
     * 'Right Now' widget. They also create an admin page at Appearance > Device Themes
     * for the website admin to save the plugin settings
     *
     * @todo Build and check nonce for admin form, sanitize user input
     */
    class DTS_Admin {

        /**
         * Internally stored reference to the single instance of this class
         * @var object
         */
        private static $_instance;

        /**
         * Return the single instance of this class
         *
         * @return object Instance of this class
         */
        static function get_instance () {

            if ( ! isset( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;

        } // function get_instance


        /**
         * DTS_Admin constructor
         */
        public function __construct() {
            // Create our plugin admin page under the 'Appearance' menu
            add_action( 'admin_menu', array( $this, 'register_admin_page' ), 10, 0 );

            // Check if we need to save any form data that was submitted
            add_action( 'load-appearance_page_device-themes', array( $this, 'save_admin_page_settings' ), 10, 0 );

            // Display a 'Settings' link with the plugin in the plugins list
            add_filter( 'plugin_action_links', array( $this, 'plugins_page_settings_link' ), 10, 2 );

            // Add admin scripts and styles used to display the admin settings view
            add_action( 'admin_init', array( $this, 'enqueue_admin_scripts' ) );
        }

        /**
         * Create the Appearance > Device Themes page
         *
         * Called via admin_menu action
         */
        public function register_admin_page () {

            // Create the admin menu page
            add_submenu_page(
                'themes.php',
                __( 'Device Theme Switcher', 'device-theme-switcher' ),
                __( 'Device Themes', 'device-theme-switcher' ),
                'manage_options',
                'device-themes',
                array( $this, 'generate_admin_settings_page' )
            );

        } // function register_admin_page

        /**
         * Enqueue admin scripts
         *
         * @uses    wp_enquque_scipt
         * @param   null
         * @return  null
         */
        public function enqueue_admin_scripts() {

            // Enqueue our JavaScript
            wp_enqueue_script(
                'device-theme-switcher-admin-scripts', // handle
                DTS_URL . 'assets/js/device-theme-switcher-admin-scripts.min.js', // srouce
                array( 'jquery' ), // dependencies
                DTS_VERSION, // version
                true // in footer
            );

            // Enqueue our Stylesheet
            wp_enqueue_style(
                'device-theme-switcher-admin-styles', //handle
                DTS_URL . 'assets/css/device-theme-switcher-admin-styles.css', // source
                array(), // dependencies
                DTS_VERSION, //version
                'all' // media
            );

        } // function enqueue_admin_scripts


        /**
         * Add a 'Settings' link to the plugin row in the WP-Admin > Plugins page
         *
         * This method is run statically in dts_controller.php
         * on the 'plugin_action_links' hook
         *
         * @uses    admin_url
         * @param   array $links   The current plugin links
         * @param   string $file   The main plugin path/filename.php
         * @return  array  $links  After adding in our own
         */
        public function plugins_page_settings_link( $links, $file ) {
            if ( 'device-theme-switcher/dts_controller.php' == $file ) {

                // Insert a new 'Settings' link which points to the
                // Appearance > Device Themes page
                $links['settings'] = sprintf(
                    '<a href="%s" class="edit"> %s </a>',
                    admin_url( 'themes.php?page=device-themes' ),
                    __( 'Settings', 'device-theme-switcher' )
                );

            } // end if

            // Return the links with our new 'Settings' link appended
            return $links;

        } // function plugins_page_settings_link


        /**
         * Generate the admin settings page
         *
         * This function is triggered as a callback via add_submenu_page()
         * run on the admin_menu hook
         *
         * @uses   wp_get_themes(), get_themes(), get_option()
         * @param  null
         * @return null
         */
        public function generate_admin_settings_page() {
            global $dts;

            // Gather all of the currently installed theme names so they can be displayed in the <select> boxes below
            if ( function_exists( 'wp_get_themes' ) ) {
                $installed_themes = wp_get_themes();
            } else {
                $installed_themes = get_themes();
            }

            // Loop through each of the installed themes and build a cache array of themes the user can choose from below
            foreach ( $installed_themes as $theme ) {

                // Pre WordPress 3.4 $theme was an array with upper case keys
                if ( is_array( $theme ) ) {
                    $name       = $theme['Name'];
                    $template   = $theme['Template'];
                    $stylesheet = $theme['Stylesheet'];
                }

                // Post WordPress 3.4 $theme is an instance of the WP_Theme object with lowercase variables
                if ( is_object( $theme ) ) {
                    $name       = $theme->name;
                    $template   = $theme->template;
                    $stylesheet = $theme->stylesheet;
                }

                // Aslo add each theme to the list of available themes
                $available_themes[] = array(
                    'name'       => $name,
                    'template'   => $template,
                    'stylesheet' => $stylesheet
                );

                // Store the theme names so we can use array_multisort on $available_theme to sort by name
                $available_theme_names[] = $name;

            } // foreach

            // Alphabetically sort the theme name list for display in the selection dropdowns
            array_multisort( $available_theme_names, SORT_ASC, $available_theme_names );

            // Get the stored cookie lifespan option if it exists
            $dts['cookie_lifespan'] = get_option( 'dts_cookie_lifespan' );

            // And that there is a default value of 0..
            if ( empty( $dts['cookie_lifespan'] ) ) {
                $dts['cookie_lifespan'] = 0 ;
            }

            // Retrieve any DTS theme options which were previously saved
            // The theme option is a url encoded string containing 3 values for name, template, and stylesheet
            parse_str( get_option( 'dts_handheld_theme'), $dts['themes']['handheld'] );
            parse_str( get_option( 'dts_tablet_theme'), $dts['themes']['tablet'] );
            parse_str( get_option( 'dts_low_support_theme'), $dts['themes']['low_support'] );

            // Ensure there are default values in each of the $dts['themes']
            foreach ( $dts['themes'] as $device => $theme ) {
                if ( empty( $theme ) ) {
                    $dts['themes'][$device] = array(
                        'name'       => '',
                        'template'   => '',
                        'stylesheet' => ''
                    );
                }
            } // foreach ?>

            <div class="wrap device-theme-switcher-settings">
                <div id="icon-themes" class="icon32"><br></div>
                <h2>Device Themes<br /><br /></h2>
                <form method="post" action="<?php echo esc_url( get_admin_url() . 'themes.php?page=device-themes' ); ?>">
                    <table>
                        <tr>
                            <th scope="row" align="right" width="150px">
                                <label for="dts_handheld_theme"><?php echo esc_html_e( 'Handheld Theme', 'device-theme-switcher' ); ?></label>
                            </th><td>
                                <select name="dts_theme[dts_handheld_theme]"><?php

                                    foreach ( $available_themes as $theme ) { ?>

                                        <option
                                            value="<?php echo esc_attr( build_query( $theme ) ); ?>" <?php
                                            selected( $theme['name'], $dts['themes']['handheld']['name'] ); ?>>
                                                <?php printf( esc_html( '%s', 'device-theme-switcher'), $theme['name'] ); ?> &nbsp;
                                        </option><?php

                                    } // foreach ?>

                                </select>
                            </td>
                            <td><span class="description"><?php esc_html_e( 'Handheld devices like Apple iPhone, Android, BlackBerry, and more.', 'device-theme-switcher' ); ?></span></td>
                        </tr><tr>
                            <th scope="row" align="right">
                                <label for="dts_tablet_theme"><?php esc_html_e( 'Tablet Theme', 'device-theme-switcher' ); ?></label>
                            </th><td>
                                <select name="dts_theme[dts_tablet_theme]"><?php

                                    foreach ( $available_themes as $theme ) { ?>

                                        <option
                                            value="<?php echo esc_attr( build_query( $theme ) ); ?>" <?php
                                            selected( $theme['name'], $dts['themes']['tablet']['name'] ); ?>><?php
                                                printf( esc_html( '%s', 'device-theme-switcher' ), $theme['name'] ); ?> &nbsp;
                                        </option><?php

                                    } // foreach ?>

                                </select>
                            </td>
                            <td><span class="description"><?php esc_html_e( 'Tablet devices like Apple iPad, Galaxy Tab, Kindle Fire, and more.', 'device-theme-switcher' ); ?></span></td>
                        </tr><tr>
                            <th scope="row" align="right">
                                <a href="#" class="optional-settings-toggle"><?php esc_html_e( 'Show Optional Settings', 'device-theme-switcher' ); ?></a>
                            </th><td colspan="2"></td>
                        </tr>
                    </table>
                    <div class="optional-settings">
                        <table>
                            <tr>
                                <th scope="row" align="right" width="150px">
                                    <label for="dts_low_support_theme"><?php esc_html_e( 'Low-Support Theme', 'device-theme-switcher' ); ?> </label>
                                </th><td>
                                    <select name="dts_theme[dts_low_support_theme]">
                                        <option><?php echo esc_html_e( 'Use Handheld Setting', 'device-theme-switcher' ); ?></option><?php

                                        foreach ( $available_themes as $theme ) { ?>

                                            <option
                                                value="<?php echo esc_attr( build_query( $theme ) ); ?>" <?php
                                                selected( $theme['name'], $dts['themes']['low_support']['name'] ); ?>><?php
                                                    printf( esc_html( '%s', 'device-theme-switcher' ), $theme['name'] ); ?> &nbsp;
                                            </option><?php

                                        } // foreach ?>

                                    </select>
                                </td><td>
                                    <span class="description"><?php esc_html_e( 'Devices which lack complete CSS & JavaScipt Support.', 'device-theme-switcher' ); ?></span>
                                </td>
                            </tr><tr>
                                <th scope="row" align="right" valign="top">
                                    <label for="dts_cookie_lifespan"><?php esc_html_e( 'Cookie Lifespan', 'device-theme-switcher' ); ?></label>
                                </th><td valign="top"><?php

                                    // Build a list of default cookie lifespans
                                    $dts_cookie_lifespans = array(
                                        array( 'value' => 0,    'text' => esc_html( 'When the browser is closed (Default)', 'device-theme-switcher' ) ),
                                        array( 'value' => 900,  'text' => esc_html( '15 Minutes', 'device-theme-switcher' ) ),
                                        array( 'value' => 1800, 'text' => esc_html( '30 Minutes', 'device-theme-switcher' ) ),
                                        array( 'value' => 2700, 'text' => esc_html( '45 Minutes', 'device-theme-switcher' ) ),
                                        array( 'value' => 3600, 'text' => esc_html( '60 Minutes', 'device-theme-switcher' ) ),
                                        array( 'value' => 4500, 'text' => esc_html( '75 Minutes', 'device-theme-switcher' ) ),
                                        array( 'value' => 5400, 'text' => esc_html( '90 Minutes', 'device-theme-switcher' ) ),
                                    ); ?>

                                    <select name="dts_cookie_lifespan"><?php
                                        foreach ( $dts_cookie_lifespans as $cookie_lifespan ) { ?>

                                        <option
                                            value="<?php echo esc_attr( $cookie_lifespan['value'] ); ?>" <?php
                                            selected( $dts['cookie_lifespan'], $cookie_lifespan['value'] ); ?>><?php
                                                printf( esc_html( '%s', 'device-theme-switcher' ), $cookie_lifespan['text'] ); ?>
                                        </option><?php

                                        } // foreach ?>
                                    </select>
                                </td><td>
                                        <span class="description"><?php
                                        esc_html_e( 'Length of time until a user is redirected back to their initial device theme after they\'ve requested the \'Desktop\' Version.', 'device-theme-switcher' ); ?></span>
                                        <br />
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <table>
                        <tr>
                            <th scope="row" align="right" width="150px">
                                <input type="hidden" name="dts_settings_update" value="true" />
                                <input type="submit" value="<?php esc_html_e( 'Save Settings', 'device-theme-switcher' ); ?>" class="button button-primary" />
                            </th></td colspan="2"></td>
                        </tr>
                    </table>
                </form>
                <br /><br />
                <table>
                    <tr>
                        <th scope="row" align="right" width="150px">
                            <a href="#" class="help-and-support-toggle"><?php esc_html_e( 'Help & Support', 'device-theme-switcher' ); ?></a>
                        </th><td colspan="2"></td>
                    </tr>
                </table>
                <div class="help-and-support">
                    <table>
                        <tr>
                            <th scope="row" align="right" width="150px">
                                <?php esc_html_e( 'Helpful Links', 'device-theme-switcher' ); ?>
                            </th><td align="left">
                                <a
                                    href="http://wordpress.org/support/plugin/device-theme-switcher"
                                    title="Device Theme Switcher Support Forum"
                                    target="_blank"><?php
                                        esc_html_e( 'Support Forum', 'device-theme-switcher' ); ?>
                                </a> |
                                <a
                                    href="https://github.com/jamesmehorter/device-theme-switcher/wiki/3-FAQ"
                                    title="Device Theme Switcher FAQ"
                                    target="_blank"><?php
                                        esc_html_e( 'FAQ', 'device-theme-switcher' ); ?>
                                </a> |
                                <a
                                    href="https://github.com/jamesmehorter/device-theme-switcher/wiki/2-Features"
                                    title="Device Theme Switcher Features"
                                    target="_blank"><?php
                                        esc_html_e( 'Features', 'device-theme-switcher' ); ?>
                                </a>
                            </td>
                        </tr><tr>
                            <th scope="row" align="right" width="150px" valign="top">
                                <?php esc_html_e( 'Shortcodes', 'device-theme-switcher' ); ?>
                            </th><td align="left">
                                <span class="description"><?php esc_html_e( 'Display a link to \'View Full Website\'', 'device-theme-switcher' ); ?></span>

                                <br />

                                [link_to_full_website link_text="View Full Website" css_classes="blue-text, alignleft"]

                                <br /><br />

                                <span class="description"><?php esc_html_e( 'Display a link to \'Return to Mobile Website\'', 'device-theme-switcher' ); ?></span>

                                <br />

                                [link_back_to_device link_text="Return to Mobile Website" css_classes="red-text, alignright"]

                                <br /><br />
                            </td>
                        </tr><tr>
                            <th scope="row" align="right" valign="top">
                                <?php esc_html_e( 'Template Tags', 'device-theme-switcher' ); ?>
                            </th><td align="left" >
                                <span class="description"><?php esc_html_e( 'Display a link to \'View Full Website\'', 'device-theme-switcher' ) ?></span>

                                <br /><?php

                                // Output the example php output for the link_to_full_website() function
                                echo htmlentities( '<?php' ); ?> link_to_full_website($link_text = "View Full Website", $css_classes = array("blue-text", "alignleft"), $echo = true); <?php echo htmlentities( '?>' ); ?>

                                <br /><br />

                                <span class="description"><?php esc_html_e( 'Display a link to \'Return to Mobile Website\'', 'device-theme-switcher' ); ?></span>

                                <br /><?php

                                // Output the example php output for the link_back_to_device() function
                                echo htmlentities( '<?php' ); ?> link_back_to_device($link_text = "Return to Mobile Website", $css_classes = array("red-text", "alignright"), $echo = true); <?php echo htmlentities( '?>' ); ?>

                                <br /><br />
                            </td>
                        </tr><tr>
                            <th scope="row" align="right" valign="top"><?php
                                esc_html_e( 'URL Paramaters', 'device-theme-switcher' ); ?>

                            </th><td align="left">
                                <span class="description"><?php esc_html_e( 'Helpful for developers to view a specific device theme', 'device-theme-switcher' ) ?></span>
                                <br />
                                <a
                                    href="<?php echo esc_attr( get_bloginfo('url') . '/?theme=handheld' ); ?>"
                                    title="<?php echo esc_attr_e( 'View Handheld Theme', 'device-theme-switcher' ); ?>"
                                    target="_blank"><?php
                                        echo esc_attr( get_bloginfo( 'url' ) . '/?theme=handheld' ); ?></a>

                                <br />
                                <a
                                    href="<?php echo esc_attr( get_bloginfo( 'url' ) . '/?theme=tablet' ); ?>"
                                    title="<?php echo esc_attr_e( 'View Tablet Theme', 'device-theme-switcher' ); ?>"
                                    target="_blank"><?php
                                        echo esc_html( get_bloginfo( 'url' ) . '/?theme=tablet' ); ?>
                                </a>
                                <br />
                                <a
                                    href="<?php echo esc_attr( get_bloginfo( 'url' ) . '/?theme=low_support' ); ?>"
                                    title="<?php echo esc_attr_e( 'View Low-Support Theme', 'device-theme-switcher' ); ?>"
                                    target="_blank"><?php
                                        echo esc_html( get_bloginfo( 'url' ) . '/?theme=low_support' ); ?>
                                </a>
                                <br />
                                <a
                                    href="<?php echo esc_attr( get_bloginfo( 'url' ) . '/?theme=active' ); ?>"
                                    title="<?php echo esc_attr_e( 'View Active Theme', 'device-theme-switcher' ); ?>"
                                    target="_blank"><?php
                                        echo esc_html( get_bloginfo('url') . '/?theme=active' ); ?>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div><?php
        } // generate_admin_settings_page


        /**
         * Save the plugin settings
         *
         * Run via load-appearance_page_device-themes in dts_controller.php which
         * fires on the settings form on Appearance > Device Themes
         *
         * @uses    delete_option(), update_option(), add_option()
         * @return  null
         */
        public function save_admin_page_settings() {
            // Unfortunately we can't use the settings api on a subpage, so we need to check for and update any options this plugin uses
            if ( $_POST ) {
                if ( 'true' == $_POST['dts_settings_update'] ) {

                    // Loop through the 3 device <select>ed <option>s in the admin form
                    foreach ( $_POST['dts_theme'] as $selected_device => $chosen_theme ) {

                        if ( 'Use Handheld Setting' == $chosen_theme ) {
                            // The user is trying to disable the low support theme option
                            // Go ahead and remove the option for it
                            delete_option( $selected_device );

                        } else {

                            // Update each of the 3 dts database options with a urlencoded array of the selected theme
                            // The array contains 3 values: name, template, and stylesheet - these are all we need for use later on
                            update_option( $selected_device, $chosen_theme );
                        }

                    } // foreach

                    // Save the chosen session lifetime
                    update_option( 'dts_cookie_lifespan', $_POST['dts_cookie_lifespan'] );

                    // Display an admin notice letting the user know the save was successfull
                    add_action( 'admin_notices', array( $this, 'admin_save_settings_notice' ) );

                } // $_POST['dts_settings_update'] == "true"
            } // if $_POST
        } // function load

        /**
         * Display a notice in the admin after settings have been saved
         *
         * @param   null
         * @return  null
         */
        public function admin_save_settings_notice(){
            // Print a message to the admin window letting the user know thier settings have been saved
            // The CSS used to style this message is located in dts_admin_output.php ?>
            <div class="dts updated"><p><?php echo esc_html_e( 'Settings saved.', 'device-theme-switcher' ); ?></p></div><?php

        } // admin_save_settings_notice
    } // Class DTS_Admin


    // EOF