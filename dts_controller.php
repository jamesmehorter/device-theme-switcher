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
	
	//Instantiate a new object of type device_theme_switcher to setup our plugin controller
	$dts = new device_theme_switcher;
	
	// ------------------------------------------------------------------------
	// DEVICE THEME SWITCHER CONTROLLER CLASS                                    
	// ------------------------------------------------------------------------
	class device_theme_switcher {
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_action('admin_init', array('device_theme_switcher', 'init') );
		// ------------------------------------------------------------------------------
		public function __construct() {
			//Process saving the admin settings if the user has clicked 'Save Device Themes'
			$this->update();
			//Grab our plugin settings
			$this->handheld_theme = get_option('dts_handheld_theme');
			$this->tablet_theme = get_option('dts_tablet_theme');
			$this->installed_themes = get_themes();
			//This value will be used to differentiate which device is requesting the websit
			$this->device = "";
			//Deliver the user's chosen theme to the device requesting the page
			$this->deliver_theme_to_device();
		}//END member function init
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_action('admin_menu', array('device_theme_switcher', 'admin_menu'));
		// ------------------------------------------------------------------------------
		public function admin_menu () {
			//Create the admin menu page
			add_submenu_page('themes.php',  __('Device Theme Switcher'), __('Device Themes'), 'manage_options', 'device-themes', array('device_theme_switcher', 'generate_admin_settings_page'));
		}//END member function admin_menu
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: register_uninstall_hook(__FILE__, array('device_theme_switcher', 'remove'));
		// ------------------------------------------------------------------------------
		public function remove () {
			//Remove the plugin and it's settings
			delete_option('dts_handheld_theme');
			delete_option('dts_tablet_theme');
			delete_option('dts_device');
		}//END member function remove
		
		// ------------------------------------------------------------------------------
		// CALLED MEMBER FUNCTION FOR: if ($_POST) : $dts->update; ...
		// ------------------------------------------------------------------------------
		public function update () {
			if (!empty($_POST)) :
				if ($_POST['dts_settings_update'] == "true") :
					update_option('dts_handheld_theme', $_POST['dts_handheld_theme']);
					update_option('dts_tablet_theme', $_POST['dts_tablet_theme']);
					add_action('admin_notices', array($this, 'admin_update_notice'));
				endif;
			endif;
		}//END member function update
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_action('admin_notices', array($dts, 'dts_admin_notice'));
		// ------------------------------------------------------------------------------
		public function admin_update_notice(){
			//Print a message to the admin window letting the user know thier settings have been saved
			echo '<div class="dts updated"><p>Settings saved.</p></div>';
		}//END member function admin_update_notice
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION SPECIFIED IN: add_options_page()
		// ------------------------------------------------------------------------------
		public function generate_admin_settings_page() {
			//Include an external php file containing output for the admin settings page
			include('dts_admin_output.php'); 
		} //END member function generate_admin_settings_page
		
		// ------------------------------------------------------------------------
		// DEVICE READING & ALTERNATE THEME OUTPUT
		// ------------------------------------------------------------------------
		public function deliver_theme_to_device () {
			//Check if user sessions have been previously enabled
			//If they have not, lets go ahead and turn sessions on so we can store the visitors template preference (screen or mobile)		
			if (!session_id()) : session_start(); endif;

			//Check if the user has requested the full version of the website 'screen' or if they are requesting the device theme 'device'
			//By setting an option to this value we can let users browse the default theme & switch back to the device version at any time
			if (!empty($_GET)) :
				if ($_GET['dts_device'] == 'screen') : $_SESSION['dts_device'] = 'screen'; endif;
				if ($_GET['dts_device'] == 'device') : $_SESSION['dts_device'] = 'device'; endif;
			endif;			
			//Detect if the device is a smartphone handheld
			$uagent_info = new uagent_info;
			
			//Check if the user has implicitly requested the full version (default theme in 'Appearance > Themes')
			//If they have not, go ahead and display the device themes set in the plugin admin page
			
			if ($_SESSION['dts_device'] != "screen") :
				if ($uagent_info->DetectTierIphone()) : 
					$this->device = $this->handheld_theme;
					add_filter('stylesheet', array($this, 'deliver_stylesheet'));
					add_filter('template', array($this, 'deliver_template'));
				endif ;
				//Detect if the device is a tablets
				if ($uagent_info->DetectTierTablet()) : 
					$this->device = $this->tablet_theme;
					add_filter('stylesheet', array($this, 'deliver_stylesheet'));
					add_filter('template', array($this, 'deliver_template'));
				endif ;	
			endif;
		}//END member function deliver_theme_to_device
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_filter('stylesheet', array('device_theme_switcher', 'deliver_handheld_stylesheet'));
		//								 add_filter('template', array('device_theme_switcher', 'deliver_handheld_stylesheet'));
		// ------------------------------------------------------------------------------
		public function deliver_stylesheet(){
			foreach ($this->installed_themes as $theme) :
				if ($theme['Name'] == $this->device) :
					return $theme['Stylesheet'];
				endif;
			endforeach;
		} //END member function deliver_stylesheet
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_filter('stylesheet', array('device_theme_switcher', 'deliver_handheld_template'));
		//								 add_filter('template', array('device_theme_switcher', 'deliver_handheld_template'));
		// ------------------------------------------------------------------------------
		public function deliver_template(){
			foreach ($this->installed_themes as $theme) :
				if ($theme['Name'] == $this->device) :
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
		} //END member function deliver_template
		
		// ------------------------------------------------------------------------
		// THEME DEVICE LINK SWITCH - For switching between mobile and screen themes
		//							  Within your theme you can call this method like so: 
		//							  device_theme_switcher::generate_link_to_full_website()
		//							  OR
		//							  device_theme_switcher::generate_link_back_to_mobile()
		// ------------------------------------------------------------------------
		public static function generate_link_to_full_website () {
			?>
	        <a href="<?php bloginfo('url') ?>?dts_device=screen" title="View Full Website" class="dts-link to-full-website">View the Full Website</a>
            <?php
		}//END member function generate_link_to_full_website

		public static function generate_link_back_to_mobile () {
			if ($_SESSION['dts_device'] == 'screen') : ?>
				<a href="<?php bloginfo('url') ?>?dts_device=device" title="View Mobile Website" class="dts-link back-to-mobile">Return to the Mobile Website</a>
			<?php endif;
		}//END member function generate_link_back_to_mobile
	} //END class definition for the device_theme_switcher
?>