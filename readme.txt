=== Plugin Name ===
Plugin Name: Device Theme Switcher
Contributors: jamesmehorter
Tags: Theme, Switch, Change, Mobile, Mobile Theme, Handheld, Tablet, Tablet Theme, Different Themes, Device Theme
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Set a theme for handhelds and a theme for tablets

== Description ==

WordPress plugin that let's set one theme for handheld devices and another theme for tablet devices. Normal computer visitors are given the active theme set in 'Appearance > Themes'. WordPress child themes are supported. 

'Handheld' devices include Android, BlackBerry, iPod, iPhone, Windows Mobile, and other various 'hand held' smart phones. 'Tablet' devices include iPad, Android tablets, Kindle Fire and other large-screen hand helds. 'Low Support' devices include those which have poor CSS & Javascript rendering capabilities. Often these is older devices.

Visit our site http://jamesmehorter.github.io/device-theme-switcher/, or Fork the repo https://github.com/jamesmehorter/device-theme-switcher

== Screenshots ==

1. View of the Device Theme Switcher Window and options. 
2. View of the two widgets in the admin.

== Installation ==

1) Download and activate. After activation you'll have a new menu under 'Appearance' titled 'Device Themes'.

2) Set your normal website theme as you usually do in 'Appearance > Themes'. Then set your device themes under the new 'Appearnace > Device Themes' page.

== Frequently Asked Questions ==

= How do Menus and Widgets work?! =

In order to use different widgets or menus in different themes you must register the menu location and/or sidebar in *both your handheld and computer themes*. Then you must populate the different menus/widgets while the default/computer theme is active, and simply only output the sidebar/menu you want in each theme's files. That's it! You should only set your handheld theme as the active theme if you're developing/debugging and want to view the handheld theme on a computer.

= How can I test the plugin and see what other devices see? =

Your device themes can be easily accessed to 'test' and see what other devices see.

`www.mywebsite.com/?theme=handheld
www.mywebsite.com/?theme=tablet
www.mywebsite.com/?theme=low_support
www.mywebsite.com/?theme=active`

= How do I display a link in my handheld theme for users to "View Full Website"? =

This plugin creates two widgets for doing just that! Or you can use the template tags below.

`<?php
    //View Full Website
    link_to_full_website($link_text = "View Full Website", $css_classes = array(), $echo = true);

    //Return to Mobile Website
    link_back_to_device($link_text = "Return to Mobile Website", $css_classes = array(), $echo = true);`

The anchor tags that output both have a CSS class: 'dts-link'. The 'View Full Website' anchor tag also has a class of 'to-full-website' and the 'Return to the Mobile Website' link has an additional class of 'back-to-mobile'. This CSS can be used anywhere in your theme or style.css file.

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

= How can I progmatically detect the current device? =

The DTS Class contains all the current device theme switcher settings and the current user device. You can access the DTS Class anywhere in themes. This could be helpful if for instance, you want one theme to power all devices and are willing to write your own code logic with conditionals and such. 

`<?php 
    //Access the device theme switcher object
    global $dts
    
    //See what's in there..
    print_r($dts) ;

    DTS_Switcher Object
    (
        [handheld_theme] => Array
            (
                [name] => Responsive
                [template] => responsive
                [stylesheet] => responsive
            )

        [tablet_theme] => Array
            (
                [name] => Twenty Twelve
                [template] => twentytwelve
                [stylesheet] => twentytwelve
            )

        [low_support_theme] => Array
            (
                [name] => WordPress Default
                [template] => default
                [stylesheet] => default
            )

        [active_theme] => Array
            (
                [name] => Responsive
                [template] => responsive
                [stylesheet] => responsive
            )

        [device] => active (Possible values: computer, tablet, handheld, and low_support)
        [theme_override] => tablet
    )

    //use it..
    if ($dts->device == 'tablet') do_something() ;`

== Changelog ==

= Version 2.0 = 
* NEW - Made the Admin UI more presentable and WordPressy
* NEW - DTS Class access for use in themes; obtain info on the current user's device and saved dts settings.
* NEW - URL Switching - Easily see what other devices see
* FIX - Numerous code rewrites to improve overall performance, redundancy, and improve extensibility. 
* FIX - Included a pull request from Tim Broder (https://github.com/broderboy) which adds support for Varnish Device Detect (https://github.com/varnish/varnish-devicedetect). Thanks Tim!!

= Version 1.8 =
* Updated the Kindle detection for a wider range of support

= Version 1.7 =
* Updated the plugin to provide backwards compatible support for WordPress < v3.4 (Pre the new Themes API)
* Added a 3rd theme selection option for older/non-compliant devices, so theme authors can also supply a text-only version to those devices if they like. 
* Revised some language in the plugin readme file

= Version 1.6 =
* Updated the plugin to use the new Theme API within WordPress 3.4
* Updated MobileESP Library to the latest verion (April 23, 2012) which adds support for BlackBerry Curve Touch, e-Ink Kindle, and Kindle Fire in Silk mode. And fixed many other bugs. 
* Updated the Device Theme Switcher widgets so they only display to the devices they should, e.g. The 'View Full Website' widget will only display in the handheld theme. 
* Revised readme language and added a WordPress Plugin Repository banner graphic. 

= Version 1.5 =
* Modified the way themes are deliveried so the process is more stable for users with odd WordPress setups, by detecting where their theme folders are located instead of assuming wp-content/themes

= Version 1.4 =
* Updated to the latest version of the MobileESP library which now detects some newer phones like the BlackBerry Bold Touch (9900 and 9930)

= Version 1.3 =
* Changed the admin page to submit to admin_url() for those who have changed /wp-admin/ 
* Added a warning suppresor to session_start() in case another plugin has already called it
* Updated language in the WordPress readme file

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

Copyright (C) 2012 James Mehorter