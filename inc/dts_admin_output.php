<?php
    // ------------------------------------------------------------------------------
    // ADMIN SETTINGS PAGE  - included view device_theme_switcher::generate_admin_settings_page
    // ------------------------------------------------------------------------------
    //print_r(wp_get_themes());
    

    //wp_get_themes was introduced in WordPress v3.4 - this check ensures we're backwards compatible
    if (function_exists('wp_get_themes')) : 
        foreach (wp_get_themes() as $theme) :
            $installed_themes[] = $theme->name;
        endforeach;
    else :
       $installed_themes = array_keys(get_themes());
    endif;

    //Alphabetically sort the theme list for display in the selection dropdowns
    natcasesort($installed_themes);

    $dts_handheld_theme     = get_option('dts_handheld_theme');
    $dts_tablet_theme       = get_option('dts_tablet_theme');
    $dts_low_support_theme  = get_option('dts_low_support_theme');
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
                    <select name="dts_handheld_theme">
                        <?php foreach ($installed_themes as $key => $theme) : ?>
                            <option value="<?php echo $theme ?>" <?php selected($theme, $dts_handheld_theme) ?>><?php echo $theme ?> &nbsp; </option>
                        <?php endforeach ?>
                    </select>
                </td>
                <td><span class="description">&nbsp; &nbsp; <?php _e("Handheld devices like Apple iPhone, Android, BlackBerry, and more.") ?></span></td>                 
            </tr><tr>
                <th scope="row" align="right">
                    <label for="dts_tablet_theme"><?php _e("Tablet Theme") ?> &nbsp; &nbsp;</label>
                </th><td>
                    <select name="dts_tablet_theme">
                        <?php foreach ($installed_themes as $key => $theme) : ?>
                            <option value="<?php echo $theme ?>" <?php selected($theme, $dts_tablet_theme) ?>><?php echo $theme ?>&nbsp; &nbsp;</option>
                        <?php endforeach ?>
                    </select>
                </td>
                <td><span class="description">&nbsp; &nbsp; <?php _e("Tablet devices like Apple iPad, Galaxy Tab, Kindle Fire, and more.") ?></span></td>
            <tr>
                <th scope="row" align="right">
                    <label for="dts_low_support_theme"><?php _e("Low Support Theme") ?> &nbsp; &nbsp;</label>
                </th><td>
                    <select name="dts_low_support_theme">
                        <?php foreach ($installed_themes as $key => $theme) : ?>
                            <option value="<?php echo $theme ?>" <?php selected($theme, $dts_low_support_theme) ?>><?php echo $theme ?>&nbsp; &nbsp;</option>
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