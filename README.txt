=== Plugin Name ===

Plugin Name: Device Theme Switcher
Author: James Mehorter jamesmehorter@gmail.com
Version: 1.0
Tested up to WordPress 3.3
Plugin URI: https://github.com/jamesmehorter/device-theme-switcher
Author URI: http://www.jamesmehorter.com

=== Plugin Description ===

This plugin allows you to set a separate theme for handheld and tablet devices
	+ You can use links to allow users to switch between your screen theme and device theme via a "View Full Website" tanchor tag. 		
	+ You can also use a link to return to the device theme via a 'Return to Mobile Website' anchor tag. 
	+ This plugin saves the users chosen theme in a PHP Session, so the user can browse around the full website prior to clicking 'Return to mobile website'. You can use theme link anywhere in your themes.

=== Plugin Installation ===

Download the latest version of the plugin from our github hosted repository (Link at the top of this file). Install the plugin like any other; download the zip, install in your WordPress admin > Plugins > Add new > 'Upload' section. Once installed and activated you'll have a new menu under Appearance titled 'Device Themes'. 

=== Plugin Use ===

Set your normal website theme as you usually do in Appearance > Themes, and set your handheld and tablet themes under Appearnace > Device Themes. Using WordPress child themes is supported.

To display a link for users to "View the full website" place the following anywhere in your handheld and tablet themes.
<?php device_theme_switcher::generate_link_to_full_website() ?>

To display a link for users to "Return to the mobile website" place the following anywhere in your default/active website theme.
<?php device_theme_switcher::generate_link_back_to_mobile() ?>