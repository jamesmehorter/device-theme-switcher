=== Plugin Name ===

Plugin Name: Device Theme Switcher
Author: jamesmehorter
Author URI: http://www.jamesmehorter.com
Plugin URI: https://github.com/jamesmehorter/device-theme-switcher
Version: 1.0
Tags: Theme, Switch, Change, Mobile, Mobile Theme, Handheld, Tablet, Tablet Theme, Different Themes, Device Theme
Requires at least: 3.0
Tested up to: 3.3
Stable tag: 1.0

Set a theme for handhelds and a theme for tablets

== Description ==

This plugin creates a new admin page: 'Appearance > Device Themes', where you can specify an alternate theme specifically for handheld devices, and another theme for tablet devices. This plugin is powered by the MobileESP PHP library created by Anthony Hand (http://code.google.com/p/mobileesp/). It detects the User-Agent string from the request made by a user's web browser and determines if they're visiting your site with a handheld or tablet, and modifys the theme served by WordPress.

'Handheld' devices include iPod, iPhone, Android, Windows Mobile, and other various smart phones. 'Tablet' devices include everything from iPad and Android tablets to the Kindle Fire and other large-screen handhelds.

This plugin is based on the concepts provided by Jonas Vorwerk's (http://www.jonasvorwerk.com/) Mobile theme switcher plugin, and Jeremy Arntz's (http://www.jeremyarntz.com/) Mobile Theme Switcher plugin. 

Copyright (C) 2011 James Mehorter

== Screenshots ==

1. View of the Device Theme Switcher Window and options. 

== Installation ==

1) Download the latest version of the plugin from WordPress Plugins or from our github hosted repository (Link at the top of this file). Please note, the github version may contain beta code. Install the plugin like any other; download the zip, install in your WordPress admin > Plugins > Add new > 'Upload' section. Once installed and activated you'll have a new menu under Appearance titled 'Device Themes'.

2) Set your normal website theme as you usually do in Appearance > Themes, and set your handheld and tablet themes under Appearnace > Device Themes. Using WordPress child themes is supported.

== Usage ==

To display a link for users to "View the full website" place the following anywhere in your handheld and tablet themes.
`<?php device_theme_switcher::generate_link_to_full_website() ?>`

To display a link for users to "Return to the mobile website" place the following anywhere in your default/active website theme.
`<?php device_theme_switcher::generate_link_back_to_mobile() ?>`

With this plugin you can place links in your themes to allow users to switch between your screen theme and device themes via a "View Full Website" anchor tag. You can also use a link in your screen theme to return the user to the device theme via a 'Return to Mobile Website' anchor tag. This plugin saves the users chosen theme in a PHP Session, so the user can browse around your website prior to clicking 'Return to mobile website'. You can use the theme links anywhere in your themes, like in header.php or footer.php. The achor tags that output both have a class 'dts-link' so you can style it. The 'View Full Website' anchor tag also has a class of 'to-full-website' and the 'Return to the Mobile' website has an additional class of 'back-to-mobile'. E.g: 

.dts-link {
	font-size: 1.5em ;
}
	.dts-link.to-full-website {
    	color: red ;
    }
    .dts-link.to-full-website {
    	color: blue ;
    }