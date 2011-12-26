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
	.updated.dts {
		margin-top: 15px ;
	}
    #dts_settings_page_wrapper {
        margin: 10px 0 0 15px ;
    }
</style>
<div id="dts_settings_page_wrapper">
    <form method="post" action="<?php bloginfo('url') ?>/wp-admin/themes.php?page=device-themes">
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
            </tr>
                <td colspan="2" align="right">
                    <br /><br />
                    <input type="hidden" name="dts_settings_update" value="true" />
                    <input type="submit" value="<?php _e("Save Device Themes") ?>" />
                </td>
            </tr>
        </table>
    </form>
</div>