<?php
	/** 
		Plugin Name: Device Theme Switcher
		Plugin URI: https://github.com/jamesmehorter/device-theme-switcher/
		Description: Set a separate themes for handheld and tablet devices under Appearance > Device Themes
		Version: 2.6
		Author: James Mehorter | jamesmehorter@gmail.com
		Author URI: http://www.jamesmehorter.com
		License: GPLV2
		License URI: http://www.gnu.org/licenses/gpl-2.0.html
		
		Copyright 2014  James mehorter  (email : jamesmehorter@gmail.com)
	
		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License, version 2, as 
		published by the Free Software Foundation.
	
		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.
	
		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/

	/**
     * DTS_VERSION constant for user anywhere in WordPress 
     */
    define('DTS_VERSION', 2.6);

	/**
	 *	
	 * Load the plugin core routines
	 *
	 * This is where Device Theme Switcher hooks into the WordPress
	 * activation, deactivation, unintiall, init, and plugin_action_links
	 */
	//Include the core class
	include('inc/class.core.php');
	
	//Activation: Install any initial settings
	register_activation_hook(__FILE__, array('DTS_Core', 'activate'));
	
	//Deactivation: Run any special routines on deactivation
	register_deactivation_hook(__FILE__, array('DTS_Core', 'deactivate'));
	
	//Uninstall: Remove anything stored in the database
	register_uninstall_hook(__FILE__, array('DTS_Core', 'uninstall'));	
	
	//Display a 'Settings' link with the plugin in the plugins list
    add_filter('plugin_action_links', array('DTS_Core', 'device_theme_switcher_settings_link'), 10, 2);

	//Only run the following in the admin, the REAL admin--not admin ajax
    if ( is_admin() && (!defined( 'DOING_AJAX' ) || !DOING_AJAX) ) :

		/**
		 * Load the plugin admin features
		 *
		 * The admin features include the display of the status output in the Dashboard 
		 * 'Right Now' widget. They also create an admin page at Appearance > Device Themes
		 * for the website admin to save the plugin settings 
		 */
		//include the wp-admin class
		include('inc/class.wp-admin.php');
		
		//Create our plugin admin page under the 'Appearance' menu
		add_action('admin_menu', array('DTS_Admin', 'admin_menu'));
		
		//Check if we need to save any form data that was submitted
		add_action('load-appearance_page_device-themes', array('DTS_Admin', 'load'));
       
       	/**
		 * Load the plugin update routines
		 *
		 * The update class checks the currently installed version and runs
		 * any necessary update routines. This only occurs once per version,
		 * and ONLY in the admin.
		 */
		//include the updating class
    	include('inc/class.update.php');
		add_action('admin_init', array('DTS_Update', 'do_update'));
    else : //is_admin()

    	/**
		 * Load the plugin theme switching functionality
		 *
		 * The theme switching utilizes the MobileESP library to detect
		 * the browser User Agent and determine if it's a 'handheld' or 'tablet'.
		 * This plugin then taps into the WordPress template and stylesheet hooks 
		 * to deliver the alternately set themes in Appearance > Device Themes
		 */
    	//We only want to tap into the theme filters if a frontend page or an ajax request is being requested
    	
		//Include our external device theme switcher class library
		include('inc/class.switcher.php');
		
		//Load support for legacy GET variables
		include('inc/legacy/legacy_get_support.php');
		
		//Instantiate a new instance of this class
		//This is the single class instance that is accessible via 'global $dts;'
		$dts = new DTS_Switcher ;
		
		//Hook into the template output function with a filter and change the template delivered if need be
		add_filter('template', array($dts, 'deliver_template'), 10, 0);
		
		//Hook into the stylesheet output function with a filter and change the stylesheet delivered if need be
		add_filter('stylesheet', array($dts, 'deliver_stylesheet'), 10, 0);
		
		//Include the template tags developers can access in their themes
		include('inc/inc.template-tags.php');
		
		//Load support for legacy classes, methods, functions, and variables
		include('inc/legacy/legacy_structural_support.php');
	endif;//is_admin()

	/**
	 * Load in the plugin widgets
	 *
	 * The widgets create an option for capable users to place 'View Full Website'
	 * and 'Return to Mobile Website' links in their theme sidebars.
	 */
	//Load the widget class 
	include('inc/class.widgets.php');
	
	//Register our widgets for displaying a 'View Full Website' and 'Return to mobile website' links
	function dts_register_widgets () {
		//Register the 'View Full Website' widget
		register_widget('DTS_View_Full_Website');
		
		//Register the 'Return to Mobile Website' widget
		register_widget('DTS_Return_To_Mobile_Website');
	}//END FUNCTION dts_register_widgets
	add_action( 'widgets_init', 'dts_register_widgets' );

	/**
	 * Load the plugin shortcodes
	 *
	 * The shortcodes allow capable users to place 'View Full Website' and
	 * 'Return to Mobile Website' links in their posts / pages. Register the 
	 * [device-theme-switcher] shortcode and Include our external shortcodes class library
	 */
	//load the shortodes class
	include('inc/class.shortcodes.php');

	//Ex: [link_to_full_website link_text="View Full Website" css_classes="blue-text, alignleft"]
	//This shortcode outputs an HTML <a> link for the user to 'View Full Website' or to 'Return to Mobile Website'
	add_shortcode( 'link_to_full_website', array('DTS_Shortcode', 'link_to_full_website_shortcode') );
	
	//Ex: [link_back_to_device link_text="Return to Mobile Website" css_classes="blue-text, alignleft"]
	add_shortcode( 'link_back_to_device', array('DTS_Shortcode', 'link_back_to_device_shortcode') );
