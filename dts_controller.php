<?php
	/**
	 * Plugin Name: Device Theme Switcher
	 * Plugin URI:  https://github.com/jamesmehorter/device-theme-switcher/
	 * Description: Set a separate theme for handheld and tablet devices under Appearance > Device Themes
	 * Version:     2.9.1
	 * Author:      James Mehorter | jamesmehorter@gmail.com
	 * Author URI:  http://www.jamesmehorter.com
	 * License:     GPLv2+
	 * Text Domain: device-theme-switcher
	 * Domain Path: /languages
	 */

	/**
	 * Copyright (c) 2014 James Mehorter (email : jamesmehorter@gmail.com)
	 *
	 * This program is free software; you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License, version 2 or, at
	 * your discretion, any later version, as published by the Free
	 * Software Foundation.
	 *
	 * This program is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with this program; if not, write to the Free Software
	 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	 */

	// Bail if this file is being accessed directly
	defined( 'ABSPATH' ) OR exit;

	/**
     * DTS_VERSION constant for use anywhere in WordPress
     */
    define( 'DTS_VERSION', '2.9.1' );
    define( 'DTS_URL',     plugin_dir_url( __FILE__ ) );
	define( 'DTS_PATH',    dirname( __FILE__ ) . '/' );

    /**
     * Create one globally-accessible access point for the DTS_Switcher class
     *
     * @see  class-core.php initilization
     */
    global $dts ;

	/**
	 *
	 * Load the plugin core routines
	 *
	 * This is where Device Theme Switcher hooks into the WordPress (allthethings!)
	 * Everything from activation, deactivation, unintiall, init,
	 * and other WordPress hooks to build all the plugin functionality.
	 */

	// Include our reusable singleton class used throughout the plugin
	include_once( 'includes/class-singleton.php' );

	// Include the core class
	// Make available all the main plugin functionality (we won't run anything quite yet though..)
	include_once( 'includes/class-core.php' );

	// Activation: Install any initial settings or run any update routines
	register_activation_hook( __FILE__, array( 'DTS_Core', 'activate' ) );

	// Deactivation: Run any special routines on deactivation
	register_deactivation_hook( __FILE__, array( 'DTS_Core', 'deactivate' ) );

	// Uninstall: Remove anything stored in the database
	register_uninstall_hook( __FILE__, array( 'DTS_Core', 'uninstall' ) );

	// Startup
	//
	// This is where it all starts on each load..
	//
	// DTS_Core::factory() calls init() on itself,
	// wherein the main plugin functionality is included,
	// hooks into WordPress, and is executed when needed.
	add_action( 'plugins_loaded', array( 'DTS_Core', 'factory' ) );


	// EOF