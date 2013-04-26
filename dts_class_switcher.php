<?php
	// ------------------------------------------------------------------------
	// DEVICE THEME SWITCHER CONTROLLER CLASS 
	//		+ This class is instantiated within itself by the deliver_template() and deliver_stylesheet() functions
	// ------------------------------------------------------------------------
	class Device_Theme_Switcher {
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_action('admin_init', array('device_theme_switcher', 'init') );
		// ------------------------------------------------------------------------------
		public function __construct() {
			//Grab our plugin settings
			$this->handheld_theme = get_option('dts_handheld_theme');
			$this->tablet_theme = get_option('dts_tablet_theme');
			$this->low_support_theme = get_option('dts_low_support_theme');
			
			//wp_get_themes was introduced in WordPress v3.4 - this check ensures we're backwards compatible
			if (function_exists('wp_get_themes')) : 
				$this->installed_themes = wp_get_themes();
			else :
				$this->installed_themes = get_themes();
			endif;

			//This value will be used to differentiate which device is requesting the website
			$this->device_theme = "";
		}//END member function init
		
		public function add_defaults() {
			//No defaults are loaded 
		}
		
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
			if (!empty($_POST)) :
				if (isset($_POST['dts_settings_update'])) :
					if ($_POST['dts_settings_update'] == "true") :
						update_option('dts_handheld_theme', $_POST['dts_handheld_theme']);
						update_option('dts_tablet_theme', $_POST['dts_tablet_theme']);
						update_option('dts_low_support_theme', $_POST['dts_low_support_theme']);
						add_action('admin_notices', array('Device_Theme_Switcher', 'admin_update_notice'));
					endif;
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
		public function detect_device_and_set_flag () {
			//Include the MobileESP code library for acertaining device user agents
			include_once('mdetect.php');
			
			//Check for Varnish Device Detect: https://github.com/varnish/varnish-devicedetect/
			if (isset($_SERVER['HTTP_X_UA_DEVICE'])) :
				if (in_array($_SERVER['HTTP_X_UA_DEVICE'], array('mobile-iphone', 'mobile-android', 'mobile-smartphone', 'mobile-generic'))) :
					$this->device_theme = $this->handheld_theme;
				elseif (in_array($_SERVER['HTTP_X_UA_DEVICE'], array('tablet-ipad', 'tablet-android'))) :
					$this->device_theme = $this->tablet_theme;
				else:
					$this->device_theme = $this->low_support_theme;
				endif;
			else :
				//Include the MobileESP code library for acertaining device user agents
				include_once('mdetect.php');
				
				//Setup the MobileESP Class
				$uagent_info = new uagent_info;
				
				//Setup the MobileESP Class
				$uagent_info = new uagent_info;
				
				//Detect if the device is a handheld
				if ($uagent_info->DetectTierIphone() || $uagent_info->DetectBlackBerry() || $uagent_info->DetectTierRichCss()) : 
					$this->device_theme = $this->handheld_theme;
				endif ;
				
				//Detect if the device is a tablets
				if ($uagent_info->DetectTierTablet() || $uagent_info->DetectKindle() || $uagent_info->DetectAmazonSilk()) : 
					$this->device_theme = $this->tablet_theme;
				endif ;	

				//Detect if the device is a tablets
				if ($uagent_info->DetectBlackBerryLow() || $uagent_info->DetectTierOtherPhones()) : 
					$this->device_theme = $this->low_support_theme;
				endif ;
			endif;
		}//END member function deliver_theme_to_device
		
		public function deliver_alternate_device_theme () {
			//Open $_SESSION for use, but only if session_start() has not been called already 
			if (!isset($_SESSION)) : @session_start() ; endif; 
			
			//Check if the user has a session yet
			//Also check if the user does not have dts_device index in their session yet
			//In either case, create a session index to store the users theme preference
			if (session_id() == "" || !isset($_SESSION['dts_device'])) :  
				$_SESSION['dts_device'] = '';
			endif;
			
			//Check if the user has requested the full version of the website 'screen' or if they are requesting the device theme 'device'
			//By setting an option to this value we can let users browse the default theme & switch back to the device version at any time
			if (isset($_GET)) :
				if (isset($_GET['dts_device'])) :
					if ($_GET['dts_device'] == 'screen') : $_SESSION['dts_device'] = 'screen'; endif;
					if ($_GET['dts_device'] == 'device') : $_SESSION['dts_device'] = 'device'; endif;
				endif;
			endif;			
			
			//Check if the user has implicitly requested the full version (default theme in 'Appearance > Themes')
			//If they have not, go ahead and display the device themes set in the plugin admin page
			if ($_SESSION['dts_device'] == '' || $_SESSION['dts_device'] == 'device') :
				return true;
			endif;
			
			//Default is false
			return false;
		}
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_filter('stylesheet', array('device_theme_switcher', 'deliver_handheld_template'));
		//								 add_filter('template', array('device_theme_switcher', 'deliver_handheld_template'));
		// ------------------------------------------------------------------------------
		public function deliver_template(){
			//Instantiate a new object of type device_theme_switcher to setup our plugin controller
			$dts = new Device_Theme_Switcher;
			
			//This is not returning anything when coming a non device!
			//Since it's a filter it must, by default, return the active theme
			if ($dts->deliver_alternate_device_theme()) :
				$dts->detect_device_and_set_flag();
				if ($dts->device_theme != "") :
					//echo $dts->device_theme;
					foreach ($dts->installed_themes as $theme) :
						if ($theme['Name'] == $dts->device_theme) :
							//For the template file name, we need to check if the theme being set is a child theme
							//If it is a child theme, then we need to grab the parent theme and pass that instead 
							
							/*

								CHECK FOR wp_get_theme() if not exists use get_the_data() 
								wp_get_theme was introduced in WordPress 3.4, this ensures we're backwards compatible
							*/
							if (function_exists('wp_get_theme')) : 
								$theme_data = wp_get_theme( $theme['Stylesheet'] );
							else : 
								$theme_data = get_theme_data( get_theme_root() . '/' . $theme['Stylesheet'] . '/style.css' );
							endif;

							if (isset($theme_data) && $theme_data['Template'] != "") :
								return $theme_data['Template'];
							else :
								return $theme['Stylesheet'];
							endif ;
						endif;
					endforeach;
				else :
					return get_option('template');
				endif;
			else :
				return get_option('template');
			endif;
		} //END member function deliver_template
		
		// ------------------------------------------------------------------------------
		// CALLBACK MEMBER FUNCTION FOR: add_filter('stylesheet', array('device_theme_switcher', 'deliver_handheld_stylesheet'));
		//								 add_filter('template', array('device_theme_switcher', 'deliver_handheld_stylesheet'));
		// ------------------------------------------------------------------------------
		public function deliver_stylesheet(){
			//Instantiate a new object of type device_theme_switcher to setup our plugin controller
			$dts = new Device_Theme_Switcher;
			
			if ($dts->deliver_alternate_device_theme()) :
				$dts->detect_device_and_set_flag();
				if ($dts->device_theme != "") :
					foreach ($dts->installed_themes as $theme) :
						if ($theme['Name'] == $dts->device_theme) :
							return $theme['Stylesheet'];
						endif;
					endforeach;
				else :
					return get_option('stylesheet'); 
				endif;
			else :
				return get_option('stylesheet');
			endif;			
		} //END member function deliver_stylesheet
		
		// ------------------------------------------------------------------------
		// THEME DEVICE LINK SWITCH - For switching between mobile and screen themes
		//							  Within your theme you can call this method like so: 
		//							  device_theme_switcher::generate_link_to_full_website()
		//							  OR
		//							  device_theme_switcher::generate_link_back_to_mobile()
		// ------------------------------------------------------------------------
		public function generate_link_to_full_website ($link_text = "View Full Website") {
			//Instantiate a new object of type device_theme_switcher to setup our plugin controller
			$dts = new Device_Theme_Switcher;
			$dts->detect_device_and_set_flag();
			$dts->deliver_alternate_device_theme();
			if ($dts->device_theme != "" && $_SESSION['dts_device'] != 'screen') :
			?>
	        <a href="<?php bloginfo('url') ?>?dts_device=screen" title="<?php echo $link_text ?>" class="dts-link to-full-website"><?php echo $link_text ?></a>
            <?php
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
		public function right_now () {
			?>
            <br />
            Handheld Theme <a href="<?php bloginfo('url') ?>/wp-admin/themes.php?page=device-themes"><strong><?php echo get_option('dts_handheld_theme') ?></strong></a> 
            <br />
            Tablet Theme <a href="<?php bloginfo('url') ?>/wp-admin/themes.php?page=device-themes"><strong><?php echo get_option('dts_tablet_theme') ?></strong></a>
            <?php
		}
	} //END class definition for the device_theme_switcher
?>
