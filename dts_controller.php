<?php
	/** 
		Plugin Name: Device Theme Switcher
		Plugin URI: http://www.picadesign.com.com
		Description: This plugin allows you to set a separate theme for handheld and tablet devices
		Author: James Mehorter @ Pica Design
		Version: 1.0
		Author URI: http://www.jamesmehorter.com
	*/
	
	// ------------------------------------------------------------------------
	// REGISTER HOOKS & CALLBACK FUNCTIONS:                                    
	// ------------------------------------------------------------------------
	register_activation_hook(__FILE__, array('device_theme_switcher', 'add_defaults'));
	register_uninstall_hook(__FILE__, array('device_theme_switcher', 'remove'));
	
	//Create our plugin admin page under the 'Appearance' menu
	add_action('admin_menu', array('device_theme_switcher', 'admin_menu'));

	// ------------------------------------------------------------------------
	// EXTERNAL LIBRARIES AND CODE INITILIZATION
	// ------------------------------------------------------------------------
	//Include the MobileESP code library for acertaining device user agents
	include('mdetect.php');
	$uagent_info = new uagent_info;
	
	//Instantiate a new object of type device_theme_switcher to setup our plugin controller
	$dts = new device_theme_switcher;
	
	// ------------------------------------------------------------------------
	// DEVICE THEME SWITCHER CONTROLLER CLASS                                    
	// ------------------------------------------------------------------------
	class device_theme_switcher {
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_action('admin_init', array('device_theme_switcher', 'init') );
		// ------------------------------------------------------------------------------
		function __construct() {
			//Process saving the admin settings if the user has clicked 'Save Device Themes'
			$this->update();
			//Grab our plugin settings
			$this->handheld_theme = get_option('dts_handheld_theme');
			$this->tablet_theme = get_option('dts_tablet_theme');
			$this->installed_themes = get_themes();
			//Deliver the user's chosen theme to the device requesting the page
			$this->deliver_theme_to_device();
		}//END member function init
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_action('admin_menu', array('device_theme_switcher', 'admin_menu'));
		// ------------------------------------------------------------------------------
		function admin_menu () {
			//Create the admin menu page
			add_submenu_page('themes.php',  __('Device Theme Switcher'), __('Device Themes'), 'manage_options', 'device-themes', array('device_theme_switcher', 'generate_admin_settings_page'));
		}//END member function admin_menu
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: register_uninstall_hook(__FILE__, array('device_theme_switcher', 'remove'));
		// ------------------------------------------------------------------------------
		function remove () {
			//Remove the plugin and it's settings
			delete_option('dts_handheld_theme');
			delete_option('dts_tablet_theme');
			delete_option('dts_device');
		}//END member function remove
		
		// ------------------------------------------------------------------------------
		// CALLED MEMBER FUNCTION FOR: if ($_POST) : $dts->update; ...
		// ------------------------------------------------------------------------------
		function update () {
			if ($_POST) :
				update_option('dts_handheld_theme', $_POST['dts_handheld_theme']);
				update_option('dts_tablet_theme', $_POST['dts_tablet_theme']);
				add_action('admin_notices', array($this, 'admin_update_notice'));
			endif;
		}//END member function update
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_action('admin_notices', array($dts, 'dts_admin_notice'));
		// ------------------------------------------------------------------------------
		function admin_update_notice(){
			//Print a message to the admin window letting the user know thier settings have been saved
			echo '<div class="updated"><p>Settings saved.</p></div>';
		}//END member function admin_update_notice
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION SPECIFIED IN: add_options_page()
		// ------------------------------------------------------------------------------
		function generate_admin_settings_page() {
			//Include an external php file containing output for the admin settings page
			include('dts_admin_output.php'); 
		} //END member function generate_admin_settings_page
		
		// ------------------------------------------------------------------------
		// DEVICE READING & ALTERNATE THEME OUTPUT
		// ------------------------------------------------------------------------
		function deliver_theme_to_device () {
			//Detect if the device is a smartphone handheld
			global $uagent_info;
			
			//Check if the user has requested the full version of the website 'screen' or if they are requesting the device theme 'device'
			//By setting an option to this value we can let users browse the default theme & switch back to the device version at any time
			if ($_GET['dts_device'] == 'screen') : update_option('dts_device', 'screen'); endif;
			if ($_GET['dts_device'] == 'device') : update_option('dts_device', 'device'); endif;
			
			//Check if the user has implicitly requested the full version (default theme in 'Appearance > Themes')
			//If they have not, go ahead and display the device themes set in the plugin admin page
			if (get_option('dts_device') != "screen") :
				if ($uagent_info->DetectTierIphone()) : 
					add_filter('stylesheet', array($this, 'deliver_handheld_stylesheet'));
					add_filter('template', array($this, 'deliver_handheld_template'));
				endif ;
				//Detect if the device is a tablets
				if ($uagent_info->DetectTierTablet()) : 
					add_filter('stylesheet', array($this, 'deliver_tablet_stylesheet'));
					add_filter('template', array($this, 'deliver_tablet_template'));
				endif ;	
			endif;
		}//END member function deliver_theme_to_device
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_filter('stylesheet', array('device_theme_switcher', 'deliver_handheld_stylesheet'));
		//								 add_filter('template', array('device_theme_switcher', 'deliver_handheld_stylesheet'));
		// ------------------------------------------------------------------------------
		function deliver_handheld_stylesheet(){
			foreach ($this->installed_themes as $theme) :
				if ($theme['Name'] == $this->handheld_theme) :
					return $theme['Stylesheet'];
				endif;
			endforeach;
		} //END member function deliver_handheld_template
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_filter('stylesheet', array('device_theme_switcher', 'deliver_handheld_template'));
		//								 add_filter('template', array('device_theme_switcher', 'deliver_handheld_template'));
		// ------------------------------------------------------------------------------
		function deliver_handheld_template(){
			foreach ($this->installed_themes as $theme) :
				if ($theme['Name'] == $this->handheld_theme) :
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
		//								 add_filter('template', array('device_theme_switcher', 'deliver_tablet_stylesheet'));
		// ------------------------------------------------------------------------------
		function deliver_tablet_stylesheet(){
			foreach ($this->installed_themes as $theme) :
				if ($theme['Name'] == $this->tablet_theme) :
					return $theme['Stylesheet'];
				endif;
			endforeach;
		} //END member function deliver_tablet_template
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_filter('stylesheet', array('device_theme_switcher', 'deliver_tablet_template'));
		//								 add_filter('template', array('device_theme_switcher', 'deliver_tablet_template'));
		// ------------------------------------------------------------------------------
		function deliver_tablet_template(){
			foreach ($this->installed_themes as $theme) :
				if ($theme['Name'] == $this->tablet_theme) :
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