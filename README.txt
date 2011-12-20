Plugin Name: Device Theme Switcher
Description: This plugin allows you to set a separate theme for handheld and tablet devices
Author: James Mehorter @ Pica Design
Version: 1.0
Author URI: http://www.jamesmehorter.com

For theme developers / admins:
	+ You can use links allow users to switch between your screen theme and device theme via a "View Full Website" tanchor tag. 		
	+ You can also use a link to return to the device theme via a 'Return to Mobile Website' anchor tag. 
	+ This plugin saves the users chosen theme in a wp option, so the user can browse around the full website prior to clicking 'Return to mobile website'. You can use theme link anywhere in your themes.

//Place the following anywhere in your handheld and tablet themes
<?php device_theme_switcher::generate_link_to_full_website() ?>

//Place the following anywhere in your default/active website theme (Appearance > Themes)
<?php device_theme_switcher::generate_link_back_to_mobile() ?>