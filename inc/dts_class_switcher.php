<?php
	// ------------------------------------------------------------------------
	// DEVICE THEME SWITCHER CONTROLLER CLASS 
	//		+ This class is instantiated within itself by the deliver_template() and deliver_stylesheet() functions
	// ------------------------------------------------------------------------
	class DTS {
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_action('admin_init', array('device_theme_switcher', 'init') );
		// ------------------------------------------------------------------------------
		public function __construct() {
			//Update the global variable used in themes
			$this->device = 'computer' ;
			
			//Retrieve any DTS theme options which were previously saved
		    //The theme option is a url encoded string containing 3 values for name, template, and stylesheet
		    parse_str(get_option('dts_handheld_theme'), $this->handheld_theme);
		    parse_str(get_option('dts_tablet_theme'), $this->tablet_theme);
		    parse_str(get_option('dts_low_support_theme'), $this->low_support_theme);

			//Store the current theme
			$this->default_template = get_option('template');
			$this->default_stylesheet = get_option('stylesheet');

			//This value will be used to differentiate which device is requesting the website
			$this->device_theme = array();
		}//END member function init
		
		public function add_defaults() { //No defaults are loaded 
		}//ENDadd_defaults
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_action('admin_menu', array('device_theme_switcher', 'admin_menu'));
		// ------------------------------------------------------------------------------
		public function admin_menu () {
			//Create the admin menu page
			add_submenu_page('themes.php',  __('Device Theme Switcher'), __('Device Themes'), 'manage_options', 'device-themes', array('Device_Theme_Switcher', 'generate_admin_settings_page'));
		}//END member function admin_menu
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: register_uninstall_hook(__FILE__, array('device_theme_switcher', 'remove'));
		// ------------------------------------------------------------------------------
		public function remove () {
			//Remove the plugin and it's settings
			delete_option('dts_handheld_theme');
			delete_option('dts_tablet_theme');
			delete_option('dts_low_support_theme');
			delete_option('dts_device');
		}//END member function remove
		
		// ------------------------------------------------------------------------------
		// CALLED MEMBER FUNCTION FOR: if ($_POST) : $dts->update; ...
		// ------------------------------------------------------------------------------
		public function load () {
			//Unfortunetly we can't use the settings api on a subpage, so we need to check for and update any options this plugin uses
			if ($_POST) : if ($_POST['dts_settings_update'] == "true") :
				//Loop through the 3 device <select>ed <option>s in the admin form
				foreach ($_POST['dts'] as $selected_device => $chosen_theme) : 
					//Update each of the 3 dts database options with a urlencoded array of the selected theme 
					//The array contains 3 values: name, template, and stylesheet - these are all we need for use later on
					update_option($selected_device, $chosen_theme);
				endforeach ; 
				//Display an admin notice letting the user know the save was successfull
				add_action('admin_notices', array('Device_Theme_Switcher', 'admin_update_notice'));
			endif; endif;
		}//END member function update
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_action('admin_notices', array($dts, 'dts_admin_notice'));
		// ------------------------------------------------------------------------------
		public function admin_update_notice(){
			//Print a message to the admin window letting the user know thier settings have been saved
			//The CSS used to style this message is located in dts_admin_output.php
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
		// DEVICE READING & THEME ASSIGNMENT
		// ------------------------------------------------------------------------
		public function detect_device_and_assign_theme () {
			//Use Varnish Device Detect: https://github.com/varnish/varnish-devicedetect/
			//Thanks to Tim Broder for this addition! https://github.com/broderboy | http://timbroder.com/
			if (isset($_SERVER['HTTP_X_UA_DEVICE'])) :
				if (in_array($_SERVER['HTTP_X_UA_DEVICE'], array('mobile-iphone', 'mobile-android', 'mobile-smartphone', 'mobile-generic'))) :
					$this->device_theme = $this->handheld_theme;
				elseif (in_array($_SERVER['HTTP_X_UA_DEVICE'], array('tablet-ipad', 'tablet-android'))) :
					$this->device_theme = $this->tablet_theme;
				else:
					$this->device_theme = $this->low_support_theme;
				endif;

			//Use MobileESP
			else :
				//Include the MobileESP code library for acertaining device user agents
				include_once('mdetect.php');
				
				//Setup the MobileESP Class
				$uagent_info = new uagent_info;
				
				//Detect if the device is a handheld
				if ($uagent_info->DetectTierIphone() || $uagent_info->DetectBlackBerry() || $uagent_info->DetectTierRichCss()) : 
					//Set constant for access in themes and other plugins
					//The @ error/warning suppression sucks here.. but this hook is called multiple times and produces errors
					$this->device = 'handheld' ;
					//Tell DTS to use the handheld theme
					$this->device_theme = $this->handheld_theme;
				endif ;
				
				//Detect if the device is a tablet
				if ($uagent_info->DetectTierTablet() || $uagent_info->DetectKindle() || $uagent_info->DetectAmazonSilk()) : 
					//Set constant for access in themes and other plugins
					//The @ error/warning suppression sucks here.. but this hook is called multiple times and produces errors
					$this->device = 'tablet' ;
					//Tell DTS to use the tablet theme
					$this->device_theme = $this->tablet_theme;
				endif ;	

				//Detect if the device is a low support device (poor css and javascript rendering / older devices)
				if ($uagent_info->DetectBlackBerryLow() || $uagent_info->DetectTierOtherPhones()) : 
					//Set constant for access in themes and other plugins
					//The @ error/warning suppression sucks here.. but this hook is called multiple times and produces errors
					$this->device = 'low_support' ;
					//Tell DTS to use the low support theme
					$this->device_theme = $this->low_support_theme;
				endif ;
			endif;
		}//END member function deliver_theme_to_device
		
		// ------------------------------------------------------------------------
		// SESSION MANAGEMENT - This section allows users to click 'view full website' and be able to browse the active-theme website
		// ------------------------------------------------------------------------
		public function has_not_requested_delivery_of_default_theme () {
			//Open $_SESSION for use, but only if session_start() has not been called already
			//NOTE: WordPress is inherently stateless. DTS only stores a small string in SESSION,
			//though if COOKIE's are disabled, at least SESSION will persistantly append the session id on URLS.
			//Using wp_session_manager to creae psuedo sessions with WP transients works but lacks the persistant urls
			//This is the best scenario I've come up with.
			if (!isset($_SESSION['dts_device'])) @session_start() ;
			
			//Check if the user has a session yet
			//Also check if the user does not have dts_device index in their session yet
			//In either case, create a session index to store the users theme preference (handheld users who chose to view the full site)
			if (session_id() == "" || !isset($_SESSION['dts_device'])) $_SESSION['dts_device'] = '' ;
			
			//Check if the user has requested the full version of the website 'screen' or if they are requesting the device theme 'device'
			//By setting an option to this value we can let users browse the default theme & switch back to the device version at any time
			if (isset($_GET)) : if (isset($_GET['dts_device'])) :
				if ($_GET['dts_device'] == 'screen') $_SESSION['dts_device'] = 'screen' ; 
				if ($_GET['dts_device'] == 'device') $_SESSION['dts_device'] = 'device' ;
			endif; endif;			
			
			//Check if the user has implicitly requested the full version (default theme in 'Appearance > Themes')
			//If they have not, go ahead and display the device themes set in the plugin admin page
			if ($_SESSION['dts_device'] == '' || $_SESSION['dts_device'] == 'device') return true ; 

			//Default is false unless otherwise (true returned above) 
			//the user has not implicitly requested the active theme
			return false;
		}//ENDhas_not_requested_delivery_of_default_theme

		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_filter('stylesheet', array('device_theme_switcher', 'deliver_handheld_template'));
		//								 add_filter('template', array('device_theme_switcher', 'deliver_handheld_template'));
		// ------------------------------------------------------------------------------
		public function deliver_template(){
			//return the template file (pointing to one of 4 possible themes [handheld, tablet, low support, default/active])
			return DTS::which_theme('template') ;
		} //END member function deliver_template
		

		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_filter('stylesheet', array('device_theme_switcher', 'deliver_handheld_stylesheet'));
		//								 add_filter('template', array('device_theme_switcher', 'deliver_handheld_stylesheet'));
		// ------------------------------------------------------------------------------
		public function deliver_stylesheet(){
			//return the template file (pointing to one of 4 possible themes [handheld, tablet, low support, default/active])
			return DTS::which_theme('stylesheet') ;
		} //END member function deliver_stylesheet
		

		// ------------------------------------------------------------------------
		// THEME OUTPUT
		// ------------------------------------------------------------------------
		//$theme_file should be either 'template' or 'stylesheet'
		public function which_theme ($theme_file) {
			//Instantiate a new object of type device_theme_switcher to setup our plugin controller
			$dts = new DTS;
			
			//Only change the theme if the user did not implicitly requested the full/active theme
			if ($dts->has_not_requested_delivery_of_default_theme()) : 
				//Determine which device device/theme to deliver
				$dts->detect_device_and_assign_theme();

				//Check if the user is a mobile user - if so return the theme set by the admin for their device type
				if (!empty($dts->device_theme)) : return $dts->device_theme[$theme_file]; //Deliver the device theme set via the

				//This is the option all computer users are given - just the default active theme
				else : return $dts->default_template_or_stylesheet($theme_file); endif;
			else :
				//The user is using a mobile (handheld or tablet) and has requested the default/active theme
				return $dts->default_template_or_stylesheet($theme_file);
			endif;
		}//ENDwhich_theme
			//which_theme helper function to reduce redundany 
			//$theme_file should be either 'template' or 'stylesheet'
			public function default_template_or_stylesheet ($theme_file) {
				$this->device = 'computer' ;

				switch ($theme_file) :
					case 'template' : return $this->default_template; break;
					case 'stylesheet' : return $this->default_stylesheet; break;
				endswitch;
			}//ENDdefault_template_or_stylesheet

		// ------------------------------------------------------------------------
		// THEME DEVICE LINK SWITCH - For switching between mobile and screen themes
		//							  Within your theme you can call this method like so: 
		//							  device_theme_switcher::generate_link_to_full_website()
		//							  OR
		//							  device_theme_switcher::generate_link_back_to_mobile()
		// ------------------------------------------------------------------------
		public function generate_link_to_full_website ($link_text = "View Full Website") {
			//Instantiate a new object of type device_theme_switcher to setup our plugin controller
			$dts = new DTS;
			$dts->detect_device_and_assign_theme();
			if (!isset($_SESSION['dts_device'])) @session_start() ;
			if (!empty($dts->device_theme) && $_SESSION['dts_device'] != 'screen') : ?>
	        	<a href="<?php bloginfo('url') ?>?dts_device=screen" title="<?php echo $link_text ?>" class="dts-link to-full-website"><?php echo $link_text ?></a><?php
			endif;
		}//END member function generate_link_to_full_website
		
		//Generate a link back to the mobile website
		public static function generate_link_back_to_mobile ($link_text = "Return to Mobile Website") {
			if ($_SESSION['dts_device'] == 'screen') : ?>
				<a href="<?php bloginfo('url') ?>?dts_device=device" title="<?php echo $link_text ?>" class="dts-link back-to-mobile"><?php echo $link_text ?></a>
			<?php endif;
		}//END member function generate_link_back_to_mobile
		
		//Display some output in the WP Admin Dashboard 'Right Now' section
		//		+ Show what device these have been selected below what default theme is active
		public function right_now () { ?>
            <br />Handheld Theme <a href="<?php bloginfo('url') ?>/wp-admin/themes.php?page=device-themes"><strong><?php echo get_option('dts_handheld_theme') ?></strong></a> 
            <br />Tablet Theme <a href="<?php bloginfo('url') ?>/wp-admin/themes.php?page=device-themes"><strong><?php echo get_option('dts_tablet_theme') ?></strong></a><?php
		}//END right_now
	} //END DTS Class

	//We renamed the primary class to DTS in version 1.9
	//This line can be deleted once everyone is on 1.9 :)
	//It was used in documentation for users inserting the html links wrapped with if(class_exists(Device_Theme_Switcher))
	class Device_Theme_Switcher Extends DTS {}
?>
