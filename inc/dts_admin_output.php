<?php
    // ------------------------------------------------------------------------------
    // ADMIN SETTINGS PAGE  - included view device_theme_switcher::generate_admin_settings_page
    // ------------------------------------------------------------------------------
    
    //Gather all of the currently installed theme names
    if (function_exists('wp_get_themes')) : 
        $installed_themes = wp_get_themes();
    else :
        $installed_themes = get_themes();
    endif;

    //Loop through each of the installed themes
    foreach ($installed_themes as $theme) : 
        //Gather each theme's theme data
        //wp_get_theme was introduced in WordPress v3.4 - this check ensures we're backwards compatible
        if (function_exists('wp_get_theme')) : $theme_data = wp_get_theme( $theme['Stylesheet'] );
        else : $theme_data = get_theme_data( get_theme_root() . '/' . $theme['Stylesheet'] . '/style.css' ); endif;

        //We'll only display a theme if it is an actual / functioning theme with theme data
        if (isset($theme_data)) : 
            //Check if the theme is a child theme
            //In this instance the 'Template' variable will be empty and we're supposed to submit the stylesheet instead
            if (!empty($theme_data['Template'])) : $template = $theme_data['Template'];
            else : $template = $theme['Stylesheet']; endif;

            //Increment $available_themes with each functional theme    
            //We're going to output each array in the value of each theme <option> below
            $available_themes[] = array(
                'name' => $theme->Name,
                'template' => $template,
                'stylesheet' => $theme['Stylesheet']);

            //Store the theme names so we can use array_multisort on $available_theme to sort by name
            $available_theme_names[] = $theme->Name;
        endif;
    endforeach;

    //Alphabetically sort the theme name list for display in the selection dropdowns
    array_multisort($available_theme_names, SORT_ASC, $available_theme_names);

    //Retrieve any DTS theme options which were previously saved
    //The theme option is a url encoded string containing 3 values for name, template, and stylesheet
    parse_str(get_option('dts_handheld_theme'), $dts_handheld_theme);
    parse_str(get_option('dts_tablet_theme'), $dts_tablet_theme);
    parse_str(get_option('dts_low_support_theme'), $dts_low_support_theme);
?>
<div class="wrap">
    <div id="icon-themes" class="icon32"><br></div>
    <h2>Device Themes<br /><br /></h2>
    <form method="post" action="<?php echo admin_url() ?>themes.php?page=device-themes">
        <table>
            <tr>
                <th scope="row" align="right">
                    <label for="dts_handheld_theme"><?php _e("Handheld Theme") ?> &nbsp; &nbsp;</label>
                </th><td>
                    <select name="dts[dts_handheld_theme]">
                        <?php foreach ($available_themes as $theme) : ?>
                            <option value="<?php echo http_build_query($theme)?>" <?php selected($theme['name'], $dts_handheld_theme['name']) ?>><?php echo $theme['name'] ?> &nbsp; </option>
                        <?php endforeach ?>
                    </select>
                </td>
                <td><span class="description">&nbsp; &nbsp; <?php _e("Handheld devices like Apple iPhone, Android, BlackBerry, and more.") ?></span></td>                 
            </tr><tr>
                <th scope="row" align="right">
                    <label for="dts_tablet_theme"><?php _e("Tablet Theme") ?> &nbsp; &nbsp;</label>
                </th><td>
                    <select name="dts[dts_tablet_theme]">
                        <?php foreach ($available_themes as $theme) : ?>
                            <option value="<?php echo http_build_query($theme)?>" <?php selected($theme['name'], $dts_tablet_theme['name']) ?>><?php echo $theme['name'] ?> &nbsp; </option>
                        <?php endforeach ?>
                    </select>
                </td>
                <td><span class="description">&nbsp; &nbsp; <?php _e("Tablet devices like Apple iPad, Galaxy Tab, Kindle Fire, and more.") ?></span></td>
            <tr>
                <th scope="row" align="right">
                    <label for="dts_low_support_theme"><?php _e("Low Support Theme") ?> &nbsp; &nbsp;</label>
                </th><td>
                    <select name="dts[dts_low_support_theme]">
                        <?php foreach ($available_themes as $theme) : ?>
                            <option value="<?php echo http_build_query($theme)?>" <?php selected($theme['name'], $dts_low_support_theme['name']) ?>><?php echo $theme['name'] ?> &nbsp; </option>
                        <?php endforeach ?>
                    </select>
                    <td><span class="description">&nbsp; &nbsp; <?php _e("Devices which lack complete CSS / Javascript support") ?></span></td>
                </td>
            </tr></tr>
                <td colspan="3">
                    <br />
                    <input type="hidden" name="dts_settings_update" value="true" />
                    <input type="submit" value="<?php _e("Save Device Themes") ?>" class="button button-primary" />
                </td>
            </tr>
        </table>
    </form>
</div>