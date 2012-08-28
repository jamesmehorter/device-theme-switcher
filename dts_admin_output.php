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
<!-- This is the html page that displays on the plugin settings page -->
<style type="text/css">
    .updated.dts {
        margin-top: 15px ;
    }
    #dts_settings_page_wrapper {
        margin: 20px 20px 20px 15px ;
    }
</style>
<div id="dts_settings_page_wrapper">
    <h3>Select your device-specific themes below</h3>
    <em>Each selected theme below will be given to the respective device indicated. I.e. The chosen tablet theme will only be visible to users who visit your website using a tablet device. 
        <br /><br />
        The Low Support theme is given to devices that lack complete CSS / Javascript support, like older model phones. This option is helpful if you would like to give a text-only version of your theme to those users.
    </em>
    <br /><br />
    <form method="post" action="<?php echo admin_url() ?>themes.php?page=device-themes">
        <table>
            <tr>
                <td>
                    <label for="dts_handheld_theme"><?php _e("Handheld Theme") ?></label>
                </td><td align="right">
                    <select name="dts_handheld_theme">
                        <?php foreach ($installed_themes as $key => $theme) : ?>
                            <option value="<?php echo $theme ?>" <?php selected($theme, $dts_handheld_theme) ?>><?php echo $theme ?> &nbsp; </option>
                        <?php endforeach ?>
                    </select>
                </td>                       
            </tr><tr>
                <td>
                    <label for="dts_tablet_theme"><?php _e("Tablet Theme") ?></label>
                </td><td align="right">
                    <select name="dts_tablet_theme">
                        <?php foreach ($installed_themes as $key => $theme) : ?>
                            <option value="<?php echo $theme ?>" <?php selected($theme, $dts_tablet_theme) ?>><?php echo $theme ?>&nbsp; &nbsp;</option>
                        <?php endforeach ?>
                    </select>
                </td>
            <tr>
                <td>
                    <label for="dts_low_support_theme"><?php _e("Low Support Theme") ?></label>
                </td><td align="right">
                    <select name="dts_low_support_theme">
                        <?php foreach ($installed_themes as $key => $theme) : ?>
                            <option value="<?php echo $theme ?>" <?php selected($theme, $dts_low_support_theme) ?>><?php echo $theme ?>&nbsp; &nbsp;</option>
                        <?php endforeach ?>
                    </select>
                </td>
            </tr></tr>
                <td colspan="2" align="right">
                    <br /><br />
                    <input type="hidden" name="dts_settings_update" value="true" />
                    <input type="submit" value="<?php _e("Save Device Themes") ?>" />
                </td>
            </tr>
        </table>
    </form>
</div>