<?php
	// ------------------------------------------------------------------------------
	// ADMIN SETTINGS PAGE  - included view device_theme_switcher::generate_admin_settings_page
	// ------------------------------------------------------------------------------
	$themeList = array_keys(get_themes());
	natcasesort($themeList);
	$device_theme = get_option('dts_device_themes');
?>
<!-- This is the html page that displays on the plugin settings page -->
<style type="text/css">
    #dts_settings_page_wrapper {
        margin: 15px 0 0 15px ;
    }
</style>
<div id="dts_settings_page_wrapper">
    <form method="post" action="options.php">
        <?php settings_fields('dts_settings'); ?>
        <table>
            <tr>
                <td colspan="2">
                    <h3>Select your device-specific themes below</h3>
                </td>
            </tr><tr>
                <td>
                    <label for="dts_device_themes[handheld]"><?php _e("Handheld Theme") ?></label>
                </td><td align="right">
                    <select name="dts_device_themes[handheld]">
                        <?php foreach ($themeList as $key => $theme) : ?>
                            <option value="<?php echo $theme ?>" <?php selected($theme, $device_theme['handheld']) ?>><?php echo $theme ?></option>
                        <?php endforeach ?>
                    </select>
                </td>						
            </tr><tr>
                <td>
                    <label for="dts_device_themes[tablet]"><?php _e("Tablet Theme") ?></label>
                </td><td align="right">
                    <select name="dts_device_themes[tablet]">
                        <?php foreach ($themeList as $key => $theme) : ?>
                            <option value="<?php echo $theme ?>" <?php selected($theme, $device_theme['tablet']) ?>><?php echo $theme ?></option>
                        <?php endforeach ?>
                    </select>
                </td>
            </tr>
                <td colspan="2" align="right">
                    <br /><br />
                    <input type="submit" value="<?php _e("Save Device Themes") ?>" />
                </td>
            </tr>
        </table>
    </form>
</div>