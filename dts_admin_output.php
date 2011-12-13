<?php
	// ------------------------------------------------------------------------------
	// ADMIN SETTINGS PAGE  - included view device_theme_switcher::generate_admin_settings_page
	// ------------------------------------------------------------------------------
	$installed_themes = array_keys(get_themes());
	natcasesort($installed_themes);
	
	$dts_handheld_theme = get_option('dts_handheld_theme');
	$dts_tablet_theme 	= get_option('dts_tablet_theme');
?>
<!-- This is the html page that displays on the plugin settings page -->
<style type="text/css">
    #dts_settings_page_wrapper {
        margin: 15px 0 0 15px ;
    }
</style>
<div id="dts_settings_page_wrapper">
    <form method="post" action="<?php echo $_SERVER['HTTP_REFERRER'] ?>">
        <table>
            <tr>
                <td colspan="2">
                    <h3>Select your device-specific themes below</h3>
                </td>
            </tr><tr>
                <td>
                    <label for="dts_handheld_theme"><?php _e("Handheld Theme") ?></label>
                </td><td align="right">
                    <select name="dts_handheld_theme">
                        <?php foreach ($installed_themes as $key => $theme) : ?>
                            <option value="<?php echo $theme ?>" <?php selected($theme, $dts_handheld_theme) ?>><?php echo $theme ?></option>
                        <?php endforeach ?>
                    </select>
                </td>						
            </tr><tr>
                <td>
                    <label for="dts_tablet_theme"><?php _e("Tablet Theme") ?></label>
                </td><td align="right">
                    <select name="dts_tablet_theme">
                        <?php foreach ($installed_themes as $key => $theme) : ?>
                            <option value="<?php echo $theme ?>" <?php selected($theme, $dts_tablet_theme) ?>><?php echo $theme ?></option>
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