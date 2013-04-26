<?php
	/** 
		Plugin Name: Device Theme Switcher
		Plugin URI: https://github.com/jamesmehorter/device-theme-switcher/
		Description: This plugin allows you to set a separate theme for handheld and tablet devices under Appearance > Device Themes
		Version: 1.8
		Author: James Mehorter | jamesmehorter@gmail.com
		Author URI: http://www.jamesmehorter.com
		License: GPLV2
		License URI: http://www.gnu.org/licenses/gpl-2.0.html
		
		Copyright 2012  James mehorter  (email : jamesmehorter@gmail.com)
	
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
	
	// ------------------------------------------------------------------------
	// REGISTER HOOKS & CALLBACK FUNCTIONS:                                    
	// ------------------------------------------------------------------------
	//Set a member function of the plugin to run upon plugin activation, to set any plugin default values
	register_activation_hook(__FILE__, array('Device_Theme_Switcher', 'add_defaults'));
	//Set a member function of the plugin tp run upon plugin deactivation, to remove any plugin values that have been stored in the db
	register_uninstall_hook(__FILE__, array('Device_Theme_Switcher', 'remove'));
	//Add a notice about the selected device themes in the Dashboard Right Now widget
	add_action('activity_box_end', array('Device_Theme_Switcher', 'right_now'));
	//Create our plugin admin page under the 'Appearance' menu
	add_action('admin_menu', array('Device_Theme_Switcher', 'admin_menu'));
	//Check if we need to save any form data that was submitted
	add_action('load-appearance_page_device-themes', array('Device_Theme_Switcher', 'load'));

	//We only want to tap into the theme filters if a frontend page is being requested
	if (!is_admin()) :
		//Hook into the template output function with a filter and change the template delivered if need be
		add_filter('template', array('Device_Theme_Switcher', 'deliver_template'));
		//Hook into the stylesheet output function with a filter and change the stylesheet delivered if need be
		add_filter('stylesheet', array('Device_Theme_Switcher', 'deliver_stylesheet'));
	endif;

	//Register our widgets for displaying a 'View Full Website' and 'Return to mobile website' links
	add_action( 'widgets_init', 'dts_register_widgets' );
	function dts_register_widgets () {
		//Register the 'View Full Website' widget
		register_widget('DTS_View_Full_Website');
		//Register the 'Return to Mobile Website' widget
		register_widget('DTS_Return_To_Mobile_Website');
	}//END FUNCTION dts_register_widgets

	// ------------------------------------------------------------------------
	// DEVICE THEME SWITCHER
	// ------------------------------------------------------------------------
	//Include our external device theme switcher class library
	include_once('dts_class_switcher.php');
	
	// ------------------------------------------------------------------------
	// WIDGETS
	// ------------------------------------------------------------------------
	//Include our external widget class library
	include_once('dts_class_widgets.php');
?>