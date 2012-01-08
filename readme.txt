=== Plugin Name ===
Plugin Name: Device Theme Switcher
Contributors: jamesmehorter
Donate Link: http://www.jamesmehorter.com/donate/
Tags: Theme, Switch, Change, Mobile, Mobile Theme, Handheld, Tablet, Tablet Theme, Different Themes, Device Theme
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: 1.2

Set a theme for handhelds and a theme for tablets

== Description ==

Device Theme Switcher creates a new page in your WordPress Admin; 'Appearance > Device Themes', where you can set one theme for handheld devices, and another theme for tablet devices. Normal computer visitors are given the active theme set in 'Appearance > Themes'.

Device Theme Switcher is especially helpful if you're a theme developer and want to provide a consitant, but different look on these popular device sizes. WordPress child themes are supported, so you can easily create 3 versions of your theme, with just a few overrides. For instance, you may want your page-home.php to show a large slideshow to normal computer visitors, to pull a smaller set of thumbnail images for handheld visitors, and also load a touch-friendly jQuery script for tablet users to navigate your image gallery - so you're not just stuck with @media queries.

'Handheld' devices include iPod, iPhone, Android, Windows Mobile, and other various 'hand held' smart phones. 'Tablet' devices include everything from iPad and Android tablets to the Kindle Fire and other large-screen hand helds.

== Screenshots ==

1. View of the Device Theme Switcher Window and options. 

== Installation ==

1) Download the latest version of the plugin from WordPress Plugins or from our github hosted repository (https://github.com/jamesmehorter/device-theme-switcher). Please note, the github version may contain beta code. Install the plugin like any other; download the zip, install in your WordPress admin > Plugins > Add new > 'Upload' section. Once installed and activated you'll have a new menu under Appearance titled 'Device Themes'.

2) Set your normal website theme as you usually do in 'Appearance > Themes'. Then set your handheld and tablet themes under the new 'Appearnace > Device Themes' page. Using WordPress child themes is supported.

== Frequently Asked Questions ==

= How do I display a link in my handheld theme for users to "View Full Website"? =

This plugin creates two widgets for doing just that! Or you can use the template tags below.

To display a link for users to "View the full website" place the following anywhere in your handheld and tablet themes.
`<?php if (class_exists('Device_Theme_Switcher')) : Device_Theme_Switcher::generate_link_to_full_website(); endif; ?>`

To display a link for users to "Return to the mobile website" place the following anywhere in your default/active website theme.
`<?php if (class_exists('Device_Theme_Switcher')) : Device_Theme_Switcher::generate_link_back_to_mobile(); endif; ?>`

The users chosen theme is stored in a PHP Session, so the user can browse around your website prior to clicking 'Return to mobile website'. You can use the theme links anywhere in your themes, like in header.php or footer.php. 

= How do I style the widget or template tag output?? =

Device Theme Switcher really just echo's an html anchor tag. Both links have a CSS class of 'dts-link'. The 'View Full Website' anchor tag also has a class of 'to-full-website' and the 'Return to the Mobile Website' link has an additional class of 'back-to-mobile' so you can style the link differently if you want

*Styling Example*
`.dts-link {
    font-size: 1.5em ;
}
    .dts-link.to-full-website {
        color: red ;
    }
    .dts-link.back-to-mobile {
    	color: blue ;
    }`

== Changelog ==

= Version 1.2 =
* Added the handheld and tablet theme choices to the WordPress Dashboard Right Now panel
* Update both GitHub and WordPress readme files to be better styled and versed
* Added two wigets for users to put in their themes
* Coding and efficiency improvments

= Version 1.1 =
* Bug fixes
* Efficency improvements
    
= Version 1.0 =
* First Public Stable Release
    
== Upgrade Notice ==

Auto Updating this plugin is OK. Your saved settings for this plugin will never be changed when updating. You can even deactivate / reactivate this plugin without ever losing those settings. Deleteing the plugin correctly (from within the Admin > Plugins page) is the only way to remove the plugin settings. 

== Credits ==

This plugin is powered by the MobileESP PHP library created by Anthony Hand (http://code.google.com/p/mobileesp/). 

This plugin is based on the concepts provided by Jonas Vorwerk's (http://www.jonasvorwerk.com/) Mobile theme switcher plugin, and Jeremy Arntz's (http://www.jeremyarntz.com/) Mobile Theme Switcher plugin. 

Copyright (C) 2011 James Mehorter