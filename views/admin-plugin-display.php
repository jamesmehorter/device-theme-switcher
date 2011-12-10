<?php
	/**
		Device Theme Switcher Controller Class
	*/
	class device_theme_switcher {
		//Construct our class
		public function __construct () {
			add_action('admin_menu', array($this, 'create_menu'));
			add_action('admin_init', array($this, 'register_settings'));
		}
			//Create the admin menu page
			public function create_menu () {
				add_submenu_page('themes.php', __('Device Theme Switcher'), __('Device Themes'), 'administrator', 'device-theme-switcher', array($this, 'generate_settings_page'));
				
			}
			//Register our plugin settings in the wp-options table
			public function register_settings() {
				register_setting('dts-settings', 'handheld_theme');
				register_setting('dts-settings', 'tablet_theme');
			}
			//Generate and output the plugin settings page
			public function generate_settings_page() {
				echo "blah";
				$handheldTheme 	= get_option('handheld_theme');
				$tabletTheme	= get_option('tablet_theme');
				$themeList 		= get_themes();
				$themeNames 	= array_keys($themeList); 
				$defaultTheme 	= get_current_theme();
				natcasesort($themeNames);
				?>
                <div id="dts_settings_page_wrapper">
                    <form>
                    	<select name="dts_
                    </form>
                </div>
				<?php 
			} //End Plugin Settings Page Output 
	} //End device_theme_switcher class
?>