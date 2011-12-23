=== Plugin Name ===

Plugin Name: Device Theme Switcher
Author: James Mehorter jamesmehorter@gmail.com
Version: 1.0
Tags: Template, Theme, Switch, Change, Mobile, Handheld, Tablet, Different Themes
Requires at least: 3.0
Tested up to: 3.3
Stable tag: 1.0

This plugin allows you to set a separate theme for handheld and tablet devices

== Description ==

This plugin allows you to set a separate theme for handheld and tablet devices
	+ You can use links to allow users to switch between your screen theme and device theme via a "View Full Website" tanchor tag. 		
	+ You can also use a link to return to the device theme via a 'Return to Mobile Website' anchor tag. 
	+ This plugin saves the users chosen theme in a PHP Session, so the user can browse around the full website prior to clicking 'Return to mobile website'. You can use theme link anywhere in your themes.

Based on the concepts provided by Jonas Vorwerk's (http://www.jonasvorwerk.com/) Mobile heme switcher plugin, and Jeremy Arntz's (http://www.jeremyarntz.com/) Mobile Theme Switcher plugin. 

This plugin uses the MobileESP code library created by Anthony Hand. http://code.google.com/p/mobileesp/

Copyright (C) 2011 James Mehorter

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Library General Public
License as published by the Free Software Foundation; either
version 2 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Library General Public License for more details.

You should have received a copy of the GNU Library General Public
License along with this library; if not, write to the
Free Software Foundation, Inc., 51 Franklin St, Fifth Floor,
Boston, MA  02110-1301, USA.

== Installation ==

1) Download the latest version of the plugin from WordPress Plugins or from our github hosted repository (Link at the top of this file). Please note, the github version may contain beta code. Install the plugin like any other; download the zip, install in your WordPress admin > Plugins > Add new > 'Upload' section. Once installed and activated you'll have a new menu under Appearance titled 'Device Themes'.

2) Set your normal website theme as you usually do in Appearance > Themes, and set your handheld and tablet themes under Appearnace > Device Themes. Using WordPress child themes is supported.

3) To display a link for users to "View the full website" place the following anywhere in your handheld and tablet themes.
`<?php device_theme_switcher::generate_link_to_full_website() ?>`

To display a link for users to "Return to the mobile website" place the following anywhere in your default/active website theme.
`<?php device_theme_switcher::generate_link_back_to_mobile() ?>`

== Screenshots ==

1. View of the Device Theme Switcher Window and options. 