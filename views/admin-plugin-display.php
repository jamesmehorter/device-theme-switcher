<?php
	// create custom plugin settings menu
	add_action('admin_menu', 'dts_create_menu');
	
	function dts_create_menu() {
		add_submenu_page('themes.php', 'Device Theme Switcher', 'Device Themes', 'administrator', 'device-theme-switcher', 'dts_settings_page');
		add_action('admin_init', 'register_dts_settings');
	}
	
	function register_dts_settings() {
		register_setting('mts-settings-group', 'handheld_theme');
		register_setting('mts-settings-group', 'tablet_theme');
	}
	
	function dts_settings_page() {
		$handheldTheme 	= get_option('handheld_theme');
		$tabletTheme	= get_option('tablet_theme');
		$themeList 		= get_themes();
		$themeNames 	= array_keys($themeList); 
		$defaultTheme 	= get_current_theme();
		natcasesort($themeNames);
?>

        <div id="dts_settings_page_wrapper">
            Test
        </div>

<?php } //End Plugin Settings Page Output ?>