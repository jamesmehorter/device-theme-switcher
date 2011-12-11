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
		// CALLBACK FUNCTION SPECIFIED IN: add_options_page()
		// ------------------------------------------------------------------------------
		public function generate_admin_settings_page() {
			//Include an external php file containing output for the admin settings page
			include('dts_admin_output.php'); 
		} //END member function generate_admin_settings_page
		
		
		public function deliver_theme_to_viewer () {
			//Determine which type of device is viewing the website
			
			//$isiPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
			
			//If the device is a handheld or tablet
				//if the user has defined alternative themes for the devices
					//Deliver the cooresponding theme
		}
	} //END class definition for the device_theme_switcher
?>