=== Plugin Name ===
Plugin Name: Device Theme Switcher
Contributors: jamesmehorter
Requires at least: 3.0
Tested up to: 3.6
Stable tag: 2.0
Tags: Theme, Switch, Change, Mobile, Mobile Theme, Handheld, Tablet, iPad, iPhone, Android, Blackberry, Tablet Theme, Different Themes, Device Theme
Author URI: http://www.jamesmehorter.com/
Donate Link: http://www.jamesmehorter.com/donate/
Plugin URI: https://github.com/jamesmehorter/device-theme-switcher
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Set a theme for handhelds and a theme for tablets

== Description ==

Device Theme Switcher is a WordPress plugin which delivers one of your WordPress themes to handheld visitors and another theme to tablet visitors. Computer visitors are given the active theme in 'Appearance > Themes'.

== Screenshots ==

1. View of the Device Theme Switcher settings.
2. View of the two Device Theme Switcher Widgets and their settings.

== Installation ==

= How to Install = 

Install and activate in your WordPress 'Plugins > Add New' section by searching for 'Device Theme Switcher', or download the plugin zip (Big orange button up and to the right which says 'Download Version 2.0') and upload it manually in the 'WordPress Plugins > Add New > Upload' section. After activation you'll have a new menu: 'Appearance > Device Themes'-where you set which theme is given to which type of device.t

= URL Switching - NEW in Version 2.0! =

Your device themes can be easily accessed to 'test' and see what other devices see.

`www.mywebsite.com/?theme=handheld
www.mywebsite.com/?theme=tablet
www.mywebsite.com/?theme=low_support
www.mywebsite.com/?theme=active`

= DTS Class - NEW in Version 2.0! =

The DTS Class contains all the current device theme switcher settings and the current user device. You can access the DTS Class anywhere in themes. This could be helpful if for instance, you want one theme to power all devices and are willing to write your own code logic with conditionals and such. 

`<?php 
    //Access the device theme switcher object anywhere in your theme or plugin
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
        [device] => active (Possible values: active, handheld, tablet, and low_support)
        [theme_override] => tablet (I was given the active theme [I'm on a computer] and I used a url parameter [site.com?theme=tablet] to view the tablet theme)
    )

    //use it..
    if ($dts->device == 'tablet') do_something() ;
?>`

= Template Tags =

`//Display a link to 'View Full Website'
<?php link_to_full_website($link_text = "View Full Website", $css_classes = array(), $echo = true) ?>
//Display a link to 'Return to Mobile Website'
<?php link_back_to_device($link_text = "Return to Mobile Website", $css_classes = array(), $echo = true) ?>`

= Link Styling Example (For Tempalte Tags, Widgets, or Shortcodes) =

The anchor tags that output both have a CSS class: 'dts-link'. The 'View Full Website' anchor tag also has a class of 'to-full-website' and the 'Return to the Mobile Website' link has an additional class of 'back-to-mobile'. This CSS can be used anywhere in your theme or style.css file.

`<style type="text/css">
    .dts-link {
        font-size: 1.5em ;
    }
        .dts-link.to-full-website {
            color: red ;
        }
        .dts-link.back-to-mobile {
            color: blue ;
        }
</style>`

== Frequently Asked Questions ==

= Which devices are considered 'handheld' and which are 'tablet'? =
'Handheld' devices include Android, BlackBerry, iPod, iPhone, Windows Mobile, and other various 'hand held' smart phones. 'Tablet' devices include iPad, Android tablets, Kindle Fire and other large-screen hand helds. 'Low Support' devices include those which have poor CSS & Javascript rendering capabilities-often these are older devices.

= Are WordPress child themes supported? =
Yes!

= Help!? My theme isn't working! = 
If your theme does not work as expected on any of the devices-**the issue is with the theme, NOT with the plugin!** Follow [typical WordPress debugging practices](http://codex.wordpress.org/Debugging_in_WordPress) to find and correct the issue in your theme (Or possibly another plugin). The only theme-requirement this plugin relies on is that you have [properly-written WordPress themes](http://codex.wordpress.org/Theme_Development#Theme_Development_Standards). If your theme activates in Appearance > Themes and you can preview the theme in your computer's browser-then it is 'properly-written' as far as Device Theme Switcher is concerned.

= How do Menus and Widgets work?! =
All Device Theme Switcher really does is change which theme is delivered to the visitor based on the deivce they're using-*but WordPress still thinks the active theme is 'active' the whole time*. This means you need to have your `register_nav_menu()` or `register_widget()` functions in all your themes! See below for details and examples.

= How do I show the same menu in each theme? =
Simply place you register_nav_menu('my-menu-location', 'My Menu Location Name'); function in both of your primary/active and handheld/tablet theme functions.php files. Then, while your primary theme is 'active' go into Appearance > Menus-create your menu and assign it to the menu location-and populate it with some menu items. That's it!

= How do I show one menu in my active theme and a different menu in my handheld/tablet theme? =
Register a menu location for each theme, and place your register_nav_menus() code in each theme. E.g.:

In each theme's functions.php file:
register_nav_menus(array(
    'active-menu-location' => 'Active Theme Menu Location',
    'handheld-menu-location' => 'Handheld Theme Menu Location',
    'tablet-menu-location' => 'Tablet Theme Menu Location',
));

Then, while your primary theme is 'active' go into Appearance > Menus-create your 3 menus-assign each one to their designated menu location-and populate each with some menu items. 

Lastly we just need to display each menu in each theme:

Active theme header.php:
`wp_nav_menu(array('theme_location' => 'active-menu-location'));`

Handheld theme header.php
`wp_nav_menu(array('theme_location' => 'handheld-menu-location'));`

Tablet theme header.php
`wp_nav_menu(array('theme_location' => 'tablet-menu-location'));`

That's it-the important part is that you register each location in each theme!

= How can I display the same sidebar in each theme? =

Place the same register_sidebar() function in each theme's functions.php file, and add your widgets to your sidebar while the primary theme is 'active'.

= How can I display a different sidebar in each theme? =

Place the same 3 register_sidebar() functions in each theme's functions.php file, and add your widgets to each sidebar while the primary theme is 'active'. E.g:

In each theme's functions.php file:
`register_sidebar(array('name' => 'Active Theme Sidebar'));
register_sidebar(array('name' => 'Handheld Theme Sidebar'));
register_sidebar(array('name' => 'Tablet Theme Sidebar'));`

Then, while your primary theme is 'active' go into Appearance > Widgets-and assign some widgets to each sidebar. Lastly we just need to display each sidebar in each theme:

Active theme page.php:
`dynamic_sidebar('Active Theme Sidebar');`

Handheld theme page.php
`dynamic_sidebar('Handheld Theme Sidebar');`

Tablet theme page.php
`dynamic_sidebar('Tablet Theme Sidebar');`

== Changelog ==

= Version 2.0 - Released 08/24/2013 = 
* Complete code rewrites to improve overall performance, redundancy, and improve extensibility. 
* NEW - DTS Class access for use in themes; obtain info on the current user's device and saved dts settings. Examples in the FAQ.
* NEW - URL Switching - Easily check what other devices see. Examples in the FAQ.
* NEW - Session Timeout, so users who visit the 'Desktop' theme are bumped back to their device theme after 15 minutes
* NEW - Optional Settings section in the WordPress admin in Appearance > Device Themes Allows users to override the session timeout. Also moved the low-support theme setting to this section. This new section also a
* NEW - Help & Support section in the WordPress admin in Appearance > Device Themes
* Included the latest version of MobileESP ~ Thanks Anthony!

= Version 1.9 =
* Note: DTS Version 1.9 was not released to the public
* NEW - Made the Admin UI more presentable and WordPressy
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

= 2.0 =
Major Improvements, New Features, and even prettier!

== Credits ==

This plugin is powered by the MobileESP PHP library created by Anthony Hand (http://blog.mobileesp.com/). 

This plugin is based on the concepts provided by Jonas Vorwerk's (http://www.jonasvorwerk.com/) Mobile theme switcher plugin, and Jeremy Arntz's (http://www.jeremyarntz.com/) Mobile Theme Switcher plugin. 

Copyright (C) 2012 James Mehorter