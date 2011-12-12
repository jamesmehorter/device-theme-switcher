<?php
	/** 
		Plugin Name: Device Theme Switcher
		Plugin URI: http://www.picadesign.com.com
		Description: Plugin that allows you to set a separate theme for handheld and tablet devices
		Author: James Mehorter @ Pica Design
		Version: 0.1
		Author URI: http://www.jamesmehorter.com
	*/
	
	// ------------------------------------------------------------------------
	// REGISTER HOOKS & CALLBACK FUNCTIONS:                                    
	// ------------------------------------------------------------------------
	register_activation_hook(__FILE__, array('device_theme_switcher', 'add_defaults'));
	register_uninstall_hook(__FILE__, array('device_theme_switcher', 'remove'));
	add_action('admin_init', array('device_theme_switcher', 'init') );
	add_action('admin_menu', array('device_theme_switcher', 'admin_menu'));
	
	// ------------------------------------------------------------------------
	// DEVICE READING & ALTERNATE THEME OUTPUT
	// ------------------------------------------------------------------------
	//Include the MobileESP code library for acertaining device user agents
	include('mdetect.php');
	$uagent_info = new uagent_info;
	
	
	if (!is_admin()):
		//Detect if the device is a handheld, most likely a smart phone
		if ($uagent_info->DetectTierIphone()) : 
			add_filter('stylesheet', array('device_theme_switcher', 'deliver_handheld_stylesheet'));
			add_filter('template', array('device_theme_switcher', 'deliver_handheld_template'));
		endif ;
	
		//Detect if the device is a tablets
		if ($uagent_info->DetectTierTablet()) : 
			add_filter('stylesheet', array('device_theme_switcher', 'deliver_tablet_stylesheet'));
			add_filter('template', array('device_theme_switcher', 'deliver_tablet_template'));
		endif ;	
	endif;
	
	
	// ------------------------------------------------------------------------
	// DEVICE THEME SWITCHER CONTROLLER CLASS                                    
	// ------------------------------------------------------------------------
	class device_theme_switcher {
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_action('admin_init', array('device_theme_switcher', 'init') );
		// ------------------------------------------------------------------------------
		public function init() {
			//Register our plugin settings in the wp-options table
			register_setting('dts_settings', 'dts_device_themes');
		}//END member function init
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: register_activation_hook(__FILE__, array('device_theme_switcher', 'add_defaults'));
		// ------------------------------------------------------------------------------
		//Set plugin default settings
		public function add_defaults () {
			$default_values = array(
				'handheld' => '',
				'tablet' => ''
			);
			update_option('dts_device_themes', $arr);
		}//END member function add_defaults
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_action('admin_menu', array('device_theme_switcher', 'admin_menu'));
		// ------------------------------------------------------------------------------
		public function admin_menu () {
			//Create the admin menu page
			add_options_page( __('Device Theme Switcher'), __('Device Themes'), 10, basename(__FILE__), array('device_theme_switcher', 'generate_admin_settings_page'));
		}//END member function admin_menu
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: register_uninstall_hook(__FILE__, array('device_theme_switcher', 'remove'));
		// ------------------------------------------------------------------------------
		public function remove () {
			//Remove the plugin and it's settings
			delete_option('dts_device_themes');
		}//END member function remove
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION SPECIFIED IN: add_options_page()
		// ------------------------------------------------------------------------------
		public function generate_admin_settings_page() {
			//Include an external php file containing output for the admin settings page
			include('dts_admin_output.php'); 
		} //END member function generate_admin_settings_page
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_filter('stylesheet', array('device_theme_switcher', 'deliver_handheld_stylesheet'));
		//								add_filter('template', array('device_theme_switcher', 'deliver_handheld_stylesheet'));
		// ------------------------------------------------------------------------------
		public function deliver_handheld_stylesheet(){
			$device_theme = get_option('dts_device_themes');
			$themeList = get_themes();
			foreach ($themeList as $theme) :
				if ($theme['Name'] == $device_theme['handheld']) :
					return $theme['Stylesheet'];
				endif;
			endforeach;
		} //END member function deliver_handheld_template
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_filter('stylesheet', array('device_theme_switcher', 'deliver_handheld_template'));
		//								 add_filter('template', array('device_theme_switcher', 'deliver_handheld_template'));
		// ------------------------------------------------------------------------------
		public function deliver_handheld_template(){
			$device_theme = get_option('dts_device_themes');
			$themeList = get_themes();
			foreach ($themeList as $theme) :
				if ($theme['Name'] == $device_theme['handheld']) :
					//For the template file name, we need to check if the theme being set is a child theme
					//If it is a child theme, then we need to grab the parent theme and pass that instead 
					$theme_data = get_theme_data("wp-content/themes/{$theme['Stylesheet']}/style.css");
					if (isset($theme_data) && $theme_data['Template'] != "") :
						return $theme_data['Template'];
					else :
						return $theme['Stylesheet'];
					endif ;
				endif;
			endforeach;
		} //END member function deliver_tablet_template
		
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_filter('stylesheet', array('device_theme_switcher', 'deliver_tablet_stylesheet'));
		//								add_filter('template', array('device_theme_switcher', 'deliver_tablet_stylesheet'));
		// ------------------------------------------------------------------------------
		public function deliver_tablet_stylesheet(){
			$device_theme = get_option('dts_device_themes');
			$themeList = get_themes();
			foreach ($themeList as $theme) :
				if ($theme['Name'] == $device_theme['tablet']) :
					return $theme['Stylesheet'];
				endif;
			endforeach;
		} //END member function deliver_tablet_template
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_filter('stylesheet', array('device_theme_switcher', 'deliver_tablet_template'));
		//								 add_filter('template', array('device_theme_switcher', 'deliver_tablet_template'));
		// ------------------------------------------------------------------------------
		public function deliver_tablet_template(){
			$device_theme = get_option('dts_device_themes');
			$themeList = get_themes();
			foreach ($themeList as $theme) :
				if ($theme['Name'] == $device_theme['tablet']) :
					//For the template file name, we need to check if the theme being set is a child theme
					//If it is a child theme, then we need to grab the parent theme and pass that instead 
					$theme_data = get_theme_data("wp-content/themes/{$theme['Stylesheet']}/style.css");
					if (isset($theme_data) && $theme_data['Template'] != "") :
						return $theme_data['Template'];
					else :
						return $theme['Stylesheet'];
					endif ;
				endif;
			endforeach;
		} //END member function deliver_tablet_template
	} //END class definition for the device_theme_switcher
?>