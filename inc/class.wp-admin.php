<?php
    class DTS_Admin {
        //Display some output in the WP Admin Dashboard 'Right Now' section
        //      + Show what device these have been selected below what default theme is active
        static function right_now () { 
            parse_str(get_option('dts_handheld_theme'), $dts['themes']['handheld']);
            parse_str(get_option('dts_tablet_theme'), $dts['themes']['tablet']) ?>

            <br />Handheld Device Theme <a href="<?php echo admin_url('themes.php?page=device-themes') ?>"><strong><?php echo $dts['themes']['handheld']['name'] ?></strong></a> 
            <br />Tablet Device Theme <a href="<?php echo admin_url('themes.php?page=device-themes') ?>"><strong><?php echo $dts['themes']['tablet']['name'] ?></strong></a><?php
        }//right_now
        
        // ------------------------------------------------------------------------------
        // CALLBACK MEMBER FUNCTION FOR: add_action('admin_menu', array('device_theme_switcher', 'admin_menu'));
        // ------------------------------------------------------------------------------
        static function admin_menu () {
            //Create the admin menu page
            add_submenu_page('themes.php',  __('Device Theme Switcher'), __('Device Themes'), 'manage_options', 'device-themes', array('DTS_Admin', 'generate_admin_settings_page'));
        }//admin_menu

        // ------------------------------------------------------------------------------
        // CALLED MEMBER FUNCTION FOR: if ($_POST) : $dts->update; ...
        // ------------------------------------------------------------------------------
        static function load () {
            //Unfortunetly we can't use the settings api on a subpage, so we need to check for and update any options this plugin uses
            if ($_POST) : if ($_POST['dts_settings_update'] == "true") :
                //Loop through the 3 device <select>ed <option>s in the admin form
                foreach ($_POST['dts_theme'] as $selected_device => $chosen_theme) : 
                    if ($chosen_theme == "Use Handheld Setting") : 
                        //The user is trying to disable the low support theme option
                        //Go ahead and remove the option for it
                        delete_option($selected_device);
                    else :
                        //Update each of the 3 dts database options with a urlencoded array of the selected theme 
                        //The array contains 3 values: name, template, and stylesheet - these are all we need for use later on
                        update_option($selected_device, $chosen_theme);
                    endif;
                endforeach ; 

                //Save the chosen session lifetime
                update_option('dts_session_lifetime', $_POST['dts_session_lifetime']);

                //Display an admin notice letting the user know the save was successfull
                add_action('admin_notices', array('DTS_Admin', 'admin_save_settings_notice'));
            endif; endif;
        }//update
        
        // ------------------------------------------------------------------------------
        // CALLBACK MEMBER FUNCTION SPECIFIED IN: add_options_page()
        // ------------------------------------------------------------------------------
        static function generate_admin_settings_page() {
            //Gather all of the currently installed theme names so they can be displayed in the <select> boxes below
            if (function_exists('wp_get_themes')) : 
                $installed_themes = wp_get_themes();
            else :
                $installed_themes = get_themes();
            endif;
            //Loop through each of the installed themes and build a cache array of themes the user can choose from below
            foreach ($installed_themes as $theme) : 
                //Pre WordPress 3.4 $theme was an array with upper case keyes
                if (is_array($theme)) : 
                    $name = $theme['Name'];
                    $template = $theme['Template'];
                    $stylesheet = $theme['Stylesheet'];
                endif;
                //Post WordPress 3.4 $theme is an instance of the WP_Theme object with lowercase variables
                if (is_object($theme)) : 
                    $name = $theme->name;
                    $template = $theme->template;
                    $stylesheet = $theme->stylesheet;
                endif;
                $available_themes[] = array(
                    'name' => $name,
                    'template' => $template,
                    'stylesheet' => $stylesheet);
                //Store the theme names so we can use array_multisort on $available_theme to sort by name
                $available_theme_names[] = $name;
            endforeach;
            //Alphabetically sort the theme name list for display in the selection dropdowns
            array_multisort($available_theme_names, SORT_ASC, $available_theme_names);
            //Get the set option if it exists
            $dts['session_lifetime'] = get_option('dts_session_lifetime');
            //Retrieve any DTS theme options which were previously saved
            //The theme option is a url encoded string containing 3 values for name, template, and stylesheet
            parse_str(get_option('dts_handheld_theme'), $dts['themes']['handheld']);
            parse_str(get_option('dts_tablet_theme'), $dts['themes']['tablet']);
            parse_str(get_option('dts_low_support_theme'), $dts['themes']['low_support']);
            //Ensure there are default values in each of the $dts['themes']
            foreach ($dts['themes'] as $device => $theme) : 
                if (empty($theme)) : $dts['themes'][$device] = array('name' => '', 'template' => '', 'stylesheet' => ''); endif;
            endforeach ?>
            <style type="text/css">
                div.wrap.device-theme-switcher-settings table td {
                    padding: 0 5px 0 5px ;
                }
                    div.wrap.device-theme-switcher-settings select {
                        width: 155px ;
                    }
                .optional-settings-toggle, .help-and-support-toggle {
                    font-size: 0.9em ;
                    outline: none ;
                }
                .optional-settings, .help-and-support {
                    max-width: 850px ;
                    display: none ; /* We'll enable this via JavaScript */
                }
            </style>
            
            <div class="wrap device-theme-switcher-settings">
                <div id="icon-themes" class="icon32"><br></div>
                <h2>Device Themes<br /><br /></h2>
                <form method="post" action="<?php echo admin_url() ?>themes.php?page=device-themes">
                    <table>
                        <tr>
                            <th scope="row" align="right" width="150px">
                                <label for="dts_handheld_theme"><?php _e("Handheld Theme") ?></label>
                            </th><td>
                                <select name="dts_theme[dts_handheld_theme]">
                                    <?php foreach ($available_themes as $theme) : ?>
                                        <option value="<?php echo build_query($theme)?>" <?php selected($theme['name'], $dts['themes']['handheld']['name']) ?>><?php echo $theme['name'] ?> &nbsp; </option>
                                    <?php endforeach ?>
                                </select>
                            </td>
                            <td><span class="description"> <?php _e("Handheld devices like Apple iPhone, Android, BlackBerry, and more.") ?></span></td>                 
                        </tr><tr>
                            <th scope="row" align="right">
                                <label for="dts_tablet_theme"><?php _e("Tablet Theme") ?> </label>
                            </th><td>
                                <select name="dts_theme[dts_tablet_theme]">
                                    <?php foreach ($available_themes as $theme) : ?>
                                        <option value="<?php echo build_query($theme)?>" <?php selected($theme['name'], $dts['themes']['tablet']['name']) ?>><?php echo $theme['name'] ?> &nbsp; </option>
                                    <?php endforeach ?>
                                </select>
                            </td>
                            <td><span class="description"> <?php _e("Tablet devices like Apple iPad, Galaxy Tab, Kindle Fire, and more.") ?></span></td>
                        </tr><tr>
                            <th scope="row" align="right">
                                <a href="#" class="optional-settings-toggle"><?php _e("Show Optional Settings") ?></a> 
                            </th><td colspan="2"></td>
                        </tr>
                    </table>
                    <div class="optional-settings">
                        <table>
                            <tr>
                                <th scope="row" align="right" width="150px">
                                    <label for="dts_low_support_theme"><?php _e("Low-Support Theme") ?> </label>
                                </th><td>
                                    <select name="dts_theme[dts_low_support_theme]">
                                        <option>Use Handheld Setting</option><?php 
                                        foreach ($available_themes as $theme) : ?>
        
                                        <option value="<?php echo build_query($theme)?>" <?php selected($theme['name'], $dts['themes']['low_support']['name']) ?>><?php echo $theme['name'] ?> &nbsp; </option><?php endforeach ?>

                                    </select>
                                </td><td>
                                    <span class="description"> <?php _e("Devices which lack complete CSS & JavaScipt Support.") ?></span>
                                </td>
                            </tr><tr>
                                <th scope="row" align="right"valign="top">
                                    <label for="dts_session_lifetime"><?php _e("Session Lifetime") ?> </label>
                                </th><td valign="top">
                                    <?php 
                                        function array_multi_search($needle, $haystack, $keytosearch) { 
                                            $matched_key = false ;
                                            return $matched_key ;
                                        }//array_multi_search
                                        //Build a list of default session lifetimes
                                        $dts_session_lifetimes = array(
                                            array('value' => 0, 'text' => _("Until Browser is closed")),
                                            array('value' => 300, 'text' => _("5 Minutes")),
                                            array('value' => 900, 'text' => _("15 Minutes (Plugin Default)")),
                                            array('value' => 1800, 'text' => _("30 Minutes")),
                                            array('value' => 2700, 'text' => _("45 Minutes")),
                                            array('value' => 3600, 'text' => _("60 Minutes")),
                                            array('value' => 4500, 'text' => _("75 Minutes")),
                                            array('value' => 5400, 'text' => _("90 Minutes")));
                                        //Grab the server's session parms
                                        $session_params = session_get_cookie_params();
                                        
                                        //See if the server's session lifetime is already in our default list
                                        $lifetime_cookie_match = false;
                                        foreach($dts_session_lifetimes as $key => $value) : 
                                            if($key == $session_params['lifetime']) $lifetime_cookie_match = $key ;
                                        endforeach;
                                       
                                        //If it is found in the default list..
                                        if ($lifetime_cookie_match !== false)
                                            //Append some descriptive text our default 'text' index (that displays in the <option> below)
                                            $dts_session_lifetimes[$lifetime_cookie_match]['text'] .= _(" (Set in php.ini)");
                                        else
                                            //add it to the array at position 1
                                            array_splice($dts_session_lifetimes, 1, 0, array(
                                                'value' => $session_params['lifetime'], 
                                                'title' => _($session_params['lifetime'] * 60 . ' Minutes (Set in php.ini)')));
                                    ?>
                                    <select name="dts_session_lifetime"><?php     
                                        foreach ($dts_session_lifetimes as $lifetime) : ?>
                                        <option value="<?php echo $lifetime['value'] ?>" <?php selected($dts['session_lifetime'], $lifetime['value']) ?>><?php echo $lifetime['text'] ?></option>

                                        <?php endforeach ?>
                                    </select>
                                </td><td>
                                    <span class="description">
                                    <?php _e("Length of time until a user is redirected back to their initial device theme <br />after they've requested the 'Desktop' Version.<br /><strong>Note:</strong> A lot of mobile browsers do not actually close and end the session!") ?><br />
                                    </span>
                                </td>                 
                            </tr>
                        </table>
                    </div>
                    <table>
                        <tr>
                            <th scope="row" align="right" width="150px">
                                <input type="hidden" name="dts_settings_update" value="true" />
                                <input type="submit" value="<?php _e("Save Settings") ?>" class="button button-primary" /> 
                            </th></td colspan="2"></td>
                        </tr>
                    </table>
                </form>
                <br /><br />
                <table>
                    <tr>
                        <th scope="row" align="right" width="150px">
                            <a href="#" class="help-and-support-toggle"><?php _e("Help & Support") ?></a> 
                        </th><td colspan="2"></td>
                    </tr>
                </table>
                <div class="help-and-support">
                    <table>
                        <tr>
                            <th scope="row" align="right" width="150px">
                                <?php _e("Helpful Links") ?>
                            </th><td align="left">
                                <a href="http://wordpress.org/support/plugin/device-theme-switcher" title="Device Theme Switcher Support Forum" target="_blank"><?php _e("Support Forum") ?></a> | 
                                <a href="http://wordpress.org/plugins/device-theme-switcher/faq/" title="Device Theme Switcher FAQ" target="_blank"><?php _e("FAQ") ?></a>
                            </td>
                        </tr><tr>
                            <th scope="row" align="right" width="150px" valign="top">
                                <?php _e("Shortcodes") ?> 
                            </th><td align="left">
                                <span class="description"><?php _e("Display a link to 'View Full Website'") ?></span><br />
                                [link_to_full_website link_text="View Full Website" css_classes="blue-text, alignleft"]<br /><br />
                                <span class="description"><?php _e("Display a link to 'Return to Mobile Website'") ?></span><br />
                                [link_back_to_device link_text="Return to Mobile Website" css_classes="red-text, alignright"]
                                <br /><br />
                            </td>
                        </tr><tr>
                            <th scope="row" align="right" valign="top">
                                <?php _e("Template Tags") ?> 
                            </th><td align="left" >
                                <span class="description"><?php _e("Display a link to 'View Full Website'") ?></span><br />
                                <?php echo htmlentities("<?php") ?> link_to_full_website($link_text = "View Full Website", $css_classes = array("blue-text", "alignleft"), $echo = true); <?php echo "?>" ?>
                                <br /><br />
                                <span class="description"><?php _e("Display a link to 'Return to Mobile Website'") ?></span><br />
                                <?php echo htmlentities("<?php") ?> link_back_to_device($link_text = "Return to Mobile Website", $css_classes = array("red-text", "alignright"), $echo = true); <?php echo "?>" ?>
                                <br /><br />
                            </td>
                        </tr><tr>
                            <th scope="row" align="right" valign="top">
                                <?php _e("URL Paramaters") ?>
                            </th><td align="left">
                                <span class="description"> <?php _e("Helpful for developers to view a specific device theme") ?></span>
                                <br />
                                <a href="<?php bloginfo('url') ?>/?theme=handheld" title="View Handheld Theme" target="_blank"><?php bloginfo('url') ?>/?theme=handheld</a><br />
                                <a href="<?php bloginfo('url') ?>/?theme=tablet" title="View Tablet Theme" target="_blank"><?php bloginfo('url') ?>/?theme=tablet</a><br />
                                <a href="<?php bloginfo('url') ?>/?theme=low_support" title="View Low-Support Theme" target="_blank"><?php bloginfo('url') ?>/?theme=low_support</a><br />
                                <a href="<?php bloginfo('url') ?>/?theme=active" title="View Active Theme" target="_blank"><?php bloginfo('url') ?>/?theme=active</a>
                            </td>
                        </tr>
                    </table>
                </div>
                <script type="text/javascript">
                    (function($){
                        $('.optional-settings-toggle').click(function(){
                            oThis = $(this)
                            $('.optional-settings').slideToggle(600, function(){
                                if ($(this).is(':visible')) {
                                    oThis.text('Hide Optional Settings')
                                } else {
                                    oThis.text('Show Optional Settings')
                                }
                            })
                        })
                        $('.help-and-support-toggle').click(function(){
                            $('.help-and-support').slideToggle(600, function(){
                                //complete
                            })
                        })
                    })(jQuery)
                </script>
            </div><?php
        } //generate_admin_settings_page
        // ------------------------------------------------------------------------------
        // ADMIN NOTICES
        // ------------------------------------------------------------------------------
        static function admin_activation_notice(){
            //Print a message to the admin window letting the user know thier settings have been saved
            //echo '<div class="dts activated"><p>Welcome to Device Theme Switcher!</p></div>';
        }//admin_activation_notice
        static function admin_save_settings_notice(){
            //Print a message to the admin window letting the user know thier settings have been saved
            //The CSS used to style this message is located in dts_admin_output.php
            echo '<div class="dts updated"><p>Settings saved.</p></div>';
        }//admin_save_settings_notice
    }//Class DTS_Admin