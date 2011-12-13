Plugin Name: Device Theme Switcher
Plugin URI: http://www.picadesign.com.com
Description: This plugin allows you to set a separate theme for handheld and tablet devices
Author: James Mehorter @ Pica Design
Version: 1.0
Author URI: http://www.jamesmehorter.com

For theme developers / admins:
	+ You can use links like below to allow users to switch between your screen theme and device theme via a "View Full Website" link. This fuctionality also allows you to incorporate a link to return to the device theme you set in this plugin via a 'Return to Mobile Website' liink. 
	+ This plugin saves the users chosen theme in a wp option, so the user can browse around the full website prior to clicking 'Return to mobile website'. You can use theme link anywhere in your themes.

//Place the following anywhere in your handheld and tablet themes
<a href="<?php echo $_SERVER['PHP_SELF'] ?>?dts_device=screen" title="View Full Website">View Full Website</a>

//Place the following anywhere in your default/active website theme (Appearance > Themes)
<a href="<?php echo $_SERVER['PHP_SELF'] ?>?dts_device=device" title="View Mobile Website">Return to the Mobile Website</a>